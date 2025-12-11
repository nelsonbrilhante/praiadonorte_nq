<?php

namespace App\Filament\Resources\Paginas;

use App\Filament\Resources\Paginas\Pages\CreatePagina;
use App\Filament\Resources\Paginas\Pages\EditPagina;
use App\Filament\Resources\Paginas\Pages\ListPaginas;
use App\Filament\Resources\Paginas\Schemas\PaginaForm;
use App\Filament\Resources\Paginas\Tables\PaginasTable;
use App\Models\Pagina;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PaginaResource extends Resource
{
    protected static ?string $model = Pagina::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Páginas';

    protected static ?int $navigationSort = 1;

    /**
     * Hide from navigation - replaced by entity-specific resources.
     * @see \App\Filament\Resources\PraiaNorte\PraiaNortePageResource
     * @see \App\Filament\Resources\Carsurf\CarsurfPageResource
     * @see \App\Filament\Resources\NazareQualifica\NQPageResource
     */
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $modelLabel = 'Página';

    protected static ?string $pluralModelLabel = 'Páginas';

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
