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

    public function __construct(
        public array $siteStats,
        public array $storeStats,
        public array $topPages,
        public array $topReferrers,
        public array $languages,
        public string $startDate,
        public string $endDate,
        public ?array $previousSiteStats = null,
        public ?string $dashboardUrl = null,
    ) {}

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
            markdown: 'emails.weekly-statistics',
        );
    }
}
