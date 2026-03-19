<?php

namespace App\Filament\Resources\Paginas\Pages;

use App\Filament\Resources\Paginas\PaginaResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreatePagina extends CreateRecord
{
    protected static string $resource = PaginaResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }
}
