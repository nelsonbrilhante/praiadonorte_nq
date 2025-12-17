@php
    use App\Services\ForecastService;
    $locale = $locale ?? app('laravellocalization')->getCurrentLocale();
    $current = $forecast['current'] ?? null;
    $daily = $forecast['daily'] ?? [];
    $lastUpdated = $forecast['lastUpdated'] ?? null;

    // Helper data
    $windType = $current ? ForecastService::getWindType($current['windDirection'], $locale) : null;
    $waveCondition = $current ? ForecastService::getWaveCondition($current['waveHeight'], $locale) : null;
    $windStrength = $current ? ForecastService::getWindStrength($current['windSpeed'], $locale) : null;
    $wetsuitRec = $current ? ForecastService::getWetsuitRecommendation($current['waterTemperature'], $locale) : null;
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
            @if($lastUpdated)
                <p class="mt-4 text-sm opacity-75">
                    {{ $locale === 'pt' ? 'Atualizado:' : 'Updated:' }}
                    {{ \Carbon\Carbon::parse($lastUpdated)->locale($locale)->diffForHumans() }}
                </p>
            @endif
        </div>
    </section>

    {{-- Current Conditions --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-6 text-2xl font-bold">{{ __('messages.forecast.marine.title') }}</h2>

            @if($current)
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    {{-- Wave Height --}}
                    <x-ui.card class="sm:col-span-2 border-ocean/30 bg-gradient-to-br from-ocean/5 to-ocean/10">
                        <x-ui.card-header>
                            <x-ui.card-title class="text-base font-semibold">
                                {{ __('messages.forecast.marine.waveHeight') }}
                            </x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <div class="text-4xl font-bold text-ocean">{{ number_format($current['waveHeight'], 1) }} m</div>
                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ $waveCondition }}
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
                            <div class="text-4xl font-bold text-ocean">{{ number_format($current['swellHeight'], 1) }} m</div>
                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ ForecastService::degreesToCardinal($current['swellDirection']) }} @ {{ number_format($current['swellPeriod'], 0) }}s
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
                            <div class="text-2xl font-bold">{{ number_format($current['wavePeriod'], 0) }} s</div>
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
                            <div class="flex items-center gap-2">
                                <div class="text-2xl font-bold">{{ ForecastService::degreesToCardinal($current['waveDirection']) }}</div>
                                <span class="text-sm text-muted-foreground">({{ number_format($current['waveDirection'], 0) }}°)</span>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- Wind Speed --}}
                    <x-ui.card class="{{ $windType['quality'] === 'good' ? 'border-green-500/30 bg-green-50 dark:bg-green-950/20' : ($windType['quality'] === 'poor' ? 'border-red-500/30 bg-red-50 dark:bg-red-950/20' : '') }}">
                        <x-ui.card-header>
                            <x-ui.card-title class="text-sm font-medium">
                                {{ __('messages.forecast.marine.windSpeed') }}
                            </x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <div class="text-2xl font-bold">{{ number_format($current['windSpeed'], 0) }} km/h</div>
                            <p class="text-xs text-muted-foreground">{{ $windStrength }}</p>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- Wind Direction --}}
                    <x-ui.card class="{{ $windType['quality'] === 'good' ? 'border-green-500/30 bg-green-50 dark:bg-green-950/20' : ($windType['quality'] === 'poor' ? 'border-red-500/30 bg-red-50 dark:bg-red-950/20' : '') }}">
                        <x-ui.card-header>
                            <x-ui.card-title class="text-sm font-medium">
                                {{ __('messages.forecast.marine.windDirection') }}
                            </x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <div class="flex items-center gap-2">
                                <div class="text-2xl font-bold">{{ ForecastService::degreesToCardinal($current['windDirection']) }}</div>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $windType['quality'] === 'good' ? 'bg-green-500 text-white' : ($windType['quality'] === 'poor' ? 'bg-red-500 text-white' : 'bg-yellow-500 text-black') }}">
                                    {{ $windType['label'] }}
                                </span>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- Wind Gusts --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title class="text-sm font-medium">
                                {{ __('messages.forecast.marine.windGusts') }}
                            </x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <div class="text-2xl font-bold">{{ number_format($current['windGusts'], 0) }} km/h</div>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- Air Temperature --}}
                    <x-ui.card>
                        <x-ui.card-header>
                            <x-ui.card-title class="text-sm font-medium">
                                {{ $locale === 'pt' ? 'Temperatura do Ar' : 'Air Temperature' }}
                            </x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <div class="text-2xl font-bold">{{ number_format($current['airTemperature'], 1) }}°C</div>
                        </x-ui.card-content>
                    </x-ui.card>

                    {{-- Water Temperature --}}
                    <x-ui.card class="sm:col-span-2">
                        <x-ui.card-header>
                            <x-ui.card-title class="text-sm font-medium">
                                {{ __('messages.forecast.marine.waterTemperature') }}
                            </x-ui.card-title>
                        </x-ui.card-header>
                        <x-ui.card-content>
                            <div class="flex items-center gap-4">
                                <div class="text-2xl font-bold">{{ number_format($current['waterTemperature'], 1) }}°C</div>
                                <span class="rounded-full bg-ocean/10 px-3 py-1 text-sm text-ocean">{{ $wetsuitRec }}</span>
                            </div>
                        </x-ui.card-content>
                    </x-ui.card>
                </div>
            @else
                {{-- Error state --}}
                <x-ui.card class="border-yellow-500/30 bg-yellow-50 dark:bg-yellow-950/20">
                    <x-ui.card-content class="py-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-yellow-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                            <path d="M12 9v4"/>
                            <path d="M12 17h.01"/>
                        </svg>
                        <p class="mt-4 text-lg font-medium">
                            {{ $locale === 'pt' ? 'Dados indisponíveis' : 'Data unavailable' }}
                        </p>
                        <p class="mt-2 text-sm text-muted-foreground">
                            {{ $locale === 'pt' ? 'Não foi possível obter os dados de previsão. Por favor, tente novamente mais tarde.' : 'Could not fetch forecast data. Please try again later.' }}
                        </p>
                    </x-ui.card-content>
                </x-ui.card>
            @endif
        </div>
    </section>

    {{-- 7-Day Forecast --}}
    @if(count($daily) > 0)
    <section class="border-t bg-muted/30 py-12">
        <div class="container mx-auto px-4">
            <h2 class="mb-6 text-2xl font-bold">
                {{ $locale === 'pt' ? 'Previsão 7 Dias' : '7-Day Forecast' }}
            </h2>
            <x-ui.card>
                <x-ui.card-content class="overflow-x-auto p-0">
                    <table class="w-full min-w-[600px]">
                        <thead>
                            <tr class="border-b bg-muted/50">
                                <th class="px-4 py-3 text-left text-sm font-medium">{{ $locale === 'pt' ? 'Data' : 'Date' }}</th>
                                <th class="px-4 py-3 text-center text-sm font-medium">{{ $locale === 'pt' ? 'Onda Máx.' : 'Max Wave' }}</th>
                                <th class="px-4 py-3 text-center text-sm font-medium">{{ $locale === 'pt' ? 'Período' : 'Period' }}</th>
                                <th class="px-4 py-3 text-center text-sm font-medium">{{ $locale === 'pt' ? 'Direção' : 'Direction' }}</th>
                                <th class="px-4 py-3 text-center text-sm font-medium">{{ $locale === 'pt' ? 'Condição' : 'Condition' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daily as $day)
                                @php
                                    $date = \Carbon\Carbon::parse($day['date']);
                                    $isToday = $date->isToday();
                                    $dayCondition = ForecastService::getWaveCondition($day['maxWaveHeight'], $locale);
                                @endphp
                                <tr class="border-b last:border-0 {{ $isToday ? 'bg-ocean/5' : '' }}">
                                    <td class="px-4 py-3">
                                        <div class="font-medium {{ $isToday ? 'text-ocean' : '' }}">
                                            {{ $date->locale($locale)->isoFormat('ddd, D MMM') }}
                                        </div>
                                        @if($isToday)
                                            <span class="text-xs text-ocean">{{ $locale === 'pt' ? 'Hoje' : 'Today' }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="font-bold {{ $day['maxWaveHeight'] >= 6 ? 'text-red-500' : ($day['maxWaveHeight'] >= 3 ? 'text-orange-500' : 'text-ocean') }}">
                                            {{ number_format($day['maxWaveHeight'], 1) }} m
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">{{ number_format($day['maxWavePeriod'], 0) }}s</td>
                                    <td class="px-4 py-3 text-center">
                                        {{ ForecastService::degreesToCardinal($day['dominantDirection']) }}
                                        <span class="text-xs text-muted-foreground">({{ number_format($day['dominantDirection'], 0) }}°)</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            {{ $day['maxWaveHeight'] >= 6 ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' :
                                               ($day['maxWaveHeight'] >= 3 ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400' :
                                               ($day['maxWaveHeight'] >= 1.5 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                               'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400')) }}">
                                            {{ $dayCondition }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-ui.card-content>
            </x-ui.card>
        </div>
    </section>
    @endif

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
