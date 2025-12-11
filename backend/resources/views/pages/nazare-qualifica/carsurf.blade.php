@php
    $locale = app('laravellocalization')->getCurrentLocale();
    $content = $page->content[$locale] ?? $page->content['pt'] ?? [];
@endphp

<x-layouts.app>
    {{-- Breadcrumbs --}}
    <div class="border-b bg-muted/30">
        <div class="container mx-auto px-4">
            <x-ui.breadcrumbs />
        </div>
    </div>

    {{-- Header --}}
    <section class="gradient-surf py-16 text-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                        <line x1="9" y1="9" x2="9.01" y2="9"/>
                        <line x1="15" y1="9" x2="15.01" y2="9"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold md:text-5xl">{{ $page->title[$locale] ?? $page->title['pt'] }}</h1>
                    <p class="text-xl opacity-90">{{ __('messages.nq.services.carsurf.shortDescription') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Description --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-4xl">
                <div class="grid gap-8 md:grid-cols-2">
                    <div>
                        <h2 class="mb-4 text-2xl font-bold">{{ __('messages.nq.about.intro.title') }}</h2>
                        <p class="text-lg text-muted-foreground">
                            {{ $content['description'] ?? __('messages.nq.services.carsurf.description') }}
                        </p>
                    </div>
                    <div class="rounded-lg bg-muted/30 p-6">
                        <h3 class="mb-4 font-semibold">{{ __('messages.carsurf.facilities.title') }}</h3>
                        <ul class="space-y-3">
                            @foreach($content['features'] ?? __('messages.nq.services.carsurf.features') as $feature)
                                <li class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-surf" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Link to Full Carsurf Section --}}
    <section class="bg-surf/10 py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-2xl font-bold">{{ __('messages.carsurf.cta.title') }}</h2>
            <p class="mb-6 text-muted-foreground">{{ __('messages.carsurf.cta.subtitle') }}</p>
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <x-ui.button href="{{ route('carsurf.index') }}" class="bg-surf hover:bg-surf/90">
                    {{ __('messages.carsurf.hero.about') }}
                </x-ui.button>
                <x-ui.button href="{{ route('carsurf.programas') }}" variant="outline" class="border-surf text-surf hover:bg-surf/10">
                    {{ __('messages.carsurf.hero.programs') }}
                </x-ui.button>
            </div>
        </div>
    </section>

    {{-- Contact CTA --}}
    <section class="bg-institutional py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.nq.services.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.nq.services.cta.text') }}</p>
            <x-ui.button href="{{ route('nq.servicos') }}" class="bg-white text-institutional hover:bg-white/90">
                {{ __('messages.nq.services.title') }}
            </x-ui.button>
        </div>
    </section>
</x-layouts.app>
