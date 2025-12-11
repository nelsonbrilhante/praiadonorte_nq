@php
    $locale = LaravelLocalization::getCurrentLocale();
@endphp

<header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
    <div class="container mx-auto flex h-16 items-center justify-between px-4">
        {{-- Logo --}}
        <a href="{{ LaravelLocalization::localizeURL('/') }}" class="flex items-center gap-2">
            <div class="flex flex-col">
                <span class="text-xl font-bold text-ocean">Nazaré Qualifica</span>
                <span class="text-[10px] text-muted-foreground">Nazaré, Portugal</span>
            </div>
        </a>

        {{-- Navigation (Desktop) --}}
        <nav class="hidden md:flex items-center gap-1">
            <a href="{{ LaravelLocalization::localizeURL('/') }}"
               class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 {{ request()->is($locale) ? 'bg-accent' : '' }}">
                {{ __('messages.navigation.home') }}
            </a>

            {{-- About Dropdown --}}
            <div class="relative group">
                <button class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 gap-1">
                    {{ __('messages.navigation.about') }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:rotate-180">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </button>
                <div class="absolute left-0 top-full pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                    <div class="w-[400px] rounded-md border bg-popover p-4 shadow-lg">
                        <div class="grid gap-3 md:grid-cols-2">
                            <a href="{{ LaravelLocalization::localizeURL('/sobre') }}"
                               class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground">
                                <div class="text-sm font-medium leading-none text-ocean">
                                    {{ __('messages.entities.praiaDoNorte') }}
                                </div>
                                <p class="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                    Ondas gigantes e surf de elite
                                </p>
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/carsurf') }}"
                               class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground">
                                <div class="text-sm font-medium leading-none text-performance">
                                    {{ __('messages.entities.carsurf') }}
                                </div>
                                <p class="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                    Centro de alto rendimento
                                </p>
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/sobre') }}"
                               class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground">
                                <div class="text-sm font-medium leading-none text-institutional">
                                    {{ __('messages.entities.nazareQualifica') }}
                                </div>
                                <p class="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                    {{ __('messages.nq.about.subtitle') }}
                                </p>
                            </a>
                            <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/servicos') }}"
                               class="block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground">
                                <div class="text-sm font-medium leading-none text-institutional">
                                    {{ __('messages.nq.services.title') }}
                                </div>
                                <p class="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                    {{ __('messages.nq.services.subtitle') }}
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ LaravelLocalization::localizeURL('/noticias') }}"
               class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 {{ request()->is($locale.'/noticias*') ? 'bg-accent' : '' }}">
                {{ __('messages.navigation.news') }}
            </a>

            <a href="{{ LaravelLocalization::localizeURL('/surfer-wall') }}"
               class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 {{ request()->is($locale.'/surfer-wall*') ? 'bg-accent' : '' }}">
                {{ __('messages.navigation.surferWall') }}
            </a>

            <a href="{{ LaravelLocalization::localizeURL('/eventos') }}"
               class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 {{ request()->is($locale.'/eventos*') ? 'bg-accent' : '' }}">
                {{ __('messages.navigation.events') }}
            </a>

            <a href="{{ LaravelLocalization::localizeURL('/previsoes') }}"
               class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 {{ request()->is($locale.'/previsoes*') ? 'bg-accent' : '' }}">
                {{ __('messages.navigation.forecast') }}
            </a>
        </nav>

        {{-- Right Side --}}
        <div class="flex items-center gap-2">
            {{-- Search Button --}}
            <button type="button" class="hidden lg:inline-flex items-center gap-2 rounded-md border bg-muted/50 px-3 py-1.5 text-sm text-muted-foreground transition-colors hover:bg-muted">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
                <span>{{ __('messages.search.placeholder') }}</span>
                <kbd class="pointer-events-none hidden h-5 select-none items-center gap-1 rounded border bg-background px-1.5 font-mono text-[10px] font-medium opacity-100 sm:flex">
                    <span class="text-xs">⌘</span>K
                </kbd>
            </button>

            {{-- Dark Mode Toggle --}}
            <button type="button" onclick="document.documentElement.classList.toggle('dark')" class="inline-flex items-center justify-center rounded-md text-sm font-medium h-10 w-10 hover:bg-accent transition-colors" title="Toggle dark mode">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="dark:hidden">
                    <circle cx="12" cy="12" r="4"/>
                    <path d="M12 2v2"/>
                    <path d="M12 20v2"/>
                    <path d="m4.93 4.93 1.41 1.41"/>
                    <path d="m17.66 17.66 1.41 1.41"/>
                    <path d="M2 12h2"/>
                    <path d="M20 12h2"/>
                    <path d="m6.34 17.66-1.41 1.41"/>
                    <path d="m19.07 4.93-1.41 1.41"/>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden dark:block">
                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                </svg>
            </button>

            {{-- Language Switcher --}}
            <livewire:language-switcher />

            {{-- Contact Button --}}
            <a href="{{ LaravelLocalization::localizeURL('/contacto') }}"
               class="hidden sm:inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-4 py-2 bg-ocean text-white hover:bg-ocean-dark transition-colors">
                {{ __('messages.navigation.contact') }}
            </a>

            {{-- Mobile Menu Button --}}
            <button type="button" class="md:hidden inline-flex items-center justify-center rounded-md text-sm font-medium h-10 w-10 hover:bg-accent" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" x2="20" y1="12" y2="12"/>
                    <line x1="4" x2="20" y1="6" y2="6"/>
                    <line x1="4" x2="20" y1="18" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden border-t">
        <nav class="container mx-auto px-4 py-4 space-y-2">
            <a href="{{ LaravelLocalization::localizeURL('/') }}" class="block py-2 text-sm hover:text-ocean">
                {{ __('messages.navigation.home') }}
            </a>

            {{-- Praia do Norte --}}
            <div class="py-2">
                <span class="text-xs font-semibold text-ocean uppercase tracking-wide">{{ __('messages.entities.praiaDoNorte') }}</span>
                <div class="mt-1 space-y-1 pl-2">
                    <a href="{{ LaravelLocalization::localizeURL('/sobre') }}" class="block py-1 text-sm hover:text-ocean">
                        {{ __('messages.navigation.about') }}
                    </a>
                    <a href="{{ LaravelLocalization::localizeURL('/surfer-wall') }}" class="block py-1 text-sm hover:text-ocean">
                        {{ __('messages.navigation.surferWall') }}
                    </a>
                    <a href="{{ LaravelLocalization::localizeURL('/previsoes') }}" class="block py-1 text-sm hover:text-ocean">
                        {{ __('messages.navigation.forecast') }}
                    </a>
                </div>
            </div>

            {{-- Carsurf --}}
            <div class="py-2">
                <span class="text-xs font-semibold text-performance uppercase tracking-wide">{{ __('messages.entities.carsurf') }}</span>
                <div class="mt-1 space-y-1 pl-2">
                    <a href="{{ LaravelLocalization::localizeURL('/carsurf') }}" class="block py-1 text-sm hover:text-performance">
                        {{ __('messages.carsurf.hero.about') }}
                    </a>
                    <a href="{{ LaravelLocalization::localizeURL('/carsurf/programas') }}" class="block py-1 text-sm hover:text-performance">
                        {{ __('messages.pages.programs') }}
                    </a>
                </div>
            </div>

            {{-- Nazaré Qualifica --}}
            <div class="py-2">
                <span class="text-xs font-semibold text-institutional uppercase tracking-wide">{{ __('messages.entities.nazareQualifica') }}</span>
                <div class="mt-1 space-y-1 pl-2">
                    <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/sobre') }}" class="block py-1 text-sm hover:text-institutional">
                        {{ __('messages.navigation.about') }}
                    </a>
                    <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/servicos') }}" class="block py-1 text-sm hover:text-institutional">
                        {{ __('messages.nq.services.title') }}
                    </a>
                    <a href="{{ LaravelLocalization::localizeURL('/nazare-qualifica/equipa') }}" class="block py-1 text-sm hover:text-institutional">
                        {{ __('messages.nq.team.title') }}
                    </a>
                </div>
            </div>

            {{-- Content --}}
            <div class="border-t pt-2 mt-2 space-y-2">
                <a href="{{ LaravelLocalization::localizeURL('/noticias') }}" class="block py-2 text-sm hover:text-ocean">
                    {{ __('messages.navigation.news') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/eventos') }}" class="block py-2 text-sm hover:text-ocean">
                    {{ __('messages.navigation.events') }}
                </a>
                <a href="{{ LaravelLocalization::localizeURL('/contacto') }}" class="block py-2 text-sm text-ocean font-medium">
                    {{ __('messages.navigation.contact') }}
                </a>
            </div>
        </nav>
    </div>
</header>
