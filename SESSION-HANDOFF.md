# Session Handoff - Praia do Norte

> Este ficheiro serve como ponto de continuidade entre sessões de desenvolvimento.
> Lê-lo no início de cada sessão para retomar o contexto.

---

## Última Sessão

- **Data**: 2026-03-04 (sessão 4)
- **Resumo**: Shipping E2E verificado + SMTP email configurado e testado com sucesso
- **Branch**: `main`

### O que foi feito:

1. **Shipping Rates E2E Verificado (Playwright)**
   - Todos os produtos têm pesos atribuídos (0.3–0.5 kg)
   - T-Shirt no carrinho → €6.80 shipping ✅
   - Local Pickup "Recolha no Forte de S. Miguel Arcanjo" (grátis) disponível ✅
   - Rate table funciona correctamente conforme plugin `pn-table-rate-shipping`

2. **SMTP Email Configurado**
   - Conta `store@nazarequalifica.pt` criada no WHM (cPanel `nq`)
   - Plugin `wp-mail-smtp` configurado no WP Admin via Playwright
   - **SMTP host discovery**: `mail.nazarequalifica.pt` resolve para VPS (sem mail server) → testado `whm.cm-nazare.pt` (SSL cert mismatch) → **`vm01.cm-nazare.pt:465/SSL` funciona**
   - Config final: `vm01.cm-nazare.pt`, porta 465, SSL, auth `store@nazarequalifica.pt`
   - Test email enviado com sucesso para `zumuha@gmail.com` ✅

3. **Entrypoint.sh actualizado (Phase 6.7)**
   - Adicionado bloco SMTP config que corre em todos os deploys (não gated por IS_FIRST_RUN)
   - Defaults: `vm01.cm-nazare.pt:465/ssl`
   - Usa env vars: `SMTP_HOST`, `SMTP_PORT`, `SMTP_ENCRYPTION`, `SMTP_USER`, `SMTP_PASSWORD`, `SMTP_FROM`, `SMTP_FROM_NAME`

4. **WooCommerce Email Templates Verificados**
   - "From" address actualizado de `admin@praiadonorte.pt` → `store@nazarequalifica.pt`
   - "From" name: "Praia do Norte – Loja" ✅
   - Recipient "New order": `nelsonbrilhante@gmail.com` ✅
   - Recipients "Cancelled order" e "Failed order": actualizados de `admin@praiadonorte.pt` → `nelsonbrilhante@gmail.com`
   - Todos os 14 email templates activos e configurados correctamente

5. **Ficheiros modificados**
   - `wordpress/coolify/entrypoint.sh` — Phase 6.7 SMTP config (defaults corrigidos para vm01.cm-nazare.pt:465/ssl)
   - `.credentials.md` — Secção SMTP adicionada com credenciais e env vars correctos

### Acção pendente (manual):
- **Coolify env vars**: Adicionar ao serviço WooCommerce no Coolify:
  ```
  SMTP_HOST=vm01.cm-nazare.pt
  SMTP_PORT=465
  SMTP_ENCRYPTION=ssl
  SMTP_USER=store@nazarequalifica.pt
  SMTP_PASSWORD=StoreNQ-2026!Smtp
  SMTP_FROM=store@nazarequalifica.pt
  SMTP_FROM_NAME=Praia do Norte - Loja
  ```
- **Redeploy WooCommerce** após adicionar env vars para que o entrypoint.sh aplique a config

### Sessão anterior (2026-03-04, sessão 3):

- Teste E2E Easypay WooCommerce — integração bloqueada por "Connection not validated by easypay"
- Requer contacto com suporte Easypay

### Sessão anterior (2026-03-04, sessão 2):

- Migração de domínios — 4 novos domínios configurados e verificados
- Store domain fix no Coolify, WP siteurl/home actualizado
- Variáveis de ambiente Coolify actualizadas
- Docker network fix

---

## Estado Actual do Projecto

| Item | Valor |
|------|-------|
| **Fase** | Produção (deployed) + QA contínuo |
| **Branch** | `main` |
| **Stack** | Laravel 12 + Filament 4.x + MySQL 8.0 + WooCommerce (Coolify) |
| **Produção Laravel** | `nazarequalifica.pt` (+ `praiadonortenazare.pt`, `carsurf.nazare.pt`) |
| **Produção WooCommerce** | `store.praiadonortenazare.pt` |
| **Domínios antigos (activos)** | `nq.nelsonbrilhante.com`, `store-nq.nelsonbrilhante.com` |
| **Dev Laravel** | `localhost:8000` |
| **Dev WordPress** | `localhost:8080` |
| **CI/CD** | Push to `main` → Coolify webhook (Laravel). WordPress deploy manual via Coolify. |

---

## Filament Admin - Organização

### Estrutura do Menu

```
📊 Dashboard

🏠 Geral
   └── Homepage

🏋️ Carsurf
   └── Páginas

🏢 Nazaré Qualifica
   └── Páginas

📰 Conteúdo
   ├── Notícias
   └── Eventos

🌊 Praia do Norte
   ├── Páginas
   └── Surfers

🌐 Website
   └── Ver Website (abre em nova aba)
```

**Nota**: Resource `Pranchas` (Surfboard) foi eliminada. Surfer foi simplificado com campos `aka`, `quote`, `board_image`, `social_media` directamente no modelo.

### Resources por Entidade

```
backend/app/Filament/Resources/
├── Geral/
│   ├── HomepageResource.php
│   └── Pages/
├── Paginas/
│   └── BasePageResource.php          # Classe base abstracta
├── PraiaNorte/
│   └── PraiaNortePageResource.php
├── Carsurf/
│   └── CarsurfPageResource.php
├── NazareQualifica/
│   └── NQPageResource.php
└── Surfers/
    ├── SurferResource.php
    ├── Schemas/SurferForm.php
    ├── Tables/SurfersTable.php
    └── Pages/
```

---

## Content Models (actual)

| Model | Campos Chave |
|-------|-------------|
| **Surfer** | name, slug, aka, bio (i18n), quote (i18n), photo, board_image, social_media, featured, order |
| **Noticia** | title (i18n), slug, content (i18n), excerpt (i18n), cover_image, author, category, entity, tags, featured, published_at |
| **Evento** | Dates, location, entity, gallery, schedule, partners |
| **Pagina** | i18n content, entity, hero_image. `hasMany(HeroSlide)` |
| **HeroSlide** | Homepage hero slides (até 5). Video/image, LIVE badge, auto-rotate |
| **CorporateBody** | Corpos Sociais (NQ). Secções: conselho_gerencia, assembleia_geral, fiscal_unico |
| **DocumentCategory** | i18n name/description, ordered. `hasMany(Document)` |
| **Document** | PDF uploads per category, i18n title |

**Nota**: Modelo `Surfboard` foi eliminado. Campos de surfboard movidos directamente para `Surfer` (`board_image`). Campos SEO removidos de `Noticia`.

---

## Próximas Tarefas

### Prioridade Alta — WooCommerce & Pagamentos
1. [ ] Integrar Easypay com WooCommerce (Multibanco, MBWay, Cartão de Crédito)
2. [ ] Campo NIF/CIF/NIE nas facturas
3. [ ] Locais de levantamento em loja física (pickup locations)

### Prioridade Alta — Conteúdo
4. [ ] Rever e corrigir texto de surfers e notícias (PT-PT, AO90, formatação)
5. [ ] Traduzir conteúdo para EN (notícias, eventos, surfers)
6. [ ] Melhorar distribuição de cards na edição de surfers no backend

### Prioridade Média — QA
7. [ ] Testes funcionais de todas as páginas
8. [ ] Verificar responsividade (mobile, tablet, desktop)
9. [ ] Lighthouse audit (target: >90 em todas as métricas)

### Prioridade Média — Segurança (Phase 5)
10. [ ] Security headers (CSP, HSTS, X-Frame-Options)
11. [ ] Rate limiting nas rotas públicas
12. [ ] Input sanitization audit

---

## Gaps Conhecidos

| Item | Estado | Notas |
|------|--------|-------|
| Formulário de Contacto backend | ⚠️ Não implementado | `action="#"` sem handler POST |
| Easypay WooCommerce | 🔴 Bloqueado | Plugin instalado e configurado, mas "Connection not validated by easypay" bloqueia checkout. Requer suporte Easypay. |
| Easypay Success URL bug | ⚠️ Bug plugin | `epEasypaySuccessApi` tem `wp-json/wp-json/...` duplicado |
| NIF/CIF em facturas | ⚠️ Não implementado | Campo custom no checkout |
| Pickup locations | ⚠️ Não implementado | Local store pickup |
| Conteúdo EN | ⚠️ Parcial | Estrutura i18n pronta, falta tradução |
| Shipping WooCommerce | ✅ Verificado E2E | Plugin table-rate funcional, rates correctos, Local Pickup activo |
| SMTP Email WooCommerce | ✅ Configurado | `vm01.cm-nazare.pt:465/SSL`, test email OK. Falta: adicionar env vars no Coolify + redeploy |

---

## Arquivo — Sessões Anteriores (Dez 2025)

<details>
<summary>Sessões de Dezembro 2025 (clique para expandir)</summary>

### 2025-12-19 — Merge Hero Slider + Dark Mode
- PR #1 merged: Hero Slider + cleanup logos
- Verificação dark mode (prefers-color-scheme)
- Branch `feature/hero-slider` eliminada

### 2025-12-18 — Hero Slider
- Convertido Hero Section para slider com até 5 slides
- Auto-rotação, pausa LIVE, transições fade
- Indicadores pill/circular com progresso SVG
- Filament Repeater com drag & drop

### 2025-12-17 (tarde) — Fix botões + Contraordenações
- Fix botões invisíveis (outline variant + bg-transparent)
- Nova página Contraordenações NQ com 6 PDFs
- Botões navegação no header Sobre NQ

### 2025-12-17 (manhã) — Forecast API + uploads + favicon
- Migração Forecast API (Open-Meteo Marine + Weather)
- Fix FileUpload disk (local → public)
- Favicon e app icons
- Dark mode logo no header

### 2025-12-16 (tarde) — Search fix + dark theme
- Search Spotlight: Livewire → Alpine.js (fix 404)
- Tema dark/light persistente com localStorage

### 2025-12-16 (manhã) — Páginas legais + logo header
- Páginas /privacidade, /termos, /cookies
- Logo no header (transparente/sólido)
- Botão idioma invertido (mostra destino)

### 2025-12-15 — YouTube captcha fix
- Embed youtube.com → youtube-nocookie.com

### 2025-12-12 — Logo dinâmico Hero Section
- Campos hero_logo, hero_use_logo, hero_logo_height
- Toggle texto/imagem no admin + slider tamanho

</details>

---

## Notas Técnicas Importantes

### Filament 4 - Namespaces

```php
// Correcto no Filament 4
use Filament\Actions\EditAction;        // ✅
use Filament\Actions\DeleteAction;      // ✅

// Incorrecto (Filament 3)
use Filament\Tables\Actions\EditAction; // ❌
```

### Entity Filter nas Queries

Cada Resource de páginas filtra por `entity`:
- `praia-norte` — Praia do Norte (exclui homepage)
- `carsurf` — Carsurf
- `nazare-qualifica` — Nazaré Qualifica
- Homepage usa query `where('slug', 'homepage')` (sem filtro de entity)

### Hidratação de Campos JSON Aninhados

O Filament 4 não hidrata automaticamente campos com paths como `content.pt.intro.title`. Solução:

```php
TextInput::make('content.pt.intro.title')
    ->afterStateHydrated(fn ($state, $set, $record) =>
        $set('content.pt.intro.title', $record?->content['pt']['intro']['title'] ?? $state))
```

---

## Como Continuar

```bash
# 1. Ler este ficheiro para contexto
# 2. Iniciar servidor
cd backend && composer dev    # Full dev environment (server + queue + logs + vite)

# 3. Aceder ao admin
open http://localhost:8000/admin

# 4. Produção
# Laravel: nazarequalifica.pt (+ praiadonortenazare.pt, carsurf.nazare.pt)
# WooCommerce: store.praiadonortenazare.pt
# Antigos (ainda activos): nq.nelsonbrilhante.com, store-nq.nelsonbrilhante.com

# 5. Continuar com tarefas prioritárias
# 6. Actualizar este ficheiro no final da sessão
```
