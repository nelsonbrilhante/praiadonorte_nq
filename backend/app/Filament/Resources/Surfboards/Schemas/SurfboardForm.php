<?php

namespace App\Filament\Resources\Surfboards\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SurfboardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informação da Prancha')
                    ->schema([
                        Select::make('surfer_id')
                            ->label('Surfista')
                            ->relationship('surfer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('brand')
                            ->label('Marca')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('model')
                            ->label('Modelo')
                            ->maxLength(255),
                        TextInput::make('length')
                            ->label('Comprimento')
                            ->placeholder("6'2\"")
                            ->maxLength(50),
                        TextInput::make('order')
                            ->label('Ordem')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),

                Section::make('Imagem')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Foto da Prancha')
                            ->image()
                            ->directory('surfboards')
                            ->columnSpanFull(),
                    ]),

                Section::make('Especificações')
                    ->schema([
                        KeyValue::make('specs')
                            ->label('Especificações Técnicas')
                            ->keyLabel('Especificação')
                            ->valueLabel('Valor')
                            ->keyPlaceholder('width, thickness, volume, fins...')
                            ->valuePlaceholder('19", 2.5", 32L...')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }
}
