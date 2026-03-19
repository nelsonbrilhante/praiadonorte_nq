<?php

namespace App\Filament\Resources\PraiaNorte\Pages;

use App\Filament\Resources\PraiaNorte\PraiaNortePageResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreatePraiaNortePage extends CreateRecord
{
    protected static string $resource = PraiaNortePageResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['entity'] = 'praia-norte';

        return $data;
    }
}
