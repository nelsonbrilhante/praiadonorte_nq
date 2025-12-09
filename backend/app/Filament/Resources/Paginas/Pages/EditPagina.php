<?php

namespace App\Filament\Resources\Paginas\Pages;

use App\Filament\Resources\Paginas\PaginaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPagina extends EditRecord
{
    protected static string $resource = PaginaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
