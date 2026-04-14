<?php

namespace App\Filament\Resources\Configuracoes\Tables;

use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Ação')
                    ->searchable()
                    ->limit(60),
                TextColumn::make('causer.name')
                    ->label('Utilizador')
                    ->searchable()
                    ->default('—')
                    ->placeholder('Sistema'),
                TextColumn::make('subject_type')
                    ->label('Tipo')
                    ->formatStateUsing(fn (?string $state): string => self::formatSubjectType($state))
                    ->badge(),
                TextColumn::make('log_name')
                    ->label('Categoria')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => self::formatLogName($state))
                    ->color(fn (?string $state): string => match ($state) {
                        'auth' => 'warning',
                        'deploy' => 'success',
                        'default' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('log_name')
                    ->label('Categoria')
                    ->options([
                        'auth' => 'Autenticação',
                        'default' => 'Conteúdo',
                        'deploy' => 'Deploy',
                    ]),
                SelectFilter::make('causer_id')
                    ->label('Utilizador')
                    ->relationship('causer', 'name'),
                SelectFilter::make('subject_type')
                    ->label('Tipo de Conteúdo')
                    ->options([
                        'App\\Models\\Noticia' => 'Notícias',
                        'App\\Models\\Evento' => 'Eventos',
                        'App\\Models\\Surfer' => 'Surfers',
                        'App\\Models\\Pagina' => 'Páginas',
                        'App\\Models\\HeroSlide' => 'Hero Slides',
                        'App\\Models\\Document' => 'Documentos',
                        'App\\Models\\DocumentCategory' => 'Cat. Documentos',
                        'App\\Models\\CorporateBody' => 'Órgãos Sociais',
                        'App\\Models\\ContactMessage' => 'Mensagens',
                        'App\\Models\\ContraOrdenacaoDocument' => 'Docs. Contraordenação',
                        'App\\Models\\SiteSetting' => 'Definições',
                        'App\\Models\\User' => 'Utilizadores',
                    ]),
                SelectFilter::make('event')
                    ->label('Evento')
                    ->options([
                        'created' => 'Criado',
                        'updated' => 'Atualizado',
                        'deleted' => 'Eliminado',
                        'login' => 'Login',
                        'logout' => 'Logout',
                        'login_failed' => 'Login falhado',
                    ]),
                Filter::make('created_at')
                    ->label('Período')
                    ->schema([
                        DatePicker::make('from')->label('De'),
                        DatePicker::make('until')->label('Até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }

    protected static function formatSubjectType(?string $state): string
    {
        return match ($state) {
            'App\\Models\\Noticia' => 'Notícia',
            'App\\Models\\Evento' => 'Evento',
            'App\\Models\\Surfer' => 'Surfer',
            'App\\Models\\Pagina' => 'Página',
            'App\\Models\\HeroSlide' => 'Hero Slide',
            'App\\Models\\Document' => 'Documento',
            'App\\Models\\DocumentCategory' => 'Cat. Documento',
            'App\\Models\\CorporateBody' => 'Órgão Social',
            'App\\Models\\ContactMessage' => 'Mensagem',
            'App\\Models\\ContraOrdenacaoDocument' => 'Doc. C.O.',
            'App\\Models\\SiteSetting' => 'Definição',
            'App\\Models\\User' => 'Utilizador',
            null, '' => '—',
            default => class_basename($state),
        };
    }

    protected static function formatLogName(?string $state): string
    {
        return match ($state) {
            'auth' => 'Autenticação',
            'deploy' => 'Deploy',
            'default', null => 'Conteúdo',
            default => ucfirst($state),
        };
    }
}
