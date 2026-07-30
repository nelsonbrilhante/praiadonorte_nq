<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Events are suppressed because SiteSetting logs activity, and the
        // activity_log table is only created by a later migration
        // (2026_04_14_080804) — on a fresh database this would otherwise fail.
        // A data migration should not produce audit-log entries anyway.
        Model::withoutEvents(function () {
            // LegalContentSeeder is idempotent — only seeds if keys don't exist
            (new \Database\Seeders\LegalContentSeeder)->run();
        });
    }

    public function down(): void
    {
        // Legal content managed via admin panel, don't delete on rollback
    }
};
