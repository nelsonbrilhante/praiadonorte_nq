<?php

namespace App\Filament\Widgets;

use App\Models\Evento;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingEventosWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Próximos Eventos';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Evento::query()
                    ->where('start_date', '>=', now())
                    ->orderBy('start_date', 'asc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title.pt')
                    ->label('Título')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Local')
                    ->limit(30),

                Tables\Columns\BadgeColumn::make('entity')
                    ->label('Entidade')
                    ->colors([
                        'primary' => 'praia-norte',
                        'warning' => 'nazare-qualifica',
                        'success' => 'carsurf',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'praia-norte' => 'Praia do Norte',
                        'carsurf' => 'Carsurf',
                        'nazare-qualifica' => 'Nazaré Qualifica',
                        default => $state,
                    }),
            ])
            ->paginated(false)
            ->emptyStateHeading('Sem eventos próximos')
            ->emptyStateDescription('Não há eventos agendados para o futuro.');
    }
}
