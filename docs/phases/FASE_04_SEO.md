# Fase 4: SEO e Performance

**Duração Estimada**: 1 semana
**Dependências**: Fase 3
**Bloco**: 3 - Qualidade

---

## Objetivos

- Otimizar SEO
- Melhorar performance
- Implementar structured data

---

## Tarefas

### 9.1 Metadata Dinâmica

**`src/app/[locale]/(praia-do-norte)/loja/[slug]/page.tsx`**:

```typescript
import { Metadata } from 'next'
import { getProduct } from '@/lib/api/aimeos'

export async function generateMetadata({
  params: { locale, slug }
}): Promise<Metadata> {
  const product = await getProduct(slug, locale)

  return {
    title: `${product.label} | Loja Praia do Norte`,
    description: product.description.substring(0, 160),
    openGraph: {
      title: product.label,
      description: product.description,
      images: [product.image],
      type: 'product',
    },
  }
}
```

### 9.2 Structured Data (JSON-LD)

**`src/components/seo/ProductJsonLd.tsx`**:

```typescript
interface ProductJsonLdProps {
  product: {
    name: string
    description: string
    image: string
    price: number
    sku: string
    availability: 'InStock' | 'OutOfStock'
  }
}

export function ProductJsonLd({ product }: ProductJsonLdProps) {
  const jsonLd = {
    '@context': 'https://schema.org',
    '@type': 'Product',
    name: product.name,
    description: product.description,
    image: product.image,
    sku: product.sku,
    offers: {
      '@type': 'Offer',
      price: product.price,
      priceCurrency: 'EUR',
      availability: `https://schema.org/${product.availability}`,
    },
  }

  return (
    <script
      type="application/ld+json"
      dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
    />
  )
}
```

**`src/components/seo/OrganizationJsonLd.tsx`**:

```typescript
export function OrganizationJsonLd() {
  const jsonLd = {
    '@context': 'https://schema.org',
    '@type': 'Organization',
    name: 'Praia do Norte',
    url: 'https://praiadonortenazare.pt',
    logo: 'https://praiadonortenazare.pt/logo.png',
    contactPoint: {
      '@type': 'ContactPoint',
      email: 'info@praiadonortenazare.pt',
      contactType: 'customer service',
    },
    sameAs: [
      'https://facebook.com/praiadonorte',
      'https://instagram.com/praiadonorte',
    ],
  }

  return (
    <script
      type="application/ld+json"
      dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
    />
  )
}
```

### 9.3 Performance Optimizations

**`next.config.js`**:

```javascript
/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    remotePatterns: [
      {
        protocol: 'https',
        hostname: 'api.praiadonortenazare.pt',
      },
    ],
    formats: ['image/avif', 'image/webp'],
  },

  experimental: {
    optimizePackageImports: ['lucide-react'],
  },
}

module.exports = nextConfig
```

### 9.4 Sitemap Automático

**`src/app/sitemap.ts`**:

```typescript
import { MetadataRoute } from 'next'
import { getProducts, getArticles, getSurfers } from '@/lib/api/aimeos'

export default async function sitemap(): MetadataRoute.Sitemap {
  const baseUrl = 'https://praiadonortenazare.pt'

  // Páginas estáticas
  const staticPages = [
    { url: baseUrl, lastModified: new Date() },
    { url: `${baseUrl}/loja`, lastModified: new Date() },
    { url: `${baseUrl}/noticias`, lastModified: new Date() },
    { url: `${baseUrl}/surfer-wall`, lastModified: new Date() },
    { url: `${baseUrl}/sobre`, lastModified: new Date() },
    { url: `${baseUrl}/contacto`, lastModified: new Date() },
  ]

  // Produtos
  const products = await getProducts({ limit: 100 })
  const productPages = products.data.map((product) => ({
    url: `${baseUrl}/loja/${product.code}`,
    lastModified: new Date(product.updatedAt),
  }))

  // Artigos
  const articles = await getArticles({ limit: 100 })
  const articlePages = articles.data.map((article) => ({
    url: `${baseUrl}/noticias/${article.slug}`,
    lastModified: new Date(article.updatedAt),
  }))

  // Surfistas
  const surfers = await getSurfers({ limit: 50 })
  const surferPages = surfers.data.map((surfer) => ({
    url: `${baseUrl}/surfer-wall/${surfer.slug}`,
    lastModified: new Date(surfer.updatedAt),
  }))

  return [...staticPages, ...productPages, ...articlePages, ...surferPages]
}
```

### 9.5 Robots.txt

**`src/app/robots.ts`**:

```typescript
import { MetadataRoute } from 'next'

export default function robots(): MetadataRoute.Robots {
  return {
    rules: {
      userAgent: '*',
      allow: '/',
      disallow: ['/api/', '/admin/', '/checkout/'],
    },
    sitemap: 'https://praiadonortenazare.pt/sitemap.xml',
  }
}
```

### 9.6 Image Optimization

```typescript
// Sempre usar next/image para imagens
import Image from 'next/image'

// Usar sizes para responsive
<Image
  src={product.image}
  alt={product.name}
  width={400}
  height={400}
  sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 25vw"
  priority={isAboveFold}
/>
```

---

## Métricas Target

| Métrica | Target |
|---------|--------|
| Lighthouse Performance | > 90 |
| Lighthouse Accessibility | > 95 |
| Lighthouse Best Practices | > 95 |
| Lighthouse SEO | > 95 |
| LCP | < 2.5s |
| FID | < 100ms |
| CLS | < 0.1 |

---

## Entregáveis

- [ ] Meta tags dinâmicas em todas as páginas
- [ ] Open Graph tags para redes sociais
- [ ] Structured data (JSON-LD) para produtos
- [ ] Sitemap.xml automático
- [ ] Robots.txt configurado
- [ ] Lighthouse score > 90
- [ ] Core Web Vitals otimizados

---

## Critérios de Conclusão

1. Todas as páginas têm meta tags únicos
2. Partilha em redes sociais mostra preview correto
3. Google Search Console mostra structured data válido
4. Sitemap indexa todas as páginas dinâmicas
5. Lighthouse Performance > 90 em mobile
6. Zero erros de acessibilidade críticos
