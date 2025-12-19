<?php

namespace App\Filament\Resources\Geral;

use App\Filament\Resources\Geral\Pages\EditHomepage;
use App\Filament\Resources\Geral\Pages\ListHomepages;
use App\Models\HeroSlide;
use App\Models\Pagina;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
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
                Section::make('Hero Slider')
                    ->description('Até 5 slides rotativos para o hero da homepage')
                    ->icon('heroicon-o-squares-2x2')
                    ->columnSpanFull()
                    ->schema([
                        // Global slider settings
                        Grid::make(2)->schema([
                            TextInput::make('slider_interval')
                                ->label('Intervalo (segundos)')
                                ->numeric()
                                ->default(8)
                                ->minValue(5)
                                ->maxValue(30)
                                ->helperText('Tempo entre slides (5-30s)'),
                            Toggle::make('slider_autoplay')
                                ->label('Auto-rotação')
                                ->default(true)
                                ->helperText('Pausa automaticamente quando há slide LIVE'),
                        ]),

                        // Slides repeater
                        Repeater::make('heroSlides')
                            ->relationship()
                            ->label('Slides')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('title.pt')
                                        ->label('Título (PT)')
                                        ->required(),
                                    TextInput::make('title.en')
                                        ->label('Title (EN)'),
                                ]),
                                Grid::make(2)->schema([
                                    TextInput::make('subtitle.pt')
                                        ->label('Subtítulo (PT)'),
                                    TextInput::make('subtitle.en')
                                        ->label('Subtitle (EN)'),
                                ]),
                                Grid::make(2)->schema([
                                    TextInput::make('cta_text.pt')
                                        ->label('Botão (PT)'),
                                    TextInput::make('cta_text.en')
                                        ->label('Button (EN)'),
                                ]),
                                Grid::make(2)->schema([
                                    TextInput::make('cta_url.pt')
                                        ->label('URL Botão (PT)'),
                                    TextInput::make('cta_url.en')
                                        ->label('Button URL (EN)'),
                                ]),

                                Section::make('Media')
                                    ->schema([
                                        TextInput::make('video_url')
                                            ->label('YouTube URL')
                                            ->url()
                                            ->placeholder('https://youtube.com/watch?v=...')
                                            ->columnSpanFull(),
                                        FileUpload::make('fallback_image')
                                            ->label('Imagem de Fallback')
                                            ->image()
                                            ->disk('public')
                                            ->directory('hero-slides')
                                            ->visibility('public')
                                            ->helperText('Mostrada quando não há vídeo'),
                                    ])->columns(2)->collapsed(),

                                Section::make('Logo Alternativo')
                                    ->schema([
                                        Toggle::make('use_logo_as_title')
                                            ->label('Usar logótipo em vez de título')
                                            ->live(),
                                        FileUpload::make('hero_logo')
                                            ->label('Logótipo')
                                            ->image()
                                            ->disk('public')
                                            ->directory('hero-slides')
                                            ->visibility('public')
                                            ->visible(fn ($get) => $get('use_logo_as_title')),
                                        TextInput::make('logo_height')
                                            ->label('Altura do logótipo (px)')
                                            ->numeric()
                                            ->default(120)
                                            ->minValue(80)
                                            ->maxValue(300)
                                            ->visible(fn ($get) => $get('use_logo_as_title')),
                                    ])->collapsed(),

                                Section::make('Live Stream')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Toggle::make('is_live')
                                                ->label('Em Direto')
                                                ->helperText('Badge LIVE + pausa rotação'),
                                            Toggle::make('audio_enabled')
                                                ->label('Áudio Disponível'),
                                        ]),
                                    ])->collapsed(),

                                Toggle::make('active')
                                    ->label('Slide Ativo')
                                    ->default(true),
                            ])
                            ->itemLabel(fn (array $state): ?string =>
                                $state['title']['pt'] ?? 'Novo Slide')
                            ->collapsible()
                            ->collapsed()
                            ->reorderable()
                            ->reorderableWithDragAndDrop()
                            ->orderColumn('order')
                            ->addActionLabel('Adicionar Slide')
                            ->deleteAction(
                                fn (Action $action) => $action
                                    ->requiresConfirmation()
                                    ->modalHeading('Eliminar Slide')
                                    ->modalDescription('Tem a certeza que deseja eliminar este slide? Esta ação não pode ser revertida.')
                                    ->modalSubmitActionLabel('Sim, eliminar')
                                    ->modalCancelActionLabel('Cancelar')
                                    ->after(function (array $arguments, Repeater $component) {
                                        $items = $component->getState();
                                        $itemKey = $arguments['item'];

                                        // Get the slide ID from the deleted item
                                        if (isset($items[$itemKey]['id'])) {
                                            $slideId = $items[$itemKey]['id'];
                                            HeroSlide::where('id', $slideId)->delete();
                                        }
                                    })
                            )
                            ->maxItems(5)
                            ->minItems(1)
                            ->defaultItems(1)
                            ->columnSpanFull(),
                    ]),

                Section::make('SEO')
                    ->columnSpanFull()
                    ->collapsed()
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
                    ]),
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
