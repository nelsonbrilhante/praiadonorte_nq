# Design Brief - Homepage Praia do Norte

## Documento para Colaboração com Gemini

Este brief contém todas as especificações necessárias para criar o design visual da homepage do "Praia do Norte Unified Platform".

---

## 1. Visão Geral do Projeto

### Contexto
- **Projeto**: Praia do Norte Unified Platform
- **Tipo**: Website institucional + E-commerce
- **Objetivo**: Homepage impactante que capture a essência das ondas gigantes da Nazaré
- **Público-alvo**: Turistas internacionais, surfistas, entusiastas do surf, residentes locais

### Entidade Responsável

**Nazaré Qualifica, EM** (Empresa Municipal) é a entidade proprietária e gestora desta plataforma unificada.

### Três Marcas Unificadas

| Marca | Função | Cor Associada |
|-------|--------|---------------|
| **Praia do Norte** | Marca principal - Ondas gigantes, merchandising | Ocean Blue (#0066cc) |
| **Carsurf** | Centro de Alto Rendimento de Surf | Performance Green (#00cc66) |
| **Nazaré Qualifica** | Empresa municipal - infraestruturas e serviços | Institutional Orange (#ffa500) |

**Princípio Central**: Praia do Norte é SEMPRE o elemento dominante visualmente. As outras marcas são secundárias mas fazem parte do mesmo ecossistema gerido pela Nazaré Qualifica, EM.

---

## 2. Identidade Visual

### Paleta de Cores

```
CORES PRIMÁRIAS (Praia do Norte)
├── Ocean Blue 500:    #0066cc  ← Principal (botões, links, CTAs)
├── Ocean Blue 900:    #003366  ← Dark (footer, backgrounds escuros)
├── Ocean Blue 50:     #e6f3ff  ← Light (backgrounds sutis)
└── White:             #ffffff  ← Base

CORES SECUNDÁRIAS (Nazaré Qualifica)
├── Orange 500:        #ffa500  ← Destaques secundários
├── Orange 900:        #cc6600  ← Texto accent
└── Orange 50:         #fff4e6  ← Backgrounds cards

CORES TERCIÁRIAS (Carsurf)
├── Green 500:         #00cc66  ← Success, sport accents
├── Green 900:         #008844  ← Dark text
└── Green 50:          #e6fff5  ← Backgrounds

NEUTROS
├── Slate 900:         #0f172a  ← Headings
├── Slate 500:         #64748b  ← Body text
├── Slate 200:         #e2e8f0  ← Borders
└── Slate 50:          #f8fafc  ← Backgrounds
```

### Tipografia

**Headings: Montserrat (Google Fonts)**
- Hero titles: `Montserrat Black (900)` - 72px/80px
- Section titles: `Montserrat Bold (700)` - 36px/48px
- Card titles: `Montserrat SemiBold (600)` - 20px/24px

**Body: Inter (Google Fonts)**
- Body text: `Inter Regular (400)` - 16px
- Navigation: `Inter Medium (500)` - 14px
- Captions: `Inter Regular (400)` - 12px

---

## 3. Estrutura da Homepage

### Layout Geral (Desktop)

```
┌─────────────────────────────────────────────────────────────┐
│                         HEADER                               │
│  [Logo] [Praia do Norte ▼] [Carsurf ▼] [NQ ▼] [PT|EN] [🛒]  │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│                    HERO SECTION (100vh)                      │
│                                                              │
│            Vídeo/Imagem de Onda Gigante                      │
│                                                              │
│              ══════════════════════════                      │
│                   PRAIA DO NORTE                             │
│              Onde nascem as ondas gigantes                   │
│                                                              │
│                  [ Explorar Loja ]                           │
│                                                              │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│                  PRODUTOS EM DESTAQUE                        │
│                                                              │
│    ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐         │
│    │ Produto │ │ Produto │ │ Produto │ │ Produto │         │
│    │   1     │ │   2     │ │   3     │ │   4     │         │
│    └─────────┘ └─────────┘ └─────────┘ └─────────┘         │
│                                                              │
│                    [ Ver Todos ]                             │
│                                                              │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│                   ÚLTIMAS NOTÍCIAS                           │
│                                                              │
│    ┌──────────────┐ ┌──────────────┐ ┌──────────────┐       │
│    │   Artigo 1   │ │   Artigo 2   │ │   Artigo 3   │       │
│    │   [Imagem]   │ │   [Imagem]   │ │   [Imagem]   │       │
│    │   Título     │ │   Título     │ │   Título     │       │
│    │   Data       │ │   Data       │ │   Data       │       │
│    └──────────────┘ └──────────────┘ └──────────────┘       │
│                                                              │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│                      SURFER WALL                             │
│              "Os Heróis das Ondas Gigantes"                  │
│                                                              │
│  ◀  [Surfista] [Surfista] [Surfista] [Surfista]  ▶          │
│                                                              │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│                    TRÊS ENTIDADES                            │
│                                                              │
│    ┌─────────────┐ ┌─────────────┐ ┌─────────────┐          │
│    │ Praia do    │ │   Carsurf   │ │   Nazaré    │          │
│    │ Norte       │ │             │ │  Qualifica  │          │
│    │ [Ocean]     │ │  [Green]    │ │  [Orange]   │          │
│    │ Merchandis. │ │  Centro AR  │ │  Serviços   │          │
│    │ [Saber +]   │ │  [Saber +]  │ │  [Saber +]  │          │
│    └─────────────┘ └─────────────┘ └─────────────┘          │
│                                                              │
├─────────────────────────────────────────────────────────────┤
│                         FOOTER                               │
│                    (Background: Slate-900)                   │
│                                                              │
│  Praia do Norte    │    Carsurf      │   Nazaré Qualifica   │
│  ─────────────────  │  ─────────────── │ ───────────────────  │
│  Loja               │  Instalações    │  Serviços            │
│  Notícias           │  Programas      │  Infraestruturas     │
│  Eventos            │  Equipamentos   │  Contactos           │
│  Surfistas          │  Reservas       │                      │
│                                                              │
│  ─────────────────────────────────────────────────────────  │
│  © 2025 Nazaré Qualifica, EM • Política Privacidade         │
└─────────────────────────────────────────────────────────────┘
```

---

## 4. Especificações por Secção

### 4.1 Header

**Comportamento**: Sticky top, backdrop-blur
**Background**: `bg-white/95 backdrop-blur-md`
**Altura**: 64px (desktop), 56px (mobile)

**Elementos**:
- Logo Praia do Norte (esquerda)
- Mega menu com 3 entidades
- Language switcher (PT/EN)
- Ícone carrinho com badge

### 4.2 Hero Section

**Dimensões**: 100vh (fullscreen)
**Background**: Vídeo em loop OU imagem de alta resolução

**Requisitos do vídeo/imagem**:
- Onda gigante da Nazaré
- Cores predominantes: azuis profundos, brancos (espuma)
- Qualidade: 4K para desktop, 1080p para mobile
- Formato vídeo: MP4 (H.264), WebM como fallback

**Overlay**: Gradient linear
```css
background: linear-gradient(
  to bottom,
  rgba(0, 0, 0, 0.7) 0%,
  rgba(0, 0, 0, 0.3) 50%,
  rgba(0, 0, 0, 0.5) 100%
);
```

**Texto**:
- Título: "PRAIA DO NORTE" - Montserrat Black, 72px, uppercase, white
- Subtítulo: "Onde nascem as ondas gigantes" - Inter Regular, 24px, white/80%
- CTA: "Explorar Loja" - Botão azul (#0066cc), padding 16px 32px

**Animação**: Fade-in suave no texto ao carregar (300ms delay, 500ms duration)

### 4.3 Produtos em Destaque

**Layout**: Grid 4 colunas (desktop), 2 colunas (tablet), 1 coluna (mobile)
**Gap**: 24px
**Padding secção**: 80px vertical

**Card de Produto**:
```
┌────────────────────────┐
│      [Imagem 4:5]      │  Background: white
│                        │  Border: 1px slate-200
│                        │  Border-radius: 8px
├────────────────────────┤  Shadow: sm → md on hover
│  Nome do Produto       │
│  €XX.XX                │
│  [Adicionar ao Cart]   │
└────────────────────────┘
```

**Hover**: `transform: translateY(-4px)`, shadow aumenta

### 4.4 Últimas Notícias

**Layout**: Grid 3 colunas (desktop), 1 coluna (mobile)
**Background secção**: Slate-50

**Card de Notícia**:
```
┌────────────────────────┐
│      [Imagem 16:9]     │
├────────────────────────┤
│  Categoria (badge)     │  Badge: Ocean Blue
│  Título do Artigo      │  Title: Montserrat SemiBold
│  12 Dezembro 2025      │  Date: Inter Regular, slate-500
│  Excerpt breve...      │
└────────────────────────┘
```

### 4.5 Surfer Wall

**Layout**: Carousel horizontal com scroll snap
**Background**: White

**Card de Surfista**:
```
┌────────────────┐
│    [Foto]      │  Foto: Circular ou 3:4
│    circular    │
├────────────────┤
│  Nome Surfista │  Name: Montserrat Bold
│  🇵🇹 Portugal  │  Flag + Country
│  "Big Wave"    │  Tag/Achievement
└────────────────┘
```

**Navegação**: Setas laterais + indicadores de posição

### 4.6 Três Entidades

**Layout**: Grid 3 colunas iguais
**Gap**: 32px

**Card de Entidade**:
- Praia do Norte: Border-top Ocean Blue
- Carsurf: Border-top Green
- Nazaré Qualifica: Border-top Orange

```
┌────────────────────────┐
│═══════════════════════│  ← Border-top colorida (4px)
│                        │
│       [Logo]           │
│                        │
│   Nome da Entidade     │
│                        │
│   Breve descrição do   │
│   que oferece...       │
│                        │
│   [ Saber Mais → ]     │  ← Link na cor da entidade
│                        │
└────────────────────────┘
```

### 4.7 Footer

**Background**: Slate-900
**Texto**: White / White-60%
**Layout**: 3 colunas + linha de copyright

---

## 5. Responsive Breakpoints

| Breakpoint | Largura | Colunas Grid |
|------------|---------|--------------|
| Mobile | < 640px | 1 |
| Tablet | 640px - 1024px | 2 |
| Desktop | 1024px - 1280px | 3-4 |
| Large | > 1280px | 4 |

**Container max-width**: 1280px
**Padding lateral**: 16px (mobile), 24px (tablet), 32px (desktop)

---

## 6. Restrições Técnicas

### Framework & Bibliotecas
- **Framework**: Next.js 15 (App Router)
- **Styling**: Tailwind CSS 3.4
- **Componentes**: shadcn/ui (Radix UI primitives)
- **Ícones**: Lucide React
- **Animações**: Framer Motion (para interações complexas)

### Performance Targets
- **LCP**: < 2.5 segundos
- **CLS**: < 0.1
- **FID**: < 100ms

### Acessibilidade
- **Contraste mínimo**: 4.5:1 para texto normal
- **Foco visível**: Em todos os elementos interativos
- **Alt text**: Em todas as imagens

---

## 7. Mood Board & Referências

### Estilo Visual Desejado
- **Imersivo**: O utilizador deve sentir o poder das ondas
- **Premium**: Qualidade fotográfica de revista
- **Moderno**: Clean, espaçoso, tipografia forte
- **Confiável**: Institucional mas não "boring"

### Sites de Referência (inspiração)
- WSL (World Surf League) - worldsurfleague.com
- Nazaré Tourism - visitnazare.pt
- Red Bull Surfing - redbull.com/surfing
- Patagonia - patagonia.com

### Keywords Visuais
- Ondas gigantes
- Farol da Nazaré
- Surfistas em ação
- Azul oceano profundo
- Espuma branca
- Poder da natureza
- Adrenalina
- Portugal

---

## 8. Component Architecture (shadcn/ui Mapping)

Mapeamento dos componentes visuais para a biblioteca shadcn/ui para acelerar o desenvolvimento:

| Secção | Componente UI | Personalização Necessária |
|--------|--------------|---------------------------|
| **Header** | `NavigationMenu` | Adicionar suporte para "Mega Menu" grid layout e Mobile Sheet |
| **Hero** | `Button` | Variantes 'default' (Ocean Blue) e 'outline' (White) |
| **Produtos** | `Card` | Com `CardHeader` (Imagem), `CardContent` (Info), `CardFooter` (Ação) |
| **Notícias** | `Card` + `Badge` | Badge para categoria, layout horizontal ou vertical |
| **Surfer Wall** | `Carousel` | Usar o novo componente Carousel do shadcn/ui (Embla wrapper) |
| **Entidades** | `Card` | Custom border-top utility class |
| **Geral** | `Sheet` | Para menu mobile e carrinho de compras |
| **Geral** | `Dialog` | Para quick view de produtos (opcional) |
| **Forms** | `Form` + `Input` | Para newsletter no footer |

---

## 9. Motion Design System (Framer Motion)

Estratégia de animação para criar uma experiência "Imersiva" e "Premium":

### 9.1 Page Transitions
- **Entrada**: Suave `opacity: 0` -> `1` com `y: 20` -> `0`
- **Stagger**: Elementos filhos carregam com delay de 0.1s entre si

### 9.2 Scroll Animations (ScrollTrigger)
- **Hero Text**: Fade up lento e dramático
- **Secções**: Reveal suave ao entrar na viewport (threshold 0.2)
- **Parallax**: Ligeiro efeito parallax no background do Hero e imagens de destaque

### 9.3 Micro-interações
- **Botões**: Scale 0.98 on tap, brightness 110% on hover
- **Cards**: Elevation (shadow-lg) e translateY(-4px) on hover
- **Images**: Zoom subtil (scale 1.05) on hover em containers com `overflow-hidden`

---

## 10. Tailwind Configuration Proposal

Extensão do tema para suportar a identidade visual unificada:

```typescript
// tailwind.config.ts partial
theme: {
  extend: {
    colors: {
      brand: {
        blue: {
          DEFAULT: '#0066cc', // Ocean Blue 500
          dark: '#003366',    // Ocean Blue 900
          light: '#e6f3ff',   // Ocean Blue 50
        },
        orange: {
          DEFAULT: '#ffa500', // Orange 500
          dark: '#cc6600',    // Orange 900
          light: '#fff4e6',   // Orange 50
        },
        green: {
          DEFAULT: '#00cc66', // Green 500
          dark: '#008844',    // Green 900
          light: '#e6fff5',   // Green 50
        }
      }
    },
    fontFamily: {
      sans: ['var(--font-inter)', ...fontFamily.sans],
      heading: ['var(--font-montserrat)', ...fontFamily.sans],
    },
    backgroundImage: {
      'hero-gradient': 'linear-gradient(to bottom, rgba(0,0,0,0.7), rgba(0,0,0,0.3), rgba(0,0,0,0.5))',
    }
  }
}
```

---

## 11. Entregáveis Esperados

1. **Wireframes detalhados** de cada secção
2. **Especificações de espaçamento** (margins, paddings)
3. **Código HTML/CSS/React** de mockup para cada secção
4. **Prompts de imagem AI** para gerar assets visuais
5. **Sugestões de animações** e micro-interações


---

*Brief criado para colaboração Claude Code + Gemini*
*Projeto: Praia do Norte Unified Platform*
*Data: Dezembro 2025*
