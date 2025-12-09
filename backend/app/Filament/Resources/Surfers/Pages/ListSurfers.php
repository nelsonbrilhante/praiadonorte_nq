<?php

namespace App\Filament\Resources\Surfers\Pages;

use App\Filament\Resources\Surfers\SurferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSurfers extends ListRecords
{
    protected static string $resource = SurferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
