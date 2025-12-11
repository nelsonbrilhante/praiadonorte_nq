<textarea
    {{ $attributes->merge([
        'class' => 'placeholder:text-muted-foreground border-input min-h-[80px] w-full min-w-0 rounded-md border bg-transparent px-3 py-2 text-base shadow-xs transition-colors outline-none disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] resize-y'
    ]) }}
>{{ $slot }}</textarea>
