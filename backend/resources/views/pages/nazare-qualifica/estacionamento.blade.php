@php
    $locale = app('laravellocalization')->getCurrentLocale();
    $content = ($page->content[$locale] ?? null) ?: ($page->content['pt'] ?? []);
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero title="{{ ($page->title[$locale] ?? null) ?: ($page->title['pt'] ?? '') }}" subtitle="{{ __('messages.nq.services.estacionamento.shortDescription') }}" entity="nazare-qualifica" image="{{ $page->hero_image ? asset('storage/' . $page->hero_image) : '' }}" />

    {{-- Description --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-4xl">
                <div class="grid gap-8 md:grid-cols-2">
                    <div>
                        <h2 class="mb-4 text-2xl font-bold">{{ __('messages.nq.about.intro.title') }}</h2>
                        <p class="text-lg text-muted-foreground">
                            {{ $content['description'] ?? __('messages.nq.services.estacionamento.description') }}
                        </p>
                    </div>
                    <div class="rounded-lg bg-muted/10 p-6">
                        <h3 class="mb-4 font-semibold">{{ __('messages.carsurf.facilities.title') }}</h3>
                        <ul class="space-y-3">
                            @foreach($content['features'] ?? __('messages.nq.services.estacionamento.features') as $feature)
                                <li class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

    {{-- Stats (if available) --}}
    @if(!empty($content['stats']))
    <section class="bg-muted/10 py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="mb-6 text-2xl font-bold">{{ __('messages.about.location.title') }}</h2>
                <div class="grid grid-cols-2 gap-6 md:grid-cols-{{ count($content['stats']) }}">
                    @foreach($content['stats'] as $stat)
                        <div class="rounded-lg bg-white p-4 border">
                            <div class="text-3xl font-bold text-institutional">{{ $stat['value'] ?? '' }}</div>
                            <div class="text-sm text-muted-foreground">{{ $stat['label'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @else
    {{-- Location Info --}}
    <section class="bg-muted/10 py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="mb-4 text-2xl font-bold">{{ __('messages.about.location.title') }}</h2>
                <div class="rounded-lg bg-white p-6 border">
                    <div class="flex items-center justify-center gap-3 text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span>Largo Cândido dos Reis, Nazaré</span>
                    </div>
                    <p class="mt-4 text-muted-foreground">
                        {{ __('messages.nq.services.estacionamento.features.location') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Documents --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-8 text-center text-2xl font-bold">{{ __('messages.nq.services.estacionamento.documents.title') }}</h2>
            <div class="mx-auto max-w-sm">
                <x-ui.card class="group transition-colors hover:border-institutional">
                    <x-ui.card-content class="pt-6">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-institutional/10 transition-all group-hover:bg-institutional/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-semibold group-hover:text-institutional">{{ __('messages.nq.services.estacionamento.documents.regulamento') }}</h3>
                        <p class="mb-4 text-sm text-muted-foreground">{{ __('messages.nq.services.estacionamento.documents.regulamentoDesc') }}</p>
                        @php
                            $regulamentoUrl = !empty($page->content['documents']['regulamento'])
                                ? asset('storage/' . $page->content['documents']['regulamento'])
                                : asset('documents/nq/regulamento-parque.pdf');
                        @endphp
                        <a href="{{ $regulamentoUrl }}" target="_blank" class="inline-flex items-center gap-2 text-sm font-medium text-institutional hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            {{ __('messages.nq.contraordenacoes.download') }}
                        </a>
                    </x-ui.card-content>
                </x-ui.card>
            </div>
        </div>
    </section>

    {{-- Map --}}
    <section class="border-t">
        <div class="container mx-auto px-4 py-8">
            <h2 class="mb-4 text-center text-2xl font-bold">{{ __('messages.nq.services.estacionamento.directions') }}</h2>
        </div>
        <div class="aspect-[21/9] w-full bg-muted">
            <iframe
                src="https://www.openstreetmap.org/export/embed.html?bbox=-9.0743%2C39.5948%2C-9.0673%2C39.5998&layer=mapnik&marker=39.5973%2C-9.0708"
                width="100%"
                height="100%"
                style="border:0;"
                loading="lazy"
            ></iframe>
        </div>
    </section>

    {{-- Contact CTA --}}
    <section class="bg-institutional py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.nq.services.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.nq.services.cta.text') }}</p>
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <x-ui.button href="tel:{{ __('messages.nq.services.estacionamento.contact.phone') }}" class="bg-white text-institutional hover:bg-white/90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    {{ __('messages.nq.services.estacionamento.contact.phone') }}
                </x-ui.button>
                <x-ui.button href="tel:+351{{ str_replace(' ', '', __('messages.nq.services.estacionamento.contact.mobile')) }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    {{ __('messages.nq.services.estacionamento.contact.mobile') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.app>
