@php
    $locale = app('laravellocalization')->getCurrentLocale();
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero title="{{ __('messages.forte.title') }}" subtitle="{{ __('messages.forte.subtitle') }}" entity="praia-norte" image="{{ $page->hero_image ? asset('storage/' . $page->hero_image) : '' }}" />

    {{-- Introduction --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-6xl">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:items-center">
                    <div class="relative aspect-[4/3] overflow-hidden rounded-lg bg-muted">
                        <img
                            src="{{ asset('images/forte/intro-aerea.jpg') }}"
                            alt="{{ __('messages.forte.images.intro') }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        />
                    </div>
                    <div class="prose prose-lg max-w-none">
                        <p class="text-lg text-muted-foreground leading-relaxed">
                            {{ __('messages.forte.intro') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Brief History --}}
    <section class="bg-muted/10 py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-6xl">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:items-center">
                    <div>
                        <h2 class="mb-6 text-3xl font-bold">{{ __('messages.forte.history.title') }}</h2>
                        <p class="text-lg text-muted-foreground leading-relaxed">
                            {{ __('messages.forte.history.text') }}
                        </p>
                    </div>
                    <div class="relative aspect-[4/3] overflow-hidden rounded-lg bg-muted">
                        <img
                            src="{{ asset('images/forte/historia.jpg') }}"
                            alt="{{ __('messages.forte.images.history') }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Centro Interpretativo --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-6xl">
                <h2 class="mb-6 text-3xl font-bold">{{ __('messages.forte.centro.title') }}</h2>
                <p class="mb-8 text-lg text-muted-foreground leading-relaxed max-w-4xl">
                    {{ __('messages.forte.centro.text') }}
                </p>
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:items-center">
                    <div class="relative aspect-[4/3] overflow-hidden rounded-lg bg-muted order-2 lg:order-1">
                        <img
                            src="{{ asset('images/forte/sala-ih.jpg') }}"
                            alt="{{ __('messages.forte.images.salaIh') }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        />
                    </div>
                    <div class="order-1 lg:order-2">
                        <div class="rounded-lg bg-ocean/5 border border-ocean/20 p-6">
                            <h3 class="mb-3 text-xl font-semibold text-ocean-deep">{{ __('messages.forte.centro.ih.title') }}</h3>
                            <p class="text-muted-foreground leading-relaxed">
                                {{ __('messages.forte.centro.ih.text') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Surfer Wall --}}
    <section class="bg-muted/10 py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-6xl">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:items-center">
                    <div>
                        <h2 class="mb-6 text-3xl font-bold">{{ __('messages.forte.surferWall.title') }}</h2>
                        <p class="text-lg text-muted-foreground leading-relaxed">
                            {{ __('messages.forte.surferWall.text') }}
                        </p>
                    </div>
                    <div class="relative aspect-[4/3] overflow-hidden rounded-lg bg-muted">
                        <img
                            src="{{ asset('images/forte/surfer-wall-sala.jpg') }}"
                            alt="{{ __('messages.forte.images.surferWall') }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="bg-muted/10 py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl">
                <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-ocean">1577</div>
                        <p class="text-sm text-muted-foreground">{{ __('messages.forte.stats.year') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-ocean">650k+</div>
                        <p class="text-sm text-muted-foreground">{{ __('messages.forte.stats.visitors') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-ocean">2015</div>
                        <p class="text-sm text-muted-foreground">{{ __('messages.forte.stats.centro') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-ocean">2016</div>
                        <p class="text-sm text-muted-foreground">{{ __('messages.forte.stats.surferWall') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="gradient-ocean-deep py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.forte.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.forte.cta.subtitle') }}</p>
            <div class="flex flex-wrap justify-center gap-4">
                <x-ui.button href="{{ route('pn.surfers.index') }}" class="bg-white text-ocean hover:bg-white/90">
                    {{ __('messages.navigation.surferWall') }}
                </x-ui.button>
                <x-ui.button href="{{ route('pn.forecast') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                    {{ __('messages.navigation.forecast') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.app>
