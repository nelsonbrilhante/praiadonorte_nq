@php
    $locale = app('laravellocalization')->getCurrentLocale();

    // Try to get About page from database
    $pagina = \App\Models\Pagina::where('entity', 'praia-norte')
        ->where('slug', 'about')
        ->first();

    $getLocalized = function($field, $locale) {
        if (is_array($field)) {
            return $field[$locale] ?? $field['pt'] ?? '';
        }
        return $field ?? '';
    };
@endphp

<x-layouts.app>
    {{-- Breadcrumbs --}}
    <div class="border-b bg-muted/30">
        <div class="container mx-auto px-4">
            <x-ui.breadcrumbs />
        </div>
    </div>

    {{-- Header --}}
    <section class="gradient-ocean py-16 text-white">
        <div class="container mx-auto px-4">
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ __('messages.about.title') }}</h1>
            <p class="text-xl opacity-90">{{ __('messages.about.subtitle') }}</p>
        </div>
    </section>

    {{-- Introduction Section --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:items-center">
                <div class="relative aspect-video overflow-hidden rounded-lg">
                    <img
                        src="{{ asset('pn-ai-wave-hero.png') }}"
                        alt="Praia do Norte"
                        class="h-full w-full object-cover"
                    />
                </div>
                <div>
                    {{-- Logo --}}
                    <img
                        src="{{ asset('images/logos/LOGOTIPO PN.png') }}"
                        alt="Praia do Norte"
                        class="mb-6 h-16 w-auto md:h-20"
                    />
                    <h2 class="mb-6 text-3xl font-bold">{{ __('messages.about.intro.title') }}</h2>
                    <div class="prose max-w-none">
                        @if($pagina && $pagina->content)
                            {!! $getLocalized($pagina->content, $locale) !!}
                        @else
                            <p class="text-lg text-muted-foreground">
                                {{ __('messages.about.intro.text') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Giant Waves Section --}}
    <section class="bg-muted/30 py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="mb-6 text-3xl font-bold">{{ __('messages.about.waves.title') }}</h2>
                <p class="mb-8 text-lg text-muted-foreground">
                    {{ __('messages.about.waves.text') }}
                </p>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <x-ui.card class="text-center">
                        <x-ui.card-content class="pt-6">
                            <div class="text-4xl font-bold text-ocean">30m+</div>
                            <p class="text-muted-foreground">{{ __('messages.about.waves.height') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>
                    <x-ui.card class="text-center">
                        <x-ui.card-content class="pt-6">
                            <div class="text-4xl font-bold text-ocean">2011</div>
                            <p class="text-muted-foreground">{{ __('messages.about.waves.worldRecord') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>
                    <x-ui.card class="text-center">
                        <x-ui.card-content class="pt-6">
                            <div class="text-4xl font-bold text-ocean">WSL</div>
                            <p class="text-muted-foreground">{{ __('messages.about.waves.bigWaveTour') }}</p>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            </div>
        </div>
    </section>

    {{-- History Section --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl">
                <h2 class="mb-6 text-3xl font-bold">{{ __('messages.about.history.title') }}</h2>
                <div class="prose max-w-none">
                    <p>{{ __('messages.about.history.text1') }}</p>
                    <p>{{ __('messages.about.history.text2') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Location Section --}}
    <section class="bg-muted/30 py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-8 text-center text-3xl font-bold">{{ __('messages.about.location.title') }}</h2>
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <div>
                    <h3 class="mb-4 text-xl font-semibold">{{ __('messages.about.location.howToGet') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-ocean/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ocean" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">{{ __('messages.about.location.fromLisbon.title') }}</p>
                                <p class="text-sm text-muted-foreground">{{ __('messages.about.location.fromLisbon.text') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-ocean/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-ocean" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium">{{ __('messages.about.location.fromPorto.title') }}</p>
                                <p class="text-sm text-muted-foreground">{{ __('messages.about.location.fromPorto.text') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="aspect-video overflow-hidden rounded-lg bg-muted">
                    {{-- Map placeholder --}}
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3093.2067!2d-9.0709!3d39.6012!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd18e8b5e8c1c8c1%3A0x5c1c8c1c8c1c8c1c!2sPraia%20do%20Norte!5e0!3m2!1sen!2spt!4v1234567890"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="bg-ocean py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.about.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.about.cta.subtitle') }}</p>
            <div class="flex flex-wrap justify-center gap-4">
                <x-ui.button href="{{ route('surfers.index') }}" class="bg-white text-ocean hover:bg-white/90">
                    {{ __('messages.about.cta.surferWall') }}
                </x-ui.button>
                <x-ui.button href="{{ route('forecast') }}" variant="outline" class="border-white text-white hover:bg-white/10">
                    {{ __('messages.about.cta.forecast') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.app>
