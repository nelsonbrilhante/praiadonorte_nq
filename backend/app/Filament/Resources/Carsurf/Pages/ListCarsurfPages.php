<?php

namespace App\Filament\Resources\Carsurf\Pages;

use App\Filament\Resources\Carsurf\CarsurfPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarsurfPages extends ListRecords
{
    protected static string $resource = CarsurfPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
