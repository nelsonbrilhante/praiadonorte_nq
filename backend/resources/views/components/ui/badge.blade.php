@props([
    'variant' => 'default',
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center rounded-full border px-2 py-0.5 text-xs font-medium w-fit whitespace-nowrap shrink-0 gap-1 transition-colors overflow-hidden';

    $variantClasses = match($variant) {
        'default' => 'border-transparent bg-primary text-primary-foreground',
        'secondary' => 'border-transparent bg-secondary text-secondary-foreground',
        'destructive' => 'border-transparent bg-destructive text-white',
        'outline' => 'text-foreground',
        'ocean' => 'border-transparent bg-ocean/10 text-ocean',
        'institutional' => 'border-transparent bg-institutional/10 text-institutional',
        'performance' => 'border-transparent bg-performance/10 text-performance',
        'success' => 'border-transparent bg-green-100 text-green-800',
        'warning' => 'border-transparent bg-amber-100 text-amber-800',
        default => 'border-transparent bg-primary text-primary-foreground',
    };

    $classes = implode(' ', [$baseClasses, $variantClasses]);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes . ' hover:opacity-80']) }}>
        {{ $slot }}
    </a>
@else
    <span {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </span>
@endif
