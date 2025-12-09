<?php

namespace Database\Seeders;

use App\Models\Pagina;
use Illuminate\Database\Seeder;

class PaginaSeeder extends Seeder
{
    public function run(): void
    {
        $paginas = [
            // Praia do Norte
            [
                'title' => [
                    'pt' => 'Sobre a Praia do Norte',
                    'en' => 'About Praia do Norte',
                ],
                'slug' => 'sobre',
                'content' => [
                    'pt' => '<h2>O Berço das Ondas Gigantes</h2><p>A Praia do Norte, situada na vila piscatória da Nazaré, é mundialmente reconhecida pelas suas ondas gigantes. Este fenómeno natural único é causado pelo Canhão da Nazaré, uma formação submarina que amplifica as ondulações do Atlântico Norte.</p><h2>História</h2><p>Embora a Nazaré seja conhecida há séculos pela sua tradição piscatória, foi apenas em 2011 que o surfista havaiano Garrett McNamara colocou a Praia do Norte no mapa mundial do surf ao surfar uma onda de 23,77 metros.</p><p>Desde então, a Praia do Norte tornou-se um destino obrigatório para os surfistas de ondas gigantes e para milhares de turistas que querem assistir a este espetáculo da natureza.</p><h2>O Canhão da Nazaré</h2><p>O Canhão da Nazaré é um vale submarino com cerca de 230 km de comprimento e 5 km de profundidade. Esta formação geológica única canaliza a energia das ondulações atlânticas, criando as condições perfeitas para a formação de ondas gigantes.</p>',
                    'en' => '<h2>The Cradle of Giant Waves</h2><p>Praia do Norte, located in the fishing village of Nazaré, is world-renowned for its giant waves. This unique natural phenomenon is caused by the Nazaré Canyon, an underwater formation that amplifies swells from the North Atlantic.</p><h2>History</h2><p>Although Nazaré has been known for centuries for its fishing tradition, it was only in 2011 that Hawaiian surfer Garrett McNamara put Praia do Norte on the world surfing map by riding a 23.77-meter wave.</p><p>Since then, Praia do Norte has become a must-visit destination for big wave surfers and thousands of tourists who want to witness this spectacle of nature.</p><h2>The Nazaré Canyon</h2><p>The Nazaré Canyon is an underwater valley about 230 km long and 5 km deep. This unique geological formation channels the energy of Atlantic swells, creating perfect conditions for giant wave formation.</p>',
                ],
                'entity' => 'praia-norte',
                'published' => true,
                'seo_title' => ['pt' => 'Sobre a Praia do Norte - Ondas Gigantes Nazaré', 'en' => 'About Praia do Norte - Nazaré Giant Waves'],
                'seo_description' => ['pt' => 'Conheça a história da Praia do Norte e do Canhão da Nazaré, berço das maiores ondas do mundo.', 'en' => 'Discover the history of Praia do Norte and the Nazaré Canyon, home of the world\'s largest waves.'],
            ],
            // Carsurf
            [
                'title' => [
                    'pt' => 'Sobre o Carsurf',
                    'en' => 'About Carsurf',
                ],
                'slug' => 'sobre',
                'content' => [
                    'pt' => '<h2>Centro de Alto Rendimento de Surf</h2><p>O Carsurf é o primeiro Centro de Alto Rendimento de Surf em Portugal, criado para apoiar o desenvolvimento de atletas de elite e a formação de novos talentos.</p><h2>Instalações</h2><p>O centro dispõe de instalações de última geração, incluindo:</p><ul><li>Ginásio especializado em treino funcional para surfistas</li><li>Sala de análise de vídeo e performance</li><li>Piscina para treino de apneia e técnicas aquáticas</li><li>Gabinete de fisioterapia e recuperação</li><li>Balneários e áreas de descanso</li></ul><h2>Programas</h2><p>Oferecemos programas adaptados a diferentes níveis e objetivos, desde iniciação ao surf até preparação para competições internacionais de ondas gigantes.</p>',
                    'en' => '<h2>High Performance Surf Center</h2><p>Carsurf is the first High Performance Surf Center in Portugal, created to support the development of elite athletes and the training of new talents.</p><h2>Facilities</h2><p>The center has state-of-the-art facilities, including:</p><ul><li>Gym specialized in functional training for surfers</li><li>Video and performance analysis room</li><li>Pool for breath-hold and aquatic technique training</li><li>Physiotherapy and recovery office</li><li>Locker rooms and rest areas</li></ul><h2>Programs</h2><p>We offer programs adapted to different levels and objectives, from surf initiation to preparation for international big wave competitions.</p>',
                ],
                'entity' => 'carsurf',
                'published' => true,
                'seo_title' => ['pt' => 'Carsurf - Centro de Alto Rendimento Surf', 'en' => 'Carsurf - High Performance Surf Center'],
                'seo_description' => ['pt' => 'Conheça o Carsurf, o primeiro Centro de Alto Rendimento de Surf em Portugal.', 'en' => 'Discover Carsurf, the first High Performance Surf Center in Portugal.'],
            ],
            [
                'title' => [
                    'pt' => 'Programas de Treino',
                    'en' => 'Training Programs',
                ],
                'slug' => 'programas',
                'content' => [
                    'pt' => '<h2>Programas Disponíveis</h2><h3>Programa de Iniciação</h3><p>Para quem quer dar os primeiros passos no surf. Inclui aulas práticas e teóricas sobre segurança, técnica básica e leitura de ondas.</p><h3>Programa de Evolução</h3><p>Para surfistas intermédios que querem melhorar a sua técnica e consistência. Análise de vídeo e treino físico específico.</p><h3>Programa de Alto Rendimento</h3><p>Para atletas de competição e surfistas avançados. Preparação física, mental e técnica ao mais alto nível.</p><h3>Programa Big Wave</h3><p>Preparação específica para surf de ondas grandes. Inclui treino de apneia, segurança aquática e sessões supervisionadas na Praia do Norte.</p>',
                    'en' => '<h2>Available Programs</h2><h3>Initiation Program</h3><p>For those who want to take their first steps in surfing. Includes practical and theoretical classes on safety, basic technique, and wave reading.</p><h3>Evolution Program</h3><p>For intermediate surfers who want to improve their technique and consistency. Video analysis and specific physical training.</p><h3>High Performance Program</h3><p>For competition athletes and advanced surfers. Physical, mental, and technical preparation at the highest level.</p><h3>Big Wave Program</h3><p>Specific preparation for big wave surfing. Includes breath-hold training, water safety, and supervised sessions at Praia do Norte.</p>',
                ],
                'entity' => 'carsurf',
                'published' => true,
                'seo_title' => ['pt' => 'Programas de Treino Carsurf', 'en' => 'Carsurf Training Programs'],
                'seo_description' => ['pt' => 'Descubra os programas de treino do Carsurf para todos os níveis.', 'en' => 'Discover Carsurf training programs for all levels.'],
            ],
            // Nazaré Qualifica
            [
                'title' => [
                    'pt' => 'Sobre a Nazaré Qualifica',
                    'en' => 'About Nazaré Qualifica',
                ],
                'slug' => 'sobre',
                'content' => [
                    'pt' => '<h2>Empresa Municipal</h2><p>A Nazaré Qualifica, EM é uma empresa municipal responsável pela gestão de infraestruturas e serviços no concelho da Nazaré. A empresa tem como missão promover o desenvolvimento sustentável da região através da gestão eficiente dos seus recursos.</p><h2>Áreas de Atuação</h2><ul><li><strong>Gestão de Infraestruturas Desportivas</strong> - Incluindo o Centro de Alto Rendimento Carsurf</li><li><strong>Promoção Turística</strong> - Valorização da Praia do Norte como destino de surf</li><li><strong>Eventos</strong> - Organização e apoio a eventos desportivos e culturais</li><li><strong>Sustentabilidade</strong> - Iniciativas de preservação ambiental</li></ul><h2>Contactos</h2><p>Estamos ao dispor para esclarecer qualquer dúvida ou pedido de informação.</p>',
                    'en' => '<h2>Municipal Company</h2><p>Nazaré Qualifica, EM is a municipal company responsible for managing infrastructure and services in the Nazaré municipality. The company\'s mission is to promote sustainable development of the region through efficient management of its resources.</p><h2>Areas of Activity</h2><ul><li><strong>Sports Infrastructure Management</strong> - Including the Carsurf High Performance Center</li><li><strong>Tourism Promotion</strong> - Enhancing Praia do Norte as a surf destination</li><li><strong>Events</strong> - Organization and support for sports and cultural events</li><li><strong>Sustainability</strong> - Environmental preservation initiatives</li></ul><h2>Contacts</h2><p>We are available to answer any questions or requests for information.</p>',
                ],
                'entity' => 'nazare-qualifica',
                'published' => true,
                'seo_title' => ['pt' => 'Nazaré Qualifica - Empresa Municipal', 'en' => 'Nazaré Qualifica - Municipal Company'],
                'seo_description' => ['pt' => 'Conheça a Nazaré Qualifica, empresa municipal de gestão de infraestruturas.', 'en' => 'Discover Nazaré Qualifica, municipal infrastructure management company.'],
            ],
            [
                'title' => [
                    'pt' => 'Serviços',
                    'en' => 'Services',
                ],
                'slug' => 'servicos',
                'content' => [
                    'pt' => '<h2>Nossos Serviços</h2><h3>Gestão de Espaços</h3><p>A Nazaré Qualifica gere diversos espaços públicos e equipamentos municipais, garantindo a sua manutenção e funcionamento adequado.</p><h3>Apoio a Eventos</h3><p>Prestamos apoio logístico e organizacional a eventos desportivos, culturais e turísticos que decorram no concelho da Nazaré.</p><h3>Informação Turística</h3><p>Disponibilizamos informação atualizada sobre atrações, eventos e serviços disponíveis na região.</p><h3>Aluguer de Espaços</h3><p>Alguns dos nossos espaços estão disponíveis para aluguer para eventos privados ou corporativos.</p>',
                    'en' => '<h2>Our Services</h2><h3>Space Management</h3><p>Nazaré Qualifica manages various public spaces and municipal facilities, ensuring their proper maintenance and operation.</p><h3>Event Support</h3><p>We provide logistical and organizational support for sports, cultural, and tourist events taking place in the Nazaré municipality.</p><h3>Tourist Information</h3><p>We provide up-to-date information about attractions, events, and services available in the region.</p><h3>Space Rental</h3><p>Some of our spaces are available for rental for private or corporate events.</p>',
                ],
                'entity' => 'nazare-qualifica',
                'published' => true,
                'seo_title' => ['pt' => 'Serviços Nazaré Qualifica', 'en' => 'Nazaré Qualifica Services'],
                'seo_description' => ['pt' => 'Descubra os serviços disponibilizados pela Nazaré Qualifica.', 'en' => 'Discover the services provided by Nazaré Qualifica.'],
            ],
        ];

        foreach ($paginas as $pagina) {
            Pagina::create($pagina);
        }
    }
}
