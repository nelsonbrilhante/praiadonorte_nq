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
        Schema::table('paginas', function (Blueprint $table) {
            $table->string('hero_logo')->nullable()->after('audio_enabled');
            $table->boolean('hero_use_logo')->default(false)->after('hero_logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paginas', function (Blueprint $table) {
            $table->dropColumn(['hero_logo', 'hero_use_logo']);
        });
    }
};
