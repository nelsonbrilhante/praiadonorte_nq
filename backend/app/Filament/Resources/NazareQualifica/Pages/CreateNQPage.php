<?php

namespace App\Filament\Resources\NazareQualifica\Pages;

use App\Filament\Resources\NazareQualifica\NQPageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNQPage extends CreateRecord
{
    protected static string $resource = NQPageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['entity'] = 'nazare-qualifica';

        return $data;
    }
}
