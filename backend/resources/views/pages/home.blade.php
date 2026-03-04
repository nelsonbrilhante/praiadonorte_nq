<x-layouts.app>
    @php
        // Helper function to get localized field
        $getLocalized = function($field, $locale) {
            if (is_array($field)) {
                return $field[$locale] ?? $field['pt'] ?? '';
            }
            return $field ?? '';
        };
    @endphp

    {{-- Hero Slider --}}
    <x-praia-norte.hero-slider
        :slides="$homepage?->heroSlides ?? collect()"
        :interval="$homepage?->slider_interval ?? 8"
        :autoplay="$homepage?->slider_autoplay ?? true"
    />

    {{-- News Section --}}
    <section class="relative py-16 bg-background">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-3xl font-bold md:text-4xl">{{ __('messages.home.news.title') }}</h2>
                <x-ui.button variant="outline" href="{{ LaravelLocalization::localizeURL('/noticias') }}">
                    {{ __('messages.home.news.viewAll') }}
                </x-ui.button>
            </div>

            {{-- Magazine layout: featured + 2 side cards --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @forelse($noticias as $index => $noticia)
                    @if($index === 0)
                        {{-- Featured article (large) --}}
                        <a href="{{ LaravelLocalization::localizeURL('/noticias/' . $noticia->slug) }}"
                           class="group relative block overflow-hidden rounded-2xl {{ count($noticias) > 1 ? 'md:row-span-2' : '' }}"
                           style="--stagger-index: 0">
                            <div class="relative {{ count($noticias) > 1 ? 'h-64 md:h-full md:min-h-[420px]' : 'h-64 md:h-80' }}">
                                @if($noticia->cover_image)
                                    <img
                                        src="{{ asset('storage/' . $noticia->cover_image) }}"
                                        alt="{{ $getLocalized($noticia->title, $locale) }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    />
                                @else
                                    <div class="h-full w-full gradient-ocean-deep"></div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8">
                                    <h3 class="mb-2 text-xl font-bold text-white md:text-2xl line-clamp-3">
                                        {{ $getLocalized($noticia->title, $locale) }}
                                    </h3>
                                    <p class="text-sm text-white/70 line-clamp-2">
                                        {{ $getLocalized($noticia->excerpt, $locale) }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @else
                        {{-- Side cards --}}
                        <a href="{{ LaravelLocalization::localizeURL('/noticias/' . $noticia->slug) }}"
                           class="group relative block overflow-hidden rounded-2xl"
                           style="--stagger-index: {{ $index }}">
                            <div class="relative h-48 md:h-[200px]">
                                @if($noticia->cover_image)
                                    <img
                                        src="{{ asset('storage/' . $noticia->cover_image) }}"
                                        alt="{{ $getLocalized($noticia->title, $locale) }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    />
                                @else
                                    <div class="h-full w-full gradient-ocean-mid"></div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-5">
                                    <h3 class="mb-1 text-lg font-bold text-white line-clamp-2">
                                        {{ $getLocalized($noticia->title, $locale) }}
                                    </h3>
                                    <p class="text-sm text-white/60 line-clamp-1">
                                        {{ $getLocalized($noticia->excerpt, $locale) }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endif
                @empty
                    @for($i = 0; $i < 3; $i++)
                        <div class="{{ $i === 0 ? 'md:row-span-2' : '' }} relative overflow-hidden rounded-2xl"
                             style="--stagger-index: {{ $i }}">
                            <div class="relative {{ $i === 0 ? 'h-64 md:h-full md:min-h-[420px]' : 'h-48 md:h-[200px]' }} gradient-ocean-deep">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-6">
                                    <div class="h-6 w-3/4 rounded bg-white/20 mb-2"></div>
                                    <div class="h-4 w-1/2 rounded bg-white/10"></div>
                                </div>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>

    {{-- Surfer Wall Section (light museum gallery) --}}
    <section class="section-ocean-light py-16 md:py-20">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-foreground md:text-4xl">{{ __('messages.home.surfers.title') }}</h2>
                    <p class="text-muted-foreground">{{ __('messages.home.surfers.subtitle') }}</p>
                </div>
                <x-ui.button variant="outline" href="{{ LaravelLocalization::localizeURL('/praia-norte/surfer-wall') }}">
                    {{ __('messages.home.surfers.viewAll') }}
                </x-ui.button>
            </div>

            @if($surfers->count() <= 3)
                {{-- Static grid for 3 or fewer surfers --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                    @forelse($surfers as $index => $surfer)
                        <a href="{{ LaravelLocalization::localizeURL('/praia-norte/surfer-wall/' . $surfer->slug) }}"
                           class="group relative block overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 ring-1 ring-black/5 dark:ring-white/10"
                           style="--stagger-index: {{ $index }}">
                            <div class="relative aspect-square">
                                @if($surfer->photo)
                                    <img src="{{ asset('storage/' . $surfer->photo) }}" alt="{{ $surfer->name }}"
                                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                @else
                                    <div class="h-full w-full bg-ocean-deep"></div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    <p class="font-bold text-white">{{ $surfer->name }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        @for($i = 0; $i < 3; $i++)
                            <div class="relative overflow-hidden rounded-xl shadow-lg ring-1 ring-black/5 dark:ring-white/10" style="--stagger-index: {{ $i }}">
                                <div class="aspect-square bg-ocean-100 dark:bg-ocean-800/50">
                                    <div class="absolute bottom-0 left-0 right-0 p-4">
                                        <div class="h-5 w-2/3 rounded bg-ocean-200 dark:bg-white/10 mb-1"></div>
                                        <div class="h-4 w-1/3 rounded bg-ocean-200/60 dark:bg-white/5"></div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @endforelse
                </div>
            @else
                {{-- Carousel for more than 3 surfers --}}
                <div x-data="{
                    current: 0,
                    total: {{ $surfers->count() }},
                    visible: 3,
                    autoplayInterval: null,
                    paused: false,
                    get isCarousel() { return this.total > 3 },
                    get maxIndex() { return this.total - this.visible },
                    next() {
                        this.current = this.current >= this.maxIndex ? 0 : this.current + 1;
                    },
                    prev() {
                        this.current = this.current <= 0 ? this.maxIndex : this.current - 1;
                    },
                    updateVisible() {
                        if (window.innerWidth < 640) { this.visible = 1; }
                        else if (window.innerWidth < 768) { this.visible = 2; }
                        else { this.visible = 3; }
                        if (this.current > this.maxIndex) this.current = Math.max(0, this.maxIndex);
                    },
                    startAutoplay() {
                        this.stopAutoplay();
                        this.autoplayInterval = setInterval(() => { if (!this.paused) this.next(); }, 5000);
                    },
                    stopAutoplay() {
                        if (this.autoplayInterval) { clearInterval(this.autoplayInterval); this.autoplayInterval = null; }
                    }
                }"
                x-init="updateVisible(); startAutoplay(); window.addEventListener('resize', () => updateVisible())"
                x-on:mouseenter="paused = true"
                x-on:mouseleave="paused = false"
                class="relative group/carousel">

                    {{-- Track --}}
                    <div class="overflow-hidden">
                        <div class="flex transition-transform duration-700 ease-in-out"
                             :style="`transform: translateX(-${current * (100 / visible)}%)`">
                            @foreach($surfers as $index => $surfer)
                                <div class="flex-none w-full sm:w-1/2 md:w-1/3 px-2">
                                    <a href="{{ LaravelLocalization::localizeURL('/praia-norte/surfer-wall/' . $surfer->slug) }}"
                                       class="group relative block overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 ring-1 ring-black/5 dark:ring-white/10">
                                        <div class="relative aspect-square">
                                            @if($surfer->photo)
                                                <img src="{{ asset('storage/' . $surfer->photo) }}" alt="{{ $surfer->name }}"
                                                     class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" />
                                            @else
                                                <div class="h-full w-full bg-ocean-deep"></div>
                                            @endif
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                                <p class="font-bold text-white">{{ $surfer->name }}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Navigation arrows --}}
                    <button x-on:click="prev()"
                            class="absolute left-2 top-1/2 -translate-y-1/2 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-black/40 text-white opacity-0 group-hover/carousel:opacity-100 transition-opacity duration-300 hover:bg-black/60"
                            aria-label="Previous">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <button x-on:click="next()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-black/40 text-white opacity-0 group-hover/carousel:opacity-100 transition-opacity duration-300 hover:bg-black/60"
                            aria-label="Next">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </button>

                    {{-- Dot indicators --}}
                    <div class="flex justify-center gap-2 mt-6">
                        <template x-for="i in (maxIndex + 1)" :key="i">
                            <button x-on:click="current = i - 1"
                                    class="rounded-full transition-all duration-300"
                                    :class="current === i - 1 ? 'w-3 h-3 bg-ocean' : 'w-2 h-2 bg-ocean/30 hover:bg-ocean/50'"
                                    aria-label="Go to slide"></button>
                        </template>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Entities Section --}}
    <section class="py-16 md:py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                {{-- Praia do Norte --}}
                <a href="{{ LaravelLocalization::localizeURL('/praia-norte/sobre') }}"
                   class="group relative block overflow-hidden rounded-2xl"
                   style="--stagger-index: 0">
                    <div class="relative h-64 md:h-72 gradient-ocean-deep">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        <div class="absolute bottom-0 p-6">
                            <img src="{{ asset('images/logos/LOGOTIPO PN.png') }}" alt="Praia do Norte" class="h-10 mb-3 brightness-0 invert" />
                            <p class="text-sm text-white/80">
                                {{ $locale === 'pt' ? 'O lar das ondas gigantes mais famosas do mundo' : 'Home to the world\'s most famous giant waves' }}
                            </p>
                            <span class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-white">
                                {{ __('messages.common.learnMore') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-300 group-hover:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </a>

                {{-- Carsurf --}}
                <a href="{{ LaravelLocalization::localizeURL('/carsurf') }}"
                   class="group relative block overflow-hidden rounded-2xl"
                   style="--stagger-index: 1">
                    <div class="relative h-64 md:h-72" style="background-color: #004d2a;">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        <div class="absolute bottom-0 p-6">
                            <img src="{{ asset('images/logos/CARSURF_001.png') }}" alt="Carsurf" class="h-10 mb-3 brightness-0 invert" />
                            <p class="text-sm text-white/80">
                                {{ $locale === 'pt' ? 'Centro de alto rendimento para atletas de surf' : 'High-performance center for surf athletes' }}
                            </p>
                            <span class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-white">
                                {{ __('messages.common.learnMore') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-300 group-hover:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </a>

                {{-- Nazaré Qualifica --}}
                <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/sobre') }}"
                   class="group relative block overflow-hidden rounded-2xl"
                   style="--stagger-index: 2">
                    <div class="relative h-64 md:h-72" style="background-color: #3d2800;">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        <div class="absolute bottom-0 p-6">
                            <img src="{{ asset('images/logos/imagem-grafica-nq-white-name.svg') }}" alt="Nazaré Qualifica" class="h-10 mb-3" />
                            <p class="text-sm text-white/80">
                                {{ $locale === 'pt' ? 'Empresa municipal gestora das infraestruturas' : 'Municipal company managing infrastructure' }}
                            </p>
                            <span class="mt-3 inline-flex items-center gap-2 text-sm font-medium text-white">
                                {{ __('messages.common.learnMore') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-300 group-hover:translate-x-1"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- Events Section --}}
    <section class="relative py-16 md:py-20">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-3xl font-bold md:text-4xl">{{ __('messages.home.events.title') }}</h2>
                <x-ui.button variant="outline" href="{{ LaravelLocalization::localizeURL('/eventos') }}">
                    {{ __('messages.home.events.viewAll') }}
                </x-ui.button>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @forelse($eventos as $index => $evento)
                    @php
                        $startDate = $evento->start_date;
                        $day = $startDate->format('d');
                        $month = $startDate->locale($locale === 'pt' ? 'pt_PT' : 'en_GB')->shortMonthName;
                        $entityColor = match($evento->entity ?? 'praia-norte') {
                            'carsurf' => 'bg-performance',
                            'nazare-qualifica' => 'bg-institutional',
                            default => 'bg-ocean',
                        };
                    @endphp
                    <a href="{{ LaravelLocalization::localizeURL('/eventos/' . $evento->slug) }}"
                       class="group flex overflow-hidden rounded-xl border bg-card transition-colors duration-200 hover:bg-accent/50 dark:border-white/10"
                       style="--stagger-index: {{ $index }}">
                        {{-- Date badge with entity color strip --}}
                        <div class="relative flex w-28 shrink-0 items-center justify-center {{ $entityColor }}">
                            <div class="text-center text-white">
                                <p class="text-3xl font-bold leading-none">{{ $day }}</p>
                                <p class="mt-1 text-sm font-medium uppercase tracking-wider opacity-80">{{ $month }}</p>
                            </div>
                        </div>
                        <div class="flex flex-1 flex-col justify-center p-5">
                            <h3 class="mb-1 text-lg font-bold line-clamp-2 text-card-foreground">
                                {{ $getLocalized($evento->title, $locale) }}
                            </h3>
                            <p class="flex items-center gap-1.5 text-sm text-muted-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                {{ $evento->location }}
                            </p>
                        </div>
                    </a>
                @empty
                    @for($i = 0; $i < 2; $i++)
                        <div class="flex overflow-hidden rounded-xl border bg-card dark:border-white/10"
                             style="--stagger-index: {{ $i }}">
                            <div class="flex w-28 shrink-0 items-center justify-center bg-ocean">
                                <div class="text-center text-white">
                                    <p class="text-3xl font-bold leading-none">15</p>
                                    <p class="mt-1 text-sm font-medium uppercase tracking-wider opacity-80">Jan</p>
                                </div>
                            </div>
                            <div class="flex flex-1 flex-col justify-center p-5">
                                <div class="h-5 w-3/4 rounded bg-muted mb-2"></div>
                                <div class="h-4 w-1/2 rounded bg-muted/50"></div>
                            </div>
                        </div>
                    @endfor
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>
