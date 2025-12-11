# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

> **Status: Architecture Migration - Next.js → Laravel Blade**
>
> Migrating from split architecture (Next.js + Laravel API) to monolithic Laravel (Blade + Livewire).
> All content pages being converted to Blade views with pixel-perfect design preservation.
> Motivation: Eliminate API complexity, improve security, simplify deployment.

## Project Overview

**Praia do Norte Unified Platform** - A unified e-commerce and institutional website integrating three Portuguese municipal entities:
- **Praia do Norte** (primary brand) - World-renowned big wave surfing destination
- **Carsurf** - High-performance surf training center
- **Nazaré Qualifica** - Municipal infrastructure management company

**Critical Design Principle**: Praia do Norte is the central focus of the website. While the platform unifies three entities, all architecture, content hierarchy, and user experience decisions must prioritize Praia do Norte's visibility and e-commerce objectives.

## Quick Reference (Critical Constraints)

| Constraint | Requirement |
|------------|-------------|
| **Primary Brand** | Praia do Norte is always the focus |
| **Payment Processing** | Server-side ONLY (Laravel) |
| **Languages** | PT (primary) + EN from day one |
| **Architecture** | Monolithic Laravel (single codebase) |
| **Frontend** | Blade templates + Livewire |
| **Security** | OWASP Top 10 mitigations mandatory |

## Project Status

**Current Phase**: Architecture Migration (Blade conversion)

- [x] Backend: Laravel 12.41.1 + Filament 4.2.4 installed
- [x] Backend CMS: All Filament Resources (Noticias, Eventos, Surfers, Surfboards, Paginas)
- [x] Database: All migrations and seeders complete
- [x] Admin UX: Navigation groups, distinct icons, dashboard widgets, Navy Blue theme
- [ ] **IN PROGRESS**: Blade templates migration (from Next.js)
- [ ] Livewire components for interactivity
- [ ] Tailwind CSS setup in Laravel
- [ ] i18n with Laravel localization
- [ ] SEO metadata, performance optimization

## Technical Architecture

### Core Stack Decision

The project uses a **monolithic architecture** on a single VPS (vm01.cm-nazare.pt):

- **Backend + Frontend**: Laravel 12 + Blade + Livewire + Tailwind CSS 4
- **Admin Panel**: Filament 4.x
- **Database**: MySQL 8.0
- **Server**: PHP 8.3 on cPanel VPS

**Why Monolithic**:
1. **Simpler security** - No API exposure, no CORS, session-based auth
2. **Easier deployment** - Single codebase, single server
3. **No image proxy issues** - Direct file serving
4. **Better for e-commerce** - Direct Easypay/Sage integration
5. **Lower complexity** - One stack to maintain (PHP only)

**Previous Architecture** (deprecated):
- ~~Next.js 16 on Vercel~~ → Replaced by Blade templates
- ~~REST API~~ → Direct Eloquent queries
- ~~API tokens~~ → Session-based authentication

### Technology Stack

| Layer | Technology | Notes |
|-------|-----------|-------|
| **Frontend Views** | Blade + Livewire | SSR, reactive components |
| **Styling** | Tailwind CSS 4 | Same design system as before |
| **Backend** | Laravel 12 | Controllers, Services, Models |
| **Admin Panel** | Filament 4.x | CMS for content management |
| **Database** | MySQL 8.0 | All data storage |
| **Authentication** | Laravel Sessions | Secure, server-side |
| **Payments** | Easypay API v2.0 | Direct Laravel integration |
| **i18n** | Laravel Localization | `mcamara/laravel-localization` |

### Project Structure

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/           # Web controllers
│   │   │   ├── HomeController.php
│   │   │   ├── NoticiaController.php
│   │   │   ├── EventoController.php
│   │   │   ├── SurferController.php
│   │   │   ├── ForecastController.php
│   │   │   ├── CarsurfController.php
│   │   │   ├── NazareQualificaController.php
│   │   │   └── ContactController.php
│   │   ├── Controllers/Api/       # API controllers (legacy, may remove)
│   │   └── Middleware/
│   │       └── LocalizationMiddleware.php
│   ├── Livewire/                  # Livewire components
│   │   ├── LanguageSwitcher.php
│   │   ├── NewsFilter.php
│   │   ├── EventsFilter.php
│   │   └── ContactForm.php
│   ├── Filament/
│   │   ├── Resources/             # NoticiaResource, SurferResource, etc.
│   │   └── Widgets/               # Dashboard widgets
│   ├── Models/                    # Eloquent models
│   └── Services/
│       └── ForecastService.php    # Open-Meteo API integration
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php      # Master layout
│   │   ├── components/
│   │   │   ├── layout/
│   │   │   │   ├── header.blade.php
│   │   │   │   └── footer.blade.php
│   │   │   └── ui/
│   │   │       ├── button.blade.php
│   │   │       ├── card.blade.php
│   │   │       ├── badge.blade.php
│   │   │       └── ...
│   │   ├── pages/
│   │   │   ├── home.blade.php
│   │   │   ├── noticias/
│   │   │   │   ├── index.blade.php
│   │   │   │   └── show.blade.php
│   │   │   ├── eventos/
│   │   │   ├── surfer-wall/
│   │   │   ├── previsoes.blade.php
│   │   │   ├── carsurf/
│   │   │   └── nazare-qualifica/
│   │   └── livewire/
│   │       ├── language-switcher.blade.php
│   │       ├── news-filter.blade.php
│   │       └── ...
│   ├── css/
│   │   └── app.css                # Tailwind + custom styles
│   └── js/
│       └── app.js
├── lang/
│   ├── pt/                        # Portuguese translations
│   │   ├── messages.php
│   │   └── navigation.php
│   └── en/                        # English translations
├── routes/
│   ├── web.php                    # Public routes
│   └── api.php                    # API routes (legacy)
├── public/
│   ├── storage/                   # Symlink to storage/app/public
│   ├── pn-ai-wave-hero.png
│   └── ...
└── database/
    ├── migrations/
    └── seeders/

frontend/                          # DEPRECATED - being migrated to Blade
└── (to be archived after migration)

docs/
├── tech-stack/
├── phases/
└── architecture/
```

## Key Architectural Decisions

### 1. Monolithic Laravel Architecture

**Rationale**: Eliminates complexity of split architecture while maintaining all functionality.

**Benefits**:
- Single codebase, single deployment
- No CORS configuration needed
- Session-based auth (more secure than tokens)
- Direct database access (no API layer)
- Images served directly (no proxy issues)
- Easier debugging and maintenance

**Trade-offs**:
- No Vercel CDN (mitigated by Laravel caching)
- Server must handle all traffic (VPS has 4GB RAM, should be sufficient)

### 2. Blade + Livewire for Frontend

**Why Blade**:
- Native Laravel templating
- Server-side rendering for SEO
- Same Tailwind CSS classes work identically
- Blade components replace React components

**Why Livewire**:
- Reactive components without JavaScript complexity
- Perfect for filters, forms, language switcher
- Server-side state management
- No build step for interactivity

### 3. Laravel + Filament CMS Platform

**Admin Panel Configuration** (Nazaré Qualifica branding):
- Theme: Navy Blue (#1e3a5f)
- Navigation organized in groups: Conteúdo, Surfer Wall, Páginas
- Dashboard with stats widgets (totals) + recent content tables
- Labels em português (Notícias, Eventos, Surfers, Pranchas, Páginas)

### 4. Multi-Entity Content Strategy

The platform serves three distinct brands within one codebase:

**Entity Field Pattern**: All content models include an `entity` field:
```php
'entity' => 'praia-norte' | 'carsurf' | 'nazare-qualifica'
```

### 5. Internationalization (i18n)

**Languages**: Portuguese (PT) - primary, English (EN) - secondary

**Implementation**:
- Route-based locales: `/pt/sobre`, `/en/about`
- Laravel localization package (`mcamara/laravel-localization`)
- JSON columns for translatable content (title, content, bio, etc.)
- PHP lang files for UI strings (`lang/pt/`, `lang/en/`)

### 6. Payment Integration Pattern

**Easypay Integration Architecture**:
- API credentials stored in Laravel `.env` ONLY
- Custom Laravel Service Provider for Easypay
- All payment communication server-side
- Webhook handlers with signature validation
- Support for: Credit/Debit cards, MB WAY, Multibanco, Direct Debit

**Security Requirements**:
- Never expose Easypay credentials
- Always use idempotency keys
- Validate webhook signatures (HMAC)
- Log all transactions for audit
- Server-side price validation

## Content Management (Filament)

### Content Types (Filament Resources)

All content is managed via Filament Resources with i18n support through JSON columns.

**Noticia** (news/blog):
- title (JSON: pt, en), slug, content (JSON)
- cover_image, author, category
- entity, tags, featured
- published_at, seo_title, seo_description

**Evento**:
- title (JSON), description (JSON)
- start_date, end_date, location
- entity, image, ticket_url
- featured

**Surfer** (unique to this project):
- name, slug, bio (JSON), photo
- nationality, achievements (JSON)
- surfboards (relation)
- social_media (JSON), featured

**Surfboard**:
- brand, model, length, image
- surfer_id (FK)

**Pagina** (institutional pages):
- title (JSON), slug, content (JSON)
- video_url, entity, seo_title, seo_description

### Future E-commerce

When e-commerce is implemented:
- Native Laravel e-commerce OR WooCommerce headless
- Easypay direct integration (PHP SDK)
- Sage connector for inventory sync
- Session-based shopping cart

## Security Requirements

**Critical Security Priorities** (per project requirements):

1. **Protection against cyber attacks** is a PRIMARY concern
2. Implement all OWASP Top 10 mitigations
3. CSRF protection on all forms (Laravel default)
4. Input validation with Laravel Form Requests
5. Never store credit card data (use Easypay tokenization)
6. HTTPS enforcement in production
7. Security headers (CSP, HSTS, etc.)
8. GDPR compliance (PT/EU regulations)

**Monolithic Security Advantages**:
- No exposed API surface
- Session-based auth (not JWT tokens)
- No CORS vulnerabilities
- Server-side validation only

## Development Workflow

### Build & Test Commands

**Laravel (Backend + Frontend)**:
```bash
cd backend
php artisan serve          # Start dev server (localhost:8000)
npm run dev                # Vite dev server for assets
php artisan migrate        # Run migrations
php artisan migrate:fresh --seed  # Reset DB with seeds
php artisan make:livewire ComponentName  # Create Livewire component
php artisan livewire:layout  # Publish Livewire layout
```

### Development URLs

| Service | URL | Notes |
|---------|-----|-------|
| Public Site | http://localhost:8000/pt | Portuguese |
| Public Site | http://localhost:8000/en | English |
| Filament Admin | http://localhost:8000/admin | CMS |

### Scripts de Desenvolvimento

Scripts disponíveis em `scripts/` para gestão do servidor:

| Script | Descrição |
|--------|-----------|
| `scripts/start.sh` | Inicia Laravel + Vite em background |
| `scripts/stop.sh` | Para todos os servidores |
| `scripts/restart.sh` | Reinicia servidores |

**Uso:**
```bash
./scripts/start.sh    # Iniciar servidores
./scripts/stop.sh     # Parar servidores
./scripts/restart.sh  # Reiniciar servidores
```

### Phase Overview

**Block 1 - Foundations** ✅ Complete
- **Phase 0**: ✅ Project setup - Laravel 12 + Filament 4 installed
- **Phase 1**: ✅ Design system, Tailwind CSS, component patterns

**Block 2 - Institutional** 🔄 In Progress (Migration)
- **Phase 2**: ✅ CMS backend (Filament resources, seeders)
- **Phase 3**: 🔄 Blade templates migration (converting from Next.js)

**Block 3 - Quality**
- **Phase 4**: SEO + Performance optimization
- **Phase 5**: Security hardening

**Block 4 - E-commerce** *(future phase)*
- Native Laravel e-commerce or WooCommerce headless
- Easypay payment integration
- Sage inventory sync

### Deployment

**Single VPS Deployment**:
- Deploy via GitHub Actions + SSH
- Vite build for assets
- PHP-FPM + Apache
- MySQL on same server

### Environment Variables Required

**Laravel `.env`**:
```env
# App
APP_NAME="Praia do Norte"
APP_URL=https://praiadonortenazare.pt
APP_LOCALE=pt
APP_FALLBACK_LOCALE=en

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=praia_do_norte
DB_USERNAME=
DB_PASSWORD=

# Easypay (NEVER commit these)
EASYPAY_ACCOUNT_ID=
EASYPAY_API_KEY=
EASYPAY_BASE_URL=https://api.prod.easypay.pt/2.0
EASYPAY_WEBHOOK_SECRET=

# Open-Meteo (no key needed - free API)
OPEN_METEO_LATITUDE=39.6012
OPEN_METEO_LONGITUDE=-9.0709
```

## Special Features

### Surfer Wall

Unique feature showcasing big wave surfers who ride Praia do Norte:
- Surfer profiles with photos, bios, achievements
- Associated surfboards with specs and images
- Social media integration
- Featured surfer highlighting

### Multi-Entity Navigation

Navigation clearly distinguishes between three entities while maintaining Praia do Norte prominence:
- Primary navigation highlights Praia do Norte (shop, news, events, surfer wall)
- Secondary navigation for Carsurf and Nazaré Qualifica
- Footer organized in three columns (one per entity)

### Wave Forecast & Live Webcams

**Route**: `/{locale}/previsoes`

**APIs Integradas**:
- **Open-Meteo Marine API** - Ondas, swell, temperatura da água (gratuita)
- **Open-Meteo Weather API** - Vento, rajadas (gratuita)
- **MONICAN** - Previsão oficial Instituto Hidrográfico (iframe)

**Funcionalidades**:
- 8 cards de condições atuais com dados em tempo real
- Previsão 7 dias em tabela
- Links para webcams (Surfline, Beachcam MEO)
- Código de cores para direção do vento (offshore/onshore)
- Recomendações de fato baseadas na temperatura da água

## Testing & Quality Standards

### Performance Targets

- Lighthouse Performance: > 90
- Lighthouse Accessibility: > 95
- Lighthouse Best Practices: > 95
- Lighthouse SEO: > 95
- TTFB (Time to First Byte): < 200ms
- LCP (Largest Contentful Paint): < 2.5s

### Security Standards

- Zero critical/high vulnerabilities (composer audit)
- Security headers score: A (securityheaders.com)
- SSL Labs rating: A+
- All user input validated with Form Requests
- CSRF protection on all forms
- No sensitive data in client-side code

## Documentation References

**Session Continuity** (IMPORTANT):
- **Session Handoff**: `SESSION-HANDOFF.md` - Read at start of each session, update at end

**Tech Stack Reference**:
- **Laravel 12 Guide**: `docs/tech-stack/LARAVEL_12.md`
- **Filament 4.x Guide**: `docs/tech-stack/FILAMENT_4.md`
- **Livewire 3 Guide**: `docs/tech-stack/LIVEWIRE_3.md` (to create)
- **Setup Log**: `docs/tech-stack/SETUP_LOG.md` (versions, issues, solutions)

**Planning Docs**:
- **Full Development Plan**: `PLANO_DESENVOLVIMENTO.md` (Portuguese, overview)
- **Phase-by-Phase Guides**: `docs/phases/` (implementation guides)
- **Folder Structure**: `docs/architecture/FOLDER_STRUCTURE.md`
- **Naming Conventions**: `docs/architecture/NAMING_CONVENTIONS.md`
- **Migration Plan**: `MIGRATION_PLAN.md`
- **Security Strategy**: `CYBERSECURITY_ASSESSMENT.md`

**Archived**:
- **Next.js 16 Guide**: `docs/archive/NEXTJS_16.md` (deprecated)

## Important Notes for Future Claude Instances

1. **Monolithic Architecture** - The project uses Laravel for everything (backend + frontend). There is no separate Next.js frontend anymore.

2. **Blade Templates** - Frontend uses Blade templates with Livewire for interactivity. Same Tailwind CSS classes as before.

3. **Praia do Norte is PRIMARY** - When balancing content from the three entities, always prioritize Praia do Norte visibility.

4. **Security is non-negotiable** - The client specifically emphasized protection against cyber attacks. Monolithic architecture reduces attack surface.

5. **Multi-language from day one** - All content must support PT/EN via JSON columns. UI strings via Laravel lang files.

6. **Easypay credentials are sacred** - All payment logic in Laravel backend with PHP SDK.

7. **VPS Constraints** - The VPS has 4GB RAM. Monolithic Laravel is more efficient than split architecture.

8. **CentOS 7 EOL Warning** - The VPS runs CentOS 7 which reached EOL June 2024. Migration to AlmaLinux/Rocky Linux is recommended.

9. **Filament 4.x Type Hints** - When adding navigation properties to Resources, use correct types:
    - `$navigationIcon`: `string|\BackedEnum|null`
    - `$navigationGroup`: `string|\UnitEnum|null`

10. **Admin Panel is Nazaré Qualifica** - The Filament admin panel represents Nazaré Qualifica branding. Navy Blue theme (#1e3a5f). Praia do Norte branding is for public frontend only.

11. **Session Handoff** - Always update `SESSION-HANDOFF.md` at the end of each session with what was done, files changed, and next steps.

12. **Design Pixel-Perfect** - The Blade templates must match the original Next.js design exactly. Same Tailwind classes, same layout, same responsive behavior.

## VPS Infrastructure

**Server**: vm01.cm-nazare.pt
- CPU: 4 vCPUs @ 2.1GHz
- RAM: 4GB (sufficient for monolithic Laravel)
- Storage: 114GB free
- OS: CentOS 7 (EOL - migration recommended)
- PHP: 8.3 with FPM
- MySQL: 8.0.42
- Apache: 2.4
- cPanel: 110.0.50

## Routes Reference

```php
// routes/web.php

Route::prefix('{locale}')
    ->where(['locale' => 'pt|en'])
    ->middleware('localization')
    ->group(function () {
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

// Redirect root to default locale
Route::get('/', fn() => redirect('/pt'));
```
