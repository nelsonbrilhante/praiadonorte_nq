@php
    $locale = app('laravellocalization')->getCurrentLocale();
    $content = $page->content[$locale] ?? $page->content['pt'] ?? [];

    // Visual mappings for icons and colors
    $serviceVisuals = [
        'carsurf' => [
            'icon' => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>',
            'colorText' => 'text-surf',
            'colorBg' => 'bg-surf/10',
        ],
        'estacionamento' => [
            'icon' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
            'colorText' => 'text-institutional',
            'colorBg' => 'bg-institutional/10',
        ],
        'forte' => [
            'icon' => '<path d="M3 21h18M5 21V7l8-4 8 4v14M9 21v-6h6v6"/>',
            'colorText' => 'text-amber-600',
            'colorBg' => 'bg-amber-600/10',
        ],
        'ale' => [
            'icon' => '<path d="M2 20h.01M7 20v-4M12 20v-8M17 20V8M22 4v16"/>',
            'colorText' => 'text-green-600',
            'colorBg' => 'bg-green-600/10',
        ],
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
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ $page->title[$locale] ?? $page->title['pt'] }}</h1>
            <p class="text-xl opacity-90">{{ __('messages.nq.services.subtitle') }}</p>
        </div>
    </section>

    {{-- Services Grid --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                @if(!empty($content['services']))
                    @foreach($content['services'] as $service)
                        @php
                            $slug = $service['slug'] ?? '';
                            $visual = $serviceVisuals[$slug] ?? $serviceVisuals['carsurf'];
                        @endphp
                        <a href="{{ route('nq.' . $slug) }}" class="group">
                            <x-ui.card class="h-full overflow-hidden transition-all hover:shadow-xl hover:border-institutional">
                                <x-ui.card-header class="pb-4">
                                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-xl {{ $visual['colorBg'] }} transition-all group-hover:scale-110">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 {{ $visual['colorText'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            {!! $visual['icon'] !!}
                                        </svg>
                                    </div>
                                    <x-ui.card-title class="text-2xl group-hover:text-institutional transition-colors">
                                        {{ $service['title'] ?? '' }}
                                    </x-ui.card-title>
                                    <x-ui.card-description class="text-base">
                                        {{ $service['shortDescription'] ?? '' }}
                                    </x-ui.card-description>
                                </x-ui.card-header>
                                <x-ui.card-content>
                                    <p class="mb-4 text-muted-foreground">
                                        {{ __("messages.nq.services.{$slug}.description") }}
                                    </p>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach(__("messages.nq.services.{$slug}.features") as $feature)
                                            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $visual['colorText'] }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                                {{ $feature }}
                                            </div>
                                        @endforeach
                                    </div>
                                </x-ui.card-content>
                                <x-ui.card-footer class="pt-0">
                                    <x-ui.button variant="outline" class="w-full group-hover:bg-institutional group-hover:text-white group-hover:border-institutional transition-all">
                                        {{ __('messages.common.learnMore') }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                            <polyline points="12 5 19 12 12 19"/>
                                        </svg>
                                    </x-ui.button>
                                </x-ui.card-footer>
                            </x-ui.card>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    {{-- Contact CTA --}}
    <section class="bg-muted/30 py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="mb-4 text-2xl font-bold">{{ __('messages.nq.services.cta.title') }}</h2>
                <p class="mb-6 text-muted-foreground">{{ __('messages.nq.services.cta.text') }}</p>
                <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
                    <x-ui.button href="tel:{{ __('messages.nq.contact.phone') }}" variant="outline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        {{ __('messages.nq.contact.phone') }}
                    </x-ui.button>
                    <x-ui.button href="mailto:{{ __('messages.nq.contact.email') }}" variant="outline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        {{ __('messages.nq.contact.email') }}
                    </x-ui.button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
