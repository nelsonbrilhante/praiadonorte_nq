<?php

namespace App\Filament\Resources\Configuracoes\Pages;

use App\Filament\Resources\Configuracoes\ActivityLogResource;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    public function getTitle(): string
    {
        return 'Registos de Atividade';
    }
}
