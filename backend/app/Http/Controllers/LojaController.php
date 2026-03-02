<?php

namespace App\Http\Controllers;

use App\Services\WooCommerceService;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LojaController extends Controller
{
    public function __construct(
        private WooCommerceService $woocommerce,
    ) {}

    public function index()
    {
        $locale = LaravelLocalization::getCurrentLocale();
        $page = (int) request('page', 1);
        $categorySlug = request('category');

        $categories = $this->woocommerce->getCategories();

        $categoryId = null;
        if ($categorySlug) {
            $category = collect($categories)->firstWhere('slug', $categorySlug);
            $categoryId = $category['id'] ?? null;
        }

        $result = $this->woocommerce->getProducts($page, $categoryId);

        return view('pages.loja.index', [
            'products' => $result['products'],
            'totalProducts' => $result['total'],
            'totalPages' => $result['pages'],
            'currentPage' => $page,
            'categories' => $categories,
            'currentCategory' => $categorySlug,
            'locale' => $locale,
        ]);
    }

    public function show(string $slug)
    {
        $locale = LaravelLocalization::getCurrentLocale();

        $product = $this->woocommerce->getProductBySlug($slug);

        if (!$product) {
            abort(404);
        }

        return view('pages.loja.show', [
            'product' => $product,
            'locale' => $locale,
        ]);
    }
}
