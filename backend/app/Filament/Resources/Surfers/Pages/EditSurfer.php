<?php

namespace App\Filament\Resources\Surfers\Pages;

use App\Filament\Resources\Surfers\SurferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditSurfer extends EditRecord
{
    protected static string $resource = SurferResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
