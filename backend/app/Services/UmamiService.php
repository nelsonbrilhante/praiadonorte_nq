<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UmamiService
{
    private string $baseUrl;
    private ?string $apiKey;
    private int $cacheTtl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.umami.url', ''), '/');
        $this->apiKey = config('services.umami.api_key');
        $this->cacheTtl = 300; // 5 minutes
    }

    /**
     * Get stats for a website (pageviews, visitors, bounces, totaltime).
     */
    public function getStats(string $websiteId, string $startDate, string $endDate): ?array
    {
        $cacheKey = "umami_stats_{$websiteId}_{$startDate}_{$endDate}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($websiteId, $startDate, $endDate) {
            return $this->request("api/websites/{$websiteId}/stats", [
                'startAt' => $this->toTimestamp($startDate),
                'endAt' => $this->toTimestamp($endDate),
            ]);
        });
    }

    /**
     * Get metrics (top pages, referrers, browsers, OS, countries, etc.).
     */
    public function getMetrics(string $websiteId, string $startDate, string $endDate, string $type = 'url', int $limit = 10): ?array
    {
        $cacheKey = "umami_metrics_{$websiteId}_{$startDate}_{$endDate}_{$type}_{$limit}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($websiteId, $startDate, $endDate, $type, $limit) {
            return $this->request("api/websites/{$websiteId}/metrics", [
                'startAt' => $this->toTimestamp($startDate),
                'endAt' => $this->toTimestamp($endDate),
                'type' => $type,
                'limit' => $limit,
            ]);
        });
    }

    /**
     * Get pageviews over time (for charts/trend data).
     */
    public function getPageviews(string $websiteId, string $startDate, string $endDate, string $unit = 'day'): ?array
    {
        $cacheKey = "umami_pageviews_{$websiteId}_{$startDate}_{$endDate}_{$unit}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($websiteId, $startDate, $endDate, $unit) {
            return $this->request("api/websites/{$websiteId}/pageviews", [
                'startAt' => $this->toTimestamp($startDate),
                'endAt' => $this->toTimestamp($endDate),
                'unit' => $unit,
            ]);
        });
    }

    /**
     * Check if the Umami API is reachable.
     */
    public function isAvailable(): bool
    {
        if (empty($this->baseUrl) || empty($this->apiKey)) {
            return false;
        }

        try {
            return Http::timeout(5)
                ->withHeaders($this->headers())
                ->get("{$this->baseUrl}/api/me")
                ->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Make an authenticated API request.
     */
    private function request(string $endpoint, array $params = []): ?array
    {
        if (empty($this->baseUrl) || empty($this->apiKey)) {
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders($this->headers())
                ->get("{$this->baseUrl}/{$endpoint}", $params);

            if ($response->failed()) {
                Log::warning("Umami API error [{$response->status()}]: {$endpoint}", [
                    'params' => $params,
                ]);
                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::warning("Umami connection error: {$e->getMessage()}");
            return null;
        }
    }

    private function headers(): array
    {
        return [
            'x-umami-api-key' => $this->apiKey,
            'Accept' => 'application/json',
        ];
    }

    private function toTimestamp(string $date): int
    {
        return (int) (strtotime($date) * 1000);
    }
}
