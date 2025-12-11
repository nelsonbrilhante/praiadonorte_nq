<?php

namespace App\Filament\Resources\Carsurf;

use App\Filament\Resources\Paginas\BasePageResource;
use App\Filament\Resources\Carsurf\Pages\CreateCarsurfPage;
use App\Filament\Resources\Carsurf\Pages\EditCarsurfPage;
use App\Filament\Resources\Carsurf\Pages\ListCarsurfPages;

class CarsurfPageResource extends BasePageResource
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-trophy';

    protected static string|\UnitEnum|null $navigationGroup = 'Carsurf';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Páginas';

    protected static ?string $slug = 'carsurf/paginas';

    public static function getEntityFilter(): string
    {
        return 'carsurf';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCarsurfPages::route('/'),
            'create' => CreateCarsurfPage::route('/create'),
            'edit' => EditCarsurfPage::route('/{record}/edit'),
        ];
    }
}
