<?php

namespace App\Filament\Resources\Carsurf\Pages;

use App\Filament\Resources\Carsurf\CarsurfPageResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateCarsurfPage extends CreateRecord
{
    protected static string $resource = CarsurfPageResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['entity'] = 'carsurf';

        return $data;
    }
}
