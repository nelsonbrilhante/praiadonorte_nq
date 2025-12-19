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
    <section class="bg-gradient-to-r from-green-600 to-green-700 py-16 text-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 20h.01M7 20v-4M12 20v-8M17 20V8M22 4v16"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-bold md:text-5xl">{{ $page->title[$locale] ?? $page->title['pt'] }}</h1>
                    <p class="text-xl opacity-90">{{ __('messages.nq.services.ale.shortDescription') }}</p>
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
                            {{ $content['description'] ?? __('messages.nq.services.ale.description') }}
                        </p>
                    </div>
                    <div class="rounded-lg bg-green-50 p-6 border border-green-200">
                        <h3 class="mb-4 font-semibold text-green-900">{{ __('messages.carsurf.facilities.title') }}</h3>
                        <ul class="space-y-3">
                            @foreach($content['features'] ?? __('messages.nq.services.ale.features') as $feature)
                                <li class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                    <span class="text-green-900">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Key Numbers --}}
    @if(!empty($content['stats']))
    <section class="bg-muted/30 py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl text-center">
                <h2 class="mb-6 text-2xl font-bold">Números Chave</h2>
                <div class="grid grid-cols-2 gap-6 md:grid-cols-{{ count($content['stats']) }}">
                    @foreach($content['stats'] as $stat)
                        <div class="rounded-lg bg-white p-4 shadow-sm">
                            <div class="text-3xl font-bold text-green-600">{{ $stat['value'] ?? '' }}</div>
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
                <h2 class="mb-6 text-2xl font-bold">Números Chave</h2>
                <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <div class="text-3xl font-bold text-green-600">30</div>
                        <div class="text-sm text-muted-foreground">{{ __('messages.nq.services.ale.features.area') }}</div>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <div class="text-3xl font-bold text-green-600">34</div>
                        <div class="text-sm text-muted-foreground">{{ __('messages.nq.services.ale.features.lots') }}</div>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <div class="flex items-center justify-center text-3xl font-bold text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div class="text-sm text-muted-foreground">{{ __('messages.nq.services.ale.features.location') }}</div>
                    </div>
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <div class="flex items-center justify-center text-3xl font-bold text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <line x1="3" y1="9" x2="21" y2="9"/>
                                <line x1="9" y1="21" x2="9" y2="9"/>
                            </svg>
                        </div>
                        <div class="text-sm text-muted-foreground">{{ __('messages.nq.services.ale.features.infrastructure') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Investment Opportunity --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl text-center">
                <div class="mb-6 flex justify-center">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-green-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"/>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                </div>
                <h2 class="mb-4 text-2xl font-bold">Oportunidade de Investimento</h2>
                <p class="text-muted-foreground">
                    A ALE de Valado dos Frades representa uma oportunidade estratégica para empresas que procuram estabelecer-se no concelho da Nazaré. Com acesso a vias de comunicação importantes e infraestruturas modernas, é o local ideal para o seu negócio crescer.
                </p>
            </div>
        </div>
    </section>

    {{-- Contact CTA --}}
    <section class="bg-green-600 py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.nq.services.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.nq.services.cta.text') }}</p>
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <x-ui.button href="tel:{{ $content['contact']['phone'] ?? __('messages.nq.contact.phone') }}" class="bg-white text-green-600 hover:bg-white/90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    {{ $content['contact']['phone'] ?? __('messages.nq.contact.phone') }}
                </x-ui.button>
                <x-ui.button href="mailto:{{ $content['contact']['email'] ?? __('messages.nq.contact.email') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    {{ $content['contact']['email'] ?? __('messages.nq.contact.email') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.app>
