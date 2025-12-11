<?php

namespace Database\Seeders;

use App\Models\Surfer;
use Database\Seeders\Traits\DownloadsImages;
use Illuminate\Database\Seeder;

class SurferSeeder extends Seeder
{
    use DownloadsImages;

    public function run(): void
    {
        // Unsplash portraits for surfers (diverse athletes)
        $photos = [
            // Male surfer 1 (António)
            'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=600&h=800&fit=crop',
            // Female surfer (Maya)
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=600&h=800&fit=crop',
            // Male surfer 2 (Lucas)
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=800&fit=crop',
            // Male Asian (Kai)
            'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=600&h=800&fit=crop',
            // Female 2 (Sofia)
            'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=600&h=800&fit=crop',
            // Male Nordic (Erik)
            'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&h=800&fit=crop',
        ];

        $surfers = [
            [
                'name' => 'António Laureano',
                'slug' => 'antonio-laureano',
                'nationality' => 'Portugal',
                'bio' => [
                    'pt' => '<p>António Laureano é um dos surfistas portugueses mais respeitados nas ondas gigantes da Nazaré. Natural de Peniche, começou a surfar aos 8 anos e rapidamente se destacou pela sua coragem e técnica.</p><p>Em 2020, estabeleceu um novo recorde pessoal ao surfar uma onda de 20 metros na Praia do Norte, consolidando a sua posição entre os melhores big wave surfers do mundo.</p>',
                    'en' => '<p>António Laureano is one of the most respected Portuguese surfers in the giant waves of Nazaré. Born in Peniche, he started surfing at age 8 and quickly stood out for his courage and technique.</p><p>In 2020, he set a new personal record by surfing a 20-meter wave at Praia do Norte, consolidating his position among the best big wave surfers in the world.</p>',
                ],
                'photo_url' => $photos[0],
                'achievements' => [
                    ['pt' => 'Campeão Nacional de Ondas Grandes 2022', 'en' => 'National Big Wave Champion 2022'],
                    ['pt' => 'Top 10 WSL Big Wave Tour 2021', 'en' => 'Top 10 WSL Big Wave Tour 2021'],
                    ['pt' => 'Onda do Ano Nazaré 2020', 'en' => 'Wave of the Year Nazaré 2020'],
                ],
                'social_media' => [
                    'instagram' => '@antonio.laureano.surf',
                    'youtube' => 'AntonioLaureanoOfficial',
                ],
                'featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'Maya Richardson',
                'slug' => 'maya-richardson',
                'nationality' => 'Estados Unidos',
                'bio' => [
                    'pt' => '<p>Maya Richardson é uma força da natureza no mundo do surf feminino de ondas gigantes. Nascida no Havai, mudou-se para Portugal em 2018 para treinar exclusivamente na Nazaré.</p><p>É conhecida pela sua abordagem técnica e pela capacidade de ler as ondas com precisão milimétrica. Maya é também uma defensora ativa da sustentabilidade dos oceanos.</p>',
                    'en' => '<p>Maya Richardson is a force of nature in women\'s big wave surfing. Born in Hawaii, she moved to Portugal in 2018 to train exclusively in Nazaré.</p><p>She is known for her technical approach and ability to read waves with millimetric precision. Maya is also an active advocate for ocean sustainability.</p>',
                ],
                'photo_url' => $photos[1],
                'achievements' => [
                    ['pt' => 'Campeã Mundial Feminina de Big Wave 2023', 'en' => 'Women\'s Big Wave World Champion 2023'],
                    ['pt' => 'Maior onda surfada por uma mulher 2022', 'en' => 'Largest wave surfed by a woman 2022'],
                ],
                'social_media' => [
                    'instagram' => '@mayarichardson',
                    'twitter' => '@MayaRichSurf',
                ],
                'featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'Lucas Fonseca',
                'slug' => 'lucas-fonseca',
                'nationality' => 'Brasil',
                'bio' => [
                    'pt' => '<p>Lucas Fonseca trocou as ondas de Florianópolis pelas montanhas de água da Nazaré em 2015. Desde então, tornou-se um dos surfistas mais consistentes e respeitados do circuito de ondas gigantes.</p><p>A sua experiência em diferentes tipos de ondas dá-lhe uma versatilidade única que o distingue dos demais competidores.</p>',
                    'en' => '<p>Lucas Fonseca traded the waves of Florianópolis for the water mountains of Nazaré in 2015. Since then, he has become one of the most consistent and respected surfers on the big wave circuit.</p><p>His experience in different types of waves gives him a unique versatility that sets him apart from other competitors.</p>',
                ],
                'photo_url' => $photos[2],
                'achievements' => [
                    ['pt' => 'Vice-campeão WSL Big Wave 2023', 'en' => 'WSL Big Wave Runner-up 2023'],
                    ['pt' => '3º lugar Nazaré Tow Challenge 2022', 'en' => '3rd place Nazaré Tow Challenge 2022'],
                ],
                'social_media' => [
                    'instagram' => '@lucasfonsecasurf',
                ],
                'featured' => true,
                'order' => 3,
            ],
            [
                'name' => 'Kai Nakamura',
                'slug' => 'kai-nakamura',
                'nationality' => 'Japão',
                'bio' => [
                    'pt' => '<p>Kai Nakamura é o primeiro japonês a destacar-se consistentemente no circuito de ondas gigantes. A sua disciplina e dedicação são lendárias entre os seus pares.</p><p>Começou a visitar a Nazaré em 2017 e agora passa seis meses por ano em Portugal, sempre em busca da onda perfeita.</p>',
                    'en' => '<p>Kai Nakamura is the first Japanese to consistently stand out on the big wave circuit. His discipline and dedication are legendary among his peers.</p><p>He started visiting Nazaré in 2017 and now spends six months a year in Portugal, always in search of the perfect wave.</p>',
                ],
                'photo_url' => $photos[3],
                'achievements' => [
                    ['pt' => 'Pioneiro japonês no Big Wave Tour', 'en' => 'Japanese pioneer in Big Wave Tour'],
                    ['pt' => 'Embaixador do Surf no Japão 2023', 'en' => 'Surf Ambassador in Japan 2023'],
                ],
                'social_media' => [
                    'instagram' => '@kai.nakamura',
                    'youtube' => 'KaiNakamuraSurf',
                ],
                'featured' => false,
                'order' => 4,
            ],
            [
                'name' => 'Sofia Mendes',
                'slug' => 'sofia-mendes',
                'nationality' => 'Portugal',
                'bio' => [
                    'pt' => '<p>Sofia Mendes é a jovem promessa portuguesa do surf de ondas gigantes. Aos 24 anos, já conquistou o respeito dos veteranos do circuito pela sua determinação e talento natural.</p><p>Natural da Nazaré, cresceu a ver as ondas gigantes desde criança e sempre soube que o seu destino era surfá-las.</p>',
                    'en' => '<p>Sofia Mendes is the young Portuguese promise of big wave surfing. At 24, she has already earned the respect of circuit veterans for her determination and natural talent.</p><p>Born in Nazaré, she grew up watching the giant waves since childhood and always knew her destiny was to surf them.</p>',
                ],
                'photo_url' => $photos[4],
                'achievements' => [
                    ['pt' => 'Revelação do Ano 2023', 'en' => 'Rookie of the Year 2023'],
                    ['pt' => 'Campeã Nacional Sub-25', 'en' => 'National U25 Champion'],
                ],
                'social_media' => [
                    'instagram' => '@sofiamendes.surf',
                ],
                'featured' => true,
                'order' => 5,
            ],
            [
                'name' => 'Erik Johansson',
                'slug' => 'erik-johansson',
                'nationality' => 'Suécia',
                'bio' => [
                    'pt' => '<p>Erik Johansson provou que não é preciso nascer junto ao mar para se tornar um surfista de ondas gigantes de classe mundial. O sueco descobriu o surf aos 18 anos e nunca mais parou.</p><p>A sua história inspiradora de superação tornou-o num dos surfistas mais queridos do público na Nazaré.</p>',
                    'en' => '<p>Erik Johansson proved that you don\'t need to be born by the sea to become a world-class big wave surfer. The Swede discovered surfing at 18 and never stopped.</p><p>His inspiring story of overcoming adversity has made him one of the most beloved surfers among fans in Nazaré.</p>',
                ],
                'photo_url' => $photos[5],
                'achievements' => [
                    ['pt' => 'Prémio Coragem 2022', 'en' => 'Courage Award 2022'],
                    ['pt' => 'Documentário "Nordic Giant" 2023', 'en' => '"Nordic Giant" Documentary 2023'],
                ],
                'social_media' => [
                    'instagram' => '@erikjohansson_surf',
                ],
                'featured' => false,
                'order' => 6,
            ],
        ];

        foreach ($surfers as $surfer) {
            // Download the photo
            $photoUrl = $surfer['photo_url'] ?? null;
            unset($surfer['photo_url']);

            $photo = null;
            if ($photoUrl) {
                $filename = "surfer-{$surfer['slug']}.jpg";
                $photo = $this->downloadImage($photoUrl, 'surfers', $filename);
            }

            $surfer['photo'] = $photo;
            Surfer::create($surfer);
        }
    }
}
