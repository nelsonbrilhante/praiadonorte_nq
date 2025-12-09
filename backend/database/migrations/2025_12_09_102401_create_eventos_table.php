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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->json('title');                    // i18n: {pt: '', en: ''}
            $table->string('slug')->unique();
            $table->json('description')->nullable();  // i18n: {pt: '', en: ''}
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('location')->nullable();
            $table->string('entity')->default('praia-norte'); // praia-norte, carsurf, nazare-qualifica
            $table->string('image')->nullable();
            $table->string('ticket_url')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
