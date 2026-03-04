@php
    $locale = app('laravellocalization')->getCurrentLocale();

    $surfer = \App\Models\Surfer::where('slug', $slug)->firstOrFail();

    // Prev/Next surfers by order
    $prevSurfer = \App\Models\Surfer::where('order', '<', $surfer->order)
        ->orderBy('order', 'desc')->orderBy('id', 'desc')->first();
    $nextSurfer = \App\Models\Surfer::where('order', '>', $surfer->order)
        ->orderBy('order', 'asc')->orderBy('id', 'asc')->first();

    // Get related surfers
    $related = \App\Models\Surfer::where('id', '!=', $surfer->id)
        ->orderBy('featured', 'desc')
        ->orderBy('order', 'asc')
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
    @push('head')
        <title>{{ $surfer->name }} | Nazaré Qualifica</title>
        <meta name="description" content="{{ Str::limit(strip_tags($getLocalized($surfer->bio, $locale)), 160) }}">
    @endpush

    {{-- Hero Section --}}
    <section class="relative overflow-hidden gradient-ocean-deep py-12 md:py-20 text-white">
        {{-- Board image as hero background --}}
        @if($surfer->board_image)
            <img
                src="{{ asset('storage/' . $surfer->board_image) }}"
                alt=""
                class="absolute inset-0 w-full h-full object-cover object-center opacity-[0.24] pointer-events-none select-none"
                aria-hidden="true"
            />
        @endif
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-end">
                {{-- Photo (mobile first) --}}
                <div class="md:order-2 flex justify-center">
                    <div class="relative w-full max-w-xs md:max-w-sm">
                        {{-- Glow effect --}}
                        <div class="absolute -inset-2 bg-gradient-to-br from-ocean-light/30 to-ocean/10 blur-sm rounded-xl"></div>
                        @if($surfer->photo)
                            <img
                                src="{{ asset('storage/' . $surfer->photo) }}"
                                alt="{{ $surfer->name }}"
                                class="relative w-full aspect-[3/4] object-cover rounded-xl shadow-2xl"
                            />
                        @else
                            <div class="relative w-full aspect-[3/4] rounded-xl bg-gradient-to-br from-white/10 to-white/5"></div>
                        @endif
                    </div>
                </div>

                {{-- Identity --}}
                <div class="md:order-1 flex flex-col justify-end">
                    @if($surfer->featured)
                        <x-ui.badge class="mb-4 bg-white/20 w-fit">{{ __('messages.common.featured') }}</x-ui.badge>
                    @endif
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight">{{ $surfer->name }}</h1>
                    @if($surfer->aka)
                        <p class="mt-2 text-xl md:text-2xl text-white/60 italic">"{{ $surfer->aka }}"</p>
                    @endif

                    {{-- Social Media --}}
                    @if($surfer->social_media && count(array_filter($surfer->social_media)))
                        <div class="flex gap-3 mt-6">
                            @if(!empty($surfer->social_media['instagram']))
                                <a href="https://instagram.com/{{ $surfer->social_media['instagram'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 text-white/80 hover:bg-white/20 transition-colors" aria-label="Instagram">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                </a>
                            @endif
                            @if(!empty($surfer->social_media['facebook']))
                                <a href="https://facebook.com/{{ $surfer->social_media['facebook'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 text-white/80 hover:bg-white/20 transition-colors" aria-label="Facebook">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            @endif
                            @if(!empty($surfer->social_media['twitter']))
                                <a href="https://x.com/{{ $surfer->social_media['twitter'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/10 text-white/80 hover:bg-white/20 transition-colors" aria-label="X / Twitter">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Quote Section --}}
    @if($surfer->quote && trim(strip_tags($getLocalized($surfer->quote, $locale))))
        <section class="py-12 md:py-16 border-b">
            <div class="max-w-3xl mx-auto px-4 text-center">
                <span class="text-6xl md:text-8xl text-ocean/20 leading-none select-none" style="font-family: var(--font-quote)">"</span>
                <blockquote class="-mt-8 md:-mt-12">
                    <p class="text-xl md:text-2xl lg:text-3xl italic font-semibold text-foreground/80 leading-relaxed" style="font-family: var(--font-quote)">
                        {{ strip_tags($getLocalized($surfer->quote, $locale)) }}
                    </p>
                    <footer class="mt-6 flex items-center justify-center gap-3">
                        <span class="h-px w-8 bg-ocean/30"></span>
                        <span class="text-sm font-medium uppercase tracking-wider text-ocean">{{ $surfer->name }}</span>
                        <span class="h-px w-8 bg-ocean/30"></span>
                    </footer>
                </blockquote>
            </div>
        </section>
    @endif

    {{-- Bio Section --}}
    @if($surfer->bio)
        <section class="py-12 md:py-16">
            <div class="max-w-3xl mx-auto px-4 reveal-up" x-data x-intersect.once="$el.classList.add('is-visible')">
                <h2 class="text-2xl font-bold mb-6">{{ __('messages.surfers.bio') }}</h2>
                <div class="prose prose-lg max-w-none dark:prose-invert">
                    {!! $getLocalized($surfer->bio, $locale) !!}
                </div>
            </div>
        </section>
    @endif

    {{-- Board Image Section with Magnifier --}}
    @if($surfer->board_image)
        <section class="py-12 md:py-16 bg-muted/10 border-t">
            <div class="max-w-3xl mx-auto px-4 text-center reveal-up" x-data x-intersect.once="$el.classList.add('is-visible')">
                <h2 class="text-2xl font-bold mb-2">{{ __('messages.surfers.board') }}</h2>
                <p class="text-muted-foreground mb-8">{{ $surfer->name }}</p>

                {{-- Desktop: magnifier on hover --}}
                <div
                    x-data="boardMagnifier('{{ asset('storage/' . $surfer->board_image) }}')"
                    class="relative inline-block mx-auto"
                    @mouseenter="onEnter()"
                    @mouseleave="onLeave()"
                    @mousemove.throttle.16ms="onMove($event)"
                >
                    <img
                        x-ref="boardImg"
                        src="{{ asset('storage/' . $surfer->board_image) }}"
                        alt="{{ __('messages.surfers.board') }} — {{ $surfer->name }}"
                        class="max-w-xs md:max-w-sm max-h-96 object-contain drop-shadow-lg"
                        :class="{ 'board-magnifier-active': showLens && !isTouch }"
                        loading="lazy"
                        @load="onImageLoad()"
                    />

                    {{-- Magnifier lens (desktop only) --}}
                    <div
                        x-show="showLens && !isTouch"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-90"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-90"
                        class="magnifier-lens absolute rounded-full pointer-events-none overflow-hidden z-10"
                        :style="lensStyle"
                    ></div>

                    {{-- Mobile: zoom badge --}}
                    <button
                        x-show="isTouch && isLoaded"
                        x-cloak
                        @click="openFullscreen()"
                        class="absolute bottom-3 right-3 flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-black/60 text-white text-xs font-medium backdrop-blur-sm hover:bg-black/80 transition-colors"
                        :aria-label="'{{ __('messages.surfers.zoom_board') }}'"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                        </svg>
                        {{ __('messages.surfers.zoom_board') }}
                    </button>

                    {{-- Fullscreen overlay (mobile) --}}
                    <template x-teleport="body">
                        <div
                            x-show="fullscreen"
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-[9999] bg-black/95 flex items-center justify-center"
                            @keydown.escape.window="closeFullscreen()"
                        >
                            <div class="w-full h-full overflow-auto touch-pinch-zoom overscroll-contain">
                                <img
                                    :src="src"
                                    alt="{{ __('messages.surfers.board') }} — {{ $surfer->name }}"
                                    class="min-w-full min-h-full object-contain"
                                />
                            </div>
                            <button
                                @click="closeFullscreen()"
                                class="absolute top-4 right-4 flex items-center gap-1.5 px-3 py-2 rounded-full bg-white/10 text-white text-sm font-medium backdrop-blur-sm hover:bg-white/20 transition-colors"
                                aria-label="{{ __('messages.surfers.close_fullscreen') }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ __('messages.surfers.close_fullscreen') }}
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </section>

        <script>
        function boardMagnifier(src) {
            return {
                src,
                showLens: false,
                fullscreen: false,
                isLoaded: false,
                isTouch: ('ontouchstart' in window || navigator.maxTouchPoints > 0),
                naturalW: 0,
                naturalH: 0,
                lensSize: 150,
                zoom: 2.5,
                lensStyle: '',

                onImageLoad() {
                    const img = this.$refs.boardImg;
                    this.naturalW = img.naturalWidth;
                    this.naturalH = img.naturalHeight;
                    this.isLoaded = true;
                },

                getRenderedRect() {
                    const img = this.$refs.boardImg;
                    const bw = img.clientWidth;
                    const bh = img.clientHeight;
                    const imgRatio = this.naturalW / this.naturalH;
                    const boxRatio = bw / bh;

                    let rw, rh, rx, ry;
                    if (imgRatio > boxRatio) {
                        rw = bw;
                        rh = bw / imgRatio;
                        rx = 0;
                        ry = (bh - rh) / 2;
                    } else {
                        rh = bh;
                        rw = bh * imgRatio;
                        rx = (bw - rw) / 2;
                        ry = 0;
                    }
                    return { rx, ry, rw, rh };
                },

                onEnter() {
                    if (this.isTouch || !this.isLoaded) return;
                },

                onLeave() {
                    this.showLens = false;
                },

                onMove(e) {
                    if (this.isTouch || !this.isLoaded) return;

                    const img = this.$refs.boardImg;
                    const rect = img.getBoundingClientRect();
                    const cx = e.clientX - rect.left;
                    const cy = e.clientY - rect.top;

                    const { rx, ry, rw, rh } = this.getRenderedRect();

                    // Check if cursor is over actual image pixels
                    if (cx < rx || cx > rx + rw || cy < ry || cy > ry + rh) {
                        this.showLens = false;
                        return;
                    }

                    this.showLens = true;

                    const pctX = (cx - rx) / rw;
                    const pctY = (cy - ry) / rh;

                    const half = this.lensSize / 2;

                    // Clamp lens position within the image bounding box
                    const lx = Math.max(0, Math.min(cx - half, img.clientWidth - this.lensSize));
                    const ly = Math.max(0, Math.min(cy - half, img.clientHeight - this.lensSize));

                    const bgW = this.naturalW * this.zoom;
                    const bgH = this.naturalH * this.zoom;

                    // Background position: place the zoomed pixel at the center of the lens
                    const bgX = -(pctX * bgW - half);
                    const bgY = -(pctY * bgH - half);

                    this.lensStyle = `
                        width: ${this.lensSize}px;
                        height: ${this.lensSize}px;
                        left: ${lx}px;
                        top: ${ly}px;
                        background-image: url('${this.src}');
                        background-size: ${bgW}px ${bgH}px;
                        background-position: ${bgX}px ${bgY}px;
                        background-repeat: no-repeat;
                    `;
                },

                openFullscreen() {
                    this.fullscreen = true;
                    document.body.style.overflow = 'hidden';
                },

                closeFullscreen() {
                    this.fullscreen = false;
                    document.body.style.overflow = '';
                },
            };
        }
        </script>
    @endif

    {{-- Prev/Next Navigation --}}
    @if($prevSurfer || $nextSurfer)
        <section class="py-8 border-t">
            <div class="container mx-auto px-4">
                <div class="flex items-center gap-4">
                    {{-- Previous --}}
                    <div class="flex-1">
                        @if($prevSurfer)
                            <a href="{{ LaravelLocalization::localizeURL('/praia-norte/surfer-wall/' . $prevSurfer->slug) }}" class="group flex items-center gap-3 p-4 rounded-lg border hover:bg-accent/30 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-muted-foreground transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                <div class="min-w-0">
                                    <span class="text-xs text-muted-foreground">{{ __('messages.surfers.previousSurfer') }}</span>
                                    <p class="font-medium truncate group-hover:text-ocean transition-colors">{{ $prevSurfer->name }}</p>
                                </div>
                            </a>
                        @endif
                    </div>

                    {{-- Grid icon (desktop) --}}
                    <a href="{{ LaravelLocalization::localizeURL('/praia-norte/surfer-wall') }}" class="hidden md:flex items-center justify-center w-10 h-10 rounded-lg border text-muted-foreground hover:bg-accent/30 hover:text-ocean transition-colors" title="{{ __('messages.surfers.backToList') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </a>

                    {{-- Next --}}
                    <div class="flex-1">
                        @if($nextSurfer)
                            <a href="{{ LaravelLocalization::localizeURL('/praia-norte/surfer-wall/' . $nextSurfer->slug) }}" class="group flex items-center justify-end gap-3 p-4 rounded-lg border hover:bg-accent/30 transition-colors">
                                <div class="min-w-0 text-right">
                                    <span class="text-xs text-muted-foreground">{{ __('messages.surfers.nextSurfer') }}</span>
                                    <p class="font-medium truncate group-hover:text-ocean transition-colors">{{ $nextSurfer->name }}</p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-muted-foreground transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Mobile: Back to Surfer Wall --}}
                <div class="mt-4 text-center md:hidden">
                    <x-ui.button href="{{ LaravelLocalization::localizeURL('/praia-norte/surfer-wall') }}" variant="outline" size="sm">
                        {{ __('messages.surfers.backToList') }}
                    </x-ui.button>
                </div>
            </div>
        </section>
    @endif

    {{-- Related Surfers --}}
    @if($related->count() > 0)
        <section class="py-12 border-t">
            <div class="container mx-auto px-4">
                <h2 class="text-2xl font-bold mb-8">{{ __('messages.surfers.otherSurfers') }}</h2>
                <div class="grid grid-cols-2 gap-4 md:grid-cols-4 reveal-stagger" x-data x-intersect.once="$el.classList.add('is-visible')">
                    @foreach($related as $relatedSurfer)
                        <a href="{{ LaravelLocalization::localizeURL('/praia-norte/surfer-wall/' . $relatedSurfer->slug) }}">
                            <x-ui.card class="group h-full cursor-pointer overflow-hidden transition-colors" :noPadding="true">
                                <div class="relative aspect-[3/4]">
                                    @if($relatedSurfer->photo)
                                        <img
                                            src="{{ asset('storage/' . $relatedSurfer->photo) }}"
                                            alt="{{ $relatedSurfer->name }}"
                                            class="w-full h-full object-cover transition-transform group-hover:scale-105"
                                            loading="lazy"
                                        />
                                    @else
                                        <div class="h-full w-full bg-gradient-to-br from-ocean/20 to-ocean/5"></div>
                                    @endif
                                    {{-- Gradient overlay on hover --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>
                                </div>
                                <x-ui.card-content class="p-4">
                                    <h3 class="font-semibold">{{ $relatedSurfer->name }}</h3>
                                    @if($relatedSurfer->aka)
                                        <p class="text-sm text-muted-foreground italic">"{{ $relatedSurfer->aka }}"</p>
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
