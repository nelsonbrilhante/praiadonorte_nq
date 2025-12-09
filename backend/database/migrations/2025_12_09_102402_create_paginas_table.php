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
        Schema::create('paginas', function (Blueprint $table) {
            $table->id();
            $table->json('title');                       // i18n: {pt: '', en: ''}
            $table->string('slug');
            $table->json('content')->nullable();         // i18n: {pt: '', en: ''}
            $table->string('entity')->default('praia-norte'); // praia-norte, carsurf, nazare-qualifica
            $table->json('seo_title')->nullable();       // i18n
            $table->json('seo_description')->nullable(); // i18n
            $table->boolean('published')->default(true);
            $table->timestamps();

            // Unique constraint: slug must be unique per entity
            $table->unique(['entity', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paginas');
    }
};
