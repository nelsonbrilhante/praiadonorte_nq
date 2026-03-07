@php
    $locale = LaravelLocalization::getCurrentLocale();
    $isHomepage = request()->routeIs('home');

    // Determine which entity section is active for pill underline color
    $currentPath = request()->path();
    $activeEntity = 'none';
    if (preg_match('#^(pt|en)/nazare-qualifica#', $currentPath)) {
        $activeEntity = 'nq';
    } elseif (preg_match('#^(pt|en)/carsurf#', $currentPath)) {
        $activeEntity = 'carsurf';
    } elseif (preg_match('#^(pt|en)/praia-norte#', $currentPath)) {
        $activeEntity = 'pn';
    }

    // Detect active top-level pages (Noticias, Eventos, Loja)
    $isNoticias = preg_match('#^(pt|en)/noticias#', $currentPath);
    $isEventos = preg_match('#^(pt|en)/eventos#', $currentPath);
    $isLoja = preg_match('#^(pt|en)/(loja|shop)#', $currentPath);
@endphp

{{-- Wrapper: shares Alpine state between header and overlay (siblings) --}}
<div
    x-data="{
        scrolled: false,
        fullMenuOpen: false,
        openDropdown: null,
        isHomepage: {{ $isHomepage ? 'true' : 'false' }},
        isDark: document.documentElement.classList.contains('dark'),
        lastScrollY: 0,
        headerVisible: true
    }"
    x-init="
        window.addEventListener('scroll', () => {
            const currentY = window.scrollY;
            if (currentY > 80 && currentY > lastScrollY && !fullMenuOpen) {
                headerVisible = false;
            } else {
                headerVisible = true;
            }
            scrolled = currentY > 50;
            lastScrollY = currentY;
        });
        const observer = new MutationObserver(() => { isDark = document.documentElement.classList.contains('dark') });
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    "
    x-effect="document.body.style.overflow = fullMenuOpen ? 'hidden' : ''"
>
    <header
        :class="{
            '-translate-y-full': !headerVisible && !fullMenuOpen,
            'bg-transparent': isHomepage && !scrolled && !fullMenuOpen,
            'bg-background/95 backdrop-blur-lg border-b border-border shadow-sm': scrolled || !isHomepage || fullMenuOpen
        }"
        class="fixed {{ App\Models\SiteSetting::isMaintenanceMode() && auth()->check() ? 'top-6' : 'top-0' }} z-50 w-full transition-transform duration-300"
    >
        <div class="container mx-auto flex h-16 items-center justify-between px-4">

            {{-- Left Zone: Grid Icon + Logo --}}
            <div class="flex items-center gap-3">
                {{-- Grid Icon (opens full-screen menu) --}}
                <button
                    type="button"
                    @click="fullMenuOpen = !fullMenuOpen"
                    :class="(isHomepage && !scrolled && !fullMenuOpen) ? 'text-white bg-white/20 hover:bg-white/20' : 'bg-accent/50 hover:bg-accent'"
                    class="inline-flex items-center justify-center rounded-full h-9 w-9 transition-colors"
                    aria-label="Menu"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"/>
                        <rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/>
                        <rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                </button>

                {{-- Logo --}}
                <a href="{{ LaravelLocalization::localizeURL('/') }}" class="flex items-center">
                    <picture :class="((isHomepage && !scrolled && !fullMenuOpen) || isDark) ? 'block' : 'hidden'">
                        <source media="(min-width: 768px)" srcset="{{ asset('images/logos/imagem-grafica-nq-white-name.svg') }}" type="image/svg+xml">
                        <img
                            src="{{ asset('images/logos/imagem-grafica-nq-white-name@2x.png') }}"
                            alt="Nazaré Qualifica"
                            class="h-10 w-auto transition-opacity duration-300"
                        />
                    </picture>
                    <picture :class="((isHomepage && !scrolled && !fullMenuOpen) || isDark) ? 'hidden' : 'block'">
                        <source media="(min-width: 768px)" srcset="{{ asset('images/logos/imagem-grafica-nq-original-name.svg') }}" type="image/svg+xml">
                        <img
                            src="{{ asset('images/logos/imagem-grafica-nq-original-name@2x.png') }}"
                            alt="Nazaré Qualifica"
                            class="h-10 w-auto transition-opacity duration-300"
                        />
                    </picture>
                </a>
            </div>

            {{-- Center Zone: Nav Pill (Desktop only) --}}
            <nav class="hidden lg:flex items-center">
                <div
                    :class="(isHomepage && !scrolled) ? 'bg-white/15 backdrop-blur-sm' : 'bg-foreground/5 dark:bg-white/10'"
                    class="flex items-center gap-0.5 rounded-full px-1.5 py-1 transition-colors duration-300"
                >
                    {{-- Nazaré Qualifica Dropdown --}}
                    <div class="relative" @mouseenter="openDropdown = 'nq'" @mouseleave="openDropdown = null">
                        <button
                            :class="(isHomepage && !scrolled)
                                ? 'text-white/80 hover:text-white hover:bg-white/10'
                                : 'text-foreground/70 hover:text-foreground hover:bg-foreground/5 dark:text-white/70 dark:hover:text-white dark:hover:bg-white/10'"
                            class="relative inline-flex items-center gap-1 px-4 py-2 text-sm font-medium rounded-full transition-colors"
                        >
                            {{ __('messages.entities.nazareQualifica') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-200" :class="openDropdown === 'nq' ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
                            @if($activeEntity === 'nq')
                                <span class="absolute bottom-0 left-3 right-3 h-0.5 bg-institutional rounded-full"></span>
                            @endif
                        </button>
                        <div x-show="openDropdown === 'nq'" x-cloak
                             x-transition:enter="transition ease-[cubic-bezier(0.4,0,0.2,1)] duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="absolute left-1/2 -translate-x-1/2 top-full pt-3 z-50">
                            <div class="w-56 rounded-lg bg-white dark:bg-popover p-2 shadow-xl border border-border dropdown-stagger"
                                 :class="openDropdown === 'nq' ? 'is-open' : ''">
                                <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/sobre') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.navigation.about') }}
                                </a>
                                <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/equipa') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.breadcrumbs.equipa') }}
                                </a>
                                <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/servicos') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.breadcrumbs.servicos') }}
                                </a>
                                <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/contraordenacoes') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.breadcrumbs.contraordenacoes') }}
                                </a>
                                <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/documentos') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.nq.documentos.nav') }}
                                </a>
                                <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/estacionamento') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.breadcrumbs.estacionamento') }}
                                </a>
                                <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/ale') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.breadcrumbs.ale') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Praia do Norte Dropdown --}}
                    <div class="relative" @mouseenter="openDropdown = 'pn'" @mouseleave="openDropdown = null">
                        <button
                            :class="(isHomepage && !scrolled)
                                ? 'text-white/80 hover:text-white hover:bg-white/10'
                                : 'text-foreground/70 hover:text-foreground hover:bg-foreground/5 dark:text-white/70 dark:hover:text-white dark:hover:bg-white/10'"
                            class="relative inline-flex items-center gap-1 px-4 py-2 text-sm font-medium rounded-full transition-colors"
                        >
                            {{ __('messages.entities.praiaDoNorte') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-200" :class="openDropdown === 'pn' ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
                            @if($activeEntity === 'pn')
                                <span class="absolute bottom-0 left-3 right-3 h-0.5 bg-ocean rounded-full"></span>
                            @endif
                        </button>
                        <div x-show="openDropdown === 'pn'" x-cloak
                             x-transition:enter="transition ease-[cubic-bezier(0.4,0,0.2,1)] duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="absolute left-1/2 -translate-x-1/2 top-full pt-3 z-50">
                            <div class="w-56 rounded-lg bg-white dark:bg-popover p-2 shadow-xl border border-border dropdown-stagger"
                                 :class="openDropdown === 'pn' ? 'is-open' : ''">
                                <a href="{{ LaravelLocalization::localizeURL('/praia-norte/sobre') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.navigation.about') }}
                                </a>
                                <a href="{{ LaravelLocalization::localizeURL('/praia-norte/forte') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.breadcrumbs.forte') }}
                                </a>
                                <a href="{{ LaravelLocalization::localizeURL('/praia-norte/hidrografico') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.breadcrumbs.hidrografico') }}
                                </a>
                                <a href="{{ LaravelLocalization::localizeURL('/praia-norte/surfer-wall') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.navigation.surferWall') }}
                                </a>
                                <a href="{{ LaravelLocalization::localizeURL('/praia-norte/previsoes') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.navigation.forecast') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Carsurf Dropdown --}}
                    <div class="relative" @mouseenter="openDropdown = 'carsurf'" @mouseleave="openDropdown = null">
                        <button
                            :class="(isHomepage && !scrolled)
                                ? 'text-white/80 hover:text-white hover:bg-white/10'
                                : 'text-foreground/70 hover:text-foreground hover:bg-foreground/5 dark:text-white/70 dark:hover:text-white dark:hover:bg-white/10'"
                            class="relative inline-flex items-center gap-1 px-4 py-2 text-sm font-medium rounded-full transition-colors"
                        >
                            {{ __('messages.entities.carsurf') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-200" :class="openDropdown === 'carsurf' ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
                            @if($activeEntity === 'carsurf')
                                <span class="absolute bottom-0 left-3 right-3 h-0.5 bg-performance rounded-full"></span>
                            @endif
                        </button>
                        <div x-show="openDropdown === 'carsurf'" x-cloak
                             x-transition:enter="transition ease-[cubic-bezier(0.4,0,0.2,1)] duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="absolute left-1/2 -translate-x-1/2 top-full pt-3 z-50">
                            <div class="w-56 rounded-lg bg-white dark:bg-popover p-2 shadow-xl border border-border dropdown-stagger"
                                 :class="openDropdown === 'carsurf' ? 'is-open' : ''">
                                <a href="{{ LaravelLocalization::localizeURL('/carsurf/sobre') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.navigation.about') }}
                                </a>
                                <a href="{{ LaravelLocalization::localizeURL('/carsurf/instalacoes') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.pages.facilities') }}
                                </a>
                                <a href="{{ LaravelLocalization::localizeURL('/carsurf/formularios') }}" class="block rounded-md px-3 py-2.5 text-sm font-medium text-foreground hover:bg-muted transition-colors dark:text-white">
                                    {{ __('messages.pages.forms') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Eventos (direct link) --}}
                    <a
                        href="{{ LaravelLocalization::localizeURL('/eventos') }}"
                        :class="(isHomepage && !scrolled)
                            ? 'text-white/80 hover:text-white hover:bg-white/10'
                            : 'text-foreground/70 hover:text-foreground hover:bg-foreground/5 dark:text-white/70 dark:hover:text-white dark:hover:bg-white/10'"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-full transition-colors"
                    >
                        {{ __('messages.navigation.events') }}
                        @if($isEventos)
                            <span class="absolute bottom-0 left-3 right-3 h-0.5 bg-ocean rounded-full"></span>
                        @endif
                    </a>

                    {{-- Notícias (direct link) --}}
                    <a
                        href="{{ LaravelLocalization::localizeURL('/noticias') }}"
                        :class="(isHomepage && !scrolled)
                            ? 'text-white/80 hover:text-white hover:bg-white/10'
                            : 'text-foreground/70 hover:text-foreground hover:bg-foreground/5 dark:text-white/70 dark:hover:text-white dark:hover:bg-white/10'"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-full transition-colors"
                    >
                        {{ __('messages.navigation.news') }}
                        @if($isNoticias)
                            <span class="absolute bottom-0 left-3 right-3 h-0.5 bg-ocean rounded-full"></span>
                        @endif
                    </a>

                    {{-- Loja (direct link) --}}
                    <a
                        href="{{ LaravelLocalization::localizeURL(LaravelLocalization::getCurrentLocale() === 'pt' ? '/loja' : '/shop') }}"
                        :class="(isHomepage && !scrolled)
                            ? 'text-white/80 hover:text-white hover:bg-white/10'
                            : 'text-foreground/70 hover:text-foreground hover:bg-foreground/5 dark:text-white/70 dark:hover:text-white dark:hover:bg-white/10'"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-full transition-colors"
                    >
                        {{ __('messages.navigation.shop') }}
                        @if($isLoja)
                            <span class="absolute bottom-0 left-3 right-3 h-0.5 bg-ocean rounded-full"></span>
                        @endif
                    </a>
                </div>
            </nav>

            {{-- Right Zone: Icons --}}
            <div class="flex items-center gap-1.5">
                {{-- Dark Mode Toggle --}}
                <button
                    type="button"
                    x-data="{ isDark: document.documentElement.classList.contains('dark') }"
                    @click="
                        isDark = !isDark;
                        document.documentElement.classList.toggle('dark', isDark);
                        localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    "
                    :class="(isHomepage && !scrolled && !fullMenuOpen) ? 'text-white hover:bg-white/10' : 'hover:bg-accent'"
                    class="inline-flex items-center justify-center rounded-full h-9 w-9 transition-colors"
                    :title="isDark ? '{{ __('messages.theme.switchToLight') ?? 'Mudar para modo claro' }}' : '{{ __('messages.theme.switchToDark') ?? 'Mudar para modo escuro' }}'"
                >
                    <svg x-show="isDark" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
                    </svg>
                    <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                    </svg>
                </button>

                {{-- Language Switcher --}}
                <div>
                    <livewire:language-switcher />
                </div>

                {{-- Auth Button --}}
                @auth
                    <a href="{{ url('/admin') }}"
                       :class="(isHomepage && !scrolled && !fullMenuOpen) ? 'text-white hover:bg-white/10' : 'hover:bg-accent'"
                       class="inline-flex items-center justify-center rounded-full h-9 w-9 transition-colors"
                       title="Dashboard">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ url('/admin/login') }}"
                       :class="(isHomepage && !scrolled && !fullMenuOpen) ? 'text-white hover:bg-white/10' : 'hover:bg-accent'"
                       class="inline-flex items-center justify-center rounded-full h-9 w-9 transition-colors"
                       title="Login">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </a>
                @endauth

                {{-- Search --}}
                <button
                    type="button"
                    @click="$dispatch('open-search')"
                    :class="(isHomepage && !scrolled && !fullMenuOpen) ? 'text-white hover:bg-white/10' : 'hover:bg-accent'"
                    class="inline-flex items-center justify-center rounded-full h-9 w-9 transition-colors"
                    aria-label="{{ __('messages.search.placeholder') }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    {{-- ============================================
       FULL-SCREEN OVERLAY MENU
       Sibling of <header>, not child — fixed positioning works correctly
       ============================================ --}}
    <div
        x-show="fullMenuOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="fullMenuOpen = false"
        class="fixed inset-0 z-[60] bg-[#0b1022] overflow-y-auto"
    >
        {{-- Top Bar --}}
        <div class="container mx-auto flex h-16 items-center justify-between px-4">
            <a href="{{ LaravelLocalization::localizeURL('/') }}" @click="fullMenuOpen = false" class="flex items-center">
                <picture>
                    <source media="(min-width: 768px)" srcset="{{ asset('images/logos/imagem-grafica-nq-white-name.svg') }}" type="image/svg+xml">
                    <img
                        src="{{ asset('images/logos/imagem-grafica-nq-white-name@2x.png') }}"
                        alt="Nazaré Qualifica"
                        class="h-10 w-auto"
                    />
                </picture>
            </a>
            <button
                type="button"
                @click="fullMenuOpen = false"
                class="inline-flex items-center justify-center rounded-full h-9 w-9 text-white hover:bg-white/10 transition-colors"
                aria-label="Close menu"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>

        {{-- Menu Content --}}
        <div class="container mx-auto px-4 py-8 md:py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16">
                {{-- Left: Navigation --}}
                <div class="space-y-8">
                    {{-- Nazaré Qualifica --}}
                    <div class="border-l-2 border-institutional pl-6">
                        <h3 class="text-2xl font-bold text-white md:text-3xl mb-4">
                            {{ __('messages.entities.nazareQualifica') }}
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/sobre') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.navigation.about') }}
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/equipa') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.breadcrumbs.equipa') }}
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/servicos') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.breadcrumbs.servicos') }}
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/contraordenacoes') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.breadcrumbs.contraordenacoes') }}
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/documentos') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.nq.documentos.nav') }}
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/estacionamento') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.breadcrumbs.estacionamento') }}
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/ale') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.breadcrumbs.ale') }}
                            </a>
                        </div>
                    </div>

                    {{-- Praia do Norte --}}
                    <div class="border-l-2 border-ocean pl-6">
                        <h3 class="text-2xl font-bold text-white md:text-3xl mb-4">
                            {{ __('messages.entities.praiaDoNorte') }}
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ LaravelLocalization::localizeURL('/praia-norte/sobre') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.navigation.about') }}
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/praia-norte/forte') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.breadcrumbs.forte') }}
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/praia-norte/hidrografico') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.breadcrumbs.hidrografico') }}
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/praia-norte/surfer-wall') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.navigation.surferWall') }}
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/praia-norte/previsoes') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.navigation.forecast') }}
                            </a>
                        </div>
                    </div>

                    {{-- Carsurf --}}
                    <div class="border-l-2 border-performance pl-6">
                        <h3 class="text-2xl font-bold text-white md:text-3xl mb-4">
                            {{ __('messages.entities.carsurf') }}
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ LaravelLocalization::localizeURL('/carsurf/sobre') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.navigation.about') }}
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/carsurf/instalacoes') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.pages.facilities') }}
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/carsurf/formularios') }}" @click="fullMenuOpen = false" class="block text-lg text-white/60 hover:text-white transition-colors">
                                {{ __('messages.pages.forms') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right: Quick Links / Highlights --}}
                <div class="space-y-8">
                    <div>
                        <h4 class="text-sm font-semibold text-white/40 uppercase tracking-wider mb-4">{{ __('messages.navigation.home') }}</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ LaravelLocalization::localizeURL('/noticias') }}" @click="fullMenuOpen = false" class="group block rounded-xl bg-white/5 p-4 hover:bg-white/10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-ocean mb-3">
                                    <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/>
                                </svg>
                                <span class="text-sm font-medium text-white">{{ __('messages.navigation.news') }}</span>
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/eventos') }}" @click="fullMenuOpen = false" class="group block rounded-xl bg-white/5 p-4 hover:bg-white/10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-ocean mb-3">
                                    <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>
                                </svg>
                                <span class="text-sm font-medium text-white">{{ __('messages.navigation.events') }}</span>
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/praia-norte/surfer-wall') }}" @click="fullMenuOpen = false" class="group block rounded-xl bg-white/5 p-4 hover:bg-white/10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-ocean mb-3">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                                <span class="text-sm font-medium text-white">{{ __('messages.navigation.surferWall') }}</span>
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/praia-norte/previsoes') }}" @click="fullMenuOpen = false" class="group block rounded-xl bg-white/5 p-4 hover:bg-white/10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-ocean mb-3">
                                    <path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/>
                                </svg>
                                <span class="text-sm font-medium text-white">{{ __('messages.navigation.forecast') }}</span>
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL(LaravelLocalization::getCurrentLocale() === 'pt' ? '/loja' : '/shop') }}" @click="fullMenuOpen = false" class="group block rounded-xl bg-white/5 p-4 hover:bg-white/10 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-ocean mb-3">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                                </svg>
                                <span class="text-sm font-medium text-white">{{ __('messages.navigation.shop') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom: Language + Search --}}
            <div class="mt-12 flex items-center gap-4 border-t border-white/10 pt-6">
                <livewire:language-switcher />
                <button
                    type="button"
                    @click="fullMenuOpen = false; $nextTick(() => $dispatch('open-search'))"
                    class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-sm text-white hover:bg-white/15 transition-colors"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                    {{ __('messages.search.placeholder') }}
                </button>
            </div>
        </div>
    </div>
</div>
