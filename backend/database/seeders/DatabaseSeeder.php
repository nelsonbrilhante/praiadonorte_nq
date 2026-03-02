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
        // Create admin user (idempotent — safe to run multiple times)
        User::firstOrCreate(
            ['email' => 'admin@nazarequalifica.pt'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );

        // Only seed content if tables are empty (handles partial seeding failure)
        if (\App\Models\Evento::count() === 0) {
            $this->command?->info('Seeding content (tables empty)...');
            $this->call([
                SurferSeeder::class,
                SurfboardSeeder::class,
                NoticiaSeeder::class,
                EventoSeeder::class,
                PaginaSeeder::class,
                DocumentCategorySeeder::class,
                DocumentSeeder::class,
                CorporateBodySeeder::class,
            ]);
        } else {
            $this->command?->info('Content already exists, skipping seeders.');
        }
    }
}
