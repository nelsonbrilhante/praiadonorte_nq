<?php

namespace App\Filament\Resources\Carsurf\Pages;

use App\Filament\Resources\Carsurf\CarsurfPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditCarsurfPage extends EditRecord
{
    protected static string $resource = CarsurfPageResource::class;

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
