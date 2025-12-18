<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migrates existing hero data from homepage to hero_slides table.
     */
    public function up(): void
    {
        // Get the homepage record
        $homepage = DB::table('paginas')
            ->where('slug', 'homepage')
            ->first();

        if ($homepage) {
            $content = json_decode($homepage->content, true) ?? [];

            // Create first slide from existing hero data
            DB::table('hero_slides')->insert([
                'pagina_id' => $homepage->id,
                'order' => 0,
                'video_url' => $homepage->video_url,
                'fallback_image' => null,
                'is_live' => $homepage->is_live ?? false,
                'audio_enabled' => $homepage->audio_enabled ?? false,
                'hero_logo' => $homepage->hero_logo,
                'use_logo_as_title' => $homepage->hero_use_logo ?? false,
                'logo_height' => $homepage->hero_logo_height ?? 120,
                'title' => json_encode([
                    'pt' => $content['pt']['hero']['title'] ?? null,
                    'en' => $content['en']['hero']['title'] ?? null,
                ]),
                'subtitle' => json_encode([
                    'pt' => $content['pt']['hero']['subtitle'] ?? null,
                    'en' => $content['en']['hero']['subtitle'] ?? null,
                ]),
                'cta_text' => json_encode([
                    'pt' => $content['pt']['hero']['cta_text'] ?? null,
                    'en' => $content['en']['hero']['cta_text'] ?? null,
                ]),
                'cta_url' => json_encode([
                    'pt' => $content['pt']['hero']['cta_url'] ?? null,
                    'en' => $content['en']['hero']['cta_url'] ?? null,
                ]),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get homepage and delete its slides
        $homepage = DB::table('paginas')
            ->where('slug', 'homepage')
            ->first();

        if ($homepage) {
            DB::table('hero_slides')
                ->where('pagina_id', $homepage->id)
                ->delete();
        }
    }
};
