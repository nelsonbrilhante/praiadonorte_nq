# Fase 7: E-commerce - Catálogo de Produtos

**Duração Estimada**: 1 semana
**Dependências**: Fase 6
**Bloco**: 4 - E-commerce

---

## Objetivos

- Implementar listagem de produtos
- Criar página de produto individual
- Integrar com Aimeos API

---

## Tarefas

### 4.1 API Client para Aimeos

**`src/lib/api/aimeos.ts`**:

```typescript
const API_URL = process.env.NEXT_PUBLIC_API_URL

export async function getProducts(params?: {
  category?: string
  locale?: string
  page?: number
  limit?: number
}) {
  const searchParams = new URLSearchParams()
  if (params?.category) searchParams.set('filter[f_catid]', params.category)
  if (params?.locale) searchParams.set('locale', params.locale)
  if (params?.page) searchParams.set('page[offset]', String((params.page - 1) * (params.limit || 12)))
  if (params?.limit) searchParams.set('page[limit]', String(params.limit))

  const res = await fetch(`${API_URL}/jsonapi/product?${searchParams}`, {
    next: { revalidate: 60 },
  })

  if (!res.ok) throw new Error('Failed to fetch products')
  return res.json()
}

export async function getProduct(slug: string, locale: string) {
  const res = await fetch(`${API_URL}/jsonapi/product?filter[f_code]=${slug}&locale=${locale}`, {
    next: { revalidate: 60 },
  })

  if (!res.ok) throw new Error('Product not found')
  const data = await res.json()
  return data.data[0]
}
```

### 4.2 Componente de Produto

**`src/components/e-commerce/ProductCard.tsx`**:

```typescript
import Image from 'next/image'
import Link from 'next/link'
import { Card, CardContent, CardFooter } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { formatPrice } from '@/lib/utils'
import { useCartStore } from '@/store/cart'

interface ProductCardProps {
  product: {
    id: string
    code: string
    label: string
    price: number
    image: string
    stock: number
  }
}

export function ProductCard({ product }: ProductCardProps) {
  const addItem = useCartStore((state) => state.addItem)

  return (
    <Card className="group overflow-hidden">
      <Link href={`/loja/${product.code}`}>
        <div className="relative aspect-square overflow-hidden">
          <Image
            src={product.image}
            alt={product.label}
            fill
            className="object-cover transition-transform group-hover:scale-105"
          />
        </div>
      </Link>

      <CardContent className="p-4">
        <h3 className="font-display font-semibold">{product.label}</h3>
        <p className="text-lg font-bold text-ocean-500">
          {formatPrice(product.price)}
        </p>
      </CardContent>

      <CardFooter className="p-4 pt-0">
        <Button
          onClick={() => addItem(product)}
          disabled={product.stock === 0}
          className="w-full"
        >
          {product.stock > 0 ? 'Adicionar ao Carrinho' : 'Esgotado'}
        </Button>
      </CardFooter>
    </Card>
  )
}
```

### 4.3 Página de Listagem

**`src/app/[locale]/(praia-do-norte)/loja/page.tsx`**:

```typescript
import { getProducts } from '@/lib/api/aimeos'
import { ProductCard } from '@/components/e-commerce/ProductCard'

export default async function LojaPage({
  params: { locale },
  searchParams,
}: {
  params: { locale: string }
  searchParams: { category?: string; page?: string }
}) {
  const products = await getProducts({
    locale,
    category: searchParams.category,
    page: Number(searchParams.page) || 1,
  })

  return (
    <main className="container py-8">
      <h1 className="font-display text-4xl font-bold mb-8">Loja</h1>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {products.data.map((product) => (
          <ProductCard key={product.id} product={product} />
        ))}
      </div>
    </main>
  )
}
```

### 4.4 Página de Produto Individual

**`src/app/[locale]/(praia-do-norte)/loja/[slug]/page.tsx`**:

```typescript
import { getProduct } from '@/lib/api/aimeos'
import { ProductGallery } from '@/components/e-commerce/ProductGallery'
import { AddToCartButton } from '@/components/e-commerce/AddToCartButton'

export default async function ProductPage({
  params: { locale, slug },
}: {
  params: { locale: string; slug: string }
}) {
  const product = await getProduct(slug, locale)

  return (
    <main className="container py-8">
      <div className="grid md:grid-cols-2 gap-8">
        <ProductGallery images={product.images} />

        <div>
          <h1 className="font-display text-3xl font-bold">{product.label}</h1>
          <p className="text-2xl font-bold text-ocean-500 mt-4">
            {formatPrice(product.price)}
          </p>

          <div className="mt-6" dangerouslySetInnerHTML={{ __html: product.description }} />

          <AddToCartButton product={product} />
        </div>
      </div>
    </main>
  )
}
```

---

## Entregáveis

- [ ] Página de listagem de produtos com filtros
- [ ] Página de produto individual
- [ ] Galeria de imagens do produto
- [ ] Seleção de variantes (tamanho, cor)
- [ ] Indicador de stock
- [ ] Produtos relacionados

---

## Critérios de Conclusão

1. Listagem mostra todos os produtos do Aimeos
2. Filtros por categoria funcionam
3. Página de produto mostra todas as informações
4. Imagens carregam com otimização Next.js
5. Variantes atualizam preço e disponibilidade
