<?php

namespace App\Filament\Resources\Paginas\Pages;

use App\Filament\Resources\Paginas\PaginaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditPagina extends EditRecord
{
    protected static string $resource = PaginaResource::class;

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
