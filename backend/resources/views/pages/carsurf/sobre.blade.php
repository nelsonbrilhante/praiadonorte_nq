@php
    $locale = app('laravellocalization')->getCurrentLocale();
    $pagina = \App\Models\Pagina::where('entity', 'carsurf')->where('slug', 'sobre')->first();
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero
        title="{{ __('messages.carsurf.about.pageTitle') }}"
        subtitle="{{ __('messages.carsurf.about.pageSubtitle') }}"
        entity="carsurf"
        image="{{ $pagina?->hero_image ? asset('storage/' . $pagina->hero_image) : '' }}"
    />

    {{-- Content --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl">
                <div class="prose max-w-none">
                    <h2>{{ __('messages.carsurf.about.mission.title') }}</h2>
                    <p>{{ __('messages.carsurf.about.mission.text') }}</p>

                    <h2>{{ __('messages.carsurf.about.history.title') }}</h2>
                    <p>{{ __('messages.carsurf.about.history.text') }}</p>

                    <h2>{{ __('messages.carsurf.about.values.title') }}</h2>
                    <ul>
                        <li>{{ __('messages.carsurf.about.values.item1') }}</li>
                        <li>{{ __('messages.carsurf.about.values.item2') }}</li>
                        <li>{{ __('messages.carsurf.about.values.item3') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Team Section --}}
    <section class="bg-muted/10 py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-8 text-center text-3xl font-bold">{{ __('messages.carsurf.team.title') }}</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @for($i = 1; $i <= 4; $i++)
                    <x-ui.card class="overflow-hidden" :noPadding="true">
                        <div class="aspect-square bg-gradient-to-br from-performance/20 to-performance/5"></div>
                        <x-ui.card-content class="p-4 text-center">
                            <h3 class="font-semibold">{{ __("messages.carsurf.team.member{$i}.name") }}</h3>
                            <p class="text-sm text-muted-foreground">{{ __("messages.carsurf.team.member{$i}.role") }}</p>
                        </x-ui.card-content>
                    </x-ui.card>
                @endfor
            </div>
        </div>
    </section>
</x-layouts.app>
