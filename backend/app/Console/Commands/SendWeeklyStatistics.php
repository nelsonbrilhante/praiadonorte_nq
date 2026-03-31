<?php

namespace App\Console\Commands;

use App\Mail\WeeklyStatisticsReport;
use App\Models\SiteSetting;
use App\Services\UmamiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeeklyStatistics extends Command
{
    protected $signature = 'stats:send-weekly
        {--email= : Override recipients (comma-separated)}
        {--test : Send test email (uses --email or falls back to configured recipients)}';

    protected $description = 'Send weekly website statistics report via email';

    /** Pages to exclude from top pages lists. */
    private const EXCLUDED_PATHS = [
        '/robots.txt', '/site.webmanifest', '/favicon.ico', '/favicon.svg',
        '/sitemap.xml', '/sw.js', '/manifest.json',
        '/admin/login', '/admin', '/livewire/update',
    ];

    /** Path prefixes to exclude. */
    private const EXCLUDED_PREFIXES = [
        '/wp-json/', '/wp-', '/index.php', '/api/',
        '/.well-known/', '/vendor/',
    ];

    public function handle(UmamiService $umami): int
    {
        // Check if enabled (skip check in test mode)
        if (!$this->option('test') && SiteSetting::get('stats_weekly_enabled', '0') !== '1') {
            $this->info('Weekly statistics email is disabled.');
            return self::SUCCESS;
        }

        // Determine recipients
        $recipients = $this->getRecipients();
        if (empty($recipients)) {
            $this->error('No recipients configured. Set stats_weekly_recipients in Site Settings.');
            return self::FAILURE;
        }

        $this->info('Fetching statistics from Umami...');

        // Date range: previous 7 days (Monday to Sunday)
        $endDate = $this->option('test')
            ? now()->format('Y-m-d')
            : now()->subDay()->format('Y-m-d'); // Yesterday (Sunday)
        $startDate = now()->parse($endDate)->subDays(6)->format('Y-m-d');

        // Previous week for comparison
        $prevEndDate = now()->parse($startDate)->subDay()->format('Y-m-d');
        $prevStartDate = now()->parse($prevEndDate)->subDays(6)->format('Y-m-d');

        $websiteId = config('services.umami.website_id');
        $storeId = config('services.umami.store_website_id');

        if (empty($websiteId)) {
            $this->error('UMAMI_WEBSITE_ID not configured.');
            return self::FAILURE;
        }

        // Fetch main site data
        $siteStats = $umami->getStats($websiteId, $startDate, $endDate) ?? [];
        $previousSiteStats = $umami->getStats($websiteId, $prevStartDate, $prevEndDate);

        // Top pages with readable names (type=path, not url)
        $topPagesRaw = $umami->getMetrics($websiteId, $startDate, $endDate, 'path', 30) ?? [];
        $topPages = collect($topPagesRaw)
            ->filter(fn ($p) => !$this->isExcludedPath($p['x'] ?? ''))
            ->map(fn ($page) => [
                'label' => $this->humanizeUrl($page['x'] ?? ''),
                'value' => $page['y'] ?? 0,
            ])
            ->take(15)
            ->values()
            ->all();

        // Referrers (grouped by domain)
        $rawReferrers = $umami->getMetrics($websiteId, $startDate, $endDate, 'referrer', 20) ?? [];
        $topReferrers = $this->groupReferrers($rawReferrers);

        // Languages (filter unknown)
        $rawLanguages = $umami->getMetrics($websiteId, $startDate, $endDate, 'language', 10) ?? [];
        $languages = collect($rawLanguages)
            ->filter(fn ($l) => !empty($l['x']) && $l['x'] !== '?')
            ->take(5)
            ->values()
            ->all();

        // Browsers
        $browsers = $umami->getMetrics($websiteId, $startDate, $endDate, 'browser', 5) ?? [];

        // Devices
        $devices = $umami->getMetrics($websiteId, $startDate, $endDate, 'device', 5) ?? [];

        // Operating systems
        $operatingSystems = $umami->getMetrics($websiteId, $startDate, $endDate, 'os', 5) ?? [];

        // Countries
        $rawCountries = $umami->getMetrics($websiteId, $startDate, $endDate, 'country', 10) ?? [];
        $countries = collect($rawCountries)
            ->filter(fn ($c) => !empty($c['x']))
            ->take(8)
            ->values()
            ->all();

        // Entity breakdown (type=path)
        $allPagesRaw = $umami->getMetrics($websiteId, $startDate, $endDate, 'path', 100) ?? [];
        $entities = $this->computeEntities($allPagesRaw);

        // Daily traffic (pageviews + sessions per day)
        $dailyTraffic = $umami->getPageviews($websiteId, $startDate, $endDate, 'day') ?? [];

        // Hourly traffic (to find peak hour)
        $hourlyTraffic = $umami->getPageviews($websiteId, $startDate, $endDate, 'hour') ?? [];

        // Store data
        $storeStats = $storeId ? ($umami->getStats($storeId, $startDate, $endDate) ?? []) : [];
        $previousStoreStats = $storeId ? $umami->getStats($storeId, $prevStartDate, $prevEndDate) : null;
        $storeTopPagesRaw = $storeId ? ($umami->getMetrics($storeId, $startDate, $endDate, 'path', 15) ?? []) : [];
        $storeTopPages = collect($storeTopPagesRaw)
            ->filter(fn ($p) => !$this->isExcludedPath($p['x'] ?? ''))
            ->map(fn ($page) => [
                'label' => $this->humanizeStoreUrl($page['x'] ?? ''),
                'value' => $page['y'] ?? 0,
            ])
            ->take(8)
            ->values()
            ->all();
        $rawStoreReferrers = $storeId ? ($umami->getMetrics($storeId, $startDate, $endDate, 'referrer', 15) ?? []) : [];
        $storeReferrers = !empty($rawStoreReferrers) ? $this->groupReferrers($rawStoreReferrers) : [];

        // Year-to-date (YTD) stats
        $ytdStart = now()->startOfYear()->format('Y-m-d');
        $ytdEnd = $endDate;
        $ytdSiteStats = $umami->getStats($websiteId, $ytdStart, $ytdEnd) ?? [];
        $ytdStoreStats = $storeId ? ($umami->getStats($storeId, $ytdStart, $ytdEnd) ?? []) : [];

        // Previous year same period for comparison
        $prevYtdStart = now()->subYear()->startOfYear()->format('Y-m-d');
        $prevYtdEnd = now()->subYear()->setMonth(now()->month)->setDay(now()->day)->format('Y-m-d');
        $prevYtdSiteStats = $umami->getStats($websiteId, $prevYtdStart, $prevYtdEnd);
        $prevYtdStoreStats = $storeId ? $umami->getStats($storeId, $prevYtdStart, $prevYtdEnd) : null;

        $dashboardUrl = config('services.umami.url');

        $this->info("Sending report to: " . implode(', ', $recipients));

        Mail::to($recipients)->send(new WeeklyStatisticsReport(
            siteStats: $siteStats,
            storeStats: $storeStats,
            topPages: $topPages,
            topReferrers: $topReferrers,
            languages: $languages,
            browsers: $browsers,
            devices: $devices,
            operatingSystems: $operatingSystems,
            entities: $entities,
            storeTopPages: $storeTopPages,
            storeReferrers: $storeReferrers,
            countries: $countries,
            dailyTraffic: $dailyTraffic,
            hourlyTraffic: $hourlyTraffic,
            startDate: $startDate,
            endDate: $endDate,
            previousSiteStats: $previousSiteStats,
            previousStoreStats: $previousStoreStats,
            ytdSiteStats: $ytdSiteStats,
            prevYtdSiteStats: $prevYtdSiteStats,
            ytdStoreStats: $ytdStoreStats,
            prevYtdStoreStats: $prevYtdStoreStats,
            dashboardUrl: $dashboardUrl,
        ));

        $this->info('Weekly statistics report sent successfully.');

        return self::SUCCESS;
    }

    private function getRecipients(): array
    {
        if ($email = $this->option('email')) {
            return array_map('trim', explode(',', $email));
        }

        $configured = SiteSetting::get('stats_weekly_recipients', '');

        return array_filter(array_map('trim', explode(',', $configured)));
    }

    private function isExcludedPath(string $path): bool
    {
        if (in_array($path, self::EXCLUDED_PATHS, true)) {
            return true;
        }

        foreach (self::EXCLUDED_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Group referrers by parent domain (e.g. lm.facebook.com → Facebook).
     */
    private function groupReferrers(array $referrers): array
    {
        $domainGroups = [
            'Facebook' => ['facebook.com', 'fb.com'],
            'Google' => ['google.com', 'google.pt', 'google.co.uk', 'google.es', 'google.fr', 'google.de'],
            'Instagram' => ['instagram.com'],
            'Bing' => ['bing.com'],
            'Twitter/X' => ['t.co', 'twitter.com', 'x.com'],
            'CM Nazaré' => ['cm-nazare.pt'],
            'Praia do Norte (site)' => ['praiadonortenazare.pt', 'praiadonortenazare.com', 'nazarequalifica.pt'],
        ];

        $grouped = [];
        $ungrouped = [];

        foreach ($referrers as $ref) {
            $domain = $ref['x'] ?? '';
            $value = $ref['y'] ?? 0;
            $matched = false;

            foreach ($domainGroups as $label => $patterns) {
                foreach ($patterns as $pattern) {
                    if ($domain === $pattern || str_ends_with($domain, '.' . $pattern)) {
                        $grouped[$label] = ($grouped[$label] ?? 0) + $value;
                        $matched = true;
                        break 2;
                    }
                }
            }

            if (!$matched && !empty($domain)) {
                $ungrouped[] = $ref;
            }
        }

        // Build result: grouped first (sorted by value), then ungrouped
        $result = [];
        arsort($grouped);
        foreach ($grouped as $label => $value) {
            $result[] = ['x' => $label, 'y' => $value];
        }
        foreach ($ungrouped as $ref) {
            $result[] = $ref;
        }

        return array_slice($result, 0, 10);
    }

    /**
     * Compute page views grouped by entity.
     */
    private function computeEntities(array $allPages): array
    {
        $entityPrefixes = [
            'Praia do Norte' => ['/pt/praia-norte', '/en/praia-norte', '/en/north-beach'],
            'Nazaré Qualifica' => ['/pt/nazare-qualifica', '/en/nazare-qualifica'],
            'Carsurf' => ['/pt/carsurf', '/en/carsurf'],
        ];

        $entities = [];
        foreach ($entityPrefixes as $name => $prefixes) {
            $views = 0;
            foreach ($allPages as $page) {
                $url = $page['x'] ?? '';
                foreach ($prefixes as $prefix) {
                    if (str_starts_with($url, $prefix)) {
                        $views += $page['y'] ?? 0;
                        break;
                    }
                }
            }
            if ($views > 0) {
                $entities[] = ['name' => $name, 'views' => $views];
            }
        }

        usort($entities, fn ($a, $b) => $b['views'] <=> $a['views']);

        return $entities;
    }

    /**
     * Convert URL paths to human-readable page names.
     */
    private function humanizeUrl(string $url): string
    {
        $map = [
            '/' => 'Página Inicial',
            '/pt' => 'Página Inicial (PT)',
            '/en' => 'Página Inicial (EN)',
            '/pt/noticias' => 'Notícias',
            '/en/news' => 'News',
            '/en/noticias' => 'Notícias (EN)',
            '/pt/eventos' => 'Eventos',
            '/en/events' => 'Events',
            '/en/eventos' => 'Eventos (EN)',
            '/pt/praia-norte/sobre' => 'Praia do Norte — Sobre',
            '/pt/praia-norte/surfer-wall' => 'Surfer Wall',
            '/pt/praia-norte/forte' => 'Forte S. Miguel Arcanjo',
            '/pt/praia-norte/hidrografico' => 'Inst. Hidrográfico',
            '/pt/praia-norte/previsoes' => 'Previsão de Ondas',
            '/pt/carsurf/sobre' => 'Carsurf — Sobre',
            '/pt/carsurf/instalacoes' => 'Carsurf — Instalações',
            '/pt/carsurf/reservas' => 'Carsurf — Reservas',
            '/pt/nazare-qualifica/sobre' => 'NQ — Sobre',
            '/pt/nazare-qualifica/equipa' => 'NQ — Equipa',
            '/pt/nazare-qualifica/servicos' => 'NQ — Serviços',
            '/pt/nazare-qualifica/documentos' => 'NQ — Documentos',
            '/pt/nazare-qualifica/estacionamento' => 'NQ — Estacionamento',
            '/pt/nazare-qualifica/contraordenacoes' => 'NQ — Contraordenações',
            '/pt/nazare-qualifica/ale' => 'NQ — ALE',
            '/pt/loja' => 'Loja',
            '/en/shop' => 'Shop',
            '/pt/contacto' => 'Contacto',
            '/en/contact' => 'Contact',
            '/en/contacto' => 'Contacto (EN)',
            '/en/nazare-qualifica/sobre' => 'NQ — Sobre (EN)',
            '/en/carsurf/sobre' => 'Carsurf — Sobre (EN)',
            '/en/praia-norte/sobre' => 'Praia do Norte — Sobre (EN)',
            '/en/praia-norte/about' => 'Praia do Norte — About',
            '/en/praia-norte/surfer-wall' => 'Surfer Wall (EN)',
            '/en/praia-norte/fort' => 'Fort S. Miguel Arcanjo',
            '/en/praia-norte/hydrographic' => 'Hydrographic Inst.',
            '/en/praia-norte/forecast' => 'Wave Forecast',
            '/en/carsurf/about' => 'Carsurf — About',
            '/en/nazare-qualifica/about' => 'NQ — About',
            '/en/nazare-qualifica/team' => 'NQ — Team',
            '/en/nazare-qualifica/services' => 'NQ — Services',
            '/en/nazare-qualifica/documents' => 'NQ — Documents',
            '/en/nazare-qualifica/parking' => 'NQ — Parking',
            '/pt/privacidade' => 'Política de Privacidade',
            '/pt/termos' => 'Termos e Condições',
            '/en/privacy' => 'Privacy Policy',
            '/en/terms' => 'Terms & Conditions',
        ];

        if (isset($map[$url])) {
            return $map[$url];
        }

        // News/event detail pages — capitalise and restore common accented words
        if (preg_match('#^/pt/noticias/(.+)$#', $url, $m)) {
            return 'Notícia: ' . $this->prettifySlug($m[1]);
        }
        if (preg_match('#^/en/news/(.+)$#', $url, $m)) {
            return 'News: ' . $this->prettifySlug($m[1]);
        }
        if (preg_match('#^/pt/eventos/(.+)$#', $url, $m)) {
            return 'Evento: ' . $this->prettifySlug($m[1]);
        }
        if (preg_match('#^/en/events/(.+)$#', $url, $m)) {
            return 'Event: ' . $this->prettifySlug($m[1]);
        }

        return $url;
    }

    /**
     * Convert a URL slug to a human-readable title, restoring common Portuguese accents.
     */
    private function prettifySlug(string $slug): string
    {
        $text = ucfirst(str_replace('-', ' ', rtrim($slug, '/')));

        // Restore common Portuguese accented words
        $accents = [
            'nazare' => 'Nazaré', 'qualifica' => 'Qualifica', 'tres' => 'três',
            'areas' => 'áreas', 'informacao' => 'informação', 'educacao' => 'educação',
            'formacao' => 'formação', 'emprego' => 'emprego', 'inicio' => 'início',
            'servicos' => 'serviços', 'noticias' => 'notícias', 'contraordenacoes' => 'contraordenações',
            'estacionamento' => 'estacionamento', 'previsoes' => 'previsões', 'instalacoes' => 'instalações',
            'ondulacao' => 'ondulação', 'exposicao' => 'exposição', 'promocao' => 'promoção',
            'competicao' => 'competição', 'edicao' => 'edição', 'realizacao' => 'realização',
        ];

        foreach ($accents as $plain => $accented) {
            $text = preg_replace('/\b' . preg_quote($plain, '/') . '\b/i', $accented, $text);
        }

        return $text;
    }

    /**
     * Convert WooCommerce store URLs to human-readable product names.
     */
    private function humanizeStoreUrl(string $url): string
    {
        $url = rtrim($url, '/');

        $map = [
            '/' => 'Página inicial da loja',
            '' => 'Página inicial da loja',
            '/shop' => 'Catálogo',
            '/cart' => 'Carrinho de compras',
            '/checkout' => 'Checkout',
            '/my-account' => 'A minha conta',
            '/loja' => 'Catálogo',
            '/carrinho' => 'Carrinho de compras',
        ];

        if (isset($map[$url])) {
            return $map[$url];
        }

        // Product pages: /product/sweat-pn-preto/ → "Sweat Pn Preto"
        if (preg_match('#^/product/(.+)$#', $url, $m)) {
            return ucwords(str_replace('-', ' ', $m[1]));
        }

        // Category pages
        if (preg_match('#^/product-category/(.+)$#', $url, $m)) {
            return 'Categoria: ' . ucfirst(str_replace('-', ' ', $m[1]));
        }

        return $url;
    }
}
