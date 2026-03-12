<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Editor = 'editor';
    case EntityEditor = 'entity_editor';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Editor => 'Editor de Conteúdo',
            self::EntityEditor => 'Editor de Entidade',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Admin => 'danger',
            self::Editor => 'success',
            self::EntityEditor => 'warning',
        };
    }
}
