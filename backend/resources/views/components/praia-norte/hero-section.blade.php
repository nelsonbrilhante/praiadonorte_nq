@props([
    'title' => null,
    'subtitle' => null,
    'ctaText' => null,
    'ctaUrl' => null,
    'youtubeUrl' => null,
    'fallbackImage' => '/pn-ai-wave-hero.png',
    'isLive' => false,
    'audioEnabled' => false,
    'heroLogo' => null,
    'useLogoAsTitle' => false,
    'heroLogoHeight' => 120,
])

@php
    $locale = LaravelLocalization::getCurrentLocale();

    // Extract YouTube video ID from various URL formats
    $youtubeVideoId = null;
    if ($youtubeUrl) {
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&\n?#]+)/', $youtubeUrl, $matches)) {
            $youtubeVideoId = $matches[1];
        } elseif (preg_match('/^([a-zA-Z0-9_-]{11})$/', $youtubeUrl, $matches)) {
            $youtubeVideoId = $matches[1];
        }
    }

    $hasVideo = !empty($youtubeVideoId);

    // Use provided values or fallback to translations
    $heroTitle = $title ?? __('messages.home.hero.title');
    $heroSubtitle = $subtitle ?? __('messages.home.hero.subtitle');
    $heroCta = $ctaText ?? __('messages.home.hero.cta');
    $heroCtaUrl = $ctaUrl ?? '/sobre';
@endphp

<section class="relative flex h-screen flex-col justify-end overflow-hidden text-white">
    {{-- YouTube Video Background --}}
    @if($hasVideo)
        <div class="absolute inset-0 overflow-hidden">
            <iframe
                id="hero-youtube-player"
                src="https://www.youtube-nocookie.com/embed/{{ $youtubeVideoId }}?autoplay=1&mute=1&loop=1&playlist={{ $youtubeVideoId }}&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&vq=hd1080&enablejsapi=1&origin={{ url('/') }}"
                allow="autoplay; encrypted-media"
                allowfullscreen
                class="pointer-events-none absolute left-1/2 top-1/2 h-[150%] w-[150%] -translate-x-1/2 -translate-y-1/2"
                style="border: none;"
                title="Background video"
            ></iframe>
        </div>
    @else
        {{-- Fallback Image --}}
        <img
            src="{{ asset($fallbackImage) }}"
            alt="Giant wave at Praia do Norte, Nazare"
            class="absolute inset-0 h-full w-full object-cover"
        />
        <div class="absolute inset-0 bg-black/40"></div>
    @endif

    {{-- LIVE Badge - Top Right --}}
    @if($isLive)
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
    @if($isLive && $audioEnabled && $hasVideo)
        <button
            id="audio-toggle-btn"
            type="button"
            class="absolute bottom-24 right-4 z-20 flex h-12 w-12 items-center justify-center rounded-full bg-black/50 text-white backdrop-blur transition-all hover:bg-black/70 md:bottom-28 md:right-8"
            title="{{ __('messages.home.hero.toggleAudio') }}"
            aria-label="Toggle audio"
        >
            {{-- Muted icon (default) --}}
            <svg id="icon-muted" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                <line x1="23" y1="9" x2="17" y2="15"/>
                <line x1="17" y1="9" x2="23" y2="15"/>
            </svg>
            {{-- Unmuted icon (hidden) --}}
            <svg id="icon-unmuted" class="hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
                <path d="M19.07 4.93a10 10 0 0 1 0 14.14"/>
            </svg>
        </button>
    @endif

    {{-- Content - positioned at bottom --}}
    <div class="container relative z-10 mx-auto px-4 pb-12 text-center">
        @if($useLogoAsTitle && $heroLogo)
            <img
                src="{{ asset('storage/' . $heroLogo) }}"
                alt="Praia do Norte"
                class="mx-auto mb-4 w-auto"
                style="height: {{ $heroLogoHeight }}px;"
            />
        @else
            <h1 class="mb-4 text-5xl font-bold md:text-7xl">
                {{ $heroTitle }}
            </h1>
        @endif
        <p class="mb-8 text-xl md:text-2xl">
            {{ $heroSubtitle }}
        </p>
        <a href="{{ LaravelLocalization::localizeURL($heroCtaUrl) }}"
           class="inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-6 border border-white bg-transparent text-white hover:bg-white/10 transition-colors">
            {{ $heroCta }}
        </a>
    </div>
</section>

{{-- YouTube IFrame API for Audio Control --}}
@if($isLive && $audioEnabled && $hasVideo)
@push('scripts')
<script>
    // YouTube IFrame API
    var player;
    var isMuted = true;

    // This function is called by the YouTube API when ready
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('hero-youtube-player', {
            events: {
                'onReady': onPlayerReady
            }
        });
    }

    function onPlayerReady(event) {
        // Player is ready, audio toggle button is now functional
        var audioBtn = document.getElementById('audio-toggle-btn');
        if (audioBtn) {
            audioBtn.addEventListener('click', toggleAudio);
        }
    }

    function toggleAudio() {
        if (!player) return;

        var iconMuted = document.getElementById('icon-muted');
        var iconUnmuted = document.getElementById('icon-unmuted');

        if (isMuted) {
            player.unMute();
            player.setVolume(100);
            if (iconMuted) iconMuted.classList.add('hidden');
            if (iconUnmuted) iconUnmuted.classList.remove('hidden');
        } else {
            player.mute();
            if (iconMuted) iconMuted.classList.remove('hidden');
            if (iconUnmuted) iconUnmuted.classList.add('hidden');
        }
        isMuted = !isMuted;
    }

    // Load YouTube IFrame API
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
</script>
@endpush
@endif
