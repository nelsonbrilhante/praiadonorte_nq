<?php

namespace App\Filament\Resources\Surfboards\Pages;

use App\Filament\Resources\Surfboards\SurfboardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSurfboards extends ListRecords
{
    protected static string $resource = SurfboardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
