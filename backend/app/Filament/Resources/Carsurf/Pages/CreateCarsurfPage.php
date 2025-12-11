<?php

namespace App\Filament\Resources\Carsurf\Pages;

use App\Filament\Resources\Carsurf\CarsurfPageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCarsurfPage extends CreateRecord
{
    protected static string $resource = CarsurfPageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['entity'] = 'carsurf';

        return $data;
    }
}
