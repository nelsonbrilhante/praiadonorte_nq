<?php

namespace App\Filament\Resources\Surfers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SurferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                // Content area (2/3)
                Grid::make(1)
                    ->columnSpan(['lg' => 2, 'default' => 'full'])
                    ->schema([
                        Section::make('Informação Básica')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('name')
                                                ->label('Nome')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn ($state, callable $set) =>
                                                    $set('slug', Str::slug($state))),
                                            TextInput::make('aka')
                                                ->label('Alcunha / aka')
                                                ->maxLength(255)
                                                ->placeholder('e.g. MAYA, AXI'),
                                        ]),
                                        Grid::make(2)->schema([
                                            TextInput::make('slug')
                                                ->required()
                                                ->unique(ignoreRecord: true)
                                                ->maxLength(255),
                                            TextInput::make('order')
                                                ->label('Ordem')
                                                ->numeric()
                                                ->default(0),
                                        ]),
                                    ]),

                                Section::make('Biografia')
                                    ->schema([
                                        Tabs::make('Bio Idiomas')
                                            ->tabs([
                                                Tab::make('Português')
                                                    ->icon('heroicon-o-flag')
                                                    ->schema([
                                                        RichEditor::make('bio.pt')
                                                            ->label('Biografia (PT)')
                                                            ->extraInputAttributes(['style' => 'min-height: 12rem'])
                                                            ->columnSpanFull(),
                                                    ]),
                                                Tab::make('English')
                                                    ->icon('heroicon-o-globe-alt')
                                                    ->schema([
                                                        RichEditor::make('bio.en')
                                                            ->label('Biography (EN)')
                                                            ->extraInputAttributes(['style' => 'min-height: 12rem'])
                                                            ->columnSpanFull(),
                                                    ]),
                                            ])
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Citação')
                                    ->schema([
                                        Tabs::make('Quote Idiomas')
                                            ->tabs([
                                                Tab::make('Português')
                                                    ->icon('heroicon-o-flag')
                                                    ->schema([
                                                        RichEditor::make('quote.pt')
                                                            ->label('Citação (PT)')
                                                            ->extraInputAttributes(['style' => 'min-height: 8rem'])
                                                            ->columnSpanFull(),
                                                    ]),
                                                Tab::make('English')
                                                    ->icon('heroicon-o-globe-alt')
                                                    ->schema([
                                                        RichEditor::make('quote.en')
                                                            ->label('Quote (EN)')
                                                            ->extraInputAttributes(['style' => 'min-height: 8rem'])
                                                            ->columnSpanFull(),
                                                    ]),
                                            ])
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                // Sidebar (1/3)
                Section::make('Publicação')
                    ->columnSpan(['lg' => 1, 'default' => 'full'])
                            ->schema([
                                Toggle::make('featured')
                                    ->label('Surfista em Destaque')
                                    ->default(false),
                                FileUpload::make('photo')
                                    ->label('Foto do Surfista')
                                    ->image()
                                    ->disk('public')
                                    ->directory('surfers')
                                    ->visibility('public'),
                                FileUpload::make('board_image')
                                    ->label('Imagem da Prancha')
                                    ->image()
                                    ->disk('public')
                                    ->directory('surfers/boards')
                                    ->visibility('public'),
                                Section::make('Redes Sociais')
                                    ->schema([
                                        TextInput::make('social_media.instagram')
                                            ->label('Instagram')
                                            ->placeholder('handle sem @')
                                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                                $set('social_media.instagram', $record?->social_media['instagram'] ?? $state)),
                                        TextInput::make('social_media.facebook')
                                            ->label('Facebook')
                                            ->placeholder('username ou page slug')
                                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                                $set('social_media.facebook', $record?->social_media['facebook'] ?? $state)),
                                        TextInput::make('social_media.twitter')
                                            ->label('Twitter / X')
                                            ->placeholder('handle sem @')
                                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                                $set('social_media.twitter', $record?->social_media['twitter'] ?? $state)),
                                    ])
                                    ->compact(),
                            ]),
            ]);
    }
}
