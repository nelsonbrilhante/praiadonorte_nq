# Next.js 16 - Referência Rápida

> Documentação de referência para Next.js 16 no projeto Praia do Norte.

---

## Versões Instaladas

- **Next.js**: 16.0.7
- **React**: 19.2.0
- **Tailwind CSS**: 4.x
- **TypeScript**: 5.x
- **Documentação oficial**: https://nextjs.org/docs

---

## Comandos NPM

```bash
# Desenvolvimento
npm run dev

# Build de produção
npm run build

# Iniciar produção
npm run start

# Lint
npm run lint
```

---

## Estrutura de Pastas (App Router)

```
frontend/
├── src/
│   └── app/
│       ├── layout.tsx           # Root layout
│       ├── page.tsx             # Homepage (/)
│       ├── globals.css          # Estilos globais
│       │
│       └── [locale]/            # i18n (a criar)
│           ├── layout.tsx
│           ├── page.tsx         # /pt ou /en
│           │
│           ├── (praia-do-norte)/
│           │   ├── sobre/
│           │   │   └── page.tsx # /pt/sobre
│           │   ├── noticias/
│           │   │   ├── page.tsx # /pt/noticias
│           │   │   └── [slug]/
│           │   │       └── page.tsx # /pt/noticias/titulo-noticia
│           │   └── surfer-wall/
│           │       ├── page.tsx
│           │       └── [slug]/
│           │           └── page.tsx
│           │
│           ├── (carsurf)/
│           │   └── sobre/
│           │       └── page.tsx
│           │
│           └── (nazare-qualifica)/
│               └── sobre/
│                   └── page.tsx
│
├── public/                      # Assets estáticos
├── messages/                    # Traduções i18n (a criar)
│   ├── pt.json
│   └── en.json
└── package.json
```

---

## Padrões do Projeto

### Layout com i18n

```tsx
// src/app/[locale]/layout.tsx
import { NextIntlClientProvider } from 'next-intl';
import { getMessages } from 'next-intl/server';

export default async function LocaleLayout({
  children,
  params: { locale }
}: {
  children: React.ReactNode;
  params: { locale: string };
}) {
  const messages = await getMessages();

  return (
    <html lang={locale}>
      <body>
        <NextIntlClientProvider messages={messages}>
          {children}
        </NextIntlClientProvider>
      </body>
    </html>
  );
}
```

### Página com Data Fetching

```tsx
// src/app/[locale]/noticias/page.tsx
import { getNoticias } from '@/lib/api/client';

export default async function NoticiasPage({
  params: { locale }
}: {
  params: { locale: string };
}) {
  const noticias = await getNoticias(locale);

  return (
    <div className="container mx-auto py-8">
      <h1 className="text-3xl font-bold mb-8">Notícias</h1>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {noticias.map((noticia) => (
          <NoticiaCard key={noticia.id} noticia={noticia} />
        ))}
      </div>
    </div>
  );
}
```

### API Client

```tsx
// src/lib/api/client.ts
const API_URL = process.env.NEXT_PUBLIC_API_URL;

export async function getNoticias(locale: string, limit?: number) {
  const url = new URL(`${API_URL}/v1/noticias`);
  url.searchParams.set('locale', locale);
  if (limit) url.searchParams.set('limit', String(limit));

  const res = await fetch(url.toString(), {
    next: { revalidate: 60 }, // ISR: revalidate a cada 60s
  });

  if (!res.ok) throw new Error('Failed to fetch noticias');
  return res.json();
}

export async function getNoticia(slug: string, locale: string) {
  const res = await fetch(`${API_URL}/v1/noticias/${slug}?locale=${locale}`, {
    next: { revalidate: 60 },
  });

  if (!res.ok) throw new Error('Failed to fetch noticia');
  return res.json();
}
```

### Metadata Dinâmica (SEO)

```tsx
// src/app/[locale]/noticias/[slug]/page.tsx
import { Metadata } from 'next';
import { getNoticia } from '@/lib/api/client';

type Props = {
  params: { slug: string; locale: string };
};

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { data: noticia } = await getNoticia(params.slug, params.locale);

  return {
    title: noticia.seo_title || noticia.title[params.locale],
    description: noticia.seo_description || noticia.excerpt,
    openGraph: {
      images: [noticia.cover_image],
    },
  };
}

export default async function NoticiaPage({ params }: Props) {
  const { data: noticia } = await getNoticia(params.slug, params.locale);
  // ...
}
```

---

## Tailwind CSS 4.x

### Configuração

```css
/* src/app/globals.css */
@import "tailwindcss";

/* Cores do projeto */
@theme {
  --color-ocean: #0066cc;           /* Praia do Norte */
  --color-institutional: #ffa500;   /* Nazaré Qualifica */
  --color-performance: #00cc66;     /* Carsurf */
}
```

### Uso

```tsx
<div className="bg-ocean text-white">
  Praia do Norte
</div>
<div className="bg-institutional">
  Nazaré Qualifica
</div>
<div className="bg-performance">
  Carsurf
</div>
```

---

## Environment Variables

```env
# frontend/.env.local

# API Backend
NEXT_PUBLIC_API_URL=http://localhost:8000/api

# Cloudinary (opcional)
NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME=

# Site URL (para sitemap, OG images)
NEXT_PUBLIC_SITE_URL=https://praiadonortenazare.pt
```

---

## Packages a Instalar

```bash
# i18n
npm install next-intl

# UI Components
npx shadcn@latest init
npx shadcn@latest add button card input navigation-menu

# State Management
npm install zustand

# Validação
npm install zod
```

---

## Links Úteis

- [Next.js 16 Documentation](https://nextjs.org/docs)
- [App Router](https://nextjs.org/docs/app)
- [Data Fetching](https://nextjs.org/docs/app/building-your-application/data-fetching)
- [Metadata API](https://nextjs.org/docs/app/building-your-application/optimizing/metadata)
- [next-intl](https://next-intl-docs.vercel.app/)
- [shadcn/ui](https://ui.shadcn.com/)
- [Tailwind CSS 4](https://tailwindcss.com/docs)
