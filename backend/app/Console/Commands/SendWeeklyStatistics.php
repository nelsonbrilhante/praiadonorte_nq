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

        // Fetch data
        $siteStats = $umami->getStats($websiteId, $startDate, $endDate) ?? [];
        $previousSiteStats = $umami->getStats($websiteId, $prevStartDate, $prevEndDate);
        $storeStats = $storeId ? ($umami->getStats($storeId, $startDate, $endDate) ?? []) : [];

        // Top pages with readable names
        $topPagesRaw = $umami->getMetrics($websiteId, $startDate, $endDate, 'url', 10) ?? [];
        $topPages = collect($topPagesRaw)->map(fn ($page) => [
            'label' => $this->humanizeUrl($page['x'] ?? ''),
            'value' => $page['y'] ?? 0,
        ])->all();

        // Referrers
        $topReferrers = $umami->getMetrics($websiteId, $startDate, $endDate, 'referrer', 10) ?? [];

        // Languages
        $languages = $umami->getMetrics($websiteId, $startDate, $endDate, 'language', 5) ?? [];

        $dashboardUrl = config('services.umami.url');

        $this->info("Sending report to: " . implode(', ', $recipients));

        Mail::to($recipients)->send(new WeeklyStatisticsReport(
            siteStats: $siteStats,
            storeStats: $storeStats,
            topPages: $topPages,
            topReferrers: $topReferrers,
            languages: $languages,
            startDate: $startDate,
            endDate: $endDate,
            previousSiteStats: $previousSiteStats,
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

    /**
     * Convert URL paths to human-readable page names.
     */
    private function humanizeUrl(string $url): string
    {
        $map = [
            '/' => 'Homepage',
            '/pt' => 'Homepage (PT)',
            '/en' => 'Homepage (EN)',
            '/pt/noticias' => 'Notícias',
            '/en/news' => 'News',
            '/pt/eventos' => 'Eventos',
            '/en/events' => 'Events',
            '/pt/praia-norte/sobre' => 'Praia do Norte — Sobre',
            '/pt/praia-norte/surfer-wall' => 'Surfer Wall',
            '/pt/praia-norte/forte' => 'Forte S. Miguel Arcanjo',
            '/pt/praia-norte/hidrografico' => 'Inst. Hidrográfico',
            '/pt/praia-norte/previsoes' => 'Previsão de Ondas',
            '/pt/carsurf/sobre' => 'Carsurf — Sobre',
            '/pt/carsurf/instalacoes' => 'Carsurf — Instalações',
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
        ];

        return $map[$url] ?? $url;
    }
}
