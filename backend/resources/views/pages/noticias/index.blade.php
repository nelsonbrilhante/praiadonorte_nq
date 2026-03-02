<x-layouts.app>
    @php
        $currentEntity = request('entity');

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

        $entityTextColors = [
            'praia-norte' => 'text-ocean',
            'carsurf' => 'text-performance',
            'nazare-qualifica' => 'text-institutional',
        ];

        $entityGradients = [
            'praia-norte' => 'gradient-ocean-deep',
            'carsurf' => 'gradient-performance',
            'nazare-qualifica' => 'gradient-institutional',
        ];

        $heroNoticia = $featuredNoticias->first() ?? $noticias->first();
    @endphp

    {{-- Hero with featured news image or dark gradient fallback --}}
    <section class="relative h-[40vh] min-h-[320px] overflow-hidden">
        @if($heroNoticia?->cover_image)
            <img src="{{ asset('storage/' . $heroNoticia->cover_image) }}"
                 class="absolute inset-0 h-full w-full object-cover" alt="" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/20"></div>
        @else
            <div class="absolute inset-0 gradient-ocean-deep"></div>
        @endif
        <div class="container relative mx-auto flex h-full flex-col justify-end px-4 pb-10">
            <h1 class="text-4xl font-bold text-white md:text-5xl lg:text-6xl">{{ __('messages.news.title') }}</h1>
            <p class="mt-2 max-w-2xl text-lg text-white/80">{{ __('messages.news.subtitle') }}</p>
        </div>
    </section>

    {{-- Featured News Carousel --}}
    @if($featuredNoticias->count() > 0)
    <section class="py-8 overflow-hidden border-b">
        <div class="container mx-auto px-4">
            <h2 class="mb-5 text-xl font-bold md:text-2xl">{{ __('messages.news.featuredNews') }}</h2>
            <div class="relative" x-data="{
                scrollEl: null,
                init() { this.scrollEl = this.$refs.carousel; },
                scrollLeft() { this.scrollEl.scrollBy({ left: -340, behavior: 'smooth' }); },
                scrollRight() { this.scrollEl.scrollBy({ left: 340, behavior: 'smooth' }); }
            }">
                <div class="flex gap-3 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide"
                     x-ref="carousel">
                    @foreach($featuredNoticias as $fNoticia)
                        @php
                            $fTitle = $getLocalized($fNoticia->title, $locale);
                            $fDate = $fNoticia->published_at->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D MMM YYYY');
                        @endphp
                        <a href="{{ LaravelLocalization::localizeURL('/noticias/' . $fNoticia->slug) }}"
                           class="group flex-shrink-0 w-[260px] md:w-[300px] snap-start">
                            <div class="relative aspect-[4/5] overflow-hidden rounded-xl">
                                {{-- Background image --}}
                                @if($fNoticia->cover_image)
                                    <img src="{{ asset('storage/' . $fNoticia->cover_image) }}"
                                         alt="{{ $fTitle }}"
                                         class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />
                                @else
                                    <div class="absolute inset-0 {{ $entityGradients[$fNoticia->entity] ?? 'gradient-ocean-deep' }}"></div>
                                @endif
                                {{-- Gradient overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                                {{-- Badge (top-left) --}}
                                <div class="absolute left-4 top-4 flex items-center gap-2">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $entityColors[$fNoticia->entity] ?? 'bg-muted' }}">
                                        {{ $entityLabels[$fNoticia->entity] ?? $fNoticia->entity }}
                                    </span>
                                    <span class="rounded-full bg-white/20 px-2.5 py-1 text-xs font-medium text-white backdrop-blur-sm">
                                        {{ __('messages.news.featured') }}
                                    </span>
                                </div>
                                {{-- Info (bottom) --}}
                                <div class="absolute inset-x-0 bottom-0 p-5">
                                    <h3 class="text-lg font-bold text-white line-clamp-2">{{ $fTitle }}</h3>
                                    <div class="mt-2 text-sm text-white/70">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                            {{ $fDate }}
                                        </div>
                                    </div>
                                    @if($fNoticia->category)
                                        <span class="mt-2 inline-block rounded-full bg-white/20 px-2.5 py-0.5 text-xs text-white backdrop-blur-sm">
                                            {{ $fNoticia->category }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                {{-- Navigation arrows --}}
                @if($featuredNoticias->count() > 3)
                <div class="mt-4 flex justify-end gap-2">
                    <button @click="scrollLeft()"
                            class="rounded-full border border-border bg-card p-2 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <button @click="scrollRight()"
                            class="rounded-full border border-border bg-card p-2 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- Sticky Filters --}}
    <section class="sticky top-16 z-30 border-b bg-background/95 backdrop-blur-sm py-3">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex flex-wrap gap-2">
                    <a
                        href="{{ LaravelLocalization::localizeURL('/noticias') }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition-colors {{ !$currentEntity ? 'bg-ocean text-white' : 'bg-muted hover:bg-muted/80' }}"
                    >
                        {{ __('messages.common.all') }}
                    </a>
                    @foreach(['praia-norte', 'carsurf', 'nazare-qualifica'] as $entity)
                        <a
                            href="{{ LaravelLocalization::localizeURL('/noticias') }}?entity={{ $entity }}"
                            class="rounded-full px-4 py-2 text-sm font-medium transition-colors {{ $currentEntity === $entity ? $entityColors[$entity] : 'bg-muted hover:bg-muted/80' }}"
                        >
                            {{ $entityLabels[$entity] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- News List --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            @if($noticias->count() > 0)
                <div class="space-y-6">
                    @foreach($noticias as $noticia)
                        @php
                            $pubDate = $noticia->published_at;
                            $day = $pubDate->format('d');
                            $month = $pubDate->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->shortMonthName;
                            $year = $pubDate->format('Y');
                            $excerptText = $getLocalized($noticia->excerpt, $locale);
                        @endphp
                        <a href="{{ LaravelLocalization::localizeURL('/noticias/' . $noticia->slug) }}"
                           class="group flex flex-col overflow-hidden rounded-xl border bg-card transition-colors hover:bg-accent/30 sm:flex-row">
                            {{-- Thumbnail --}}
                            <div class="relative h-32 w-full flex-shrink-0 overflow-hidden sm:h-auto sm:w-24 md:w-28">
                                @if($noticia->cover_image)
                                    <img src="{{ asset('storage/' . $noticia->cover_image) }}"
                                         alt="{{ $getLocalized($noticia->title, $locale) }}"
                                         class="aspect-square h-full w-full object-cover" />
                                @else
                                    <div class="flex aspect-square h-full w-full items-center justify-center bg-muted">
                                        <svg class="h-8 w-8 text-muted-foreground/40" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                                            <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex flex-1 flex-col justify-center p-4 sm:p-5">
                                {{-- Entity + Category + Featured --}}
                                <div class="mb-1 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs">
                                    <span class="font-semibold uppercase tracking-wider {{ $entityTextColors[$noticia->entity] ?? 'text-ocean' }}">
                                        {{ $entityLabels[$noticia->entity] ?? $noticia->entity }}
                                    </span>
                                    @if($noticia->category)
                                        <span class="text-muted-foreground">&middot; {{ $noticia->category }}</span>
                                    @endif
                                    @if($noticia->featured)
                                        <span class="flex items-center gap-1.5 text-ocean">
                                            <span class="h-1.5 w-1.5 rounded-full bg-ocean"></span>
                                            {{ __('messages.news.featured') }}
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-base font-bold line-clamp-2 md:text-lg">
                                    {{ $getLocalized($noticia->title, $locale) }}
                                </h3>
                                {{-- Excerpt --}}
                                @if($excerptText)
                                    <p class="mt-1 text-sm text-muted-foreground line-clamp-2">{{ $excerptText }}</p>
                                @endif
                                <div class="mt-1.5 flex flex-wrap items-center gap-3 text-sm text-muted-foreground">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>
                                        </svg>
                                        {{ $pubDate->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->isoFormat('D [de] MMMM [de] YYYY') }}
                                    </span>
                                    @if($noticia->author)
                                        <span class="flex items-center gap-1.5">
                                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                                <circle cx="12" cy="7" r="4"/>
                                            </svg>
                                            {{ $noticia->author }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Date column (desktop) --}}
                            <div class="hidden flex-shrink-0 flex-col items-center justify-center border-l px-6 sm:flex">
                                <span class="text-3xl font-bold">{{ $day }}</span>
                                <span class="text-sm uppercase text-muted-foreground">{{ $month }}</span>
                                <span class="text-xs text-muted-foreground">{{ $year }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center">
                    <p class="text-lg text-muted-foreground">{{ __('messages.news.noNews') }}</p>
                </div>
            @endif

            {{-- Pagination --}}
            @if($noticias->lastPage() > 1)
                <div class="mt-8 flex justify-center gap-2">
                    {{ $noticias->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
