<?php

namespace App\Filament\Resources\Carsurf\Pages;

use App\Filament\Resources\Carsurf\CarsurfPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarsurfPage extends EditRecord
{
    protected static string $resource = CarsurfPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
