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
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->json('title');              // i18n: {pt: '', en: ''}
            $table->string('slug')->unique();
            $table->json('content');            // i18n: {pt: '', en: ''}
            $table->json('excerpt')->nullable(); // i18n: {pt: '', en: ''}
            $table->string('cover_image')->nullable();
            $table->string('author')->nullable();
            $table->string('category')->nullable();
            $table->string('entity')->default('praia-norte'); // praia-norte, carsurf, nazare-qualifica
            $table->json('tags')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->json('seo_title')->nullable();       // i18n
            $table->json('seo_description')->nullable(); // i18n
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};
