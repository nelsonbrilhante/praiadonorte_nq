<?php

namespace App\Filament\Resources\NazareQualifica\DocumentCategoryResource\Pages;

use App\Filament\Resources\NazareQualifica\DocumentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDocumentCategory extends EditRecord
{
    protected static string $resource = DocumentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
