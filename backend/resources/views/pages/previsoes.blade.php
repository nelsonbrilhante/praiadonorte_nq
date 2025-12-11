@php
    $locale = app('laravellocalization')->getCurrentLocale();
@endphp

<x-layouts.app>

    {{-- Breadcrumbs --}}
    <div class="border-b bg-muted/30">
        <div class="container mx-auto px-4">
            <x-ui.breadcrumbs />
        </div>
    </div>

    {{-- Hero --}}
    <section class="gradient-ocean py-16 text-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                    <path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                    <path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                </svg>
                <div>
                    <h1 class="text-4xl font-bold md:text-5xl">{{ __('messages.forecast.title') }}</h1>
                    <p class="mt-2 text-xl opacity-90">{{ __('messages.forecast.subtitle') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Current Conditions Placeholder --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-6 text-2xl font-bold">{{ __('messages.forecast.marine.title') }}</h2>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Wave Height --}}
                <x-ui.card class="sm:col-span-2 border-ocean/30 bg-gradient-to-br from-ocean/5 to-ocean/10">
                    <x-ui.card-header>
                        <x-ui.card-title class="text-base font-semibold">
                            {{ __('messages.forecast.marine.waveHeight') }}
                        </x-ui.card-title>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="text-4xl font-bold text-ocean">-- m</div>
                        <p class="mt-1 text-sm text-muted-foreground">
                            {{ $locale === 'pt' ? 'A carregar...' : 'Loading...' }}
                        </p>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Swell Height --}}
                <x-ui.card class="sm:col-span-2 border-ocean/30 bg-gradient-to-br from-ocean/5 to-ocean/10">
                    <x-ui.card-header>
                        <x-ui.card-title class="text-base font-semibold">
                            {{ __('messages.forecast.marine.swellHeight') }}
                        </x-ui.card-title>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="text-4xl font-bold text-ocean">-- m</div>
                        <p class="mt-1 text-sm text-muted-foreground">
                            {{ $locale === 'pt' ? 'A carregar...' : 'Loading...' }}
                        </p>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Wave Period --}}
                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title class="text-sm font-medium">
                            {{ __('messages.forecast.marine.wavePeriod') }}
                        </x-ui.card-title>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="text-2xl font-bold">-- s</div>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Wave Direction --}}
                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title class="text-sm font-medium">
                            {{ __('messages.forecast.marine.waveDirection') }}
                        </x-ui.card-title>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="text-2xl font-bold">--</div>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Wind Speed --}}
                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title class="text-sm font-medium">
                            {{ __('messages.forecast.marine.windSpeed') }}
                        </x-ui.card-title>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="text-2xl font-bold">-- km/h</div>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Wind Direction --}}
                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title class="text-sm font-medium">
                            {{ __('messages.forecast.marine.windDirection') }}
                        </x-ui.card-title>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="text-2xl font-bold">--</div>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Wind Gusts --}}
                <x-ui.card class="sm:col-span-1 lg:col-span-2">
                    <x-ui.card-header>
                        <x-ui.card-title class="text-sm font-medium">
                            {{ __('messages.forecast.marine.windGusts') }}
                        </x-ui.card-title>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="text-2xl font-bold">-- km/h</div>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Water Temperature --}}
                <x-ui.card class="sm:col-span-1 lg:col-span-2">
                    <x-ui.card-header>
                        <x-ui.card-title class="text-sm font-medium">
                            {{ __('messages.forecast.marine.waterTemperature') }}
                        </x-ui.card-title>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="text-2xl font-bold">--°C</div>
                    </x-ui.card-content>
                </x-ui.card>
            </div>
        </div>
    </section>

    {{-- MONICAN Section --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-6 text-2xl font-bold">{{ __('messages.forecast.monican.title') }}</h2>
            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>{{ __('messages.forecast.monican.subtitle') }}</x-ui.card-title>
                    <x-ui.card-description>{{ __('messages.forecast.monican.description') }}</x-ui.card-description>
                </x-ui.card-header>
                <x-ui.card-content>
                    <div class="space-y-4">
                        {{-- MONICAN iframe --}}
                        <div class="relative aspect-[16/10] w-full overflow-hidden rounded-lg border bg-muted">
                            <iframe
                                src="https://monican.hidrografico.pt/previsao"
                                title="MONICAN - Previsao Agitacao Maritima"
                                class="h-full w-full"
                                sandbox="allow-scripts allow-same-origin"
                                loading="lazy"
                            ></iframe>
                        </div>

                        {{-- Attribution & Link --}}
                        <div class="flex flex-col items-center justify-between gap-4 rounded-lg bg-muted/50 p-4 sm:flex-row">
                            <div>
                                <p class="text-sm font-medium">{{ __('messages.forecast.monican.credit') }}</p>
                                <p class="text-xs text-muted-foreground">
                                    {{ $locale === 'pt' ? 'Sistema de previsao da agitacao maritima para a costa portuguesa' : 'Maritime wave forecast system for the Portuguese coast' }}
                                </p>
                            </div>
                            <x-ui.button variant="outline" href="https://monican.hidrografico.pt/previsao" target="_blank">
                                {{ __('messages.forecast.monican.viewFull') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                    <polyline points="15 3 21 3 21 9"/>
                                    <line x1="10" y1="14" x2="21" y2="3"/>
                                </svg>
                            </x-ui.button>
                        </div>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        </div>
    </section>

    {{-- Webcams Section --}}
    <section class="border-t bg-muted/30 py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-6 text-2xl font-bold">{{ __('messages.forecast.webcams.title') }}</h2>
            <div class="grid gap-6 md:grid-cols-2">
                {{-- Praia do Norte Webcam --}}
                <x-ui.card class="overflow-hidden">
                    <x-ui.card-header>
                        <x-ui.card-title class="flex items-center gap-2">
                            <span class="relative flex h-3 w-3">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex h-3 w-3 rounded-full bg-red-500"></span>
                            </span>
                            {{ __('messages.forecast.webcams.praiaDoNorte') }}
                        </x-ui.card-title>
                        <x-ui.card-description>
                            {{ $locale === 'pt' ? 'Vista ao vivo da Praia do Norte - Ondas Gigantes' : 'Live view of Praia do Norte - Giant Waves' }}
                        </x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="relative aspect-video w-full overflow-hidden rounded-lg border bg-muted">
                            <div class="flex h-full flex-col items-center justify-center gap-4 p-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                                    <path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                                    <path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                                </svg>
                                <p class="text-sm text-muted-foreground">
                                    {{ $locale === 'pt' ? 'Clique para ver a webcam em janela externa' : 'Click to view webcam in external window' }}
                                </p>
                                <x-ui.button variant="outline" href="https://www.surfline.com/surf-report/praia-do-norte/584204214e65fad6a7709c4f" target="_blank">
                                    {{ $locale === 'pt' ? 'Ver Webcam' : 'View Webcam' }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                        <polyline points="15 3 21 3 21 9"/>
                                        <line x1="10" y1="14" x2="21" y2="3"/>
                                    </svg>
                                </x-ui.button>
                            </div>
                        </div>
                    </x-ui.card-content>
                </x-ui.card>

                {{-- Forte Webcam --}}
                <x-ui.card class="overflow-hidden">
                    <x-ui.card-header>
                        <x-ui.card-title class="flex items-center gap-2">
                            <span class="relative flex h-3 w-3">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex h-3 w-3 rounded-full bg-red-500"></span>
                            </span>
                            {{ __('messages.forecast.webcams.forte') }}
                        </x-ui.card-title>
                        <x-ui.card-description>
                            {{ $locale === 'pt' ? 'Vista do Forte de Sao Miguel Arcanjo' : 'View from Fort Sao Miguel Arcanjo' }}
                        </x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <div class="relative aspect-video w-full overflow-hidden rounded-lg border bg-muted">
                            <div class="flex h-full flex-col items-center justify-center gap-4 p-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 6c.6.5 1.2 1 2.5 1C7 7 7 5 9.5 5c2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                                    <path d="M2 12c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                                    <path d="M2 18c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.6 0 2.4 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                                </svg>
                                <p class="text-sm text-muted-foreground">
                                    {{ $locale === 'pt' ? 'Clique para ver a webcam em janela externa' : 'Click to view webcam in external window' }}
                                </p>
                                <x-ui.button variant="outline" href="https://beachcam.meo.pt/livecams/nazare-norte/" target="_blank">
                                    {{ $locale === 'pt' ? 'Ver Webcam' : 'View Webcam' }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                        <polyline points="15 3 21 3 21 9"/>
                                        <line x1="10" y1="14" x2="21" y2="3"/>
                                    </svg>
                                </x-ui.button>
                            </div>
                        </div>
                    </x-ui.card-content>
                </x-ui.card>
            </div>
        </div>
    </section>
</x-layouts.app>
