<?php

namespace App\Filament\Resources\Surfers\Pages;

use App\Filament\Resources\Surfers\SurferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSurfer extends EditRecord
{
    protected static string $resource = SurferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
