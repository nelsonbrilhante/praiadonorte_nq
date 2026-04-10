<?php

namespace App\Filament\Resources\NazareQualifica\ContraOrdenacaoDocumentResource\Pages;

use App\Filament\Resources\NazareQualifica\ContraOrdenacaoDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContraOrdenacaoDocuments extends ListRecords
{
    protected static string $resource = ContraOrdenacaoDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
