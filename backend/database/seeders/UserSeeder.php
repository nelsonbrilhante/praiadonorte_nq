<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'editor@test.dev'],
            ['name' => 'Editor Conteúdo', 'password' => 'password', 'role' => 'editor']
        );

        User::updateOrCreate(
            ['email' => 'pn@test.dev'],
            ['name' => 'Editor Praia Norte', 'password' => 'password', 'role' => 'entity_editor', 'entities' => ['praia-norte']]
        );

        User::updateOrCreate(
            ['email' => 'nq@test.dev'],
            ['name' => 'Editor NQ', 'password' => 'password', 'role' => 'entity_editor', 'entities' => ['nazare-qualifica']]
        );

        User::updateOrCreate(
            ['email' => 'carsurf@test.dev'],
            ['name' => 'Editor Carsurf', 'password' => 'password', 'role' => 'entity_editor', 'entities' => ['carsurf']]
        );

        User::updateOrCreate(
            ['email' => 'multi@test.dev'],
            ['name' => 'Editor Multi-Entidade', 'password' => 'password', 'role' => 'entity_editor', 'entities' => ['praia-norte', 'nazare-qualifica']]
        );
    }
}
