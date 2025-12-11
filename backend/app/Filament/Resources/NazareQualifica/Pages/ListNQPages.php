<?php

namespace App\Filament\Resources\NazareQualifica\Pages;

use App\Filament\Resources\NazareQualifica\NQPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNQPages extends ListRecords
{
    protected static string $resource = NQPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
