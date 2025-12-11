@php
    $locale = app('laravellocalization')->getCurrentLocale();

    $evento = \App\Models\Evento::where('slug', $slug)->firstOrFail();

    // Get related events (same entity)
    $related = \App\Models\Evento::where('entity', $evento->entity)
        ->where('id', '!=', $evento->id)
        ->where('start_date', '>=', now())
        ->orderBy('start_date', 'asc')
        ->limit(3)
        ->get();

    // Helper function to get localized field
    $getLocalized = function($field, $locale) {
        if (is_array($field)) {
            return $field[$locale] ?? $field['pt'] ?? '';
        }
        return $field ?? '';
    };

    $entityColors = [
        'praia-norte' => 'bg-ocean text-white',
        'carsurf' => 'bg-performance text-white',
        'nazare-qualifica' => 'bg-institutional text-white',
    ];
@endphp

<x-layouts.app>
    @php

        $entityLabels = [
            'praia-norte' => __('messages.entities.praiaDoNorte'),
            'carsurf' => __('messages.entities.carsurf'),
            'nazare-qualifica' => __('messages.entities.nazareQualifica'),
        ];
    @endphp

    {{-- Breadcrumbs --}}
    <div class="border-b bg-muted/30">
        <div class="container mx-auto px-4">
            <x-ui.breadcrumbs />
        </div>
    </div>

    {{-- Header --}}
    <section class="gradient-ocean py-16 text-white">
        <div class="container mx-auto px-4">
            <div class="mb-4 flex items-center gap-2">
                <x-ui.badge class="{{ $entityColors[$evento->entity] ?? 'bg-muted' }}">
                    {{ $entityLabels[$evento->entity] ?? $evento->entity }}
                </x-ui.badge>
                @if($evento->featured)
                    <x-ui.badge variant="secondary">{{ __('messages.common.featured') }}</x-ui.badge>
                @endif
            </div>
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ $getLocalized($evento->title, $locale) }}</h1>
            <div class="flex flex-wrap items-center gap-4 text-lg opacity-90">
                <span>{{ $evento->start_date->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
                @if($evento->end_date)
                    <span>-</span>
                    <span>{{ $evento->end_date->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
                @endif
                @if($evento->location)
                    <span>|</span>
                    <span>{{ $evento->location }}</span>
                @endif
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                {{-- Main Content --}}
                <div class="lg:col-span-2">
                    @if($evento->image)
                        <img
                            src="{{ asset('storage/' . $evento->image) }}"
                            alt="{{ $getLocalized($evento->title, $locale) }}"
                            class="mb-8 w-full rounded-lg object-cover"
                        />
                    @endif

                    <div class="prose max-w-none">
                        {!! $getLocalized($evento->description, $locale) !!}
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Event Details Card --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title>{{ __('messages.events.details') }}</x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">{{ __('messages.events.startDate') }}</p>
                                <p>{{ $evento->start_date->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}</p>
                            </div>
                            @if($evento->end_date)
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">{{ __('messages.events.endDate') }}</p>
                                    <p>{{ $evento->end_date->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}</p>
                                </div>
                            @endif
                            @if($evento->location)
                                <div>
                                    <p class="text-sm font-medium text-muted-foreground">{{ __('messages.events.location') }}</p>
                                    <p>{{ $evento->location }}</p>
                                </div>
                            @endif
                            @if($evento->ticket_url)
                                <x-ui.button href="{{ $evento->ticket_url }}" target="_blank" class="w-full">
                                    {{ __('messages.events.getTickets') }}
                                </x-ui.button>
                            @endif
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- Related Events --}}
                    @if($related->count() > 0)
                        <x-ui.card>
                            <x-ui.card-header>
                                <x-ui.card-title>{{ __('messages.events.relatedEvents') }}</x-ui.card-title>
                            </x-ui.card-header>
                            <x-ui.card-content class="space-y-4">
                                @foreach($related as $relatedEvento)
                                    <a href="{{ LaravelLocalization::localizeURL('/eventos/' . $relatedEvento->slug) }}" class="block hover:bg-muted/50 rounded-lg p-2 -mx-2 transition-colors">
                                        <p class="font-medium">{{ $getLocalized($relatedEvento->title, $locale) }}</p>
                                        <p class="text-sm text-muted-foreground">
                                            {{ $relatedEvento->start_date->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}
                                        </p>
                                    </a>
                                @endforeach
                            </x-ui.card-content>
                        </x-ui.card>
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
