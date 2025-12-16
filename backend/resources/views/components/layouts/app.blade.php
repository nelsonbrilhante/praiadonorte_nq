<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}">
<head>
    {{-- Theme initialization - Must run before content renders to prevent flash --}}
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'system';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (theme === 'dark' || (theme === 'system' && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <title>{{ $seo_title ?? __('messages.metadata.title') }}</title>
    <meta name="description" content="{{ $seo_description ?? __('messages.metadata.description') }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $seo_title ?? __('messages.metadata.title') }}">
    <meta property="og:description" content="{{ $seo_description ?? __('messages.metadata.description') }}">
    <meta property="og:image" content="{{ $og_image ?? asset('images/og-default.jpg') }}">
    <meta property="og:type" content="{{ $og_type ?? 'website' }}">
    <meta property="og:locale" content="{{ LaravelLocalization::getCurrentLocale() == 'pt' ? 'pt_PT' : 'en_GB' }}">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo_title ?? __('messages.metadata.title') }}">
    <meta name="twitter:description" content="{{ $seo_description ?? __('messages.metadata.description') }}">
    <meta name="twitter:image" content="{{ $og_image ?? asset('images/og-default.jpg') }}">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Alternate Languages --}}
    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
    @endforeach

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">

    {{-- Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire Styles --}}
    @livewireStyles

    {{-- Page-specific head content --}}
    @stack('head')

    {{-- JSON-LD Structured Data --}}
    @stack('json-ld')
</head>
<body class="min-h-screen bg-background font-sans antialiased">
    {{-- Header --}}
    <x-layout.header />

    {{-- Search Spotlight (Alpine.js - bypasses Livewire server issues) --}}
    <x-search-spotlight />

    {{-- Main Content --}}
    <main @class(['pt-16' => !request()->routeIs('home')])>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <x-layout.footer />

    {{-- Livewire Scripts --}}
    @livewireScripts

    {{-- Page-specific scripts --}}
    @stack('scripts')
</body>
</html>
