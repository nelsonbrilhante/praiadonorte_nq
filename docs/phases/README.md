# Plano de Implementação por Fases

## Praia do Norte Unified Platform

Este diretório contém o plano de implementação dividido em **4 blocos** e **11 fases**.

> **Nota**: Arquitectura actualizada para Laravel Monolítico (Blade + Livewire) em 11 Dez 2025.
> As fases de e-commerce (Bloco 4) aguardam definição da integração com API SAGE.

---

## Visão Geral das Fases

### Bloco 1: Fundações

| Fase | Nome | Descrição | Status |
|------|------|-----------|--------|
| **0** | [Setup](./FASE_00_SETUP.md) | Laravel 12 + Blade + Livewire, CI/CD | ✅ Completo |
| **1** | [Design](./FASE_01_DESIGN.md) | Tailwind, Blade Components, layout | ✅ Completo |

### Bloco 2: Institucional

| Fase | Nome | Descrição | Status |
|------|------|-----------|--------|
| **2** | [Homepage](./FASE_02_HOMEPAGE.md) | Homepage e páginas institucionais | ✅ Completo |
| **3** | [Conteúdo](./FASE_03_CONTEUDO.md) | Notícias, Surfer Wall, Eventos | 🔄 Migrar para Blade |

### Bloco 3: Qualidade

| Fase | Nome | Descrição | Status |
|------|------|-----------|--------|
| **4** | [SEO](./FASE_04_SEO.md) | Meta tags, structured data, sitemap | ⏳ Pendente |
| **5** | [Segurança](./FASE_05_SEGURANCA.md) | Headers, rate limiting, validação | ⏳ Pendente |

### Bloco 4: E-commerce (Aguarda API SAGE)

| Fase | Nome | Descrição | Status |
|------|------|-----------|--------|
| **6** | [E-commerce Setup](./FASE_06_ECOMMERCE.md) | WooCommerce ou integração SAGE | ⏳ Pendente |
| **7** | [Catálogo](./FASE_07_CATALOGO.md) | Listagem e página de produto | ⏳ Pendente |
| **8** | [Checkout](./FASE_08_CHECKOUT.md) | Carrinho e fluxo de compra | ⏳ Pendente |
| **9** | [Pagamentos](./FASE_09_EASYPAY.md) | Integração Easypay | ⏳ Pendente |
| **10** | [Área Cliente](./FASE_10_AUTH.md) | Auth completa, histórico | ⏳ Pendente |

---

## Diagrama de Dependências

```
BLOCOS 1-3: INSTITUCIONAL (pode começar já)
===========================================

Fase 0 (Setup)
    │
    └── Fase 1 (Design)
            │
            └── Fase 2 (Homepage)
                    │
                    └── Fase 3 (Conteúdo)
                            │
                            └── Fase 4 (SEO)
                                    │
                                    └── Fase 5 (Segurança)
                                            │
                                            ▼
                                  [SITE INSTITUCIONAL ✓]


BLOCO 4: E-COMMERCE (após decisão API SAGE)
===========================================

                                            │
                              ┌─────────────┴─────────────┐
                              │   Análise API SAGE        │
                              │   Decisão: WooCommerce    │
                              └─────────────┬─────────────┘
                                            │
                                            ▼
                              Fase 6 (E-commerce Setup)
                                            │
                                            └── Fase 7 (Catálogo)
                                                    │
                                                    └── Fase 8 (Checkout)
                                                            │
                                                            └── Fase 9 (Easypay)
                                                                    │
                                                                    └── Fase 10 (Área Cliente)
                                                                            │
                                                                            ▼
                                                                    [E-COMMERCE ✓]
```

---

## Stack Tecnológica (ACTUALIZADA)

| Componente | Tecnologia | Localização |
|------------|------------|-------------|
| Frontend Views | Blade + Livewire | VPS |
| Styling | Tailwind CSS 4 | VPS |
| Backend | Laravel 12 | VPS |
| Admin Panel | Filament 4.x | VPS |
| Database | MySQL 8.0 | VPS |
| Auth | Laravel Sessions | VPS |
| Payments | Easypay v2.0 | VPS |
| i18n | Laravel Localization | VPS |
| E-commerce | WooCommerce (futuro) | VPS |

---

## Arquitectura Monolítica (11 Dez 2025)

A arquitectura foi alterada de **split** (Next.js + Laravel API) para **monolítica** (Laravel + Blade + Livewire).

**Benefícios**:
- Elimina problemas de API/CORS/imagens
- Menor superfície de ataque (segurança)
- Um único deployment (VPS)
- Integração directa com Easypay/Sage

---

## Documentação Relacionada

- [Plano Geral](../PLANO_DESENVOLVIMENTO.md) - Visão executiva
- [CLAUDE.md](../../CLAUDE.md) - Referência técnica principal
- [SESSION-HANDOFF.md](../../SESSION-HANDOFF.md) - Estado actual e continuidade
- [Migration Plan](../MIGRATION_PLAN.md) - Deploy para produção
- [Security](../CYBERSECURITY_ASSESSMENT.md) - Avaliação de segurança
- [Livewire Guide](../tech-stack/LIVEWIRE_3.md) - Guia de componentes Livewire

---

*Actualizado: 11 Dezembro 2025 - Migração para arquitectura monolítica*
