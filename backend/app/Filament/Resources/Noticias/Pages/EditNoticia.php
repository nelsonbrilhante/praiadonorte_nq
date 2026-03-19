<?php

namespace App\Filament\Resources\Noticias\Pages;

use App\Filament\Resources\Noticias\NoticiaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditNoticia extends EditRecord
{
    protected static string $resource = NoticiaResource::class;

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
