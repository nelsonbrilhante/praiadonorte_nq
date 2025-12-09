<?php

namespace App\Filament\Resources\Noticias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class NoticiasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Imagem')
                    ->circular(),
                TextColumn::make('title.pt')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('category')
                    ->label('Categoria')
                    ->badge()
                    ->searchable(),
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
                TextColumn::make('published_at')
                    ->label('Publicado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
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
