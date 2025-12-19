<?php

namespace App\Filament\Resources\Noticias\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NoticiaForm
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
                                        RichEditor::make('content.pt')
                                            ->label('Conteúdo (PT)')
                                            ->required()
                                            ->columnSpanFull(),
                                        Textarea::make('excerpt.pt')
                                            ->label('Excerto (PT)')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                                Tab::make('English')
                                    ->icon('heroicon-o-globe-alt')
                                    ->schema([
                                        TextInput::make('title.en')
                                            ->label('Title (EN)')
                                            ->maxLength(255),
                                        RichEditor::make('content.en')
                                            ->label('Content (EN)')
                                            ->columnSpanFull(),
                                        Textarea::make('excerpt.en')
                                            ->label('Excerpt (EN)')
                                            ->rows(3)
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

                Section::make('Media & Metadados')
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label('Imagem de Capa')
                            ->image()
                            ->disk('public')
                            ->directory('noticias')
                            ->visibility('public')
                            ->columnSpanFull(),
                        Grid::make(3)
                            ->schema([
                                TextInput::make('author')
                                    ->label('Autor')
                                    ->maxLength(255),
                                TextInput::make('category')
                                    ->label('Categoria')
                                    ->maxLength(255),
                                Select::make('entity')
                                    ->label('Entidade')
                                    ->options([
                                        'praia-norte' => 'Praia do Norte',
                                        'carsurf' => 'Carsurf',
                                        'nazare-qualifica' => 'Nazaré Qualifica',
                                    ])
                                    ->default('praia-norte')
                                    ->required(),
                            ]),
                        TagsInput::make('tags')
                            ->label('Tags')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Publicação')
                    ->schema([
                        Toggle::make('featured')
                            ->label('Destaque')
                            ->default(false),
                        DateTimePicker::make('published_at')
                            ->label('Data de Publicação')
                            ->default(now()),
                    ])
                    ->columns(2),

                Section::make('SEO')
                    ->schema([
                        Tabs::make('SEO Idiomas')
                            ->tabs([
                                Tab::make('PT')
                                    ->schema([
                                        TextInput::make('seo_title.pt')
                                            ->label('SEO Título (PT)')
                                            ->maxLength(60),
                                        Textarea::make('seo_description.pt')
                                            ->label('SEO Descrição (PT)')
                                            ->rows(2)
                                            ->maxLength(160),
                                    ]),
                                Tab::make('EN')
                                    ->schema([
                                        TextInput::make('seo_title.en')
                                            ->label('SEO Title (EN)')
                                            ->maxLength(60),
                                        Textarea::make('seo_description.en')
                                            ->label('SEO Description (EN)')
                                            ->rows(2)
                                            ->maxLength(160),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }
}
