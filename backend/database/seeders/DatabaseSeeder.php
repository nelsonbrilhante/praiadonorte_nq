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
        // Remove old admin user if exists
        User::where('email', 'admin@nazarequalifica.pt')->delete();

        // Create/update admin user (idempotent — safe to run multiple times)
        User::updateOrCreate(
            ['email' => 'nelson.brilhante@cm-nazare.pt'],
            ['name' => 'Nelson Brilhante', 'password' => 'Nzr€Qu@l!f1c4-2026', 'role' => 'admin']
        );

        // Ensure maintenance mode is ON (won't override if already set)
        \App\Models\SiteSetting::firstOrCreate(
            ['key' => 'maintenance_mode'],
            ['value' => '1']
        );

        // Only seed content if ALL content tables are empty (protects production data)
        $hasContent = \App\Models\Surfer::count() > 0
            || \App\Models\Noticia::count() > 0
            || \App\Models\Evento::count() > 0;

        if (! $hasContent) {
            $this->command?->info('Seeding content (tables empty)...');
            $this->call([
                SurferSeeder::class,
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

        // Seed test users in non-production environments
        if (! app()->isProduction()) {
            $this->call(UserSeeder::class);
        }

        // Seed legal content (idempotent — safe to run multiple times)
        $this->call(LegalContentSeeder::class);
    }
}
