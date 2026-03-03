#!/bin/bash
set -euo pipefail

# ──────────────────────────────────────────────
# Phase 1: Run original WordPress entrypoint
# ──────────────────────────────────────────────
# The official entrypoint creates wp-config.php from WORDPRESS_DB_* env vars,
# then execs into the CMD. We call it with a no-op command to get the config
# setup, then handle Apache ourselves.
echo "[wp-entrypoint] Phase 1: Running original WordPress entrypoint..."
docker-entrypoint.sh true

# ──────────────────────────────────────────────
# Phase 2: Wait for WordPress files
# ──────────────────────────────────────────────
echo "[wp-entrypoint] Phase 2: Waiting for WordPress files..."
MAX_WAIT=120
ELAPSED=0
until [ -f /var/www/html/wp-includes/version.php ]; do
    sleep 2
    ELAPSED=$((ELAPSED + 2))
    if [ "$ELAPSED" -ge "$MAX_WAIT" ]; then
        echo "[wp-entrypoint] ERROR: WordPress files not ready after ${MAX_WAIT}s"
        exit 1
    fi
done
echo "[wp-entrypoint] WordPress files ready (${ELAPSED}s)"

# ──────────────────────────────────────────────
# Phase 3: Wait for database
# ──────────────────────────────────────────────
echo "[wp-entrypoint] Phase 3: Waiting for database..."
DB_WAIT=0
until wp db check --allow-root --path=/var/www/html >/dev/null 2>&1; do
    sleep 2
    DB_WAIT=$((DB_WAIT + 2))
    if [ "$DB_WAIT" -ge 60 ]; then
        echo "[wp-entrypoint] ERROR: Database not ready after 60s"
        exit 1
    fi
done
echo "[wp-entrypoint] Database ready (${DB_WAIT}s)"

# ──────────────────────────────────────────────
# Phase 4: Install WordPress core (if needed)
# ──────────────────────────────────────────────
if wp core is-installed --allow-root --path=/var/www/html 2>/dev/null; then
    echo "[wp-entrypoint] Phase 4: WordPress already installed, skipping."
else
    echo "[wp-entrypoint] Phase 4: Installing WordPress core..."
    wp core install \
        --url="${WP_SITE_URL}" \
        --title="${WP_SITE_TITLE:-Praia do Norte - Loja}" \
        --admin_user="${WP_ADMIN_USER:-admin}" \
        --admin_password="${WP_ADMIN_PASSWORD}" \
        --admin_email="${WP_ADMIN_EMAIL:-admin@praiadonorte.pt}" \
        --skip-email \
        --allow-root \
        --path=/var/www/html
    echo "[wp-entrypoint] WordPress installed."
fi

# ──────────────────────────────────────────────
# Phase 5: Install & activate plugins
# ──────────────────────────────────────────────
echo "[wp-entrypoint] Phase 5: Installing plugins..."

PLUGINS=(
    "woocommerce"
    "wordpress-seo"
    "wordfence"
    "updraftplus"
    "wp-super-cache"
    "wp-mail-smtp"
    "contact-form-7"
)

for plugin in "${PLUGINS[@]}"; do
    if wp plugin is-installed "$plugin" --allow-root --path=/var/www/html 2>/dev/null; then
        wp plugin activate "$plugin" --allow-root --path=/var/www/html 2>/dev/null || true
        echo "[wp-entrypoint]   $plugin: already installed, activated."
    else
        wp plugin install "$plugin" --activate --allow-root --path=/var/www/html
        echo "[wp-entrypoint]   $plugin: installed and activated."
    fi
done

# ──────────────────────────────────────────────
# Phase 6: Configure WooCommerce
# ──────────────────────────────────────────────
echo "[wp-entrypoint] Phase 6: Configuring WooCommerce..."
wp option update woocommerce_currency EUR --allow-root --path=/var/www/html
wp option update woocommerce_currency_pos left_space --allow-root --path=/var/www/html
wp option update woocommerce_price_decimal_sep "," --allow-root --path=/var/www/html
wp option update woocommerce_price_thousand_sep "." --allow-root --path=/var/www/html
wp option update woocommerce_price_num_decimals 2 --allow-root --path=/var/www/html
wp option update woocommerce_default_country "PT" --allow-root --path=/var/www/html
wp option update woocommerce_calc_taxes "no" --allow-root --path=/var/www/html
wp option update blogname "${WP_SITE_TITLE:-Praia do Norte - Loja}" --allow-root --path=/var/www/html
wp option update blogdescription "Loja oficial Praia do Norte Nazare" --allow-root --path=/var/www/html

# Pretty permalinks (required for REST API)
wp rewrite structure '/%postname%/' --allow-root --path=/var/www/html
wp rewrite flush --allow-root --path=/var/www/html

echo "[wp-entrypoint] WooCommerce configured (EUR, Portugal, pretty permalinks)."

# ──────────────────────────────────────────────
# Phase 7: Start Apache
# ──────────────────────────────────────────────
echo "[wp-entrypoint] Setup complete. Starting Apache..."
exec "$@"
