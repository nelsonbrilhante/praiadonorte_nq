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
        // Skip seeding if real data has been imported
        if (Surfer::count() > 0) {
            return;
        }

        // Unsplash portraits for surfers (diverse athletes)
        $photos = [
            'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=600&h=800&fit=crop',
            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=600&h=800&fit=crop',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=800&fit=crop',
            'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=600&h=800&fit=crop',
            'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=600&h=800&fit=crop',
            'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&h=800&fit=crop',
        ];

        $surfers = [
            [
                'name' => 'António Laureano',
                'slug' => 'antonio-laureano',
                'bio' => [
                    'pt' => '<p>António Laureano é um dos surfistas portugueses mais respeitados nas ondas gigantes da Nazaré.</p>',
                    'en' => '<p>António Laureano is one of the most respected Portuguese surfers in the giant waves of Nazaré.</p>',
                ],
                'photo_url' => $photos[0],
                'featured' => true,
                'order' => 1,
            ],
            [
                'name' => 'Maya Richardson',
                'slug' => 'maya-richardson',
                'bio' => [
                    'pt' => '<p>Maya Richardson é uma força da natureza no mundo do surf feminino de ondas gigantes.</p>',
                    'en' => '<p>Maya Richardson is a force of nature in women\'s big wave surfing.</p>',
                ],
                'photo_url' => $photos[1],
                'featured' => true,
                'order' => 2,
            ],
            [
                'name' => 'Lucas Fonseca',
                'slug' => 'lucas-fonseca',
                'bio' => [
                    'pt' => '<p>Lucas Fonseca trocou as ondas de Florianópolis pelas montanhas de água da Nazaré em 2015.</p>',
                    'en' => '<p>Lucas Fonseca traded the waves of Florianópolis for the water mountains of Nazaré in 2015.</p>',
                ],
                'photo_url' => $photos[2],
                'featured' => true,
                'order' => 3,
            ],
            [
                'name' => 'Kai Nakamura',
                'slug' => 'kai-nakamura',
                'bio' => [
                    'pt' => '<p>Kai Nakamura é o primeiro japonês a destacar-se consistentemente no circuito de ondas gigantes.</p>',
                    'en' => '<p>Kai Nakamura is the first Japanese to consistently stand out on the big wave circuit.</p>',
                ],
                'photo_url' => $photos[3],
                'featured' => false,
                'order' => 4,
            ],
            [
                'name' => 'Sofia Mendes',
                'slug' => 'sofia-mendes',
                'bio' => [
                    'pt' => '<p>Sofia Mendes é a jovem promessa portuguesa do surf de ondas gigantes.</p>',
                    'en' => '<p>Sofia Mendes is the young Portuguese promise of big wave surfing.</p>',
                ],
                'photo_url' => $photos[4],
                'featured' => true,
                'order' => 5,
            ],
            [
                'name' => 'Erik Johansson',
                'slug' => 'erik-johansson',
                'bio' => [
                    'pt' => '<p>Erik Johansson provou que não é preciso nascer junto ao mar para se tornar um surfista de ondas gigantes.</p>',
                    'en' => '<p>Erik Johansson proved that you don\'t need to be born by the sea to become a world-class big wave surfer.</p>',
                ],
                'photo_url' => $photos[5],
                'featured' => false,
                'order' => 6,
            ],
        ];

        foreach ($surfers as $surfer) {
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
