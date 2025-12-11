# Fase 6: E-commerce Setup

**Status**: ⏳ Pendente (Aguarda Decisão API SAGE)
**Dependências**: Fase 5 + Decisão API SAGE
**Bloco**: 4 - E-commerce

> **IMPORTANTE**: Esta fase aguarda análise da documentação API SAGE para decidir entre:
> - **Opção A**: WooCommerce headless para gestão de produtos + Laravel para checkout
> - **Opção B**: Laravel nativo com Easypay (produtos geridos em Filament)
> - **Opção C**: Integração directa com API SAGE para stock/facturação

---

## Vantagens da Arquitectura Monolítica para E-commerce

| Aspecto | Benefício |
|---------|-----------|
| **Easypay** | Integração directa via PHP SDK, sem camada API |
| **Sessions** | Carrinho persistente via sessions (mais seguro) |
| **Checkout** | Server-side rendering, sem exposição de preços no cliente |
| **Webhooks** | Recepção directa no Laravel |
| **SAGE** | Conexão directa ao ERP via PHP |

---

## Objetivos

- Decidir abordagem e-commerce (WooCommerce vs Laravel nativo)
- Configurar estrutura de produtos
- Preparar integração com Easypay
- Planear sincronização SAGE (se aplicável)

---

## Tarefas

### 6.1 Estrutura de Produtos (Filament)

Se opção **Laravel nativo**, criar Resource para produtos no Filament:

**Model**: `app/Models/Product.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',           // JSON: pt, en
        'description',    // JSON: pt, en
        'price',
        'compare_price',
        'stock',
        'images',
        'category_id',
        'entity',
        'featured',
        'active',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'images' => 'array',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'featured' => 'boolean',
        'active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function getTranslation(string $field, string $locale): string
    {
        return $this->{$field}[$locale] ?? $this->{$field}['pt'] ?? '';
    }
}
```

**Filament Resource**: `app/Filament/Resources/ProductResource.php`

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'E-commerce';
    protected static ?string $modelLabel = 'Produto';
    protected static ?string $pluralModelLabel = 'Produtos';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identificação')->schema([
                Forms\Components\TextInput::make('sku')
                    ->required()
                    ->unique(ignoreRecord: true),
            ]),

            Forms\Components\Tabs::make('Traduções')->tabs([
                Forms\Components\Tabs\Tab::make('Português')->schema([
                    Forms\Components\TextInput::make('name.pt')
                        ->label('Nome')
                        ->required(),
                    Forms\Components\RichEditor::make('description.pt')
                        ->label('Descrição'),
                ]),
                Forms\Components\Tabs\Tab::make('English')->schema([
                    Forms\Components\TextInput::make('name.en')
                        ->label('Name'),
                    Forms\Components\RichEditor::make('description.en')
                        ->label('Description'),
                ]),
            ]),

            Forms\Components\Section::make('Preços & Stock')->schema([
                Forms\Components\TextInput::make('price')
                    ->label('Preço')
                    ->numeric()
                    ->prefix('€')
                    ->required(),
                Forms\Components\TextInput::make('compare_price')
                    ->label('Preço Original')
                    ->numeric()
                    ->prefix('€'),
                Forms\Components\TextInput::make('stock')
                    ->numeric()
                    ->default(0),
            ])->columns(3),

            Forms\Components\Section::make('Imagens')->schema([
                Forms\Components\FileUpload::make('images')
                    ->multiple()
                    ->image()
                    ->directory('products'),
            ]),

            Forms\Components\Section::make('Organização')->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Categoria'),
                Forms\Components\Select::make('entity')
                    ->options([
                        'praia-norte' => 'Praia do Norte',
                        'carsurf' => 'Carsurf',
                        'nazare-qualifica' => 'Nazaré Qualifica',
                    ])
                    ->default('praia-norte'),
                Forms\Components\Toggle::make('featured')
                    ->label('Destaque'),
                Forms\Components\Toggle::make('active')
                    ->label('Activo')
                    ->default(true),
            ])->columns(2),
        ]);
    }
}
```

---

### 6.2 Categorias de Produtos

**Migration**: `database/migrations/create_product_categories_table.php`

```php
Schema::create('product_categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->unsignedBigInteger('parent_id')->nullable();
    $table->integer('sort_order')->default(0);
    $table->timestamps();

    $table->foreign('parent_id')->references('id')->on('product_categories');
});
```

**Estrutura de Categorias**:

```
Loja Praia do Norte
├── Vestuário
│   ├── T-Shirts
│   ├── Hoodies
│   └── Caps
├── Acessórios
│   ├── Stickers
│   └── Patches
├── Equipamento
│   ├── Pranchas (showcase)
│   └── Fatos
└── Colecionáveis
    └── Edições Limitadas
```

---

### 6.3 Preparação Easypay

**Configuração** (`.env`):

```env
EASYPAY_ACCOUNT_ID=your_account_id
EASYPAY_API_KEY=your_api_key
EASYPAY_BASE_URL=https://api.prod.easypay.pt/2.0
EASYPAY_WEBHOOK_SECRET=your_webhook_secret
```

**Service Provider**: `app/Providers/EasypayServiceProvider.php`

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\EasypayService;

class EasypayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EasypayService::class, function ($app) {
            return new EasypayService(
                config('services.easypay.account_id'),
                config('services.easypay.api_key'),
                config('services.easypay.base_url')
            );
        });
    }
}
```

---

### 6.4 Decisão Pendente: SAGE

A integração com SAGE determinará:

1. **Origem dos produtos**: SAGE (sync) vs Filament (manual)
2. **Gestão de stock**: SAGE (automático) vs Filament (manual)
3. **Facturação**: SAGE (automática) vs manual

**Cenários**:

| Cenário | Produtos | Stock | Facturação |
|---------|----------|-------|------------|
| **A** - SAGE completo | SAGE → Laravel | SAGE → Laravel | Laravel → SAGE |
| **B** - SAGE facturação | Filament | Filament | Laravel → SAGE |
| **C** - Sem SAGE | Filament | Filament | Easypay recibos |

---

## Entregáveis

- [ ] Decisão arquitectura e-commerce (SAGE vs nativo)
- [ ] Product model e migration
- [ ] ProductCategory model e migration
- [ ] Filament ProductResource
- [ ] Categorias de produtos criadas
- [ ] Configuração Easypay preparada
- [ ] Produtos de teste inseridos (PT + EN)

---

## Critérios de Conclusão

1. Decisão documentada sobre integração SAGE
2. Filament admin permite criar/editar produtos
3. Produtos com traduções PT/EN
4. Categorias hierárquicas funcionam
5. Pelo menos 5 produtos de teste inseridos

---

## Próxima Fase

→ [Fase 7: Catálogo](./FASE_07_CATALOGO.md)

---

*Actualizado: 11 Dezembro 2025 - Arquitectura monolítica*
