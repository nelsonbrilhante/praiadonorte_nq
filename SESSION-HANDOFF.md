# Session Handoff - Praia do Norte

> Este ficheiro serve como ponto de continuidade entre sessões de desenvolvimento.
> Lê-lo no início de cada sessão para retomar o contexto.

---

## Última Sessão

- **Data**: 2025-12-09
- **Resumo**: Melhorada página de Previsões com 8 cards de condições, temperatura da água e layout reequilibrado

---

## O Que Foi Feito

### Fase 0 - Setup ✅
- [x] Instalado Laravel 12.41.1 em `backend/`
- [x] Instalado Filament 4.2.4 (admin panel)
- [x] Instalado Next.js 16.0.7 + React 19 em `frontend/`
- [x] Tailwind CSS 4.x configurado
- [x] Criada documentação em `docs/tech-stack/`
- [x] Configurados .env files
- [x] Criado utilizador admin Filament

### Fase 1 - Design System ✅
- [x] Instalado e configurado shadcn/ui
- [x] Instalado next-intl para i18n (PT/EN)
- [x] Configuradas cores do projeto (ocean, institutional, performance)
- [x] Criado componente Header com navegação
- [x] Criado componente Footer com 4 colunas
- [x] Criado componente LanguageSwitcher (PT/EN)
- [x] Criada estrutura de rotas [locale]
- [x] Criada Homepage com secções placeholder

### Fase 2 - Backend CMS (Filament) ✅
- [x] Criados models: Noticia, Surfer, Surfboard, Evento, Pagina
- [x] Criadas migrations com colunas JSON para i18n
- [x] Criados Filament Resources para cada model
- [x] Configuradas API routes em `routes/api.php`
- [x] Criados API Controllers
- [x] Sistema de pesquisa global (SearchController)

### Fase 3 - Integração Frontend-Backend ✅
- [x] Criado API client no frontend (`src/lib/api/`)
- [x] Conectadas páginas aos dados reais
- [x] Implementadas páginas dinâmicas (notícias, surfers, eventos)
- [x] Página de detalhe do surfer com pranchas
- [x] Conversão de medidas imperial para métrico

### Funcionalidades Adicionais ✅
- [x] Breadcrumbs de navegação
- [x] Pesquisa global (Cmd+K) com cmdk
- [x] Dark mode toggle
- [x] Sistema de medidas métricas para surfboards
- [x] Correção de entidades (exibir nomes completos)

### Página de Previsões Marítimas ✅
- [x] Página dedicada em `/previsoes`
- [x] Integração Open-Meteo Marine API (dados em tempo real)
- [x] Integração Open-Meteo Weather API (vento, temperatura)
- [x] **8 cards de condições atuais** (layout reequilibrado):
  - Altura das Ondas (card grande, 2 colunas)
  - Ondulação/Swell (card grande, 2 colunas)
  - Período das Ondas
  - Direção das Ondas
  - Velocidade do Vento
  - Direção do Vento (com código de cores offshore/onshore)
  - Rajadas de Vento (card médio, 2 colunas)
  - **Temperatura da Água** (card médio, 2 colunas) - NOVO
- [x] Previsão 7 dias em tabela
- [x] MONICAN embed (Instituto Hidrográfico)
- [x] Secção de webcams ao vivo
- [x] Link "Previsões" no menu principal
- [x] Traduções PT/EN completas
- [x] Recomendações de fato baseadas na temperatura da água

---

## Estado Atual do Projeto

| Item | Valor |
|------|-------|
| **Fase** | Fase 3 Completa - Funcionalidades extras |
| **Branch** | `main` |
| **Backend** | Laravel 12.41.1 + Filament 4.2.4 |
| **Frontend** | Next.js 16.0.7 + React 19.2.0 + Tailwind 4.x |
| **i18n** | next-intl configurado (PT/EN) |
| **UI** | shadcn/ui instalado |

---

## Ficheiros Criados/Modificados (Sessão Atual)

### API Forecast (Atualizado)
```
frontend/src/lib/api/forecast.ts
├── getMarineForecast()      # Ondas, swell, temp. água
├── getWeatherForecast()     # Vento, rajadas
├── getFullForecast()        # Ambas APIs em paralelo
├── processForecast()        # Processa dados para UI
├── getWindType()            # Offshore/onshore para Nazaré
├── getWindStrength()        # Descrição força do vento
└── getWaveCondition()       # Descrição estado do mar
```

### Página de Previsões (Atualizada)
```
frontend/src/app/[locale]/previsoes/page.tsx
├── Layout com 8 cards (grid responsivo)
├── Cards grandes: Altura Ondas + Swell (2 cols cada)
├── Cards normais: Período, Direção, Vento, Dir. Vento
├── Cards médios: Rajadas + Temp. Água (2 cols cada)
└── Código de cores para direção do vento
```

### Traduções (Atualizadas)
- `frontend/messages/pt.json` - Adicionado `waterTemperature`
- `frontend/messages/en.json` - Adicionado `waterTemperature`

---

## Dados das APIs (Verificados)

Todos os dados são **100% reais** das APIs Open-Meteo:

| Dado | API | Parâmetro |
|------|-----|-----------|
| Altura Ondas | Marine | `wave_height` |
| Período | Marine | `wave_period` |
| Direção Ondas | Marine | `wave_direction` |
| Swell | Marine | `swell_wave_height` |
| Swell Período | Marine | `swell_wave_period` |
| Temp. Água | Marine | `sea_surface_temperature` |
| Velocidade Vento | Weather | `wind_speed_10m` |
| Direção Vento | Weather | `wind_direction_10m` |
| Rajadas | Weather | `wind_gusts_10m` |

**Nota**: Altura Total ≠ Swell (explicação no código):
- `wave_height` = ondas totais (swell + vento local)
- `swell_wave_height` = apenas ondulação de longo período (qualidade)

---

## Próximas Tarefas Sugeridas

### Melhorias Potenciais
1. Adicionar gráfico visual de ondas (recharts)
2. Melhorar embeds de webcams com iframes diretos
3. Adicionar alertas de ondas gigantes
4. SEO metadata para página de previsões
5. Adicionar período do swell à tabela de 7 dias

### Funcionalidades Pendentes
1. Sistema de e-commerce (WooCommerce headless) - futuro
2. Integração Easypay para pagamentos - futuro
3. Sistema de newsletters
4. Área de utilizador registado

---

## Ficheiros Importantes

| Ficheiro | Propósito |
|----------|-----------|
| `CLAUDE.md` | Instruções técnicas para Claude Code |
| `docs/tech-stack/SETUP_LOG.md` | Log de instalação e problemas |
| `docs/tech-stack/LARAVEL_12.md` | Referência Laravel |
| `docs/tech-stack/FILAMENT_4.md` | Referência Filament |
| `docs/tech-stack/NEXTJS_16.md` | Referência Next.js |

---

## Stack Tecnológica Instalada

| Camada | Tecnologia | Versão |
|--------|------------|--------|
| **Frontend** | Next.js + React | 16.0.7 / 19.2.0 |
| **Styling** | Tailwind CSS | 4.x |
| **UI Components** | shadcn/ui | latest |
| **i18n** | next-intl | latest |
| **Search** | cmdk | latest |
| **Backend** | Laravel | 12.41.1 |
| **Admin Panel** | Filament | 4.2.4 |
| **Database** | SQLite (dev) / MySQL (prod) | - |

---

## Estrutura de Ficheiros Atual

```
frontend/
├── messages/
│   ├── pt.json                    # Traduções PT (incluindo forecast)
│   └── en.json                    # Traduções EN (incluindo forecast)
├── src/
│   ├── i18n/
│   │   ├── config.ts              # Locales config
│   │   └── request.ts             # next-intl server
│   ├── middleware.ts              # i18n routing
│   ├── components/
│   │   ├── ui/                    # shadcn/ui components
│   │   ├── layout/                # Header, Footer, etc.
│   │   └── forecast/              # Componentes previsões
│   ├── lib/
│   │   ├── api/                   # API client (noticias, surfers, forecast)
│   │   └── utils/                 # Utilidades (measurements, etc.)
│   └── app/
│       └── [locale]/
│           ├── layout.tsx
│           ├── page.tsx           # Homepage
│           ├── previsoes/         # Página de previsões (8 cards)
│           ├── noticias/          # Listagem e detalhe
│           ├── eventos/           # Listagem e detalhe
│           ├── surfer-wall/       # Listagem e detalhe
│           └── ...
└── next.config.ts

backend/
├── app/
│   ├── Filament/Resources/        # Admin resources
│   ├── Http/Controllers/Api/      # API controllers
│   └── Models/                    # Eloquent models
├── database/migrations/           # Database schema
└── routes/api.php                 # API routes
```

---

## URLs de Desenvolvimento

| Serviço | URL | Comando |
|---------|-----|---------|
| Frontend | http://localhost:3000/pt | `./scripts/start.sh` |
| Backend API | http://localhost:8000/api | `./scripts/start.sh` |
| Filament Admin | http://localhost:8000/admin | `./scripts/start.sh` |
| **Previsões** | http://localhost:3000/pt/previsoes | - |

**Credenciais Filament:**
- Email: `admin@nazarequalifica.pt`
- Password: `password`

**Scripts Úteis:**
```bash
./scripts/start.sh    # Iniciar servidores
./scripts/stop.sh     # Parar servidores
./scripts/restart.sh  # Reiniciar servidores
```

---

## Cores do Projeto

| Cor | Hex | Uso |
|-----|-----|-----|
| **ocean** | #0066cc | Praia do Norte (primário) |
| **institutional** | #ffa500 | Nazaré Qualifica |
| **performance** | #00cc66 | Carsurf |

---

## APIs Externas Integradas

| API | Uso | Documentação |
|-----|-----|--------------|
| **Open-Meteo Marine** | Ondas, swell, temp. água | https://open-meteo.com/en/docs/marine-weather-api |
| **Open-Meteo Weather** | Vento, rajadas | https://open-meteo.com/en/docs |
| **MONICAN** | Previsão oficial (iframe) | https://monican.hidrografico.pt/previsao |
| **Surfline** | Webcam Praia do Norte | https://www.surfline.com |
| **Beachcam MEO** | Webcam Forte | https://beachcam.meo.pt |

---

## Avisos Conhecidos

1. **Middleware deprecated warning** (Next.js 16)
   - next-intl usa middleware que está deprecated
   - Não é crítico, funciona normalmente
   - Será atualizado em versão futura do next-intl

2. **Workspace root warning** (Next.js)
   - Detecta múltiplos package-lock.json
   - Não afeta funcionamento

3. **legacyBehavior deprecated** (Next.js Link)
   - Alguns links usam legacyBehavior
   - Funciona mas será removido em versões futuras

---

## Como Continuar

```
1. Lê este ficheiro para contexto
2. Consulta CLAUDE.md para instruções técnicas
3. Inicia servidores: ./scripts/start.sh
4. Verifica página de previsões: http://localhost:3000/pt/previsoes
5. Continua com melhorias ou novas funcionalidades
6. Atualiza este ficheiro no final da sessão
```
