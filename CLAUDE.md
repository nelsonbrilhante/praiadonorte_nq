# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Praia do Norte Unified Platform** - Monolithic Laravel e-commerce and institutional website for three Portuguese municipal entities:
- **Praia do Norte** (primary brand, always prioritized) - Big wave surfing destination
- **Carsurf** - Surf training center
- **Nazaré Qualifica** - Municipal infrastructure management

**Current Phase**: Quality Assurance (Phase 4) - SEO, performance optimization. Next: security hardening (Phase 5). Blade migration from Next.js is complete.

## Critical Constraints

- **Praia do Norte is PRIMARY** - All content hierarchy and UX decisions prioritize Praia do Norte visibility
- **Payment processing server-side ONLY** (Laravel) - Easypay credentials never exposed to client
- **Multi-language from day one** - PT (primary) + EN. Content via JSON columns, UI strings via `lang/pt/` and `lang/en/`
- **Pixel-perfect design** - Blade templates must match the original Next.js design exactly
- **Security is non-negotiable** - OWASP Top 10 mitigations mandatory
- **Never auto-commit** - Always ask user permission before `git commit`

## Development Commands

All commands run from `backend/`:

```bash
# Full dev environment (server + queue + logs + vite)
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

# Convenience scripts (from project root)
./scripts/start.sh             # Start Laravel + Vite in background
./scripts/stop.sh              # Stop all servers
./scripts/restart.sh           # Restart servers
```

**Dev URLs**: `localhost:8000/pt` (Portuguese), `localhost:8000/en` (English), `localhost:8000/admin` (Filament CMS)

## Architecture

**Stack**: Laravel 12 + Blade + Livewire + Tailwind CSS 4 + Filament 4.x + MySQL 8.0 + PHP 8.3

**Why monolithic**: No API surface to secure, no CORS, session-based auth, direct Eloquent queries, single deployment to VPS (vm01.cm-nazare.pt, 4GB RAM, CentOS 7).

### Key Patterns

**Multi-entity content**: All content models include an `entity` field (`'praia-norte' | 'carsurf' | 'nazare-qualifica'`).

**i18n routing**: All public routes are prefixed with `{locale}` (`pt|en`) and pass through `LocalizationMiddleware`. Translatable model fields use JSON columns. UI strings use Laravel lang files via `mcamara/laravel-localization`.

**Filament admin panel**: Branded as Nazaré Qualifica (Navy Blue #1e3a5f). Praia do Norte branding is public frontend only. When adding navigation properties to Resources, use types: `$navigationIcon: string|\BackedEnum|null`, `$navigationGroup: string|\UnitEnum|null`.

**Livewire components**: Used for reactive UI (LanguageSwitcher, NewsFilter, EventsFilter, ContactForm) - no custom JavaScript needed.

**Forecast feature** (`/{locale}/previsoes`): Integrates Open-Meteo Marine + Weather APIs (free, no keys) and MONICAN iframe for wave/weather data.

### Routes

See `backend/routes/web.php` for all public routes. Root `/` redirects to `/pt`. Route names follow: `home`, `noticias.index`, `noticias.show`, `eventos.*`, `surfers.*`, `forecast`, `carsurf.*`, `nq.*`, `sobre`, `contacto`.

### Content Models (Filament Resources)

- **Noticia** - News/blog with i18n title/content, cover image, entity, category, tags
- **Evento** - Events with dates, location, entity
- **Surfer** - Big wave surfer profiles with bio, achievements, surfboards relation
- **Surfboard** - Board specs linked to surfers
- **Pagina** - Institutional pages with i18n content, entity

## Session Protocol

- **Start of session**: Read `SESSION-HANDOFF.md` for context
- **End of session** (user says "fechar sessão", "end session", etc.): Update `SESSION-HANDOFF.md` with date, summary, files changed, next tasks. Update this file's Project Status if phase changed.

## Documentation References

- `SESSION-HANDOFF.md` - Session continuity (read first, update last)
- `docs/tech-stack/LARAVEL_12.md` - Laravel reference
- `docs/tech-stack/FILAMENT_4.md` - Filament reference
- `docs/tech-stack/SETUP_LOG.md` - Version history, issues, solutions
- `docs/phases/` - Phase-by-phase implementation guides
- `docs/architecture/NAMING_CONVENTIONS.md` - Naming conventions
- `CYBERSECURITY_ASSESSMENT.md` - Security strategy
- `PLANO_DESENVOLVIMENTO.md` - Full development plan (Portuguese)

## Performance Targets

Lighthouse: Performance >90, Accessibility >95, Best Practices >95, SEO >95. TTFB <200ms, LCP <2.5s.
