<?php

namespace App\Filament\Resources\Geral\Pages;

use App\Filament\Resources\Geral\HomepageResource;
use Filament\Resources\Pages\EditRecord;

class EditHomepage extends EditRecord
{
    protected static string $resource = HomepageResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
