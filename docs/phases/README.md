# Plano de Implementação por Fases

## Praia do Norte Unified Platform

Este diretório contém o plano de implementação dividido em **4 blocos** e **11 fases**.

> **Nota**: As fases de e-commerce (Bloco 4) foram movidas para o final, aguardando definição da integração com API SAGE.

---

## Visão Geral das Fases

### Bloco 1: Fundações (Semanas 1-2)

| Fase | Nome | Descrição | Dependências |
|------|------|-----------|--------------|
| **0** | [Setup](./FASE_00_SETUP.md) | Laravel 12 + Next.js 15, CI/CD | - |
| **1** | [Design](./FASE_01_DESIGN.md) | Tailwind, shadcn/ui, layout | Fase 0 |

### Bloco 2: Institucional (Semanas 3-4)

| Fase | Nome | Descrição | Dependências |
|------|------|-----------|--------------|
| **2** | [Homepage](./FASE_02_HOMEPAGE.md) | Homepage e páginas institucionais | Fase 1 |
| **3** | [Conteúdo](./FASE_03_CONTEUDO.md) | Notícias, Surfer Wall, Eventos | Fase 2 |

### Bloco 3: Qualidade (Semana 5)

| Fase | Nome | Descrição | Dependências |
|------|------|-----------|--------------|
| **4** | [SEO](./FASE_04_SEO.md) | Meta tags, structured data, sitemap | Fase 3 |
| **5** | [Segurança](./FASE_05_SEGURANCA.md) | Headers, rate limiting, validação | Fase 4 |

### Bloco 4: E-commerce (Semanas 6-10) - Aguarda API SAGE

| Fase | Nome | Descrição | Dependências |
|------|------|-----------|--------------|
| **6** | [E-commerce Setup](./FASE_06_ECOMMERCE.md) | Aimeos OU integração SAGE | Fase 5 + Decisão SAGE |
| **7** | [Catálogo](./FASE_07_CATALOGO.md) | Listagem e página de produto | Fase 6 |
| **8** | [Checkout](./FASE_08_CHECKOUT.md) | Carrinho e fluxo de compra | Fase 7 |
| **9** | [Pagamentos](./FASE_09_EASYPAY.md) | Integração Easypay | Fase 8 |
| **10** | [Área Cliente](./FASE_10_AUTH.md) | Auth completa, histórico | Fase 9 |

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
                              │   Decisão: Aimeos/SAGE    │
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

## Como Usar

1. **Leia primeiro**: `../PLANO_DESENVOLVIMENTO.md` para visão geral
2. **Execute por blocos**: Blocos 1-3 podem começar imediatamente
3. **Verifique entregáveis**: Cada fase tem checklist de conclusão
4. **Bloco 4**: Aguarda documentação API SAGE antes de iniciar

---

## Stack Tecnológica

| Componente | Tecnologia |
|------------|------------|
| Frontend | Next.js 15 (Vercel) |
| Backend | Laravel 12 (VPS) |
| Database | MySQL 8.0 |
| Auth | Laravel Sanctum |
| Payments | Easypay v2.0 |
| E-commerce | Aimeos ou API SAGE (pendente) |

---

## Documentação Relacionada

- [Plano Geral](../PLANO_DESENVOLVIMENTO.md) - Visão executiva
- [CLAUDE.md](../../CLAUDE.md) - Referência técnica principal
- [Migration Plan](../MIGRATION_PLAN.md) - Deploy para produção
- [Security](../../CYBERSECURITY_ASSESSMENT.md) - Avaliação de segurança
