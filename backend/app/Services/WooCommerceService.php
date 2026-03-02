<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WooCommerceService
{
    private string $baseUrl;
    private string $consumerKey;
    private string $consumerSecret;
    private string $apiVersion;
    private int $cacheTtl;
    private int $perPage;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('woocommerce.url'), '/');
        $this->consumerKey = config('woocommerce.consumer_key');
        $this->consumerSecret = config('woocommerce.consumer_secret');
        $this->apiVersion = config('woocommerce.api_version');
        $this->cacheTtl = config('woocommerce.cache_ttl');
        $this->perPage = config('woocommerce.per_page');
        $this->timeout = config('woocommerce.timeout');
    }

    /**
     * Get paginated products.
     */
    public function getProducts(int $page = 1, ?int $categoryId = null, ?string $search = null): array
    {
        $cacheKey = "woo_products_{$page}_{$categoryId}_{$search}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($page, $categoryId, $search) {
            try {
                $params = [
                    'page' => $page,
                    'per_page' => $this->perPage,
                    'status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'desc',
                ];

                if ($categoryId) {
                    $params['category'] = $categoryId;
                }

                if ($search) {
                    $params['search'] = $search;
                }

                $response = $this->request('products', $params);

                if (!$response) {
                    return ['products' => [], 'total' => 0, 'pages' => 0];
                }

                return [
                    'products' => collect($response->json())->map(fn ($p) => $this->transformProduct($p))->all(),
                    'total' => (int) $response->header('X-WP-Total', 0),
                    'pages' => (int) $response->header('X-WP-TotalPages', 0),
                ];
            } catch (\Exception $e) {
                Log::error('WooCommerce getProducts error: ' . $e->getMessage());
                return ['products' => [], 'total' => 0, 'pages' => 0];
            }
        });
    }

    /**
     * Get a single product by slug.
     */
    public function getProductBySlug(string $slug): ?array
    {
        $cacheKey = "woo_product_{$slug}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($slug) {
            try {
                $response = $this->request('products', ['slug' => $slug]);

                if (!$response) {
                    return null;
                }

                $products = $response->json();

                if (empty($products)) {
                    return null;
                }

                return $this->transformProduct($products[0]);
            } catch (\Exception $e) {
                Log::error('WooCommerce getProductBySlug error: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get product categories.
     */
    public function getCategories(): array
    {
        return Cache::remember('woo_categories', $this->cacheTtl, function () {
            try {
                $response = $this->request('products/categories', [
                    'per_page' => 100,
                    'orderby' => 'name',
                    'order' => 'asc',
                    'hide_empty' => true,
                ]);

                if (!$response) {
                    return [];
                }

                return collect($response->json())->map(fn ($c) => [
                    'id' => $c['id'],
                    'name' => $c['name'],
                    'slug' => $c['slug'],
                    'count' => $c['count'],
                    'image' => $c['image']['src'] ?? null,
                ])->all();
            } catch (\Exception $e) {
                Log::error('WooCommerce getCategories error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Check if the WooCommerce API is reachable.
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(5)
                ->withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get("{$this->baseUrl}/wp-json/{$this->apiVersion}/system_status");

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Make an authenticated API request.
     */
    private function request(string $endpoint, array $params = []): ?\Illuminate\Http\Client\Response
    {
        if (empty($this->baseUrl) || empty($this->consumerKey)) {
            return null;
        }

        try {
            $url = "{$this->baseUrl}/wp-json/{$this->apiVersion}/{$endpoint}";

            $response = Http::timeout($this->timeout)
                ->withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get($url, $params);

            if ($response->failed()) {
                Log::warning("WooCommerce API error [{$response->status()}]: {$endpoint}", [
                    'params' => $params,
                ]);
                return null;
            }

            return $response;
        } catch (\Exception $e) {
            Log::warning("WooCommerce connection error: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Transform a raw WooCommerce product into a clean array.
     */
    private function transformProduct(array $product): array
    {
        $images = collect($product['images'] ?? [])->map(fn ($img) => [
            'src' => $img['src'],
            'alt' => $img['alt'] ?? '',
        ])->all();

        return [
            'id' => $product['id'],
            'name' => $product['name'],
            'slug' => $product['slug'],
            'type' => $product['type'],
            'status' => $product['status'],
            'description' => $product['description'],
            'short_description' => $product['short_description'],
            'price' => $product['price'],
            'regular_price' => $product['regular_price'],
            'sale_price' => $product['sale_price'],
            'on_sale' => $product['on_sale'],
            'stock_status' => $product['stock_status'],
            'categories' => collect($product['categories'] ?? [])->map(fn ($c) => [
                'id' => $c['id'],
                'name' => $c['name'],
                'slug' => $c['slug'],
            ])->all(),
            'images' => $images,
            'featured_image' => $images[0] ?? null,
            'permalink' => $product['permalink'],
        ];
    }
}
