@props([
    'value' => '0',
    'label' => '',
    'color' => 'text-ocean',
])

@php
    // Extract numeric part and suffix (e.g., "30m+" -> number: 30, suffix: "m+")
    preg_match('/^(\d+)(.*)$/', $value, $matches);
    $isNumeric = !empty($matches[1]);
    $number = $isNumeric ? (int) $matches[1] : 0;
    $suffix = $isNumeric ? ($matches[2] ?? '') : '';
@endphp

<div
    class="text-center"
    x-data="{ count: 0, target: {{ $number }}, shown: false }"
    x-intersect.once="
        shown = true;
        if ({{ $isNumeric ? 'true' : 'false' }}) {
            let start = 0;
            const duration = 1500;
            const step = Math.max(1, Math.floor(target / 60));
            const interval = setInterval(() => {
                start += step;
                if (start >= target) {
                    count = target;
                    clearInterval(interval);
                } else {
                    count = start;
                }
            }, duration / 60);
        }
    "
>
    <div
        class="text-4xl font-bold md:text-5xl {{ $color }}"
        :class="shown ? 'animate-[counter-in_0.6s_ease-out_forwards]' : 'opacity-0'"
    >
        @if($isNumeric)
            <span x-text="count">0</span>{{ $suffix }}
        @else
            {{ $value }}
        @endif
    </div>
    <p class="mt-2 text-sm text-white/70 md:text-base">{{ $label }}</p>
</div>
