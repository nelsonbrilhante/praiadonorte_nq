# Fase 2: Homepage e Páginas Institucionais

**Duração Estimada**: 1 semana
**Dependências**: Fase 1
**Bloco**: 2 - Institucional

> **Nota**: Esta fase não inclui a secção "Produtos em Destaque" da homepage, que será adicionada na Fase 7 (Catálogo).

---

## Objetivos

- Criar homepage impactante
- Implementar páginas institucionais
- Configurar i18n nas rotas

---

## Tarefas

### 3.1 Homepage

**Estrutura da Homepage:**

```
┌─────────────────────────────────────────────────────────┐
│                    HERO SECTION                         │
│  (Vídeo/Imagem ondas gigantes + CTA "Explorar Loja")   │
├─────────────────────────────────────────────────────────┤
│                 PRODUTOS EM DESTAQUE                    │
│  [Card] [Card] [Card] [Card]                           │
├─────────────────────────────────────────────────────────┤
│                   ÚLTIMAS NOTÍCIAS                      │
│  [Artigo] [Artigo] [Artigo]                            │
├─────────────────────────────────────────────────────────┤
│                    SURFER WALL                          │
│  (Carrossel de surfistas em destaque)                  │
├─────────────────────────────────────────────────────────┤
│              ENTIDADES (3 colunas)                      │
│  [Praia do Norte] [Carsurf] [Nazaré Qualifica]        │
├─────────────────────────────────────────────────────────┤
│                      FOOTER                             │
└─────────────────────────────────────────────────────────┘
```

### 3.2 Configuração i18n

**`src/i18n.ts`**:

```typescript
import { getRequestConfig } from 'next-intl/server'

export const locales = ['pt', 'en'] as const
export const defaultLocale = 'pt' as const

export default getRequestConfig(async ({ locale }) => ({
  messages: (await import(`./messages/${locale}.json`)).default,
}))
```

**`src/messages/pt.json`**:

```json
{
  "common": {
    "shop": "Loja",
    "news": "Notícias",
    "surferWall": "Surfer Wall",
    "about": "Sobre",
    "contact": "Contacto"
  },
  "home": {
    "hero": {
      "title": "Praia do Norte",
      "subtitle": "Onde as ondas gigantes ganham vida",
      "cta": "Explorar Loja"
    }
  },
  "shop": {
    "addToCart": "Adicionar ao Carrinho",
    "outOfStock": "Esgotado"
  }
}
```

**`src/messages/en.json`**:

```json
{
  "common": {
    "shop": "Shop",
    "news": "News",
    "surferWall": "Surfer Wall",
    "about": "About",
    "contact": "Contact"
  },
  "home": {
    "hero": {
      "title": "Praia do Norte",
      "subtitle": "Where giant waves come to life",
      "cta": "Explore Shop"
    }
  },
  "shop": {
    "addToCart": "Add to Cart",
    "outOfStock": "Out of Stock"
  }
}
```

### 3.3 Páginas Institucionais

Criar páginas para cada entidade:

- `/sobre` - Sobre Praia do Norte
- `/carsurf/sobre` - Sobre Carsurf
- `/nazare-qualifica/sobre` - Sobre NQ
- `/contacto` - Formulário de contacto

---

## Entregáveis

- [ ] Homepage completa com todas as secções
- [ ] Página "Sobre" para cada entidade
- [ ] Página de contacto com formulário
- [ ] i18n funcionando (PT/EN)
- [ ] Language switcher no header
- [ ] SEO meta tags por página

---

## Critérios de Conclusão

1. Homepage carrega em menos de 3s
2. Troca de idioma funciona corretamente
3. Meta tags dinâmicas por página
4. Formulário de contacto envia emails
5. Design responsivo em todos os breakpoints
