# Session Handoff - Praia do Norte

> Este ficheiro serve como ponto de continuidade entre sessões de desenvolvimento.
> Lê-o no início de cada sessão para retomar o contexto.

---

## Última Sessão

- **Data**: 2025-12-03
- **Resumo**: Inicialização do repositório Git e push para GitHub

---

## O Que Foi Feito

- [x] Corrigido ownership nos documentos (Nazaré Qualifica, EM)
- [x] Criado `.gitignore` completo para Next.js + Laravel
- [x] Criado `README.md` com overview do projeto
- [x] Inicializado repositório Git
- [x] Push para GitHub: `nelsonbrilhante/praiadonorte_nq`
- [x] Criado este ficheiro `SESSION-HANDOFF.md`

---

## Estado Atual do Projeto

| Item | Valor |
|------|-------|
| **Fase** | Pré-implementação (Planeamento Completo) |
| **Branch** | `main` |
| **Último commit** | `47272d3` - chore: initial commit - project planning documentation |
| **Repositório** | https://github.com/nelsonbrilhante/praiadonorte_nq |

---

## Próximas Tarefas

1. **Fase 0: Setup do Projeto** (`docs/phases/FASE_00_SETUP.md`)
   - Criar estrutura de pastas `frontend/` e `backend/`
   - Inicializar Next.js 15 com TypeScript
   - Inicializar Laravel 12 com Aimeos
   - Configurar ambiente de desenvolvimento

2. **Fase 1: Design System** (`docs/phases/FASE_01_DESIGN.md`)
   - Instalar e configurar shadcn/ui
   - Criar componentes base (Button, Card, Input)
   - Configurar Tailwind com cores do projeto

---

## Ficheiros Importantes

| Ficheiro | Propósito |
|----------|-----------|
| `CLAUDE.md` | Instruções técnicas para Claude Code |
| `PLANO_DESENVOLVIMENTO.md` | Plano completo de desenvolvimento |
| `docs/phases/FASE_00_SETUP.md` | Próxima fase a implementar |
| `docs/design/DESIGN_BRIEF.md` | Brief de design da homepage |

---

## Notas e Contexto

### Stack Tecnológica Definida
- **Frontend**: Next.js 15 + TypeScript + Tailwind + shadcn/ui (Vercel)
- **Backend**: Laravel 12 + Aimeos 2025.10 LTS (VPS cPanel)
- **Database**: MySQL 8.0
- **Pagamentos**: Easypay API v2.0 (server-side only)

### Decisões Importantes
- Praia do Norte é a marca PRIMÁRIA - todo o design e UX deve priorizá-la
- i18n desde o dia 1: PT (primário) + EN
- Pagamentos APENAS no backend Laravel (nunca expor credenciais)
- VPS tem 4GB RAM - frontend fica no Vercel

### Três Entidades
1. **Praia do Norte** - E-commerce + ondas gigantes (foco principal)
2. **Carsurf** - Centro de alto rendimento de surf
3. **Nazaré Qualifica** - Empresa municipal (proprietária)

---

## Bloqueios ou Pendentes

- [ ] Upgrade PHP 8.1 → 8.3 no VPS (necessário para Laravel 12)
- [ ] Migração CentOS 7 → AlmaLinux (CentOS 7 EOL desde Jun 2024)
- [ ] Credenciais Easypay de produção (aguarda cliente)
- [ ] Acesso API SAGE para integração de inventário

---

## Como Continuar

```
1. Lê este ficheiro para contexto
2. Consulta CLAUDE.md para instruções técnicas
3. Segue docs/phases/FASE_XX_*.md para a próxima fase
4. Atualiza este ficheiro no final da sessão
```
