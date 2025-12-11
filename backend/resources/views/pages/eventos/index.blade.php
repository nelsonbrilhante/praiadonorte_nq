@php
    $locale = app('laravellocalization')->getCurrentLocale();
    $isUpcoming = request('upcoming') !== '0';
    $currentEntity = request('entity');

    $query = \App\Models\Evento::query();

    if ($isUpcoming) {
        $query->where('start_date', '>=', now())->orderBy('start_date', 'asc');
    } else {
        $query->where('start_date', '<', now())->orderBy('start_date', 'desc');
    }

    if ($currentEntity) {
        $query->where('entity', $currentEntity);
    }

    $eventos = $query->paginate(12);

    // Helper function to get localized field
    $getLocalized = function($field, $locale) {
        if (is_array($field)) {
            return $field[$locale] ?? $field['pt'] ?? '';
        }
        return $field ?? '';
    };
@endphp

<x-layouts.app>
    @php

        $entityColors = [
            'praia-norte' => 'bg-ocean text-white',
            'carsurf' => 'bg-performance text-white',
            'nazare-qualifica' => 'bg-institutional text-white',
        ];

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
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ __('messages.events.title') }}</h1>
            <p class="text-xl opacity-90">{{ __('messages.events.subtitle') }}</p>
        </div>
    </section>

    {{-- Filters --}}
    <section class="border-b bg-muted/30 py-4">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center gap-4">
                {{-- Upcoming/Past filter --}}
                <div class="flex gap-2">
                    <a
                        href="{{ LaravelLocalization::localizeURL('/eventos') }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition-colors {{ $isUpcoming ? 'bg-ocean text-white' : 'bg-muted hover:bg-muted/80' }}"
                    >
                        {{ __('messages.events.upcoming') }}
                    </a>
                    <a
                        href="{{ LaravelLocalization::localizeURL('/eventos') }}?upcoming=0"
                        class="rounded-full px-4 py-2 text-sm font-medium transition-colors {{ !$isUpcoming ? 'bg-ocean text-white' : 'bg-muted hover:bg-muted/80' }}"
                    >
                        {{ __('messages.events.past') }}
                    </a>
                </div>

                <div class="h-6 w-px bg-border"></div>

                {{-- Entity filter --}}
                <div class="flex flex-wrap gap-2">
                    <a
                        href="{{ LaravelLocalization::localizeURL('/eventos') }}{{ !$isUpcoming ? '?upcoming=0' : '' }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition-colors {{ !$currentEntity ? 'bg-ocean text-white' : 'bg-muted hover:bg-muted/80' }}"
                    >
                        {{ __('messages.common.all') }}
                    </a>
                    @foreach(['praia-norte', 'carsurf', 'nazare-qualifica'] as $entity)
                        <a
                            href="{{ LaravelLocalization::localizeURL('/eventos') }}?entity={{ $entity }}{{ !$isUpcoming ? '&upcoming=0' : '' }}"
                            class="rounded-full px-4 py-2 text-sm font-medium transition-colors {{ $currentEntity === $entity ? $entityColors[$entity] : 'bg-muted hover:bg-muted/80' }}"
                        >
                            {{ $entityLabels[$entity] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Events List --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            @if($eventos->count() > 0)
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    @foreach($eventos as $evento)
                        @php
                            $startDate = $evento->start_date;
                            $day = $startDate->format('d');
                            $month = $startDate->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->shortMonthName;
                            $year = $startDate->format('Y');
                        @endphp
                        <a href="{{ LaravelLocalization::localizeURL('/eventos/' . $evento->slug) }}">
                            <x-ui.card class="flex h-full cursor-pointer overflow-hidden transition-shadow hover:shadow-lg" :noPadding="true">
                                {{-- Date badge --}}
                                <div class="flex w-32 flex-shrink-0 flex-col items-center justify-center bg-gradient-to-br from-ocean to-ocean-dark p-4 text-white">
                                    <span class="text-3xl font-bold">{{ $day }}</span>
                                    <span class="text-sm uppercase">{{ $month }}</span>
                                    <span class="text-xs opacity-75">{{ $year }}</span>
                                </div>

                                <div class="flex flex-1 flex-col">
                                    <x-ui.card-header>
                                        <div class="mb-2 flex items-center gap-2">
                                            <x-ui.badge class="{{ $entityColors[$evento->entity] ?? 'bg-muted' }}">
                                                {{ $entityLabels[$evento->entity] ?? $evento->entity }}
                                            </x-ui.badge>
                                            @if($evento->featured)
                                                <x-ui.badge variant="secondary">{{ __('messages.common.featured') }}</x-ui.badge>
                                            @endif
                                        </div>
                                        <x-ui.card-title class="line-clamp-2">
                                            {{ $getLocalized($evento->title, $locale) }}
                                        </x-ui.card-title>
                                        @if($evento->location)
                                            <x-ui.card-description class="flex items-center gap-1">
                                                <span>📍 {{ $evento->location }}</span>
                                            </x-ui.card-description>
                                        @endif
                                    </x-ui.card-header>
                                    <x-ui.card-content class="mt-auto">
                                        @if($evento->end_date)
                                            <p class="text-sm text-muted-foreground">
                                                {{ __('messages.events.startDate') }}: {{ $startDate->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}
                                                <br />
                                                {{ __('messages.events.endDate') }}: {{ $evento->end_date->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}
                                            </p>
                                        @endif
                                        @if($evento->ticket_url)
                                            <x-ui.button variant="outline" size="sm" class="mt-2">
                                                {{ __('messages.events.tickets') }}
                                            </x-ui.button>
                                        @endif
                                    </x-ui.card-content>
                                </div>
                            </x-ui.card>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center">
                    <p class="text-lg text-muted-foreground">{{ __('messages.events.noEvents') }}</p>
                </div>
            @endif

            {{-- Pagination --}}
            @if($eventos->lastPage() > 1)
                <div class="mt-8 flex justify-center gap-2">
                    {{ $eventos->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
