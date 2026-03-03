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
        "description"   => "T-shirt oficial Praia do Norte com design exclusivo da onda gigante da Nazare. 100% algodao organico.",
        "short_desc"    => "T-shirt oficial com design da onda gigante.",
    ],
    [
        "name"          => "Hoodie Nazare Big Wave",
        "sku"           => "PN-HD-001",
        "regular_price" => "59.90",
        "sale_price"    => "49.90",
        "category"      => "Vestuario",
        "stock"         => 25,
        "description"   => "Hoodie premium Nazare Big Wave. Interior em fleece, capuz ajustavel. Ideal para os dias frios junto ao mar.",
        "short_desc"    => "Hoodie premium com design Big Wave.",
    ],
    [
        "name"          => "Bone Praia do Norte",
        "sku"           => "PN-CAP-001",
        "regular_price" => "19.90",
        "sale_price"    => "",
        "category"      => "Acessorios",
        "stock"         => 100,
        "description"   => "Bone ajustavel Praia do Norte. Bordado frontal com logo. Aba curva, fecho em metal.",
        "short_desc"    => "Bone ajustavel com logo bordado.",
    ],
    [
        "name"          => "Poster Onda Gigante (Edicao Limitada)",
        "sku"           => "PN-POST-001",
        "regular_price" => "15.00",
        "sale_price"    => "",
        "category"      => "Acessorios",
        "stock"         => 0,
        "description"   => "Poster de edicao limitada com fotografia da onda gigante da Nazare. Impressao fine art em papel 300g. Formato A2.",
        "short_desc"    => "Poster edicao limitada - fotografia da onda gigante.",
    ],
    [
        "name"          => "Cera de Surf - Pack 3",
        "sku"           => "PN-WAX-001",
        "regular_price" => "12.50",
        "sale_price"    => "",
        "category"      => "Equipamento",
        "stock"         => 200,
        "description"   => "Pack de 3 barras de cera de surf. Formula para agua fria (abaixo de 15C). Aderencia superior.",
        "short_desc"    => "Pack 3 barras de cera para agua fria.",
    ],
];

$created = 0;
$skipped = 0;

foreach ($products as $p) {
    $existing_id = wc_get_product_id_by_sku($p["sku"]);
    if ($existing_id) {
        echo "  Skipped (exists): {$p["name"]} [{$p["sku"]}]\n";
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
    echo "  Created: {$p["name"]} [{$p["sku"]}] - {$p["regular_price"]} EUR\n";
    $created++;
}

echo "\n  Done: {$created} created, {$skipped} skipped.\n";
'
