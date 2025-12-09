<?php

namespace App\Filament\Resources\Paginas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PaginasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title.pt')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Slug copiado!'),
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
                IconColumn::make('published')
                    ->label('Publicada')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Atualizada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criada')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('entity')
                    ->label('Entidade')
                    ->options([
                        'praia-norte' => 'Praia do Norte',
                        'carsurf' => 'Carsurf',
                        'nazare-qualifica' => 'Nazaré Qualifica',
                    ]),
                TernaryFilter::make('published')
                    ->label('Publicada'),
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
