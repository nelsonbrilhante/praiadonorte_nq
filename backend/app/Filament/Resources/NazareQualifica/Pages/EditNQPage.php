<?php

namespace App\Filament\Resources\NazareQualifica\Pages;

use App\Filament\Resources\NazareQualifica\NQPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditNQPage extends EditRecord
{
    protected static string $resource = NQPageResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
