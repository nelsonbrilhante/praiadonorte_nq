<?php

return [

    /*
    |--------------------------------------------------------------------------
    | WooCommerce API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the WooCommerce REST API integration.
    | The Laravel site consumes products server-side from the WooCommerce
    | instance running as a separate Docker service.
    |
    | Payment: Easypay plugin for WooCommerce
    | Manual: https://www.easypay.pt/suporte/manual-para-woocommerce/
    |
    */

    'url' => env('WOOCOMMERCE_URL', 'https://store.praiadonortenazare.pt'),

    'consumer_key' => env('WOOCOMMERCE_CONSUMER_KEY', ''),

    'consumer_secret' => env('WOOCOMMERCE_CONSUMER_SECRET', ''),

    // API version
    'api_version' => env('WOOCOMMERCE_API_VERSION', 'wc/v3'),

    // Cache TTL in seconds (default: 5 minutes)
    'cache_ttl' => env('WOOCOMMERCE_CACHE_TTL', 300),

    // Products per page
    'per_page' => env('WOOCOMMERCE_PER_PAGE', 12),

    // Request timeout in seconds
    'timeout' => env('WOOCOMMERCE_TIMEOUT', 10),

];
