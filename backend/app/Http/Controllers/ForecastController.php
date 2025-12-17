<?php

namespace App\Http\Controllers;

use App\Services\ForecastService;

class ForecastController extends Controller
{
    public function __construct(
        private ForecastService $forecastService
    ) {}

    public function index()
    {
        $forecast = $this->forecastService->getFullForecast();
        $locale = app('laravellocalization')->getCurrentLocale();

        return view('pages.previsoes', [
            'forecast' => $forecast,
            'locale' => $locale,
        ]);
    }
}
