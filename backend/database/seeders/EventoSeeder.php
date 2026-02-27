<?php

namespace Database\Seeders;

use App\Models\Evento;
use Database\Seeders\Traits\DownloadsImages;
use Illuminate\Database\Seeder;

class EventoSeeder extends Seeder
{
    use DownloadsImages;

    public function run(): void
    {
        Evento::truncate();

        // Unsplash direct image URLs for events
        $images = [
            // Tow surfing competition
            'https://images.unsplash.com/photo-1509914398892-963f53e6e2f1?w=1200&h=800&fit=crop',
            // Safety workshop/training
            'https://images.unsplash.com/photo-1599058917765-a780eda07a3e?w=1200&h=800&fit=crop',
            // Exhibition/museum
            'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200&h=800&fit=crop',
            // Young surfers/kids
            'https://images.unsplash.com/photo-1530870110042-98b2cb110834?w=1200&h=800&fit=crop',
            // Conference
            'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&h=800&fit=crop',
            // Beach cleanup
            'https://images.unsplash.com/photo-1618477461853-cf6ed80faba5?w=1200&h=800&fit=crop',
            // Big wave from above
            'https://images.unsplash.com/photo-1502680390469-be75c86b636f?w=1200&h=800&fit=crop',
            // Sunset beach
            'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&h=800&fit=crop',
            // Surf training
            'https://images.unsplash.com/photo-1455264745730-cb3b76250ae8?w=1200&h=800&fit=crop',
            // Aerial ocean
            'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=800&fit=crop',
            // Female surfer
            'https://images.unsplash.com/photo-1531722569936-825d3dd91b15?w=1200&h=800&fit=crop',
            // Cliff/lighthouse
            'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=1200&h=800&fit=crop',
        ];

        // Gallery images (extra images for gallery fields)
        $galleryImages = [
            'https://images.unsplash.com/photo-1468581264429-2548ef9eb732?w=800&h=800&fit=crop',
            'https://images.unsplash.com/photo-1502933691298-84fc14542831?w=800&h=800&fit=crop',
            'https://images.unsplash.com/photo-1527769929977-c341ee9f2e66?w=800&h=800&fit=crop',
            'https://images.unsplash.com/photo-1486890598084-3673ba1a5c5d?w=800&h=800&fit=crop',
            'https://images.unsplash.com/photo-1519451241324-20b4ea2c4220?w=800&h=800&fit=crop',
            'https://images.unsplash.com/photo-1505459668311-8dfac7952bf0?w=800&h=800&fit=crop',
            'https://images.unsplash.com/photo-1510022079733-8b58aca7c4a9?w=800&h=800&fit=crop',
            'https://images.unsplash.com/photo-1416339306562-f3d12fefd36f?w=800&h=800&fit=crop',
        ];

        $eventos = [
            // ===== UPCOMING EVENTS =====
            [
                'title' => [
                    'pt' => 'Nazaré Tow Surfing Challenge 2026',
                    'en' => 'Nazaré Tow Surfing Challenge 2026',
                ],
                'slug' => 'nazare-tow-surfing-challenge-2026',
                'description' => [
                    'pt' => '<p>O evento mais aguardado do ano no mundo do surf de ondas gigantes regressa à Nazaré. O Nazaré Tow Surfing Challenge faz parte do circuito Big Wave Tour da WSL e reúne os melhores surfistas do planeta.</p><p>Durante a janela do evento, os organizadores aguardam as condições ideais para dar o green light e iniciar a competição. Os atletas estarão em standby, prontos para entrar na água assim que as ondas atinjam o tamanho necessário.</p>',
                    'en' => '<p>The most anticipated event of the year in big wave surfing returns to Nazaré. The Nazaré Tow Surfing Challenge is part of the WSL Big Wave Tour and brings together the best surfers on the planet.</p><p>During the event window, organizers await ideal conditions to give the green light and start the competition. Athletes will be on standby, ready to enter the water as soon as waves reach the required size.</p>',
                ],
                'excerpt' => [
                    'pt' => 'O principal evento de surf de ondas gigantes do mundo regressa à Praia do Norte. Os melhores surfistas do planeta competem nas maiores ondas do mundo.',
                    'en' => 'The premier big wave surfing event returns to Praia do Norte. The world\'s best surfers compete on the biggest waves on the planet.',
                ],
                'start_date' => now()->addWeeks(2),
                'end_date' => now()->addWeeks(6),
                'location' => 'Praia do Norte, Nazaré',
                'entity' => 'praia-norte',
                'category' => 'Surf',
                'image_url' => $images[0],
                'gallery_urls' => [$galleryImages[0], $galleryImages[1], $galleryImages[2], $galleryImages[3]],
                'ticket_url' => 'https://www.worldsurfleague.com',
                'video_url' => 'https://www.youtube.com/embed/GJc4Ir78KRc',
                'schedule' => [
                    'pt' => '<ul><li><strong>Janela de espera:</strong> 2 semanas — os organizadores monitorizam as condições</li><li><strong>Dia de competição:</strong> Green light às 8h00, competição das 9h00 às 16h00</li><li><strong>Cerimónia de encerramento:</strong> 17h00 no Forte de São Miguel Arcanjo</li></ul>',
                    'en' => '<ul><li><strong>Waiting period:</strong> 2 weeks — organizers monitor conditions</li><li><strong>Competition day:</strong> Green light at 8:00 AM, competition from 9:00 AM to 4:00 PM</li><li><strong>Closing ceremony:</strong> 5:00 PM at Fort São Miguel Arcanjo</li></ul>',
                ],
                'partners' => [
                    ['name' => 'WSL', 'logo' => null, 'url' => 'https://www.worldsurfleague.com', 'type' => 'premium'],
                    ['name' => 'Câmara Municipal da Nazaré', 'logo' => null, 'url' => 'https://www.cm-nazare.pt', 'type' => 'institutional'],
                    ['name' => 'Turismo de Portugal', 'logo' => null, 'url' => 'https://www.turismodeportugal.pt', 'type' => 'institutional'],
                ],
                'featured' => true,
            ],
            [
                'title' => [
                    'pt' => 'Workshop de Segurança em Ondas Grandes',
                    'en' => 'Big Wave Safety Workshop',
                ],
                'slug' => 'workshop-seguranca-ondas-grandes-2026',
                'description' => [
                    'pt' => '<p>Workshop intensivo de dois dias focado em técnicas de segurança para surf de ondas grandes. O programa inclui treino de apneia, resgate com jet ski, primeiros socorros aquáticos e gestão de riscos.</p><p>Ministrado por profissionais experientes, este workshop é essencial para quem pretende surfar ondas de grande dimensão em segurança.</p>',
                    'en' => '<p>Intensive two-day workshop focused on safety techniques for big wave surfing. The program includes breath-hold training, jet ski rescue, aquatic first aid, and risk management.</p><p>Taught by experienced professionals, this workshop is essential for anyone looking to surf large waves safely.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Workshop intensivo de segurança com treino de apneia, resgate com jet ski e primeiros socorros aquáticos.',
                    'en' => 'Intensive safety workshop with breath-hold training, jet ski rescue and aquatic first aid.',
                ],
                'start_date' => now()->addWeeks(3),
                'end_date' => now()->addWeeks(3)->addDays(1),
                'location' => 'Carsurf - Centro de Alto Rendimento',
                'entity' => 'carsurf',
                'category' => 'Formação',
                'image_url' => $images[1],
                'gallery_urls' => [$galleryImages[4], $galleryImages[5]],
                'ticket_url' => null,
                'video_url' => null,
                'schedule' => [
                    'pt' => '<ul><li><strong>Dia 1 — Manhã:</strong> Teoria de segurança e gestão de riscos</li><li><strong>Dia 1 — Tarde:</strong> Treino de apneia em piscina</li><li><strong>Dia 2 — Manhã:</strong> Resgate com jet ski (prática no mar)</li><li><strong>Dia 2 — Tarde:</strong> Primeiros socorros aquáticos e avaliação</li></ul>',
                    'en' => '<ul><li><strong>Day 1 — Morning:</strong> Safety theory and risk management</li><li><strong>Day 1 — Afternoon:</strong> Pool breath-hold training</li><li><strong>Day 2 — Morning:</strong> Jet ski rescue (ocean practice)</li><li><strong>Day 2 — Afternoon:</strong> Aquatic first aid and evaluation</li></ul>',
                ],
                'partners' => null,
                'featured' => true,
            ],
            [
                'title' => [
                    'pt' => 'Programa Jovens Surfistas - Inscrições Abertas',
                    'en' => 'Young Surfers Program - Open Registrations',
                ],
                'slug' => 'programa-jovens-surfistas-2026',
                'description' => [
                    'pt' => '<p>O Carsurf abre inscrições para o programa de formação de jovens surfistas. O programa destina-se a jovens entre os 12 e os 18 anos que pretendam desenvolver as suas competências no surf.</p><p>As sessões decorrem aos fins de semana e incluem treino técnico, preparação física e educação ambiental.</p>',
                    'en' => '<p>Carsurf opens registrations for the young surfers training program. The program is aimed at young people between 12 and 18 years old who wish to develop their surfing skills.</p><p>Sessions take place on weekends and include technical training, physical preparation, and environmental education.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Programa de formação para jovens entre 12 e 18 anos com treino técnico, preparação física e educação ambiental.',
                    'en' => 'Training program for young people aged 12-18 with technical training, physical preparation and environmental education.',
                ],
                'start_date' => now()->addWeeks(5),
                'end_date' => now()->addMonths(4),
                'location' => 'Carsurf - Centro de Alto Rendimento',
                'entity' => 'carsurf',
                'category' => 'Formação',
                'image_url' => $images[3],
                'gallery_urls' => null,
                'ticket_url' => null,
                'video_url' => null,
                'schedule' => null,
                'partners' => null,
                'featured' => false,
            ],
            [
                'title' => [
                    'pt' => 'Conferência Internacional de Turismo de Surf',
                    'en' => 'International Surf Tourism Conference',
                ],
                'slug' => 'conferencia-turismo-surf-2026',
                'description' => [
                    'pt' => '<p>A Nazaré Qualifica organiza a primeira Conferência Internacional de Turismo de Surf, reunindo especialistas de todo o mundo para discutir o futuro do turismo ligado ao surf.</p><p>Temas como sustentabilidade, impacto económico local, gestão de destinos de surf e tendências do mercado serão abordados ao longo de dois dias de conferência.</p>',
                    'en' => '<p>Nazaré Qualifica organizes the first International Surf Tourism Conference, bringing together experts from around the world to discuss the future of surf-related tourism.</p><p>Topics such as sustainability, local economic impact, surf destination management, and market trends will be addressed over two days of conference.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Especialistas de todo o mundo discutem sustentabilidade, impacto económico e o futuro do turismo de surf.',
                    'en' => 'Experts from around the world discuss sustainability, economic impact and the future of surf tourism.',
                ],
                'start_date' => now()->addMonths(2)->startOfMonth()->addDays(14),
                'end_date' => now()->addMonths(2)->startOfMonth()->addDays(15),
                'location' => 'Centro Cultural da Nazaré',
                'entity' => 'nazare-qualifica',
                'category' => 'Conferência',
                'image_url' => $images[4],
                'gallery_urls' => null,
                'ticket_url' => 'https://nazarequalifica.pt/conferencia',
                'video_url' => null,
                'schedule' => [
                    'pt' => '<ul><li><strong>Dia 1:</strong> Painéis sobre sustentabilidade e impacto económico local</li><li><strong>Dia 2:</strong> Workshops práticos e networking</li></ul>',
                    'en' => '<ul><li><strong>Day 1:</strong> Panels on sustainability and local economic impact</li><li><strong>Day 2:</strong> Practical workshops and networking</li></ul>',
                ],
                'partners' => [
                    ['name' => 'Turismo de Portugal', 'logo' => null, 'url' => 'https://www.turismodeportugal.pt', 'type' => 'premium'],
                    ['name' => 'UNWTO', 'logo' => null, 'url' => 'https://www.unwto.org', 'type' => 'institutional'],
                ],
                'featured' => true,
            ],
            [
                'title' => [
                    'pt' => 'Limpeza de Praia - Voluntariado Ambiental',
                    'en' => 'Beach Cleanup - Environmental Volunteering',
                ],
                'slug' => 'limpeza-praia-voluntariado-2026',
                'description' => [
                    'pt' => '<p>Junte-se a nós numa ação de limpeza da Praia do Norte. Esta iniciativa faz parte do compromisso da Nazaré Qualifica com a preservação ambiental e a sustentabilidade.</p><p>Todos os participantes receberão um kit de limpeza e um certificado de participação. Traga a família e amigos!</p>',
                    'en' => '<p>Join us for a cleanup action at Praia do Norte. This initiative is part of Nazaré Qualifica\'s commitment to environmental preservation and sustainability.</p><p>All participants will receive a cleanup kit and a certificate of participation. Bring family and friends!</p>',
                ],
                'excerpt' => [
                    'pt' => 'Ação de voluntariado ambiental na Praia do Norte. Traga a família e amigos!',
                    'en' => 'Environmental volunteering action at Praia do Norte. Bring family and friends!',
                ],
                'start_date' => now()->addWeeks(1)->next('Saturday'),
                'end_date' => null,
                'location' => 'Praia do Norte, Nazaré',
                'entity' => 'nazare-qualifica',
                'category' => 'Ambiental',
                'image_url' => $images[5],
                'gallery_urls' => null,
                'ticket_url' => null,
                'video_url' => null,
                'schedule' => null,
                'partners' => null,
                'featured' => false,
            ],
            [
                'title' => [
                    'pt' => 'Nazaré Big Wave Awards 2026',
                    'en' => 'Nazaré Big Wave Awards 2026',
                ],
                'slug' => 'nazare-big-wave-awards-2026',
                'description' => [
                    'pt' => '<p>A cerimónia anual de entrega de prémios que reconhece as melhores performances nas ondas gigantes da Nazaré. Categorias incluem Maior Onda, Melhor Tubo, Melhor Wipeout e Performance do Ano.</p><p>O evento inclui uma gala de jantar, exposição fotográfica e exibição dos melhores vídeos da temporada.</p>',
                    'en' => '<p>The annual awards ceremony recognizing the best performances in Nazaré\'s giant waves. Categories include Biggest Wave, Best Barrel, Best Wipeout, and Performance of the Year.</p><p>The event includes a dinner gala, photography exhibition, and screening of the season\'s best videos.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Gala de prémios com categorias de Maior Onda, Melhor Tubo e Performance do Ano.',
                    'en' => 'Awards gala with categories for Biggest Wave, Best Barrel and Performance of the Year.',
                ],
                'start_date' => now()->addMonths(3),
                'end_date' => null,
                'location' => 'Hotel Miramar, Nazaré',
                'entity' => 'praia-norte',
                'category' => 'Surf',
                'image_url' => $images[6],
                'gallery_urls' => null,
                'ticket_url' => 'https://praiadonorte.pt/awards',
                'video_url' => null,
                'schedule' => null,
                'partners' => null,
                'featured' => false,
            ],

            // ===== PAST EVENTS =====
            [
                'title' => [
                    'pt' => 'Nazaré Tow Surfing Challenge 2025',
                    'en' => 'Nazaré Tow Surfing Challenge 2025',
                ],
                'slug' => 'nazare-tow-surfing-challenge-2025',
                'description' => [
                    'pt' => '<p>A edição 2025 do Nazaré Tow Surfing Challenge foi um sucesso absoluto. Ondas de mais de 20 metros foram surfadas durante os dois dias de competição.</p><p>O brasileiro Lucas Chumbo levou para casa o troféu principal, enquanto a portuguesa Maya Gabeira impressionou com uma onda de 18 metros.</p>',
                    'en' => '<p>The 2025 edition of the Nazaré Tow Surfing Challenge was an absolute success. Waves over 20 meters were surfed during the two days of competition.</p><p>Brazilian Lucas Chumbo took home the main trophy, while Portuguese Maya Gabeira impressed with an 18-meter wave.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Ondas de mais de 20 metros surfadas na edição 2025. Lucas Chumbo levou o troféu principal.',
                    'en' => 'Waves over 20 meters surfed in the 2025 edition. Lucas Chumbo took the main trophy.',
                ],
                'start_date' => now()->subMonths(2)->startOfMonth()->addDays(4),
                'end_date' => now()->subMonths(2)->startOfMonth()->addDays(5),
                'location' => 'Praia do Norte, Nazaré',
                'entity' => 'praia-norte',
                'category' => 'Surf',
                'image_url' => $images[7],
                'gallery_urls' => [$galleryImages[6], $galleryImages[7], $galleryImages[0], $galleryImages[1]],
                'ticket_url' => null,
                'video_url' => 'https://www.youtube.com/embed/GJc4Ir78KRc',
                'schedule' => null,
                'partners' => [
                    ['name' => 'WSL', 'logo' => null, 'url' => 'https://www.worldsurfleague.com', 'type' => 'premium'],
                    ['name' => 'Câmara Municipal da Nazaré', 'logo' => null, 'url' => 'https://www.cm-nazare.pt', 'type' => 'institutional'],
                ],
                'featured' => false,
            ],
            [
                'title' => [
                    'pt' => 'Workshop de Fotografia de Ondas Gigantes',
                    'en' => 'Giant Wave Photography Workshop',
                ],
                'slug' => 'workshop-fotografia-ondas-gigantes',
                'description' => [
                    'pt' => '<p>Workshop de dois dias dedicado à fotografia de ondas gigantes, ministrado por fotógrafos profissionais reconhecidos internacionalmente.</p><p>Os participantes aprenderam técnicas de composição, configurações de câmara para capturar movimento, e dicas para fotografar em condições extremas.</p>',
                    'en' => '<p>Two-day workshop dedicated to giant wave photography, taught by internationally recognized professional photographers.</p><p>Participants learned composition techniques, camera settings for capturing motion, and tips for photographing in extreme conditions.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Técnicas de composição e configurações de câmara para fotografar ondas gigantes.',
                    'en' => 'Composition techniques and camera settings for photographing giant waves.',
                ],
                'start_date' => now()->subWeeks(6),
                'end_date' => now()->subWeeks(6)->addDay(),
                'location' => 'Forte de São Miguel Arcanjo',
                'entity' => 'praia-norte',
                'category' => 'Formação',
                'image_url' => $images[9],
                'gallery_urls' => null,
                'ticket_url' => null,
                'video_url' => null,
                'schedule' => null,
                'partners' => null,
                'featured' => false,
            ],
            [
                'title' => [
                    'pt' => 'Treino Intensivo de Apneia para Surfistas',
                    'en' => 'Intensive Breath-Hold Training for Surfers',
                ],
                'slug' => 'treino-apneia-surfistas-2025',
                'description' => [
                    'pt' => '<p>Programa de treino de apneia especificamente desenhado para surfistas de ondas grandes. O programa incluiu sessões em piscina e no mar, com foco em técnicas de relaxamento e gestão de stress debaixo de água.</p><p>Ministrado por instrutores certificados em apneia competitiva e resgate aquático.</p>',
                    'en' => '<p>Breath-hold training program specifically designed for big wave surfers. The program included pool and ocean sessions, focusing on relaxation techniques and underwater stress management.</p><p>Taught by instructors certified in competitive freediving and aquatic rescue.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Treino de apneia em piscina e mar para surfistas de ondas grandes.',
                    'en' => 'Pool and ocean breath-hold training for big wave surfers.',
                ],
                'start_date' => now()->subMonths(1),
                'end_date' => now()->subMonths(1)->addDays(2),
                'location' => 'Carsurf - Centro de Alto Rendimento',
                'entity' => 'carsurf',
                'category' => 'Formação',
                'image_url' => $images[8],
                'gallery_urls' => null,
                'ticket_url' => null,
                'video_url' => null,
                'schedule' => null,
                'partners' => null,
                'featured' => false,
            ],
            [
                'title' => [
                    'pt' => 'Reunião Pública: Plano de Mobilidade da Nazaré',
                    'en' => 'Public Meeting: Nazaré Mobility Plan',
                ],
                'slug' => 'reuniao-plano-mobilidade-nazare',
                'description' => [
                    'pt' => '<p>Sessão de apresentação e discussão pública do novo Plano de Mobilidade da Nazaré. O documento propõe melhorias no estacionamento, transportes públicos e acessibilidade pedonal.</p><p>Foram recolhidas sugestões dos residentes para incorporação no plano final.</p>',
                    'en' => '<p>Presentation and public discussion session of the new Nazaré Mobility Plan. The document proposes improvements in parking, public transport, and pedestrian accessibility.</p><p>Suggestions from residents were collected for incorporation into the final plan.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Discussão pública sobre melhorias no estacionamento e transportes públicos da Nazaré.',
                    'en' => 'Public discussion on parking and public transport improvements in Nazaré.',
                ],
                'start_date' => now()->subWeeks(3),
                'end_date' => null,
                'location' => 'Auditório Municipal da Nazaré',
                'entity' => 'nazare-qualifica',
                'category' => 'Institucional',
                'image_url' => $images[11],
                'gallery_urls' => null,
                'ticket_url' => null,
                'video_url' => null,
                'schedule' => null,
                'partners' => null,
                'featured' => false,
            ],
            [
                'title' => [
                    'pt' => 'Exposição: A História das Ondas Gigantes da Nazaré',
                    'en' => 'Exhibition: The History of Nazaré Giant Waves',
                ],
                'slug' => 'exposicao-historia-ondas-gigantes-2025',
                'description' => [
                    'pt' => '<p>Uma exposição única que conta a história das ondas gigantes da Nazaré, desde as primeiras sessões de surf até aos recordes mundiais. A exposição inclui fotografias históricas, equipamento utilizado pelos pioneiros e documentários exclusivos.</p><p>Visite o Forte de São Miguel Arcanjo e descubra como a Nazaré se tornou a capital mundial das ondas gigantes.</p>',
                    'en' => '<p>A unique exhibition telling the story of Nazaré\'s giant waves, from the first surf sessions to world records. The exhibition includes historical photographs, equipment used by pioneers, and exclusive documentaries.</p><p>Visit Fort São Miguel Arcanjo and discover how Nazaré became the world capital of giant waves.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Fotografias históricas, equipamento dos pioneiros e documentários no Forte de São Miguel Arcanjo.',
                    'en' => 'Historical photographs, pioneer equipment and documentaries at Fort São Miguel Arcanjo.',
                ],
                'start_date' => now()->subMonths(4),
                'end_date' => now()->subWeeks(2),
                'location' => 'Forte de São Miguel Arcanjo',
                'entity' => 'praia-norte',
                'category' => 'Cultura',
                'image_url' => $images[2],
                'gallery_urls' => [$galleryImages[2], $galleryImages[3]],
                'ticket_url' => null,
                'video_url' => null,
                'schedule' => null,
                'partners' => null,
                'featured' => false,
            ],
            [
                'title' => [
                    'pt' => 'Festival de Surf Feminino da Nazaré',
                    'en' => 'Nazaré Women\'s Surf Festival',
                ],
                'slug' => 'festival-surf-feminino-nazare-2025',
                'description' => [
                    'pt' => '<p>O primeiro Festival de Surf Feminino da Nazaré reuniu mais de 50 atletas de 15 países. O evento celebrou a presença crescente das mulheres no surf de ondas grandes.</p><p>Além da competição, o festival incluiu palestras, workshops e uma exposição fotográfica dedicada às pioneiras do surf feminino.</p>',
                    'en' => '<p>The first Nazaré Women\'s Surf Festival brought together more than 50 athletes from 15 countries. The event celebrated the growing presence of women in big wave surfing.</p><p>In addition to the competition, the festival included talks, workshops, and a photography exhibition dedicated to pioneers of women\'s surfing.</p>',
                ],
                'excerpt' => [
                    'pt' => 'Mais de 50 atletas de 15 países celebraram o surf feminino com competição, palestras e exposição.',
                    'en' => 'Over 50 athletes from 15 countries celebrated women\'s surfing with competition, talks and exhibition.',
                ],
                'start_date' => now()->subMonths(3),
                'end_date' => now()->subMonths(3)->addDays(2),
                'location' => 'Praia do Norte, Nazaré',
                'entity' => 'praia-norte',
                'category' => 'Surf',
                'image_url' => $images[10],
                'gallery_urls' => [$galleryImages[4], $galleryImages[5], $galleryImages[6]],
                'ticket_url' => null,
                'video_url' => 'https://www.youtube.com/embed/74pnrYPozcU',
                'schedule' => null,
                'partners' => null,
                'featured' => false,
            ],
        ];

        foreach ($eventos as $evento) {
            // Download the main image
            $imageUrl = $evento['image_url'] ?? null;
            unset($evento['image_url']);

            // Download gallery images
            $galleryUrls = $evento['gallery_urls'] ?? null;
            unset($evento['gallery_urls']);

            $image = null;
            if ($imageUrl) {
                $filename = "evento-{$evento['slug']}.jpg";
                $image = $this->downloadImage($imageUrl, 'eventos', $filename);
            }

            $gallery = null;
            if ($galleryUrls) {
                $gallery = [];
                foreach ($galleryUrls as $i => $galleryUrl) {
                    $galleryFilename = "evento-{$evento['slug']}-gallery-{$i}.jpg";
                    $galleryPath = $this->downloadImage($galleryUrl, 'eventos/gallery', $galleryFilename);
                    if ($galleryPath) {
                        $gallery[] = $galleryPath;
                    }
                }
                if (empty($gallery)) {
                    $gallery = null;
                }
            }

            $evento['image'] = $image;
            $evento['gallery'] = $gallery;
            Evento::create($evento);
        }
    }
}
