<?php

namespace App\Filament\Resources\NazareQualifica\Pages;

use App\Filament\Resources\NazareQualifica\NQPageResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateNQPage extends CreateRecord
{
    protected static string $resource = NQPageResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['entity'] = 'nazare-qualifica';

        return $data;
    }
}
