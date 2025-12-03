# Convenções de Nomenclatura

## Praia do Norte Unified Platform

Este documento define os padrões de nomenclatura oficiais do projeto.

---

## URLs e Domínios

| Ambiente | Frontend | API |
|----------|----------|-----|
| **Produção** | `https://praiadonortenazare.pt` | `https://api.praiadonortenazare.pt` |
| **Desenvolvimento** | `http://localhost:3000` | `http://localhost:8000` |

### Redirects Configurados

| Domínio Antigo | Redireciona Para |
|----------------|------------------|
| `carsurf.nazare.pt` | `praiadonortenazare.pt/pt/carsurf` |
| `nazarequalifica.pt` | `praiadonortenazare.pt/pt/nazare-qualifica` |

---

## Entidades

### Slugs Oficiais

| Entidade | Nome Display | Slug (URL) | Slug (DB) |
|----------|--------------|------------|-----------|
| **Praia do Norte** | Praia do Norte | `praia-norte` | `praia_norte` |
| **Carsurf** | Carsurf | `carsurf` | `carsurf` |
| **Nazaré Qualifica** | Nazaré Qualifica | `nazare-qualifica` | `nazare_qualifica` |

### Uso nos Route Groups (Next.js)

```
app/[locale]/
├── (praia-norte)/     # Marca principal
├── (carsurf)/         # Centro de alto rendimento
└── (nazare-qualifica)/ # Empresa municipal
```

### Uso no Backend (Laravel)

```php
// Filtrar por entidade
Product::where('entity', 'praia_norte')->get();
Article::where('entity', 'carsurf')->get();
```

---

## Rotas i18n

### Mapeamento PT → EN

| Português | Inglês |
|-----------|--------|
| `/pt/loja` | `/en/shop` |
| `/pt/loja/[slug]` | `/en/shop/[slug]` |
| `/pt/noticias` | `/en/news` |
| `/pt/noticias/[slug]` | `/en/news/[slug]` |
| `/pt/surfistas` | `/en/surfers` |
| `/pt/surfistas/[slug]` | `/en/surfers/[slug]` |
| `/pt/eventos` | `/en/events` |
| `/pt/sobre` | `/en/about` |
| `/pt/contacto` | `/en/contact` |
| `/pt/carrinho` | `/en/cart` |
| `/pt/checkout` | `/en/checkout` |
| `/pt/conta` | `/en/account` |
| `/pt/conta/encomendas` | `/en/account/orders` |
| `/pt/conta/perfil` | `/en/account/profile` |

### Entidades

| PT | EN |
|----|-----|
| `/pt/carsurf` | `/en/carsurf` |
| `/pt/carsurf/instalacoes` | `/en/carsurf/facilities` |
| `/pt/carsurf/programas` | `/en/carsurf/programs` |
| `/pt/nazare-qualifica` | `/en/nazare-qualifica` |
| `/pt/nazare-qualifica/servicos` | `/en/nazare-qualifica/services` |
| `/pt/nazare-qualifica/infraestruturas` | `/en/nazare-qualifica/infrastructure` |

---

## Componentes (Frontend)

### Padrão de Nomenclatura

| Tipo | Convenção | Exemplo |
|------|-----------|---------|
| **Componentes** | PascalCase | `ProductCard.tsx` |
| **Hooks** | camelCase com `use` | `useCart.ts` |
| **Stores (Zustand)** | kebab-case com `-store` | `cart-store.ts` |
| **Utils** | camelCase | `formatters.ts` |
| **Types** | PascalCase | `Product`, `CartItem` |
| **Validações (Zod)** | camelCase | `checkoutSchema` |

### Estrutura de Componentes

```typescript
// components/ecommerce/ProductCard.tsx

interface ProductCardProps {
  product: Product
  onAddToCart?: (product: Product) => void
}

export function ProductCard({ product, onAddToCart }: ProductCardProps) {
  // ...
}
```

---

## API Endpoints

### Convenção REST

| Método | Rota | Descrição |
|--------|------|-----------|
| `GET` | `/api/products` | Listar produtos |
| `GET` | `/api/products/{id}` | Obter produto |
| `POST` | `/api/cart` | Adicionar ao carrinho |
| `PUT` | `/api/cart/{id}` | Atualizar item |
| `DELETE` | `/api/cart/{id}` | Remover item |
| `POST` | `/api/orders` | Criar encomenda |
| `GET` | `/api/orders` | Listar encomendas |
| `POST` | `/api/auth/login` | Login |
| `POST` | `/api/auth/register` | Registo |
| `POST` | `/api/auth/logout` | Logout |
| `POST` | `/api/payments/webhook` | Webhook Easypay |

### Parâmetros Comuns

| Parâmetro | Uso | Exemplo |
|-----------|-----|---------|
| `locale` | Idioma | `?locale=pt` |
| `entity` | Filtrar por entidade | `?entity=praia_norte` |
| `page` | Paginação | `?page=2` |
| `per_page` | Items por página | `?per_page=12` |
| `sort` | Ordenação | `?sort=-created_at` |

---

## Base de Dados

### Tabelas

| Tabela | Descrição |
|--------|-----------|
| `users` | Utilizadores |
| `products` | Produtos |
| `product_variants` | Variantes (tamanho, cor) |
| `categories` | Categorias |
| `carts` | Carrinhos |
| `cart_items` | Items do carrinho |
| `orders` | Encomendas |
| `order_items` | Items da encomenda |
| `transactions` | Transações Easypay |
| `articles` | Notícias/artigos |
| `surfers` | Perfis de surfistas |
| `surfboards` | Pranchas |
| `events` | Eventos |

### Campos Comuns

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | BIGINT | Primary key |
| `entity` | ENUM | `'praia_norte'`, `'carsurf'`, `'nazare_qualifica'` |
| `locale` | VARCHAR(2) | `'pt'`, `'en'` |
| `slug` | VARCHAR | URL amigável |
| `created_at` | TIMESTAMP | Data criação |
| `updated_at` | TIMESTAMP | Data atualização |

---

## Variáveis de Ambiente

### Frontend (.env.local)

```env
NEXT_PUBLIC_API_URL=https://api.praiadonortenazare.pt
NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME=
```

### Backend (.env)

```env
APP_URL=https://api.praiadonortenazare.pt
SANCTUM_STATEFUL_DOMAINS=praiadonortenazare.pt,www.praiadonortenazare.pt

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=praia_do_norte
DB_USERNAME=pdn_app
DB_PASSWORD=

EASYPAY_ACCOUNT_ID=
EASYPAY_API_KEY=
EASYPAY_BASE_URL=https://api.prod.easypay.pt/2.0
EASYPAY_WEBHOOK_SECRET=
```

---

## Git

### Branches

| Branch | Uso |
|--------|-----|
| `main` | Produção |
| `develop` | Desenvolvimento |
| `feature/*` | Novas funcionalidades |
| `fix/*` | Correções |
| `hotfix/*` | Correções urgentes em produção |

### Commits

Usar [Conventional Commits](https://www.conventionalcommits.org/):

```
feat: adicionar página de surfistas
fix: corrigir cálculo de IVA no checkout
docs: atualizar README
style: formatar código com prettier
refactor: extrair lógica de carrinho para hook
test: adicionar testes para EasypayService
chore: atualizar dependências
```

---

## Documentação Relacionada

- [FOLDER_STRUCTURE.md](./FOLDER_STRUCTURE.md) - Estrutura de pastas
- [CLAUDE.md](../../CLAUDE.md) - Referência técnica
