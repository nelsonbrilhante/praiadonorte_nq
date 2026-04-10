@php
    $locale = app('laravellocalization')->getCurrentLocale();

    $icons = [
        'document' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>',
        'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'chat' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
        'table' => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/>',
        'stamp' => '<path d="M5 22h14"/><path d="M5 18h14v4H5z"/><path d="M12 2l-2 8h4l-2 8"/>',
    ];
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero title="{{ __('messages.nq.contraordenacoes.title') }}" subtitle="{{ __('messages.nq.contraordenacoes.subtitle') }}" entity="nazare-qualifica">
        <div class="flex flex-wrap gap-4">
            <x-ui.button href="{{ route('nq.sobre') }}" class="bg-white text-institutional hover:bg-white/90">
                {{ __('messages.pages.about') }}
            </x-ui.button>
            <x-ui.button href="{{ route('nq.sobre') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                {{ __('messages.nq.services.title') }}
            </x-ui.button>
        </div>
    </x-praia-norte.page-hero>

    {{-- Introduction --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="mb-6 text-3xl font-bold">{{ __('messages.nq.contraordenacoes.intro.title') }}</h2>
                <p class="text-lg text-muted-foreground">
                    {{ __('messages.nq.contraordenacoes.intro.text') }}
                </p>
            </div>
        </div>
    </section>

    {{-- Online Forms --}}
    <section class="bg-muted/10 py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-8 text-center text-3xl font-bold">{{ __('messages.nq.contraordenacoes.onlineForms') }}</h2>
            <div class="mx-auto grid max-w-4xl grid-cols-1 gap-6 md:grid-cols-2">
                {{-- Identificação de Condutor --}}
                <a href="{{ route('nq.identificacao-condutor') }}" class="group block rounded-xl border-2 border-institutional/20 bg-institutional/5 p-6 transition-all hover:border-institutional hover:bg-institutional/10 hover:shadow-lg">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-institutional/15 transition-all group-hover:bg-institutional/25">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3 class="mb-2 text-xl font-bold group-hover:text-institutional">{{ __('messages.nq.contraordenacoes.identificacaoCondutor.title') }}</h3>
                    <p class="mb-4 text-sm text-muted-foreground">{{ __('messages.nq.contraordenacoes.identificacaoCondutor.cardDesc') }}</p>
                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-institutional">
                        {{ __('messages.common.learnMore') }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </span>
                </a>

                {{-- Apresentação de Defesa --}}
                <a href="{{ route('nq.apresentacao-defesa') }}" class="group block rounded-xl border-2 border-institutional/20 bg-institutional/5 p-6 transition-all hover:border-institutional hover:bg-institutional/10 hover:shadow-lg">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-institutional/15 transition-all group-hover:bg-institutional/25">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h3 class="mb-2 text-xl font-bold group-hover:text-institutional">{{ __('messages.nq.contraordenacoes.apresentacaoDefesa.title') }}</h3>
                    <p class="mb-4 text-sm text-muted-foreground">{{ __('messages.nq.contraordenacoes.apresentacaoDefesa.cardDesc') }}</p>
                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-institutional">
                        {{ __('messages.common.learnMore') }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </section>

    {{-- Documents Grid --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-8 text-center text-3xl font-bold">{{ __('messages.nq.contraordenacoes.documentsTitle') }}</h2>
            @if($documents->isEmpty())
                <p class="text-center text-muted-foreground">{{ __('messages.common.noResults') }}</p>
            @else
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($documents as $doc)
                        <x-ui.card class="group transition-colors hover:border-institutional">
                            <x-ui.card-content class="pt-6">
                                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-institutional/10 transition-all group-hover:bg-institutional/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        {!! $icons[$doc->icon ?? 'document'] !!}
                                    </svg>
                                </div>
                                <h3 class="mb-2 text-lg font-semibold group-hover:text-institutional">{{ $doc->title[$locale] ?? $doc->title['pt'] }}</h3>
                                <p class="mb-4 text-sm text-muted-foreground">{{ $doc->description[$locale] ?? $doc->description['pt'] ?? '' }}</p>
                                <a
                                    href="{{ asset('storage/' . $doc->file) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 text-sm font-medium text-institutional hover:underline"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="7 10 12 15 17 10"/>
                                        <line x1="12" y1="15" x2="12" y2="3"/>
                                    </svg>
                                    {{ __('messages.nq.contraordenacoes.download') }}
                                </a>
                            </x-ui.card-content>
                        </x-ui.card>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Contact Info --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-2xl">
                <x-ui.card>
                    <x-ui.card-content class="pt-6">
                        <h3 class="mb-4 text-xl font-bold">{{ __('messages.nq.contraordenacoes.contact.title') }}</h3>
                        <p class="mb-6 text-muted-foreground">{{ __('messages.nq.contraordenacoes.contact.text') }}</p>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-institutional/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Email</p>
                                    <a href="mailto:geral@nazarequalifica.pt" class="font-medium text-institutional hover:underline">geral@nazarequalifica.pt</a>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-institutional/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">{{ __('messages.nq.contact.phone') }}</p>
                                    <a href="tel:+351262550010" class="font-medium text-institutional hover:underline">+351 262 550 010</a>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-institutional/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">{{ __('messages.nq.contact.address') }}</p>
                                    <p class="font-medium">Rua da Praia do Norte, Centro de Alto Rendimento de Surf, 2450-504 Nazaré</p>
                                </div>
                            </div>
                        </div>
                    </x-ui.card-content>
                </x-ui.card>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-institutional py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.nq.contraordenacoes.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.nq.contraordenacoes.cta.subtitle') }}</p>
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <x-ui.button href="{{ route('nq.sobre') }}" class="bg-white text-institutional hover:bg-white/90">
                    {{ __('messages.pages.about') }}
                </x-ui.button>
                <x-ui.button href="{{ route('nq.sobre') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                    {{ __('messages.nq.services.title') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.app>
