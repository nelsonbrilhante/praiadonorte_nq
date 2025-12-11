<?php

namespace App\Filament\Resources\Geral;

use App\Filament\Resources\Geral\Pages\EditHomepage;
use App\Filament\Resources\Geral\Pages\ListHomepages;
use App\Models\Pagina;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class HomepageResource extends Resource
{
    protected static ?string $model = Pagina::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static string|\UnitEnum|null $navigationGroup = 'Geral';

    protected static ?int $navigationSort = 0;

    protected static ?string $navigationLabel = 'Homepage';

    protected static ?string $modelLabel = 'Homepage';

    protected static ?string $pluralModelLabel = 'Homepage';

    protected static ?string $slug = 'geral/homepage';

    /**
     * Only show the homepage record.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('slug', 'homepage');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero da Homepage')
                    ->description('Vídeo de fundo e textos principais')
                    ->icon('heroicon-o-play-circle')
                    ->schema([
                        TextInput::make('video_url')
                            ->label('YouTube URL')
                            ->url()
                            ->placeholder('https://www.youtube.com/watch?v=LDi6PQ4b6W8')
                            ->helperText('URL do vídeo YouTube para fundo do Hero')
                            ->columnSpanFull()
                            ->afterStateHydrated(fn ($state, $set, $record) =>
                                $set('video_url', $record?->video_url ?? $state)),

                        Grid::make(2)->schema([
                            TextInput::make('content.pt.hero.title')
                                ->label('Título (PT)')
                                ->required()
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.hero.title', $record?->content['pt']['hero']['title'] ?? $state)),
                            TextInput::make('content.en.hero.title')
                                ->label('Title (EN)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.en.hero.title', $record?->content['en']['hero']['title'] ?? $state)),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('content.pt.hero.subtitle')
                                ->label('Subtítulo (PT)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.hero.subtitle', $record?->content['pt']['hero']['subtitle'] ?? $state)),
                            TextInput::make('content.en.hero.subtitle')
                                ->label('Subtitle (EN)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.en.hero.subtitle', $record?->content['en']['hero']['subtitle'] ?? $state)),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('content.pt.hero.cta_text')
                                ->label('Texto Botão (PT)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.hero.cta_text', $record?->content['pt']['hero']['cta_text'] ?? $state)),
                            TextInput::make('content.en.hero.cta_text')
                                ->label('Button Text (EN)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.en.hero.cta_text', $record?->content['en']['hero']['cta_text'] ?? $state)),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('content.pt.hero.cta_url')
                                ->label('URL Botão (PT)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.pt.hero.cta_url', $record?->content['pt']['hero']['cta_url'] ?? $state)),
                            TextInput::make('content.en.hero.cta_url')
                                ->label('Button URL (EN)')
                                ->afterStateHydrated(fn ($state, $set, $record) =>
                                    $set('content.en.hero.cta_url', $record?->content['en']['hero']['cta_url'] ?? $state)),
                        ]),
                    ]),

                Section::make('SEO')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('seo_title.pt')
                                ->label('SEO Título (PT)')
                                ->maxLength(60),
                            TextInput::make('seo_title.en')
                                ->label('SEO Title (EN)')
                                ->maxLength(60),
                        ]),
                        Grid::make(2)->schema([
                            Textarea::make('seo_description.pt')
                                ->label('SEO Descrição (PT)')
                                ->rows(2)
                                ->maxLength(160),
                            Textarea::make('seo_description.en')
                                ->label('SEO Description (EN)')
                                ->rows(2)
                                ->maxLength(160),
                        ]),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title.pt')
                    ->label('Título')
                    ->searchable(),
                IconColumn::make('published')
                    ->label('Publicada')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Atualizada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHomepages::route('/'),
            'edit' => EditHomepage::route('/{record}/edit'),
        ];
    }
}
