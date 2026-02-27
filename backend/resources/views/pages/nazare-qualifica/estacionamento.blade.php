@php
    $locale = app('laravellocalization')->getCurrentLocale();
    $content = $page->content[$locale] ?? $page->content['pt'] ?? [];
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero title="{{ $page->title[$locale] ?? $page->title['pt'] }}" subtitle="{{ __('messages.nq.services.estacionamento.shortDescription') }}" entity="nazare-qualifica" image="{{ $page->hero_image ? asset('storage/' . $page->hero_image) : '' }}" />

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

    {{-- Contact CTA --}}
    <section class="bg-institutional py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.nq.services.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.nq.services.cta.text') }}</p>
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                <x-ui.button href="tel:{{ $content['contact']['phone'] ?? __('messages.nq.contact.phone') }}" class="bg-white text-institutional hover:bg-white/90">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    {{ $content['contact']['phone'] ?? __('messages.nq.contact.phone') }}
                </x-ui.button>
                <x-ui.button href="{{ route('nq.servicos') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                    {{ __('messages.nq.services.title') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.app>
