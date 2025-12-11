# Fase 4: SEO e Performance

**Status**: ⏳ Pendente
**Dependências**: Fase 3
**Bloco**: 3 - Qualidade

---

## Objetivos

- Otimizar SEO com Laravel
- Melhorar performance
- Implementar structured data (JSON-LD)
- Criar sitemap e robots.txt

---

## Tarefas

### 4.1 SEO Service Provider

**`app/Providers/SeoServiceProvider.php`**:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class SeoServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $defaults = [
                'seo_title' => config('app.name'),
                'seo_description' => 'Praia do Norte - Onde as ondas gigantes ganham vida',
                'og_image' => asset('images/og-default.jpg'),
            ];

            $view->with('seo', array_merge($defaults, $view->getData()['seo'] ?? []));
        });
    }
}
```

---

### 4.2 Layout com Meta Tags Dinâmicas

**`resources/views/layouts/app.blade.php`** (actualizado):

```blade
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <title>{{ $seo['seo_title'] ?? 'Praia do Norte' }}</title>
    <meta name="description" content="{{ $seo['seo_description'] ?? '' }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $seo['seo_title'] ?? 'Praia do Norte' }}">
    <meta property="og:description" content="{{ $seo['seo_description'] ?? '' }}">
    <meta property="og:image" content="{{ $seo['og_image'] ?? asset('images/og-default.jpg') }}">
    <meta property="og:type" content="{{ $seo['og_type'] ?? 'website' }}">
    <meta property="og:locale" content="{{ app()->getLocale() == 'pt' ? 'pt_PT' : 'en_GB' }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['seo_title'] ?? 'Praia do Norte' }}">
    <meta name="twitter:description" content="{{ $seo['seo_description'] ?? '' }}">
    <meta name="twitter:image" content="{{ $seo['og_image'] ?? asset('images/og-default.jpg') }}">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Alternate Languages --}}
    <link rel="alternate" hreflang="pt" href="{{ str_replace('/en/', '/pt/', url()->current()) }}">
    <link rel="alternate" hreflang="en" href="{{ str_replace('/pt/', '/en/', url()->current()) }}">

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- JSON-LD Structured Data --}}
    @stack('json-ld')
</head>
<body class="min-h-screen bg-background font-sans antialiased">
    <x-layout.header />

    <main>
        {{ $slot }}
    </main>

    <x-layout.footer />

    @livewireScripts
</body>
</html>
```

---

### 4.3 Structured Data (JSON-LD)

**Componente Organization**: `resources/views/components/seo/organization-jsonld.blade.php`

```blade
@push('json-ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Praia do Norte",
    "url": "https://praiadonortenazare.pt",
    "logo": "https://praiadonortenazare.pt/logo.png",
    "contactPoint": {
        "@type": "ContactPoint",
        "email": "info@praiadonortenazare.pt",
        "contactType": "customer service"
    },
    "sameAs": [
        "https://facebook.com/praiadonorte",
        "https://instagram.com/praiadonorte"
    ]
}
</script>
@endpush
```

**Componente Article**: `resources/views/components/seo/article-jsonld.blade.php`

```blade
@props(['article'])

@push('json-ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "headline": "{{ $article->getTranslation('title', app()->getLocale()) }}",
    "image": "{{ Storage::url($article->cover_image) }}",
    "datePublished": "{{ $article->published_at->toIso8601String() }}",
    "dateModified": "{{ $article->updated_at->toIso8601String() }}",
    "author": {
        "@type": "Person",
        "name": "{{ $article->author ?? 'Praia do Norte' }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "Praia do Norte",
        "logo": {
            "@type": "ImageObject",
            "url": "https://praiadonortenazare.pt/logo.png"
        }
    }
}
</script>
@endpush
```

---

### 4.4 Sitemap Dinâmico

**Route**: `routes/web.php`

```php
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
```

**Controller**: `app/Http/Controllers/SitemapController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Evento;
use App\Models\Surfer;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $noticias = Noticia::where('published', true)->get();
        $eventos = Evento::all();
        $surfers = Surfer::all();

        $content = view('sitemap', compact('noticias', 'eventos', 'surfers'))->render();

        return response($content)
            ->header('Content-Type', 'application/xml');
    }
}
```

**View**: `resources/views/sitemap.blade.php`

```blade
{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Static Pages --}}
    @foreach(['pt', 'en'] as $locale)
    <url>
        <loc>{{ url($locale) }}</loc>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url($locale . '/noticias') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url($locale . '/surfer-wall') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url($locale . '/eventos') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ url($locale . '/previsoes') }}</loc>
        <changefreq>hourly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

    {{-- News Articles --}}
    @foreach($noticias as $noticia)
    @foreach(['pt', 'en'] as $locale)
    <url>
        <loc>{{ url($locale . '/noticias/' . $noticia->slug) }}</loc>
        <lastmod>{{ $noticia->updated_at->toIso8601String() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    @endforeach

    {{-- Surfers --}}
    @foreach($surfers as $surfer)
    @foreach(['pt', 'en'] as $locale)
    <url>
        <loc>{{ url($locale . '/surfer-wall/' . $surfer->slug) }}</loc>
        <lastmod>{{ $surfer->updated_at->toIso8601String() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
    @endforeach
</urlset>
```

---

### 4.5 Robots.txt

**`public/robots.txt`**:

```
User-agent: *
Allow: /

Disallow: /admin/
Disallow: /api/

Sitemap: https://praiadonortenazare.pt/sitemap.xml
```

---

### 4.6 Performance Optimizations

**Image Optimization com Intervention Image**:

```bash
composer require intervention/image
```

**Service Provider** (`config/app.php`):

```php
'providers' => [
    Intervention\Image\ImageServiceProvider::class,
],
```

**Middleware para Cache Headers**: `app/Http/Middleware/CacheHeaders.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Cache static assets for 1 year
        if ($request->is('build/*') || $request->is('storage/*')) {
            $response->header('Cache-Control', 'public, max-age=31536000');
        }

        return $response;
    }
}
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
- [ ] Structured data (JSON-LD) para artigos
- [ ] Sitemap.xml automático
- [ ] Robots.txt configurado
- [ ] Lighthouse score > 90
- [ ] Core Web Vitals otimizados

---

## Critérios de Conclusão

1. Todas as páginas têm meta tags únicos
2. Partilha em redes sociais mostra preview correcto
3. Google Search Console mostra structured data válido
4. Sitemap indexa todas as páginas dinâmicas
5. Lighthouse Performance > 90 em mobile

---

## Próxima Fase

→ [Fase 5: Segurança](./FASE_05_SEGURANCA.md)

---

*Actualizado: 11 Dezembro 2025 - Arquitectura monolítica*
