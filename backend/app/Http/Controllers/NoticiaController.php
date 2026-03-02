<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class NoticiaController extends Controller
{
    public function index()
    {
        $locale = LaravelLocalization::getCurrentLocale();
        $currentEntity = request('entity');

        // Featured news (all entities, published, limit 6)
        $featuredNoticias = Noticia::where('featured', true)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->limit(6)
            ->get();

        // Filtered list with pagination
        $query = Noticia::where('published_at', '<=', now())
            ->orderBy('published_at', 'desc');

        if ($currentEntity) {
            $query->where('entity', $currentEntity);
        }

        $noticias = $query->paginate(12);

        return view('pages.noticias.index', compact('featuredNoticias', 'noticias', 'locale'));
    }

    public function show(string $slug)
    {
        $locale = LaravelLocalization::getCurrentLocale();

        $noticia = Noticia::where('slug', $slug)
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Get related news (same entity, excluding current)
        $related = Noticia::where('entity', $noticia->entity)
            ->where('id', '!=', $noticia->id)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('pages.noticias.show', compact('noticia', 'related', 'locale'));
    }
}
