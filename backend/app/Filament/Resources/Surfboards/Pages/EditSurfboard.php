<?php

namespace App\Filament\Resources\Surfboards\Pages;

use App\Filament\Resources\Surfboards\SurfboardResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSurfboard extends EditRecord
{
    protected static string $resource = SurfboardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
