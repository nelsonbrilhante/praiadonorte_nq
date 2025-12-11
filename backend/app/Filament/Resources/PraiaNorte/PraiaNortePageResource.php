<?php

namespace App\Filament\Resources\PraiaNorte;

use App\Filament\Resources\Paginas\BasePageResource;
use App\Filament\Resources\PraiaNorte\Pages\CreatePraiaNortePage;
use App\Filament\Resources\PraiaNorte\Pages\EditPraiaNortePage;
use App\Filament\Resources\PraiaNorte\Pages\ListPraiaaNortePages;
use Illuminate\Database\Eloquent\Builder;

class PraiaNortePageResource extends BasePageResource
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Praia do Norte';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Páginas';

    protected static ?string $slug = 'praia-norte/paginas';

    public static function getEntityFilter(): string
    {
        return 'praia-norte';
    }

    /**
     * Exclude homepage from this resource (it has its own resource in Geral).
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('slug', '!=', 'homepage');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPraiaaNortePages::route('/'),
            'create' => CreatePraiaNortePage::route('/create'),
            'edit' => EditPraiaNortePage::route('/{record}/edit'),
        ];
    }
}
