<?php

namespace App\Filament\Resources\PraiaNorte\Pages;

use App\Filament\Resources\PraiaNorte\PraiaNortePageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePraiaNortePage extends CreateRecord
{
    protected static string $resource = PraiaNortePageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['entity'] = 'praia-norte';

        return $data;
    }
}
