# Filament 4.x - Referência Rápida

> Documentação de referência para Filament 4.x no projeto Praia do Norte.

---

## Versão Instalada

- **Filament**: 4.2.4
- **Livewire**: 3.7.1
- **Documentação oficial**: https://filamentphp.com/docs/4.x

---

## Comandos Artisan

```bash
# Criar usuário admin
php artisan make:filament-user

# Criar Resource (CRUD)
php artisan make:filament-resource Noticia

# Criar Resource com soft deletes
php artisan make:filament-resource Noticia --soft-deletes

# Criar Page customizada
php artisan make:filament-page Dashboard

# Criar Widget
php artisan make:filament-widget StatsOverview

# Publicar assets
php artisan filament:assets
```

---

## Acesso ao Admin Panel

- **URL**: `/admin`
- **Provider**: `app/Providers/Filament/AdminPanelProvider.php`

---

## Estrutura de Resources

```
app/Filament/
├── Resources/
│   ├── NoticiaResource.php
│   ├── NoticiaResource/
│   │   └── Pages/
│   │       ├── CreateNoticia.php
│   │       ├── EditNoticia.php
│   │       └── ListNoticias.php
│   ├── SurferResource.php
│   └── EventoResource.php
├── Pages/
│   └── Dashboard.php
└── Widgets/
    └── StatsOverview.php
```

---

## Exemplo de Resource com i18n

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoticiaResource\Pages;
use App\Models\Noticia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NoticiaResource extends Resource
{
    protected static ?string $model = Noticia::class;
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Conteúdo';
    protected static ?int $navigationSort = 1;

    // Labels em Português
    protected static ?string $modelLabel = 'Notícia';
    protected static ?string $pluralModelLabel = 'Notícias';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Notícia')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Português')
                        ->schema([
                            Forms\Components\TextInput::make('title.pt')
                                ->label('Título')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\RichEditor::make('content.pt')
                                ->label('Conteúdo')
                                ->columnSpanFull(),
                        ]),
                    Forms\Components\Tabs\Tab::make('English')
                        ->schema([
                            Forms\Components\TextInput::make('title.en')
                                ->label('Title')
                                ->maxLength(255),
                            Forms\Components\RichEditor::make('content.en')
                                ->label('Content')
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull(),

            Forms\Components\Section::make('Media & Configurações')
                ->schema([
                    Forms\Components\FileUpload::make('cover_image')
                        ->label('Imagem de Capa')
                        ->image()
                        ->directory('noticias')
                        ->columnSpan(1),

                    Forms\Components\Select::make('entity')
                        ->label('Entidade')
                        ->options([
                            'praia-norte' => 'Praia do Norte',
                            'carsurf' => 'Carsurf',
                            'nazare-qualifica' => 'Nazaré Qualifica',
                        ])
                        ->required(),

                    Forms\Components\Toggle::make('featured')
                        ->label('Destaque'),

                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('Data de Publicação')
                        ->default(now()),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Capa'),
                Tables\Columns\TextColumn::make('title.pt')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('entity')
                    ->label('Entidade')
                    ->colors([
                        'primary' => 'praia-norte',
                        'warning' => 'nazare-qualifica',
                        'success' => 'carsurf',
                    ]),
                Tables\Columns\IconColumn::make('featured')
                    ->label('Destaque')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publicação')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('entity')
                    ->options([
                        'praia-norte' => 'Praia do Norte',
                        'carsurf' => 'Carsurf',
                        'nazare-qualifica' => 'Nazaré Qualifica',
                    ]),
                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Destaque'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNoticias::route('/'),
            'create' => Pages\CreateNoticia::route('/create'),
            'edit' => Pages\EditNoticia::route('/{record}/edit'),
        ];
    }
}
```

---

## Form Components Mais Usados

```php
use Filament\Forms\Components;

// Texto
Components\TextInput::make('name')->required();
Components\Textarea::make('description');
Components\RichEditor::make('content');

// Seleção
Components\Select::make('status')->options([...]);
Components\Toggle::make('active');
Components\Checkbox::make('agree');

// Data/Hora
Components\DatePicker::make('date');
Components\DateTimePicker::make('datetime');

// Upload
Components\FileUpload::make('image')->image();

// Layout
Components\Section::make('Título')->schema([...]);
Components\Tabs::make('Tabs')->tabs([...]);
Components\Grid::make(2)->schema([...]);
```

---

## Table Columns Mais Usadas

```php
use Filament\Tables\Columns;

Columns\TextColumn::make('name')->searchable()->sortable();
Columns\ImageColumn::make('avatar');
Columns\IconColumn::make('active')->boolean();
Columns\BadgeColumn::make('status')->colors([...]);
Columns\TextColumn::make('created_at')->dateTime('d/m/Y');
```

---

## Configuração do Panel

Ficheiro: `app/Providers/Filament/AdminPanelProvider.php`

```php
<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => '#0066cc', // Ocean blue (Praia do Norte)
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->pages([
                \Filament\Pages\Dashboard::class,
            ])
            ->middleware([
                // ...
            ])
            ->authMiddleware([
                \Filament\Http\Middleware\Authenticate::class,
            ]);
    }
}
```

---

## Links Úteis

- [Filament 4.x Documentation](https://filamentphp.com/docs/4.x)
- [Form Builder](https://filamentphp.com/docs/4.x/forms)
- [Table Builder](https://filamentphp.com/docs/4.x/tables)
- [Resources](https://filamentphp.com/docs/4.x/resources)
- [Plugins](https://filamentphp.com/plugins)
