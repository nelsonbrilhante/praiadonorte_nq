<?php

namespace App\Filament\Resources\Eventos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class EventosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Imagem')
                    ->circular(),
                TextColumn::make('title.pt')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('start_date')
                    ->label('Início')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Fim')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('location')
                    ->label('Local')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('entity')
                    ->label('Entidade')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'praia-norte' => 'Praia do Norte',
                        'carsurf' => 'Carsurf',
                        'nazare-qualifica' => 'Nazaré Qualifica',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'praia-norte' => 'primary',
                        'carsurf' => 'success',
                        'nazare-qualifica' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),
                IconColumn::make('featured')
                    ->label('Destaque')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Criado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                SelectFilter::make('entity')
                    ->label('Entidade')
                    ->options([
                        'praia-norte' => 'Praia do Norte',
                        'carsurf' => 'Carsurf',
                        'nazare-qualifica' => 'Nazaré Qualifica',
                    ]),
                TernaryFilter::make('featured')
                    ->label('Destaque'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
