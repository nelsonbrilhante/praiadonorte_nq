<?php

namespace App\Filament\Resources\Configuracoes;

use App\Filament\Resources\Configuracoes\Pages\ListActivityLogs;
use App\Filament\Resources\Configuracoes\Pages\ViewActivityLog;
use App\Filament\Resources\Configuracoes\Schemas\ActivityLogInfolist;
use App\Filament\Resources\Configuracoes\Tables\ActivityLogTable;
use App\Models\ActivityLog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string|\UnitEnum|null $navigationGroup = 'Configurações';

    protected static ?int $navigationSort = 999;

    protected static ?string $modelLabel = 'Registo de Atividade';

    protected static ?string $pluralModelLabel = 'Registos de Atividade';

    protected static ?string $navigationLabel = 'Registos de Atividade';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return ActivityLogTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ActivityLogInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            'view' => ViewActivityLog::route('/{record}'),
        ];
    }
}
