<?php

namespace App\Filament\Resources\Eventos\Pages;

use App\Filament\Resources\Eventos\EventoResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateEvento extends CreateRecord
{
    protected static string $resource = EventoResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }
}
