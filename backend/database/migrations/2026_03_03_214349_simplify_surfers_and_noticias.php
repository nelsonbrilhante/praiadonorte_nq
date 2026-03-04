<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surfers', function (Blueprint $table) {
            $table->dropColumn(['nationality', 'achievements', 'social_media']);
        });

        Schema::table('noticias', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description']);
        });

        Schema::dropIfExists('surfboards');
    }

    public function down(): void
    {
        Schema::table('surfers', function (Blueprint $table) {
            $table->string('nationality')->nullable();
            $table->json('achievements')->nullable();
            $table->json('social_media')->nullable();
        });

        Schema::table('noticias', function (Blueprint $table) {
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
        });

        Schema::create('surfboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surfer_id')->constrained()->cascadeOnDelete();
            $table->string('brand');
            $table->string('model');
            $table->string('length')->nullable();
            $table->string('image')->nullable();
            $table->json('specs')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }
};
