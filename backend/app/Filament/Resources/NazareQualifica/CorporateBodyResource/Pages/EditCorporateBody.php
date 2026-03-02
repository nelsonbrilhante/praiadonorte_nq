<?php

namespace App\Filament\Resources\NazareQualifica\CorporateBodyResource\Pages;

use App\Filament\Resources\NazareQualifica\CorporateBodyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCorporateBody extends EditRecord
{
    protected static string $resource = CorporateBodyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
