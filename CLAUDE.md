# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Praia do Norte Unified Platform** — Monolithic Laravel website for three Portuguese municipal entities:
- **Praia do Norte** (primary brand, always prioritized) — Big wave surfing destination
- **Carsurf** — Surf training center
- **Nazare Qualifica** — Municipal infrastructure management

**Current Phase**: Quality Assurance (Phase 4) — SEO, performance optimization. Next: security hardening (Phase 5). Blade migration from Next.js is complete.

## Critical Constraints

- **Praia do Norte is PRIMARY** — All content hierarchy and UX decisions prioritize Praia do Norte visibility
- **Payment processing server-side ONLY** (Laravel) — Easypay credentials never exposed to client
- **Multi-language from day one** — PT (primary) + EN. Content via JSON columns, UI strings via `lang/pt/` and `lang/en/`
- **Never auto-commit** — Always ask user permission before `git commit`
- **Always backup DB before seeding/importing** — Before any `migrate:fresh --seed`, `db:seed`, or import command, copy the SQLite DB: `cp backend/database/database.sqlite backend/storage/app/backups/backup-$(date +%Y%m%d-%H%M%S).sqlite`. For MySQL: `mysqldump` to `backend/storage/app/backups/`

## Development Commands

### Laravel (from `backend/`)

```bash
# Full dev environment (server + queue + logs + vite — runs 4 processes via concurrently)
composer dev

# Individual services
php artisan serve              # Laravel dev server (localhost:8000)
npm run dev                    # Vite asset dev server

# Database
php artisan migrate            # Run migrations
php artisan migrate:fresh --seed  # Reset DB with seeds

# Testing
composer test                  # Clear config + run tests
php artisan test               # Run tests directly
php artisan test --filter=ExampleTest  # Run single test

# Build
npm run build                  # Production asset build

# Content download commands
php artisan app:download-corporate-bodies  # Download photos/CVs from old site
php artisan app:download-documents         # Download 102 PDFs across 13 categories
```

### WordPress/WooCommerce (from `wordpress/`)

```bash
make setup      # Full automated setup: Docker build, WP install, WooCommerce, seed 5 products, update backend/.env
make start      # Start containers (data preserved)
make stop       # Stop containers (data preserved)
make status     # Show container status and WP/plugin info
make logs       # Tail container logs
make seed       # Re-seed categories + products (idempotent)
make teardown   # Destroy everything (containers + volumes)
make reset      # Teardown + fresh setup
```

### Convenience scripts (from project root)

```bash
./scripts/start.sh             # Start Laravel + Vite in background (PIDs in .pids/, logs in .logs/)
./scripts/stop.sh              # Stop all servers
./scripts/restart.sh           # Restart servers
```

**Dev URLs**: `localhost:8000/pt` (Portuguese), `localhost:8000/en` (English), `localhost:8000/admin` (Filament CMS, credentials: `nelson.brilhante@cm-nazare.pt` / `Nzr€Qu@l!f1c4-2026`), `localhost:8080` (WordPress admin: `admin` / `admin123`)

**Credentials**: All environment credentials (production, dev, VPS, Coolify, WooCommerce API keys) are in `.credentials.md` (git-ignored). Check there for WordPress production/dev store logins, SSH access, and API tokens.

## Architecture

**Stack**: Laravel 12 + Blade + Livewire 3 + Alpine.js + Tailwind CSS 4 + Filament 4.x + MySQL 8.0 + PHP 8.3

**Why monolithic**: No API surface to secure, no CORS, session-based auth, direct Eloquent queries, single deployment to VPS.

**Project structure**:
```
backend/        ← Laravel application (main codebase)
wordpress/      ← WooCommerce Docker setup (local dev + production reference)
scripts/        ← start.sh, stop.sh, restart.sh (convenience)
docs/           ← Technical documentation, phase guides, architecture docs
Dockerfile      ← Multi-stage production build (Composer → Vite → PHP-FPM + Nginx)
```

### WooCommerce Integration

Users browse products on the Laravel frontend (`/pt/loja`), click "Buy", get redirected to WooCommerce for cart/checkout/payment (via Easypay plugin).

- **`WooCommerceService`** (`backend/app/Services/WooCommerceService.php`) — REST API client. Methods: `getProducts()`, `getProductBySlug()`, `getCategories()`, `isAvailable()`. Responses cached 5min (configurable). Graceful fallback on connection failure.
- **`LojaController`** (`backend/app/Http/Controllers/LojaController.php`) — Handles `/pt/loja` and `/en/shop` routes with category filtering and pagination.
- **Config**: `backend/config/woocommerce.php` reads `WOOCOMMERCE_*` env vars. The `make setup` command in `wordpress/` auto-generates API keys and updates `backend/.env`.

### Multi-Entity Content

All content models include an `entity` field. **Always use hyphenated slugs**: `'praia-norte'`, `'carsurf'`, `'nazare-qualifica'`. The `docs/architecture/NAMING_CONVENTIONS.md` suggests underscores but the actual codebase uses hyphens everywhere.

### i18n

**Routing**: All public routes prefixed with `{locale}` (`pt|en`) via `mcamara/laravel-localization`. Middleware stack: `localeSessionRedirect`, `localizationRedirect`, `localeViewPath`. Root `/` redirects to `/pt`.

**Translatable model fields** use JSON columns. Structure: `{"pt": "...", "en": "..."}`.
- In Blade: `$model->title[app()->getLocale()] ?? $model->title['pt']`
- In Filament forms: `TextInput::make('title.pt')` / `TextInput::make('title.en')` with tabs per locale
- Filament 4 does not auto-hydrate nested JSON paths like `content.pt.intro.title`. Fix with `->afterStateHydrated()`:
  ```php
  TextInput::make('content.pt.intro.title')
      ->afterStateHydrated(fn ($state, $set, $record) =>
          $set('content.pt.intro.title', $record?->content['pt']['intro']['title'] ?? $state))
  ```

**UI strings**: `lang/pt/messages.php` and `lang/en/messages.php`. Access via `__('messages.key')`.

### Filament Admin Panel

Branded as Nazare Qualifica (Navy Blue `#1e3a5f`). Admin locale forced to `pt` via `SetLocaleMiddleware`.

**Resource organization** — navigation groups: `Geral`, `Conteudo`, `Praia do Norte`, `Carsurf`, `Nazare Qualifica`, `Website`.

**BasePageResource pattern**: `Filament/Resources/Paginas/BasePageResource` is an abstract class. Entity-specific resources (`NQPageResource`, `CarsurfPageResource`, `PraiaNortePageResource`) extend it and implement `getEntityFilter()` to auto-scope queries by entity.

**Form/Table separation**: Larger resources extract form schemas to `Schemas/XxxForm.php` and table config to `Tables/XxxTable.php` (e.g., `NoticiaForm`, `NoticiasTable`, `EventoForm`, `EventosTable`, `SurferForm`).

**File uploads**: All `FileUpload` components **must** specify `->disk('public')->visibility('public')` or uploaded files won't be accessible on the frontend.

**Filament 4 namespaces** (different from v3):
```php
use Filament\Actions\EditAction;        // Correct (v4)
use Filament\Tables\Actions\EditAction; // Wrong (v3)
```

**Do not use `->viteTheme()`** in AdminPanelProvider — it breaks Filament CSS loading.

### Content Models

| Model | Key Details |
|---|---|
| **Noticia** | News/blog. i18n title/content/excerpt, cover image, entity, category, tags |
| **Evento** | Events. Dates, location, entity, gallery, schedule, partners |
| **Surfer** | Big wave surfer profiles. Bio, achievements, `hasMany(Surfboard)` |
| **Surfboard** | Board specs, `belongsTo(Surfer)` |
| **Pagina** | Institutional pages. i18n content, entity, hero_image. `hasMany(HeroSlide)` |
| **HeroSlide** | Homepage hero slides (up to 5). Video/image, LIVE badge, auto-rotate. Pauses when any slide has `is_live=true` |
| **CorporateBody** | Corpos Sociais (NQ). Sections: `conselho_gerencia`, `assembleia_geral`, `fiscal_unico` |
| **DocumentCategory** | i18n name/description, ordered. `hasMany(Document)` |
| **Document** | PDF uploads per category, i18n title |

### Routes

See `backend/routes/web.php`. Route names: `home`, `noticias.index`, `noticias.show`, `eventos.*`, `surfers.*`, `forecast`, `loja.index`, `loja.show`, `carsurf.*`, `nq.*`, `sobre`, `contacto`, `privacidade`, `termos`, `cookies`.

**Legacy API routes** (`/api/v1/*`) still exist from the Next.js era. The search spotlight (`Cmd+K`) uses `/api/v1/search` via Alpine.js fetch (converted from Livewire to avoid PHP built-in server single-thread issues).

### Frontend Architecture

**CSS**: Tailwind CSS v4 with `@theme inline` tokens in `resources/css/app.css` — no `tailwind.config.js`. Custom color tokens per entity:
- Praia do Norte: `ocean-*` (primary `#0066cc`, deep `#003566`, abyss `#001d3d`)
- Carsurf: `performance-*` (primary `#00cc66`)
- Nazare Qualifica: `institutional-*` (primary `#ffa500`)

**Fonts**: Inter (body, weight 400/500) + Montserrat (headings, weight 600/700/800).

**Dark mode**: Class-based (`.dark` on `<html>`), persisted in `localStorage`, initialized with inline `<head>` script to prevent flash.

**Alpine.js**: Used for search spotlight, header dropdowns, dark mode toggle, scroll reveal. Registered with `@alpinejs/intersect` plugin.

**Livewire**: Used for `LanguageSwitcher` only. Other interactive components use Alpine.js.

**UI components**: `components/ui/` has button, card (with subcomponents), badge, breadcrumbs, input, textarea, stat-counter — inspired by shadcn/ui patterns, implemented in Blade.

**Entity CSS classes**: `.badge-praia-norte`, `.badge-carsurf`, `.badge-nazare-qualifica` for colored badges. `.gradient-ocean`, `.gradient-performance`, `.gradient-institutional` for gradient headers. `.hover-glow-*` for hover effects.

### Build Configuration

Vite with three entry points:
1. `resources/css/app.css` — public frontend styles
2. `resources/js/app.js` — public frontend JS (Alpine.js)
3. `resources/css/filament/admin.css` — Filament admin custom theme

Uses `@tailwindcss/vite` plugin (Tailwind v4 native Vite integration).

### Production Deployment

**Dockerfile** (project root) — Multi-stage build: Composer (PHP deps) → Node (Vite assets) → PHP 8.4-FPM Alpine with Nginx + Supervisor. Runs PHP-FPM, Nginx, queue worker, database seeder, and content downloader. Entrypoint creates `.env` from Docker env vars, runs migrations, caches config, publishes Livewire assets.

**CI/CD**: Push to `main` triggers `.github/workflows/deploy.yml` which calls Coolify webhook (secrets: `COOLIFY_WEBHOOK_URL`, `COOLIFY_API_TOKEN`).

## Session Protocol

- **Start of session**: Read `SESSION-HANDOFF.md` for context
- **End of session** (user says "fechar sessao", "end session", etc.): Update `SESSION-HANDOFF.md` with date, summary, files changed, next tasks. Update this file's Project Status if phase changed.

## Git Conventions

Branches: `main` (production), `feature/*`, `fix/*`, `hotfix/*`. Commits: conventional format (`feat:`, `fix:`, `docs:`, `chore:`, `refactor:`).

## Documentation References

- `SESSION-HANDOFF.md` — Session continuity (read first, update last)
- `docs/tech-stack/LARAVEL_12.md` — Laravel reference
- `docs/tech-stack/FILAMENT_4.md` — Filament reference
- `docs/tech-stack/SETUP_LOG.md` — Version history, issues, solutions
- `docs/phases/` — Phase-by-phase implementation guides
- `docs/architecture/NAMING_CONVENTIONS.md` — Naming conventions
- `DESIGN_GUIDELINES.md` — Visual identity and UX patterns
- `CYBERSECURITY_ASSESSMENT.md` — Security strategy
- `PLANO_DESENVOLVIMENTO.md` — Full development plan (Portuguese)

## Performance Targets

Lighthouse: Performance >90, Accessibility >95, Best Practices >95, SEO >95. TTFB <200ms, LCP <2.5s.
