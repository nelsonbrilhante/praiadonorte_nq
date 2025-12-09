<?php

namespace App\Filament\Resources\Paginas\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PaginaForm
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
                                    ]),
                            ])
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(
                                table: 'paginas',
                                column: 'slug',
                                ignoreRecord: true,
                                modifyRuleUsing: fn ($rule, $get) => $rule->where('entity', $get('entity'))
                            ),
                        Select::make('entity')
                            ->label('Entidade')
                            ->options([
                                'praia-norte' => 'Praia do Norte',
                                'carsurf' => 'Carsurf',
                                'nazare-qualifica' => 'Nazaré Qualifica',
                            ])
                            ->default('praia-norte')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Publicação')
                    ->schema([
                        Toggle::make('published')
                            ->label('Publicada')
                            ->default(true),
                    ]),

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
