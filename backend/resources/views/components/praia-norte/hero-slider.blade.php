@props([
    'slides' => collect(),
    'interval' => 8,
    'autoplay' => true,
])

@php
    $locale = LaravelLocalization::getCurrentLocale();
    $activeSlides = $slides->where('active', true)->values();
    $totalSlides = $activeSlides->count();
    $hasLiveSlide = $activeSlides->contains('is_live', true);

    // Convert interval from seconds to milliseconds
    $intervalMs = $interval * 1000;
@endphp

@if($totalSlides > 0)
<div
    x-data="{
        currentSlide: 0,
        totalSlides: {{ $totalSlides }},
        interval: {{ $intervalMs }},
        autoplay: {{ $autoplay ? 'true' : 'false' }},
        hasLiveSlide: {{ $hasLiveSlide ? 'true' : 'false' }},
        progressTimer: null,
        isPaused: false,
        progress: 0,
        players: {},
        isMuted: {},

        init() {
            // Pause autoplay if any slide is live
            if (this.hasLiveSlide) {
                this.isPaused = true;
            }

            // Start auto-rotation if enabled and not paused
            if (this.autoplay && !this.isPaused && this.totalSlides > 1) {
                this.startProgress();
            }

            // Initialize YouTube API
            this.initYouTubeAPI();
        },

        startProgress() {
            this.progress = 0;
            const updateInterval = 50; // Update every 50ms for smooth animation
            const step = 100 / (this.interval / updateInterval);

            this.progressTimer = setInterval(() => {
                this.progress += step;
                if (this.progress >= 100) {
                    this.nextSlide();
                }
            }, updateInterval);
        },

        stopProgress() {
            if (this.progressTimer) {
                clearInterval(this.progressTimer);
                this.progressTimer = null;
            }
        },

        resetProgress() {
            this.progress = 0;
        },

        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
            this.resetProgress();
            this.onSlideChange();
        },

        goToSlide(index) {
            if (index !== this.currentSlide) {
                this.currentSlide = index;
                this.resetProgress();
                this.onSlideChange();

                // Restart progress on manual navigation
                if (!this.isPaused && this.autoplay && this.totalSlides > 1) {
                    this.stopProgress();
                    this.startProgress();
                }
            }
        },

        onSlideChange() {
            // Pause all non-active video players
            Object.entries(this.players).forEach(([index, player]) => {
                if (parseInt(index) !== this.currentSlide && player && player.pauseVideo) {
                    try { player.pauseVideo(); } catch(e) {}
                }
            });

            // Play active video
            const activePlayer = this.players[this.currentSlide];
            if (activePlayer && activePlayer.playVideo) {
                try { activePlayer.playVideo(); } catch(e) {}
            }
        },

        initYouTubeAPI() {
            if (window.YT && window.YT.Player) {
                this.setupPlayers();
                return;
            }

            // Load YouTube IFrame API
            if (!document.getElementById('youtube-iframe-api')) {
                const tag = document.createElement('script');
                tag.id = 'youtube-iframe-api';
                tag.src = 'https://www.youtube.com/iframe_api';
                document.head.appendChild(tag);
            }

            // Wait for API to load
            window.onYouTubeIframeAPIReady = () => {
                this.setupPlayers();
            };
        },

        setupPlayers() {
            for (let i = 0; i < this.totalSlides; i++) {
                const iframe = document.getElementById('hero-youtube-player-' + i);
                if (iframe) {
                    this.isMuted[i] = true;
                    this.players[i] = new YT.Player('hero-youtube-player-' + i, {
                        events: {
                            onReady: (e) => {
                                e.target.mute();
                                if (i === this.currentSlide) {
                                    e.target.playVideo();
                                }
                            }
                        }
                    });
                }
            }
        },

        toggleAudio(index) {
            const player = this.players[index];
            if (!player) return;

            if (this.isMuted[index]) {
                player.unMute();
                player.setVolume(100);
            } else {
                player.mute();
            }
            this.isMuted[index] = !this.isMuted[index];
        }
    }"
    class="relative h-screen overflow-hidden"
>
    {{-- Slides --}}
    @foreach($activeSlides as $index => $slide)
        @php
            // Extract YouTube video ID
            $youtubeVideoId = null;
            if ($slide->video_url) {
                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/', $slide->video_url, $matches)) {
                    $youtubeVideoId = $matches[1];
                } elseif (preg_match('/^([a-zA-Z0-9_-]{11})$/', $slide->video_url, $matches)) {
                    $youtubeVideoId = $matches[1];
                }
            }
            $hasVideo = !empty($youtubeVideoId);

            // Get localized content
            $slideTitle = $slide->title[$locale] ?? $slide->title['pt'] ?? __('messages.home.hero.title');
            $slideSubtitle = $slide->subtitle[$locale] ?? $slide->subtitle['pt'] ?? __('messages.home.hero.subtitle');
            $slideCtaText = $slide->cta_text[$locale] ?? $slide->cta_text['pt'] ?? __('messages.home.hero.cta');
            $slideCtaUrl = $slide->cta_url[$locale] ?? $slide->cta_url['pt'] ?? '/sobre';
        @endphp

        <div
            x-show="currentSlide === {{ $index }}"
            x-transition:enter="transition-opacity ease-out duration-1000"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-1000"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0"
            :class="{ 'z-10': currentSlide === {{ $index }}, 'z-0': currentSlide !== {{ $index }} }"
        >
            <section class="relative flex h-screen flex-col justify-end overflow-hidden text-white">
                {{-- YouTube Video Background --}}
                @if($hasVideo)
                    <div class="absolute inset-0 overflow-hidden">
                        <iframe
                            id="hero-youtube-player-{{ $index }}"
                            src="https://www.youtube-nocookie.com/embed/{{ $youtubeVideoId }}?autoplay=1&mute=1&loop=1&playlist={{ $youtubeVideoId }}&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&vq=hd1080&enablejsapi=1&origin={{ url('/') }}"
                            allow="autoplay; encrypted-media"
                            allowfullscreen
                            class="pointer-events-none absolute left-1/2 top-1/2 h-[150%] w-[150%] -translate-x-1/2 -translate-y-1/2"
                            style="border: none;"
                            title="Background video slide {{ $index + 1 }}"
                        ></iframe>
                    </div>
                @else
                    {{-- Fallback Image --}}
                    <img
                        src="{{ $slide->fallback_image ? asset('storage/' . $slide->fallback_image) : asset('/pn-ai-wave-hero.png') }}"
                        alt="Giant wave at Praia do Norte, Nazare"
                        class="absolute inset-0 h-full w-full object-cover"
                    />
                    <div class="absolute inset-0 bg-black/40"></div>
                @endif

                {{-- LIVE Badge - Top Right --}}
                @if($slide->is_live)
                    <div class="absolute right-4 top-20 z-20 md:right-8 md:top-24">
                        <div class="flex items-center gap-2 rounded-md bg-red-600 px-3 py-1.5 text-sm font-bold uppercase tracking-wide text-white shadow-lg">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-75"></span>
                                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-white"></span>
                            </span>
                            LIVE
                        </div>
                    </div>
                @endif

                {{-- Audio Toggle Button - Bottom Right (only when LIVE and audio enabled) --}}
                @if($slide->is_live && $slide->audio_enabled && $hasVideo)
                    <button
                        @click="toggleAudio({{ $index }})"
                        type="button"
                        class="absolute bottom-24 right-4 z-20 flex h-12 w-12 items-center justify-center rounded-full bg-black/50 text-white backdrop-blur transition-all hover:bg-black/70 md:bottom-28 md:right-8"
                        title="{{ __('messages.home.hero.toggleAudio') }}"
                        aria-label="Toggle audio"
                    >
                        {{-- Muted icon --}}
                        <svg x-show="isMuted[{{ $index }}]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                            <line x1="23" y1="9" x2="17" y2="15"/>
                            <line x1="17" y1="9" x2="23" y2="15"/>
                        </svg>
                        {{-- Unmuted icon --}}
                        <svg x-show="!isMuted[{{ $index }}]" x-cloak xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                            <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
                            <path d="M19.07 4.93a10 10 0 0 1 0 14.14"/>
                        </svg>
                    </button>
                @endif

                {{-- Content - positioned at bottom --}}
                <div class="container relative z-10 mx-auto px-4 pb-16 text-center">
                    @if($slide->use_logo_as_title && $slide->hero_logo)
                        <img
                            src="{{ asset('storage/' . $slide->hero_logo) }}"
                            alt="Praia do Norte"
                            class="mx-auto mb-4 w-auto"
                            style="height: {{ $slide->logo_height ?? 120 }}px;"
                        />
                    @else
                        <h1 class="mb-4 text-5xl font-bold md:text-7xl">
                            {{ $slideTitle }}
                        </h1>
                    @endif
                    <p class="mb-8 text-xl md:text-2xl">
                        {{ $slideSubtitle }}
                    </p>
                    @if($slideCtaText)
                        <a href="{{ LaravelLocalization::localizeURL($slideCtaUrl) }}"
                           class="inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-6 border border-white bg-transparent text-white hover:bg-white/10 transition-colors">
                            {{ $slideCtaText }}
                        </a>
                    @endif
                </div>
            </section>
        </div>
    @endforeach

    {{-- Dot Indicators with Progress --}}
    @if($totalSlides > 1)
        <div class="absolute bottom-6 left-1/2 z-20 flex -translate-x-1/2 items-center gap-3">
            @foreach($activeSlides as $index => $slide)
                <button
                    @click="goToSlide({{ $index }})"
                    :class="currentSlide === {{ $index }} ? 'w-10 h-5' : 'w-5 h-5'"
                    class="relative flex items-center justify-center transition-all duration-300 focus:outline-none"
                    aria-label="Go to slide {{ $index + 1 }}"
                >
                    {{-- Progress pill (only on active slide when autoplay) --}}
                    <template x-if="currentSlide === {{ $index }} && autoplay && !isPaused">
                        <svg class="absolute inset-0 h-full w-full" viewBox="0 0 40 20">
                            {{-- Background pill path --}}
                            <path
                                d="M20 2 L30 2 A8 8 0 0 1 30 18 L10 18 A8 8 0 0 1 10 2 Z"
                                stroke="rgba(255,255,255,0.3)"
                                stroke-width="2"
                                fill="none"
                            />
                            {{-- Progress pill path - starts from top center, goes clockwise --}}
                            <path
                                d="M20 2 L30 2 A8 8 0 0 1 30 18 L10 18 A8 8 0 0 1 10 2 Z"
                                stroke="white"
                                stroke-width="2"
                                fill="none"
                                stroke-linecap="round"
                                :stroke-dasharray="92"
                                :stroke-dashoffset="92 - (progress / 100 * 92)"
                                class="transition-none"
                            />
                        </svg>
                    </template>
                    {{-- Inactive progress ring (circle for non-active) --}}
                    <template x-if="currentSlide !== {{ $index }}">
                        <span class="sr-only">Slide {{ $index + 1 }}</span>
                    </template>
                    {{-- Dot/Pill shape --}}
                    <span
                        :class="currentSlide === {{ $index }}
                            ? 'bg-white h-2.5 w-7 rounded-full'
                            : 'bg-white/50 h-2 w-2 rounded-full hover:bg-white/70'"
                        class="transition-all duration-300"
                    ></span>
                </button>
            @endforeach
        </div>
    @endif
</div>
@else
    {{-- Fallback when no slides --}}
    <x-praia-norte.hero-section />
@endif
