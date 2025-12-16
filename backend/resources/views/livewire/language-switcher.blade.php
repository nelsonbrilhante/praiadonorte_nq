@php
    $currentPath = request()->path();
    $pathWithoutLocale = preg_replace('/^(pt|en)\//', '', $currentPath);
    $pathWithoutLocale = preg_replace('/^(pt|en)$/', '', $pathWithoutLocale);
    $targetLocale = $currentLocale === 'pt' ? 'en' : 'pt';
    $targetUrl = '/' . $targetLocale . ($pathWithoutLocale ? '/' . $pathWithoutLocale : '');
@endphp

<div>
    <a
        href="{{ $targetUrl }}"
        class="language-toggle inline-flex items-center justify-center rounded-md text-xs font-semibold h-8 w-8 border transition-all duration-200"
        aria-label="{{ __('messages.accessibility.languageSelector') }}"
        title="{{ $currentLocale === 'pt' ? 'Switch to English' : 'Mudar para Português' }}"
    >
        {{ strtoupper($targetLocale) }}
    </a>

    <style>
        /* Default state (solid header) */
        .language-toggle {
            border-color: hsl(var(--border));
            background-color: transparent;
            color: hsl(var(--foreground));
        }
        .language-toggle:hover {
            background-color: hsl(var(--accent));
            color: hsl(var(--accent-foreground));
        }

        /* Transparent header state (homepage, not scrolled) */
        header[class*="bg-transparent"] .language-toggle {
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
        }
        header[class*="bg-transparent"] .language-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
    </style>
</div>
