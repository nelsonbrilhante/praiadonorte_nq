@props([
    'title' => null,
    'subtitle' => null,
    'ctaText' => null,
    'ctaUrl' => null,
    'youtubeUrl' => null,
    'fallbackImage' => '/pn-ai-wave-hero.png',
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

<section class="relative flex min-h-[70vh] flex-col justify-end overflow-hidden text-white">
    {{-- YouTube Video Background --}}
    @if($hasVideo)
        <div class="absolute inset-0 overflow-hidden">
            <iframe
                src="https://www.youtube.com/embed/{{ $youtubeVideoId }}?autoplay=1&mute=1&loop=1&playlist={{ $youtubeVideoId }}&controls=0&showinfo=0&rel=0&modestbranding=1&playsinline=1&vq=hd1080"
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

    {{-- Content - positioned at bottom --}}
    <div class="container relative z-10 mx-auto px-4 pb-12 text-center">
        <h1 class="mb-4 text-5xl font-bold md:text-7xl">
            {{ $heroTitle }}
        </h1>
        <p class="mb-8 text-xl md:text-2xl">
            {{ $heroSubtitle }}
        </p>
        <a href="{{ LaravelLocalization::localizeURL($heroCtaUrl) }}"
           class="inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-6 border border-white bg-transparent text-white hover:bg-white/10 transition-colors">
            {{ $heroCta }}
        </a>
    </div>
</section>
