<?php

namespace Database\Seeders;

use App\Models\Evento;
use Illuminate\Database\Seeder;

class EventoSeeder extends Seeder
{
    public function run(): void
    {
        $eventos = [
            [
                'title' => [
                    'pt' => 'Nazaré Tow Surfing Challenge 2025',
                    'en' => 'Nazaré Tow Surfing Challenge 2025',
                ],
                'slug' => 'nazare-tow-surfing-challenge-2025',
                'description' => [
                    'pt' => '<p>O evento mais aguardado do ano no mundo do surf de ondas gigantes regressa à Nazaré. O Nazaré Tow Surfing Challenge faz parte do circuito Big Wave Tour da WSL e reúne os melhores surfistas do planeta.</p><p>Durante a janela do evento, os organizadores aguardam as condições ideais para dar o green light e iniciar a competição. Os atletas estarão em standby, prontos para entrar na água assim que as ondas atinjam o tamanho necessário.</p>',
                    'en' => '<p>The most anticipated event of the year in big wave surfing returns to Nazaré. The Nazaré Tow Surfing Challenge is part of the WSL Big Wave Tour and brings together the best surfers on the planet.</p><p>During the event window, organizers await ideal conditions to give the green light and start the competition. Athletes will be on standby, ready to enter the water as soon as waves reach the required size.</p>',
                ],
                'start_date' => now()->addMonths(2)->startOfMonth(),
                'end_date' => now()->addMonths(2)->endOfMonth(),
                'location' => 'Praia do Norte, Nazaré',
                'entity' => 'praia-norte',
                'image' => null,
                'ticket_url' => 'https://www.worldsurfleague.com',
                'featured' => true,
            ],
            [
                'title' => [
                    'pt' => 'Workshop de Segurança em Ondas Grandes',
                    'en' => 'Big Wave Safety Workshop',
                ],
                'slug' => 'workshop-seguranca-ondas-grandes',
                'description' => [
                    'pt' => '<p>Workshop intensivo de dois dias focado em técnicas de segurança para surf de ondas grandes. O programa inclui treino de apneia, resgate com jet ski, primeiros socorros aquáticos e gestão de riscos.</p><p>Ministrado por profissionais experientes, este workshop é essencial para quem pretende surfar ondas de grande dimensão em segurança.</p>',
                    'en' => '<p>Intensive two-day workshop focused on safety techniques for big wave surfing. The program includes breath-hold training, jet ski rescue, aquatic first aid, and risk management.</p><p>Taught by experienced professionals, this workshop is essential for anyone looking to surf large waves safely.</p>',
                ],
                'start_date' => now()->addWeeks(3),
                'end_date' => now()->addWeeks(3)->addDays(1),
                'location' => 'Carsurf - Centro de Alto Rendimento',
                'entity' => 'carsurf',
                'image' => null,
                'ticket_url' => null,
                'featured' => true,
            ],
            [
                'title' => [
                    'pt' => 'Exposição: A História das Ondas Gigantes da Nazaré',
                    'en' => 'Exhibition: The History of Nazaré Giant Waves',
                ],
                'slug' => 'exposicao-historia-ondas-gigantes',
                'description' => [
                    'pt' => '<p>Uma exposição única que conta a história das ondas gigantes da Nazaré, desde as primeiras sessões de surf até aos recordes mundiais. A exposição inclui fotografias históricas, equipamento utilizado pelos pioneiros e documentários exclusivos.</p><p>Visite o Forte de São Miguel Arcanjo e descubra como a Nazaré se tornou a capital mundial das ondas gigantes.</p>',
                    'en' => '<p>A unique exhibition telling the story of Nazaré\'s giant waves, from the first surf sessions to world records. The exhibition includes historical photographs, equipment used by pioneers, and exclusive documentaries.</p><p>Visit Fort São Miguel Arcanjo and discover how Nazaré became the world capital of giant waves.</p>',
                ],
                'start_date' => now()->subMonths(1),
                'end_date' => now()->addMonths(4),
                'location' => 'Forte de São Miguel Arcanjo',
                'entity' => 'praia-norte',
                'image' => null,
                'ticket_url' => null,
                'featured' => false,
            ],
            [
                'title' => [
                    'pt' => 'Programa Jovens Surfistas - Inscrições Abertas',
                    'en' => 'Young Surfers Program - Open Registrations',
                ],
                'slug' => 'programa-jovens-surfistas-2025',
                'description' => [
                    'pt' => '<p>O Carsurf abre inscrições para o programa de formação de jovens surfistas. O programa destina-se a jovens entre os 12 e os 18 anos que pretendam desenvolver as suas competências no surf.</p><p>As sessões decorrem aos fins de semana e incluem treino técnico, preparação física e educação ambiental.</p>',
                    'en' => '<p>Carsurf opens registrations for the young surfers training program. The program is aimed at young people between 12 and 18 years old who wish to develop their surfing skills.</p><p>Sessions take place on weekends and include technical training, physical preparation, and environmental education.</p>',
                ],
                'start_date' => now()->addWeeks(6),
                'end_date' => now()->addMonths(6),
                'location' => 'Carsurf - Centro de Alto Rendimento',
                'entity' => 'carsurf',
                'image' => null,
                'ticket_url' => null,
                'featured' => false,
            ],
            [
                'title' => [
                    'pt' => 'Conferência Internacional de Turismo de Surf',
                    'en' => 'International Surf Tourism Conference',
                ],
                'slug' => 'conferencia-turismo-surf-2025',
                'description' => [
                    'pt' => '<p>A Nazaré Qualifica organiza a primeira Conferência Internacional de Turismo de Surf, reunindo especialistas de todo o mundo para discutir o futuro do turismo ligado ao surf.</p><p>Temas como sustentabilidade, impacto económico local, gestão de destinos de surf e tendências do mercado serão abordados ao longo de dois dias de conferência.</p>',
                    'en' => '<p>Nazaré Qualifica organizes the first International Surf Tourism Conference, bringing together experts from around the world to discuss the future of surf-related tourism.</p><p>Topics such as sustainability, local economic impact, surf destination management, and market trends will be addressed over two days of conference.</p>',
                ],
                'start_date' => now()->addMonths(3)->startOfMonth()->addDays(14),
                'end_date' => now()->addMonths(3)->startOfMonth()->addDays(15),
                'location' => 'Centro Cultural da Nazaré',
                'entity' => 'nazare-qualifica',
                'image' => null,
                'ticket_url' => null,
                'featured' => true,
            ],
            [
                'title' => [
                    'pt' => 'Limpeza de Praia - Voluntariado Ambiental',
                    'en' => 'Beach Cleanup - Environmental Volunteering',
                ],
                'slug' => 'limpeza-praia-voluntariado',
                'description' => [
                    'pt' => '<p>Junte-se a nós numa ação de limpeza da Praia do Norte. Esta iniciativa faz parte do compromisso da Nazaré Qualifica com a preservação ambiental e a sustentabilidade.</p><p>Todos os participantes receberão um kit de limpeza e um certificado de participação. Traga a família e amigos!</p>',
                    'en' => '<p>Join us for a cleanup action at Praia do Norte. This initiative is part of Nazaré Qualifica\'s commitment to environmental preservation and sustainability.</p><p>All participants will receive a cleanup kit and a certificate of participation. Bring family and friends!</p>',
                ],
                'start_date' => now()->addWeeks(2)->next('Saturday'),
                'end_date' => null,
                'location' => 'Praia do Norte, Nazaré',
                'entity' => 'nazare-qualifica',
                'image' => null,
                'ticket_url' => null,
                'featured' => false,
            ],
        ];

        foreach ($eventos as $evento) {
            Evento::create($evento);
        }
    }
}
