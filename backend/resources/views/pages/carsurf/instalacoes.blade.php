@php
    $locale = app('laravellocalization')->getCurrentLocale();
    $pagina = \App\Models\Pagina::where('entity', 'carsurf')->where('slug', 'instalacoes')->first();
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero
        title="{{ __('messages.carsurf.instalacoes.title') }}"
        subtitle="{{ __('messages.carsurf.instalacoes.subtitle') }}"
        entity="carsurf"
        image="{{ $pagina?->hero_image ? asset('storage/' . $pagina->hero_image) : '' }}"
    />

    {{-- Intro Section --}}
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-4xl">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-5 lg:items-start">
                    {{-- Logo --}}
                    <div class="lg:col-span-2 flex justify-center lg:justify-start">
                        <img
                            src="{{ asset('images/logos/CARSURF_001.png') }}"
                            alt="Carsurf"
                            class="h-20 w-auto md:h-24"
                        />
                    </div>
                    {{-- Text --}}
                    <div class="lg:col-span-3 space-y-6">
                        <p class="text-lg text-muted-foreground leading-relaxed">
                            {{ __('messages.carsurf.instalacoes.intro') }}
                        </p>
                        <div class="rounded-lg bg-performance/10 p-6">
                            <p class="font-medium text-performance">
                                {{ __('messages.carsurf.instalacoes.mission') }}
                            </p>
                        </div>
                        <p class="text-sm text-muted-foreground">
                            {{ __('messages.carsurf.instalacoes.sports') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Center Heading --}}
    <section class="bg-muted/10 py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold md:text-3xl">{{ __('messages.carsurf.instalacoes.center_tagline') }}</h2>
            <p class="mt-4 mx-auto max-w-3xl text-muted-foreground">
                {{ __('messages.carsurf.instalacoes.equipment_intro') }}
            </p>
        </div>
    </section>

    {{-- Facilities Grid --}}
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 max-w-4xl mx-auto">
                {{-- Cardiovascular --}}
                <x-ui.card class="overflow-hidden" :noPadding="true">
                    <div class="h-3 bg-gradient-to-r from-performance to-performance/60"></div>
                    <x-ui.card-content class="pt-6">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-performance/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-performance" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold">{{ __('messages.carsurf.instalacoes.cardio.title') }}</h3>
                        <p class="text-muted-foreground">{{ __('messages.carsurf.instalacoes.cardio.description') }}</p>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Strength --}}
                <x-ui.card class="overflow-hidden" :noPadding="true">
                    <div class="h-3 bg-gradient-to-r from-performance to-performance/60"></div>
                    <x-ui.card-content class="pt-6">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-performance/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-performance" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6.5 6.5 11 11"/><path d="m21 21-1-1"/><path d="m3 3 1 1"/><path d="m18 22 4-4"/><path d="m2 6 4-4"/><path d="m3 10 7-7"/><path d="m14 21 7-7"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold">{{ __('messages.carsurf.instalacoes.strength.title') }}</h3>
                        <p class="text-muted-foreground">{{ __('messages.carsurf.instalacoes.strength.description') }}</p>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Functional --}}
                <x-ui.card class="overflow-hidden" :noPadding="true">
                    <div class="h-3 bg-gradient-to-r from-performance to-performance/60"></div>
                    <x-ui.card-content class="pt-6">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-performance/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-performance" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8a6 6 0 0 0-6-6 6 6 0 0 0-6 6c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold">{{ __('messages.carsurf.instalacoes.functional.title') }}</h3>
                        <p class="text-muted-foreground">{{ __('messages.carsurf.instalacoes.functional.description') }}</p>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Recovery --}}
                <x-ui.card class="overflow-hidden" :noPadding="true">
                    <div class="h-3 bg-gradient-to-r from-performance to-performance/60"></div>
                    <x-ui.card-content class="pt-6">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-performance/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-performance" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 2a2 2 0 0 0-2 2v5H4a2 2 0 0 0-2 2v2c0 1.1.9 2 2 2h5v5c0 1.1.9 2 2 2h2a2 2 0 0 0 2-2v-5h5a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2h-5V4a2 2 0 0 0-2-2h-2z"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold">{{ __('messages.carsurf.instalacoes.recovery.title') }}</h3>
                        <p class="text-muted-foreground">{{ __('messages.carsurf.instalacoes.recovery.description') }}</p>
                    </x-ui.card-content>
                </x-ui.card>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="bg-performance py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.carsurf.instalacoes.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.carsurf.instalacoes.cta.subtitle') }}</p>
            <x-ui.button href="{{ route('contacto') }}" class="bg-white text-performance hover:bg-white/90">
                {{ __('messages.carsurf.instalacoes.cta.button') }}
            </x-ui.button>
        </div>
    </section>
</x-layouts.app>
