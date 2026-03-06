# PLANO DE DESENVOLVIMENTO

## Praia do Norte Unified Platform

**Versão**: 6.0
**Data**: 11 de Dezembro de 2025
**Status**: Migração de Arquitectura em Curso

---

## ÍNDICE

1. [Resumo Executivo](#1-resumo-executivo)
2. [Arquitetura Técnica](#2-arquitetura-técnica)
3. [Estrutura do Projeto](#3-estrutura-do-projeto)
4. [Fases de Implementação](#4-fases-de-implementação)
5. [Estratégia de Segurança](#5-estratégia-de-segurança)
6. [Métricas de Sucesso](#6-métricas-de-sucesso)

---

## 1. RESUMO EXECUTIVO

### 1.1 Visão Geral do Projeto

O projeto "Praia do Norte Unified Platform" visa criar um website único que unifique três entidades municipais da Nazaré:

- **Praia do Norte** (entidade principal) - Marca reconhecida mundialmente pelas ondas gigantes
- **Carsurf** - Centro de Alto Rendimento de Surf
- **Nazaré Qualifica** - Empresa municipal gestora de infraestruturas e serviços

O website funcionará como uma plataforma institucional, informativa e de e-commerce, com a **Praia do Norte como elemento central**.

### 1.2 Objectivos Principais

1. **Unificação Estratégica**: Criar uma experiência digital coesa entre as três entidades
2. **E-commerce Robusto**: Implementar loja online completa para merchandising
3. **Visibilidade Internacional**: Suporte multi-idioma (PT/EN)
4. **Segurança Máxima**: Protecção contra ciberataques
5. **Performance Excepcional**: SEO optimizado e tempos de carregamento rápidos
6. **Integração de Pagamentos**: Sistema completo com Easypay

### 1.3 Mudança de Arquitectura (v6.0)

**Data da Decisão**: 11 de Dezembro de 2025

A arquitectura foi alterada de **split** (Next.js + Laravel API) para **monolítica** (Laravel + Blade + Livewire).

**Motivos**:
- Eliminar problemas de API/CORS/proxy de imagens
- Reduzir superfície de ataque (segurança)
- Simplificar deployment e manutenção
- Facilitar integração futura com Easypay e Sage

---

## 2. ARQUITETURA TÉCNICA

### 2.1 Stack Tecnológica (ACTUALIZADA)

```
                    ┌─────────────────────────────────────┐
                    │         CLOUDFLARE (CDN/SSL)        │
                    └──────────────┬──────────────────────┘
                                   │
                                   ▼
                    ┌─────────────────────────────────────┐
                    │    praiadonortenazare.pt            │
                    │         VPS (cPanel)                │
                    │    Laravel 12 + Blade + Livewire    │
                    │         + Filament Admin            │
                    └─────────────────────────────────────┘
```

| Camada | Tecnologia | Localização |
|--------|-----------|-------------|
| **Frontend Views** | Blade + Livewire | VPS |
| **Styling** | Tailwind CSS 4 | VPS |
| **Backend** | Laravel 12 | VPS |
| **Admin Panel** | Filament 4.x | VPS |
| **Database** | MySQL 8.0 | VPS |
| **Auth** | Laravel Sessions | VPS |
| **Payments** | Easypay v2.0 (futuro) | VPS |
| **i18n** | Laravel Localization | VPS |

### 2.2 Arquitectura Anterior (DEPRECATED)

~~A arquitectura anterior usava:~~
- ~~Next.js 16 no Vercel (frontend)~~
- ~~Laravel API no VPS (backend)~~
- ~~REST API com Sanctum tokens~~

Esta arquitectura foi abandonada devido a problemas técnicos com proxy de imagens e complexidade de CORS.

### 2.3 Infraestrutura VPS

**Servidor**: vm01.cm-nazare.pt

| Recurso | Especificação |
|---------|--------------|
| CPU | 4 vCPUs @ 2.1GHz |
| RAM | 4GB (suficiente para Laravel monolítico) |
| Armazenamento | 114GB livre |
| SO | CentOS 7 (migração recomendada) |
| PHP | 8.3 com FPM |
| MySQL | 8.0.42 |

### 2.4 Benefícios da Nova Arquitectura

| Aspecto | Antes (Split) | Depois (Monolítico) |
|---------|---------------|---------------------|
| Codebases | 2 | 1 |
| Deployments | 2 (Vercel + VPS) | 1 (VPS) |
| Autenticação | API tokens | Sessions (mais seguro) |
| Imagens | Proxy com erros | Directo (funciona) |
| CORS | Configuração complexa | N/A |
| E-commerce | API complexa | Integração directa |

---

## 3. ESTRUTURA DO PROJETO

> **Documentação Completa**: Ver [docs/architecture/FOLDER_STRUCTURE.md](architecture/FOLDER_STRUCTURE.md)

```
praia-do-norte-unified/
├── backend/                        # Laravel 12 + Blade + Livewire
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/        # Web controllers
│   │   │   └── Middleware/         # Localization, etc.
│   │   ├── Livewire/              # Livewire components
│   │   ├── Filament/              # Admin panel
│   │   ├── Models/                # Eloquent models
│   │   └── Services/              # Business logic
│   ├── resources/
│   │   ├── views/
│   │   │   ├── layouts/           # Master layouts
│   │   │   ├── components/        # Blade components (UI)
│   │   │   ├── pages/             # Page views
│   │   │   └── livewire/          # Livewire views
│   │   ├── css/                   # Tailwind CSS
│   │   └── js/                    # JavaScript
│   ├── lang/
│   │   ├── pt/                    # Português
│   │   └── en/                    # English
│   ├── routes/
│   │   └── web.php                # Public routes
│   ├── public/
│   │   └── storage/               # Uploaded files symlink
│   └── database/
│       ├── migrations/
│       └── seeders/
│
├── frontend/                       # DEPRECATED (arquivar após migração)
│   └── (Next.js - referência para conversão)
│
└── docs/
    ├── phases/                     # Guias de implementação
    ├── architecture/               # Documentação técnica
    └── archive/                    # Documentos históricos
```

---

## 4. FASES DE IMPLEMENTAÇÃO

O desenvolvimento está dividido em **11 fases** organizadas em **4 blocos**.

### Bloco 1: Fundações ✅ Completo

| Fase | Nome | Descrição | Status |
|------|------|-----------|--------|
| **0** | Setup | Laravel 12 + Filament 4 | ✅ Completo |
| **1** | Design | Tailwind CSS, componentes base | ✅ Completo |

### Bloco 2: Institucional 🔄 Migração em Curso

| Fase | Nome | Descrição | Status |
|------|------|-----------|--------|
| **2** | Homepage | CMS backend, seeders | ✅ Completo |
| **3** | Conteúdo | Notícias, Surfers, Eventos | 🔄 Migrar para Blade |

**Tarefas de Migração (Fase 3)**:
1. [ ] Configurar Tailwind + Vite no Laravel
2. [ ] Instalar Livewire + Laravel Localization
3. [ ] Criar layout master Blade
4. [ ] Converter componentes UI (button, card, badge)
5. [ ] Converter Homepage
6. [ ] Converter Notícias (listagem + detalhe)
7. [ ] Converter Eventos (listagem + detalhe)
8. [ ] Converter Surfer Wall (listagem + detalhe)
9. [ ] Converter Previsões marítimas
10. [ ] Converter Carsurf pages
11. [ ] Converter Nazaré Qualifica pages
12. [ ] Testar i18n (PT/EN)

### Bloco 3: Qualidade (Após Migração)

| Fase | Nome | Descrição | Status |
|------|------|-----------|--------|
| **4** | SEO | Meta tags, structured data | ⏳ Pendente |
| **5** | Segurança | Headers, rate limiting, validação | ⏳ Pendente |

### Bloco 4: E-commerce (Futuro)

| Fase | Nome | Descrição | Status |
|------|------|-----------|--------|
| **6** | E-commerce Setup | Laravel nativo ou WooCommerce | ⏳ Pendente |
| **7** | Catálogo | Listagem, página de produto | ⏳ Pendente |
| **8** | Checkout | Carrinho, fluxo de compra | ⏳ Pendente |
| **9** | Pagamentos | Integração Easypay | ⏳ Pendente |
| **10** | Área Cliente | Auth completa, histórico | ⏳ Pendente |

**Nota**: A decisão sobre e-commerce nativo vs WooCommerce headless aguarda validação do conector Sage.

---

## 5. ESTRATÉGIA DE SEGURANÇA

### 5.1 Princípios Fundamentais

1. **Arquitectura Monolítica**: Menos superfície de ataque (sem API exposta)
2. **Sessions, não Tokens**: Autenticação baseada em sessões é mais segura
3. **Server-Side Validation**: Toda validação feita no servidor
4. **CSRF Protection**: Protecção nativa do Laravel em todos os forms

### 5.2 Medidas Implementadas

| Camada | Medida | Implementação |
|--------|--------|---------------|
| **CDN** | WAF + DDoS | Cloudflare |
| **Application** | CSRF | Laravel built-in |
| **Application** | XSS | Blade auto-escaping |
| **Application** | SQL Injection | Eloquent ORM |
| **Auth** | Sessions | Laravel Sessions |
| **Payments** | Server-only | Easypay PHP SDK |
| **Webhooks** | HMAC | Signature validation |

### 5.3 Vantagens de Segurança (Monolítico)

- **Sem API exposta**: Não há endpoints públicos para atacar
- **Sem CORS**: Não há configuração de CORS para errar
- **Sessions**: Mais seguras que JWT tokens
- **Server-side rendering**: Menos exposição de lógica no cliente

### 5.4 Conformidade GDPR

- Minimização de dados
- Consentimento explícito para marketing
- Cookie banner com opt-in
- Direito ao esquecimento
- Política de privacidade clara

---

## 6. MÉTRICAS DE SUCESSO

### 6.1 Performance

| Métrica | Target |
|---------|--------|
| Lighthouse Performance | > 90 |
| Lighthouse Accessibility | > 95 |
| Lighthouse SEO | > 95 |
| TTFB | < 200ms |
| LCP | < 2.5s |
| CLS | < 0.1 |

### 6.2 Segurança

| Métrica | Target |
|---------|--------|
| Security Headers | Grade A |
| SSL Labs | A+ |
| composer audit | 0 críticas |

### 6.3 Funcionalidade

| Métrica | Target |
|---------|--------|
| Uptime | > 99.9% |
| Tempo checkout | < 3 min |
| Taxa conversão | > 2% |

---

## Documentação Relacionada

- **Referência Técnica**: [CLAUDE.md](../CLAUDE.md)
- **Handoff de Sessão**: [SESSION-HANDOFF.md](../SESSION-HANDOFF.md)
- **Estrutura de Pastas**: [docs/architecture/FOLDER_STRUCTURE.md](architecture/FOLDER_STRUCTURE.md)
- **Convenções de Nomenclatura**: [docs/architecture/NAMING_CONVENTIONS.md](architecture/NAMING_CONVENTIONS.md)
- **Guia de Deploy**: [MIGRATION_PLAN.md](MIGRATION_PLAN.md)
- **Segurança**: [CYBERSECURITY_ASSESSMENT.md](CYBERSECURITY_ASSESSMENT.md)
- **Design**: [DESIGN_GUIDELINES.md](design/DESIGN_GUIDELINES.md)

---

## Histórico de Versões

| Versão | Data | Alterações |
|--------|------|------------|
| 1.0 | 2025-11-19 | Documento inicial (Strapi + NextAuth.js) |
| 2.0 | 2025-11-25 | Aviso de mudança arquitetural |
| 3.0 | 2025-11-25 | Reescrita para Laravel + Aimeos + MySQL |
| 4.0 | 2025-11-25 | Reorganização: fases extraídas para docs/phases/ |
| 5.0 | 2025-12-03 | E-commerce movido para final; aguarda API SAGE |
| **6.0** | **2025-12-11** | **Migração para arquitectura monolítica (Blade + Livewire)** |

---

*Documento criado como parte do projeto Praia do Norte Unified Platform*
