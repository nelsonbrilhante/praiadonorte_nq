<?php

namespace App\Filament\Resources\Surfers\Pages;

use App\Filament\Resources\Surfers\SurferResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateSurfer extends CreateRecord
{
    protected static string $resource = SurferResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::Full;
    }
}
