@props([
    'title' => '',
    'subtitle' => '',
    'entity' => 'praia-norte',
    'image' => '',
])

@php
    $gradientClass = match($entity) {
        'carsurf' => 'gradient-performance',
        'nazare-qualifica' => 'gradient-institutional',
        default => 'gradient-ocean-deep',
    };
@endphp

@if($image)
<section class="relative overflow-hidden min-h-[320px] py-16 md:py-24 text-white">
    <img src="{{ $image }}" class="absolute inset-0 h-full w-full object-cover" alt="" />
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-black/20"></div>
    <div class="container relative mx-auto px-4">
        <h1 class="mb-4 text-4xl font-bold md:text-5xl lg:text-6xl">{{ $title }}</h1>
        @if($subtitle)
            <p class="max-w-2xl text-lg opacity-90 md:text-xl">{{ $subtitle }}</p>
        @endif
        @if($slot->isNotEmpty())
            <div class="mt-8 flex flex-wrap gap-4">
                {{ $slot }}
            </div>
        @endif
    </div>
</section>
@else
<section class="{{ $gradientClass }} relative overflow-hidden py-16 text-white md:py-24">
    <div class="container relative mx-auto px-4">
        <h1 class="mb-4 text-4xl font-bold md:text-5xl lg:text-6xl">{{ $title }}</h1>
        @if($subtitle)
            <p class="max-w-2xl text-lg opacity-90 md:text-xl">{{ $subtitle }}</p>
        @endif
        @if($slot->isNotEmpty())
            <div class="mt-8 flex flex-wrap gap-4">
                {{ $slot }}
            </div>
        @endif
    </div>
</section>
@endif
