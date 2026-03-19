<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // LegalContentSeeder is idempotent — only seeds if keys don't exist
        (new \Database\Seeders\LegalContentSeeder)->run();
    }

    public function down(): void
    {
        // Legal content managed via admin panel, don't delete on rollback
    }
};
