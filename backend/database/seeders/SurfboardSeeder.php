<?php

namespace Database\Seeders;

use App\Models\Surfboard;
use App\Models\Surfer;
use Illuminate\Database\Seeder;

class SurfboardSeeder extends Seeder
{
    public function run(): void
    {
        $surfers = Surfer::all();

        $surfboards = [
            // António Laureano
            [
                'surfer_slug' => 'antonio-laureano',
                'brand' => 'Pyzel',
                'model' => 'Ghost Pro',
                'length' => "6'4\"",
                'specs' => [
                    'width' => '19.5"',
                    'thickness' => '2.75"',
                    'volume' => '35L',
                    'fins' => 'Thruster',
                    'tail' => 'Squash',
                ],
                'order' => 1,
            ],
            [
                'surfer_slug' => 'antonio-laureano',
                'brand' => 'Channel Islands',
                'model' => 'Big Wave Gun',
                'length' => "10'2\"",
                'specs' => [
                    'width' => '21"',
                    'thickness' => '3.5"',
                    'volume' => '85L',
                    'fins' => 'Single + Stabilizers',
                    'tail' => 'Pin',
                ],
                'order' => 2,
            ],
            // Maya Richardson
            [
                'surfer_slug' => 'maya-richardson',
                'brand' => 'Firewire',
                'model' => 'Thunderbolt',
                'length' => "9'6\"",
                'specs' => [
                    'width' => '20"',
                    'thickness' => '3.25"',
                    'volume' => '72L',
                    'fins' => 'Quad',
                    'tail' => 'Pin',
                ],
                'order' => 1,
            ],
            [
                'surfer_slug' => 'maya-richardson',
                'brand' => 'JS Industries',
                'model' => 'Black Baron',
                'length' => "6'0\"",
                'specs' => [
                    'width' => '18.75"',
                    'thickness' => '2.5"',
                    'volume' => '28L',
                    'fins' => 'Thruster',
                    'tail' => 'Round',
                ],
                'order' => 2,
            ],
            // Lucas Fonseca
            [
                'surfer_slug' => 'lucas-fonseca',
                'brand' => 'Sharpeye',
                'model' => 'Storm',
                'length' => "10'0\"",
                'specs' => [
                    'width' => '20.5"',
                    'thickness' => '3.4"',
                    'volume' => '80L',
                    'fins' => 'Thruster',
                    'tail' => 'Pin',
                ],
                'order' => 1,
            ],
            // Kai Nakamura
            [
                'surfer_slug' => 'kai-nakamura',
                'brand' => 'Album',
                'model' => 'Tsunami',
                'length' => "9'8\"",
                'specs' => [
                    'width' => '20"',
                    'thickness' => '3.3"',
                    'volume' => '76L',
                    'fins' => 'Single + Stabilizers',
                    'tail' => 'Pin',
                ],
                'order' => 1,
            ],
            // Sofia Mendes
            [
                'surfer_slug' => 'sofia-mendes',
                'brand' => 'Lost',
                'model' => 'Rocket Redux',
                'length' => "5'10\"",
                'specs' => [
                    'width' => '19"',
                    'thickness' => '2.4"',
                    'volume' => '27L',
                    'fins' => 'Thruster',
                    'tail' => 'Squash',
                ],
                'order' => 1,
            ],
            [
                'surfer_slug' => 'sofia-mendes',
                'brand' => 'Pyzel',
                'model' => 'Padillac',
                'length' => "9'4\"",
                'specs' => [
                    'width' => '19.5"',
                    'thickness' => '3.2"',
                    'volume' => '68L',
                    'fins' => 'Quad',
                    'tail' => 'Pin',
                ],
                'order' => 2,
            ],
            // Erik Johansson
            [
                'surfer_slug' => 'erik-johansson',
                'brand' => 'DHD',
                'model' => 'Skeleton Key',
                'length' => "6'2\"",
                'specs' => [
                    'width' => '19.25"',
                    'thickness' => '2.6"',
                    'volume' => '32L',
                    'fins' => 'Thruster',
                    'tail' => 'Swallow',
                ],
                'order' => 1,
            ],
            [
                'surfer_slug' => 'erik-johansson',
                'brand' => 'Christenson',
                'model' => 'Wolverine',
                'length' => "10'4\"",
                'specs' => [
                    'width' => '21.5"',
                    'thickness' => '3.6"',
                    'volume' => '88L',
                    'fins' => 'Single',
                    'tail' => 'Pin',
                ],
                'order' => 2,
            ],
        ];

        foreach ($surfboards as $board) {
            $surfer = $surfers->where('slug', $board['surfer_slug'])->first();
            if ($surfer) {
                Surfboard::create([
                    'surfer_id' => $surfer->id,
                    'brand' => $board['brand'],
                    'model' => $board['model'],
                    'length' => $board['length'],
                    'specs' => $board['specs'],
                    'image' => null,
                    'order' => $board['order'],
                ]);
            }
        }
    }
}
