<?php

namespace App\Filament\Widgets;

use App\Models\Evento;
use App\Models\Noticia;
use App\Models\Pagina;
use App\Models\Surfer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Notícias', Noticia::count())
                ->description('Total de notícias')
                ->descriptionIcon('heroicon-o-newspaper')
                ->color('primary'),

            Stat::make('Eventos', Evento::count())
                ->description('Total de eventos')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('success'),

            Stat::make('Surfers', Surfer::count())
                ->description('Total de surfers')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('warning'),

            Stat::make('Páginas', Pagina::count())
                ->description('Total de páginas')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('info'),
        ];
    }
}
