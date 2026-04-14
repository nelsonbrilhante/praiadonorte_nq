<?php

namespace App\Filament\Resources\Configuracoes\Pages;

use App\Filament\Resources\Configuracoes\ActivityLogResource;
use Filament\Resources\Pages\ViewRecord;

class ViewActivityLog extends ViewRecord
{
    protected static string $resource = ActivityLogResource::class;

    public function getTitle(): string
    {
        return 'Detalhe do Registo';
    }
}
