@php
    $currentPath = request()->path();
    $pathWithoutLocale = preg_replace('/^(pt|en)\//', '', $currentPath);
    $pathWithoutLocale = preg_replace('/^(pt|en)$/', '', $pathWithoutLocale);
@endphp

<div class="relative" x-data="{ open: false }">
    <button
        @click="open = !open"
        @click.outside="open = false"
        class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium h-10 px-3 hover:bg-accent transition-colors"
        aria-label="{{ __('messages.accessibility.languageSelector') }}"
    >
        <span class="text-base">{{ $currentLocale === 'pt' ? '🇵🇹' : '🇬🇧' }}</span>
        <span>{{ $currentLocale === 'pt' ? 'Português' : 'English' }}</span>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-32 rounded-md border bg-popover shadow-lg z-50"
        style="display: none;"
    >
        <div class="py-1">
            @foreach($locales as $localeCode => $properties)
                <a
                    href="/{{ $localeCode }}{{ $pathWithoutLocale ? '/' . $pathWithoutLocale : '' }}"
                    class="w-full px-4 py-2 text-sm text-left hover:bg-accent transition-colors flex items-center gap-2 {{ $currentLocale === $localeCode ? 'bg-accent font-medium' : '' }}"
                >
                    <span class="text-base">{{ $localeCode === 'pt' ? '🇵🇹' : '🇬🇧' }}</span>
                    <span>{{ $properties['native'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
