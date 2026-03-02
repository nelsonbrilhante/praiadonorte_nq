<?php

namespace App\Filament\Resources\NazareQualifica\DocumentResource\Pages;

use App\Filament\Resources\NazareQualifica\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
