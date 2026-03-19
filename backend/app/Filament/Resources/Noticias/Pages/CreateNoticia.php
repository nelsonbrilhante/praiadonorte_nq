<?php

namespace App\Filament\Resources\Noticias\Pages;

use App\Filament\Resources\Noticias\NoticiaResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateNoticia extends CreateRecord
{
    protected static string $resource = NoticiaResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }
}
