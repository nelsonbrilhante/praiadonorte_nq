@php
    $locale = app('laravellocalization')->getCurrentLocale();
@endphp

<x-layouts.app>
    {{-- Breadcrumbs --}}
    <div class="border-b bg-muted/30">
        <div class="container mx-auto px-4">
            <x-ui.breadcrumbs />
        </div>
    </div>

    {{-- Header --}}
    <section class="gradient-performance py-16 text-white">
        <div class="container mx-auto px-4">
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ __('messages.carsurf.programs.title') }}</h1>
            <p class="text-xl opacity-90">{{ __('messages.carsurf.programs.subtitle') }}</p>
        </div>
    </section>

    {{-- Programs Grid --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @for($i = 1; $i <= 6; $i++)
                    <x-ui.card class="overflow-hidden" :noPadding="true">
                        <div class="aspect-video bg-gradient-to-br from-performance/20 to-performance/5"></div>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __("messages.carsurf.programs.program{$i}.title") }}</x-ui.card-title>
                            <x-ui.card-description>{{ __("messages.carsurf.programs.program{$i}.description") }}</x-ui.card-description>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-performance">{{ __("messages.carsurf.programs.program{$i}.price") }}</span>
                                <x-ui.button variant="outline" size="sm">
                                    {{ __('messages.common.learnMore') }}
                                </x-ui.button>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                @endfor
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="bg-performance py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.carsurf.programs.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.carsurf.programs.cta.subtitle') }}</p>
            <x-ui.button href="{{ route('contacto') }}" class="bg-white text-performance hover:bg-white/90">
                {{ __('messages.carsurf.programs.cta.button') }}
            </x-ui.button>
        </div>
    </section>
</x-layouts.app>
