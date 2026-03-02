<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corporate_bodies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('role');
            $table->string('section'); // conselho_gerencia, assembleia_geral, fiscal_unico
            $table->string('photo')->nullable();
            $table->string('cv_file')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corporate_bodies');
    }
};
