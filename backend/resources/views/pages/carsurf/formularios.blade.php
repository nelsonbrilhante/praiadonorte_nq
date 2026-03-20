@php
    $locale = app('laravellocalization')->getCurrentLocale();

    $documents = [
        [
            'name' => __('messages.carsurf.formularios.documents.pt'),
            'description' => __('messages.carsurf.formularios.documents.ptDesc'),
            'file' => 'formulario-cedencia-ginasio-pt.pdf',
            'icon' => 'document',
        ],
        [
            'name' => __('messages.carsurf.formularios.documents.en'),
            'description' => __('messages.carsurf.formularios.documents.enDesc'),
            'file' => 'formulario-cedencia-ginasio-en.pdf',
            'icon' => 'globe',
        ],
        [
            'name' => __('messages.carsurf.formularios.documents.fr'),
            'description' => __('messages.carsurf.formularios.documents.frDesc'),
            'file' => 'formulario-cedencia-ginasio-fr.pdf',
            'icon' => 'globe',
        ],
        [
            'name' => __('messages.carsurf.formularios.documents.es'),
            'description' => __('messages.carsurf.formularios.documents.esDesc'),
            'file' => 'formulario-cedencia-ginasio-es.pdf',
            'icon' => 'globe',
        ],
    ];

    $icons = [
        'document' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>',
        'globe' => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
    ];
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero
        title="{{ __('messages.carsurf.formularios.title') }}"
        subtitle="{{ __('messages.carsurf.formularios.subtitle') }}"
        entity="carsurf"
    />

    {{-- Introduction --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="mb-6 text-3xl font-bold">{{ __('messages.carsurf.formularios.intro.title') }}</h2>
                <p class="text-lg text-muted-foreground">
                    {{ __('messages.carsurf.formularios.intro.text') }}
                </p>
            </div>
        </div>
    </section>

    {{-- Documents Grid --}}
    <section class="bg-muted/10 py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-8 text-center text-3xl font-bold">{{ __('messages.carsurf.formularios.documentsTitle') }}</h2>
            <div class="mx-auto grid max-w-4xl grid-cols-1 gap-6 md:grid-cols-2">
                @foreach($documents as $doc)
                    <x-ui.card class="group transition-colors hover:border-performance">
                        <x-ui.card-content class="pt-6">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-performance/10 transition-all group-hover:bg-performance/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-performance" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    {!! $icons[$doc['icon']] !!}
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold group-hover:text-performance">{{ $doc['name'] }}</h3>
                            <p class="mb-4 text-sm text-muted-foreground">{{ $doc['description'] }}</p>
                            <a
                                href="{{ asset('documents/carsurf/' . $doc['file']) }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 text-sm font-medium text-performance hover:underline"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                {{ __('messages.carsurf.formularios.download') }}
                            </a>
                        </x-ui.card-content>
                    </x-ui.card>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="bg-performance py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.carsurf.formularios.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.carsurf.formularios.cta.subtitle') }}</p>
            <x-ui.button href="{{ route('contacto') }}" class="bg-white text-performance hover:bg-white/90">
                {{ __('messages.carsurf.formularios.cta.button') }}
            </x-ui.button>
        </div>
    </section>
</x-layouts.app>
