@props([
    'variant' => 'default',
    'size' => 'default',
    'href' => null,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:ring-ring/50 focus-visible:ring-[3px]';

    $variantClasses = match($variant) {
        'default' => 'bg-primary text-primary-foreground hover:bg-primary/90',
        'destructive' => 'bg-destructive text-white hover:bg-destructive/90 focus-visible:ring-destructive/20',
        'outline' => 'border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground',
        'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary/80',
        'ghost' => 'hover:bg-accent hover:text-accent-foreground',
        'link' => 'text-primary underline-offset-4 hover:underline',
        'ocean' => 'bg-ocean text-white hover:bg-ocean-600',
        'institutional' => 'bg-institutional text-white hover:bg-institutional-600',
        'performance' => 'bg-performance text-white hover:bg-performance-600',
        default => 'bg-primary text-primary-foreground hover:bg-primary/90',
    };

    $sizeClasses = match($size) {
        'default' => 'h-9 px-4 py-2',
        'sm' => 'h-8 rounded-md gap-1.5 px-3',
        'lg' => 'h-10 rounded-md px-6',
        'icon' => 'size-9',
        'icon-sm' => 'size-8',
        'icon-lg' => 'size-10',
        default => 'h-9 px-4 py-2',
    };

    $classes = implode(' ', [$baseClasses, $variantClasses, $sizeClasses]);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
