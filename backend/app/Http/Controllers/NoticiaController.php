<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class NoticiaController extends Controller
{
    public function index()
    {
        $locale = LaravelLocalization::getCurrentLocale();

        $noticias = Noticia::where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('pages.noticias.index', compact('noticias', 'locale'));
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
