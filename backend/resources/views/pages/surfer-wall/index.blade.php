@php
    $locale = app('laravellocalization')->getCurrentLocale();

    $surfers = \App\Models\Surfer::orderBy('featured', 'desc')->orderBy('name', 'asc')->get();
    $featuredSurfers = $surfers->where('featured', true);
    $regularSurfers = $surfers->where('featured', false);

    // Helper function to get localized field
    $getLocalized = function($field, $locale) {
        if (is_array($field)) {
            return $field[$locale] ?? $field['pt'] ?? '';
        }
        return $field ?? '';
    };

    // Helper function to strip HTML tags
    $stripHtml = function($html) {
        return trim(str_replace('&nbsp;', ' ', strip_tags($html)));
    };
@endphp

<x-layouts.app>

    {{-- Breadcrumbs --}}
    <div class="border-b bg-muted/30">
        <div class="container mx-auto px-4">
            <x-ui.breadcrumbs />
        </div>
    </div>

    {{-- Header --}}
    <section class="gradient-ocean py-16 text-white">
        <div class="container mx-auto px-4">
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ __('messages.surfers.title') }}</h1>
            <p class="text-xl opacity-90">{{ __('messages.surfers.subtitle') }}</p>
        </div>
    </section>

    {{-- Featured Surfers --}}
    @if($featuredSurfers->count() > 0)
        <section class="py-12">
            <div class="container mx-auto px-4">
                <h2 class="mb-8 text-2xl font-bold">{{ __('messages.surfers.featuredSurfers') }}</h2>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($featuredSurfers as $surfer)
                        <a href="{{ LaravelLocalization::localizeURL('/surfer-wall/' . $surfer->slug) }}">
                            <x-ui.card class="group h-full cursor-pointer overflow-hidden transition-all hover:shadow-lg border-2 border-ocean" :noPadding="true">
                                {{-- Photo --}}
                                <div class="relative aspect-[3/4]">
                                    @if($surfer->photo)
                                        <img
                                            src="{{ asset('storage/' . $surfer->photo) }}"
                                            alt="{{ $surfer->name }}"
                                            class="w-full h-full object-cover transition-transform group-hover:scale-105"
                                        />
                                    @else
                                        <div class="h-full w-full bg-gradient-to-br from-ocean/20 to-ocean/5"></div>
                                    @endif
                                    <x-ui.badge class="absolute left-2 top-2 z-10 bg-ocean text-white">
                                        {{ __('messages.common.featured') }}
                                    </x-ui.badge>
                                    {{-- Gradient overlay on hover --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>
                                </div>

                                <x-ui.card-content class="p-4 space-y-2">
                                    <h3 class="font-semibold text-xl">{{ $surfer->name }}</h3>
                                    @if($surfer->nationality)
                                        <p class="text-sm text-muted-foreground">🌍 {{ $surfer->nationality }}</p>
                                    @endif
                                    @if($surfer->bio)
                                        <p class="line-clamp-2 text-sm text-muted-foreground">
                                            {{ $stripHtml($getLocalized($surfer->bio, $locale)) }}
                                        </p>
                                    @endif
                                    @if($surfer->achievements && is_array($surfer->achievements) && count($surfer->achievements) > 0)
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            @foreach(array_slice($surfer->achievements, 0, 2) as $achievement)
                                                <x-ui.badge variant="secondary" class="text-xs">
                                                    {{ $getLocalized($achievement, $locale) }}
                                                </x-ui.badge>
                                            @endforeach
                                            @if(count($surfer->achievements) > 2)
                                                <x-ui.badge variant="outline" class="text-xs">
                                                    +{{ count($surfer->achievements) - 2 }}
                                                </x-ui.badge>
                                            @endif
                                        </div>
                                    @endif
                                </x-ui.card-content>
                            </x-ui.card>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- All Surfers --}}
    <section class="py-12 {{ $featuredSurfers->count() > 0 ? 'bg-muted/30' : '' }}">
        <div class="container mx-auto px-4">
            @if($featuredSurfers->count() > 0 && $regularSurfers->count() > 0)
                <h2 class="mb-8 text-2xl font-bold">{{ __('messages.surfers.allSurfers') }}</h2>
            @endif

            @if($surfers->count() > 0)
                <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                    @foreach($featuredSurfers->count() > 0 ? $regularSurfers : $surfers as $surfer)
                        <a href="{{ LaravelLocalization::localizeURL('/surfer-wall/' . $surfer->slug) }}">
                            <x-ui.card class="group h-full cursor-pointer overflow-hidden transition-all hover:shadow-lg" :noPadding="true">
                                {{-- Photo --}}
                                <div class="relative aspect-square">
                                    @if($surfer->photo)
                                        <img
                                            src="{{ asset('storage/' . $surfer->photo) }}"
                                            alt="{{ $surfer->name }}"
                                            class="w-full h-full object-cover transition-transform group-hover:scale-105"
                                        />
                                    @else
                                        <div class="h-full w-full bg-gradient-to-br from-ocean/20 to-ocean/5"></div>
                                    @endif
                                    {{-- Gradient overlay on hover --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>
                                </div>

                                <x-ui.card-content class="p-4">
                                    <h3 class="font-semibold text-base">{{ $surfer->name }}</h3>
                                    @if($surfer->nationality)
                                        <p class="text-sm text-muted-foreground">🌍 {{ $surfer->nationality }}</p>
                                    @endif
                                </x-ui.card-content>
                            </x-ui.card>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center">
                    <p class="text-lg text-muted-foreground">{{ __('messages.surfers.noSurfers') }}</p>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
