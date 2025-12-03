# Prompts para Gemini - Design Homepage

## Como Usar

1. Abre o Gemini no VS Code
2. Copia o prompt desejado
3. Anexa o ficheiro `DESIGN_BRIEF.md` se necessário
4. Guarda o output em `docs/design/outputs/`

---

## Prompt 1: Análise do Brief e Sugestões

```
Actua como um web designer sénior especializado em websites de turismo e desporto.

Analisa o seguinte design brief para a homepage de "Praia do Norte" - um website sobre as famosas ondas gigantes da Nazaré, Portugal.

[COLAR CONTEÚDO DO DESIGN_BRIEF.md]

Com base no brief, fornece:

1. **Feedback Geral**: O que está bem definido e o que pode ser melhorado
2. **Sugestões Criativas**: 3 ideias inovadoras para tornar a homepage mais impactante
3. **Potenciais Problemas**: Desafios técnicos ou de UX que devemos antecipar
4. **Prioridades**: Se tivesses de escolher 3 elementos para focar, quais seriam?

Responde em português.
```

---

## Prompt 2: Hero Section - Código HTML/CSS

```
Cria código HTML e CSS moderno para uma hero section fullscreen com as seguintes especificações:

REQUISITOS:
- Altura: 100vh (fullscreen)
- Background: Vídeo em loop (autoplay, muted) com fallback para imagem
- Overlay: Gradient escuro (70% topo → 30% meio → 50% fundo)
- Conteúdo centrado verticalmente:
  - Título: "PRAIA DO NORTE" (72px, uppercase, bold, branco)
  - Subtítulo: "Onde nascem as ondas gigantes" (24px, branco 80%)
  - Botão CTA: "Explorar Loja" (azul #0066cc, branco texto)

CORES:
- Azul principal: #0066cc
- Branco: #ffffff
- Overlay preto: rgba(0,0,0,0.7/0.3/0.5)

ESTILO:
- Tipografia: Montserrat para título, Inter para subtítulo
- Botão: rounded corners (8px), padding 16px 32px, hover state
- Animação: fade-in suave no texto ao carregar

RESPONSIVO:
- Desktop: Como descrito
- Mobile: Título 40px, subtítulo 18px, botão full-width

Fornece:
1. Código HTML semântico
2. CSS com custom properties para cores
3. Comentários explicativos
4. Classes que possam ser convertidas para Tailwind
```

---

## Prompt 3: Product Card Component

```
Cria código HTML e CSS para um card de produto e-commerce com estas especificações:

DESIGN:
- Fundo branco
- Border: 1px solid #e2e8f0
- Border-radius: 8px
- Sombra subtil (aumenta no hover)

ELEMENTOS:
1. Imagem do produto (aspect ratio 4:5)
2. Nome do produto (Montserrat SemiBold, 18px)
3. Preço (Inter Bold, 20px, #0066cc)
4. Botão "Adicionar" (outline style, hover fill)

INTERAÇÕES:
- Hover: card eleva (-4px), sombra aumenta
- Hover imagem: zoom subtil (scale 1.05)
- Transições: 200ms ease

RESPONSIVO:
- Grid de 4 colunas (desktop) → 2 (tablet) → 1 (mobile)

Fornece código pronto para usar, com hover states e responsividade.
```

---

## Prompt 4: News/Article Card

```
Cria código para um card de artigo/notícia para um website de surf:

LAYOUT:
- Imagem topo (aspect ratio 16:9)
- Conteúdo abaixo da imagem

ELEMENTOS:
1. Imagem com overlay gradient no fundo
2. Badge de categoria (pequeno, azul #0066cc)
3. Título do artigo (Montserrat SemiBold, 20px, max 2 linhas)
4. Data de publicação (Inter, 14px, cinza #64748b)
5. Excerpt (Inter, 16px, 3 linhas max, overflow ellipsis)

HOVER:
- Imagem: zoom subtil
- Card: sombra aumenta
- Título: cor muda para azul

Fornece HTML, CSS, e uma versão com classes Tailwind.
```

---

## Prompt 5: Surfer Card (Carousel Item)

```
Cria um card para exibir um surfista de ondas gigantes:

CONCEITO:
- Card compacto para usar em carousel horizontal
- Visual impactante mas informação mínima

LAYOUT:
- Largura fixa: 280px
- Foto do surfista (pode ser circular ou quadrada com rounded corners)
- Nome em destaque
- Nacionalidade com bandeira emoji
- Tag/achievement opcional

ESTILO:
- Background: branco ou gradient subtil
- Sombra média
- Border-radius: 12px

ELEMENTOS:
1. Foto (200px altura)
2. Nome: Montserrat Bold, 18px
3. País: 🇵🇹 Portugal (Inter, 14px)
4. Tag: "Big Wave Legend" (badge pequeno)

Inclui também CSS para o container do carousel com scroll horizontal e snap points.
```

---

## Prompt 6: Three Entities Section

```
Cria uma secção com 3 cards lado a lado para representar 3 entidades diferentes:

ENTIDADES:
1. Praia do Norte (cor: #0066cc) - "Marca oficial das ondas gigantes"
2. Carsurf (cor: #00cc66) - "Centro de Alto Rendimento"
3. Nazaré Qualifica (cor: #ffa500) - "Empresa Municipal"

DESIGN DE CADA CARD:
- Border-top colorido (4px) na cor da entidade
- Background branco
- Padding generoso (32px)

CONTEÚDO:
1. Ícone ou Logo (placeholder)
2. Nome da entidade (Montserrat Bold)
3. Descrição curta (2-3 linhas)
4. Link "Saber Mais →" na cor da entidade

LAYOUT:
- 3 colunas iguais (desktop)
- Stack vertical (mobile)
- Gap: 32px

Fornece código com atenção especial à consistência visual entre os 3 cards.
```

---

## Prompt 7: Footer Multi-Coluna

```
Cria um footer dark theme para um website institucional:

ESPECIFICAÇÕES:
- Background: #0f172a (Slate-900)
- Texto: branco e branco 60%
- 4 secções em linha (desktop)

ESTRUTURA:
1. Coluna "Praia do Norte" - Links: Loja, Notícias, Eventos, Surfistas
2. Coluna "Carsurf" - Links: Instalações, Programas, Equipamentos
3. Coluna "Nazaré Qualifica" - Links: Serviços, Infraestruturas
4. Coluna "Newsletter" - Input email + botão subscrever

ELEMENTOS ADICIONAIS:
- Linha divisória subtil antes do copyright
- Copyright: "© 2025 Nazaré Qualifica, EM"
- Links: Política de Privacidade, Termos de Uso
- Ícones redes sociais (Facebook, Instagram, YouTube)

RESPONSIVO:
- Desktop: 4 colunas
- Tablet: 2x2 grid
- Mobile: Stack vertical

Fornece código completo com hover states nos links.
```

---

## Prompt 8: Header/Navigation

```
Cria um header sticky com mega menu para um website multi-marca:

ESPECIFICAÇÕES:
- Sticky top
- Background: branco com 95% opacity + backdrop-blur
- Altura: 64px

ELEMENTOS (esquerda → direita):
1. Logo "Praia do Norte" (link para home)
2. Mega menu dropdown para cada entidade:
   - Praia do Norte: Loja, Notícias, Eventos, Surfistas
   - Carsurf: Instalações, Programas
   - Nazaré Qualifica: Serviços, Infraestruturas
3. Language switcher: PT | EN
4. Ícone carrinho com badge de quantidade

COMPORTAMENTO:
- Mega menu aparece on hover
- Menu organizado em grid dentro do dropdown
- Transição suave (200ms)

MOBILE:
- Hamburger menu
- Sheet/drawer lateral

Fornece HTML, CSS, e lógica básica em JavaScript para os dropdowns.
```

---

## Prompt 9: Full Homepage Mockup

```
Com base em todas as secções anteriores, cria um ficheiro HTML único que represente a homepage completa:

SECÇÕES (ordem):
1. Header (sticky)
2. Hero Section (100vh, vídeo background)
3. Produtos em Destaque (4 cards)
4. Últimas Notícias (3 cards)
5. Surfer Wall (carousel horizontal)
6. Três Entidades (3 cards)
7. Footer (dark theme)

REQUISITOS:
- HTML semântico
- CSS organizado por secção
- Responsivo (mobile-first)
- Placeholder images (usar unsplash ou placeholder.com)
- Cores e tipografia consistentes com o brief

Este mockup servirá como referência visual para converter depois em componentes React/Next.js.
```

---

## Prompt 10: Sugestões de Animações

```
Para a homepage da "Praia do Norte" (website de ondas gigantes), sugere animações e micro-interações que melhorem a experiência:

CONTEXTO:
- Hero com vídeo de ondas
- Cards de produtos/notícias
- Carousel de surfistas
- Navegação multi-nível

PEDE:
1. **Animações de entrada**: Como os elementos devem aparecer ao fazer scroll
2. **Hover states**: Interações subtis nos cards e botões
3. **Loading states**: Skeletons e spinners
4. **Transições de página**: Como navegar entre páginas
5. **Parallax**: Onde usar efeitos de profundidade

Para cada sugestão, indica:
- Descrição do efeito
- CSS/keyframes necessários
- Timing recomendado
- Se requer JavaScript

Mantém as animações subtis e performantes (prefere transform e opacity).
```

---

## Notas de Uso

### Melhores Práticas com Gemini

1. **Sê específico**: Quanto mais detalhe deres, melhor o output
2. **Itera**: Pede refinamentos se o primeiro resultado não for perfeito
3. **Combina prompts**: Usa outputs anteriores como contexto
4. **Valida**: Testa o código gerado antes de usar

### Guardar Outputs

Cria ficheiros em `docs/design/outputs/`:
- `hero-section.html`
- `product-card.html`
- `full-mockup.html`
- etc.

### Próximo Passo

Depois de teres os mockups do Gemini, volta ao Claude Code para:
1. Converter HTML/CSS para componentes React
2. Aplicar classes Tailwind
3. Integrar com shadcn/ui
4. Adicionar TypeScript types
