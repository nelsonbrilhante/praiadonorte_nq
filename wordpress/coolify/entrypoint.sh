#!/bin/bash
set -euo pipefail

# ──────────────────────────────────────────────
# Sentinel: Protect production data on redeploys
# ──────────────────────────────────────────────
SETUP_MARKER="/var/www/html/.setup-complete"
IS_FIRST_RUN=true
if [ -f "$SETUP_MARKER" ] && [ "${FORCE_SETUP:-false}" != "true" ]; then
    IS_FIRST_RUN=false
    echo "[wp-entrypoint] Existing setup detected. REDEPLOY mode — skipping destructive phases."
else
    echo "[wp-entrypoint] FIRST-TIME SETUP (or FORCE_SETUP=true)."
fi

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
# Phase 1.5: Sync child theme to live directory
# ──────────────────────────────────────────────
echo "[wp-entrypoint] Phase 1.5: Syncing child theme..."
if [ -d /usr/src/wordpress/wp-content/themes/kadence-praia-do-norte ]; then
    cp -rf /usr/src/wordpress/wp-content/themes/kadence-praia-do-norte /var/www/html/wp-content/themes/
    chown -R www-data:www-data /var/www/html/wp-content/themes/kadence-praia-do-norte
    echo "[wp-entrypoint] Child theme synced."
else
    echo "[wp-entrypoint] WARNING: Child theme not found in image, skipping."
fi

# ──────────────────────────────────────────────
# Phase 1.6: Sync shipping plugin to live directory
# ──────────────────────────────────────────────
echo "[wp-entrypoint] Phase 1.6: Syncing shipping plugin..."
if [ -d /usr/src/wordpress/wp-content/plugins/pn-table-rate-shipping ]; then
    cp -rf /usr/src/wordpress/wp-content/plugins/pn-table-rate-shipping /var/www/html/wp-content/plugins/
    chown -R www-data:www-data /var/www/html/wp-content/plugins/pn-table-rate-shipping
    echo "[wp-entrypoint] Shipping plugin synced."
else
    echo "[wp-entrypoint] WARNING: Shipping plugin not found in image, skipping."
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
    "easypay-gateway-checkout-wc"
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

# Activate local shipping plugin (not from WP.org)
wp plugin activate pn-table-rate-shipping --allow-root --path=/var/www/html 2>/dev/null || true
echo "[wp-entrypoint]   pn-table-rate-shipping: activated."

# ──────────────────────────────────────────────
# Phase 5.5: Install Kadence parent + activate child theme
# ──────────────────────────────────────────────
echo "[wp-entrypoint] Phase 5.5: Setting up Kadence theme..."

# Install Kadence parent theme (idempotent)
if wp theme is-installed kadence --allow-root --path=/var/www/html 2>/dev/null; then
    echo "[wp-entrypoint]   Kadence parent: already installed."
else
    wp theme install kadence --allow-root --path=/var/www/html || echo "[wp-entrypoint]   WARNING: Kadence install failed (will retry next restart)."
fi

# Activate child theme
if [ -d /var/www/html/wp-content/themes/kadence-praia-do-norte ]; then
    wp theme activate kadence-praia-do-norte --allow-root --path=/var/www/html
    echo "[wp-entrypoint]   Child theme activated."
else
    echo "[wp-entrypoint]   WARNING: Child theme directory not found, activating parent."
    wp theme activate kadence --allow-root --path=/var/www/html
fi

# Install Kadence Blocks plugin
if wp plugin is-installed kadence-blocks --allow-root --path=/var/www/html 2>/dev/null; then
    wp plugin activate kadence-blocks --allow-root --path=/var/www/html 2>/dev/null || true
    echo "[wp-entrypoint]   Kadence Blocks: already installed."
else
    wp plugin install kadence-blocks --activate --allow-root --path=/var/www/html 2>&1 || echo "[wp-entrypoint]   WARNING: Kadence Blocks install failed."
fi

# ──────────────────────────────────────────────
# Phase 5.6: Apply Kadence Customizer settings
# ──────────────────────────────────────────────
if $IS_FIRST_RUN; then
echo "[wp-entrypoint] Phase 5.6: Applying Kadence Customizer settings..."

wp eval '
// Global color palette — ocean brand
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

// Typography — headings
set_theme_mod("heading_font", array(
    "family"    => "Montserrat",
    "google"    => true,
    "weight"    => "700",
    "variant"   => "700",
    "transform" => "inherit",
));

// Typography — body
set_theme_mod("base_font", array(
    "family"  => "Inter",
    "google"  => true,
    "weight"  => "400",
    "variant" => "regular",
    "size"    => array("desktop" => "16"),
));

// Header main row background
set_theme_mod("header_main_background", array(
    "desktop" => array("color" => "#001d3d"),
));

// Disable top header row (announcement bar)
set_theme_mod("header_top_height", array("desktop" => 0, "tablet" => 0, "mobile" => 0));

// Footer bottom row background
set_theme_mod("footer_bottom_background", array(
    "desktop" => array("color" => "#001d3d"),
));

// Footer bottom HTML (copyright)
$year = date("Y");
set_theme_mod("footer_html_content", "{copyright} {year} Praia do Norte — Loja Oficial. Todos os direitos reservados.");

echo "Customizer settings applied.";
' --allow-root --path=/var/www/html

echo "[wp-entrypoint] Customizer configured."
else
    echo "[wp-entrypoint] Phase 5.6: Skipped (REDEPLOY mode)."
fi

# ──────────────────────────────────────────────
# Phase 5.7: Upload logo from child theme
# ──────────────────────────────────────────────
echo "[wp-entrypoint] Phase 5.7: Setting up logo..."

wp eval '
$logo_id = get_theme_mod("custom_logo");
if ($logo_id && wp_get_attachment_url($logo_id)) {
    echo "Logo already set (ID: " . $logo_id . "), skipping.";
} else {
    $logo_src = "/var/www/html/wp-content/themes/kadence-praia-do-norte/assets/images/logo-white.png";
    if (!file_exists($logo_src)) {
        echo "WARNING: Logo file not found at " . $logo_src;
        return;
    }

    $upload_dir = wp_upload_dir();
    $dest = $upload_dir["path"] . "/logo-praia-do-norte.png";

    // Copy logo to uploads
    copy($logo_src, $dest);

    // Create attachment
    $attachment = array(
        "post_title"     => "Praia do Norte Logo",
        "post_mime_type" => "image/png",
        "post_status"    => "inherit",
    );

    $attach_id = wp_insert_attachment($attachment, $dest);

    if (!is_wp_error($attach_id)) {
        require_once ABSPATH . "wp-admin/includes/image.php";
        $attach_data = wp_generate_attachment_metadata($attach_id, $dest);
        wp_update_attachment_metadata($attach_id, $attach_data);
        set_theme_mod("custom_logo", $attach_id);
        echo "Logo uploaded and set (ID: " . $attach_id . ").";
    } else {
        echo "WARNING: Failed to create attachment.";
    }
}
' --allow-root --path=/var/www/html

echo "[wp-entrypoint] Logo setup complete."

# ──────────────────────────────────────────────
# Phase 6: Configure WooCommerce
# ──────────────────────────────────────────────
if $IS_FIRST_RUN; then
echo "[wp-entrypoint] Phase 6: Configuring WooCommerce..."
wp option update woocommerce_currency EUR --allow-root --path=/var/www/html
wp option update woocommerce_currency_pos left_space --allow-root --path=/var/www/html
wp option update woocommerce_price_decimal_sep "," --allow-root --path=/var/www/html
wp option update woocommerce_price_thousand_sep "." --allow-root --path=/var/www/html
wp option update woocommerce_price_num_decimals 2 --allow-root --path=/var/www/html
wp option update woocommerce_default_country "PT" --allow-root --path=/var/www/html
wp option update woocommerce_calc_taxes "no" --allow-root --path=/var/www/html
wp option update woocommerce_weight_unit "kg" --allow-root --path=/var/www/html
wp option update woocommerce_dimension_unit "cm" --allow-root --path=/var/www/html
wp option update woocommerce_enable_shipping_calc "yes" --allow-root --path=/var/www/html
wp option update woocommerce_shipping_cost_requires_address "yes" --allow-root --path=/var/www/html
wp option update woocommerce_ship_to_destination "billing" --allow-root --path=/var/www/html
wp option update blogname "${WP_SITE_TITLE:-Praia do Norte - Loja}" --allow-root --path=/var/www/html
wp option update blogdescription "Loja oficial Praia do Norte Nazare" --allow-root --path=/var/www/html
echo "[wp-entrypoint] WooCommerce configured (EUR, Portugal)."

# Enable easypay payment gateway
wp option update woocommerce_easypay_checkout_settings '{"enabled":"yes","title":"easypay Checkout","description":"Pay with MB Way, Ref. Multibanco, Visa \u0026 Mastercard Cards, Apple Pay, Santander Consumer Finance"}' --format=json --allow-root --path=/var/www/html
echo "[wp-entrypoint] Easypay payment gateway enabled."
else
    echo "[wp-entrypoint] Phase 6: Skipped WooCommerce options (REDEPLOY mode)."
fi

# Pretty permalinks — always run (idempotent, required for REST API)
wp rewrite structure '/%postname%/' --allow-root --path=/var/www/html
wp rewrite flush --allow-root --path=/var/www/html
echo "[wp-entrypoint] Permalinks configured."

# Laravel URL — always run (may change between deploys)
wp option update pn_laravel_url "${LARAVEL_URL:-http://localhost:8000}" --allow-root --path=/var/www/html
echo "[wp-entrypoint] Laravel URL set to: ${LARAVEL_URL:-http://localhost:8000}"

# ──────────────────────────────────────────────
# Phase 6.4: Shipping configuration
# ──────────────────────────────────────────────
echo "[wp-entrypoint] Phase 6.4: Configuring shipping..."

wp eval '
// Create shipping classes (idempotent)
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
        }
    } else {
        echo "  Shipping class exists: " . $c["name"] . "\n";
    }
}

// Create shipping zone "Portugal" with methods (idempotent)
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
    echo "  Shipping zone exists: Portugal\n";
}

$methods = $portugal_zone->get_shipping_methods();
$has_table_rate = false;
$has_local_pickup = false;
foreach ($methods as $m) {
    if ($m->id === "pn_table_rate") $has_table_rate = true;
    if ($m->id === "local_pickup") $has_local_pickup = true;
}

if (!$has_table_rate) {
    $instance_id = $portugal_zone->add_shipping_method("pn_table_rate");
    update_option("woocommerce_pn_table_rate_" . $instance_id . "_settings", [
        "title"      => "Envio para Portugal",
        "tax_status" => "none",
    ]);
    echo "  Added method: PN Table Rate\n";
} else {
    echo "  Method exists: PN Table Rate\n";
}

if (!$has_local_pickup) {
    $instance_id = $portugal_zone->add_shipping_method("local_pickup");
    update_option("woocommerce_local_pickup_" . $instance_id . "_settings", [
        "title"      => "Recolha no Forte de S. Miguel Arcanjo",
        "tax_status" => "none",
        "cost"       => "",
    ]);
    echo "  Added method: Local Pickup\n";
} else {
    echo "  Method exists: Local Pickup\n";
}

delete_transient("wc_shipping_method_count_legacy");
WC_Cache_Helper::get_transient_version("shipping", true);
echo "  Shipping cache cleared.\n";
' --allow-root --path=/var/www/html

echo "[wp-entrypoint] Shipping configured."

# ──────────────────────────────────────────────
# Phase 6.5: Portuguese language + page titles
# ──────────────────────────────────────────────
echo "[wp-entrypoint] Phase 6.5: Setting Portuguese language..."

# Install and activate Portuguese locale — always run (idempotent)
wp language core install pt_PT --allow-root --path=/var/www/html 2>/dev/null || true
wp site switch-language pt_PT --allow-root --path=/var/www/html 2>/dev/null || \
    wp option update WPLANG pt_PT --allow-root --path=/var/www/html

# Install WooCommerce + Kadence Portuguese language packs — always run (idempotent)
wp language plugin install woocommerce pt_PT --allow-root --path=/var/www/html 2>/dev/null || true
wp language theme install kadence pt_PT --allow-root --path=/var/www/html 2>/dev/null || true

if $IS_FIRST_RUN; then
# Rename WooCommerce pages to Portuguese
wp eval '
$pages = array(
    "shop"      => "Loja",
    "cart"      => "Carrinho",
    "checkout"  => "Finalizar Compra",
    "myaccount" => "Minha Conta",
);

foreach ($pages as $option_key => $title) {
    $page_id = get_option("woocommerce_" . $option_key . "_page_id");
    if ($page_id) {
        wp_update_post(array(
            "ID"         => $page_id,
            "post_title" => $title,
            "post_name"  => sanitize_title($title),
        ));
        echo "  Page \"" . $option_key . "\" renamed to \"" . $title . "\" (ID: " . $page_id . ")\n";
    }
}
' --allow-root --path=/var/www/html

# Set shop page as the homepage
SHOP_PAGE_ID=$(wp option get woocommerce_shop_page_id --allow-root --path=/var/www/html 2>/dev/null)
if [ -n "$SHOP_PAGE_ID" ] && [ "$SHOP_PAGE_ID" != "0" ]; then
    wp option update show_on_front page --allow-root --path=/var/www/html
    wp option update page_on_front "$SHOP_PAGE_ID" --allow-root --path=/var/www/html
    echo "[wp-entrypoint] Shop page set as homepage (ID: $SHOP_PAGE_ID)."
fi
else
    echo "[wp-entrypoint] Phase 6.5: Skipped page renaming/homepage (REDEPLOY mode)."
fi

echo "[wp-entrypoint] Portuguese language configured."

# ──────────────────────────────────────────────
# Phase 6.6: Clean up demo content + navigation
# ──────────────────────────────────────────────
if $IS_FIRST_RUN; then
echo "[wp-entrypoint] Phase 6.6: Cleaning up demo content..."

# Delete "Hello world!" post
HELLO_ID=$(wp post list --post_type=post --name=hello-world --field=ID --allow-root --path=/var/www/html 2>/dev/null)
if [ -n "$HELLO_ID" ]; then
    wp post delete "$HELLO_ID" --force --allow-root --path=/var/www/html
    echo "[wp-entrypoint]   Deleted 'Hello world!' post."
fi

# Delete "Sample Page"
SAMPLE_ID=$(wp post list --post_type=page --name=sample-page --field=ID --allow-root --path=/var/www/html 2>/dev/null)
if [ -n "$SAMPLE_ID" ]; then
    wp post delete "$SAMPLE_ID" --force --allow-root --path=/var/www/html
    echo "[wp-entrypoint]   Deleted 'Sample Page'."
fi

# Create clean navigation menu (idempotent)
if ! wp menu list --format=ids --allow-root --path=/var/www/html 2>/dev/null | grep -q .; then
    wp menu create "Primary" --allow-root --path=/var/www/html
    echo "[wp-entrypoint]   Created 'Primary' menu."
fi
SHOP_URL=$(wp option get siteurl --allow-root --path=/var/www/html 2>/dev/null)/loja/
wp menu item add-custom "Primary" "Loja" "$SHOP_URL" --allow-root --path=/var/www/html 2>/dev/null || true
wp menu location assign "Primary" "primary" --allow-root --path=/var/www/html 2>/dev/null || true

echo "[wp-entrypoint] Demo content cleaned, nav menu set."
else
    echo "[wp-entrypoint] Phase 6.6: Skipped demo cleanup (REDEPLOY mode)."
fi

# ──────────────────────────────────────────────
# Write sentinel file after successful setup
# ──────────────────────────────────────────────
if $IS_FIRST_RUN; then
    echo "Setup completed at $(date -u '+%Y-%m-%d %H:%M:%S UTC')" > "$SETUP_MARKER"
    echo "[wp-entrypoint] Sentinel file written. Future restarts will skip destructive phases."
fi

# ──────────────────────────────────────────────
# Phase 7: Start Apache
# ──────────────────────────────────────────────
echo "[wp-entrypoint] Setup complete. Starting Apache..."
exec apache2-foreground
