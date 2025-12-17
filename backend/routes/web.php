<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\ForecastController;

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
Route::get('/', fn() => redirect(LaravelLocalization::localizeURL('/pt')));

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
                ->firstOrFail();
            return view('pages.nazare-qualifica.sobre', compact('page'));
        })->name('sobre');

        Route::get('/equipa', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'equipa')
                ->firstOrFail();
            return view('pages.nazare-qualifica.equipa', compact('page'));
        })->name('equipa');

        Route::get('/servicos', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'servicos')
                ->firstOrFail();
            return view('pages.nazare-qualifica.servicos', compact('page'));
        })->name('servicos');

        // Individual Service Pages
        Route::get('/carsurf', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'carsurf')
                ->firstOrFail();
            return view('pages.nazare-qualifica.carsurf', compact('page'));
        })->name('carsurf');

        Route::get('/estacionamento', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'estacionamento')
                ->firstOrFail();
            return view('pages.nazare-qualifica.estacionamento', compact('page'));
        })->name('estacionamento');

        Route::get('/forte', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'forte')
                ->firstOrFail();
            return view('pages.nazare-qualifica.forte', compact('page'));
        })->name('forte');

        Route::get('/ale', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'ale')
                ->firstOrFail();
            return view('pages.nazare-qualifica.ale', compact('page'));
        })->name('ale');
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
