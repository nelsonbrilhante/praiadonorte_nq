<?php

namespace App\Filament\Resources\Surfers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
            ->components([
                Section::make('Informação Básica')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) =>
                                $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('nationality')
                            ->label('Nacionalidade')
                            ->maxLength(255),
                        TextInput::make('order')
                            ->label('Ordem')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),

                Section::make('Biografia')
                    ->schema([
                        Tabs::make('Bio Idiomas')
                            ->tabs([
                                Tab::make('Português')
                                    ->icon('heroicon-o-flag')
                                    ->schema([
                                        RichEditor::make('bio.pt')
                                            ->label('Biografia (PT)')
                                            ->columnSpanFull(),
                                    ]),
                                Tab::make('English')
                                    ->icon('heroicon-o-globe-alt')
                                    ->schema([
                                        RichEditor::make('bio.en')
                                            ->label('Biography (EN)')
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('Foto')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Foto do Surfista')
                            ->image()
                            ->directory('surfers')
                            ->columnSpanFull(),
                    ]),

                Section::make('Conquistas')
                    ->schema([
                        Repeater::make('achievements')
                            ->label('Conquistas/Prémios')
                            ->schema([
                                TextInput::make('pt')
                                    ->label('Conquista (PT)')
                                    ->required(),
                                TextInput::make('en')
                                    ->label('Achievement (EN)'),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),

                Section::make('Redes Sociais')
                    ->schema([
                        KeyValue::make('social_media')
                            ->label('Redes Sociais')
                            ->keyLabel('Plataforma')
                            ->valueLabel('URL/Username')
                            ->keyPlaceholder('instagram, facebook, twitter, youtube...')
                            ->valuePlaceholder('https://...')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

                Section::make('Opções')
                    ->schema([
                        Toggle::make('featured')
                            ->label('Surfista em Destaque')
                            ->default(false),
                    ]),
            ]);
    }
}
