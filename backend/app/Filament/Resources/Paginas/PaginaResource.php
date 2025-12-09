<?php

namespace App\Filament\Resources\Paginas;

use App\Filament\Resources\Paginas\Pages\CreatePagina;
use App\Filament\Resources\Paginas\Pages\EditPagina;
use App\Filament\Resources\Paginas\Pages\ListPaginas;
use App\Filament\Resources\Paginas\Schemas\PaginaForm;
use App\Filament\Resources\Paginas\Tables\PaginasTable;
use App\Models\Pagina;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaginaResource extends Resource
{
    protected static ?string $model = Pagina::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PaginaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaginasTable::configure($table);
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
            'index' => ListPaginas::route('/'),
            'create' => CreatePagina::route('/create'),
            'edit' => EditPagina::route('/{record}/edit'),
        ];
    }
}
