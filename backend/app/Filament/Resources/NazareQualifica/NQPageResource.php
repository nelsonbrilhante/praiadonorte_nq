<?php

namespace App\Filament\Resources\NazareQualifica;

use App\Filament\Resources\Paginas\BasePageResource;
use App\Filament\Resources\NazareQualifica\Pages\CreateNQPage;
use App\Filament\Resources\NazareQualifica\Pages\EditNQPage;
use App\Filament\Resources\NazareQualifica\Pages\ListNQPages;

class NQPageResource extends BasePageResource
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string|\UnitEnum|null $navigationGroup = 'Nazaré Qualifica';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Páginas';

    protected static ?string $slug = 'nazare-qualifica/paginas';

    public static function getEntityFilter(): string
    {
        return 'nazare-qualifica';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNQPages::route('/'),
            'create' => CreateNQPage::route('/create'),
            'edit' => EditNQPage::route('/{record}/edit'),
        ];
    }
}
