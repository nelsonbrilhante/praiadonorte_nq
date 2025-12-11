<?php

namespace App\Filament\Resources\Paginas;

use App\Filament\Resources\Paginas\Schemas\PaginaForm;
use App\Filament\Resources\Paginas\Tables\PaginasTable;
use App\Models\Pagina;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Base Resource for entity-scoped page management.
 *
 * Each entity (Praia do Norte, Carsurf, Nazaré Qualifica) extends this
 * class and provides its own entity filter. This allows each entity
 * to have its own navigation group in Filament while sharing the
 * same form and table configuration.
 */
abstract class BasePageResource extends Resource
{
    protected static ?string $model = Pagina::class;

    protected static ?string $modelLabel = 'Página';

    protected static ?string $pluralModelLabel = 'Páginas';

    /**
     * Get the entity value to filter pages by.
     * Each subclass must implement this to return their entity slug.
     */
    abstract public static function getEntityFilter(): string;

    /**
     * Scope all queries to only show pages for this entity.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('entity', static::getEntityFilter());
    }

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
        return [];
    }
}
