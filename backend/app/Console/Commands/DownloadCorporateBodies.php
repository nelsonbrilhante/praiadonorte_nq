<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadCorporateBodies extends Command
{
    protected $signature = 'app:download-corporate-bodies
        {--dry-run : List files without downloading}';

    protected $description = 'Download corporate body photos, CVs, and hero image from the old Nazaré Qualifica website';

    private const BASE_URL = 'https://www.nazarequalifica.pt';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $disk = Storage::disk('public');

        $disk->makeDirectory('corporate-bodies');
        $disk->makeDirectory('corporate-bodies/cvs');

        $downloads = [
            // Photos (full-size from data-large attributes, 369x369)
            [
                'url' => '/images/2025/11/13/alvaro_festas_web.jpg',
                'path' => 'corporate-bodies/alvaro_festas.jpg',
                'label' => 'Photo: Álvaro Festas',
            ],
            [
                'url' => '/images/2025/11/13/marco_carreira_web.jpg',
                'path' => 'corporate-bodies/marco_carreira.jpg',
                'label' => 'Photo: Marco Carreira',
            ],
            [
                'url' => '/images/2025/11/13/fatima_duarte_web.jpg',
                'path' => 'corporate-bodies/fatima_lourenco.jpg',
                'label' => 'Photo: Fátima Lourenço',
            ],
            [
                'url' => '/images/2025/11/13/joaquim_paulo_web.jpg',
                'path' => 'corporate-bodies/joaquim_paulo.jpg',
                'label' => 'Photo: Joaquim Paulo',
            ],
            [
                'url' => '/images/corpos_sociais/mazares_369x369.jpg',
                'path' => 'corporate-bodies/mazars.jpg',
                'label' => 'Photo: Mazars',
            ],
            // CVs
            [
                'url' => '/images/2025/11/13/nota-curricular-jp.pdf',
                'path' => 'corporate-bodies/cvs/nota-curricular-jp.pdf',
                'label' => 'CV: Joaquim Paulo',
            ],
            [
                'url' => '/images/2025/11/13/nota-curricular-presidente-do-cg.pdf',
                'path' => 'corporate-bodies/cvs/nota-curricular-presidente-do-cg.pdf',
                'label' => 'CV: Presidente CG',
            ],
            [
                'url' => '/images/2025/11/13/nota-curricular-1-vogal-cg.pdf',
                'path' => 'corporate-bodies/cvs/nota-curricular-1-vogal-cg.pdf',
                'label' => 'CV: 1º Vogal CG',
            ],
            [
                'url' => '/images/2025/11/13/nota-curricular-2-vogal-cg.pdf',
                'path' => 'corporate-bodies/cvs/nota-curricular-2-vogal-cg.pdf',
                'label' => 'CV: 2º Vogal CG',
            ],
            // Hero image
            [
                'url' => '/images/2023/07/31/home-img12.jpg',
                'path' => 'corporate-bodies/hero-equipa.jpg',
                'label' => 'Hero: Equipa page',
            ],
        ];

        $downloaded = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($downloads as $item) {
            if ($disk->exists($item['path'])) {
                $this->line("  SKIP {$item['label']}");
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $this->line("  [DRY] {$item['label']} -> {$item['path']}");
                $skipped++;
                continue;
            }

            $url = self::BASE_URL . $item['url'];
            try {
                $response = Http::withOptions([
                    'allow_redirects' => true,
                    'timeout' => 60,
                    'connect_timeout' => 15,
                ])->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (compatible; NQ-Migration/1.0)',
                ])->get($url);

                if ($response->successful() && strlen($response->body()) > 100) {
                    $disk->put($item['path'], $response->body());
                    $size = round(strlen($response->body()) / 1024);
                    $this->line("  OK {$item['label']} ({$size} KB)");
                    $downloaded++;
                } else {
                    $this->error("  FAIL {$item['label']} (HTTP {$response->status()})");
                    $failed++;
                }
            } catch (\Exception $e) {
                $this->error("  FAIL {$item['label']}: {$e->getMessage()}");
                $failed++;
            }

            usleep(300000); // 300ms delay
        }

        $this->newLine();
        $this->info("Downloaded: {$downloaded} | Skipped: {$skipped} | Failed: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
