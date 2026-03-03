#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

# Load .env
if [ ! -f "$PROJECT_DIR/.env" ]; then
    echo "Creating .env from .env.example..."
    cp "$PROJECT_DIR/.env.example" "$PROJECT_DIR/.env"
fi
set -a
source "$PROJECT_DIR/.env"
set +a

# Defaults
WP_PORT="${WP_PORT:-8080}"
WP_ADMIN_USER="${WP_ADMIN_USER:-admin}"
WP_ADMIN_PASSWORD="${WP_ADMIN_PASSWORD:-admin123}"
WP_ADMIN_EMAIL="${WP_ADMIN_EMAIL:-admin@praiadonorte.local}"
WP_SITE_TITLE="${WP_SITE_TITLE:-Praia do Norte - Loja}"
WP_URL="${WP_URL:-http://localhost:$WP_PORT}"
LARAVEL_ENV_PATH="${LARAVEL_ENV_PATH:-../backend/.env}"

# Resolve Laravel .env path
LARAVEL_ENV="$(cd "$PROJECT_DIR" && realpath "$LARAVEL_ENV_PATH" 2>/dev/null || echo "$PROJECT_DIR/$LARAVEL_ENV_PATH")"

# Helper: run wp-cli inside the wordpress container
wp() {
    docker compose -f "$PROJECT_DIR/docker-compose.yml" exec -T wordpress wp --allow-root --path=/var/www/html "$@"
}

echo "============================================"
echo "  Praia do Norte — WooCommerce Setup"
echo "============================================"
echo ""

# ──────────────────────────────────────────────
# Phase 1: Start containers
# ──────────────────────────────────────────────
echo "[1/8] Starting Docker containers..."
docker compose -f "$PROJECT_DIR/docker-compose.yml" up -d --build

# ──────────────────────────────────────────────
# Phase 2: Wait for WordPress + install core
# ──────────────────────────────────────────────
echo "[2/8] Waiting for WordPress to be ready..."
MAX_WAIT=120
ELAPSED=0
until docker compose -f "$PROJECT_DIR/docker-compose.yml" exec -T wordpress test -f /var/www/html/wp-includes/version.php 2>/dev/null; do
    sleep 2
    ELAPSED=$((ELAPSED + 2))
    if [ "$ELAPSED" -ge "$MAX_WAIT" ]; then
        echo "ERROR: WordPress files not ready after ${MAX_WAIT}s"
        exit 1
    fi
done
echo "  WordPress files ready (${ELAPSED}s)"

# Wait a bit more for DB connection to stabilize
sleep 3

if wp core is-installed 2>/dev/null; then
    echo "  WordPress already installed, skipping core install."
else
    echo "  Installing WordPress core..."
    wp core install \
        --url="$WP_URL" \
        --title="$WP_SITE_TITLE" \
        --admin_user="$WP_ADMIN_USER" \
        --admin_password="$WP_ADMIN_PASSWORD" \
        --admin_email="$WP_ADMIN_EMAIL" \
        --skip-email
    echo "  WordPress installed."
fi

# ──────────────────────────────────────────────
# Phase 3: Install & activate WooCommerce
# ──────────────────────────────────────────────
echo "[3/8] Installing WooCommerce..."
if wp plugin is-installed woocommerce 2>/dev/null; then
    echo "  WooCommerce already installed."
    wp plugin activate woocommerce 2>/dev/null || true
else
    wp plugin install woocommerce --activate
fi
echo "  WooCommerce active."

# ──────────────────────────────────────────────
# Phase 4: Configure WooCommerce settings
# ──────────────────────────────────────────────
echo "[4/8] Configuring WooCommerce..."
wp option update woocommerce_currency EUR
wp option update woocommerce_currency_pos left_space
wp option update woocommerce_price_decimal_sep ","
wp option update woocommerce_price_thousand_sep "."
wp option update woocommerce_price_num_decimals 2
wp option update woocommerce_default_country "PT"
wp option update woocommerce_calc_taxes "no"
wp option update blogname "$WP_SITE_TITLE"
wp option update blogdescription "Loja oficial Praia do Norte Nazare"

# Pretty permalinks (required for REST API)
wp rewrite structure '/%postname%/'
wp rewrite flush

echo "  WooCommerce configured (EUR, Portugal, pretty permalinks)."

# ──────────────────────────────────────────────
# Phase 5: Generate REST API credentials
# ──────────────────────────────────────────────
echo "[5/8] Generating REST API credentials..."

# Use WordPress Application Passwords (works reliably over HTTP with WP_ENVIRONMENT_TYPE=local)
# Delete existing application password for "Laravel" if present (idempotent re-run)
wp eval '
$user = get_user_by("login", "admin");
$passwords = WP_Application_Passwords::get_user_application_passwords($user->ID);
foreach ($passwords as $pw) {
    if ($pw["name"] === "Laravel") {
        WP_Application_Passwords::delete_application_password($user->ID, $pw["uuid"]);
    }
}
'

# Create new application password
APP_PASSWORD=$(wp eval '
$user = get_user_by("login", "admin");
$result = WP_Application_Passwords::create_new_application_password(
    $user->ID,
    array("name" => "Laravel", "app_id" => "")
);
if (is_wp_error($result)) {
    echo "ERROR:" . $result->get_error_message();
} else {
    echo $result[0];
}
')

if [[ "$APP_PASSWORD" == ERROR:* ]]; then
    echo "  ERROR: $APP_PASSWORD"
    exit 1
fi

# Format: WooCommerce REST API uses Basic Auth with admin:application_password
# The consumer_key = admin username, consumer_secret = application password
CONSUMER_KEY="$WP_ADMIN_USER"
CONSUMER_SECRET="$APP_PASSWORD"

echo "  Application password generated for user '$WP_ADMIN_USER'."

# ──────────────────────────────────────────────
# Phase 6: Seed products
# ──────────────────────────────────────────────
echo "[6/8] Seeding products..."
bash "$SCRIPT_DIR/seed-products.sh"

# ──────────────────────────────────────────────
# Phase 7: Update Laravel .env
# ──────────────────────────────────────────────
echo "[7/8] Updating Laravel .env..."

if [ ! -f "$LARAVEL_ENV" ]; then
    echo "  WARNING: Laravel .env not found at $LARAVEL_ENV — skipping."
else
    # Function to set a var in .env (replace if exists, append if not)
    set_env_var() {
        local key="$1"
        local value="$2"
        local file="$3"

        if grep -q "^${key}=" "$file" 2>/dev/null; then
            # macOS sed requires '' after -i
            sed -i '' "s|^${key}=.*|${key}=${value}|" "$file"
        elif grep -q "^#.*${key}=" "$file" 2>/dev/null; then
            sed -i '' "s|^#.*${key}=.*|${key}=${value}|" "$file"
        else
            echo "${key}=${value}" >> "$file"
        fi
    }

    set_env_var "WOOCOMMERCE_URL" "$WP_URL" "$LARAVEL_ENV"
    set_env_var "WOOCOMMERCE_CONSUMER_KEY" "$CONSUMER_KEY" "$LARAVEL_ENV"
    set_env_var "WOOCOMMERCE_CONSUMER_SECRET" "$CONSUMER_SECRET" "$LARAVEL_ENV"

    echo "  Laravel .env updated with WooCommerce credentials."
fi

# ──────────────────────────────────────────────
# Phase 8: Verify
# ──────────────────────────────────────────────
echo "[8/8] Verifying REST API..."

# Use Basic Auth (username:application_password)
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" -u "${CONSUMER_KEY}:${CONSUMER_SECRET}" "${WP_URL}/wp-json/wc/v3/products?per_page=1" 2>/dev/null || echo "000")

if [ "$HTTP_CODE" = "200" ]; then
    PRODUCT_COUNT=$(curl -s -u "${CONSUMER_KEY}:${CONSUMER_SECRET}" "${WP_URL}/wp-json/wc/v3/products?per_page=100" | python3 -c "import sys,json; print(len(json.load(sys.stdin)))" 2>/dev/null || echo "?")
    echo "  REST API responding (HTTP $HTTP_CODE), products found: $PRODUCT_COUNT"
else
    echo "  WARNING: REST API returned HTTP $HTTP_CODE (may need a moment to initialize)"
fi

echo ""
echo "============================================"
echo "  Setup Complete!"
echo "============================================"
echo ""
echo "  WordPress Admin:  $WP_URL/wp-admin"
echo "    Username: $WP_ADMIN_USER"
echo "    Password: $WP_ADMIN_PASSWORD"
echo ""
echo "  WooCommerce REST API:  $WP_URL/wp-json/wc/v3/"
echo "    Auth: Basic Auth (user: $CONSUMER_KEY)"
echo ""
echo "  Laravel .env:     $LARAVEL_ENV"
echo ""
echo "  Next steps:"
echo "    cd ../backend && php artisan serve"
echo "    Visit: http://localhost:8000/pt/loja"
echo ""
