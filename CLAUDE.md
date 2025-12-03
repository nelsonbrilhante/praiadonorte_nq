# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

> **Status: Pre-Implementation (Planning Complete)**
>
> No code has been written yet. This is a planning repository with comprehensive documentation.
> When implementation begins, follow Phase 0 in `docs/phases/FASE_00_SETUP.md`.

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

**Current Phase**: Pre-implementation (Planning Complete)

This is a greenfield project. No code has been written yet. The complete development plan exists in `PLANO_DESENVOLVIMENTO.md` with an 11-week phased implementation roadmap.

## Technical Architecture

### Core Stack Decision

The project uses a **split architecture** optimized for the available VPS infrastructure (vm01.cm-nazare.pt):

- **Backend**: Laravel 12 + Aimeos 2025.10 LTS on cPanel VPS (PHP 8.3, MySQL 8.0)
- **Frontend**: Next.js 15 on Vercel (free tier)

This architecture was chosen because:
1. VPS requires PHP 8.3 upgrade via EasyApache 4 (Laravel 12 requirement)
2. RAM constraints (4GB, swap full) prevent running Node.js alongside PHP
3. Aimeos has no major CVEs (security priority)
4. Aimeos has best-in-class i18n support (PT/EN requirement)
5. Vercel free tier offloads frontend processing (zero extra cost)

### Technology Stack

| Layer | Technology | Location | Notes |
|-------|-----------|----------|-------|
| **Frontend** | Next.js 15 (App Router) + TypeScript | Vercel | SSR for SEO |
| **UI Framework** | Tailwind CSS + shadcn/ui | Vercel | Radix UI primitives |
| **State Management** | Zustand | Vercel | Shopping cart |
| **E-commerce** | Laravel 12 + Aimeos 2025.10 LTS | VPS | Products, orders, inventory |
| **Database** | MySQL 8.0 | VPS | All data storage |
| **Authentication** | Laravel Sanctum | VPS | API tokens for frontend |
| **Payments** | Easypay API v2.0 | VPS | Custom Laravel integration |
| **Validation** | Zod (frontend) + Laravel (backend) | Both | All forms + API |
| **i18n** | next-intl (frontend) + Aimeos (backend) | Both | PT (primary) + EN |

### Project Structure (When Implemented)

```
praia-do-norte-unified/
├── frontend/                      # Next.js 15 (deploys to Vercel)
│   ├── src/
│   │   ├── app/[locale]/          # App Router with i18n
│   │   │   ├── (praia-do-norte)/  # Route group for main brand
│   │   │   ├── (carsurf)/         # Route group for Carsurf
│   │   │   └── (nazare-qualifica)/ # Route group for NQ
│   │   ├── components/
│   │   │   ├── ui/                # shadcn/ui components
│   │   │   ├── layout/            # Header, Footer, Navigation
│   │   │   ├── e-commerce/        # Product, Cart, Checkout
│   │   │   └── seo/               # Structured data components
│   │   ├── lib/
│   │   │   └── api/               # Aimeos API client
│   │   ├── store/                 # Zustand stores
│   │   └── types/                 # TypeScript definitions
│   └── package.json
│
└── backend/                       # Laravel 12 + Aimeos 2025.10 LTS (deploys to VPS)
    ├── app/
    │   ├── Http/Controllers/      # API controllers
    │   └── Services/              # Easypay integration
    ├── config/
    │   └── shop.php               # Aimeos configuration
    ├── routes/
    │   └── api.php                # API routes
    └── composer.json
```

## Key Architectural Decisions

### 1. Laravel + Aimeos E-commerce Platform

**Rationale**: Aimeos is a mature, secure PHP e-commerce framework that runs natively on the VPS infrastructure.

**Why Aimeos over alternatives**:
- No major CVEs (unlike Bagisto with 3 XSS vulnerabilities in 2025)
- Best-in-class multi-language support
- Built-in admin panel for content and products
- REST + GraphQL APIs for headless frontend
- Handles both e-commerce AND content management

**Implementation**:
- Products, orders, customers managed in Aimeos
- Shopping cart state in Zustand (frontend) synced with Aimeos API
- Checkout flow via Aimeos checkout controller
- Custom Easypay payment service provider

### 2. Multi-Entity Content Strategy

The platform serves three distinct brands within one codebase:

**Entity Field Pattern**: All Aimeos content includes a `site` identifier:
```php
'site' => 'praia-norte' | 'carsurf' | 'nazare-qualifica'
```

This allows content filtering and ensures proper brand association throughout the platform.

### 3. Internationalization (i18n)

**Languages**: Portuguese (PT) - primary, English (EN) - secondary

**Implementation**:
- Route-based locales: `/pt/loja`, `/en/shop`
- Aimeos built-in i18n (30+ languages supported)
- `next-intl` for frontend UI translations
- All product/content fields support translations

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

## Content Management (Aimeos)

### Built-in E-commerce Data

Aimeos provides built-in managers for e-commerce:

**Product**:
- label (i18n), code (SKU), description (i18n)
- price, stock levels
- images (media), categories
- variants (size, color)
- SEO fields

**Order**:
- Customer details, shipping address
- Order items with prices
- Payment status, delivery status
- Transaction history

**Customer**:
- Registration, authentication
- Address book
- Order history

### Custom Content Types (via Aimeos CMS)

Additional content managed in Aimeos:

**Article** (news/blog):
- title (i18n), slug, content (i18n)
- coverImage, author, category
- entity, tags, featured
- publishedAt

**Event**:
- title (i18n), description (i18n)
- startDate, endDate, location
- entity, image, ticketUrl

**Surfer** (unique to this project):
- name, slug, bio (i18n), photo
- nationality, achievements
- surfboards (relation)
- socialMedia (JSON), featured

**Surfboard**:
- brand, model, length, image
- surfer (relation)

### SEO

All content supports SEO fields:
- metaTitle (i18n), metaDescription (i18n)
- keywords, og:image, canonicalURL

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

> Commands will be added when Phase 0 (Setup) is complete. Until then, see:
> - Backend setup: `docs/phases/FASE_00_SETUP.md`
> - Frontend setup: `docs/phases/FASE_01_DESIGN.md`

### When Starting Implementation

1. **Read** `PLANO_DESENVOLVIMENTO.md` for project overview and architecture
2. **Follow** `docs/phases/` for detailed phase-by-phase implementation guides
3. **Begin with Phase 0** (Setup) - creates Laravel + Aimeos backend + Next.js frontend
4. **Follow phases sequentially** - each phase has dependencies on previous ones
5. **Reference** `docs/archive/E-COMMERCE_PLATFORMS_COMPARISON.md` for architecture rationale

### Phase Overview

The project is organized in 4 blocks, with e-commerce phases at the end (pending SAGE API documentation):

**Block 1 - Foundations**
- **Phase 0**: Project setup, Git, CI/CD, Laravel + Next.js installation
- **Phase 1**: Design system (Next.js), shadcn/ui, layout components

**Block 2 - Institutional**
- **Phase 2**: Homepage and institutional pages
- **Phase 3**: Dynamic content (news, events, surfer wall)

**Block 3 - Quality**
- **Phase 4**: SEO + Performance optimization
- **Phase 5**: Security hardening

**Block 4 - E-commerce** *(pending SAGE API decision)*
- **Phase 6**: E-commerce platform setup
- **Phase 7**: Product catalog
- **Phase 8**: Cart + Checkout
- **Phase 9**: Easypay payment integration
- **Phase 10**: Authentication (Laravel Sanctum)

> **Note**: E-commerce implementation is pending decision between Aimeos and SAGE API integration.

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

### Wave Forecast & Live Webcams

Integration requirements:
- Embed wave forecast widgets (e.g., Magicseaweed, Surfline)
- Live webcam feeds from Praia do Norte and Forte São Miguel Arcanjo
- Real-time weather conditions display

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

- **Full Development Plan**: `PLANO_DESENVOLVIMENTO.md` (Portuguese, overview)
- **Phase-by-Phase Guides**: `docs/phases/` (11 detailed implementation guides)
- **Folder Structure**: `docs/architecture/FOLDER_STRUCTURE.md` (complete project structure)
- **Naming Conventions**: `docs/architecture/NAMING_CONVENTIONS.md` (URLs, slugs, routes)
- **Migration/Deployment Guide**: `MIGRATION_PLAN.md` (VPS, Vercel, Cloudflare setup)
- **E-commerce Analysis**: `docs/archive/E-COMMERCE_PLATFORMS_COMPARISON.md` (21 platforms evaluated)
- **Security Strategy**: `CYBERSECURITY_ASSESSMENT.md`
- **User Roles**: `USER_POLICY_PREVIEW.md`
- **Project Concept**: `docs/concept.txt` (Portuguese, business requirements)
- **Easypay API Spec**: `api/swagger.json`

## Important Notes for Future Claude Instances

1. **E-commerce Decision Pending** - The e-commerce solution is pending decision between Aimeos 2025.10 LTS and SAGE API integration. Laravel 12 remains the backend framework regardless. See `docs/archive/E-COMMERCE_PLATFORMS_COMPARISON.md` for Aimeos rationale.

2. **Praia do Norte is PRIMARY** - When balancing content from the three entities, always prioritize Praia do Norte visibility

3. **Security is non-negotiable** - The client specifically emphasized protection against cyber attacks. Never compromise on security for convenience.

4. **Multi-language from day one** - All content must support PT/EN. Never create single-language solutions.

5. **Easypay credentials are sacred** - Never suggest client-side payment processing. All payment logic must be in Laravel backend.

6. **VPS Constraints** - The VPS has 4GB RAM with swap fully utilized. Do not add Node.js services to the VPS. Frontend runs on Vercel.

7. **CentOS 7 EOL Warning** - The VPS runs CentOS 7 which reached EOL June 2024. Migration to AlmaLinux/Rocky Linux is recommended.

8. **TypeScript for frontend** - Next.js frontend must use TypeScript. Laravel backend uses PHP.

9. **No code exists yet** - This is a planning repository. When implementation begins, follow Phase 0 setup instructions exactly.

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
