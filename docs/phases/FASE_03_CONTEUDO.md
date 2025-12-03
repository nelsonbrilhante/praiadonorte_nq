# Fase 3: Conteúdo Dinâmico

**Duração Estimada**: 1 semana
**Dependências**: Fase 2
**Bloco**: 2 - Institucional

---

## Objetivos

- Implementar secção de notícias
- Criar Surfer Wall
- Integrar eventos

---

## Tarefas

### 8.1 Página de Notícias

**`src/app/[locale]/(praia-do-norte)/noticias/page.tsx`**:

```typescript
import { getArticles } from '@/lib/api/aimeos'
import { ArticleCard } from '@/components/content/ArticleCard'

export default async function NoticiasPage({
  params: { locale }
}: {
  params: { locale: string }
}) {
  const articles = await getArticles({ locale, limit: 12 })

  return (
    <main className="container py-8">
      <h1 className="font-display text-4xl font-bold mb-8">Notícias</h1>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {articles.map((article) => (
          <ArticleCard key={article.id} article={article} />
        ))}
      </div>
    </main>
  )
}
```

### 8.2 Surfer Wall

**`src/app/[locale]/(praia-do-norte)/surfer-wall/page.tsx`**:

```typescript
import { getSurfers } from '@/lib/api/aimeos'
import { SurferCard } from '@/components/content/SurferCard'

export default async function SurferWallPage({
  params: { locale }
}: {
  params: { locale: string }
}) {
  const surfers = await getSurfers({ locale, featured: true })

  return (
    <main className="container py-8">
      <h1 className="font-display text-4xl font-bold mb-4">Surfer Wall</h1>
      <p className="text-muted-foreground mb-8">
        Os atletas que desafiam as ondas gigantes da Praia do Norte
      </p>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {surfers.map((surfer) => (
          <SurferCard key={surfer.id} surfer={surfer} />
        ))}
      </div>
    </main>
  )
}
```

### 8.3 Componente SurferCard

**`src/components/content/SurferCard.tsx`**:

```typescript
import Image from 'next/image'
import Link from 'next/link'
import { Card, CardContent } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'

interface SurferCardProps {
  surfer: {
    id: string
    slug: string
    name: string
    photo: string
    nationality: string
    bio: string
    achievements: string[]
  }
}

export function SurferCard({ surfer }: SurferCardProps) {
  return (
    <Card className="overflow-hidden">
      <Link href={`/surfer-wall/${surfer.slug}`}>
        <div className="relative aspect-[3/4] overflow-hidden">
          <Image
            src={surfer.photo}
            alt={surfer.name}
            fill
            className="object-cover"
          />
          <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
          <div className="absolute bottom-4 left-4 right-4 text-white">
            <h3 className="font-display text-xl font-bold">{surfer.name}</h3>
            <p className="text-sm opacity-80">{surfer.nationality}</p>
          </div>
        </div>
      </Link>

      <CardContent className="p-4">
        <p className="text-sm text-muted-foreground line-clamp-2 mb-3">
          {surfer.bio}
        </p>
        <div className="flex flex-wrap gap-1">
          {surfer.achievements.slice(0, 3).map((achievement, i) => (
            <Badge key={i} variant="secondary" className="text-xs">
              {achievement}
            </Badge>
          ))}
        </div>
      </CardContent>
    </Card>
  )
}
```

### 8.4 Página de Surfista Individual

**`src/app/[locale]/(praia-do-norte)/surfer-wall/[slug]/page.tsx`**:

```typescript
import { getSurfer } from '@/lib/api/aimeos'
import { SurfboardGallery } from '@/components/content/SurfboardGallery'

export default async function SurferPage({
  params: { locale, slug }
}: {
  params: { locale: string; slug: string }
}) {
  const surfer = await getSurfer(slug, locale)

  return (
    <main className="container py-8">
      <div className="grid md:grid-cols-2 gap-8">
        <div>
          <Image src={surfer.photo} alt={surfer.name} />
        </div>

        <div>
          <h1 className="font-display text-4xl font-bold">{surfer.name}</h1>
          <p className="text-xl text-muted-foreground">{surfer.nationality}</p>

          <div className="mt-6" dangerouslySetInnerHTML={{ __html: surfer.bio }} />

          <h2 className="text-2xl font-bold mt-8 mb-4">Conquistas</h2>
          <ul className="list-disc pl-6">
            {surfer.achievements.map((a, i) => (
              <li key={i}>{a}</li>
            ))}
          </ul>
        </div>
      </div>

      <section className="mt-12">
        <h2 className="text-2xl font-bold mb-6">Pranchas</h2>
        <SurfboardGallery surfboards={surfer.surfboards} />
      </section>
    </main>
  )
}
```

### 8.5 Widget de Webcams

**`src/components/content/WebcamEmbed.tsx`**:

```typescript
interface WebcamEmbedProps {
  url: string
  title: string
}

export function WebcamEmbed({ url, title }: WebcamEmbedProps) {
  return (
    <div className="aspect-video rounded-lg overflow-hidden">
      <iframe
        src={url}
        title={title}
        className="w-full h-full"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowFullScreen
      />
    </div>
  )
}
```

---

## Entregáveis

- [ ] Listagem de notícias com paginação
- [ ] Página de artigo individual
- [ ] Surfer Wall com perfis
- [ ] Página de surfista individual
- [ ] Galeria de pranchas
- [ ] Calendário de eventos
- [ ] Widget de previsão de ondas
- [ ] Embed de webcams

---

## Critérios de Conclusão

1. Notícias carregam do Aimeos CMS
2. Surfer Wall mostra todos os surfistas
3. Perfil de surfista inclui pranchas
4. Eventos mostram datas futuras
5. Webcams carregam sem erros de CORS
6. Previsão de ondas atualiza automaticamente
