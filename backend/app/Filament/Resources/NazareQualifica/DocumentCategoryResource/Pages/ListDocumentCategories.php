<?php

namespace App\Filament\Resources\NazareQualifica\DocumentCategoryResource\Pages;

use App\Filament\Resources\NazareQualifica\DocumentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocumentCategories extends ListRecords
{
    protected static string $resource = DocumentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
