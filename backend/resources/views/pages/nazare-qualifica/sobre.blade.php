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
            <p class="mb-8 text-xl opacity-90">{{ __('messages.nq.about.subtitle') }}</p>
            <div class="flex flex-wrap gap-4">
                <x-ui.button href="{{ route('nq.equipa') }}" class="bg-white text-institutional hover:bg-white/90">
                    {{ __('messages.nq.team.title') }}
                </x-ui.button>
                <x-ui.button href="{{ route('nq.contraordenacoes') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                    {{ __('messages.nq.contraordenacoes.title') }}
                </x-ui.button>
                <x-ui.button href="{{ route('nq.servicos') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                    {{ __('messages.nq.services.title') }}
                </x-ui.button>
            </div>
        </div>
    </section>

    {{-- Intro Section --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-center">
                {{-- Logo --}}
                <div class="lg:col-span-4 flex justify-center">
                    <img
                        src="{{ asset('images/logos/imagem-grafica-nq-vertical-elements.svg') }}"
                        alt="Nazaré Qualifica - Empresa Municipal"
                        class="h-48 w-auto md:h-64 lg:h-72"
                    />
                </div>
                {{-- Text --}}
                <div class="lg:col-span-8">
                    <div class="prose max-w-none">
                        <h2>{{ $content['intro']['title'] ?? __('messages.nq.about.intro.title') }}</h2>
                        <p class="text-lg text-muted-foreground">{{ $content['intro']['text'] ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Objectives Section --}}
    @if(!empty($content['objectives']))
    <section class="bg-muted/30 py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-8 text-center text-3xl font-bold">{{ __('messages.nq.about.objectives.title') }}</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @php
                    $iconPaths = [
                        'waves' => '<path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path d="M9 10h.01M15 10h.01M9.5 15a3.5 3.5 0 005 0"/>',
                        'car' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
                        'landmark' => '<path d="M3 21h18M5 21V7l8-4 8 4v14M9 21v-6h6v6"/>',
                        'factory' => '<path d="M2 20h.01M7 20v-4M12 20v-8M17 20V8M22 4v16"/>',
                        'target' => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
                        'building' => '<path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
                        'users' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>',
                        'briefcase' => '<rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>',
                    ];
                @endphp
                @foreach($content['objectives'] as $index => $objective)
                    <x-ui.card class="text-center">
                        <x-ui.card-content class="pt-6">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-institutional/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    {!! $iconPaths[$objective['icon'] ?? 'target'] ?? $iconPaths['target'] !!}
                                </svg>
                            </div>
                            <h3 class="mb-2 text-lg font-semibold">{{ $objective['title'] ?? '' }}</h3>
                            <p class="text-muted-foreground">{{ $objective['description'] ?? '' }}</p>
                        </x-ui.card-content>
                    </x-ui.card>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Quick Links to Services --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-8 text-center text-3xl font-bold">{{ __('messages.nq.services.title') }}</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @php
                    $services = ['carsurf', 'estacionamento', 'forte', 'ale'];
                    $serviceIcons = [
                        'carsurf' => '<path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path d="M9 10h.01M15 10h.01M9.5 15a3.5 3.5 0 005 0"/>',
                        'estacionamento' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
                        'forte' => '<path d="M3 21h18M5 21V7l8-4 8 4v14M9 21v-6h6v6"/>',
                        'ale' => '<path d="M2 20h.01M7 20v-4M12 20v-8M17 20V8M22 4v16"/>',
                    ];
                @endphp
                @foreach($services as $service)
                    <a href="{{ route('nq.' . $service) }}" class="group">
                        <x-ui.card class="h-full transition-all hover:shadow-lg hover:border-institutional">
                            <x-ui.card-content class="pt-6 text-center">
                                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-institutional/10 transition-all group-hover:bg-institutional/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-institutional" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        {!! $serviceIcons[$service] !!}
                                    </svg>
                                </div>
                                <h3 class="mb-2 font-semibold group-hover:text-institutional">{{ __("messages.nq.services.{$service}.title") }}</h3>
                                <p class="text-sm text-muted-foreground">{{ __("messages.nq.services.{$service}.shortDescription") }}</p>
                            </x-ui.card-content>
                        </x-ui.card>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="bg-institutional py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ $content['cta']['title'] ?? __('messages.nq.about.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ $content['cta']['subtitle'] ?? __('messages.nq.about.cta.subtitle') }}</p>
            <x-ui.button href="{{ route('nq.servicos') }}" class="bg-white text-institutional hover:bg-white/90">
                {{ __('messages.nq.about.cta.button') }}
            </x-ui.button>
        </div>
    </section>
</x-layouts.app>
