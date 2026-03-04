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
LARAVEL_URL="${LARAVEL_URL:-http://localhost:8000}"
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
# Phase 3.5: Install Kadence + activate child theme
# ──────────────────────────────────────────────
echo "[3.5/8] Setting up Kadence child theme..."

# Sync child theme from Docker image to volume
docker compose -f "$PROJECT_DIR/docker-compose.yml" exec -T wordpress bash -c '
    if [ -d /usr/src/wordpress/wp-content/themes/kadence-praia-do-norte ]; then
        cp -rf /usr/src/wordpress/wp-content/themes/kadence-praia-do-norte /var/www/html/wp-content/themes/
        chown -R www-data:www-data /var/www/html/wp-content/themes/kadence-praia-do-norte
        echo "  Child theme synced to volume."
    else
        echo "  WARNING: Child theme not found in image."
    fi
'

# Install Kadence parent theme
if wp theme is-installed kadence 2>/dev/null; then
    echo "  Kadence parent: already installed."
else
    wp theme install kadence || echo "  WARNING: Kadence install failed."
fi

# Activate child theme
wp theme activate kadence-praia-do-norte 2>/dev/null || echo "  WARNING: Child theme activation failed."
echo "  Kadence child theme activated."

# Install Kadence Blocks plugin
if wp plugin is-installed kadence-blocks 2>/dev/null; then
    wp plugin activate kadence-blocks 2>/dev/null || true
    echo "  Kadence Blocks: already installed."
else
    wp plugin install kadence-blocks --activate 2>&1 || echo "  WARNING: Kadence Blocks install failed."
fi

# Apply Kadence Customizer settings
echo "  Applying Customizer settings..."
wp eval '
$palette = array(
    "palette" => array(
        array("color" => "#0066cc", "slug" => "palette1", "name" => "Ocean Primary"),
        array("color" => "#003566", "slug" => "palette2", "name" => "Ocean Deep"),
        array("color" => "#001d3d", "slug" => "palette3", "name" => "Ocean Abyss"),
        array("color" => "#0077b6", "slug" => "palette4", "name" => "Ocean Mid"),
        array("color" => "#00b4d8", "slug" => "palette5", "name" => "Ocean Surface"),
        array("color" => "#f4e4c1", "slug" => "palette6", "name" => "Sand"),
        array("color" => "#f8f9fa", "slug" => "palette7", "name" => "Off White"),
        array("color" => "#343a40", "slug" => "palette8", "name" => "Dark Gray"),
        array("color" => "#ffffff", "slug" => "palette9", "name" => "White"),
    ),
    "active" => "palette"
);
set_theme_mod("kadence_color_palette", json_encode($palette));
set_theme_mod("heading_font", array("family" => "Montserrat", "google" => true, "weight" => "700", "variant" => "700"));
set_theme_mod("base_font", array("family" => "Inter", "google" => true, "weight" => "400", "variant" => "regular", "size" => array("desktop" => "16")));
set_theme_mod("header_main_background", array("desktop" => array("color" => "#001d3d")));
set_theme_mod("header_top_height", array("desktop" => 0, "tablet" => 0, "mobile" => 0));
set_theme_mod("footer_bottom_background", array("desktop" => array("color" => "#001d3d")));
set_theme_mod("footer_html_content", "{copyright} {year} Praia do Norte — Loja Oficial. Todos os direitos reservados.");
echo "  Customizer settings applied.";
'

# Upload logo
echo "  Setting up logo..."
wp eval '
$logo_id = get_theme_mod("custom_logo");
if ($logo_id && wp_get_attachment_url($logo_id)) {
    echo "  Logo already set, skipping.";
} else {
    $logo_src = "/var/www/html/wp-content/themes/kadence-praia-do-norte/assets/images/logo-white.png";
    if (!file_exists($logo_src)) { echo "  WARNING: Logo not found."; return; }
    $upload_dir = wp_upload_dir();
    $dest = $upload_dir["path"] . "/logo-praia-do-norte.png";
    copy($logo_src, $dest);
    $attach_id = wp_insert_attachment(array("post_title" => "Praia do Norte Logo", "post_mime_type" => "image/png", "post_status" => "inherit"), $dest);
    if (!is_wp_error($attach_id)) {
        require_once ABSPATH . "wp-admin/includes/image.php";
        $attach_data = wp_generate_attachment_metadata($attach_id, $dest);
        wp_update_attachment_metadata($attach_id, $attach_data);
        set_theme_mod("custom_logo", $attach_id);
        echo "  Logo uploaded (ID: " . $attach_id . ").";
    }
}
'

# Portuguese language
wp language core install pt_PT 2>/dev/null || true
wp site switch-language pt_PT 2>/dev/null || wp option update WPLANG pt_PT

# Install WooCommerce + Kadence Portuguese language packs
wp language plugin install woocommerce pt_PT 2>/dev/null || true
wp language theme install kadence pt_PT 2>/dev/null || true

# Rename WooCommerce pages
wp eval '
$pages = array("shop" => "Loja", "cart" => "Carrinho", "checkout" => "Finalizar Compra", "myaccount" => "Minha Conta");
foreach ($pages as $key => $title) {
    $pid = get_option("woocommerce_" . $key . "_page_id");
    if ($pid) { wp_update_post(array("ID" => $pid, "post_title" => $title, "post_name" => sanitize_title($title))); }
}
echo "  WooCommerce pages renamed to Portuguese.";
'

echo "  Kadence theme setup complete."

# Set shop page as homepage
echo "  Setting shop as homepage..."
SHOP_PAGE_ID=$(wp option get woocommerce_shop_page_id 2>/dev/null)
if [ -n "$SHOP_PAGE_ID" ] && [ "$SHOP_PAGE_ID" != "0" ]; then
    wp option update show_on_front page
    wp option update page_on_front "$SHOP_PAGE_ID"
    echo "  Shop page set as homepage (ID: $SHOP_PAGE_ID)."
fi

# Delete demo content
echo "  Cleaning up demo content..."
HELLO_ID=$(wp post list --post_type=post --name=hello-world --field=ID 2>/dev/null)
if [ -n "$HELLO_ID" ]; then
    wp post delete "$HELLO_ID" --force
    echo "  Deleted 'Hello world!' post."
fi

SAMPLE_ID=$(wp post list --post_type=page --name=sample-page --field=ID 2>/dev/null)
if [ -n "$SAMPLE_ID" ]; then
    wp post delete "$SAMPLE_ID" --force
    echo "  Deleted 'Sample Page'."
fi

# Create clean navigation menu
echo "  Creating navigation menu..."
wp menu create "Primary" 2>/dev/null || true
wp menu item add-custom "Primary" "Loja" "$WP_URL/loja/" 2>/dev/null || true
wp menu location assign "Primary" "primary" 2>/dev/null || true
echo "  Nav menu set with 'Loja' only."

# Set Laravel URL for custom header/footer navigation
wp option update pn_laravel_url "$LARAVEL_URL"
echo "  Laravel URL set to: $LARAVEL_URL"

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
wp option update woocommerce_weight_unit "kg"
wp option update woocommerce_dimension_unit "cm"
wp option update woocommerce_enable_shipping_calc "yes"
wp option update woocommerce_shipping_cost_requires_address "yes"
wp option update woocommerce_ship_to_destination "billing"
wp option update blogname "$WP_SITE_TITLE"
wp option update blogdescription "Loja oficial Praia do Norte Nazare"

# Pretty permalinks (required for REST API)
wp rewrite structure '/%postname%/'
wp rewrite flush

echo "  WooCommerce configured (EUR, Portugal, pretty permalinks)."

# ──────────────────────────────────────────────
# Phase 4.5: Shipping configuration
# ──────────────────────────────────────────────
echo "[4.5/8] Configuring shipping..."

# Sync plugin from image to volume (same pattern as child theme)
docker compose -f "$PROJECT_DIR/docker-compose.yml" exec -T wordpress bash -c '
    if [ -d /usr/src/wordpress/wp-content/plugins/pn-table-rate-shipping ]; then
        cp -rf /usr/src/wordpress/wp-content/plugins/pn-table-rate-shipping /var/www/html/wp-content/plugins/
        chown -R www-data:www-data /var/www/html/wp-content/plugins/pn-table-rate-shipping
        echo "  Shipping plugin synced to volume."
    else
        echo "  WARNING: Shipping plugin not found in image."
    fi
'

# Activate plugin
wp plugin activate pn-table-rate-shipping 2>/dev/null || true

# Create shipping classes (idempotent)
wp eval '
$classes = [
    ["name" => "Roupa",     "slug" => "roupa",     "description" => "Artigos de vestuário"],
    ["name" => "Sem envio", "slug" => "sem-envio",  "description" => "Apenas recolha local"],
];
foreach ($classes as $c) {
    if (!get_term_by("slug", $c["slug"], "product_shipping_class")) {
        $result = wp_insert_term($c["name"], "product_shipping_class", [
            "slug"        => $c["slug"],
            "description" => $c["description"],
        ]);
        if (!is_wp_error($result)) {
            echo "  Created shipping class: " . $c["name"] . "\n";
        } else {
            echo "  ERROR creating class " . $c["name"] . ": " . $result->get_error_message() . "\n";
        }
    } else {
        echo "  Shipping class already exists: " . $c["name"] . "\n";
    }
}
'

# Create shipping zone "Portugal" with methods (idempotent)
wp eval '
// Check if zone already exists
$zones = WC_Shipping_Zones::get_zones();
$portugal_zone = null;
foreach ($zones as $z) {
    if ($z["zone_name"] === "Portugal") {
        $portugal_zone = new WC_Shipping_Zone($z["id"]);
        break;
    }
}

if (!$portugal_zone) {
    $portugal_zone = new WC_Shipping_Zone();
    $portugal_zone->set_zone_name("Portugal");
    $portugal_zone->save();
    $portugal_zone->add_location("PT", "country");
    echo "  Created shipping zone: Portugal\n";
} else {
    echo "  Shipping zone already exists: Portugal\n";
}

// Add methods if not present
$methods = $portugal_zone->get_shipping_methods();
$has_table_rate = false;
$has_local_pickup = false;
foreach ($methods as $m) {
    if ($m->id === "pn_table_rate") $has_table_rate = true;
    if ($m->id === "local_pickup") $has_local_pickup = true;
}

if (!$has_table_rate) {
    $instance_id = $portugal_zone->add_shipping_method("pn_table_rate");
    echo "  Added method: PN Table Rate (instance $instance_id)\n";
    // Set title via option
    update_option("woocommerce_pn_table_rate_" . $instance_id . "_settings", [
        "title"      => "Envio para Portugal",
        "tax_status" => "none",
    ]);
} else {
    echo "  Method already exists: PN Table Rate\n";
}

if (!$has_local_pickup) {
    $instance_id = $portugal_zone->add_shipping_method("local_pickup");
    echo "  Added method: Local Pickup (instance $instance_id)\n";
    update_option("woocommerce_local_pickup_" . $instance_id . "_settings", [
        "title"      => "Recolha no Forte de S. Miguel Arcanjo",
        "tax_status" => "none",
        "cost"       => "",
    ]);
} else {
    echo "  Method already exists: Local Pickup\n";
}

// Clear shipping cache
delete_transient("wc_shipping_method_count_legacy");
WC_Cache_Helper::get_transient_version("shipping", true);
echo "  Shipping cache cleared.\n";
'

echo "  Shipping configured."

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
