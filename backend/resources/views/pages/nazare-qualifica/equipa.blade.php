@php
    $locale = app('laravellocalization')->getCurrentLocale();
    $heroImage = file_exists(storage_path('app/public/corporate-bodies/hero-equipa.jpg'))
        ? asset('storage/corporate-bodies/hero-equipa.jpg')
        : ($page->hero_image ? asset('storage/' . $page->hero_image) : '');

    $conselho = $members->where('section', 'conselho_gerencia');
    $assembleia = $members->where('section', 'assembleia_geral');
    $fiscal = $members->where('section', 'fiscal_unico');
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero title="{{ $page->title[$locale] ?? $page->title['pt'] }}" subtitle="{{ __('messages.nq.team.subtitle') }}" entity="nazare-qualifica" image="{{ $heroImage }}" />

    {{-- Conselho de Gerência --}}
    @if($conselho->isNotEmpty())
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="mb-10 text-center text-3xl font-bold">{{ __('messages.nq.team.conselhoGerencia.title') }}</h2>
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($conselho as $member)
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg" style="aspect-ratio: 3/4;">
                        {{-- Photo background --}}
                        @if($member->photo && file_exists(storage_path('app/public/' . $member->photo)))
                            <img
                                src="{{ asset('storage/' . $member->photo) }}"
                                alt="{{ $member->name }}"
                                class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                            />
                        @else
                            <div class="absolute inset-0 flex items-center justify-center bg-institutional/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-institutional/30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Gradient overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent transition-opacity duration-300 group-hover:from-black/90"></div>

                        {{-- Content --}}
                        <div class="absolute inset-x-0 bottom-0 p-6 text-white">
                            <h3 class="text-xl font-bold">{{ $member->name }}</h3>
                            <p class="mt-1 text-sm font-medium text-white/80">{{ $member->role[$locale] ?? $member->role['pt'] ?? '' }}</p>

                            @if($member->cv_file && file_exists(storage_path('app/public/' . $member->cv_file)))
                                <a
                                    href="{{ asset('storage/' . $member->cv_file) }}"
                                    target="_blank"
                                    class="mt-3 inline-flex items-center gap-2 rounded-lg bg-white/15 px-3 py-1.5 text-sm font-medium text-white backdrop-blur-sm transition-colors duration-200 hover:bg-white/25"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7 10 12 15 17 10"/>
                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    {{ __('messages.nq.team.viewCV') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Assembleia Geral & Fiscal Único --}}
    <section class="bg-muted/10 py-20">
        <div class="container mx-auto px-4">
            <div class="mx-auto grid max-w-4xl grid-cols-1 gap-8 md:grid-cols-2">
                {{-- Assembleia Geral --}}
                @if($assembleia->isNotEmpty())
                <div class="flex flex-col">
                    <h2 class="mb-8 text-center text-2xl font-bold">{{ __('messages.nq.team.assembleiaGeral.title') }}</h2>
                    @foreach($assembleia as $member)
                        <div class="group flex flex-1 flex-col items-center justify-center overflow-hidden rounded-2xl border bg-card px-8 py-10 shadow-md transition-shadow duration-200 hover:shadow-xl">
                            {{-- Circular photo --}}
                            @if($member->photo && file_exists(storage_path('app/public/' . $member->photo)))
                                <img
                                    src="{{ asset('storage/' . $member->photo) }}"
                                    alt="{{ $member->name }}"
                                    class="h-32 w-32 shrink-0 rounded-full object-cover ring-4 ring-institutional/20 transition-transform duration-300 group-hover:scale-105"
                                />
                            @else
                                <div class="flex h-32 w-32 shrink-0 items-center justify-center rounded-full bg-institutional/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                </div>
                            @endif

                            <div class="mt-6 min-w-0 text-center">
                                <h3 class="text-xl font-bold">{{ $member->name }}</h3>
                                <p class="mt-1 text-base font-medium text-institutional">{{ $member->role[$locale] ?? $member->role['pt'] ?? '' }}</p>
                                @if($member->cv_file && file_exists(storage_path('app/public/' . $member->cv_file)))
                                    <a
                                        href="{{ asset('storage/' . $member->cv_file) }}"
                                        target="_blank"
                                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-institutional/10 px-4 py-2 text-sm font-medium text-institutional transition-colors duration-200 hover:bg-institutional/20"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="7 10 12 15 17 10"/>
                                            <line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg>
                                        {{ __('messages.nq.team.viewCV') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif

                {{-- Fiscal Único --}}
                @if($fiscal->isNotEmpty())
                <div class="flex flex-col">
                    <h2 class="mb-8 text-center text-2xl font-bold">{{ __('messages.nq.team.fiscalUnico.title') }}</h2>
                    @foreach($fiscal as $member)
                        <div class="group flex flex-1 flex-col items-center justify-center overflow-hidden rounded-2xl border bg-card px-8 py-10 shadow-md transition-shadow duration-200 hover:shadow-xl">
                            {{-- Company logo/icon --}}
                            @if($member->photo && file_exists(storage_path('app/public/' . $member->photo)))
                                <img
                                    src="{{ asset('storage/' . $member->photo) }}"
                                    alt="{{ $member->name }}"
                                    class="h-32 w-32 shrink-0 rounded-full object-cover ring-4 ring-institutional/20"
                                />
                            @else
                                <div class="flex h-32 w-32 shrink-0 items-center justify-center rounded-full bg-institutional/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            @endif

                            <div class="mt-6 min-w-0 text-center">
                                <h3 class="text-xl font-bold">{{ $member->name }}</h3>
                                <p class="mt-1 text-base text-muted-foreground">{{ $member->role[$locale] ?? $member->role['pt'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
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
