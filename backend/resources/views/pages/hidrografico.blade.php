@php
    $locale = app('laravellocalization')->getCurrentLocale();
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <x-praia-norte.page-hero title="{{ __('messages.hidrografico.title') }}" subtitle="{{ __('messages.hidrografico.subtitle') }}" entity="praia-norte" image="{{ asset('images/hidrografico/hero-costa.jpg') }}" />

    {{-- Introduction (Canhão da Nazaré) --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-6xl">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:items-center">
                    <div class="relative aspect-[4/3] overflow-hidden rounded-lg bg-muted">
                        <img
                            src="{{ asset('images/hidrografico/canhao-vista-aerea.jpg') }}"
                            alt="{{ __('messages.hidrografico.images.canhao') }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        />
                    </div>
                    <div>
                        <h2 class="mb-6 text-3xl font-bold">{{ __('messages.hidrografico.intro.title') }}</h2>
                        <p class="text-lg text-muted-foreground leading-relaxed">
                            {{ __('messages.hidrografico.intro.text') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Canyon Details (O Maior Desfiladeiro) --}}
    <section class="bg-muted/10 py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-6xl">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:items-center">
                    <div>
                        <h2 class="mb-6 text-3xl font-bold">{{ __('messages.hidrografico.canyon.title') }}</h2>
                        <p class="text-lg text-muted-foreground leading-relaxed">
                            {{ __('messages.hidrografico.canyon.text') }}
                        </p>
                    </div>
                    <div class="relative aspect-[4/3] overflow-hidden rounded-lg bg-muted">
                        <img
                            src="{{ asset('images/hidrografico/surfista-onda.jpg') }}"
                            alt="{{ __('messages.hidrografico.images.surfista') }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Wave Characteristics --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-6xl">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:items-center">
                    <div class="relative aspect-[4/3] overflow-hidden rounded-lg bg-muted order-2 lg:order-1">
                        <img
                            src="{{ asset('images/hidrografico/ondulacao.jpg') }}"
                            alt="{{ __('messages.hidrografico.images.ondulacao') }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        />
                    </div>
                    <div class="order-1 lg:order-2">
                        <h2 class="mb-6 text-3xl font-bold">{{ __('messages.hidrografico.waves.title') }}</h2>
                        <p class="text-lg text-muted-foreground leading-relaxed">
                            {{ __('messages.hidrografico.waves.text') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Wave Modeling --}}
    <section class="bg-muted/10 py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-6xl">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:items-center">
                    <div>
                        <h2 class="mb-6 text-3xl font-bold">{{ __('messages.hidrografico.modeling.title') }}</h2>
                        <p class="text-lg text-muted-foreground leading-relaxed">
                            {{ __('messages.hidrografico.modeling.text') }}
                        </p>
                    </div>
                    <div class="relative aspect-[4/3] overflow-hidden rounded-lg bg-muted">
                        <img
                            src="{{ asset('images/hidrografico/modelacao.jpg') }}"
                            alt="{{ __('messages.hidrografico.images.modelacao') }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                        />
                    </div>
                </div>
                {{-- YouTube video --}}
                <div class="mt-10 mx-auto max-w-3xl">
                    <div class="relative aspect-video overflow-hidden rounded-lg">
                        <iframe
                            src="https://www.youtube.com/embed/Yufb2MgcebM"
                            title="{{ __('messages.hidrografico.modeling.title') }}"
                            class="h-full w-full"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            loading="lazy"
                        ></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="mx-auto max-w-3xl">
                <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-ocean">~200km</div>
                        <p class="text-sm text-muted-foreground">{{ __('messages.hidrografico.stats.length') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-ocean">5.000m</div>
                        <p class="text-sm text-muted-foreground">{{ __('messages.hidrografico.stats.depth') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-ocean">&lt;1km</div>
                        <p class="text-sm text-muted-foreground">{{ __('messages.hidrografico.stats.distance') }}</p>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-ocean">~2x</div>
                        <p class="text-sm text-muted-foreground">{{ __('messages.hidrografico.stats.amplification') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="gradient-ocean-deep py-16 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="mb-4 text-3xl font-bold">{{ __('messages.hidrografico.cta.title') }}</h2>
            <p class="mb-8 text-lg opacity-90">{{ __('messages.hidrografico.cta.subtitle') }}</p>
            <div class="flex flex-wrap justify-center gap-4">
                <x-ui.button href="{{ route('pn.forecast') }}" class="bg-white text-ocean hover:bg-white/90">
                    {{ __('messages.navigation.forecast') }}
                </x-ui.button>
                <x-ui.button href="{{ route('pn.forte') }}" variant="outline" class="border-white bg-transparent text-white hover:bg-white/10">
                    {{ __('messages.forte.title') }}
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.app>
