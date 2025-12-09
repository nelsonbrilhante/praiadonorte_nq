<?php

namespace Database\Seeders;

use App\Models\Noticia;
use Illuminate\Database\Seeder;

class NoticiaSeeder extends Seeder
{
    public function run(): void
    {
        $noticias = [
            [
                'title' => [
                    'pt' => 'Temporada de Ondas Gigantes 2024/2025 Arranca com Força na Nazaré',
                    'en' => 'Giant Wave Season 2024/2025 Kicks Off Strong in Nazaré',
                ],
                'slug' => 'temporada-ondas-gigantes-2024-2025',
                'content' => [
                    'pt' => '<p>A temporada de ondas gigantes 2024/2025 começou em grande na Praia do Norte. As primeiras ondulações significativas do outono trouxeram ondas de até 15 metros, atraindo surfistas de todo o mundo.</p><p>O Forte de São Miguel Arcanjo registou uma afluência recorde de espectadores no primeiro grande swell da temporada. As condições meteorológicas favoráveis prometem uma época memorável.</p><p>"Este ano temos condições excecionais", afirmou o responsável pela segurança na praia. "A combinação de swell e vento está perfeita para os surfistas."</p>',
                    'en' => '<p>The 2024/2025 giant wave season started strong at Praia do Norte. The first significant swells of autumn brought waves up to 15 meters, attracting surfers from around the world.</p><p>Fort São Miguel Arcanjo recorded a record attendance of spectators during the first big swell of the season. Favorable weather conditions promise a memorable season.</p><p>"This year we have exceptional conditions," said the beach safety officer. "The combination of swell and wind is perfect for surfers."</p>',
                ],
                'excerpt' => [
                    'pt' => 'As primeiras ondulações do outono trouxeram ondas de até 15 metros à Praia do Norte, marcando o início de uma temporada promissora.',
                    'en' => 'The first autumn swells brought waves up to 15 meters to Praia do Norte, marking the start of a promising season.',
                ],
                'cover_image' => null,
                'author' => 'Redação',
                'category' => 'Surf',
                'entity' => 'praia-norte',
                'tags' => ['ondas gigantes', 'temporada', 'nazaré', 'surf'],
                'featured' => true,
                'published_at' => now()->subDays(2),
                'seo_title' => ['pt' => 'Temporada Ondas Gigantes Nazaré 2024/2025', 'en' => 'Nazaré Giant Wave Season 2024/2025'],
                'seo_description' => ['pt' => 'A temporada de ondas gigantes começou na Praia do Norte com ondas de 15 metros.', 'en' => 'The giant wave season has started at Praia do Norte with 15-meter waves.'],
            ],
            [
                'title' => [
                    'pt' => 'WSL Confirma Nazaré Tow Surfing Challenge para Fevereiro',
                    'en' => 'WSL Confirms Nazaré Tow Surfing Challenge for February',
                ],
                'slug' => 'wsl-nazare-tow-challenge-fevereiro',
                'content' => [
                    'pt' => '<p>A World Surf League confirmou oficialmente que o Nazaré Tow Surfing Challenge decorrerá entre 1 de fevereiro e 28 de fevereiro de 2025. O evento faz parte do circuito Big Wave Tour e reúne os melhores surfistas de ondas gigantes do planeta.</p><p>Os atletas já começaram a chegar à Nazaré para os treinos preparatórios. A organização espera que as condições ideais se verifiquem durante a janela do evento.</p>',
                    'en' => '<p>The World Surf League has officially confirmed that the Nazaré Tow Surfing Challenge will take place between February 1 and February 28, 2025. The event is part of the Big Wave Tour and brings together the best giant wave surfers on the planet.</p><p>Athletes have already started arriving in Nazaré for preparatory training. The organization expects ideal conditions during the event window.</p>',
                ],
                'excerpt' => [
                    'pt' => 'O prestigiado evento da WSL regressa à Nazaré em fevereiro de 2025 com os melhores surfistas do mundo.',
                    'en' => 'The prestigious WSL event returns to Nazaré in February 2025 with the world\'s best surfers.',
                ],
                'cover_image' => null,
                'author' => 'Redação',
                'category' => 'Competição',
                'entity' => 'praia-norte',
                'tags' => ['wsl', 'competição', 'tow surfing', 'big wave tour'],
                'featured' => true,
                'published_at' => now()->subDays(5),
                'seo_title' => ['pt' => 'WSL Nazaré Tow Challenge 2025', 'en' => 'WSL Nazaré Tow Challenge 2025'],
                'seo_description' => ['pt' => 'Evento do Big Wave Tour confirmado para fevereiro na Nazaré.', 'en' => 'Big Wave Tour event confirmed for February in Nazaré.'],
            ],
            [
                'title' => [
                    'pt' => 'Carsurf Abre Inscrições para Programa de Treino de Inverno',
                    'en' => 'Carsurf Opens Registration for Winter Training Program',
                ],
                'slug' => 'carsurf-programa-treino-inverno',
                'content' => [
                    'pt' => '<p>O Centro de Alto Rendimento Carsurf anunciou a abertura de inscrições para o programa de treino de inverno 2024/2025. O programa destina-se a surfistas de nível intermédio a avançado que pretendam melhorar a sua performance.</p><p>As sessões incluem treino físico específico, análise de vídeo, preparação mental e sessões de surf supervisionadas. Os participantes terão acesso às instalações de ponta do centro.</p><p>"Este programa é único em Portugal", explica o diretor técnico. "Combinamos metodologia científica com conhecimento prático das ondas da Nazaré."</p>',
                    'en' => '<p>The Carsurf High Performance Center has announced the opening of registrations for the 2024/2025 winter training program. The program is aimed at intermediate to advanced surfers looking to improve their performance.</p><p>Sessions include specific physical training, video analysis, mental preparation, and supervised surf sessions. Participants will have access to the center\'s state-of-the-art facilities.</p><p>"This program is unique in Portugal," explains the technical director. "We combine scientific methodology with practical knowledge of Nazaré\'s waves."</p>',
                ],
                'excerpt' => [
                    'pt' => 'Programa intensivo de treino para surfistas que querem evoluir nas ondas da Nazaré.',
                    'en' => 'Intensive training program for surfers looking to improve in Nazaré\'s waves.',
                ],
                'cover_image' => null,
                'author' => 'Carsurf',
                'category' => 'Formação',
                'entity' => 'carsurf',
                'tags' => ['treino', 'carsurf', 'formação', 'surf'],
                'featured' => false,
                'published_at' => now()->subDays(7),
                'seo_title' => ['pt' => 'Programa Treino Carsurf Inverno', 'en' => 'Carsurf Winter Training Program'],
                'seo_description' => ['pt' => 'Inscrições abertas para programa de treino no Carsurf.', 'en' => 'Registrations open for training program at Carsurf.'],
            ],
            [
                'title' => [
                    'pt' => 'Nazaré Qualifica Investe em Nova Infraestrutura de Apoio aos Surfistas',
                    'en' => 'Nazaré Qualifica Invests in New Surfer Support Infrastructure',
                ],
                'slug' => 'nazare-qualifica-infraestrutura-surfistas',
                'content' => [
                    'pt' => '<p>A Nazaré Qualifica, EM, anunciou um investimento significativo em novas infraestruturas de apoio aos surfistas na Praia do Norte. O projeto inclui novos balneários, área de armazenamento de equipamento e posto de primeiros socorros melhorado.</p><p>As obras têm início previsto para março de 2025 e deverão estar concluídas antes do início da próxima temporada de ondas gigantes.</p><p>"Queremos oferecer as melhores condições possíveis aos atletas que escolhem a Nazaré", afirmou o presidente da Nazaré Qualifica.</p>',
                    'en' => '<p>Nazaré Qualifica, EM, has announced a significant investment in new support infrastructure for surfers at Praia do Norte. The project includes new changing rooms, equipment storage area, and an improved first aid station.</p><p>Construction is scheduled to begin in March 2025 and should be completed before the start of the next giant wave season.</p><p>"We want to offer the best possible conditions to athletes who choose Nazaré," said the president of Nazaré Qualifica.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Novo investimento em balneários, armazenamento e posto médico na Praia do Norte.',
                    'en' => 'New investment in changing rooms, storage, and medical station at Praia do Norte.',
                ],
                'cover_image' => null,
                'author' => 'Nazaré Qualifica',
                'category' => 'Infraestrutura',
                'entity' => 'nazare-qualifica',
                'tags' => ['infraestrutura', 'investimento', 'nazaré qualifica'],
                'featured' => false,
                'published_at' => now()->subDays(10),
                'seo_title' => ['pt' => 'Investimento Infraestrutura Nazaré', 'en' => 'Nazaré Infrastructure Investment'],
                'seo_description' => ['pt' => 'Nazaré Qualifica investe em novas infraestruturas de apoio.', 'en' => 'Nazaré Qualifica invests in new support infrastructure.'],
            ],
            [
                'title' => [
                    'pt' => 'Recorde de Visitantes no Forte de São Miguel Arcanjo',
                    'en' => 'Visitor Record at Fort São Miguel Arcanjo',
                ],
                'slug' => 'recorde-visitantes-forte-sao-miguel',
                'content' => [
                    'pt' => '<p>O miradouro do Forte de São Miguel Arcanjo registou um novo recorde de visitantes durante o último fim de semana. Mais de 5.000 pessoas deslocaram-se ao local para assistir às ondas gigantes.</p><p>O aumento do turismo ligado ao surf tem impulsionado a economia local, com hotéis e restaurantes a registarem elevadas taxas de ocupação durante a temporada de ondas.</p>',
                    'en' => '<p>The Fort São Miguel Arcanjo viewpoint recorded a new visitor record during the last weekend. More than 5,000 people traveled to the location to watch the giant waves.</p><p>The increase in surf-related tourism has been driving the local economy, with hotels and restaurants recording high occupancy rates during the wave season.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Mais de 5.000 visitantes no forte durante fim de semana de ondas grandes.',
                    'en' => 'More than 5,000 visitors at the fort during a big wave weekend.',
                ],
                'cover_image' => null,
                'author' => 'Redação',
                'category' => 'Turismo',
                'entity' => 'praia-norte',
                'tags' => ['turismo', 'forte', 'visitantes', 'nazaré'],
                'featured' => false,
                'published_at' => now()->subDays(12),
                'seo_title' => ['pt' => 'Recorde Visitantes Forte Nazaré', 'en' => 'Nazaré Fort Visitor Record'],
                'seo_description' => ['pt' => 'Forte de São Miguel Arcanjo bate recorde de visitantes.', 'en' => 'Fort São Miguel Arcanjo breaks visitor record.'],
            ],
            [
                'title' => [
                    'pt' => 'Sofia Mendes Entra para o Top 10 Mundial de Big Wave',
                    'en' => 'Sofia Mendes Enters World Big Wave Top 10',
                ],
                'slug' => 'sofia-mendes-top-10-mundial',
                'content' => [
                    'pt' => '<p>A surfista portuguesa Sofia Mendes alcançou um marco histórico ao entrar para o top 10 do ranking mundial de big wave feminino. A atleta da Nazaré subiu cinco posições após as suas performances na última etapa do circuito.</p><p>"É um sonho tornado realidade", afirmou Sofia. "Treinar todos os dias na Nazaré deu-me a confiança necessária para competir ao mais alto nível."</p>',
                    'en' => '<p>Portuguese surfer Sofia Mendes achieved a historic milestone by entering the top 10 of the women\'s world big wave ranking. The Nazaré athlete climbed five positions after her performances in the last stage of the circuit.</p><p>"It\'s a dream come true," said Sofia. "Training every day in Nazaré gave me the confidence I needed to compete at the highest level."</p>',
                ],
                'excerpt' => [
                    'pt' => 'Surfista portuguesa alcança top 10 mundial após performances impressionantes.',
                    'en' => 'Portuguese surfer reaches world top 10 after impressive performances.',
                ],
                'cover_image' => null,
                'author' => 'Redação',
                'category' => 'Atletas',
                'entity' => 'praia-norte',
                'tags' => ['sofia mendes', 'ranking', 'big wave', 'portugal'],
                'featured' => true,
                'published_at' => now()->subDays(3),
                'seo_title' => ['pt' => 'Sofia Mendes Top 10 Big Wave', 'en' => 'Sofia Mendes Big Wave Top 10'],
                'seo_description' => ['pt' => 'Sofia Mendes entra no top 10 mundial de ondas gigantes.', 'en' => 'Sofia Mendes enters world big wave top 10.'],
            ],
        ];

        foreach ($noticias as $noticia) {
            Noticia::create($noticia);
        }
    }
}
