<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyStatisticsReport extends Mailable
{
    use Queueable, SerializesModels;

    public string $logoUrl;

    public function __construct(
        public array $siteStats,
        public array $storeStats,
        public array $topPages,
        public array $topReferrers,
        public array $languages,
        public array $browsers,
        public array $devices,
        public array $operatingSystems,
        public array $entities,
        public array $storeTopPages,
        public array $storeReferrers,
        public array $countries,
        public array $dailyTraffic,
        public array $hourlyTraffic,
        public string $startDate,
        public string $endDate,
        public ?array $previousSiteStats = null,
        public ?array $previousStoreStats = null,
        public array $ytdSiteStats = [],
        public ?array $prevYtdSiteStats = null,
        public array $ytdStoreStats = [],
        public ?array $prevYtdStoreStats = null,
        public ?string $dashboardUrl = null,
    ) {
        $r2Url = config('services.r2.public_url');
        $this->logoUrl = $r2Url
            ? rtrim($r2Url, '/') . '/nq/email/NQ-Horizontal-Cor-email.png'
            : asset('assets/email/NQ-Horizontal-Cor-email.png');
    }

    public function envelope(): Envelope
    {
        $start = date('d/m', strtotime($this->startDate));
        $end = date('d/m/Y', strtotime($this->endDate));

        return new Envelope(
            subject: "Relatório Semanal — {$start} a {$end}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.weekly-statistics',
        );
    }

    /**
     * Compute percentage change between current and previous values.
     */
    public function percentChange(int|float $current, int|float $previous): ?int
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : null;
        }

        return (int) round(($current - $previous) / $previous * 100);
    }
}
