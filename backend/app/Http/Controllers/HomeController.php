<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Evento;
use App\Models\Surfer;
use App\Models\Pagina;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HomeController extends Controller
{
    public function index()
    {
        $locale = LaravelLocalization::getCurrentLocale();

        // Fetch latest 3 published news
        $noticias = Noticia::where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Fetch upcoming 2 events
        $eventos = Evento::where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->limit(2)
            ->get();

        // Fetch all featured surfers (for carousel)
        $surfers = Surfer::where('featured', true)
            ->orderBy('order')
            ->get();

        // Fetch homepage data with hero slides
        $homepage = Pagina::with(['heroSlides' => function ($query) {
                $query->where('active', true)->orderBy('order');
            }])
            ->where('entity', 'praia-norte')
            ->where('slug', 'homepage')
            ->where('published', true)
            ->first();

        // Fetch news marked for hero slider
        $featuredNoticias = Noticia::where('show_in_hero', true)
            ->where('published_at', '<=', now())
            ->whereNotNull('cover_image')
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        // Convert featured news to virtual hero slides
        $newsSlides = $featuredNoticias->map(function ($noticia) {
            return (object) [
                'active' => true,
                'video_url' => null,
                'fallback_image' => $noticia->cover_image,
                'is_live' => false,
                'audio_enabled' => false,
                'hero_logo' => null,
                'use_logo_as_title' => false,
                'logo_height' => null,
                'title' => $noticia->title,
                'subtitle' => $noticia->excerpt ?? ['pt' => '', 'en' => ''],
                'cta_text' => ['pt' => 'Ler mais', 'en' => 'Read more'],
                'cta_url' => ['pt' => '/noticias/' . $noticia->slug, 'en' => '/noticias/' . $noticia->slug],
            ];
        });

        // Merge manual hero slides with featured news slides
        $heroSlides = collect($homepage?->heroSlides ?? [])
            ->concat($newsSlides);

        return view('pages.home', compact('noticias', 'eventos', 'surfers', 'homepage', 'heroSlides', 'locale'));
    }
}
