@php
    $locale = app('laravellocalization')->getCurrentLocale();

    $evento = \App\Models\Evento::where('slug', $slug)->firstOrFail();

    // Get related events (same entity, with images)
    $related = \App\Models\Evento::where('entity', $evento->entity)
        ->where('id', '!=', $evento->id)
        ->where('start_date', '>=', now())
        ->orderBy('start_date', 'asc')
        ->limit(3)
        ->get();

    // If not enough upcoming related, fill with past events
    if ($related->count() < 3) {
        $pastRelated = \App\Models\Evento::where('entity', $evento->entity)
            ->where('id', '!=', $evento->id)
            ->whereNotIn('id', $related->pluck('id'))
            ->orderBy('start_date', 'desc')
            ->limit(3 - $related->count())
            ->get();
        $related = $related->concat($pastRelated);
    }

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

    $entityLabels = [
        'praia-norte' => __('messages.entities.praiaDoNorte'),
        'carsurf' => __('messages.entities.carsurf'),
        'nazare-qualifica' => __('messages.entities.nazareQualifica'),
    ];

    $entityGradients = [
        'praia-norte' => 'gradient-ocean-deep',
        'carsurf' => 'gradient-performance',
        'nazare-qualifica' => 'gradient-institutional',
    ];

    $title = $getLocalized($evento->title, $locale);
    $isPast = $evento->start_date->isPast();
@endphp

<x-layouts.app>
    {{-- Full-bleed Hero --}}
    <section class="relative h-[50vh] min-h-[400px] overflow-hidden">
        @if($evento->image)
            <img src="{{ asset('storage/' . $evento->image) }}"
                 class="absolute inset-0 h-full w-full object-cover"
                 alt="{{ $title }}" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
        @else
            <div class="absolute inset-0 {{ $entityGradients[$evento->entity] ?? 'gradient-ocean-deep' }}"></div>
        @endif

        <div class="container relative mx-auto flex h-full flex-col justify-end px-4 pb-10">
            {{-- Entity + Category + Status badges --}}
            <div class="mb-4 flex flex-wrap items-center gap-2">
                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $entityColors[$evento->entity] ?? 'bg-muted' }}">
                    {{ $entityLabels[$evento->entity] ?? $evento->entity }}
                </span>
                @if($evento->category)
                    <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-medium text-white backdrop-blur-sm">
                        {{ $evento->category }}
                    </span>
                @endif
                @if($isPast)
                    <span class="rounded-full bg-muted/60 px-3 py-1 text-xs font-medium text-white backdrop-blur-sm">
                        {{ __('messages.events.pastEvent') }}
                    </span>
                @elseif($evento->featured)
                    <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-medium text-white backdrop-blur-sm">
                        {{ __('messages.common.featured') }}
                    </span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="mb-4 text-3xl font-bold text-white md:text-4xl lg:text-5xl">{{ $title }}</h1>

            {{-- Date + Location inline --}}
            <div class="flex flex-wrap items-center gap-4 text-white/80">
                <span class="flex items-center gap-2">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>
                    </svg>
                    {{ $evento->start_date->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}
                    @if($evento->end_date)
                        — {{ $evento->end_date->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}
                    @endif
                </span>
                @if($evento->location)
                    <span class="flex items-center gap-2">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        {{ $evento->location }}
                    </span>
                @endif
            </div>

            {{-- CTA: Tickets button --}}
            @if($evento->ticket_url)
                <div class="mt-6">
                    <a href="{{ $evento->ticket_url }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex rounded-full bg-white px-6 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-white/90">
                        {{ __('messages.events.tickets') }}
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- Info Bar --}}
    <section class="border-b bg-muted/10 py-4">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center gap-6 text-sm">
                <div>
                    <span class="text-muted-foreground">{{ __('messages.events.startDate') }}:</span>
                    <span class="font-medium">{{ $evento->start_date->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
                </div>
                @if($evento->end_date)
                    <div>
                        <span class="text-muted-foreground">{{ __('messages.events.endDate') }}:</span>
                        <span class="font-medium">{{ $evento->end_date->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
                    </div>
                @endif
                @if($evento->location)
                    <div>
                        <span class="text-muted-foreground">{{ __('messages.events.location') }}:</span>
                        <span class="font-medium">{{ $evento->location }}</span>
                    </div>
                @endif
                @if($evento->category)
                    <div>
                        <span class="text-muted-foreground">{{ __('messages.events.category') }}:</span>
                        <span class="font-medium">{{ $evento->category }}</span>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl reveal-up" x-data x-intersect.once="$el.classList.add('is-visible')">
                <div class="prose max-w-none">
                    {!! $getLocalized($evento->description, $locale) !!}
                </div>
            </div>
        </div>
    </section>

    {{-- Photo Gallery --}}
    @if($evento->gallery && count($evento->gallery) > 0)
    <section class="py-12 border-t">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-4xl">
                <h2 class="mb-6 text-2xl font-bold">{{ __('messages.events.gallery') }}</h2>
                {{-- md:grid-cols-2 md:grid-cols-3 md:grid-cols-4 --}}
                <div class="grid grid-cols-2 gap-4 @if(count($evento->gallery) >= 4) md:grid-cols-4 @elseif(count($evento->gallery) == 3) md:grid-cols-3 @endif">
                    @foreach($evento->gallery as $photo)
                        <figure class="group relative overflow-hidden rounded-xl">
                            <img src="{{ asset('storage/' . $photo) }}"
                                 class="aspect-[4/3] w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 alt="" loading="lazy" />
                        </figure>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Video Embed --}}
    @if($evento->video_url)
    <section class="py-12 bg-muted/10 border-t">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl">
                <h2 class="mb-6 text-2xl font-bold">{{ __('messages.events.replay') }}</h2>
                <div class="aspect-video overflow-hidden rounded-xl">
                    <iframe src="{{ $evento->video_url }}"
                            class="h-full w-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Schedule / Program --}}
    @if($evento->schedule && $getLocalized($evento->schedule, $locale))
    <section class="py-12 border-t">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl">
                <h2 class="mb-6 text-2xl font-bold">{{ __('messages.events.schedule') }}</h2>
                <div class="prose max-w-none">
                    {!! $getLocalized($evento->schedule, $locale) !!}
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Partners --}}
    @if($evento->partners && count($evento->partners) > 0)
    <section class="border-t py-12 text-center">
        <div class="container mx-auto px-4">
            <h2 class="mb-8 text-lg font-medium text-muted-foreground">
                {{ __('messages.events.partners') }}
            </h2>
            <div class="flex flex-wrap items-center justify-center gap-8">
                @foreach($evento->partners as $partner)
                    @if(!empty($partner['logo']))
                        <a href="{{ $partner['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer"
                           class="opacity-60 transition-opacity hover:opacity-100">
                            <img src="{{ asset('storage/' . $partner['logo']) }}"
                                 alt="{{ $partner['name'] }}" class="h-12 object-contain" />
                        </a>
                    @else
                        <a href="{{ $partner['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer"
                           class="rounded-lg border bg-card px-4 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-accent hover:text-foreground">
                            {{ $partner['name'] }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Related Events with Images --}}
    @if($related->count() > 0)
        <section class="border-t bg-muted/10 py-12">
            <div class="container mx-auto px-4">
                <h2 class="mb-6 text-2xl font-bold">{{ __('messages.events.relatedEvents') }}</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    @foreach($related as $relatedEvento)
                        <a href="{{ LaravelLocalization::localizeURL('/eventos/' . $relatedEvento->slug) }}"
                           class="group overflow-hidden rounded-xl border bg-card transition-colors hover:bg-accent/30">
                            @if($relatedEvento->image)
                                <div class="relative aspect-[16/9] overflow-hidden">
                                    <img src="{{ asset('storage/' . $relatedEvento->image) }}"
                                         alt="{{ $getLocalized($relatedEvento->title, $locale) }}"
                                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                         loading="lazy" />
                                </div>
                            @endif
                            <div class="p-4">
                                @if($relatedEvento->category)
                                    <span class="text-xs font-semibold uppercase tracking-wider text-ocean">{{ $relatedEvento->category }}</span>
                                @endif
                                <p class="font-medium group-hover:text-ocean line-clamp-2">{{ $getLocalized($relatedEvento->title, $locale) }}</p>
                                <p class="mt-1 text-sm text-muted-foreground">
                                    {{ $relatedEvento->start_date->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}
                                </p>
                                @if($relatedEvento->location)
                                    <p class="text-sm text-muted-foreground">{{ $relatedEvento->location }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
