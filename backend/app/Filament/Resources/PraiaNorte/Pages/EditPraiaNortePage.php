<?php

namespace App\Filament\Resources\PraiaNorte\Pages;

use App\Filament\Resources\PraiaNorte\PraiaNortePageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPraiaNortePage extends EditRecord
{
    protected static string $resource = PraiaNortePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
