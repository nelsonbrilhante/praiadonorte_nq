<?php

namespace App\Filament\Resources\NazareQualifica\CorporateBodyResource\Pages;

use App\Filament\Resources\NazareQualifica\CorporateBodyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCorporateBodies extends ListRecords
{
    protected static string $resource = CorporateBodyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
