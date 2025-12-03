# Fase 1: Design System e Componentes Base

**Duração Estimada**: 1 semana
**Dependências**: Fase 0

---

## Objetivos

- Implementar design system completo
- Criar componentes reutilizáveis
- Configurar tipografia e cores

---

## Tarefas

### 1.1 Configuração Tailwind

**`tailwind.config.ts`**:

```typescript
import type { Config } from 'tailwindcss'

const config: Config = {
  content: ['./src/**/*.{js,ts,jsx,tsx,mdx}'],
  theme: {
    extend: {
      colors: {
        // Praia do Norte (Primary)
        'ocean': {
          50: '#e6f3ff',
          500: '#0066cc',
          900: '#003366',
        },
        // Nazaré Qualifica (Secondary)
        'institutional': {
          50: '#fff4e6',
          500: '#ffa500',
          900: '#cc6600',
        },
        // Carsurf (Tertiary)
        'performance': {
          50: '#e6fff5',
          500: '#00cc66',
          900: '#008844',
        },
      },
      fontFamily: {
        sans: ['var(--font-inter)'],
        display: ['var(--font-montserrat)'],
      },
    },
  },
  plugins: [require('tailwindcss-animate')],
}

export default config
```

### 1.2 Componentes de Layout

**Header com Navegação Multi-Entidade:**

```typescript
// src/components/layout/Header.tsx
'use client'

import Link from 'next/link'
import { usePathname } from 'next/navigation'
import { NavigationMenu, NavigationMenuItem } from '@/components/ui/navigation-menu'

export function Header() {
  const pathname = usePathname()

  return (
    <header className="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur">
      <div className="container flex h-16 items-center">
        <Link href="/" className="font-display text-xl font-bold text-ocean-900">
          Praia do Norte
        </Link>

        <NavigationMenu className="ml-8">
          <NavigationMenuItem>
            <Link href="/loja" className="text-sm font-medium">
              Loja
            </Link>
          </NavigationMenuItem>
          <NavigationMenuItem>
            <Link href="/noticias" className="text-sm font-medium">
              Notícias
            </Link>
          </NavigationMenuItem>
          <NavigationMenuItem>
            <Link href="/surfer-wall" className="text-sm font-medium">
              Surfer Wall
            </Link>
          </NavigationMenuItem>
        </NavigationMenu>

        {/* Cart Icon */}
        <div className="ml-auto">
          <CartIcon />
        </div>
      </div>
    </header>
  )
}
```

### 1.3 Componentes shadcn/ui

Instalar componentes necessários:

```bash
npx shadcn@latest add button card input label form dialog sheet
npx shadcn@latest add navigation-menu dropdown-menu avatar badge
npx shadcn@latest add select textarea checkbox radio-group
```

---

## Entregáveis

- [ ] Tailwind configurado com cores das 3 entidades
- [ ] Tipografia (Montserrat + Inter) implementada
- [ ] Header responsivo com navegação
- [ ] Footer com 3 colunas (uma por entidade)
- [ ] Componentes shadcn/ui customizados
- [ ] Dark mode (opcional)

---

## Critérios de Conclusão

1. Design system documentado com exemplos
2. Componentes de layout funcionam em mobile/desktop
3. Cores e tipografia consistentes em toda a app
4. Navegação funcional entre secções
