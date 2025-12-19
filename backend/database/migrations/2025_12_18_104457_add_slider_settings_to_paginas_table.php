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
            $table->integer('slider_interval')->default(8)->after('hero_logo_height');
            $table->boolean('slider_autoplay')->default(true)->after('slider_interval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paginas', function (Blueprint $table) {
            $table->dropColumn(['slider_interval', 'slider_autoplay']);
        });
    }
};
