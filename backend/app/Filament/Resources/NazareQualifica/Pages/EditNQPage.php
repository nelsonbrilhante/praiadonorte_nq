<?php

namespace App\Filament\Resources\NazareQualifica\Pages;

use App\Filament\Resources\NazareQualifica\NQPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNQPage extends EditRecord
{
    protected static string $resource = NQPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
