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

        // Fetch featured surfers (up to 4)
        $surfers = Surfer::where('featured', true)
            ->orderBy('order')
            ->limit(4)
            ->get();

        // Fetch homepage data with hero slides
        $homepage = Pagina::with(['heroSlides' => function ($query) {
                $query->where('active', true)->orderBy('order');
            }])
            ->where('entity', 'praia-norte')
            ->where('slug', 'homepage')
            ->where('published', true)
            ->first();

        return view('pages.home', compact('noticias', 'eventos', 'surfers', 'homepage', 'locale'));
    }
}
