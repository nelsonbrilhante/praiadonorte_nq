<?php

namespace Database\Seeders;

use App\Models\Noticia;
use Database\Seeders\Traits\DownloadsImages;
use Illuminate\Database\Seeder;

class NoticiaSeeder extends Seeder
{
    use DownloadsImages;

    public function run(): void
    {
        // Skip seeding if real data has been imported
        if (Noticia::count() > 0) {
            return;
        }

        $images = [
            'https://images.unsplash.com/photo-1502680390469-be75c86b636f?w=1200&h=800&fit=crop',
            'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&h=800&fit=crop',
            'https://images.unsplash.com/photo-1455264745730-cb3b76250ae8?w=1200&h=800&fit=crop',
            'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1200&h=800&fit=crop',
            'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=800&fit=crop',
            'https://images.unsplash.com/photo-1531722569936-825d3dd91b15?w=1200&h=800&fit=crop',
        ];

        $noticias = [
            [
                'title' => [
                    'pt' => 'Temporada de Ondas Gigantes 2024/2025 Arranca com Força na Nazaré',
                    'en' => 'Giant Wave Season 2024/2025 Kicks Off Strong in Nazaré',
                ],
                'slug' => 'temporada-ondas-gigantes-2024-2025',
                'content' => [
                    'pt' => '<p>A temporada de ondas gigantes 2024/2025 começou em grande na Praia do Norte.</p>',
                    'en' => '<p>The 2024/2025 giant wave season started strong at Praia do Norte.</p>',
                ],
                'excerpt' => [
                    'pt' => 'As primeiras ondulações do outono trouxeram ondas de até 15 metros à Praia do Norte.',
                    'en' => 'The first autumn swells brought waves up to 15 meters to Praia do Norte.',
                ],
                'image_url' => $images[0],
                'author' => 'Redação',
                'category' => 'Surf',
                'entity' => 'praia-norte',
                'tags' => ['ondas gigantes', 'temporada', 'nazaré', 'surf'],
                'featured' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => [
                    'pt' => 'WSL Confirma Nazaré Tow Surfing Challenge para Fevereiro',
                    'en' => 'WSL Confirms Nazaré Tow Surfing Challenge for February',
                ],
                'slug' => 'wsl-nazare-tow-challenge-fevereiro',
                'content' => [
                    'pt' => '<p>A World Surf League confirmou oficialmente que o Nazaré Tow Surfing Challenge decorrerá entre 1 de fevereiro e 28 de fevereiro de 2025.</p>',
                    'en' => '<p>The World Surf League has officially confirmed that the Nazaré Tow Surfing Challenge will take place between February 1 and February 28, 2025.</p>',
                ],
                'excerpt' => [
                    'pt' => 'O prestigiado evento da WSL regressa à Nazaré em fevereiro de 2025.',
                    'en' => 'The prestigious WSL event returns to Nazaré in February 2025.',
                ],
                'image_url' => $images[1],
                'author' => 'Redação',
                'category' => 'Competição',
                'entity' => 'praia-norte',
                'tags' => ['wsl', 'competição', 'tow surfing'],
                'featured' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => [
                    'pt' => 'Carsurf Abre Inscrições para Programa de Treino de Inverno',
                    'en' => 'Carsurf Opens Registration for Winter Training Program',
                ],
                'slug' => 'carsurf-programa-treino-inverno',
                'content' => [
                    'pt' => '<p>O Centro de Alto Rendimento Carsurf anunciou a abertura de inscrições para o programa de treino de inverno 2024/2025.</p>',
                    'en' => '<p>The Carsurf High Performance Center has announced the opening of registrations for the 2024/2025 winter training program.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Programa intensivo de treino para surfistas que querem evoluir nas ondas da Nazaré.',
                    'en' => 'Intensive training program for surfers looking to improve in Nazaré\'s waves.',
                ],
                'image_url' => $images[2],
                'author' => 'Carsurf',
                'category' => 'Formação',
                'entity' => 'carsurf',
                'tags' => ['treino', 'carsurf', 'formação'],
                'featured' => false,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => [
                    'pt' => 'Nazaré Qualifica Investe em Nova Infraestrutura de Apoio aos Surfistas',
                    'en' => 'Nazaré Qualifica Invests in New Surfer Support Infrastructure',
                ],
                'slug' => 'nazare-qualifica-infraestrutura-surfistas',
                'content' => [
                    'pt' => '<p>A Nazaré Qualifica, EM, anunciou um investimento significativo em novas infraestruturas de apoio aos surfistas na Praia do Norte.</p>',
                    'en' => '<p>Nazaré Qualifica, EM, has announced a significant investment in new support infrastructure for surfers at Praia do Norte.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Novo investimento em balneários, armazenamento e posto médico na Praia do Norte.',
                    'en' => 'New investment in changing rooms, storage, and medical station at Praia do Norte.',
                ],
                'image_url' => $images[3],
                'author' => 'Nazaré Qualifica',
                'category' => 'Infraestrutura',
                'entity' => 'nazare-qualifica',
                'tags' => ['infraestrutura', 'investimento'],
                'featured' => false,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => [
                    'pt' => 'Recorde de Visitantes no Forte de São Miguel Arcanjo',
                    'en' => 'Visitor Record at Fort São Miguel Arcanjo',
                ],
                'slug' => 'recorde-visitantes-forte-sao-miguel',
                'content' => [
                    'pt' => '<p>O miradouro do Forte de São Miguel Arcanjo registou um novo recorde de visitantes durante o último fim de semana.</p>',
                    'en' => '<p>The Fort São Miguel Arcanjo viewpoint recorded a new visitor record during the last weekend.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Mais de 5.000 visitantes no forte durante fim de semana de ondas grandes.',
                    'en' => 'More than 5,000 visitors at the fort during a big wave weekend.',
                ],
                'image_url' => $images[4],
                'author' => 'Redação',
                'category' => 'Turismo',
                'entity' => 'praia-norte',
                'tags' => ['turismo', 'forte', 'visitantes'],
                'featured' => false,
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => [
                    'pt' => 'Sofia Mendes Entra para o Top 10 Mundial de Big Wave',
                    'en' => 'Sofia Mendes Enters World Big Wave Top 10',
                ],
                'slug' => 'sofia-mendes-top-10-mundial',
                'content' => [
                    'pt' => '<p>A surfista portuguesa Sofia Mendes alcançou um marco histórico ao entrar para o top 10 do ranking mundial de big wave feminino.</p>',
                    'en' => '<p>Portuguese surfer Sofia Mendes achieved a historic milestone by entering the top 10 of the women\'s world big wave ranking.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Surfista portuguesa alcança top 10 mundial após performances impressionantes.',
                    'en' => 'Portuguese surfer reaches world top 10 after impressive performances.',
                ],
                'image_url' => $images[5],
                'author' => 'Redação',
                'category' => 'Atletas',
                'entity' => 'praia-norte',
                'tags' => ['sofia mendes', 'ranking', 'big wave'],
                'featured' => true,
                'published_at' => now()->subDays(3),
            ],
        ];

        foreach ($noticias as $noticia) {
            $imageUrl = $noticia['image_url'] ?? null;
            unset($noticia['image_url']);

            $coverImage = null;
            if ($imageUrl) {
                $filename = "noticia-{$noticia['slug']}.jpg";
                $coverImage = $this->downloadImage($imageUrl, 'noticias', $filename);
            }

            $noticia['cover_image'] = $coverImage;
            Noticia::create($noticia);
        }
    }
}
