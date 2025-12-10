<?php

namespace App\Filament\Widgets;

use App\Models\Noticia;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestNoticiasWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Últimas Notícias';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Noticia::query()
                    ->orderBy('published_at', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title.pt')
                    ->label('Título')
                    ->limit(50)
                    ->searchable(),

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

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publicado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
