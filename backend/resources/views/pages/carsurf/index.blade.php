@php
    $locale = app('laravellocalization')->getCurrentLocale();

    // Try to get Carsurf landing page from database
    $pagina = \App\Models\Pagina::where('entity', 'carsurf')
        ->where('slug', 'landing')
        ->first();
@endphp

<x-layouts.app>
    {{-- Breadcrumbs --}}
    <div class="border-b bg-muted/30">
        <div class="container mx-auto px-4">
            <x-ui.breadcrumbs />
        </div>
    </div>

    {{-- Hero Section --}}
    <section class="gradient-performance py-24 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="mb-6 text-4xl font-bold md:text-5xl lg:text-6xl">
                    {{ __('messages.carsurf.hero.title') }}
                </h1>
                <p class="mb-8 text-xl opacity-90">
                    {{ __('messages.carsurf.hero.subtitle') }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <x-ui.button href="{{ route('carsurf.programas') }}" class="bg-white text-performance hover:bg-white/90">
                        {{ __('messages.carsurf.hero.programs') }}
                    </x-ui.button>
                    <x-ui.button variant="outline" href="{{ route('carsurf.sobre') }}" class="border-white bg-transparent text-white hover:bg-white/10">
                        {{ __('messages.carsurf.hero.about') }}
                    </x-ui.button>
                </div>
            </div>
        </div>
    </section>

    {{-- About Section --}}
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:items-center">
                <div>
                    {{-- Logo --}}
                    <img
                        src="{{ asset('images/logos/CARSURF_001.png') }}"
                        alt="Carsurf"
                        class="mb-6 h-16 w-auto md:h-20"
                    />
                    <h2 class="mb-6 text-3xl font-bold">{{ __('messages.carsurf.about.title') }}</h2>
                    <div class="prose max-w-none">
                        <p class="text-lg text-muted-foreground">
                            {{ __('messages.carsurf.about.text') }}
                        </p>
                    </div>
                    <div class="mt-8 rounded-lg bg-performance/10 p-6">
                        <p class="font-medium text-performance">
                            {{ __('messages.carsurf.about.highlight') }}
                        </p>
                    </div>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-lg bg-gradient-to-br from-performance/20 to-performance/5 flex items-center justify-center">
                    {{-- Logo large version --}}
                    <img
                        src="{{ asset('images/logos/CARSURF_001.png') }}"
                        alt="Carsurf"
                        class="h-32 w-auto opacity-50 md:h-48"
                    />
                </div>
            </div>
        </div>
    </section>

    {{-- Facilities Section --}}
    <section class="bg-muted/30 py-16">
        <div class="container mx-auto px-4">
            <h2 class="mb-8 text-center text-3xl font-bold">{{ __('messages.carsurf.facilities.title') }}</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @for($i = 1; $i <= 3; $i++)
                    <x-ui.card class="text-center">
                        <x-ui.card-content class="pt-6">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-performance/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-performance" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                                    <path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                                    <path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold">{{ __("messages.carsurf.facilities.item{$i}.title") }}</h3>
                            <p class="text-muted-foreground">{{ __("messages.carsurf.facilities.item{$i}.description") }}</p>
                        </x-ui.card-content>
                    </x-ui.card>
                @endfor
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="bg-performance py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.carsurf.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.carsurf.cta.subtitle') }}</p>
            <x-ui.button href="{{ route('contacto') }}" class="bg-white text-performance hover:bg-white/90">
                {{ __('messages.carsurf.cta.button') }}
            </x-ui.button>
        </div>
    </section>
</x-layouts.app>
