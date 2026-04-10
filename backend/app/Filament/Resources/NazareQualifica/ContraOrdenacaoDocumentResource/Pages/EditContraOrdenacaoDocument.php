<?php

namespace App\Filament\Resources\NazareQualifica\ContraOrdenacaoDocumentResource\Pages;

use App\Filament\Resources\NazareQualifica\ContraOrdenacaoDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContraOrdenacaoDocument extends EditRecord
{
    protected static string $resource = ContraOrdenacaoDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
