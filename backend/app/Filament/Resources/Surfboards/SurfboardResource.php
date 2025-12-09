<?php

namespace App\Filament\Resources\Surfboards;

use App\Filament\Resources\Surfboards\Pages\CreateSurfboard;
use App\Filament\Resources\Surfboards\Pages\EditSurfboard;
use App\Filament\Resources\Surfboards\Pages\ListSurfboards;
use App\Filament\Resources\Surfboards\Schemas\SurfboardForm;
use App\Filament\Resources\Surfboards\Tables\SurfboardsTable;
use App\Models\Surfboard;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SurfboardResource extends Resource
{
    protected static ?string $model = Surfboard::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SurfboardForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurfboardsTable::configure($table);
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
            'index' => ListSurfboards::route('/'),
            'create' => CreateSurfboard::route('/create'),
            'edit' => EditSurfboard::route('/{record}/edit'),
        ];
    }
}
