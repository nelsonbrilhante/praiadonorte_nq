#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

# Helper: run wp-cli inside the wordpress container
wp() {
    docker compose -f "$PROJECT_DIR/docker-compose.yml" exec -T wordpress wp --allow-root --path=/var/www/html "$@"
}

echo "  Seeding categories and products..."

wp eval '
// ─── Media helpers ───
require_once ABSPATH . "wp-admin/includes/media.php";
require_once ABSPATH . "wp-admin/includes/file.php";
require_once ABSPATH . "wp-admin/includes/image.php";

/**
 * Download an image URL and attach it to a post as featured image.
 * Works around media_sideload_image failing on URLs without file extensions
 * (e.g. Unsplash URLs with query params).
 */
function pn_attach_image($url, $post_id, $title) {
    $tmp = download_url($url);
    if (is_wp_error($tmp)) return $tmp;

    $file_array = [
        "name"     => sanitize_title($title) . ".jpg",
        "tmp_name" => $tmp,
    ];

    $attachment_id = media_handle_sideload($file_array, $post_id, $title);
    if (is_wp_error($attachment_id)) {
        @unlink($tmp);
        return $attachment_id;
    }

    return $attachment_id;
}

// ─── Categories ───
$categories = ["Vestuario", "Acessorios", "Equipamento"];
$cat_ids = [];

foreach ($categories as $name) {
    $slug = sanitize_title($name);
    $term = get_term_by("slug", $slug, "product_cat");
    if ($term) {
        $cat_ids[$name] = $term->term_id;
    } else {
        $result = wp_insert_term($name, "product_cat", ["slug" => $slug]);
        if (!is_wp_error($result)) {
            $cat_ids[$name] = $result["term_id"];
        }
    }
}

echo "  Categories: " . implode(", ", array_keys($cat_ids)) . "\n";

// ─── Products ───
$products = [
    [
        "name"          => "T-Shirt Praia do Norte - Onda Gigante",
        "sku"           => "PN-TS-001",
        "regular_price" => "29.90",
        "sale_price"    => "",
        "category"      => "Vestuario",
        "stock"         => 50,
        "weight"        => 0.25,
        "shipping_class"=> "roupa",
        "description"   => "T-shirt oficial Praia do Norte com design exclusivo da onda gigante da Nazare. 100% algodao organico.",
        "short_desc"    => "T-shirt oficial com design da onda gigante.",
        "image"         => "https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&h=800&fit=crop",
    ],
    [
        "name"          => "Hoodie Nazare Big Wave",
        "sku"           => "PN-HD-001",
        "regular_price" => "59.90",
        "sale_price"    => "49.90",
        "category"      => "Vestuario",
        "stock"         => 25,
        "weight"        => 0.55,
        "shipping_class"=> "roupa",
        "description"   => "Hoodie premium Nazare Big Wave. Interior em fleece, capuz ajustavel. Ideal para os dias frios junto ao mar.",
        "short_desc"    => "Hoodie premium com design Big Wave.",
        "image"         => "https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=800&h=800&fit=crop",
    ],
    [
        "name"          => "Bone Praia do Norte",
        "sku"           => "PN-CAP-001",
        "regular_price" => "19.90",
        "sale_price"    => "",
        "category"      => "Acessorios",
        "stock"         => 100,
        "weight"        => 0.15,
        "shipping_class"=> "",
        "description"   => "Bone ajustavel Praia do Norte. Bordado frontal com logo. Aba curva, fecho em metal.",
        "short_desc"    => "Bone ajustavel com logo bordado.",
        "image"         => "https://images.unsplash.com/photo-1521369909029-2afed882baee?w=800&h=800&fit=crop",
    ],
    [
        "name"          => "Poster Onda Gigante (Edicao Limitada)",
        "sku"           => "PN-POST-001",
        "regular_price" => "15.00",
        "sale_price"    => "",
        "category"      => "Acessorios",
        "stock"         => 0,
        "weight"        => 0.30,
        "shipping_class"=> "",
        "description"   => "Poster de edicao limitada com fotografia da onda gigante da Nazare. Impressao fine art em papel 300g. Formato A2.",
        "short_desc"    => "Poster edicao limitada - fotografia da onda gigante.",
        "image"         => "https://images.unsplash.com/photo-1505118380757-91f5f5632de0?w=800&h=800&fit=crop",
    ],
    [
        "name"          => "Cera de Surf - Pack 3",
        "sku"           => "PN-WAX-001",
        "regular_price" => "12.50",
        "sale_price"    => "",
        "category"      => "Equipamento",
        "stock"         => 200,
        "weight"        => 0.45,
        "shipping_class"=> "",
        "description"   => "Pack de 3 barras de cera de surf. Formula para agua fria (abaixo de 15C). Aderencia superior.",
        "short_desc"    => "Pack 3 barras de cera para agua fria.",
        "image"         => "https://images.unsplash.com/photo-1531722569936-825d3dd91b15?w=800&h=800&fit=crop",
    ],
];

$created = 0;
$skipped = 0;

foreach ($products as $p) {
    $existing_id = wc_get_product_id_by_sku($p["sku"]);
    if ($existing_id) {
        $existing = wc_get_product($existing_id);
        $updated = false;
        if (!$existing->get_image_id() && !empty($p["image"])) {
            $image_id = pn_attach_image($p["image"], $existing_id, $p["name"]);
            if (!is_wp_error($image_id)) {
                $existing->set_image_id($image_id);
                $updated = true;
                echo "  Image added: {$p["name"]} [{$p["sku"]}]\n";
            } else {
                echo "  Image failed: {$p["name"]} - " . $image_id->get_error_message() . "\n";
            }
        }
        // Update weight if missing
        if (!$existing->get_weight() && !empty($p["weight"])) {
            $existing->set_weight($p["weight"]);
            $updated = true;
        }
        // Update shipping class if missing
        if (!$existing->get_shipping_class_id() && !empty($p["shipping_class"])) {
            $term = get_term_by("slug", $p["shipping_class"], "product_shipping_class");
            if ($term) {
                $existing->set_shipping_class_id($term->term_id);
                $updated = true;
            }
        }
        if ($updated) {
            $existing->save();
            echo "  Updated: {$p["name"]} [{$p["sku"]}]\n";
        } else {
            echo "  Skipped (exists): {$p["name"]} [{$p["sku"]}]\n";
        }
        $skipped++;
        continue;
    }

    $product = new WC_Product_Simple();
    $product->set_name($p["name"]);
    $product->set_sku($p["sku"]);
    $product->set_regular_price($p["regular_price"]);
    if (!empty($p["sale_price"])) {
        $product->set_sale_price($p["sale_price"]);
    }
    $product->set_description($p["description"]);
    $product->set_short_description($p["short_desc"]);
    $product->set_status("publish");
    if (!empty($p["weight"])) {
        $product->set_weight($p["weight"]);
    }
    if (!empty($p["shipping_class"])) {
        $term = get_term_by("slug", $p["shipping_class"], "product_shipping_class");
        if ($term) {
            $product->set_shipping_class_id($term->term_id);
        }
    }
    $product->set_manage_stock(true);
    $product->set_stock_quantity($p["stock"]);
    if ($p["stock"] === 0) {
        $product->set_stock_status("outofstock");
    } else {
        $product->set_stock_status("instock");
    }

    // Assign category
    if (isset($cat_ids[$p["category"]])) {
        $product->set_category_ids([$cat_ids[$p["category"]]]);
    }

    $product->save();

    // Download and attach product image
    if (!empty($p["image"])) {
        $image_id = pn_attach_image($p["image"], $product->get_id(), $p["name"]);
        if (!is_wp_error($image_id)) {
            $product->set_image_id($image_id);
            $product->save();
            echo "  Created: {$p["name"]} [{$p["sku"]}] - {$p["regular_price"]} EUR (with image)\n";
        } else {
            echo "  Created: {$p["name"]} [{$p["sku"]}] - {$p["regular_price"]} EUR (image failed: " . $image_id->get_error_message() . ")\n";
        }
    } else {
        echo "  Created: {$p["name"]} [{$p["sku"]}] - {$p["regular_price"]} EUR\n";
    }
    $created++;
}

echo "\n  Done: {$created} created, {$skipped} skipped.\n";
'
