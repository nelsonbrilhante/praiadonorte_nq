@php
    $locale = app('laravellocalization')->getCurrentLocale();

    $documents = [
        [
            'name' => __('messages.nq.contraordenacoes.documents.requerimento'),
            'description' => __('messages.nq.contraordenacoes.documents.requerimentoDesc'),
            'file' => 'requerimento.pdf',
            'icon' => 'document',
        ],
        [
            'name' => __('messages.nq.contraordenacoes.documents.defesa'),
            'description' => __('messages.nq.contraordenacoes.documents.defesaDesc'),
            'file' => 'formulario-apresentacao-defesa.pdf',
            'icon' => 'shield',
        ],
        [
            'name' => __('messages.nq.contraordenacoes.documents.reclamacao'),
            'description' => __('messages.nq.contraordenacoes.documents.reclamacaoDesc'),
            'file' => 'formulario-reclamacao.pdf',
            'icon' => 'chat',
        ],
        [
            'name' => __('messages.nq.contraordenacoes.documents.taxas1'),
            'description' => __('messages.nq.contraordenacoes.documents.taxas1Desc'),
            'file' => 'tabela-taxas-1.pdf',
            'icon' => 'table',
        ],
        [
            'name' => __('messages.nq.contraordenacoes.documents.taxas2'),
            'description' => __('messages.nq.contraordenacoes.documents.taxas2Desc'),
            'file' => 'tabela-taxas-2.pdf',
            'icon' => 'table',
        ],
        [
            'name' => __('messages.nq.contraordenacoes.documents.despacho'),
            'description' => __('messages.nq.contraordenacoes.documents.despachoDesc'),
            'file' => 'despacho-subdelegacao.pdf',
            'icon' => 'stamp',
        ],
    ];

    $icons = [
        'document' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>',
        'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'chat' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
        'table' => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/>',
        'stamp' => '<path d="M5 22h14"/><path d="M5 18h14v4H5z"/><path d="M12 2l-2 8h4l-2 8"/>',
    ];
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
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ __('messages.nq.contraordenacoes.title') }}</h1>
            <p class="mb-8 text-xl opacity-90">{{ __('messages.nq.contraordenacoes.subtitle') }}</p>
            <div class="flex flex-wrap gap-4">
                <x-ui.button href="{{ route('nq.sobre') }}" class="bg-white text-institutional hover:bg-white/90">
                    {{ __('messages.pages.about') }}
                </x-ui.button>
                <x-ui.button href="{{ route('nq.servicos') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                    {{ __('messages.nq.services.title') }}
                </x-ui.button>
            </div>
        </div>
    </section>

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

    {{-- Documents Grid --}}
    <section class="bg-muted/30 py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-8 text-center text-3xl font-bold">{{ __('messages.nq.contraordenacoes.documentsTitle') }}</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($documents as $doc)
                    <x-ui.card class="group transition-all hover:shadow-lg hover:border-institutional">
                        <x-ui.card-content class="pt-6">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-institutional/10 transition-all group-hover:bg-institutional/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    {!! $icons[$doc['icon']] !!}
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold group-hover:text-institutional">{{ $doc['name'] }}</h3>
                            <p class="mb-4 text-sm text-muted-foreground">{{ $doc['description'] }}</p>
                            <a
                                href="{{ asset('documents/nq/' . $doc['file']) }}"
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
                <x-ui.button href="{{ route('nq.servicos') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                    {{ __('messages.nq.services.title') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.app>
