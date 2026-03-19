<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Existing users created before RBAC have role = NULL.
        // Set them to 'admin' so they retain full access.
        DB::table('users')->whereNull('role')->update(['role' => 'admin']);
    }

    public function down(): void
    {
        // No rollback — roles are managed via admin panel
    }
};
