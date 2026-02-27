@props([
    'noPadding' => false,
    'variant' => 'default',
])

@php
    $variantClasses = match($variant) {
        'editorial' => 'relative overflow-hidden rounded-xl',
        default => 'bg-card text-card-foreground rounded-xl border shadow-none hover:shadow-none transition-colors duration-150',
    };

    $paddingClass = $noPadding ? '' : ' py-6';
@endphp

<div {{ $attributes->merge(['class' => $variantClasses . ' flex flex-col gap-6' . $paddingClass]) }}>
    {{ $slot }}
</div>
