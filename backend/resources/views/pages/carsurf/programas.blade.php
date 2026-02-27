@php
    $locale = app('laravellocalization')->getCurrentLocale();
    $pagina = \App\Models\Pagina::where('entity', 'carsurf')->where('slug', 'programas')->first();
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero
        title="{{ __('messages.carsurf.programs.title') }}"
        subtitle="{{ __('messages.carsurf.programs.subtitle') }}"
        entity="carsurf"
        image="{{ $pagina?->hero_image ? asset('storage/' . $pagina->hero_image) : '' }}"
    />

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
