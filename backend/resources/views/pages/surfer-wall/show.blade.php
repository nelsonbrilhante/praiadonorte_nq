@php
    $locale = app('laravellocalization')->getCurrentLocale();

    $surfer = \App\Models\Surfer::where('slug', $slug)->with('surfboards')->firstOrFail();

    // Get related surfers
    $related = \App\Models\Surfer::where('id', '!=', $surfer->id)
        ->orderBy('featured', 'desc')
        ->limit(4)
        ->get();

    // Helper function to get localized field
    $getLocalized = function($field, $locale) {
        if (is_array($field)) {
            return $field[$locale] ?? $field['pt'] ?? '';
        }
        return $field ?? '';
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
            @if($surfer->featured)
                <x-ui.badge class="mb-4 bg-white/20">{{ __('messages.common.featured') }}</x-ui.badge>
            @endif
            <h1 class="mb-4 text-4xl font-bold md:text-5xl">{{ $surfer->name }}</h1>
            @if($surfer->nationality)
                <p class="text-xl opacity-90">{{ $surfer->nationality }}</p>
            @endif
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Photo --}}
                    @if($surfer->photo)
                        <img
                            src="{{ asset('storage/' . $surfer->photo) }}"
                            alt="{{ $surfer->name }}"
                            class="w-full rounded-lg object-cover aspect-[3/4]"
                        />
                    @else
                        <div class="w-full aspect-[3/4] rounded-lg bg-gradient-to-br from-ocean/20 to-ocean/5"></div>
                    @endif

                    {{-- Social Media --}}
                    @if($surfer->social_media && is_array($surfer->social_media) && count($surfer->social_media) > 0)
                        <x-ui.card>
                            <x-ui.card-header>
                                <x-ui.card-title>{{ __('messages.surfers.socialMedia') }}</x-ui.card-title>
                            </x-ui.card-header>
                            <x-ui.card-content>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($surfer->social_media as $platform => $url)
                                        @if($url)
                                            <a
                                                href="{{ $url }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="inline-flex items-center gap-2 rounded-lg bg-muted px-3 py-2 text-sm font-medium hover:bg-muted/80 transition-colors"
                                            >
                                                {{ ucfirst($platform) }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </x-ui.card-content>
                        </x-ui.card>
                    @endif
                </div>

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Bio --}}
                    @if($surfer->bio)
                        <div>
                            <h2 class="text-2xl font-bold mb-4">{{ __('messages.surfers.about') }}</h2>
                            <div class="prose max-w-none">
                                {!! $getLocalized($surfer->bio, $locale) !!}
                            </div>
                        </div>
                    @endif

                    {{-- Achievements --}}
                    @if($surfer->achievements && is_array($surfer->achievements) && count($surfer->achievements) > 0)
                        <div>
                            <h2 class="text-2xl font-bold mb-4">{{ __('messages.surfers.achievements') }}</h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach($surfer->achievements as $achievement)
                                    <x-ui.badge variant="secondary" class="text-sm">
                                        {{ $getLocalized($achievement, $locale) }}
                                    </x-ui.badge>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Surfboards --}}
                    @if($surfer->surfboards && $surfer->surfboards->count() > 0)
                        <div>
                            <h2 class="text-2xl font-bold mb-4">{{ __('messages.surfers.surfboards') }}</h2>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                @foreach($surfer->surfboards as $board)
                                    <x-ui.card>
                                        @if($board->image)
                                            <img
                                                src="{{ asset('storage/' . $board->image) }}"
                                                alt="{{ $board->brand }} {{ $board->model }}"
                                                class="w-full h-48 object-cover rounded-t-lg"
                                            />
                                        @endif
                                        <x-ui.card-header>
                                            <x-ui.card-title>{{ $board->brand }} {{ $board->model }}</x-ui.card-title>
                                            @if($board->length)
                                                <x-ui.card-description>{{ $board->length }}</x-ui.card-description>
                                            @endif
                                        </x-ui.card-header>
                                    </x-ui.card>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Related Surfers --}}
    @if($related->count() > 0)
        <section class="py-12 bg-muted/30">
            <div class="container mx-auto px-4">
                <h2 class="text-2xl font-bold mb-8">{{ __('messages.surfers.otherSurfers') }}</h2>
                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    @foreach($related as $relatedSurfer)
                        <a href="{{ LaravelLocalization::localizeURL('/surfer-wall/' . $relatedSurfer->slug) }}">
                            <x-ui.card class="group h-full cursor-pointer overflow-hidden transition-all hover:shadow-lg" :noPadding="true">
                                <div class="relative aspect-square">
                                    @if($relatedSurfer->photo)
                                        <img
                                            src="{{ asset('storage/' . $relatedSurfer->photo) }}"
                                            alt="{{ $relatedSurfer->name }}"
                                            class="w-full h-full object-cover transition-transform group-hover:scale-105"
                                        />
                                    @else
                                        <div class="h-full w-full bg-gradient-to-br from-ocean/20 to-ocean/5"></div>
                                    @endif
                                </div>
                                <x-ui.card-content class="p-4">
                                    <h3 class="font-semibold">{{ $relatedSurfer->name }}</h3>
                                    @if($relatedSurfer->nationality)
                                        <p class="text-sm text-muted-foreground">{{ $relatedSurfer->nationality }}</p>
                                    @endif
                                </x-ui.card-content>
                            </x-ui.card>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
