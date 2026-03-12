<?php

namespace App\Filament\Resources\Utilizadores\Pages;

use App\Filament\Resources\Utilizadores\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
