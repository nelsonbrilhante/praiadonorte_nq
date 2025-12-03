# Estrutura de Pastas

## Praia do Norte Unified Platform

Este documento define a estrutura oficial de pastas do projeto.

---

## Repositório Principal

```
praia-do-norte-unified/
├── frontend/                            # Next.js 15 (Vercel)
├── backend/                             # Laravel 12 (VPS)
├── docs/
│   ├── phases/                          # Guias de implementação (FASE_00 a FASE_10)
│   ├── architecture/                    # Documentação técnica
│   └── archive/                         # Documentos históricos
├── api/
│   └── swagger.json                     # OpenAPI spec (Easypay)
├── .github/workflows/                   # CI/CD
├── CLAUDE.md
├── PLANO_DESENVOLVIMENTO.md
└── README.md
```

---

## Frontend (Next.js 15)

```
frontend/
├── src/
│   ├── app/
│   │   ├── [locale]/                    # Rotas i18n (/pt/, /en/)
│   │   │   ├── layout.tsx
│   │   │   ├── page.tsx                 # Homepage
│   │   │   │
│   │   │   ├── (praia-norte)/           # Route group - marca principal
│   │   │   │   ├── loja/
│   │   │   │   │   ├── page.tsx
│   │   │   │   │   └── [slug]/page.tsx
│   │   │   │   ├── noticias/
│   │   │   │   │   ├── page.tsx
│   │   │   │   │   └── [slug]/page.tsx
│   │   │   │   ├── surfistas/
│   │   │   │   │   ├── page.tsx
│   │   │   │   │   └── [slug]/page.tsx
│   │   │   │   ├── eventos/page.tsx
│   │   │   │   └── sobre/page.tsx
│   │   │   │
│   │   │   ├── (carsurf)/
│   │   │   │   ├── page.tsx
│   │   │   │   ├── instalacoes/page.tsx
│   │   │   │   └── programas/page.tsx
│   │   │   │
│   │   │   ├── (nazare-qualifica)/
│   │   │   │   ├── page.tsx
│   │   │   │   ├── servicos/page.tsx
│   │   │   │   └── infraestruturas/page.tsx
│   │   │   │
│   │   │   ├── carrinho/page.tsx
│   │   │   ├── checkout/page.tsx
│   │   │   ├── conta/
│   │   │   │   ├── page.tsx
│   │   │   │   ├── encomendas/page.tsx
│   │   │   │   └── perfil/page.tsx
│   │   │   │
│   │   │   └── contacto/page.tsx
│   │   │
│   │   ├── globals.css
│   │   ├── layout.tsx
│   │   └── not-found.tsx
│   │
│   ├── components/
│   │   ├── ui/                          # shadcn/ui
│   │   │   ├── button.tsx
│   │   │   ├── card.tsx
│   │   │   ├── dialog.tsx
│   │   │   ├── dropdown-menu.tsx
│   │   │   ├── form.tsx
│   │   │   ├── input.tsx
│   │   │   ├── label.tsx
│   │   │   ├── navigation-menu.tsx
│   │   │   ├── select.tsx
│   │   │   ├── sheet.tsx
│   │   │   ├── skeleton.tsx
│   │   │   ├── toast.tsx
│   │   │   └── toaster.tsx
│   │   │
│   │   ├── layout/
│   │   │   ├── Header.tsx
│   │   │   ├── Footer.tsx
│   │   │   ├── MobileNav.tsx
│   │   │   ├── LanguageSwitcher.tsx
│   │   │   └── Logo.tsx
│   │   │
│   │   ├── content/
│   │   │   ├── ArticleCard.tsx
│   │   │   ├── ArticleList.tsx
│   │   │   ├── SurferCard.tsx
│   │   │   ├── SurferWall.tsx
│   │   │   ├── EventCard.tsx
│   │   │   ├── WebcamEmbed.tsx
│   │   │   └── WaveForecast.tsx
│   │   │
│   │   ├── ecommerce/
│   │   │   ├── ProductCard.tsx
│   │   │   ├── ProductGrid.tsx
│   │   │   ├── ProductDetail.tsx
│   │   │   ├── ProductGallery.tsx
│   │   │   ├── Cart.tsx
│   │   │   ├── CartItem.tsx
│   │   │   ├── CartIcon.tsx
│   │   │   ├── CartDrawer.tsx
│   │   │   ├── CheckoutForm.tsx
│   │   │   ├── CheckoutSummary.tsx
│   │   │   ├── PaymentMethods.tsx
│   │   │   └── OrderConfirmation.tsx
│   │   │
│   │   ├── forms/
│   │   │   ├── ContactForm.tsx
│   │   │   ├── NewsletterForm.tsx
│   │   │   └── LoginForm.tsx
│   │   │
│   │   └── seo/
│   │       ├── JsonLd.tsx
│   │       ├── ProductJsonLd.tsx
│   │       ├── ArticleJsonLd.tsx
│   │       └── OrganizationJsonLd.tsx
│   │
│   ├── lib/
│   │   ├── api/
│   │   │   ├── client.ts
│   │   │   ├── products.ts
│   │   │   ├── cart.ts
│   │   │   ├── orders.ts
│   │   │   ├── content.ts
│   │   │   └── auth.ts
│   │   │
│   │   ├── utils/
│   │   │   ├── cn.ts
│   │   │   ├── formatters.ts
│   │   │   └── validators.ts
│   │   │
│   │   └── hooks/
│   │       ├── useCart.ts
│   │       ├── useAuth.ts
│   │       └── useMediaQuery.ts
│   │
│   ├── store/
│   │   ├── cart-store.ts
│   │   ├── auth-store.ts
│   │   └── ui-store.ts
│   │
│   ├── types/
│   │   ├── index.ts
│   │   ├── product.ts
│   │   ├── cart.ts
│   │   ├── order.ts
│   │   ├── user.ts
│   │   ├── content.ts
│   │   └── api.ts
│   │
│   ├── validations/
│   │   ├── checkout.ts
│   │   ├── contact.ts
│   │   └── auth.ts
│   │
│   └── i18n/
│       ├── config.ts
│       ├── request.ts
│       └── messages/
│           ├── pt.json
│           └── en.json
│
├── public/
│   ├── images/
│   │   ├── hero/
│   │   ├── logos/
│   │   └── placeholders/
│   ├── icons/
│   └── fonts/
│
├── .env.local
├── .env.example
├── next.config.ts
├── tailwind.config.ts
├── tsconfig.json
├── components.json
└── package.json
```

---

## Backend (Laravel 12)

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── HealthController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── CartController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── CheckoutController.php
│   │   │   │   ├── PaymentController.php
│   │   │   │   ├── AuthController.php
│   │   │   │   └── ContentController.php
│   │   │   └── Controller.php
│   │   │
│   │   ├── Middleware/
│   │   │   ├── VerifyEasypaySignature.php
│   │   │   ├── ForceJsonResponse.php
│   │   │   └── LocaleMiddleware.php
│   │   │
│   │   ├── Requests/
│   │   │   ├── CheckoutRequest.php
│   │   │   ├── ContactRequest.php
│   │   │   └── AuthRequest.php
│   │   │
│   │   └── Resources/
│   │       ├── ProductResource.php
│   │       ├── CartResource.php
│   │       ├── OrderResource.php
│   │       └── ContentResource.php
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── ProductVariant.php
│   │   ├── Category.php
│   │   ├── Cart.php
│   │   ├── CartItem.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Transaction.php
│   │   ├── Article.php
│   │   ├── Surfer.php
│   │   ├── Surfboard.php
│   │   └── Event.php
│   │
│   ├── Services/
│   │   ├── EasypayService.php
│   │   ├── CartService.php
│   │   ├── OrderService.php
│   │   └── ContentService.php
│   │
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── EasypayServiceProvider.php
│
├── config/
│   ├── app.php
│   ├── auth.php
│   ├── cors.php
│   ├── database.php
│   ├── sanctum.php
│   └── easypay.php
│
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
│
├── routes/
│   ├── api.php
│   └── web.php
│
├── storage/
│   ├── app/public/
│   │   ├── products/
│   │   ├── articles/
│   │   ├── surfers/
│   │   └── events/
│   └── logs/
│
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── .env.example
├── artisan
├── composer.json
└── phpunit.xml
```

---

## Documentação Relacionada

- [NAMING_CONVENTIONS.md](./NAMING_CONVENTIONS.md) - Padrões de nomenclatura
- [PLANO_DESENVOLVIMENTO.md](../../PLANO_DESENVOLVIMENTO.md) - Plano de desenvolvimento
- [CLAUDE.md](../../CLAUDE.md) - Referência técnica
