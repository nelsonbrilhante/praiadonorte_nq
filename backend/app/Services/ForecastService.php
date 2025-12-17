<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ForecastService
{
    // Coordenadas da Praia do Norte, Nazaré
    private const LATITUDE = 39.6017;
    private const LONGITUDE = -9.0686;
    private const CACHE_TTL = 900; // 15 minutos

    /**
     * Obter previsão completa (marine + weather)
     */
    public function getFullForecast(): ?array
    {
        return Cache::remember('forecast_full', self::CACHE_TTL, function () {
            try {
                $marine = $this->fetchMarineData();
                $weather = $this->fetchWeatherData();

                if (!$marine) {
                    return null;
                }

                return $this->processForecast($marine, $weather);
            } catch (\Exception $e) {
                Log::error('Forecast API Error: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Fetch marine data from Open-Meteo Marine API
     */
    private function fetchMarineData(): ?array
    {
        $response = Http::get('https://marine-api.open-meteo.com/v1/marine', [
            'latitude' => self::LATITUDE,
            'longitude' => self::LONGITUDE,
            'hourly' => 'wave_height,wave_period,wave_direction,wind_wave_height,swell_wave_height,swell_wave_direction,swell_wave_period,ocean_current_velocity,ocean_current_direction,sea_surface_temperature',
            'daily' => 'wave_height_max,wave_period_max,wave_direction_dominant',
            'timezone' => 'Europe/Lisbon',
            'forecast_days' => '7',
        ]);

        if ($response->failed()) {
            Log::error('Marine API failed: ' . $response->status());
            return null;
        }

        return $response->json();
    }

    /**
     * Fetch weather data from Open-Meteo Weather API
     */
    private function fetchWeatherData(): ?array
    {
        $response = Http::get('https://api.open-meteo.com/v1/forecast', [
            'latitude' => self::LATITUDE,
            'longitude' => self::LONGITUDE,
            'hourly' => 'wind_speed_10m,wind_direction_10m,wind_gusts_10m,temperature_2m',
            'timezone' => 'Europe/Lisbon',
            'forecast_days' => '7',
        ]);

        if ($response->failed()) {
            Log::warning('Weather API failed: ' . $response->status());
            return null;
        }

        return $response->json();
    }

    /**
     * Process raw API data into usable format
     */
    private function processForecast(array $marine, ?array $weather): array
    {
        $now = now();
        $currentHourIndex = $this->findCurrentHourIndex($marine['hourly']['time'] ?? [], $now);
        $weatherHourIndex = $weather ? $this->findCurrentHourIndex($weather['hourly']['time'] ?? [], $now) : 0;

        $current = [
            // Wave data
            'waveHeight' => $marine['hourly']['wave_height'][$currentHourIndex] ?? 0,
            'wavePeriod' => $marine['hourly']['wave_period'][$currentHourIndex] ?? 0,
            'waveDirection' => $marine['hourly']['wave_direction'][$currentHourIndex] ?? 0,
            'swellHeight' => $marine['hourly']['swell_wave_height'][$currentHourIndex] ?? 0,
            'swellDirection' => $marine['hourly']['swell_wave_direction'][$currentHourIndex] ?? 0,
            'swellPeriod' => $marine['hourly']['swell_wave_period'][$currentHourIndex] ?? 0,
            // Wind data
            'windSpeed' => $weather['hourly']['wind_speed_10m'][$weatherHourIndex] ?? 0,
            'windDirection' => $weather['hourly']['wind_direction_10m'][$weatherHourIndex] ?? 0,
            'windGusts' => $weather['hourly']['wind_gusts_10m'][$weatherHourIndex] ?? 0,
            // Temperature
            'airTemperature' => $weather['hourly']['temperature_2m'][$weatherHourIndex] ?? 0,
            'waterTemperature' => $marine['hourly']['sea_surface_temperature'][$currentHourIndex] ?? 0,
            // Currents
            'currentVelocity' => $marine['hourly']['ocean_current_velocity'][$currentHourIndex] ?? 0,
            'currentDirection' => $marine['hourly']['ocean_current_direction'][$currentHourIndex] ?? 0,
            // Meta
            'timestamp' => $marine['hourly']['time'][$currentHourIndex] ?? $now->toIso8601String(),
        ];

        $daily = [];
        if (isset($marine['daily']['time'])) {
            foreach ($marine['daily']['time'] as $index => $date) {
                $daily[] = [
                    'date' => $date,
                    'maxWaveHeight' => $marine['daily']['wave_height_max'][$index] ?? 0,
                    'maxWavePeriod' => $marine['daily']['wave_period_max'][$index] ?? 0,
                    'dominantDirection' => $marine['daily']['wave_direction_dominant'][$index] ?? 0,
                ];
            }
        }

        return [
            'current' => $current,
            'daily' => $daily,
            'lastUpdated' => $now->toIso8601String(),
        ];
    }

    /**
     * Find the index of the current hour in the hourly data
     */
    private function findCurrentHourIndex(array $times, $now): int
    {
        $currentTime = $now->timestamp * 1000;
        $closestIndex = 0;
        $closestDiff = PHP_INT_MAX;

        foreach ($times as $i => $time) {
            $timeMs = strtotime($time) * 1000;
            $diff = abs($timeMs - $currentTime);
            if ($diff < $closestDiff) {
                $closestDiff = $diff;
                $closestIndex = $i;
            }
        }

        return $closestIndex;
    }

    /**
     * Convert degrees to cardinal direction
     */
    public static function degreesToCardinal(float $degrees): string
    {
        $directions = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'];
        $index = (int) round($degrees / 22.5) % 16;
        return $directions[$index];
    }

    /**
     * Get wave condition description based on height
     */
    public static function getWaveCondition(float $height, string $locale = 'pt'): string
    {
        $conditions = [
            'pt' => [
                'calm' => 'Calmo',
                'small' => 'Pequenas',
                'moderate' => 'Moderadas',
                'large' => 'Grandes',
                'veryLarge' => 'Muito Grandes',
                'giant' => 'Gigantes',
            ],
            'en' => [
                'calm' => 'Calm',
                'small' => 'Small',
                'moderate' => 'Moderate',
                'large' => 'Large',
                'veryLarge' => 'Very Large',
                'giant' => 'Giant',
            ],
        ];

        $c = $conditions[$locale] ?? $conditions['pt'];

        if ($height < 0.5) return $c['calm'];
        if ($height < 1.5) return $c['small'];
        if ($height < 3) return $c['moderate'];
        if ($height < 6) return $c['large'];
        if ($height < 10) return $c['veryLarge'];
        return $c['giant'];
    }

    /**
     * Determine wind type for Praia do Norte (faces West ~270°)
     */
    public static function getWindType(float $windDirection, string $locale = 'pt'): array
    {
        $labels = [
            'pt' => [
                'offshore' => 'Offshore (Terral)',
                'onshore' => 'Onshore (Nortada)',
                'cross-offshore' => 'Cross-Offshore',
                'cross-onshore' => 'Cross-Onshore',
            ],
            'en' => [
                'offshore' => 'Offshore',
                'onshore' => 'Onshore',
                'cross-offshore' => 'Cross-Offshore',
                'cross-onshore' => 'Cross-Onshore',
            ],
        ];

        $l = $labels[$locale] ?? $labels['pt'];

        // Normalize direction to 0-360
        $dir = fmod(fmod($windDirection, 360) + 360, 360);

        // Offshore: 45° - 135° (East) - wind blowing from land to sea
        if ($dir >= 45 && $dir < 135) {
            return ['type' => 'offshore', 'label' => $l['offshore'], 'quality' => 'good'];
        }
        // Onshore: 225° - 315° (West) - wind blowing from sea to land
        if ($dir >= 225 && $dir < 315) {
            return ['type' => 'onshore', 'label' => $l['onshore'], 'quality' => 'poor'];
        }
        // Cross-offshore: 135° - 225° (South) - slightly offshore component
        if ($dir >= 135 && $dir < 225) {
            return ['type' => 'cross-offshore', 'label' => $l['cross-offshore'], 'quality' => 'fair'];
        }
        // Cross-onshore: 315° - 45° (North) - slightly onshore component
        return ['type' => 'cross-onshore', 'label' => $l['cross-onshore'], 'quality' => 'fair'];
    }

    /**
     * Get wind strength description
     */
    public static function getWindStrength(float $speed, string $locale = 'pt'): string
    {
        $strengths = [
            'pt' => [
                'calm' => 'Calmo',
                'light' => 'Fraco',
                'moderate' => 'Moderado',
                'strong' => 'Forte',
                'veryStrong' => 'Muito Forte',
            ],
            'en' => [
                'calm' => 'Calm',
                'light' => 'Light',
                'moderate' => 'Moderate',
                'strong' => 'Strong',
                'veryStrong' => 'Very Strong',
            ],
        ];

        $s = $strengths[$locale] ?? $strengths['pt'];

        if ($speed < 5) return $s['calm'];
        if ($speed < 15) return $s['light'];
        if ($speed < 30) return $s['moderate'];
        if ($speed < 50) return $s['strong'];
        return $s['veryStrong'];
    }

    /**
     * Get wetsuit recommendation based on water temperature
     */
    public static function getWetsuitRecommendation(float $temperature, string $locale = 'pt'): string
    {
        $recommendations = [
            'pt' => [
                'thick' => 'Fato 5/4mm + botas',
                'medium' => 'Fato 4/3mm',
                'light' => 'Fato 3/2mm',
                'minimal' => 'Fato curto/Lycra',
            ],
            'en' => [
                'thick' => '5/4mm + boots',
                'medium' => '4/3mm wetsuit',
                'light' => '3/2mm wetsuit',
                'minimal' => 'Shorty/Rashguard',
            ],
        ];

        $r = $recommendations[$locale] ?? $recommendations['pt'];

        if ($temperature < 14) return $r['thick'];
        if ($temperature < 17) return $r['medium'];
        if ($temperature < 20) return $r['light'];
        return $r['minimal'];
    }
}
