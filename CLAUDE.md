# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

> **Status: Phase 3 Complete - Dynamic Content Pages**
>
> All dynamic content pages implemented: News, Events, Surfer Wall with listing and detail pages.
> Frontend fully integrated with backend API. All content fetched from database.
> Next step: Phase 4 - SEO + Performance optimization.

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
| **Payment Processing** | Server-side ONLY (Laravel backend) |
| **Languages** | PT (primary) + EN from day one |
| **VPS RAM** | 4GB limit - no Node.js on server |
| **Frontend** | TypeScript required |
| **Security** | OWASP Top 10 mitigations mandatory |

## Project Status

**Current Phase**: Phase 3 Complete - Starting Phase 4

- [x] Backend: Laravel 12.41.1 + Filament 4.2.4 installed
- [x] Frontend: Next.js 16.0.7 + React 19 + Tailwind 4 installed
- [x] Design System: shadcn/ui components (Button, Card, Badge, etc.)
- [x] Backend CMS: All Filament Resources (Noticias, Eventos, Surfers, Surfboards, Paginas)
- [x] API: REST endpoints for all content types
- [x] Database Seeders: Realistic PT/EN dummy content
- [x] Frontend API Client: TypeScript types and fetch functions
- [x] Homepage Integration: Real data from API
- [x] News Pages: Listing with filters + individual article pages
- [x] Events Pages: Listing with upcoming/past filters + individual event pages
- [x] Surfer Wall: Grid with featured surfers + individual profile pages
- [ ] Next: SEO metadata, performance optimization, security headers

## Technical Architecture

### Core Stack Decision

The project uses a **split architecture** optimized for the available VPS infrastructure (vm01.cm-nazare.pt):

- **Backend**: Laravel 12 + Filament 4.x on cPanel VPS (PHP 8.3, MySQL 8.0)
- **Frontend**: Next.js 16 on Vercel (free tier)

This architecture was chosen because:
1. VPS requires PHP 8.3 upgrade via EasyApache 4 (Laravel 12 requirement)
2. RAM constraints (4GB, swap full) prevent running Node.js alongside PHP
3. Filament provides a beautiful, intuitive admin panel for content editors
4. Laravel handles all CMS and future e-commerce (WooCommerce integration planned)
5. Vercel free tier offloads frontend processing (zero extra cost)

**E-commerce Strategy**: Institutional platform first, e-commerce via WooCommerce headless in future phase (pending Sage connector validation).

### Technology Stack

| Layer | Technology | Location | Notes |
|-------|-----------|----------|-------|
| **Frontend** | Next.js 16 (App Router) + TypeScript | Vercel | SSR for SEO |
| **UI Framework** | Tailwind CSS 4 + shadcn/ui | Vercel | Radix UI primitives |
| **State Management** | Zustand | Vercel | Future: shopping cart |
| **Backend/CMS** | Laravel 12 + Filament 4.x | VPS | Admin panel, API |
| **Database** | MySQL 8.0 | VPS | All data storage |
| **Authentication** | Laravel Sanctum | VPS | API tokens for frontend |
| **Payments** | Easypay API v2.0 | VPS | Future: custom Laravel integration |
| **Validation** | Zod (frontend) + Laravel (backend) | Both | All forms + API |
| **i18n** | next-intl (frontend) + Laravel (backend) | Both | PT (primary) + EN |

**Tech Stack Documentation**: See `docs/tech-stack/` for detailed reference guides.

### Project Structure

```
praiadonorte_nq/
├── backend/                       # Laravel 12 + Filament 4.x (deploys to VPS)
│   ├── app/
│   │   ├── Filament/
│   │   │   └── Resources/         # NoticiaResource, SurferResource, etc.
│   │   ├── Http/Controllers/Api/  # API controllers
│   │   ├── Models/                # Eloquent models
│   │   └── Providers/
│   │       └── Filament/
│   │           └── AdminPanelProvider.php
│   ├── database/migrations/
│   ├── routes/api.php
│   └── composer.json
│
├── frontend/                      # Next.js 16 + React 19 (deploys to Vercel)
│   ├── src/
│   │   └── app/
│   │       ├── [locale]/          # i18n routes (to create)
│   │       │   ├── (praia-do-norte)/
│   │       │   ├── (carsurf)/
│   │       │   └── (nazare-qualifica)/
│   │       ├── layout.tsx
│   │       └── page.tsx
│   ├── messages/                  # i18n (pt.json, en.json)
│   └── package.json
│
└── docs/
    ├── tech-stack/                # Technical reference docs
    ├── phases/                    # Implementation guides
    └── architecture/              # Architecture docs
```

## Key Architectural Decisions

### 1. Laravel + Filament CMS Platform

**Rationale**: Filament provides a beautiful, intuitive admin panel that content editors will love, while Laravel handles all backend logic.

**Why Filament**:
- Modern, clean UI that's intuitive for non-technical users
- Full control over admin interface customization
- Native Laravel integration (no separate system)
- Excellent form builder with i18n support via JSON fields
- Extensible via plugins

**Implementation**:
- Content (News, Surfers, Events) managed via Filament Resources
- API endpoints for frontend consumption
- i18n via JSON columns in database
- Future: WooCommerce headless for e-commerce

### 2. Multi-Entity Content Strategy

The platform serves three distinct brands within one codebase:

**Entity Field Pattern**: All content models include an `entity` field:
```php
'entity' => 'praia-norte' | 'carsurf' | 'nazare-qualifica'
```

This allows content filtering and ensures proper brand association throughout the platform.

### 3. Internationalization (i18n)

**Languages**: Portuguese (PT) - primary, English (EN) - secondary

**Implementation**:
- Route-based locales: `/pt/sobre`, `/en/about`
- JSON columns for translatable fields (title, content, bio, etc.)
- `next-intl` for frontend UI translations
- All content fields support translations via JSON structure

### 4. Payment Integration Pattern

**Easypay Integration Architecture**:
- API credentials stored in Laravel `.env` ONLY
- Custom Laravel Service Provider for Easypay
- All payment communication server-side (never client)
- Webhook handlers in Laravel with signature validation
- Support for: Credit/Debit cards, MB WAY, Multibanco, Direct Debit
- TypeScript types in frontend from `api/swagger.json`

**Security Requirements**:
- Never expose Easypay credentials to frontend
- Always use idempotency keys
- Validate webhook signatures (HMAC)
- Log all transactions for audit
- Server-side price validation (never trust frontend prices)

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
- entity, seo_title, seo_description

### Future E-commerce (WooCommerce Headless)

When e-commerce is implemented:
- WooCommerce as product/order backend
- Easypay plugin for payments
- Sage connector for inventory sync
- API integration with Next.js frontend

### SEO

All content supports SEO fields:
- seo_title (i18n), seo_description (i18n)
- og:image via cover_image field

## Security Requirements

**Critical Security Priorities** (per project requirements):

1. **Protection against cyber attacks** is a PRIMARY concern
2. Implement all OWASP Top 10 mitigations
3. Rate limiting on all API routes
4. Input validation with Zod on every form/API
5. Never store credit card data (use Easypay tokenization)
6. HTTPS enforcement in production
7. Security headers (CSP, HSTS, etc.)
8. GDPR compliance (PT/EU regulations)

## Development Workflow

### Build & Test Commands

**Backend (Laravel)**:
```bash
cd backend
php artisan serve          # Start dev server (localhost:8000)
php artisan migrate        # Run migrations
php artisan migrate:fresh --seed  # Reset DB with seeds
php artisan make:filament-resource Noticia  # Create Filament resource
```

**Frontend (Next.js)**:
```bash
cd frontend
npm run dev      # Start dev server (localhost:3000)
npm run build    # Production build
npm run lint     # ESLint check
```

### Development URLs

| Service | URL | Command |
|---------|-----|---------|
| Frontend | http://localhost:3000 | `npm run dev` |
| Backend API | http://localhost:8000/api | `php artisan serve` |
| Filament Admin | http://localhost:8000/admin | `php artisan serve` |

### Scripts de Desenvolvimento

Scripts disponíveis em `scripts/` para gestão dos servidores:

| Script | Descrição |
|--------|-----------|
| `scripts/start.sh` | Inicia backend (Laravel) e frontend (Next.js) em background |
| `scripts/stop.sh` | Para todos os servidores e limpa processos órfãos |
| `scripts/restart.sh` | Executa stop.sh seguido de start.sh |

**Uso:**
```bash
./scripts/start.sh    # Iniciar servidores
./scripts/stop.sh     # Parar servidores
./scripts/restart.sh  # Reiniciar servidores
```

**Notas:**
- Os PIDs são guardados em `.pids/` (ignorado pelo git)
- Os scripts matam processos órfãos nas portas 3000 e 8000
- Output dos servidores é silenciado (correm em background)

> **IMPORTANTE**: Estes scripts devem ser atualizados sempre que houver alterações na infraestrutura do projeto: novas portas, novos serviços, novos containers Docker, novas dependências de runtime, etc.

### Phase Overview

The project is organized in 4 blocks, with e-commerce in future phase:

**Block 1 - Foundations** ✅ Complete
- **Phase 0**: ✅ Project setup - Laravel 12 + Filament 4 + Next.js 16 installed
- **Phase 1**: ✅ Design system (Next.js), shadcn/ui, layout components

**Block 2 - Institutional** ✅ Complete
- **Phase 2**: ✅ Homepage and CMS backend (Filament resources, API, seeders)
- **Phase 3**: ✅ Dynamic content pages (news, events, surfer wall - listing + detail)

**Block 3 - Quality**
- **Phase 4**: SEO + Performance optimization
- **Phase 5**: Security hardening

**Block 4 - E-commerce** *(future phase)*
- WooCommerce headless integration (pending Sage connector validation)
- Easypay payment integration

> **Note**: E-commerce implementation postponed to future phase. Current focus is institutional platform with CMS.

### Deployment

- **Backend (Laravel)**: Deploy to VPS via GitHub Actions + SSH
- **Frontend (Next.js)**: Deploy to Vercel (free tier, Git integration)

### Environment Variables Required

**Backend (Laravel `.env`)**:
```env
# Database (VPS MySQL)
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

# App
APP_URL=https://api.praiadonortenazare.pt
SANCTUM_STATEFUL_DOMAINS=praiadonortenazare.pt,www.praiadonortenazare.pt
```

**Frontend (Vercel Environment Variables)**:
```env
# API
NEXT_PUBLIC_API_URL=https://api.praiadonortenazare.pt

# Cloudinary (media)
NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME=
```

## Special Features

### Surfer Wall

Unique feature showcasing big wave surfers who ride Praia do Norte:
- Surfer profiles with photos, bios, achievements
- Associated surfboards with specs and images
- Social media integration
- Featured surfer highlighting

### Multi-Entity Navigation

Navigation must clearly distinguish between the three entities while maintaining Praia do Norte prominence:
- Primary navigation highlights Praia do Norte (shop, news, events, surfer wall)
- Secondary navigation for Carsurf and Nazaré Qualifica
- Footer organized in three columns (one per entity)

### Wave Forecast & Live Webcams ✅ IMPLEMENTED

**Página**: `/[locale]/previsoes`

**APIs Integradas**:
- **Open-Meteo Marine API** - Ondas, swell, temperatura da água (gratuita, sem chave)
- **Open-Meteo Weather API** - Vento, rajadas (gratuita, sem chave)
- **MONICAN** - Previsão oficial Instituto Hidrográfico (iframe)

**Funcionalidades**:
- 8 cards de condições atuais com dados em tempo real
- Previsão 7 dias em tabela
- Links para webcams (Surfline, Beachcam MEO)
- Código de cores para direção do vento (offshore/onshore)
- Recomendações de fato baseadas na temperatura da água

**Ficheiros**: `frontend/src/lib/api/forecast.ts`, `frontend/src/app/[locale]/previsoes/page.tsx`

## Testing & Quality Standards

### Performance Targets

- Lighthouse Performance: > 90
- Lighthouse Accessibility: > 95
- Lighthouse Best Practices: > 95
- Lighthouse SEO: > 95
- LCP (Largest Contentful Paint): < 2.5s
- FID (First Input Delay): < 100ms
- CLS (Cumulative Layout Shift): < 0.1

### Security Standards

- Zero critical/high npm audit vulnerabilities
- Security headers score: A (securityheaders.com)
- SSL Labs rating: A+
- All user input validated with Zod
- All API responses validated
- No sensitive data in client-side code

## Documentation References

**Tech Stack Reference** (start here):
- **Laravel 12 Guide**: `docs/tech-stack/LARAVEL_12.md`
- **Filament 4.x Guide**: `docs/tech-stack/FILAMENT_4.md`
- **Next.js 16 Guide**: `docs/tech-stack/NEXTJS_16.md`
- **Setup Log**: `docs/tech-stack/SETUP_LOG.md` (versions, issues, solutions)

**Planning Docs**:
- **Full Development Plan**: `PLANO_DESENVOLVIMENTO.md` (Portuguese, overview)
- **Phase-by-Phase Guides**: `docs/phases/` (implementation guides)
- **Folder Structure**: `docs/architecture/FOLDER_STRUCTURE.md`
- **Naming Conventions**: `docs/architecture/NAMING_CONVENTIONS.md`
- **Migration/Deployment Guide**: `MIGRATION_PLAN.md`
- **E-commerce Analysis**: `docs/archive/E-COMMERCE_PLATFORMS_COMPARISON.md`
- **Security Strategy**: `CYBERSECURITY_ASSESSMENT.md`
- **Project Concept**: `docs/concept.txt` (Portuguese, business requirements)

## Important Notes for Future Claude Instances

1. **E-commerce Postponed** - E-commerce will be implemented in a future phase using WooCommerce headless. Current focus is the institutional platform with Laravel + Filament CMS.

2. **Praia do Norte is PRIMARY** - When balancing content from the three entities, always prioritize Praia do Norte visibility

3. **Security is non-negotiable** - The client specifically emphasized protection against cyber attacks. Never compromise on security for convenience.

4. **Multi-language from day one** - All content must support PT/EN via JSON columns. Never create single-language solutions.

5. **Easypay credentials are sacred** - Never suggest client-side payment processing. All payment logic must be in Laravel backend.

6. **VPS Constraints** - The VPS has 4GB RAM with swap fully utilized. Do not add Node.js services to the VPS. Frontend runs on Vercel.

7. **CentOS 7 EOL Warning** - The VPS runs CentOS 7 which reached EOL June 2024. Migration to AlmaLinux/Rocky Linux is recommended.

8. **TypeScript for frontend** - Next.js frontend must use TypeScript. Laravel backend uses PHP.

9. **Phase 3 Complete** - All dynamic content pages implemented. News, Events, and Surfer Wall with listing and detail views. Continue with Phase 4 (SEO + Performance).

10. **Tech Stack Docs** - See `docs/tech-stack/` for Laravel, Filament, and Next.js reference guides with code patterns.

## VPS Infrastructure

**Server**: vm01.cm-nazare.pt
- CPU: 4 vCPUs @ 2.1GHz
- RAM: 4GB (constraint - swap 100% used)
- Storage: 114GB free
- OS: CentOS 7 (EOL - migration recommended)
- PHP: 8.3 with FPM (requires upgrade from 8.1 via EasyApache 4)
- MySQL: 8.0.42
- Apache: 2.4
- cPanel: 110.0.50
