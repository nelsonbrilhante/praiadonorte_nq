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
        Schema::create('surfboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surfer_id')->constrained()->onDelete('cascade');
            $table->string('brand');
            $table->string('model')->nullable();
            $table->string('length')->nullable();      // e.g., "6'2"
            $table->string('image')->nullable();
            $table->json('specs')->nullable();         // {width: '', thickness: '', ...}
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surfboards');
    }
};
