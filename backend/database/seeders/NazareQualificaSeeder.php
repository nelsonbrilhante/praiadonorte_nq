<?php

namespace Database\Seeders;

use App\Models\Pagina;
use Illuminate\Database\Seeder;

class NazareQualificaSeeder extends Seeder
{
    /**
     * Seed all Nazaré Qualifica pages with CMS-managed content.
     */
    public function run(): void
    {
        $paginas = [
            // ===== SOBRE (About NQ) =====
            [
                'title' => [
                    'pt' => 'Sobre a Nazaré Qualifica',
                    'en' => 'About Nazaré Qualifica',
                ],
                'slug' => 'sobre',
                'content' => [
                    'pt' => [
                        'intro' => [
                            'title' => 'Sobre a Empresa',
                            'text' => 'A Nazaré Qualifica, EM é a empresa municipal do Município da Nazaré que gere um conjunto de infraestruturas municipais, com o objetivo de contribuir para a qualificação do território e atratividade do concelho.',
                        ],
                        'objectives' => [
                            [
                                'title' => 'Gestão de Equipamentos Desportivos',
                                'description' => 'Gerir e desenvolver o Centro de Alto Rendimento de Surf (CARSURF) e equipamentos desportivos municipais.',
                                'icon' => 'waves',
                            ],
                            [
                                'title' => 'Exploração do Parque de Estacionamento',
                                'description' => 'Gerir o Parque de Estacionamento do Sítio da Nazaré, garantindo acessibilidade e serviço de qualidade.',
                                'icon' => 'car',
                            ],
                            [
                                'title' => 'Gestão do Forte de São Miguel Arcanjo',
                                'description' => 'Dinamizar e preservar o Forte de São Miguel Arcanjo, o ponto privilegiado de observação das ondas gigantes.',
                                'icon' => 'landmark',
                            ],
                            [
                                'title' => 'Gestão da ALE de Valado dos Frades',
                                'description' => 'Gerir e desenvolver a Área de Localização Empresarial (ALE) de Valado dos Frades.',
                                'icon' => 'factory',
                            ],
                            [
                                'title' => 'Promoção do Desenvolvimento Local',
                                'description' => 'Contribuir para a dinamização económica e turística do concelho da Nazaré.',
                                'icon' => 'target',
                            ],
                        ],
                        'cta' => [
                            'title' => 'Conheça os Nossos Serviços',
                            'subtitle' => 'Descubra todas as infraestruturas geridas pela Nazaré Qualifica',
                        ],
                    ],
                    'en' => [
                        'intro' => [
                            'title' => 'About the Company',
                            'text' => 'Nazaré Qualifica, EM is the municipal company of Nazaré Municipality that manages a set of municipal infrastructures, with the aim of contributing to the qualification of the territory and attractiveness of the county.',
                        ],
                        'objectives' => [
                            [
                                'title' => 'Sports Equipment Management',
                                'description' => 'Manage and develop the High Performance Surf Center (CARSURF) and municipal sports facilities.',
                                'icon' => 'waves',
                            ],
                            [
                                'title' => 'Parking Lot Operation',
                                'description' => 'Manage the Sítio da Nazaré Parking Lot, ensuring accessibility and quality service.',
                                'icon' => 'car',
                            ],
                            [
                                'title' => 'Fort Management',
                                'description' => 'Promote and preserve the Fort of São Miguel Arcanjo, the privileged viewpoint for giant waves.',
                                'icon' => 'landmark',
                            ],
                            [
                                'title' => 'Business Area Management',
                                'description' => 'Manage and develop the Business Location Area (ALE) of Valado dos Frades.',
                                'icon' => 'factory',
                            ],
                            [
                                'title' => 'Local Development Promotion',
                                'description' => 'Contribute to the economic and tourist development of the Nazaré municipality.',
                                'icon' => 'target',
                            ],
                        ],
                        'cta' => [
                            'title' => 'Discover Our Services',
                            'subtitle' => 'Explore all infrastructures managed by Nazaré Qualifica',
                        ],
                    ],
                ],
                'entity' => 'nazare-qualifica',
                'published' => true,
                'seo_title' => [
                    'pt' => 'Nazaré Qualifica - Empresa Municipal da Nazaré',
                    'en' => 'Nazaré Qualifica - Nazaré Municipal Company',
                ],
                'seo_description' => [
                    'pt' => 'Conheça a Nazaré Qualifica, empresa municipal que gere o CARSURF, Forte de São Miguel Arcanjo e outras infraestruturas.',
                    'en' => 'Discover Nazaré Qualifica, municipal company managing CARSURF, Fort of São Miguel Arcanjo and other infrastructures.',
                ],
            ],

            // ===== EQUIPA (Team/Corpos Sociais) =====
            [
                'title' => [
                    'pt' => 'Corpos Sociais',
                    'en' => 'Governance',
                ],
                'slug' => 'equipa',
                'content' => [
                    'pt' => [
                        'conselho' => [
                            ['name' => 'Álvaro Festas', 'role' => 'Presidente'],
                            ['name' => 'Marco Carreira', 'role' => '1º Vogal'],
                            ['name' => 'Fátima Lourenço', 'role' => '2º Vogal'],
                        ],
                        'assembleia' => [
                            'name' => 'Joaquim Paulo',
                            'role' => 'Presidente',
                        ],
                        'fiscal' => [
                            'company' => 'Mazars',
                            'representative' => 'Henrique Oliveira',
                        ],
                    ],
                    'en' => [
                        'conselho' => [
                            ['name' => 'Álvaro Festas', 'role' => 'President'],
                            ['name' => 'Marco Carreira', 'role' => '1st Member'],
                            ['name' => 'Fátima Lourenço', 'role' => '2nd Member'],
                        ],
                        'assembleia' => [
                            'name' => 'Joaquim Paulo',
                            'role' => 'President',
                        ],
                        'fiscal' => [
                            'company' => 'Mazars',
                            'representative' => 'Henrique Oliveira',
                        ],
                    ],
                ],
                'entity' => 'nazare-qualifica',
                'published' => true,
                'seo_title' => [
                    'pt' => 'Corpos Sociais - Nazaré Qualifica',
                    'en' => 'Governance - Nazaré Qualifica',
                ],
                'seo_description' => [
                    'pt' => 'Conheça os órgãos de gestão da Nazaré Qualifica: Conselho de Gerência, Assembleia Geral e Fiscal Único.',
                    'en' => 'Meet the governance bodies of Nazaré Qualifica: Board of Directors, General Assembly and Sole Auditor.',
                ],
            ],

            // ===== SERVIÇOS (Services List) =====
            [
                'title' => [
                    'pt' => 'Serviços',
                    'en' => 'Services',
                ],
                'slug' => 'servicos',
                'content' => [
                    'pt' => [
                        'services' => [
                            [
                                'slug' => 'carsurf',
                                'title' => 'Carsurf',
                                'shortDescription' => 'Centro de Alto Rendimento de Surf',
                                'icon' => 'waves',
                                'color' => 'ocean',
                            ],
                            [
                                'slug' => 'estacionamento',
                                'title' => 'Estacionamento do Sítio',
                                'shortDescription' => 'Parque de estacionamento junto ao Forte',
                                'icon' => 'car',
                                'color' => 'blue',
                            ],
                            [
                                'slug' => 'forte',
                                'title' => 'Forte de São Miguel Arcanjo',
                                'shortDescription' => 'Monumento histórico e miradouro das ondas gigantes',
                                'icon' => 'landmark',
                                'color' => 'amber',
                            ],
                            [
                                'slug' => 'ale',
                                'title' => 'ALE de Valado dos Frades',
                                'shortDescription' => 'Área de Localização Empresarial',
                                'icon' => 'factory',
                                'color' => 'green',
                            ],
                        ],
                    ],
                    'en' => [
                        'services' => [
                            [
                                'slug' => 'carsurf',
                                'title' => 'Carsurf',
                                'shortDescription' => 'High Performance Surf Center',
                                'icon' => 'waves',
                                'color' => 'ocean',
                            ],
                            [
                                'slug' => 'estacionamento',
                                'title' => 'Sítio Parking',
                                'shortDescription' => 'Parking lot next to the Fort',
                                'icon' => 'car',
                                'color' => 'blue',
                            ],
                            [
                                'slug' => 'forte',
                                'title' => 'Fort of São Miguel Arcanjo',
                                'shortDescription' => 'Historic monument and giant waves viewpoint',
                                'icon' => 'landmark',
                                'color' => 'amber',
                            ],
                            [
                                'slug' => 'ale',
                                'title' => 'Valado dos Frades ALE',
                                'shortDescription' => 'Business Location Area',
                                'icon' => 'factory',
                                'color' => 'green',
                            ],
                        ],
                    ],
                ],
                'entity' => 'nazare-qualifica',
                'published' => true,
                'seo_title' => [
                    'pt' => 'Serviços - Nazaré Qualifica',
                    'en' => 'Services - Nazaré Qualifica',
                ],
                'seo_description' => [
                    'pt' => 'Descubra os serviços da Nazaré Qualifica: CARSURF, Estacionamento do Sítio, Forte de São Miguel Arcanjo e ALE.',
                    'en' => 'Discover Nazaré Qualifica services: CARSURF, Sítio Parking, Fort of São Miguel Arcanjo and ALE.',
                ],
            ],

            // ===== CARSURF (Service Detail) =====
            [
                'title' => [
                    'pt' => 'Carsurf',
                    'en' => 'Carsurf',
                ],
                'slug' => 'carsurf',
                'content' => [
                    'pt' => [
                        'description' => 'O Centro de Alto Rendimento de Surf da Nazaré (CARSURF) é uma infraestrutura de apoio ao surf de alto rendimento, oferecendo condições ideais para estágios, treinos e concentrações de atletas.',
                        'features' => [
                            'Capacidade para 30 pessoas',
                            'Ginásio de alta performance',
                            'Sala de análise de vídeo',
                            'Acesso a infraestruturas municipais',
                            'Serviço de fisioterapia',
                            'Espaço de armazenamento de material',
                        ],
                        'stats' => [],
                        'contact' => [
                            'phone' => '+351 938 013 603',
                            'email' => 'geral@carsurf.nazare.pt',
                        ],
                    ],
                    'en' => [
                        'description' => 'The Nazaré High Performance Surf Center (CARSURF) is a facility supporting high-performance surfing, offering ideal conditions for training camps, workouts and athlete concentrations.',
                        'features' => [
                            'Capacity for 30 people',
                            'High performance gym',
                            'Video analysis room',
                            'Access to municipal facilities',
                            'Physiotherapy service',
                            'Equipment storage space',
                        ],
                        'stats' => [],
                        'contact' => [
                            'phone' => '+351 938 013 603',
                            'email' => 'geral@carsurf.nazare.pt',
                        ],
                    ],
                ],
                'entity' => 'nazare-qualifica',
                'published' => true,
                'seo_title' => [
                    'pt' => 'CARSURF - Centro de Alto Rendimento de Surf',
                    'en' => 'CARSURF - High Performance Surf Center',
                ],
                'seo_description' => [
                    'pt' => 'O CARSURF é o Centro de Alto Rendimento de Surf da Nazaré. Instalações de treino e alojamento para atletas.',
                    'en' => 'CARSURF is the Nazaré High Performance Surf Center. Training and accommodation facilities for athletes.',
                ],
            ],

            // ===== ESTACIONAMENTO (Service Detail) =====
            [
                'title' => [
                    'pt' => 'Estacionamento do Sítio',
                    'en' => 'Sítio Parking',
                ],
                'slug' => 'estacionamento',
                'content' => [
                    'pt' => [
                        'description' => 'O Parque de Estacionamento do Sítio da Nazaré situa-se junto ao Forte de São Miguel Arcanjo, oferecendo acesso privilegiado ao principal ponto de observação das ondas gigantes.',
                        'features' => [
                            'Localização privilegiada junto ao Forte',
                            'Capacidade para 500 veículos',
                            'Vigilância 24 horas',
                            'Acesso facilitado ao miradouro',
                            'Parque para autocarros',
                            'Casas de banho públicas',
                        ],
                        'stats' => [
                            ['value' => '500', 'label' => 'Lugares de estacionamento'],
                            ['value' => '24h', 'label' => 'Vigilância diária'],
                        ],
                        'contact' => [
                            'phone' => '+351 262 550 010',
                            'email' => 'geral@nazarequalifica.pt',
                        ],
                    ],
                    'en' => [
                        'description' => 'The Sítio da Nazaré Parking Lot is located next to the Fort of São Miguel Arcanjo, offering privileged access to the main viewpoint for giant waves.',
                        'features' => [
                            'Privileged location next to the Fort',
                            'Capacity for 500 vehicles',
                            '24-hour surveillance',
                            'Easy access to the viewpoint',
                            'Bus parking area',
                            'Public restrooms',
                        ],
                        'stats' => [
                            ['value' => '500', 'label' => 'Parking spaces'],
                            ['value' => '24h', 'label' => 'Daily surveillance'],
                        ],
                        'contact' => [
                            'phone' => '+351 262 550 010',
                            'email' => 'geral@nazarequalifica.pt',
                        ],
                    ],
                ],
                'entity' => 'nazare-qualifica',
                'published' => true,
                'seo_title' => [
                    'pt' => 'Estacionamento do Sítio - Nazaré',
                    'en' => 'Sítio Parking - Nazaré',
                ],
                'seo_description' => [
                    'pt' => 'Estacionamento junto ao Forte de São Miguel Arcanjo com acesso ao miradouro das ondas gigantes.',
                    'en' => 'Parking next to the Fort of São Miguel Arcanjo with access to the giant waves viewpoint.',
                ],
            ],

            // ===== FORTE (Service Detail) =====
            [
                'title' => [
                    'pt' => 'Forte de São Miguel Arcanjo',
                    'en' => 'Fort of São Miguel Arcanjo',
                ],
                'slug' => 'forte',
                'content' => [
                    'pt' => [
                        'description' => 'O Forte de São Miguel Arcanjo é um monumento histórico do século XVI que se tornou no ponto de observação mais icónico para as ondas gigantes da Nazaré. De lá, pode ver os surfistas a desafiarem as maiores ondas do mundo.',
                        'features' => [
                            'Miradouro privilegiado para as ondas gigantes',
                            'Exposição permanente sobre big wave surfing',
                            'Farol histórico ainda em funcionamento',
                            'Centro interpretativo',
                            'Loja de recordações',
                        ],
                        'stats' => [
                            ['value' => '1577', 'label' => 'Ano de construção'],
                            ['value' => '650k+', 'label' => 'Visitantes/ano'],
                            ['value' => 'XVI', 'label' => 'Século de história'],
                            ['value' => '#1', 'label' => 'Miradouro da Nazaré'],
                        ],
                        'contact' => [
                            'phone' => '+351 262 550 010',
                            'email' => 'geral@nazarequalifica.pt',
                        ],
                    ],
                    'en' => [
                        'description' => 'The Fort of São Miguel Arcanjo is a 16th-century historic monument that has become the most iconic viewpoint for the giant waves of Nazaré. From there, you can watch surfers challenge the biggest waves in the world.',
                        'features' => [
                            'Privileged viewpoint for giant waves',
                            'Permanent exhibition on big wave surfing',
                            'Historic lighthouse still in operation',
                            'Interpretive center',
                            'Souvenir shop',
                        ],
                        'stats' => [
                            ['value' => '1577', 'label' => 'Year of construction'],
                            ['value' => '650k+', 'label' => 'Visitors/year'],
                            ['value' => 'XVI', 'label' => 'Century of history'],
                            ['value' => '#1', 'label' => 'Nazaré viewpoint'],
                        ],
                        'contact' => [
                            'phone' => '+351 262 550 010',
                            'email' => 'geral@nazarequalifica.pt',
                        ],
                    ],
                ],
                'entity' => 'nazare-qualifica',
                'published' => true,
                'seo_title' => [
                    'pt' => 'Forte de São Miguel Arcanjo - Ondas Gigantes Nazaré',
                    'en' => 'Fort of São Miguel Arcanjo - Nazaré Giant Waves',
                ],
                'seo_description' => [
                    'pt' => 'Visite o Forte de São Miguel Arcanjo, o miradouro histórico das ondas gigantes da Nazaré.',
                    'en' => 'Visit the Fort of São Miguel Arcanjo, the historic viewpoint for Nazaré giant waves.',
                ],
            ],

            // ===== ALE (Service Detail) =====
            [
                'title' => [
                    'pt' => 'ALE de Valado dos Frades',
                    'en' => 'Valado dos Frades ALE',
                ],
                'slug' => 'ale',
                'content' => [
                    'pt' => [
                        'description' => 'A Área de Localização Empresarial (ALE) de Valado dos Frades é um espaço industrial gerido pela Nazaré Qualifica, oferecendo lotes para instalação de empresas em condições vantajosas.',
                        'features' => [
                            'Lotes com infraestruturas completas',
                            'Rede viária de acesso',
                            'Rede de saneamento',
                            'Rede elétrica',
                            'Proximidade a vias principais',
                            'Apoio à instalação de empresas',
                        ],
                        'stats' => [
                            ['value' => '30', 'label' => 'Hectares de área'],
                            ['value' => '34', 'label' => 'Lotes disponíveis'],
                        ],
                        'contact' => [
                            'phone' => '+351 262 550 010',
                            'email' => 'geral@nazarequalifica.pt',
                        ],
                    ],
                    'en' => [
                        'description' => 'The Business Location Area (ALE) of Valado dos Frades is an industrial space managed by Nazaré Qualifica, offering lots for company installation under advantageous conditions.',
                        'features' => [
                            'Lots with complete infrastructure',
                            'Road access network',
                            'Sewage network',
                            'Electrical network',
                            'Proximity to main roads',
                            'Company installation support',
                        ],
                        'stats' => [
                            ['value' => '30', 'label' => 'Hectares of area'],
                            ['value' => '34', 'label' => 'Available lots'],
                        ],
                        'contact' => [
                            'phone' => '+351 262 550 010',
                            'email' => 'geral@nazarequalifica.pt',
                        ],
                    ],
                ],
                'entity' => 'nazare-qualifica',
                'published' => true,
                'seo_title' => [
                    'pt' => 'ALE Valado dos Frades - Área de Localização Empresarial',
                    'en' => 'Valado dos Frades ALE - Business Location Area',
                ],
                'seo_description' => [
                    'pt' => 'ALE de Valado dos Frades - Área industrial com lotes disponíveis para instalação de empresas.',
                    'en' => 'Valado dos Frades ALE - Industrial area with available lots for company installation.',
                ],
            ],
        ];

        foreach ($paginas as $pagina) {
            Pagina::updateOrCreate(
                ['entity' => $pagina['entity'], 'slug' => $pagina['slug']],
                $pagina
            );
        }

        $this->command->info('Nazaré Qualifica pages seeded successfully!');
    }
}
