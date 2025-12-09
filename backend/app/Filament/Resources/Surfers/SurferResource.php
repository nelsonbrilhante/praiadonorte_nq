<?php

namespace App\Filament\Resources\Surfers;

use App\Filament\Resources\Surfers\Pages\CreateSurfer;
use App\Filament\Resources\Surfers\Pages\EditSurfer;
use App\Filament\Resources\Surfers\Pages\ListSurfers;
use App\Filament\Resources\Surfers\Schemas\SurferForm;
use App\Filament\Resources\Surfers\Tables\SurfersTable;
use App\Models\Surfer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SurferResource extends Resource
{
    protected static ?string $model = Surfer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SurferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurfersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSurfers::route('/'),
            'create' => CreateSurfer::route('/create'),
            'edit' => EditSurfer::route('/{record}/edit'),
        ];
    }
}
