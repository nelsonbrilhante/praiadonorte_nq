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
        class="language-toggle inline-flex items-center justify-center rounded-full text-xs font-semibold h-9 w-9 transition-all duration-200"
        aria-label="{{ __('messages.accessibility.languageSelector') }}"
        title="{{ $currentLocale === 'pt' ? 'Switch to English' : 'Mudar para Português' }}"
    >
        {{ strtoupper($targetLocale) }}
    </a>

    <style>
        /* Default state (solid header) */
        .language-toggle {
            color: hsl(var(--foreground));
        }
        .language-toggle:hover {
            background-color: hsl(var(--accent));
            color: hsl(var(--accent-foreground));
        }

        /* Transparent header state (homepage, not scrolled) */
        header[class*="bg-transparent"] .language-toggle {
            color: white;
        }
        header[class*="bg-transparent"] .language-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        /* Full-screen overlay menu (dark background) */
        .bg-\[#0b1022\] .language-toggle {
            color: white;
            border-color: rgba(255, 255, 255, 0.2);
            border-width: 1px;
        }
        .bg-\[#0b1022\] .language-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
    </style>
</div>
