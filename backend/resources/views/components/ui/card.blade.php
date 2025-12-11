@props([
    'noPadding' => false,
])

<div {{ $attributes->merge(['class' => 'bg-card text-card-foreground flex flex-col gap-6 rounded-xl border shadow-sm' . ($noPadding ? '' : ' py-6')]) }}>
    {{ $slot }}
</div>
