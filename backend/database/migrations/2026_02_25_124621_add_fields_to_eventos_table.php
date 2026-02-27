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
        Schema::table('eventos', function (Blueprint $table) {
            $table->json('excerpt')->nullable()->after('description');
            $table->string('category')->nullable()->after('entity');
            $table->json('gallery')->nullable()->after('image');
            $table->string('video_url')->nullable()->after('ticket_url');
            $table->json('schedule')->nullable()->after('video_url');
            $table->json('partners')->nullable()->after('schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'category', 'gallery', 'video_url', 'schedule', 'partners']);
        });
    }
};
