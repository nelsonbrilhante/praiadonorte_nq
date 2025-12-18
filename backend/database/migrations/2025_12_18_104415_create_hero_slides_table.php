<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pagina_id')->constrained('paginas')->cascadeOnDelete();
            $table->integer('order')->default(0);

            // Video/Media
            $table->string('video_url')->nullable();
            $table->string('fallback_image')->nullable();

            // Live Stream
            $table->boolean('is_live')->default(false);
            $table->boolean('audio_enabled')->default(false);

            // Logo option
            $table->string('hero_logo')->nullable();
            $table->boolean('use_logo_as_title')->default(false);
            $table->integer('logo_height')->default(120);

            // i18n Content (JSON)
            $table->json('title')->nullable();
            $table->json('subtitle')->nullable();
            $table->json('cta_text')->nullable();
            $table->json('cta_url')->nullable();

            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['pagina_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
