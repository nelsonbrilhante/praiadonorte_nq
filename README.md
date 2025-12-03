# Praia do Norte Unified Platform

Plataforma web unificada que integra três marcas da **Nazaré Qualifica, EM**:

- **Praia do Norte** - Marca principal das ondas gigantes e merchandising
- **Carsurf** - Centro de Alto Rendimento de Surf
- **Nazaré Qualifica** - Serviços e infraestruturas municipais

## Stack Tecnológica

| Componente | Tecnologia | Deploy |
|------------|------------|--------|
| **Frontend** | Next.js 15 + TypeScript | Vercel |
| **Backend** | Laravel 12 + PHP 8.3 | VPS (cPanel) |
| **Database** | MySQL 8.0 | VPS |
| **Styling** | Tailwind CSS + shadcn/ui | - |
| **Payments** | Easypay API v2.0 | Backend only |
| **i18n** | PT (primário) + EN | - |

## Estrutura do Repositório

```
praia-do-norte-unified/
├── frontend/               # Next.js 15 (em desenvolvimento)
├── backend/                # Laravel 12 (em desenvolvimento)
├── docs/
│   ├── phases/             # Guias de implementação (FASE_00 a FASE_10)
│   ├── architecture/       # Documentação técnica
│   └── design/             # Brief e prompts para design
├── api/                    # OpenAPI specs
├── content/                # Conteúdo de teste
├── logos/                  # Assets visuais
├── CLAUDE.md               # Instruções para Claude Code
├── PLANO_DESENVOLVIMENTO.md # Plano de desenvolvimento
└── DESIGN_GUIDELINES.md    # Identidade visual
```

## Documentação

| Documento | Descrição |
|-----------|-----------|
| [PLANO_DESENVOLVIMENTO.md](./PLANO_DESENVOLVIMENTO.md) | Plano completo de desenvolvimento |
| [CLAUDE.md](./CLAUDE.md) | Referência técnica para desenvolvimento |
| [DESIGN_GUIDELINES.md](./DESIGN_GUIDELINES.md) | Identidade visual e UX |
| [docs/phases/](./docs/phases/) | Guias fase a fase |
| [docs/architecture/](./docs/architecture/) | Estrutura e nomenclaturas |

## Fases de Desenvolvimento

O projeto está organizado em **4 blocos**:

| Bloco | Fases | Estado |
|-------|-------|--------|
| **1. Fundações** | Setup, Design System | Pendente |
| **2. Institucional** | Homepage, Conteúdo | Pendente |
| **3. Qualidade** | SEO, Segurança | Pendente |
| **4. E-commerce** | Loja, Checkout, Pagamentos | Aguarda API SAGE |

## Como Começar

1. Ler [PLANO_DESENVOLVIMENTO.md](./PLANO_DESENVOLVIMENTO.md)
2. Seguir [docs/phases/FASE_00_SETUP.md](./docs/phases/FASE_00_SETUP.md)
3. Configurar ambiente de desenvolvimento

## URLs

| Ambiente | Frontend | API |
|----------|----------|-----|
| **Produção** | praiadonortenazare.pt | api.praiadonortenazare.pt |
| **Desenvolvimento** | localhost:3000 | localhost:8000 |

---

**Proprietário**: Nazaré Qualifica, EM

**Domínio**: praiadonortenazare.pt
