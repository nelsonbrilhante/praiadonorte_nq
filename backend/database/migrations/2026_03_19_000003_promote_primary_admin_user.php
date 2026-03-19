<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL applied the column default 'editor' to existing users when
        // the role column was added (unlike SQLite which leaves NULL).
        // The primary admin user must be promoted to 'admin'.
        DB::table('users')
            ->where('email', 'nelson.brilhante@cm-nazare.pt')
            ->update(['role' => 'admin']);
    }

    public function down(): void
    {
        // No rollback — roles are managed via admin panel
    }
};
