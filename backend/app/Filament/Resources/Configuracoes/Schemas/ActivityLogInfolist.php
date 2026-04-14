<?php

namespace App\Filament\Resources\Configuracoes\Schemas;

use App\Models\ActivityLog;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Registo')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Data')
                            ->dateTime('d/m/Y H:i:s'),
                        TextEntry::make('description')
                            ->label('Descrição'),
                        TextEntry::make('causer.name')
                            ->label('Utilizador')
                            ->placeholder('Sistema'),
                        TextEntry::make('causer.email')
                            ->label('Email')
                            ->placeholder('—'),
                        TextEntry::make('log_name')
                            ->label('Categoria')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'auth' => 'Autenticação',
                                'deploy' => 'Deploy',
                                'default', null => 'Conteúdo',
                                default => ucfirst($state),
                            })
                            ->color(fn (?string $state): string => match ($state) {
                                'auth' => 'warning',
                                'deploy' => 'success',
                                default => 'info',
                            }),
                        TextEntry::make('event')
                            ->label('Evento')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'created' => 'Criado',
                                'updated' => 'Atualizado',
                                'deleted' => 'Eliminado',
                                'login' => 'Login',
                                'logout' => 'Logout',
                                'login_failed' => 'Login falhado',
                                'deploy' => 'Deploy',
                                null => '—',
                                default => ucfirst($state),
                            }),
                        TextEntry::make('subject_type')
                            ->label('Tipo de Conteúdo')
                            ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '—'),
                        TextEntry::make('subject_id')
                            ->label('ID do Conteúdo')
                            ->placeholder('—'),
                        TextEntry::make('ip_address')
                            ->label('Endereço IP')
                            ->placeholder('—'),
                        TextEntry::make('user_agent')
                            ->label('User Agent')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),

                Section::make('Alterações')
                    ->description('Valores antigos vs novos.')
                    ->schema([
                        ViewEntry::make('diff')
                            ->view('filament.activity-log.diff')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
