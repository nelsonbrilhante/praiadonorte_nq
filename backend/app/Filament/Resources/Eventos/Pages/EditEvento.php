<?php

namespace App\Filament\Resources\Eventos\Pages;

use App\Filament\Resources\Eventos\EventoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditEvento extends EditRecord
{
    protected static string $resource = EventoResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
