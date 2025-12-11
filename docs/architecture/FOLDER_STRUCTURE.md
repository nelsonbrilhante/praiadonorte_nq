# Estrutura de Pastas

## Praia do Norte Unified Platform

Este documento define a estrutura oficial de pastas do projeto.

> **NOTA**: Arquitectura actualizada para monolítico Laravel + Blade (11 Dez 2025)

---

## Repositório Principal

```
praia-do-norte-unified/
├── backend/                             # Laravel 12 + Blade + Livewire (VPS)
├── frontend/                            # DEPRECATED (arquivado após migração)
├── docs/
│   ├── phases/                          # Guias de implementação
│   ├── architecture/                    # Documentação técnica
│   ├── tech-stack/                      # Guias de tecnologias
│   └── archive/                         # Documentos históricos
├── .github/workflows/                   # CI/CD
├── scripts/                             # Scripts de desenvolvimento
├── CLAUDE.md                            # Referência técnica principal
├── SESSION-HANDOFF.md                   # Continuidade entre sessões
├── PLANO_DESENVOLVIMENTO.md             # Plano de desenvolvimento
└── README.md
```

---

## Backend (Laravel 12 + Blade + Livewire)

Esta é a estrutura principal do projecto. O backend Laravel serve tanto o admin panel (Filament) como o site público (Blade).

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/                 # Web controllers (site público)
│   │   │   ├── HomeController.php
│   │   │   ├── NoticiaController.php
│   │   │   ├── EventoController.php
│   │   │   ├── SurferController.php
│   │   │   ├── ForecastController.php
│   │   │   ├── CarsurfController.php
│   │   │   ├── NazareQualificaController.php
│   │   │   ├── PageController.php
│   │   │   └── ContactController.php
│   │   │
│   │   ├── Controllers/Api/             # API controllers (legacy, pode remover)
│   │   │   ├── NoticiaController.php
│   │   │   ├── EventoController.php
│   │   │   ├── SurferController.php
│   │   │   └── PaginaController.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── LocalizationMiddleware.php
│   │   │   └── ...
│   │   │
│   │   └── Requests/
│   │       ├── ContactRequest.php
│   │       └── ...
│   │
│   ├── Livewire/                        # Livewire components
│   │   ├── LanguageSwitcher.php
│   │   ├── NewsFilter.php
│   │   ├── EventsFilter.php
│   │   ├── ContactForm.php
│   │   └── MobileMenu.php
│   │
│   ├── Filament/                        # Admin Panel
│   │   ├── Resources/
│   │   │   ├── NoticiaResource.php
│   │   │   ├── EventoResource.php
│   │   │   ├── SurferResource.php
│   │   │   ├── SurfboardResource.php
│   │   │   └── PaginaResource.php
│   │   │
│   │   ├── Resources/*/Schemas/         # Form schemas
│   │   │
│   │   └── Widgets/                     # Dashboard widgets
│   │       ├── StatsOverview.php
│   │       ├── LatestNoticias.php
│   │       └── UpcomingEventos.php
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Noticia.php
│   │   ├── Evento.php
│   │   ├── Surfer.php
│   │   ├── Surfboard.php
│   │   └── Pagina.php
│   │
│   ├── Services/
│   │   ├── ForecastService.php          # Open-Meteo API integration
│   │   └── ...
│   │
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── Filament/
│           └── AdminPanelProvider.php
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php            # Master layout
│   │   │
│   │   ├── components/
│   │   │   ├── layout/
│   │   │   │   ├── header.blade.php
│   │   │   │   ├── footer.blade.php
│   │   │   │   └── breadcrumbs.blade.php
│   │   │   │
│   │   │   └── ui/
│   │   │       ├── button.blade.php
│   │   │       ├── card.blade.php
│   │   │       ├── badge.blade.php
│   │   │       ├── input.blade.php
│   │   │       └── ...
│   │   │
│   │   ├── pages/
│   │   │   ├── home.blade.php
│   │   │   │
│   │   │   ├── noticias/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── show.blade.php
│   │   │   │
│   │   │   ├── eventos/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── show.blade.php
│   │   │   │
│   │   │   ├── surfer-wall/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── show.blade.php
│   │   │   │
│   │   │   ├── previsoes.blade.php
│   │   │   │
│   │   │   ├── carsurf/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── sobre.blade.php
│   │   │   │   └── programas.blade.php
│   │   │   │
│   │   │   ├── nazare-qualifica/
│   │   │   │   ├── sobre.blade.php
│   │   │   │   └── servicos.blade.php
│   │   │   │
│   │   │   ├── sobre.blade.php
│   │   │   └── contacto.blade.php
│   │   │
│   │   └── livewire/
│   │       ├── language-switcher.blade.php
│   │       ├── news-filter.blade.php
│   │       ├── events-filter.blade.php
│   │       ├── contact-form.blade.php
│   │       └── mobile-menu.blade.php
│   │
│   ├── css/
│   │   └── app.css                      # Tailwind CSS + custom styles
│   │
│   └── js/
│       └── app.js
│
├── lang/
│   ├── pt/
│   │   ├── messages.php                 # UI strings
│   │   ├── navigation.php
│   │   ├── news.php
│   │   ├── events.php
│   │   ├── surfers.php
│   │   └── common.php
│   │
│   └── en/
│       ├── messages.php
│       ├── navigation.php
│       └── ...
│
├── routes/
│   ├── web.php                          # Public routes (/{locale}/...)
│   └── api.php                          # API routes (legacy)
│
├── config/
│   ├── app.php
│   ├── localization.php                 # mcamara/laravel-localization
│   ├── filament.php
│   └── ...
│
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── NoticiaSeeder.php
│       ├── EventoSeeder.php
│       ├── SurferSeeder.php
│       ├── SurfboardSeeder.php
│       └── PaginaSeeder.php
│
├── storage/
│   ├── app/public/
│   │   ├── noticias/                    # News images
│   │   ├── eventos/                     # Event images
│   │   ├── surfers/                     # Surfer photos
│   │   ├── surfboards/                  # Surfboard images
│   │   └── paginas/                     # Page uploads
│   └── logs/
│
├── public/
│   ├── storage/                         # Symlink to storage/app/public
│   ├── pn-ai-wave-hero.png              # Hero image
│   ├── news-dummy.png                   # Placeholder images
│   └── build/                           # Vite compiled assets
│
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── .env
├── .env.example
├── artisan
├── composer.json
├── package.json
├── vite.config.js
├── tailwind.config.js
└── postcss.config.js
```

---

## Frontend DEPRECATED

> **IMPORTANTE**: A pasta `frontend/` contém o código Next.js que está a ser migrado para Blade.
> Manter como referência durante a conversão. Arquivar após conclusão da migração.

```
frontend/                                # DEPRECATED - referência para conversão
├── src/
│   ├── app/[locale]/                    # Páginas a converter para Blade
│   ├── components/                      # Componentes a converter
│   └── lib/api/                         # Tipos e funções (referência)
├── messages/                            # i18n a migrar para lang/
│   ├── pt.json
│   └── en.json
└── public/                              # Assets a copiar para backend/public/
```

---

## Estrutura de Rotas (web.php)

```php
// Redirect root to default locale
Route::get('/', fn() => redirect('/pt'));

// Localized routes
Route::prefix('{locale}')
    ->where(['locale' => 'pt|en'])
    ->middleware('localization')
    ->group(function () {

        // Homepage
        Route::get('/', [HomeController::class, 'index'])->name('home');

        // Notícias
        Route::get('/noticias', [NoticiaController::class, 'index'])->name('noticias.index');
        Route::get('/noticias/{slug}', [NoticiaController::class, 'show'])->name('noticias.show');

        // Eventos
        Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
        Route::get('/eventos/{slug}', [EventoController::class, 'show'])->name('eventos.show');

        // Surfer Wall
        Route::get('/surfer-wall', [SurferController::class, 'index'])->name('surfers.index');
        Route::get('/surfer-wall/{slug}', [SurferController::class, 'show'])->name('surfers.show');

        // Previsões
        Route::get('/previsoes', [ForecastController::class, 'index'])->name('forecast');

        // Carsurf
        Route::prefix('carsurf')->name('carsurf.')->group(function () {
            Route::get('/', [CarsurfController::class, 'index'])->name('index');
            Route::get('/sobre', [CarsurfController::class, 'sobre'])->name('sobre');
            Route::get('/programas', [CarsurfController::class, 'programas'])->name('programas');
        });

        // Nazaré Qualifica
        Route::prefix('nazare-qualifica')->name('nq.')->group(function () {
            Route::get('/sobre', [NazareQualificaController::class, 'sobre'])->name('sobre');
            Route::get('/servicos', [NazareQualificaController::class, 'servicos'])->name('servicos');
        });

        // Outras
        Route::get('/sobre', [PageController::class, 'sobre'])->name('sobre');
        Route::get('/contacto', [ContactController::class, 'index'])->name('contacto');
        Route::post('/contacto', [ContactController::class, 'send'])->name('contacto.send');
    });
```

---

## Documentação Relacionada

- [NAMING_CONVENTIONS.md](./NAMING_CONVENTIONS.md) - Padrões de nomenclatura
- [PLANO_DESENVOLVIMENTO.md](../../PLANO_DESENVOLVIMENTO.md) - Plano de desenvolvimento
- [CLAUDE.md](../../CLAUDE.md) - Referência técnica
- [SESSION-HANDOFF.md](../../SESSION-HANDOFF.md) - Continuidade entre sessões

---

*Actualizado: 11 Dezembro 2025 - Migração para arquitectura monolítica*
