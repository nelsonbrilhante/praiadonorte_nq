<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\LojaController;

/*
|--------------------------------------------------------------------------
| Web Routes - Praia do Norte Platform
|--------------------------------------------------------------------------
|
| Localized routes for the public website.
| Admin panel (Filament) is excluded from localization.
|
*/

// Redirect root to default locale (PT)
Route::get('/', fn() => redirect('/pt'));

// Localized routes
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {

    // Homepage
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // News
    Route::get('/noticias', [NoticiaController::class, 'index'])->name('noticias.index');
    Route::get('/noticias/{slug}', [NoticiaController::class, 'show'])->name('noticias.show');

    // Events
    Route::get('/eventos', function () {
        return view('pages.eventos.index');
    })->name('eventos.index');

    Route::get('/eventos/{slug}', function ($slug) {
        return view('pages.eventos.show', compact('slug'));
    })->name('eventos.show');

    // Surfer Wall
    Route::get('/surfer-wall', function () {
        return view('pages.surfer-wall.index');
    })->name('surfers.index');

    Route::get('/surfer-wall/{slug}', function ($slug) {
        return view('pages.surfer-wall.show', compact('slug'));
    })->name('surfers.show');

    // Forecast
    Route::get('/previsoes', [ForecastController::class, 'index'])->name('forecast');

    // Shop / Loja (/pt/loja, /en/shop)
    Route::get('/loja', [LojaController::class, 'index'])->name('loja.index');
    Route::get('/loja/{slug}', [LojaController::class, 'show'])->name('loja.show');
    Route::get('/shop', [LojaController::class, 'index'])->name('shop.index');
    Route::get('/shop/{slug}', [LojaController::class, 'show'])->name('shop.show');

    // Carsurf
    Route::prefix('carsurf')->name('carsurf.')->group(function () {
        Route::get('/', function () {
            return view('pages.carsurf.index');
        })->name('index');

        Route::get('/sobre', function () {
            return view('pages.carsurf.sobre');
        })->name('sobre');

        Route::get('/programas', function () {
            return view('pages.carsurf.programas');
        })->name('programas');
    });

    // Nazare Qualifica
    Route::prefix('nazare-qualifica')->name('nq.')->group(function () {
        Route::get('/sobre', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'sobre')
                ->first() ?? new \App\Models\Pagina(['entity' => 'nazare-qualifica', 'slug' => 'sobre', 'title' => ['pt' => 'Sobre', 'en' => 'About'], 'content' => []]);
            return view('pages.nazare-qualifica.sobre', compact('page'));
        })->name('sobre');

        Route::get('/equipa', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'equipa')
                ->first() ?? new \App\Models\Pagina(['entity' => 'nazare-qualifica', 'slug' => 'equipa', 'title' => ['pt' => 'Equipa', 'en' => 'Team'], 'content' => []]);
            $members = \App\Models\CorporateBody::where('published', true)
                ->orderBy('section')
                ->orderBy('order')
                ->get();
            return view('pages.nazare-qualifica.equipa', compact('page', 'members'));
        })->name('equipa');

        Route::get('/servicos', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'servicos')
                ->first() ?? new \App\Models\Pagina(['entity' => 'nazare-qualifica', 'slug' => 'servicos', 'title' => ['pt' => 'Serviços', 'en' => 'Services'], 'content' => []]);
            return view('pages.nazare-qualifica.servicos', compact('page'));
        })->name('servicos');

        // Individual Service Pages
        Route::get('/carsurf', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'carsurf')
                ->first() ?? new \App\Models\Pagina(['entity' => 'nazare-qualifica', 'slug' => 'carsurf', 'title' => ['pt' => 'Carsurf', 'en' => 'Carsurf'], 'content' => []]);
            return view('pages.nazare-qualifica.carsurf', compact('page'));
        })->name('carsurf');

        Route::get('/estacionamento', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'estacionamento')
                ->first() ?? new \App\Models\Pagina(['entity' => 'nazare-qualifica', 'slug' => 'estacionamento', 'title' => ['pt' => 'Estacionamento', 'en' => 'Parking'], 'content' => []]);
            return view('pages.nazare-qualifica.estacionamento', compact('page'));
        })->name('estacionamento');

        Route::get('/forte', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'forte')
                ->first() ?? new \App\Models\Pagina(['entity' => 'nazare-qualifica', 'slug' => 'forte', 'title' => ['pt' => 'Forte de S. Miguel Arcanjo', 'en' => 'Fort of São Miguel Arcanjo'], 'content' => []]);
            return view('pages.nazare-qualifica.forte', compact('page'));
        })->name('forte');

        Route::get('/ale', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'ale')
                ->first() ?? new \App\Models\Pagina(['entity' => 'nazare-qualifica', 'slug' => 'ale', 'title' => ['pt' => 'ALE', 'en' => 'ALE'], 'content' => []]);
            return view('pages.nazare-qualifica.ale', compact('page'));
        })->name('ale');

        Route::get('/contraordenacoes', function () {
            return view('pages.nazare-qualifica.contraordenacoes');
        })->name('contraordenacoes');

        Route::get('/contraordenacoes/identificacao-de-condutor', function () {
            return view('pages.nazare-qualifica.identificacao-condutor');
        })->name('identificacao-condutor');

        Route::get('/contraordenacoes/apresentacao-de-defesa', function () {
            return view('pages.nazare-qualifica.apresentacao-defesa');
        })->name('apresentacao-defesa');

        Route::get('/documentos', function () {
            $categories = \App\Models\DocumentCategory::with('documents')
                ->orderBy('order')
                ->get();
            return view('pages.nazare-qualifica.documentos', compact('categories'));
        })->name('documentos');
    });

    // Static pages
    Route::get('/sobre', function () {
        return view('pages.sobre');
    })->name('sobre');

    Route::get('/contacto', function () {
        return view('pages.contacto');
    })->name('contacto');

    // Legal pages
    Route::get('/privacidade', function () {
        return view('pages.privacidade');
    })->name('privacidade');

    Route::get('/termos', function () {
        return view('pages.termos');
    })->name('termos');

    Route::get('/cookies', function () {
        return view('pages.cookies');
    })->name('cookies');
});
