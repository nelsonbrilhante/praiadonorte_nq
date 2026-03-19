<?php

namespace App\Filament\Resources\PraiaNorte\Pages;

use App\Filament\Resources\PraiaNorte\PraiaNortePageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditPraiaNortePage extends EditRecord
{
    protected static string $resource = PraiaNortePageResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
