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
    <section class="gradient-institutional py-16 text-white">
        <div class="container mx-auto px-4">
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ $page->title[$locale] ?? $page->title['pt'] }}</h1>
            <p class="text-xl opacity-90">{{ __('messages.nq.team.subtitle') }}</p>
        </div>
    </section>

    {{-- Conselho de Gerência --}}
    @if(!empty($content['conselho']))
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-8 text-center text-3xl font-bold">{{ __('messages.nq.team.conselhoGerencia.title') }}</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach($content['conselho'] as $member)
                    <x-ui.card class="text-center">
                        <x-ui.card-content class="pt-6">
                            <div class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-full bg-institutional/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <h3 class="mb-1 text-xl font-semibold">{{ $member['name'] ?? '' }}</h3>
                            <p class="text-institutional font-medium">{{ $member['role'] ?? '' }}</p>
                        </x-ui.card-content>
                    </x-ui.card>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Assembleia Geral & Fiscal Único --}}
    <section class="bg-muted/30 py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                {{-- Assembleia Geral --}}
                @if(!empty($content['assembleia']))
                <div>
                    <h2 class="mb-6 text-2xl font-bold">{{ __('messages.nq.team.assembleiaGeral.title') }}</h2>
                    <x-ui.card>
                        <x-ui.card-content class="flex items-center gap-4 pt-6">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-institutional/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">{{ $content['assembleia']['name'] ?? '' }}</h3>
                                <p class="text-institutional">{{ $content['assembleia']['role'] ?? '' }}</p>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
                @endif

                {{-- Fiscal Único --}}
                @if(!empty($content['fiscal']))
                <div>
                    <h2 class="mb-6 text-2xl font-bold">{{ __('messages.nq.team.fiscalUnico.title') }}</h2>
                    <x-ui.card>
                        <x-ui.card-content class="flex items-center gap-4 pt-6">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-institutional/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold">{{ $content['fiscal']['company'] ?? '' }}</h3>
                                <p class="text-muted-foreground">{{ $content['fiscal']['representative'] ?? '' }}</p>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-institutional py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.nq.about.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.nq.about.cta.subtitle') }}</p>
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <x-ui.button href="{{ route('nq.sobre') }}" class="bg-white text-institutional hover:bg-white/90">
                    {{ __('messages.pages.about') }}
                </x-ui.button>
                <x-ui.button href="{{ route('nq.servicos') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                    {{ __('messages.nq.services.title') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.app>
