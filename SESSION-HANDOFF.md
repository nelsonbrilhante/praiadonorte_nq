# Session Handoff - Praia do Norte

> Este ficheiro serve como ponto de continuidade entre sessões de desenvolvimento.
> Lê-lo no início de cada sessão para retomar o contexto.

---

## Última Sessão

- **Data**: 2025-12-16
- **Resumo**: Páginas legais, logo no header, inversão do botão de idioma

### O que foi feito:
1. **Páginas Legais (RGPD/Cookies)**
   - Criadas 3 páginas: `/privacidade`, `/termos`, `/cookies`
   - Rotas adicionadas ao `web.php`
   - Traduções PT/EN completas em `lang/pt/legal.php` e `lang/en/legal.php`
   - Links do footer já funcionais

2. **Logo no Header**
   - Substituído texto "Nazaré Qualifica" por imagem de logo
   - Versão branca para header transparente (homepage)
   - Versão original para header com fundo sólido
   - Logos copiados para `public/images/logos/`

3. **Botão de Idioma (UX)**
   - Invertida lógica: agora mostra idioma destino em vez do atual
   - Se está em PT → mostra "EN" (ação: mudar para inglês)
   - Se está em EN → mostra "PT" (ação: mudar para português)

4. **Ficheiros criados/modificados:**
   - `routes/web.php` - rotas legais
   - `lang/pt/legal.php` - traduções PT
   - `lang/en/legal.php` - traduções EN
   - `pages/privacidade.blade.php` - página privacidade
   - `pages/termos.blade.php` - página termos
   - `pages/cookies.blade.php` - página cookies
   - `components/layout/header.blade.php` - logo dinâmico
   - `livewire/language-switcher.blade.php` - lógica invertida
   - `public/images/logos/` - logos copiados

---

## Sessão Anterior (2025-12-15)

- **Resumo**: Correção do captcha do YouTube no Hero Section

### O que foi feito:
1. **Correção do Captcha do YouTube**
   - O YouTube mostrava "Sign in to confirm you're not a bot" no vídeo embedado
   - Alterado domínio do embed de `youtube.com` para `youtube-nocookie.com`
   - Domínio nocookie é oficial do YouTube para embeds com privacidade melhorada

---

## Sessão Anterior (2025-12-12)

- **Resumo**: Implementação de logótipo dinâmico no Hero Section com slider de tamanho

### O que foi feito:
1. **Logótipo no Hero Section**
   - Adicionados campos `hero_logo`, `hero_use_logo`, `hero_logo_height` à tabela `paginas`
   - Toggle no admin para escolher entre texto ou imagem
   - FileUpload para carregar logótipo
   - Slider (80px-300px) para ajustar tamanho com feedback em tempo real

2. **Ficheiros criados/modificados:**
   - `database/migrations/2025_12_12_141145_add_hero_logo_to_paginas_table.php`
   - `database/migrations/2025_12_12_141939_add_hero_logo_scale_to_paginas_table.php`
   - `app/Models/Pagina.php` - adicionados campos ao fillable/casts
   - `app/Filament/Resources/Geral/HomepageResource.php` - toggle, FileUpload, slider
   - `resources/views/components/praia-norte/hero-section.blade.php` - props e condicional logo/texto
   - `resources/views/pages/home.blade.php` - novos props passados

3. **Logótipo utilizado:** `LOGOTIPO PN POSITIVO.png` (branco, transparente)

---

## Estado Actual do Projecto

| Item | Valor |
|------|-------|
| **Fase** | Migração Blade COMPLETA - Pronto para Quality Assurance |
| **Branch** | `main` |
| **Backend** | Laravel 12.41.1 + Filament 4.2.4 |
| **Frontend** | Blade + Livewire (migração concluída) |
| **i18n** | Laravel localization configurado |
| **Admin Theme** | Navy Blue (#1e3a5f) |

---

## Migração Blade - COMPLETA

### Páginas Principais (100% concluídas)

| Página | Ficheiro | Estado |
|--------|----------|--------|
| Homepage | `pages/home.blade.php` | ✅ |
| Notícias (lista) | `pages/noticias/index.blade.php` | ✅ |
| Notícias (detalhe) | `pages/noticias/show.blade.php` | ✅ |
| Eventos (lista) | `pages/eventos/index.blade.php` | ✅ |
| Eventos (detalhe) | `pages/eventos/show.blade.php` | ✅ |
| Surfer Wall (lista) | `pages/surfer-wall/index.blade.php` | ✅ |
| Surfer Wall (detalhe) | `pages/surfer-wall/show.blade.php` | ✅ |
| Previsões | `pages/previsoes.blade.php` | ✅ |
| Sobre | `pages/sobre.blade.php` | ✅ |
| Contacto | `pages/contacto.blade.php` | ✅ |
| Privacidade | `pages/privacidade.blade.php` | ✅ |
| Termos | `pages/termos.blade.php` | ✅ |
| Cookies | `pages/cookies.blade.php` | ✅ |

### Carsurf (100% concluídas)

| Página | Ficheiro | Estado |
|--------|----------|--------|
| Landing | `pages/carsurf/index.blade.php` | ✅ |
| Sobre | `pages/carsurf/sobre.blade.php` | ✅ |
| Programas | `pages/carsurf/programas.blade.php` | ✅ |

### Nazaré Qualifica (100% concluídas)

| Página | Ficheiro | Estado |
|--------|----------|--------|
| Sobre | `pages/nazare-qualifica/sobre.blade.php` | ✅ |
| Equipa | `pages/nazare-qualifica/equipa.blade.php` | ✅ |
| Serviços | `pages/nazare-qualifica/servicos.blade.php` | ✅ |
| Carsurf | `pages/nazare-qualifica/carsurf.blade.php` | ✅ |
| Estacionamento | `pages/nazare-qualifica/estacionamento.blade.php` | ✅ |
| Forte | `pages/nazare-qualifica/forte.blade.php` | ✅ |
| ALE | `pages/nazare-qualifica/ale.blade.php` | ✅ |

### Componentes (100% concluídos)

| Componente | Ficheiro | Estado |
|------------|----------|--------|
| Layout App | `components/layouts/app.blade.php` | ✅ |
| Header | `components/layout/header.blade.php` | ✅ |
| Footer | `components/layout/footer.blade.php` | ✅ |
| Hero Section | `components/praia-norte/hero-section.blade.php` | ✅ |
| Button | `components/ui/button.blade.php` | ✅ |
| Card (+ header, title, description, content, footer) | `components/ui/card*.blade.php` | ✅ |
| Badge | `components/ui/badge.blade.php` | ✅ |
| Input | `components/ui/input.blade.php` | ✅ |
| Textarea | `components/ui/textarea.blade.php` | ✅ |
| Breadcrumbs | `components/ui/breadcrumbs.blade.php` | ✅ |

### Livewire (configurado)

| Componente | Ficheiro | Estado |
|------------|----------|--------|
| Language Switcher | `livewire/language-switcher.blade.php` | ✅ |
| Search Spotlight | `livewire/search-spotlight.blade.php` | ✅ |

**SearchSpotlight Features:**
- Atalho: `Cmd+K` (Mac) / `Ctrl+K` (Windows)
- Pesquisa em: Notícias, Eventos, Surfers, Páginas
- Debounce 300ms, resultados agrupados por tipo
- Máximo 3 resultados por tipo
- Ficheiros: `app/Livewire/SearchSpotlight.php` + view

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
   ├── Surfers
   └── Pranchas

🌐 Website
   └── Ver Website (abre em nova aba)
```

### Resources por Entidade

```
backend/app/Filament/Resources/
├── Geral/
│   ├── HomepageResource.php
│   └── Pages/
│       ├── ListHomepages.php
│       └── EditHomepage.php
├── Paginas/
│   └── BasePageResource.php          # Classe base abstracta
├── PraiaNorte/
│   └── PraiaNortePageResource.php
├── Carsurf/
│   └── CarsurfPageResource.php
└── NazareQualifica/
    └── NQPageResource.php
```

---

## URLs de Desenvolvimento

| Serviço | URL |
|---------|-----|
| **Site Público** | http://localhost:8000/pt |
| **Site EN** | http://localhost:8000/en |
| **Filament Admin** | http://localhost:8000/admin |

**Credenciais Filament:**
- Email: `admin@nazarequalifica.pt`
- Password: `password`

**Scripts:**
```bash
./scripts/start.sh    # Iniciar servidor Laravel
./scripts/stop.sh     # Parar servidor
```

---

## Gaps Conhecidos

| Item | Estado | Notas |
|------|--------|-------|
| Páginas Legais (`/privacidade`, `/termos`, `/cookies`) | ✅ Implementado | Sessão 16/12 |
| Formulário de Contacto backend | ⚠️ Não implementado | `action="#"` sem handler POST |
| `app/Services/ForecastService.php` | ⚠️ Não criado | Documentado no CLAUDE.md mas não existe (baixa prioridade) |

---

## Próximas Tarefas

### Prioridade Alta (Phase 4 - Quality)
1. [x] Criar páginas legais (privacidade, termos, cookies) ✅
2. [ ] Implementar backend do formulário de contacto
3. [ ] Testes funcionais de todas as páginas
4. [ ] Verificar responsividade (mobile, tablet, desktop)
5. [ ] SEO metadata em todas as páginas
6. [ ] Lighthouse audit (target: >90 em todas as métricas)

### Prioridade Média (Phase 5 - Security)
1. [ ] Security headers (CSP, HSTS, X-Frame-Options)
2. [ ] Rate limiting nas rotas públicas
3. [ ] CSRF validation review
4. [ ] Input sanitization audit

### Prioridade Baixa (Polish)
1. [ ] Reduzir espaçamento vertical no menu Filament (CSS customizado)
2. [ ] Performance optimization (caching, lazy loading)
3. [ ] Arquivar pasta `frontend/` (Next.js deprecated)

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

### viteTheme() Causa Problemas

Não usar `->viteTheme()` no AdminPanelProvider - quebra o carregamento do CSS do Filament.

### Entity Filter nas Queries

Cada Resource de páginas filtra por `entity`:
- `praia-norte` - Praia do Norte (exclui homepage)
- `carsurf` - Carsurf
- `nazare-qualifica` - Nazaré Qualifica
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
cd backend && php artisan serve

# 3. Em outro terminal, iniciar Vite (para assets)
cd backend && npm run dev

# 4. Aceder ao admin
open http://localhost:8000/admin

# 5. Continuar com testes e quality assurance
# 6. Actualizar este ficheiro no final da sessão
```
