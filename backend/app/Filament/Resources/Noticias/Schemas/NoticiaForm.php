<?php

namespace App\Filament\Resources\Noticias\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                // Content area (2/3)
                Section::make('Conteúdo')
                    ->columnSpan(['lg' => 2, 'default' => 'full'])
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
                                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                                                    ->columnSpanFull(),
                                                RichEditor::make('content.pt')
                                                    ->label('Conteúdo (PT)')
                                                    ->required()
                                                    ->extraInputAttributes(['style' => 'min-height: 12rem'])
                                                    ->columnSpanFull(),
                                                Textarea::make('excerpt.pt')
                                                    ->label('Excerto (PT)')
                                                    ->rows(6)
                                                    ->columnSpanFull(),
                                            ]),
                                        Tab::make('English')
                                            ->icon('heroicon-o-globe-alt')
                                            ->schema([
                                                TextInput::make('title.en')
                                                    ->label('Title (EN)')
                                                    ->maxLength(255)
                                                    ->columnSpanFull(),
                                                RichEditor::make('content.en')
                                                    ->label('Content (EN)')
                                                    ->extraInputAttributes(['style' => 'min-height: 12rem'])
                                                    ->columnSpanFull(),
                                                Textarea::make('excerpt.en')
                                                    ->label('Excerpt (EN)')
                                                    ->rows(6)
                                                    ->columnSpanFull(),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ]),

                // Sidebar (1/3)
                Section::make('Publicação')
                    ->columnSpan(['lg' => 1, 'default' => 'full'])
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
                                TextInput::make('category')
                                    ->label('Categoria')
                                    ->maxLength(255),
                                TextInput::make('author')
                                    ->label('Autor')
                                    ->maxLength(255),
                                FileUpload::make('cover_image')
                                    ->label('Imagem de Capa')
                                    ->image()
                                    ->disk('public')
                                    ->directory('noticias')
                                    ->visibility('public'),
                                TagsInput::make('tags')
                                    ->label('Tags'),
                                Toggle::make('featured')
                                    ->label('Destaque')
                                    ->default(false),
                                DateTimePicker::make('published_at')
                                    ->label('Data de Publicação')
                                    ->default(now()),
                            ]),
            ]);
    }
}
