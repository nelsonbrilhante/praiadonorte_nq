#!/bin/bash
set -euo pipefail

# ──────────────────────────────────────────────
# Phase 1: Copy WordPress files from image to volume
# ──────────────────────────────────────────────
# The official entrypoint only copies files when $1='apache2-foreground',
# so we handle it ourselves to run setup before starting Apache.
if [ ! -f /var/www/html/wp-includes/version.php ]; then
    echo "[wp-entrypoint] Phase 1: Copying WordPress core files..."
    tar cf - --one-file-system -C /usr/src/wordpress . | tar xf - -C /var/www/html --no-overwrite-dir
    chown -R www-data:www-data /var/www/html
    echo "[wp-entrypoint] WordPress files copied."
else
    echo "[wp-entrypoint] Phase 1: WordPress files already present."
fi

# ──────────────────────────────────────────────
# Phase 2: Generate wp-config.php
# ──────────────────────────────────────────────
if [ ! -f /var/www/html/wp-config.php ]; then
    echo "[wp-entrypoint] Phase 2: Creating wp-config.php..."
    wp config create \
        --dbname="${WORDPRESS_DB_NAME:-wordpress}" \
        --dbuser="${WORDPRESS_DB_USER:-root}" \
        --dbpass="${WORDPRESS_DB_PASSWORD}" \
        --dbhost="${WORDPRESS_DB_HOST:-db}" \
        --allow-root \
        --path=/var/www/html \
        --skip-check \
        --force

    # Inject WORDPRESS_CONFIG_EXTRA (HTTPS proxy detection, memory limit, etc.)
    if [ -n "${WORDPRESS_CONFIG_EXTRA:-}" ]; then
        php -r '
            $config = file_get_contents("/var/www/html/wp-config.php");
            $extra = getenv("WORDPRESS_CONFIG_EXTRA");
            // Fix Coolify escaping backslash before ! (e.g. \!== becomes !==)
            $extra = str_replace("\\!", "!", $extra);
            $marker = "require_once ABSPATH . \x27wp-settings.php\x27;";
            $config = str_replace($marker, $extra . PHP_EOL . $marker, $config);
            file_put_contents("/var/www/html/wp-config.php", $config);
        '
        echo "[wp-entrypoint] Extra PHP config injected."
    fi
    echo "[wp-entrypoint] wp-config.php created."
else
    echo "[wp-entrypoint] Phase 2: wp-config.php already exists."
fi

# ──────────────────────────────────────────────
# Phase 3: Wait for database
# ──────────────────────────────────────────────
echo "[wp-entrypoint] Phase 3: Waiting for database..."
DB_WAIT=0
until php -r "new mysqli('${WORDPRESS_DB_HOST:-db}', '${WORDPRESS_DB_USER:-root}', getenv('WORDPRESS_DB_PASSWORD'), '${WORDPRESS_DB_NAME:-wordpress}');" 2>/dev/null; do
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
        if wp plugin install "$plugin" --activate --allow-root --path=/var/www/html 2>&1; then
            echo "[wp-entrypoint]   $plugin: installed and activated."
        else
            echo "[wp-entrypoint]   WARNING: $plugin failed to install (will retry on next restart)."
        fi
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
exec apache2-foreground
