<?php

return [

    // Praia do Norte Platform - PT (primary) + EN
    'supportedLocales' => [
        'pt' => ['name' => 'Portuguese', 'script' => 'Latn', 'native' => 'Português', 'regional' => 'pt_PT'],
        'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English', 'regional' => 'en_GB'],
    ],

    // Automatically determine locale from browser on first visit
    'useAcceptLanguageHeader' => true,

    // Keep locale visible in URL (e.g., /pt/noticias, /en/news)
    // This helps with SEO and user clarity
    'hideDefaultLocaleInURL' => false,

    // Display order in language selector: PT first (primary), EN second
    'localesOrder' => ['pt', 'en'],

    // No custom URL segments needed
    'localesMapping' => [],

    // UTF-8 suffix for locale functions
    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),

    // URLs to exclude from localization (admin panel, API)
    'urlsIgnored' => ['/admin', '/admin/*', '/api', '/api/*', '/livewire/*'],

    // Don't process these HTTP methods for localization redirects
    'httpMethodsIgnored' => ['POST', 'PUT', 'PATCH', 'DELETE'],
];
