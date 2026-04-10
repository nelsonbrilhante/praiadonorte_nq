<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\LojaController;
use App\Models\ContactMessage;
use App\Mail\CarsurfReservation;
use Illuminate\Support\Facades\Mail;

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
Route::get('/', fn() => redirect('/pt'))->middleware('maintenance');

// Localized routes
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['maintenance', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
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

    // 301 Redirects - old Praia do Norte URLs
    Route::get('/sobre', fn() => redirect(LaravelLocalization::localizeURL('/praia-norte/sobre'), 301))->name('sobre.redirect');
    Route::get('/surfer-wall', fn() => redirect(LaravelLocalization::localizeURL('/praia-norte/surfer-wall'), 301));
    Route::get('/surfer-wall/{slug}', fn($slug) => redirect(LaravelLocalization::localizeURL('/praia-norte/surfer-wall/' . $slug), 301));
    Route::get('/previsoes', fn() => redirect(LaravelLocalization::localizeURL('/praia-norte/previsoes'), 301));

    // Praia do Norte
    Route::prefix('praia-norte')->name('pn.')->group(function () {
        Route::get('/sobre', function () {
            return view('pages.sobre');
        })->name('sobre');

        Route::get('/surfer-wall', function () {
            return view('pages.surfer-wall.index');
        })->name('surfers.index');

        Route::get('/surfer-wall/{slug}', function ($slug) {
            return view('pages.surfer-wall.show', compact('slug'));
        })->name('surfers.show');

        Route::get('/previsoes', [ForecastController::class, 'index'])->name('forecast');

        Route::get('/forte', function () {
            $page = \App\Models\Pagina::where('entity', 'praia-norte')
                ->where('slug', 'forte')
                ->first() ?? new \App\Models\Pagina([
                    'entity' => 'praia-norte',
                    'slug' => 'forte',
                    'title' => ['pt' => 'Forte de S. Miguel Arcanjo', 'en' => 'Fort of São Miguel Arcanjo'],
                    'content' => []
                ]);
            return view('pages.forte', compact('page'));
        })->name('forte');

        Route::get('/hidrografico', function () {
            return view('pages.hidrografico');
        })->name('hidrografico');

        Route::get('/webcams', function () {
            return view('pages.webcams');
        })->name('webcams');
    });

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

        Route::get('/instalacoes', function () {
            return view('pages.carsurf.instalacoes');
        })->name('instalacoes');

        Route::get('/formularios', function () {
            return view('pages.carsurf.formularios');
        })->name('formularios');

        Route::get('/reservas', function () {
            return view('pages.carsurf.reservas');
        })->name('reservas');

        Route::post('/reservas', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:50',
                'message' => 'required|string|max:5000',
            ]);

            ContactMessage::create([
                'entity' => 'carsurf',
                'type' => 'reserva',
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'message' => $validated['message'],
            ]);

            Mail::to('geral@carsurf.nazare.pt')
                ->queue(new CarsurfReservation(
                    senderName: $validated['name'],
                    senderEmail: $validated['email'],
                    senderPhone: $validated['phone'] ?? null,
                    senderMessage: $validated['message'],
                ));

            return redirect()->back()->with('success', __('messages.carsurf.reservas.form.success'));
        })->name('reservas.submit');
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

        Route::get('/servicos', fn() => redirect(LaravelLocalization::localizeURL('/nazare-qualifica/sobre'), 301))->name('servicos');

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

        // 301 Redirect - Forte moved to Praia do Norte
        Route::get('/forte', fn() => redirect(LaravelLocalization::localizeURL('/praia-norte/forte'), 301))->name('forte');

        Route::get('/ale', function () {
            $page = \App\Models\Pagina::where('entity', 'nazare-qualifica')
                ->where('slug', 'ale')
                ->first() ?? new \App\Models\Pagina(['entity' => 'nazare-qualifica', 'slug' => 'ale', 'title' => ['pt' => 'ALE', 'en' => 'ALE'], 'content' => []]);
            return view('pages.nazare-qualifica.ale', compact('page'));
        })->name('ale');

        Route::get('/contraordenacoes', function () {
            $documents = \App\Models\ContraOrdenacaoDocument::orderBy('order')->get();
            return view('pages.nazare-qualifica.contraordenacoes', compact('documents'));
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
    Route::get('/contacto', function () {
        return view('pages.contacto');
    })->name('contacto');

    // Legal pages
    Route::get('/privacidade', [LegalController::class, 'privacy'])->name('privacidade');
    Route::get('/termos', [LegalController::class, 'terms'])->name('termos');
    Route::get('/cookies', [LegalController::class, 'cookies'])->name('cookies');
});
