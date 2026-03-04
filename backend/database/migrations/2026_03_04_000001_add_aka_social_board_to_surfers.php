<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surfers', function (Blueprint $table) {
            $table->string('aka')->nullable()->after('name');
            $table->json('social_media')->nullable()->after('photo');
            $table->string('board_image')->nullable()->after('social_media');
        });
    }

    public function down(): void
    {
        Schema::table('surfers', function (Blueprint $table) {
            $table->dropColumn(['aka', 'social_media', 'board_image']);
        });
    }
};
