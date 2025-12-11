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
    <section class="bg-gradient-to-r from-amber-600 to-amber-700 py-16 text-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18M5 21V7l8-4 8 4v14M9 21v-6h6v6"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold md:text-5xl">{{ $page->title[$locale] ?? $page->title['pt'] }}</h1>
                    <p class="text-xl opacity-90">{{ __('messages.nq.services.forte.shortDescription') }}</p>
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
                            {{ $content['description'] ?? __('messages.nq.services.forte.description') }}
                        </p>
                    </div>
                    <div class="rounded-lg bg-amber-50 p-6 border border-amber-200">
                        <h3 class="mb-4 font-semibold text-amber-900">{{ __('messages.carsurf.facilities.title') }}</h3>
                        <ul class="space-y-3">
                            @foreach($content['features'] ?? __('messages.nq.services.forte.features') as $feature)
                                <li class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    <span class="text-amber-900">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Historical Significance --}}
    @if(!empty($content['stats']))
    <section class="bg-muted/30 py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="mb-6 text-2xl font-bold">{{ __('messages.about.history.title') }}</h2>
                <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                    @foreach($content['stats'] as $stat)
                        <div class="rounded-lg bg-white p-4 shadow-sm">
                            <div class="text-3xl font-bold text-amber-600">{{ $stat['value'] ?? '' }}</div>
                            <div class="text-sm text-muted-foreground">{{ $stat['label'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @else
    <section class="bg-muted/30 py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="mb-6 text-2xl font-bold">{{ __('messages.about.history.title') }}</h2>
                <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <div class="text-3xl font-bold text-amber-600">1577</div>
                        <div class="text-sm text-muted-foreground">{{ __('messages.nq.services.forte.features.year') }}</div>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <div class="text-3xl font-bold text-amber-600">650k+</div>
                        <div class="text-sm text-muted-foreground">{{ __('messages.nq.services.forte.features.visitors') }}</div>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <div class="text-3xl font-bold text-amber-600">XVI</div>
                        <div class="text-sm text-muted-foreground">{{ __('messages.nq.services.forte.features.history') }}</div>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <div class="text-3xl font-bold text-amber-600">#1</div>
                        <div class="text-sm text-muted-foreground">{{ __('messages.nq.services.forte.features.viewpoint') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Viewpoint --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl text-center">
                <div class="mb-6 flex justify-center">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-amber-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="2" y1="12" x2="22" y2="12"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="mb-4 text-2xl font-bold">{{ __('messages.forecast.webcams.forte') }}</h2>
                <p class="text-muted-foreground">
                    O Forte de São Miguel Arcanjo é o ponto de observação mais icónico para assistir às ondas gigantes da Nazaré. De lá, pode ver os surfistas a desafiarem as maiores ondas do mundo.
                </p>
            </div>
        </div>
    </section>

    {{-- Contact CTA --}}
    <section class="bg-amber-600 py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.nq.services.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.nq.services.cta.text') }}</p>
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <x-ui.button href="tel:{{ $content['contact']['phone'] ?? __('messages.nq.contact.phone') }}" class="bg-white text-amber-600 hover:bg-white/90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    {{ $content['contact']['phone'] ?? __('messages.nq.contact.phone') }}
                </x-ui.button>
                <x-ui.button href="{{ route('nq.servicos') }}" variant="outline" class="border-white text-white hover:bg-white/10">
                    {{ __('messages.nq.services.title') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.app>
