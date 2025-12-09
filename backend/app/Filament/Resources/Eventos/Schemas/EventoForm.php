<?php

namespace App\Filament\Resources\Eventos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class EventoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Conteúdo')
                    ->schema([
                        Tabs::make('Idiomas')
                            ->tabs([
                                Tab::make('Português')
                                    ->icon('heroicon-o-flag')
                                    ->schema([
                                        TextInput::make('title.pt')
                                            ->label('Título (PT)')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, callable $set) =>
                                                $set('slug', Str::slug($state))),
                                        RichEditor::make('description.pt')
                                            ->label('Descrição (PT)')
                                            ->columnSpanFull(),
                                    ]),
                                Tab::make('English')
                                    ->icon('heroicon-o-globe-alt')
                                    ->schema([
                                        TextInput::make('title.en')
                                            ->label('Title (EN)')
                                            ->maxLength(255),
                                        RichEditor::make('description.en')
                                            ->label('Description (EN)')
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Datas e Local')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Data de Início')
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('Data de Fim'),
                        TextInput::make('location')
                            ->label('Local')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Media e Metadados')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Imagem do Evento')
                            ->image()
                            ->directory('eventos')
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                Select::make('entity')
                                    ->label('Entidade')
                                    ->options([
                                        'praia-norte' => 'Praia do Norte',
                                        'carsurf' => 'Carsurf',
                                        'nazare-qualifica' => 'Nazaré Qualifica',
                                    ])
                                    ->default('praia-norte')
                                    ->required(),
                                TextInput::make('ticket_url')
                                    ->label('URL dos Bilhetes')
                                    ->url()
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make('Opções')
                    ->schema([
                        Toggle::make('featured')
                            ->label('Evento em Destaque')
                            ->default(false),
                    ]),
            ]);
    }
}
