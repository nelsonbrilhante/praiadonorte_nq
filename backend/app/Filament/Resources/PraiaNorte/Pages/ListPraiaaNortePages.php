<?php

namespace App\Filament\Resources\PraiaNorte\Pages;

use App\Filament\Resources\PraiaNorte\PraiaNortePageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPraiaaNortePages extends ListRecords
{
    protected static string $resource = PraiaNortePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
