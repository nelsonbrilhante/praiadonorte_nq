<?php

namespace App\Filament\Resources\Eventos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                        Textarea::make('excerpt.pt')
                                            ->label('Resumo (PT)')
                                            ->rows(2)
                                            ->maxLength(300)
                                            ->helperText('Breve descrição para listagens (máx. 300 caracteres)'),
                                        RichEditor::make('description.pt')
                                            ->label('Descrição (PT)')
                                            ->columnSpanFull(),
                                        RichEditor::make('schedule.pt')
                                            ->label('Programa (PT)')
                                            ->columnSpanFull(),
                                    ]),
                                Tab::make('English')
                                    ->icon('heroicon-o-globe-alt')
                                    ->schema([
                                        TextInput::make('title.en')
                                            ->label('Title (EN)')
                                            ->maxLength(255),
                                        Textarea::make('excerpt.en')
                                            ->label('Excerpt (EN)')
                                            ->rows(2)
                                            ->maxLength(300)
                                            ->helperText('Short description for listings (max 300 chars)'),
                                        RichEditor::make('description.en')
                                            ->label('Description (EN)')
                                            ->columnSpanFull(),
                                        RichEditor::make('schedule.en')
                                            ->label('Schedule (EN)')
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

                Section::make('Media')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Imagem Principal')
                            ->image()
                            ->disk('public')
                            ->directory('eventos')
                            ->visibility('public')
                            ->columnSpanFull(),
                        FileUpload::make('gallery')
                            ->label('Galeria de Fotos')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->disk('public')
                            ->directory('eventos/gallery')
                            ->visibility('public')
                            ->columnSpanFull(),
                        TextInput::make('video_url')
                            ->label('URL do Vídeo (YouTube/Vimeo embed)')
                            ->url()
                            ->maxLength(500)
                            ->placeholder('https://www.youtube.com/embed/...')
                            ->columnSpanFull(),
                    ]),

                Section::make('Metadados')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('entity')
                                    ->label('Entidade')
                                    ->options(function () {
                                        $all = [
                                            'praia-norte' => 'Praia do Norte',
                                            'carsurf' => 'Carsurf',
                                            'nazare-qualifica' => 'Nazaré Qualifica',
                                        ];
                                        $allowed = auth()->user()->getAllowedEntities();

                                        return $allowed === null
                                            ? $all
                                            : array_intersect_key($all, array_flip($allowed));
                                    })
                                    ->default(function () {
                                        $allowed = auth()->user()->getAllowedEntities();

                                        return $allowed === null ? 'praia-norte' : ($allowed[0] ?? 'praia-norte');
                                    })
                                    ->disabled(fn () => auth()->user()->isEntityEditor() && count(auth()->user()->getAllowedEntities()) === 1)
                                    ->dehydrated()
                                    ->required(),
                                Select::make('category')
                                    ->label('Categoria')
                                    ->options([
                                        'Surf' => 'Surf',
                                        'Formação' => 'Formação',
                                        'Conferência' => 'Conferência',
                                        'Cultura' => 'Cultura',
                                        'Ambiental' => 'Ambiental',
                                        'Institucional' => 'Institucional',
                                    ])
                                    ->searchable(),
                            ]),
                        TextInput::make('ticket_url')
                            ->label('URL dos Bilhetes')
                            ->url()
                            ->maxLength(255),
                    ]),

                Section::make('Parceiros')
                    ->schema([
                        Repeater::make('partners')
                            ->label('Parceiros do Evento')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nome')
                                    ->required()
                                    ->maxLength(255),
                                FileUpload::make('logo')
                                    ->label('Logo')
                                    ->image()
                                    ->disk('public')
                                    ->directory('eventos/partners')
                                    ->visibility('public'),
                                TextInput::make('url')
                                    ->label('Website')
                                    ->url()
                                    ->maxLength(255),
                                Select::make('type')
                                    ->label('Tipo')
                                    ->options([
                                        'premium' => 'Premium',
                                        'institutional' => 'Institucional',
                                        'media' => 'Media',
                                    ])
                                    ->default('institutional'),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Opções')
                    ->schema([
                        Toggle::make('featured')
                            ->label('Evento em Destaque')
                            ->default(false),
                    ]),
            ]);
    }
}
