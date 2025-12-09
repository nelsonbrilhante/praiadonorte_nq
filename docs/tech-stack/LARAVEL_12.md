# Laravel 12 - Referência Rápida

> Documentação de referência para Laravel 12 no projeto Praia do Norte.

---

## Versão Instalada

- **Laravel Framework**: 12.41.1
- **PHP**: 8.5.0 (local) / 8.3 (produção VPS)
- **Documentação oficial**: https://laravel.com/docs/12.x

---

## Comandos Artisan Frequentes

### Desenvolvimento

```bash
# Iniciar servidor de desenvolvimento
php artisan serve

# Limpar caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Limpar tudo
php artisan optimize:clear
```

### Database & Migrations

```bash
# Criar migration
php artisan make:migration create_noticias_table

# Executar migrations
php artisan migrate

# Rollback
php artisan migrate:rollback

# Fresh (reset + migrate)
php artisan migrate:fresh

# Com seeders
php artisan migrate:fresh --seed
```

### Models & Resources

```bash
# Criar model com migration
php artisan make:model Noticia -m

# Criar model completo (migration, factory, seeder, controller)
php artisan make:model Noticia -mfsc

# Criar controller API
php artisan make:controller Api/NoticiaController --api
```

---

## Estrutura de Pastas

```
backend/
├── app/
│   ├── Console/           # Comandos Artisan customizados
│   ├── Exceptions/        # Exception handlers
│   ├── Filament/          # Filament resources, pages, widgets
│   ├── Http/
│   │   ├── Controllers/   # Controllers (API, Web)
│   │   ├── Middleware/    # HTTP Middleware
│   │   └── Requests/      # Form Requests (validação)
│   ├── Models/            # Eloquent Models
│   ├── Providers/         # Service Providers
│   └── Services/          # Business logic (Easypay, etc.)
├── config/                # Configurações
├── database/
│   ├── factories/         # Model Factories
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── routes/
│   ├── api.php            # Rotas API (prefix /api)
│   ├── console.php        # Artisan routes
│   └── web.php            # Web routes
└── storage/               # Logs, uploads, cache
```

---

## Padrões do Projeto

### Models com i18n (JSON)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $fillable = [
        'title',      // JSON: {"pt": "...", "en": "..."}
        'content',    // JSON
        'slug',
        'cover_image',
        'entity',
        'featured',
        'published_at',
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
        'featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Accessor para título na locale atual
    public function getLocalizedTitle(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $this->title[$locale] ?? $this->title['pt'] ?? '';
    }
}
```

### API Controller

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Noticia;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->get('locale', 'pt');
        $limit = $request->get('limit', 10);

        $noticias = Noticia::query()
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'data' => $noticias,
            'locale' => $locale,
        ]);
    }

    public function show(string $slug)
    {
        $noticia = Noticia::where('slug', $slug)->firstOrFail();
        return response()->json(['data' => $noticia]);
    }
}
```

### API Routes

```php
// routes/api.php
use App\Http\Controllers\Api\NoticiaController;
use App\Http\Controllers\Api\SurferController;
use App\Http\Controllers\Api\EventoController;

Route::prefix('v1')->group(function () {
    // Notícias
    Route::get('/noticias', [NoticiaController::class, 'index']);
    Route::get('/noticias/{slug}', [NoticiaController::class, 'show']);

    // Surfers
    Route::get('/surfers', [SurferController::class, 'index']);
    Route::get('/surfers/{slug}', [SurferController::class, 'show']);

    // Eventos
    Route::get('/eventos', [EventoController::class, 'index']);
});
```

---

## Configuração CORS

Ficheiro: `config/cors.php`

```php
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:3000',
        'https://praiadonortenazare.pt',
        'https://www.praiadonortenazare.pt',
    ],
    'allowed_headers' => ['*'],
    'supports_credentials' => true,
];
```

---

## Environment Variables (.env)

```env
APP_NAME="Praia do Norte"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
# DB_CONNECTION=mysql (produção)
# DB_HOST=localhost
# DB_DATABASE=praia_do_norte
# DB_USERNAME=
# DB_PASSWORD=

# Sanctum (futuro)
SANCTUM_STATEFUL_DOMAINS=localhost:3000

# Easypay (futuro - NUNCA commitar)
# EASYPAY_ACCOUNT_ID=
# EASYPAY_API_KEY=
```

---

## Links Úteis

- [Laravel 12 Documentation](https://laravel.com/docs/12.x)
- [Laravel API Resources](https://laravel.com/docs/12.x/eloquent-resources)
- [Laravel Sanctum](https://laravel.com/docs/12.x/sanctum)
- [Laravel Validation](https://laravel.com/docs/12.x/validation)
