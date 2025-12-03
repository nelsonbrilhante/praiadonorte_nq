# PLANO DE DESENVOLVIMENTO

## Praia do Norte Unified Platform

**Versão**: 5.0
**Data**: 3 de Dezembro de 2025
**Status**: Pronto para Implementação (Blocos 1-3)

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

### 1.2 Objetivos Principais

1. **Unificação Estratégica**: Criar uma experiência digital coesa entre as três entidades
2. **E-commerce Robusto**: Implementar loja online completa para merchandising
3. **Visibilidade Internacional**: Suporte multi-idioma (PT/EN)
4. **Segurança Máxima**: Proteção contra ciberataques
5. **Performance Excecional**: SEO otimizado e tempos de carregamento rápidos
6. **Integração de Pagamentos**: Sistema completo com Easypay

### 1.3 Desafios Identificados

- Balancear conteúdo das três entidades mantendo Praia do Norte como foco
- Segurança robusta contra ciberataques
- Gestão de conteúdo multi-idioma consistente
- Performance com grande volume de conteúdo multimédia

---

## 2. ARQUITETURA TÉCNICA

### 2.1 Stack Tecnológica

```
                    ┌─────────────────────────────────────┐
                    │         CLOUDFLARE (CDN/SSL)        │
                    └──────────────┬──────────────────────┘
                                   │
            ┌──────────────────────┼──────────────────────┐
            │                      │                      │
            ▼                      ▼                      ▼
┌───────────────────┐  ┌───────────────────┐  ┌───────────────────┐
│ praiadonortenazare│  │api.praiadonorte   │  │ Redirects (301)   │
│       .pt         │  │   nazare.pt       │  │                   │
│   VERCEL          │  │   VPS (cPanel)    │  │ carsurf.nazare.pt │
│   (Next.js 15)    │  │   (Laravel 12)    │  │ nazarequalifica.pt│
└───────────────────┘  └───────────────────┘  └───────────────────┘
```

| Camada | Tecnologia | Localização |
|--------|-----------|-------------|
| **Frontend** | Next.js 15 + TypeScript | Vercel |
| **UI** | Tailwind CSS + shadcn/ui | Vercel |
| **State** | Zustand | Vercel |
| **Backend** | Laravel 12 | VPS |
| **E-commerce** | Aimeos ou API SAGE (pendente) | VPS |
| **Database** | MySQL 8.0 | VPS |
| **Auth** | Laravel Sanctum | VPS |
| **Payments** | Easypay v2.0 | VPS |
| **i18n** | next-intl + Aimeos | Ambos |

### 2.2 Infraestrutura VPS

**Servidor**: vm01.cm-nazare.pt

| Recurso | Especificação |
|---------|--------------|
| CPU | 4 vCPUs @ 2.1GHz |
| RAM | 4GB |
| Armazenamento | 114GB livre |
| SO | CentOS 7 |
| PHP | 8.3 com FPM |
| MySQL | 8.0.42 |

### 2.3 Decisões Arquiteturais

**Por que Laravel + Aimeos?**
- VPS suporta PHP 8.3 (via EasyApache 4) + MySQL 8.0
- Aimeos não tem CVEs críticos conhecidos
- i18n nativo superior (30+ idiomas)

**Por que Next.js no Vercel?**
- Offload de processamento do VPS
- CDN global para performance
- Free tier suficiente

---

## 3. ESTRUTURA DO PROJETO

> **Documentação Completa**: Ver [docs/architecture/FOLDER_STRUCTURE.md](docs/architecture/FOLDER_STRUCTURE.md)

```
praia-do-norte-unified/
├── frontend/                    # Next.js 15 (Vercel)
│   ├── src/
│   │   ├── app/[locale]/        # Rotas i18n
│   │   │   ├── (praia-norte)/   # Route group - marca principal
│   │   │   ├── (carsurf)/       # Route group - Carsurf
│   │   │   └── (nazare-qualifica)/ # Route group - NQ
│   │   ├── components/          # UI components
│   │   ├── lib/api/             # API client
│   │   ├── store/               # Zustand stores
│   │   └── types/               # TypeScript definitions
│   └── package.json
│
├── backend/                     # Laravel 12 (VPS)
│   ├── app/
│   │   ├── Http/Controllers/    # API controllers
│   │   ├── Models/              # Eloquent models
│   │   └── Services/            # Business logic
│   ├── config/                  # Configurações
│   └── composer.json
│
└── docs/
    ├── phases/                  # Guias de implementação
    ├── architecture/            # Documentação técnica
    └── archive/                 # Documentos históricos
```

---

## 4. FASES DE IMPLEMENTAÇÃO

O desenvolvimento está dividido em **11 fases** organizadas em **4 blocos**. As fases de e-commerce foram movidas para o final, aguardando definição da integração com API SAGE.

> **Nota**: A decisão sobre Aimeos vs API SAGE está pendente. O Bloco 4 será definido após análise da documentação SAGE.

### Bloco 1: Fundações (Semanas 1-2)

| Fase | Nome | Descrição | Documentação |
|------|------|-----------|--------------|
| **0** | Setup | Laravel 12 + Next.js 15, CI/CD | [FASE_00_SETUP.md](docs/phases/FASE_00_SETUP.md) |
| **1** | Design | Tailwind, shadcn/ui, layout | [FASE_01_DESIGN.md](docs/phases/FASE_01_DESIGN.md) |

### Bloco 2: Institucional (Semanas 3-4)

| Fase | Nome | Descrição | Documentação |
|------|------|-----------|--------------|
| **2** | Homepage | Páginas institucionais, i18n | [FASE_02_HOMEPAGE.md](docs/phases/FASE_02_HOMEPAGE.md) |
| **3** | Conteúdo | Notícias, Surfer Wall, Eventos | [FASE_03_CONTEUDO.md](docs/phases/FASE_03_CONTEUDO.md) |

### Bloco 3: Qualidade (Semana 5)

| Fase | Nome | Descrição | Documentação |
|------|------|-----------|--------------|
| **4** | SEO | Meta tags, structured data | [FASE_04_SEO.md](docs/phases/FASE_04_SEO.md) |
| **5** | Segurança | Headers, rate limiting, validação | [FASE_05_SEGURANCA.md](docs/phases/FASE_05_SEGURANCA.md) |

### Bloco 4: E-commerce (Semanas 6-10) ⏸️ Aguarda API SAGE

| Fase | Nome | Descrição | Documentação |
|------|------|-----------|--------------|
| **6** | E-commerce Setup | Aimeos OU integração SAGE | [FASE_06_ECOMMERCE.md](docs/phases/FASE_06_ECOMMERCE.md) |
| **7** | Catálogo | Listagem, página de produto | [FASE_07_CATALOGO.md](docs/phases/FASE_07_CATALOGO.md) |
| **8** | Checkout | Carrinho, fluxo de compra | [FASE_08_CHECKOUT.md](docs/phases/FASE_08_CHECKOUT.md) |
| **9** | Pagamentos | Integração Easypay | [FASE_09_EASYPAY.md](docs/phases/FASE_09_EASYPAY.md) |
| **10** | Área Cliente | Auth completa, histórico | [FASE_10_AUTH.md](docs/phases/FASE_10_AUTH.md) |

**Índice completo**: [docs/phases/README.md](docs/phases/README.md)

---

## 5. ESTRATÉGIA DE SEGURANÇA

### 5.1 Princípios Fundamentais

1. **Zero Trust no Frontend**: Nunca confiar em dados vindos do cliente
2. **Menor Privilégio**: Cada componente tem apenas permissões necessárias
3. **Defesa em Profundidade**: Múltiplas camadas de segurança
4. **Segurança por Design**: Integrada desde o início

### 5.2 Medidas Implementadas

| Camada | Medida | Implementação |
|--------|--------|---------------|
| **CDN** | WAF | Cloudflare Managed Rules |
| **CDN** | DDoS | Cloudflare (automático) |
| **Frontend** | Headers | next.config.js |
| **Frontend** | Validation | Zod |
| **Backend** | Auth | Laravel Sanctum |
| **Backend** | Rate Limiting | Laravel Throttle |
| **Database** | Queries | Eloquent ORM |
| **Payments** | Server-only | EasypayService |
| **Webhooks** | HMAC | ValidateEasypayWebhook |

### 5.3 Conformidade GDPR

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
| LCP | < 2.5s |
| FID | < 100ms |
| CLS | < 0.1 |

### 6.2 Segurança

| Métrica | Target |
|---------|--------|
| Security Headers | Grade A |
| SSL Labs | A+ |
| npm audit | 0 críticas |
| composer audit | 0 críticas |

### 6.3 Funcionalidade

| Métrica | Target |
|---------|--------|
| Uptime | > 99.9% |
| Tempo checkout | < 3 min |
| Taxa conversão | > 2% |

---

## Documentação Relacionada

- **Referência Técnica**: [CLAUDE.md](CLAUDE.md)
- **Estrutura de Pastas**: [docs/architecture/FOLDER_STRUCTURE.md](docs/architecture/FOLDER_STRUCTURE.md)
- **Convenções de Nomenclatura**: [docs/architecture/NAMING_CONVENTIONS.md](docs/architecture/NAMING_CONVENTIONS.md)
- **Guia de Deploy**: [MIGRATION_PLAN.md](MIGRATION_PLAN.md)
- **Segurança**: [CYBERSECURITY_ASSESSMENT.md](CYBERSECURITY_ASSESSMENT.md)
- **Utilizadores**: [USER_POLICY_PREVIEW.md](USER_POLICY_PREVIEW.md)
- **Design**: [DESIGN_GUIDELINES.md](DESIGN_GUIDELINES.md)
- **Análise E-commerce**: [docs/archive/E-COMMERCE_PLATFORMS_COMPARISON.md](docs/archive/E-COMMERCE_PLATFORMS_COMPARISON.md)

---

## Histórico de Versões

| Versão | Data | Alterações |
|--------|------|------------|
| 1.0 | 2025-11-19 | Documento inicial (Strapi + NextAuth.js) |
| 2.0 | 2025-11-25 | Aviso de mudança arquitetural |
| 3.0 | 2025-11-25 | Reescrita para Laravel + Aimeos + MySQL |
| 4.0 | 2025-11-25 | Reorganização: fases extraídas para docs/phases/ |
| 5.0 | 2025-12-03 | E-commerce movido para final; aguarda API SAGE |

---

*Documento criado como parte do projeto Praia do Norte Unified Platform*
