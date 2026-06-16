<?php

use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\NoticiaController;
use App\Http\Controllers\Api\PaginaController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SurferController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Public API endpoints for the Next.js frontend.
| All routes are prefixed with /api/v1
|
*/

Route::prefix('v1')->middleware('throttle:60,1')->group(function () {

    // Notícias (News)
    Route::get('/noticias', [NoticiaController::class, 'index']);
    Route::get('/noticias/latest', [NoticiaController::class, 'latest']);
    Route::get('/noticias/{slug}', [NoticiaController::class, 'show']);

    // Eventos (Events)
    Route::get('/eventos', [EventoController::class, 'index']);
    Route::get('/eventos/upcoming', [EventoController::class, 'upcoming']);
    Route::get('/eventos/{slug}', [EventoController::class, 'show']);

    // Surfers (Surfer Wall)
    Route::get('/surfers', [SurferController::class, 'index']);
    Route::get('/surfers/featured', [SurferController::class, 'featured']);
    Route::get('/surfers/{slug}', [SurferController::class, 'show']);

    // Páginas (Institutional Pages)
    Route::get('/paginas', [PaginaController::class, 'index']);
    Route::get('/paginas/{entity}/{slug}', [PaginaController::class, 'show']);

    // Search
    Route::get('/search', [SearchController::class, 'search']);

});
