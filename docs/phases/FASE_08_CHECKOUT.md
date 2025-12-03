# Fase 8: E-commerce - Carrinho e Checkout

**Duração Estimada**: 1 semana
**Dependências**: Fase 7
**Bloco**: 4 - E-commerce

---

## Objetivos

- Implementar carrinho de compras
- Criar fluxo de checkout
- Validação de dados

---

## Tarefas

### 5.1 Store do Carrinho (Zustand)

**`src/store/cart.ts`**:

```typescript
import { create } from 'zustand'
import { persist } from 'zustand/middleware'

interface CartItem {
  id: string
  code: string
  label: string
  price: number
  quantity: number
  image: string
}

interface CartStore {
  items: CartItem[]
  addItem: (product: Omit<CartItem, 'quantity'>) => void
  removeItem: (id: string) => void
  updateQuantity: (id: string, quantity: number) => void
  clearCart: () => void
  total: () => number
}

export const useCartStore = create<CartStore>()(
  persist(
    (set, get) => ({
      items: [],

      addItem: (product) => {
        const items = get().items
        const existing = items.find((item) => item.id === product.id)

        if (existing) {
          set({
            items: items.map((item) =>
              item.id === product.id
                ? { ...item, quantity: item.quantity + 1 }
                : item
            ),
          })
        } else {
          set({ items: [...items, { ...product, quantity: 1 }] })
        }
      },

      removeItem: (id) => {
        set({ items: get().items.filter((item) => item.id !== id) })
      },

      updateQuantity: (id, quantity) => {
        if (quantity <= 0) {
          get().removeItem(id)
          return
        }
        set({
          items: get().items.map((item) =>
            item.id === id ? { ...item, quantity } : item
          ),
        })
      },

      clearCart: () => set({ items: [] }),

      total: () => {
        return get().items.reduce(
          (sum, item) => sum + item.price * item.quantity,
          0
        )
      },
    }),
    { name: 'cart-storage' }
  )
)
```

### 5.2 Schema de Validação (Checkout)

**`src/lib/validations/checkout.ts`**:

```typescript
import { z } from 'zod'

export const checkoutSchema = z.object({
  // Dados pessoais
  firstName: z.string().min(2, 'Nome muito curto'),
  lastName: z.string().min(2, 'Apelido muito curto'),
  email: z.string().email('Email inválido'),
  phone: z.string().regex(/^[0-9]{9}$/, 'Telefone inválido'),
  nif: z.string().regex(/^[0-9]{9}$/, 'NIF inválido').optional(),

  // Morada de envio
  address: z.string().min(5, 'Morada muito curta'),
  city: z.string().min(2, 'Cidade inválida'),
  postalCode: z.string().regex(/^[0-9]{4}-[0-9]{3}$/, 'Código postal inválido'),
  country: z.literal('PT'),

  // Pagamento
  paymentMethod: z.enum(['card', 'mbway', 'multibanco']),

  // Consentimentos
  acceptTerms: z.literal(true, { errorMap: () => ({ message: 'Deve aceitar os termos' }) }),
  acceptMarketing: z.boolean().optional(),
})

export type CheckoutData = z.infer<typeof checkoutSchema>
```

### 5.3 Componente do Carrinho

**`src/components/e-commerce/CartSheet.tsx`**:

```typescript
'use client'

import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet'
import { Button } from '@/components/ui/button'
import { ShoppingCart } from 'lucide-react'
import { useCartStore } from '@/store/cart'
import { formatPrice } from '@/lib/utils'

export function CartSheet() {
  const { items, removeItem, updateQuantity, total } = useCartStore()

  return (
    <Sheet>
      <SheetTrigger asChild>
        <Button variant="ghost" size="icon" className="relative">
          <ShoppingCart className="h-5 w-5" />
          {items.length > 0 && (
            <span className="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-ocean-500 text-white text-xs flex items-center justify-center">
              {items.length}
            </span>
          )}
        </Button>
      </SheetTrigger>

      <SheetContent>
        <SheetHeader>
          <SheetTitle>Carrinho</SheetTitle>
        </SheetHeader>

        <div className="mt-8 space-y-4">
          {items.map((item) => (
            <div key={item.id} className="flex gap-4">
              <img src={item.image} alt={item.label} className="w-16 h-16 object-cover rounded" />
              <div className="flex-1">
                <h4 className="font-medium">{item.label}</h4>
                <p className="text-sm text-muted-foreground">{formatPrice(item.price)}</p>
                <div className="flex items-center gap-2 mt-2">
                  <Button size="sm" variant="outline" onClick={() => updateQuantity(item.id, item.quantity - 1)}>-</Button>
                  <span>{item.quantity}</span>
                  <Button size="sm" variant="outline" onClick={() => updateQuantity(item.id, item.quantity + 1)}>+</Button>
                </div>
              </div>
              <Button variant="ghost" size="sm" onClick={() => removeItem(item.id)}>Remover</Button>
            </div>
          ))}
        </div>

        <div className="mt-8 border-t pt-4">
          <div className="flex justify-between font-bold">
            <span>Total</span>
            <span>{formatPrice(total())}</span>
          </div>
          <Button className="w-full mt-4" asChild>
            <a href="/checkout">Finalizar Compra</a>
          </Button>
        </div>
      </SheetContent>
    </Sheet>
  )
}
```

### 5.4 Página de Checkout

**`src/app/[locale]/(praia-do-norte)/checkout/page.tsx`**:

```typescript
'use client'

import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { checkoutSchema, CheckoutData } from '@/lib/validations/checkout'
import { useCartStore } from '@/store/cart'

export default function CheckoutPage() {
  const { items, total } = useCartStore()

  const form = useForm<CheckoutData>({
    resolver: zodResolver(checkoutSchema),
    defaultValues: {
      country: 'PT',
    },
  })

  const onSubmit = async (data: CheckoutData) => {
    // Enviar para API Laravel
    const response = await fetch('/api/checkout', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        ...data,
        items: items.map(i => ({ id: i.id, quantity: i.quantity })),
      }),
    })

    const result = await response.json()

    if (result.payment_url) {
      window.location.href = result.payment_url
    }
  }

  return (
    <main className="container py-8">
      <h1 className="font-display text-3xl font-bold mb-8">Checkout</h1>

      <form onSubmit={form.handleSubmit(onSubmit)}>
        {/* Form fields */}
      </form>
    </main>
  )
}
```

---

## Entregáveis

- [ ] Página do carrinho completa
- [ ] Mini-cart no header
- [ ] Fluxo de checkout multi-step
- [ ] Validação de formulários com Zod
- [ ] Cálculo de portes de envio
- [ ] Resumo da encomenda

---

## Critérios de Conclusão

1. Carrinho persiste entre sessões (localStorage)
2. Validação mostra erros em tempo real
3. Checkout não avança sem dados válidos
4. Portes calculados corretamente
5. Resumo mostra todos os itens e totais
