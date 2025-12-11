<?php

namespace App\Filament\Resources\Geral\Pages;

use App\Filament\Resources\Geral\HomepageResource;
use Filament\Resources\Pages\ListRecords;

class ListHomepages extends ListRecords
{
    protected static string $resource = HomepageResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
