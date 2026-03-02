<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadDocuments extends Command
{
    protected $signature = 'app:download-documents
        {--dry-run : List documents without downloading}
        {--category= : Download only a specific category (by PT name)}';

    protected $description = 'Download 102 PDF documents from the old Nazaré Qualifica website';

    private const BASE_URL = 'https://www.nazarequalifica.pt';

    public function handle(): int
    {
        $manifest = $this->getManifest();
        $dryRun = $this->option('dry-run');
        $filterCategory = $this->option('category');

        // Load all categories keyed by PT name
        $categories = DocumentCategory::all()->keyBy(fn ($cat) => $cat->name['pt']);

        if ($categories->isEmpty()) {
            $this->error('No categories found. Run: php artisan db:seed --class=DocumentCategorySeeder');
            return self::FAILURE;
        }

        $totalDownloaded = 0;
        $totalSkipped = 0;
        $totalFailed = 0;
        $totalExpected = 0;

        foreach ($manifest as $categoryName => $docs) {
            if ($filterCategory && $categoryName !== $filterCategory) {
                continue;
            }

            $category = $categories->get($categoryName);
            if (! $category) {
                $this->error("Category not found in DB: {$categoryName}");
                continue;
            }

            $totalExpected += count($docs);
            $this->info("\n{$category->name['pt']} ({$category->slug}) - " . count($docs) . ' files');

            // Ensure directory exists
            $dir = "documentos/{$category->slug}";
            Storage::disk('public')->makeDirectory($dir);

            foreach ($docs as $index => $doc) {
                $filename = $doc['filename'];
                $filePath = "{$dir}/{$filename}";

                // Skip if file already exists and DB record exists
                if (Storage::disk('public')->exists($filePath)) {
                    $existing = Document::where('file', $filePath)->first();
                    if ($existing) {
                        $this->line("  SKIP {$doc['title']}");
                        $totalSkipped++;
                        continue;
                    }
                }

                if ($dryRun) {
                    $this->line("  [DRY] {$doc['title']} -> {$filePath}");
                    $totalSkipped++;
                    continue;
                }

                // Download from old site
                $url = self::BASE_URL . $doc['url'];
                try {
                    $response = Http::withOptions([
                        'allow_redirects' => true,
                        'timeout' => 60,
                        'connect_timeout' => 15,
                    ])->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (compatible; NQ-Migration/1.0)',
                        'Accept' => 'application/pdf,*/*',
                    ])->get($url);

                    if ($response->successful() && strlen($response->body()) > 100) {
                        Storage::disk('public')->put($filePath, $response->body());

                        // Extract year from title for published_at
                        $publishedAt = null;
                        if (preg_match('/\b(20\d{2})\b/', $doc['title'], $matches)) {
                            $publishedAt = $matches[1] . '-01-01';
                        }

                        Document::updateOrCreate(
                            ['file' => $filePath],
                            [
                                'document_category_id' => $category->id,
                                'title' => ['pt' => $doc['title']],
                                'published_at' => $publishedAt,
                                'order' => $index + 1,
                            ]
                        );

                        $size = round(strlen($response->body()) / 1024);
                        $this->line("  OK {$doc['title']} ({$size} KB)");
                        $totalDownloaded++;
                    } else {
                        $this->error("  FAIL {$doc['title']} (HTTP {$response->status()}, " . strlen($response->body()) . ' bytes)');
                        $totalFailed++;
                    }
                } catch (\Exception $e) {
                    $this->error("  FAIL {$doc['title']}: {$e->getMessage()}");
                    $totalFailed++;
                }

                // Small delay to be polite to the old server
                usleep(300000); // 300ms
            }
        }

        $this->newLine();
        $this->info("Expected: {$totalExpected} | Downloaded: {$totalDownloaded} | Skipped: {$totalSkipped} | Failed: {$totalFailed}");

        return $totalFailed > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function getManifest(): array
    {
        return [
            // ─── 1. Contratacoes In-House (8 files) ────────────────
            'Contratações In-House' => [
                [
                    'title' => 'Contrato In House Atividade da Piscina 01 de janeiro a 31 de dezembro 2025',
                    'url' => '/index.php/a-empresa/doc-financeiros/29-contratacoes-in-house?download=98:contrato-in-house-atividade-da-piscina-01-de-janeiro-a-31-de-dezembro-2025',
                    'filename' => 'contrato-in-house-atividade-da-piscina-01-de-janeiro-a-31-de-dezembro-2025.pdf',
                ],
                [
                    'title' => 'In House - Cultura e Eventos - Ano de 2025',
                    'url' => '/index.php/a-empresa/doc-financeiros/29-contratacoes-in-house?download=102:in-house-cultura-e-eventos-ano-de-2025',
                    'filename' => 'in-house-cultura-e-eventos-ano-de-2025.pdf',
                ],
                [
                    'title' => 'In House - Educação - Ano de 2025',
                    'url' => '/index.php/a-empresa/doc-financeiros/29-contratacoes-in-house?download=103:in-house-educacao-ano-de-2025',
                    'filename' => 'in-house-educacao-ano-de-2025.pdf',
                ],
                [
                    'title' => 'In House - Manutenção de Infraestruturas e Equip. Públicos - Ano de 2025',
                    'url' => '/index.php/a-empresa/doc-financeiros/29-contratacoes-in-house?download=104:in-house-manutencao-de-infraestruturas-e-equip-publicos-ano-de-2025',
                    'filename' => 'in-house-manutencao-de-infraestruturas-e-equip-publicos-ano-de-2025.pdf',
                ],
                [
                    'title' => 'In House Cultura e Eventos 01 Jan a 31 Dez 2024',
                    'url' => '/index.php/a-empresa/doc-financeiros/29-contratacoes-in-house?download=83:in-house-cultura-e-eventos-01-jan-a-31-dez-2024',
                    'filename' => 'in-house-cultura-e-eventos-01-jan-a-31-dez-2024.pdf',
                ],
                [
                    'title' => 'In House Educação 01 Jan a 31 Dez 2024',
                    'url' => '/index.php/a-empresa/doc-financeiros/29-contratacoes-in-house?download=84:in-house-educao-01-jan-a-31-dez-2024',
                    'filename' => 'in-house-educao-01-jan-a-31-dez-2024.pdf',
                ],
                [
                    'title' => 'In House Educação 01 Jul a 31 Dez 2023',
                    'url' => '/index.php/a-empresa/doc-financeiros/29-contratacoes-in-house?download=76:in-house-educao-01-jul-a-31-dez-2023',
                    'filename' => 'in-house-educao-01-jul-a-31-dez-2023.pdf',
                ],
                [
                    'title' => 'In House Educação 01 Set a 31 Dez 2022',
                    'url' => '/index.php/a-empresa/doc-financeiros/29-contratacoes-in-house?download=75:in-house-educao-01-set-a-31-dez-2022',
                    'filename' => 'in-house-educao-01-set-a-31-dez-2022.pdf',
                ],
            ],

            // ─── 2. Contratos-Programa (31 files) ──────────────────
            'Contratos-Programa' => [
                [
                    'title' => 'CP Agua 2017',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=49:cp-agua-2017',
                    'filename' => 'cp-agua-2017.pdf',
                ],
                [
                    'title' => 'CP Ambiental 2018',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=50:cp-ambiental-2018',
                    'filename' => 'cp-ambiental-2018.pdf',
                ],
                [
                    'title' => 'CP ATL 2017',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=42:cp-atl-2017',
                    'filename' => 'cp-atl-2017.pdf',
                ],
                [
                    'title' => 'CP Bancadas 2018',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=46:cp-bancadas-2018',
                    'filename' => 'cp-bancadas-2018.pdf',
                ],
                [
                    'title' => 'CP CarSurf - 01 Jan a 31 dez 2025',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=105:cp-carsurf-01-jan-a-31-dez-2025',
                    'filename' => 'cp-carsurf-01-jan-a-31-dez-2025.pdf',
                ],
                [
                    'title' => 'CP Carsurf 01 Jan a 31 Dez 2024',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=79:cp-carsurf-01-jan-a-31-dez-2024',
                    'filename' => 'cp-carsurf-01-jan-a-31-dez-2024.pdf',
                ],
                [
                    'title' => 'CP CarSurf 2018',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=47:cp-carsurf-2018',
                    'filename' => 'cp-carsurf-2018.pdf',
                ],
                [
                    'title' => 'CP CarSurf 2019',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=44:cp-carsurf-2019',
                    'filename' => 'cp-carsurf-2019.pdf',
                ],
                [
                    'title' => 'CP CarSurf 2020',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=21:contrato-programa-com-o-municipio-da-nazare-car-surf-2020',
                    'filename' => 'contrato-programa-com-o-municipio-da-nazare-car-surf-2020.pdf',
                ],
                [
                    'title' => 'CP CarSurf 2021',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=11:carsuf',
                    'filename' => 'carsuf.pdf',
                ],
                [
                    'title' => 'CP CarSurf 2022',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=6:contrato-programa-carsurf',
                    'filename' => 'contrato-programa-carsurf.pdf',
                ],
                [
                    'title' => 'CP CarSurf 2023',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=1:contrato-programa-carsurf-2023-minuta',
                    'filename' => 'contrato-programa-carsurf-2023-minuta.pdf',
                ],
                [
                    'title' => 'CP Cultura 2018',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=48:cp-cultura-2018',
                    'filename' => 'cp-cultura-2018.pdf',
                ],
                [
                    'title' => 'CP Cultura e Eventos 2020',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=22:contrato-programa-com-o-municipio-da-nazare-cultura-e-eventos-2020',
                    'filename' => 'contrato-programa-com-o-municipio-da-nazare-cultura-e-eventos-2020.pdf',
                ],
                [
                    'title' => 'CP Cultura e Eventos 2021',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=12:cultura-e-eventos',
                    'filename' => 'cultura-e-eventos.pdf',
                ],
                [
                    'title' => 'CP Cultura e Eventos 2022',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=7:contrato-programa-cultura-e-eventos-2022',
                    'filename' => 'contrato-programa-cultura-e-eventos-2022.pdf',
                ],
                [
                    'title' => 'CP Delegação Competências Fiscalização e Estacionamento 2024',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=80:cp-delegao-competncias-fiscalizao-e-estacionamento-2024',
                    'filename' => 'cp-delegao-competncias-fiscalizao-e-estacionamento-2024.pdf',
                ],
                [
                    'title' => 'CP Educação 2017/2018',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=43:cp-educacao-2017-2018',
                    'filename' => 'cp-educacao-2017-2018.pdf',
                ],
                [
                    'title' => 'CP Educação 2018/2019',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=45:cp-educacao-2018-2019',
                    'filename' => 'cp-educacao-2018-2019.pdf',
                ],
                [
                    'title' => 'CP Educação 2019/2020',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=23:contrato-programa-com-o-municipio-da-nazare-educacao-2019-2020',
                    'filename' => 'contrato-programa-com-o-municipio-da-nazare-educacao-2019-2020.pdf',
                ],
                [
                    'title' => 'CP Educação 2021',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=13:educac-a-o',
                    'filename' => 'educac-a-o.pdf',
                ],
                [
                    'title' => 'CP Educação 2022',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=8:contrato-programa-educa-o',
                    'filename' => 'contrato-programa-educa-o.pdf',
                ],
                [
                    'title' => 'CP Gestão Estacionamento Público 2024',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=81:cp-gesto-estacionamento-pblico-2024',
                    'filename' => 'cp-gesto-estacionamento-pblico-2024.pdf',
                ],
                [
                    'title' => 'CP RSU 2019',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=51:cp-rsu-2019',
                    'filename' => 'cp-rsu-2019.pdf',
                ],
                [
                    'title' => 'CP RSU 2020',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=24:contrato-programa-com-os-servicos-municipalizados-da-nazare-rsu-2020',
                    'filename' => 'contrato-programa-com-os-servicos-municipalizados-da-nazare-rsu-2020.pdf',
                ],
                [
                    'title' => 'CP RSU 2021',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=17:rsu',
                    'filename' => 'rsu.pdf',
                ],
                [
                    'title' => 'CP RSU 2022',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=9:contrato-programa-rsu2022',
                    'filename' => 'contrato-programa-rsu2022.pdf',
                ],
                [
                    'title' => 'CP Saneamento 2020',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=25:contrato-programa-com-os-servicos-municipalizados-da-nazare-saneamento-2020',
                    'filename' => 'contrato-programa-com-os-servicos-municipalizados-da-nazare-saneamento-2020.pdf',
                ],
                [
                    'title' => 'CP Saneamento 2021',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=18:saneamento',
                    'filename' => 'saneamento.pdf',
                ],
                [
                    'title' => 'CP Transportes 2020',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=26:contrato-programa-com-os-servicos-municipalizados-da-nazare-transportes-2020',
                    'filename' => 'contrato-programa-com-os-servicos-municipalizados-da-nazare-transportes-2020.pdf',
                ],
                [
                    'title' => 'CP Transportes 2021',
                    'url' => '/index.php/a-empresa/doc-financeiros/27-contratos-programa?download=19:transportes',
                    'filename' => 'transportes.pdf',
                ],
            ],

            // ─── 3. Estatutos da Empresa (2 files) ─────────────────
            'Estatutos da Empresa' => [
                [
                    'title' => 'Estatutos NQ 2017',
                    'url' => '/index.php/a-empresa/doc-financeiros/18-estatutos-da-empresa?download=40:estatutos-nq',
                    'filename' => 'estatutos-nq.pdf',
                ],
                [
                    'title' => 'Estatutos NQ 2024',
                    'url' => '/index.php/a-empresa/doc-financeiros/18-estatutos-da-empresa?download=82:estatutos-nq-2024',
                    'filename' => 'estatutos-nq-2024.pdf',
                ],
            ],

            // ─── 4. Estrutura do Capital Social (1 file) ───────────
            'Estrutura do Capital Social' => [
                [
                    'title' => 'Estrutura do Capital Social da Empresa Municipal',
                    'url' => '/index.php/a-empresa/doc-financeiros/22-estrutura-do-capital-social?download=89:estrutura-do-capital-social-da-empresa-municipal',
                    'filename' => 'estrutura-do-capital-social-da-empresa-municipal.pdf',
                ],
            ],

            // ─── 5. Execuções Orçamentais (5 files) ────────────────
            'Execuções Orçamentais' => [
                [
                    'title' => 'EO 1.º Trimestre 2016',
                    'url' => '/index.php/a-empresa/doc-financeiros/20-execucoes-orcamentais?download=55:eo-1-trimestre-2016',
                    'filename' => 'eo-1-trimestre-2016.pdf',
                ],
                [
                    'title' => 'EO 1.º Trimestre 2017',
                    'url' => '/index.php/a-empresa/doc-financeiros/20-execucoes-orcamentais?download=52:eo-1-trimestre-2017',
                    'filename' => 'eo-1-trimestre-2017.pdf',
                ],
                [
                    'title' => 'EO 1.º Trimestre 2018',
                    'url' => '/index.php/a-empresa/doc-financeiros/20-execucoes-orcamentais?download=53:eo-1-trimestre-2018',
                    'filename' => 'eo-1-trimestre-2018.pdf',
                ],
                [
                    'title' => 'EO 1.º Trimestre 2019',
                    'url' => '/index.php/a-empresa/doc-financeiros/20-execucoes-orcamentais?download=54:eo-1-trimestre-2019',
                    'filename' => 'eo-1-trimestre-2019.pdf',
                ],
                [
                    'title' => 'EO 1.º Trimestre 2020',
                    'url' => '/index.php/a-empresa/doc-financeiros/20-execucoes-orcamentais?download=30:execucao-orcamental-1-trimestre-2020',
                    'filename' => 'execucao-orcamental-1-trimestre-2020.pdf',
                ],
            ],

            // ─── 6. Montantes Auferidos (1 file) ───────────────────
            'Montantes Auferidos Pelos Membros Remunerados dos Órgãos Sociais' => [
                [
                    'title' => 'Montantes Auferidos Pelos Membros Remunerados dos Órgãos Sociais',
                    'url' => '/index.php/a-empresa/doc-financeiros/23-montantes-auferidos-pelos-membros-remunerados-dos-orgao-sociais?download=107:montantes-auferidos-pelos-membros-remunerados-dos-orgos-sociais',
                    'filename' => 'montantes-auferidos-pelos-membros-remunerados-dos-orgos-sociais.pdf',
                ],
            ],

            // ─── 7. Número de Trabalhadores (2 files) ──────────────
            'Número de Trabalhadores por Modalidade de Vinculação' => [
                [
                    'title' => 'Número de Trabalhadores Desagregado Segundo a Modalidade de Vinculação',
                    'url' => '/index.php/a-empresa/doc-financeiros/24-numero-de-trabalhadores-desagregado-segundo-a-modalidade-de-vinculacao?download=91:numero-de-trabalhadores-desagregado-segundo-a-modalidade-de-vinculao',
                    'filename' => 'numero-de-trabalhadores-desagregado-segundo-a-modalidade-de-vinculao.pdf',
                ],
                [
                    'title' => 'Relatório sobre Remunerações por Género - Ano 2023',
                    'url' => '/index.php/a-empresa/doc-financeiros/24-numero-de-trabalhadores-desagregado-segundo-a-modalidade-de-vinculacao?download=97:relatorio-sobre-remuneracoes-por-genero-ano-2023',
                    'filename' => 'relatorio-sobre-remuneracoes-por-genero-ano-2023.pdf',
                ],
            ],

            // ─── 8. Orçamentos e Plano de Atividades (11 files) ────
            'Orçamentos e Plano de Atividades' => [
                [
                    'title' => 'Orçamento e Plano de Atividades 2015',
                    'url' => '/index.php/a-empresa/doc-financeiros/16-orcamentos-e-plano-de-atividades?download=56:orcamento-e-plano-de-atividades-2015',
                    'filename' => 'orcamento-e-plano-de-atividades-2015.pdf',
                ],
                [
                    'title' => 'Orçamento e Plano de Atividades 2016',
                    'url' => '/index.php/a-empresa/doc-financeiros/16-orcamentos-e-plano-de-atividades?download=57:orcamento-e-plano-de-atividades-2016',
                    'filename' => 'orcamento-e-plano-de-atividades-2016.pdf',
                ],
                [
                    'title' => 'Orçamento e Plano de Atividades 2017',
                    'url' => '/index.php/a-empresa/doc-financeiros/16-orcamentos-e-plano-de-atividades?download=58:orcamento-e-plano-de-atividades-2017',
                    'filename' => 'orcamento-e-plano-de-atividades-2017.pdf',
                ],
                [
                    'title' => 'Orçamento e Plano de Atividades 2018',
                    'url' => '/index.php/a-empresa/doc-financeiros/16-orcamentos-e-plano-de-atividades?download=59:orcamento-e-plano-de-atividades-2018',
                    'filename' => 'orcamento-e-plano-de-atividades-2018.pdf',
                ],
                [
                    'title' => 'Orçamento e Plano de Atividades 2019',
                    'url' => '/index.php/a-empresa/doc-financeiros/16-orcamentos-e-plano-de-atividades?download=60:orcamento-e-plano-de-atividades-2019',
                    'filename' => 'orcamento-e-plano-de-atividades-2019.pdf',
                ],
                [
                    'title' => 'Orçamento e Plano de Atividades 2020',
                    'url' => '/index.php/a-empresa/doc-financeiros/16-orcamentos-e-plano-de-atividades?download=38:orcamento-e-plano-de-atividades-2020',
                    'filename' => 'orcamento-e-plano-de-atividades-2020.pdf',
                ],
                [
                    'title' => 'Orçamento e Plano de Atividades 2021',
                    'url' => '/index.php/a-empresa/doc-financeiros/16-orcamentos-e-plano-de-atividades?download=14:orc-amento-e-plano-de-atividades-ano-2021',
                    'filename' => 'orc-amento-e-plano-de-atividades-ano-2021.pdf',
                ],
                [
                    'title' => 'Orçamento e Plano de Atividades 2022',
                    'url' => '/index.php/a-empresa/doc-financeiros/16-orcamentos-e-plano-de-atividades?download=10:or-amento-e-plano-de-atividades',
                    'filename' => 'or-amento-e-plano-de-atividades.pdf',
                ],
                [
                    'title' => 'Orçamento e Plano de Atividades 2023',
                    'url' => '/index.php/a-empresa/doc-financeiros/16-orcamentos-e-plano-de-atividades?download=4:or-amento-e-plano-atividades-2023',
                    'filename' => 'or-amento-e-plano-atividades-2023.pdf',
                ],
                [
                    'title' => 'Orçamento e Plano de Atividades 2024',
                    'url' => '/index.php/a-empresa/doc-financeiros/16-orcamentos-e-plano-de-atividades?download=85:oramento-e-plano-de-atividades-2024',
                    'filename' => 'oramento-e-plano-de-atividades-2024.pdf',
                ],
                [
                    'title' => 'Orçamento e Plano de Atividades 2025',
                    'url' => '/index.php/a-empresa/doc-financeiros/16-orcamentos-e-plano-de-atividades?download=100:orcamento-e-plano-de-atividades-2025',
                    'filename' => 'orcamento-e-plano-de-atividades-2025.pdf',
                ],
            ],

            // ─── 9. Outros (6 files) ───────────────────────────────
            'Outros' => [
                [
                    'title' => 'Código Conduta 2019',
                    'url' => '/index.php/a-empresa/doc-financeiros/28-outros?download=36:codigoconduta-nq-2019',
                    'filename' => 'codigoconduta-nq-2019.pdf',
                ],
                [
                    'title' => 'Código de Boa Conduta para Prevenção e Combate ao Assédio no Trabalho',
                    'url' => '/index.php/a-empresa/doc-financeiros/28-outros?download=95:codigo-de-boa-conduta-para-prevencao-e-combate-ao-assedio-no-trabalho',
                    'filename' => 'codigo-de-boa-conduta-para-prevencao-e-combate-ao-assedio-no-trabalho.pdf',
                ],
                [
                    'title' => 'Código de Ética e de Conduta 2024',
                    'url' => '/index.php/a-empresa/doc-financeiros/28-outros?download=93:codigo-de-etica-e-de-conduta-2024',
                    'filename' => 'codigo-de-etica-e-de-conduta-2024.pdf',
                ],
                [
                    'title' => 'Credenciais Agentes de Fiscalização de Trânsito',
                    'url' => '/index.php/a-empresa/doc-financeiros/28-outros?download=29:credenciais-agentes-de-transito',
                    'filename' => 'credenciais-agentes-de-transito.pdf',
                ],
                [
                    'title' => 'Orientações Estratégicas NQ 2023-2025',
                    'url' => '/index.php/a-empresa/doc-financeiros/28-outros?download=3:orientac-es-estrat-gicas-nq-2023-2025',
                    'filename' => 'orientac-es-estrat-gicas-nq-2023-2025.pdf',
                ],
                [
                    'title' => 'Plano para a Igualdade de Género',
                    'url' => '/index.php/a-empresa/doc-financeiros/28-outros?download=94:plano-para-a-igualdade-de-genero',
                    'filename' => 'plano-para-a-igualdade-de-genero.pdf',
                ],
            ],

            // ─── 10. Pareceres (10 files) ──────────────────────────
            'Pareceres (al. a) a c) do n.º 6 do art. 25.º)' => [
                [
                    'title' => 'Parecer SROC CP Educação 2016/2017',
                    'url' => '/index.php/a-empresa/doc-financeiros/25-pareceres-previstos-nas-alineas-a-a-c-do-n-6-do-artigo-25?download=61:parecer-sroc-cp-educacao-2016-2017',
                    'filename' => 'parecer-sroc-cp-educacao-2016-2017.pdf',
                ],
                [
                    'title' => 'Parecer SROC Contas Anuais 2020',
                    'url' => '/index.php/a-empresa/doc-financeiros/25-pareceres-previstos-nas-alineas-a-a-c-do-n-6-do-artigo-25?download=70:parecer-sroc-contas-anuais-2020',
                    'filename' => 'parecer-sroc-contas-anuais-2020.pdf',
                ],
                [
                    'title' => 'Parecer SROC Contas Semestrais 1.º Semestre 2021',
                    'url' => '/index.php/a-empresa/doc-financeiros/25-pareceres-previstos-nas-alineas-a-a-c-do-n-6-do-artigo-25?download=71:parecer-sroc-contas-semestrais-1-semestre-2021',
                    'filename' => 'parecer-sroc-contas-semestrais-1-semestre-2021.pdf',
                ],
                [
                    'title' => 'Parecer SROC Contas Anuais 2022',
                    'url' => '/index.php/a-empresa/doc-financeiros/25-pareceres-previstos-nas-alineas-a-a-c-do-n-6-do-artigo-25?download=72:parecer-sroc-contas-anuais-2022',
                    'filename' => 'parecer-sroc-contas-anuais-2022.pdf',
                ],
                [
                    'title' => 'Parecer SROC Contas Semestrais 1.º Semestre 2022',
                    'url' => '/index.php/a-empresa/doc-financeiros/25-pareceres-previstos-nas-alineas-a-a-c-do-n-6-do-artigo-25?download=73:parecer-sroc-contas-semestrais-1-semestre-2022',
                    'filename' => 'parecer-sroc-contas-semestrais-1-semestre-2022.pdf',
                ],
                [
                    'title' => 'Parecer SROC Contas Semestrais 1.º Semestre 2023',
                    'url' => '/index.php/a-empresa/doc-financeiros/25-pareceres-previstos-nas-alineas-a-a-c-do-n-6-do-artigo-25?download=77:parecer-sroc-contas-semestrais-1-semestre-2023',
                    'filename' => 'parecer-sroc-contas-semestrais-1-semestre-2023.pdf',
                ],
                [
                    'title' => 'Parecer SROC CP Delegação Competências Fiscalização Estacionamento 2024-2026',
                    'url' => '/index.php/a-empresa/doc-financeiros/25-pareceres-previstos-nas-alineas-a-a-c-do-n-6-do-artigo-25?download=86:parecer-sroc-cp-deleg-compet-fiscalizao-estacionamento-2024-2026',
                    'filename' => 'parecer-sroc-cp-deleg-compet-fiscalizao-estacionamento-2024-2026.pdf',
                ],
                [
                    'title' => 'Parecer SROC Orçamento e Plano Atividades 2024',
                    'url' => '/index.php/a-empresa/doc-financeiros/25-pareceres-previstos-nas-alineas-a-a-c-do-n-6-do-artigo-25?download=87:parecer-sroc-oramento-e-plano-atividades-2024',
                    'filename' => 'parecer-sroc-oramento-e-plano-atividades-2024.pdf',
                ],
                [
                    'title' => 'Parecer SROC Orçamento e Plano Atividades 2025',
                    'url' => '/index.php/a-empresa/doc-financeiros/25-pareceres-previstos-nas-alineas-a-a-c-do-n-6-do-artigo-25?download=88:parecer-sroc-oramento-e-plano-atividades-2024',
                    'filename' => 'parecer-sroc-oramento-e-plano-atividades-2025.pdf',
                ],
                [
                    'title' => 'Relatório do Fiscal Único 2025',
                    'url' => '/index.php/a-empresa/doc-financeiros/25-pareceres-previstos-nas-alineas-a-a-c-do-n-6-do-artigo-25?download=101:relatorio-do-fiscal-unico-2025',
                    'filename' => 'relatorio-do-fiscal-unico-2025.pdf',
                ],
            ],

            // ─── 11. Plano de Prevenção de Riscos (4 files) ────────
            'Plano de Prevenção de Riscos de Corrupção' => [
                [
                    'title' => '1.ª Revisão PPRGIRCIC',
                    'url' => '/index.php/a-empresa/doc-financeiros/21-plano-de-prevencao-de-gestao-de-riscos-de-corrupcao-e-infracoes-conexas?download=20:1-revisao-pprgircic',
                    'filename' => '1-revisao-pprgircic.pdf',
                ],
                [
                    'title' => 'Plano Prevenção Riscos Gestão incluindo Riscos de Corrupção 2019',
                    'url' => '/index.php/a-empresa/doc-financeiros/21-plano-de-prevencao-de-gestao-de-riscos-de-corrupcao-e-infracoes-conexas?download=37:plano-prevencao-riscos-gestao-incluindo-riscos-de-corrupcao',
                    'filename' => 'plano-prevencao-riscos-gestao-incluindo-riscos-de-corrupcao.pdf',
                ],
                [
                    'title' => 'Plano de Prevenção de Riscos de Gestão - Relatório de Execução 2020',
                    'url' => '/index.php/a-empresa/doc-financeiros/21-plano-de-prevencao-de-gestao-de-riscos-de-corrupcao-e-infracoes-conexas?download=33:relatorio-execucao-2020-pprgircic',
                    'filename' => 'relatorio-execucao-2020-pprgircic.pdf',
                ],
                [
                    'title' => 'Plano de Prevenção de Riscos de Corrupção e Infrações Conexas 2024',
                    'url' => '/index.php/a-empresa/doc-financeiros/21-plano-de-prevencao-de-gestao-de-riscos-de-corrupcao-e-infracoes-conexas?download=96:plano-de-prevencao-de-riscos-de-corrupcao-e-infracoes-conexas-2024',
                    'filename' => 'plano-de-prevencao-de-riscos-de-corrupcao-e-infracoes-conexas-2024.pdf',
                ],
            ],

            // ─── 12. Prestação de Contas Anuais (12 files) ─────────
            'Prestação de Contas Anuais' => [
                [
                    'title' => 'Relatório e Contas 2013',
                    'url' => '/index.php/a-empresa/doc-financeiros/17-prestacao-de-contas-anuais?download=64:relatorio-e-contas-2013',
                    'filename' => 'relatorio-e-contas-2013.pdf',
                ],
                [
                    'title' => 'Relatório e Contas 2014',
                    'url' => '/index.php/a-empresa/doc-financeiros/17-prestacao-de-contas-anuais?download=65:relatorio-e-contas-2014',
                    'filename' => 'relatorio-e-contas-2014.pdf',
                ],
                [
                    'title' => 'Relatório e Contas 2015',
                    'url' => '/index.php/a-empresa/doc-financeiros/17-prestacao-de-contas-anuais?download=66:relatorio-e-contas-2015',
                    'filename' => 'relatorio-e-contas-2015.pdf',
                ],
                [
                    'title' => 'Relatório e Contas 2016',
                    'url' => '/index.php/a-empresa/doc-financeiros/17-prestacao-de-contas-anuais?download=67:relatorio-e-contas-2016',
                    'filename' => 'relatorio-e-contas-2016.pdf',
                ],
                [
                    'title' => 'Relatório e Contas 2017',
                    'url' => '/index.php/a-empresa/doc-financeiros/17-prestacao-de-contas-anuais?download=68:relatorio-e-contas-2017',
                    'filename' => 'relatorio-e-contas-2017.pdf',
                ],
                [
                    'title' => 'Relatório e Contas 2018',
                    'url' => '/index.php/a-empresa/doc-financeiros/17-prestacao-de-contas-anuais?download=69:relatorio-e-contas-2018',
                    'filename' => 'relatorio-e-contas-2018.pdf',
                ],
                [
                    'title' => 'Relatório e Contas 2019',
                    'url' => '/index.php/a-empresa/doc-financeiros/17-prestacao-de-contas-anuais?download=31:relatorio-de-contas-2019',
                    'filename' => 'relatorio-de-contas-2019.pdf',
                ],
                [
                    'title' => 'Relatório e Contas 2020',
                    'url' => '/index.php/a-empresa/doc-financeiros/17-prestacao-de-contas-anuais?download=35:relatoriodecontas2020-compressed',
                    'filename' => 'relatoriodecontas2020-compressed.pdf',
                ],
                [
                    'title' => 'Relatório e Contas 2021',
                    'url' => '/index.php/a-empresa/doc-financeiros/17-prestacao-de-contas-anuais?download=15:relatorio-e-contas-2021-nq',
                    'filename' => 'relatorio-e-contas-2021-nq.pdf',
                ],
                [
                    'title' => 'Relatório e Contas 2022',
                    'url' => '/index.php/a-empresa/doc-financeiros/17-prestacao-de-contas-anuais?download=5:relat-rio-e-contas-2022',
                    'filename' => 'relat-rio-e-contas-2022.pdf',
                ],
                [
                    'title' => 'Relatório e Contas 2023',
                    'url' => '/index.php/a-empresa/doc-financeiros/17-prestacao-de-contas-anuais?download=92:relatorio-e-contas-2023',
                    'filename' => 'relatorio-e-contas-2023.pdf',
                ],
                [
                    'title' => 'Relatório e Contas 2024',
                    'url' => '/index.php/a-empresa/doc-financeiros/17-prestacao-de-contas-anuais?download=106:relatorio-e-contas-2024',
                    'filename' => 'relatorio-e-contas-2024.pdf',
                ],
            ],

            // ─── 13. Prestação de Contas Semestrais (9 files) ──────
            'Prestação de Contas Semestrais' => [
                [
                    'title' => 'RC 1.º Semestre 2016',
                    'url' => '/index.php/a-empresa/doc-financeiros/19-prestacao-de-contas-semestrais?download=41:rc-1-semestre-2016',
                    'filename' => 'rc-1-semestre-2016.pdf',
                ],
                [
                    'title' => 'RC 1.º Semestre 2017',
                    'url' => '/index.php/a-empresa/doc-financeiros/19-prestacao-de-contas-semestrais?download=62:rc-1-semestre-2017',
                    'filename' => 'rc-1-semestre-2017.pdf',
                ],
                [
                    'title' => 'RC 1.º Semestre 2018',
                    'url' => '/index.php/a-empresa/doc-financeiros/19-prestacao-de-contas-semestrais?download=63:rc-1-semestre-2018',
                    'filename' => 'rc-1-semestre-2018.pdf',
                ],
                [
                    'title' => 'RC 1.º Semestre 2019',
                    'url' => '/index.php/a-empresa/doc-financeiros/19-prestacao-de-contas-semestrais?download=39:relatorio-contas-1-semestre-2019',
                    'filename' => 'relatorio-contas-1-semestre-2019.pdf',
                ],
                [
                    'title' => 'RC 1.º Semestre 2020',
                    'url' => '/index.php/a-empresa/doc-financeiros/19-prestacao-de-contas-semestrais?download=32:relatorio-e-contas-1-semestre-2020',
                    'filename' => 'relatorio-e-contas-1-semestre-2020.pdf',
                ],
                [
                    'title' => 'RC 1.º Semestre 2021',
                    'url' => '/index.php/a-empresa/doc-financeiros/19-prestacao-de-contas-semestrais?download=16:relatorioecontas2021',
                    'filename' => 'relatorioecontas2021.pdf',
                ],
                [
                    'title' => 'RC 1.º Semestre 2022',
                    'url' => '/index.php/a-empresa/doc-financeiros/19-prestacao-de-contas-semestrais?download=74:rc-1-semestre-2022',
                    'filename' => 'rc-1-semestre-2022.pdf',
                ],
                [
                    'title' => 'RC 1.º Semestre 2023',
                    'url' => '/index.php/a-empresa/doc-financeiros/19-prestacao-de-contas-semestrais?download=78:rc-1-semestre-2023',
                    'filename' => 'rc-1-semestre-2023.pdf',
                ],
                [
                    'title' => 'Prestação de Contas do 1.º Semestre 2024',
                    'url' => '/index.php/a-empresa/doc-financeiros/19-prestacao-de-contas-semestrais?download=99:prestacao-de-contas-do-1-semestre-2024',
                    'filename' => 'prestacao-de-contas-do-1-semestre-2024.pdf',
                ],
            ],
        ];
    }
}
