<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@nazarequalifica.pt',
        ]);

        // Seed content
        $this->call([
            SurferSeeder::class,
            SurfboardSeeder::class,
            NoticiaSeeder::class,
            EventoSeeder::class,
            PaginaSeeder::class,
        ]);
    }
}
