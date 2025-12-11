# Fase 2: Homepage e Páginas Institucionais

**Status**: ✅ Completo
**Dependências**: Fase 1
**Bloco**: 2 - Institucional

> **Nota**: Esta fase não inclui a secção "Produtos em Destaque" da homepage, que será adicionada na Fase 7 (Catálogo).

---

## Objetivos

- Criar homepage impactante em Blade
- Implementar páginas institucionais
- Configurar i18n nas rotas

---

## Tarefas

### 2.1 Homepage

**Estrutura da Homepage:**

```
┌─────────────────────────────────────────────────────────┐
│                    HERO SECTION                         │
│  (YouTube video ondas gigantes + CTA "Explorar")       │
├─────────────────────────────────────────────────────────┤
│                   ÚLTIMAS NOTÍCIAS                      │
│  [Card] [Card] [Card]                                  │
├─────────────────────────────────────────────────────────┤
│                    SURFER WALL                          │
│  (Grid de surfistas em destaque)                       │
├─────────────────────────────────────────────────────────┤
│               PRÓXIMOS EVENTOS                          │
│  [Card] [Card]                                         │
├─────────────────────────────────────────────────────────┤
│              ENTIDADES (3 colunas)                      │
│  [Praia do Norte] [Carsurf] [Nazaré Qualifica]        │
├─────────────────────────────────────────────────────────┤
│                      FOOTER                             │
└─────────────────────────────────────────────────────────┘
```

---

### 2.2 Controller da Homepage

**`app/Http/Controllers/HomeController.php`**:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Evento;
use App\Models\Surfer;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        $noticias = Noticia::where('published', true)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        $surfers = Surfer::where('featured', true)
            ->take(4)
            ->get();

        $eventos = Evento::where('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(2)
            ->get();

        return view('pages.home', compact('noticias', 'surfers', 'eventos', 'locale'));
    }
}
```

---

### 2.3 View da Homepage

**`resources/views/pages/home.blade.php`**:

```blade
<x-layouts.app>
    {{-- Hero Section --}}
    <section class="relative h-[70vh] min-h-[500px] overflow-hidden">
        <div class="absolute inset-0">
            <iframe
                src="https://www.youtube.com/embed/VIDEO_ID?autoplay=1&mute=1&loop=1&playlist=VIDEO_ID&controls=0"
                class="absolute inset-0 w-full h-full object-cover scale-125"
                allow="autoplay"
            ></iframe>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/60"></div>
        </div>

        <div class="relative container mx-auto px-4 h-full flex flex-col justify-end pb-16">
            <h1 class="font-display text-5xl md:text-7xl font-bold text-white mb-4">
                @lang('home.hero.title')
            </h1>
            <p class="text-xl text-white/90 mb-8 max-w-2xl">
                @lang('home.hero.subtitle')
            </p>
            <div class="flex gap-4">
                <x-ui.button href="{{ route('noticias.index', ['locale' => $locale]) }}">
                    @lang('home.hero.cta')
                </x-ui.button>
            </div>
        </div>
    </section>

    {{-- Latest News --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="font-display text-3xl font-bold">@lang('home.latestNews')</h2>
                <a href="{{ route('noticias.index', ['locale' => $locale]) }}"
                   class="text-ocean-500 hover:text-ocean-600 font-medium">
                    @lang('common.viewAll') →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($noticias as $noticia)
                    <x-ui.card :href="route('noticias.show', ['locale' => $locale, 'slug' => $noticia->slug])">
                        <img
                            src="{{ Storage::url($noticia->cover_image) }}"
                            alt="{{ $noticia->getTranslation('title', $locale) }}"
                            class="w-full h-48 object-cover"
                        >
                        <div class="p-4">
                            <x-ui.badge :variant="$noticia->entity">
                                {{ ucfirst(str_replace('-', ' ', $noticia->entity)) }}
                            </x-ui.badge>
                            <h3 class="font-semibold mt-2 line-clamp-2">
                                {{ $noticia->getTranslation('title', $locale) }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $noticia->published_at->format('d M Y') }}
                            </p>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured Surfers --}}
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="font-display text-3xl font-bold">@lang('home.surferWall')</h2>
                <a href="{{ route('surfers.index', ['locale' => $locale]) }}"
                   class="text-ocean-500 hover:text-ocean-600 font-medium">
                    @lang('common.viewAll') →
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($surfers as $surfer)
                    <x-ui.card :href="route('surfers.show', ['locale' => $locale, 'slug' => $surfer->slug])">
                        <div class="relative aspect-[3/4]">
                            <img
                                src="{{ Storage::url($surfer->photo) }}"
                                alt="{{ $surfer->name }}"
                                class="w-full h-full object-cover"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <h3 class="font-display font-bold">{{ $surfer->name }}</h3>
                                <p class="text-sm opacity-80">{{ $surfer->nationality }}</p>
                            </div>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Upcoming Events --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="font-display text-3xl font-bold">@lang('home.upcomingEvents')</h2>
                <a href="{{ route('eventos.index', ['locale' => $locale]) }}"
                   class="text-ocean-500 hover:text-ocean-600 font-medium">
                    @lang('common.viewAll') →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($eventos as $evento)
                    <x-ui.card :href="route('eventos.show', ['locale' => $locale, 'slug' => $evento->slug])">
                        <div class="md:flex">
                            <img
                                src="{{ Storage::url($evento->image) }}"
                                alt="{{ $evento->getTranslation('title', $locale) }}"
                                class="w-full md:w-48 h-48 object-cover"
                            >
                            <div class="p-4">
                                <x-ui.badge :variant="$evento->entity">
                                    {{ ucfirst(str_replace('-', ' ', $evento->entity)) }}
                                </x-ui.badge>
                                <h3 class="font-semibold text-lg mt-2">
                                    {{ $evento->getTranslation('title', $locale) }}
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $evento->start_date->format('d M Y') }}
                                    @if($evento->location)
                                        • {{ $evento->location }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Entity Cards --}}
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Praia do Norte --}}
                <div class="text-center p-8 rounded-lg bg-ocean-50 border border-ocean-100">
                    <h3 class="font-display text-2xl font-bold text-ocean-900 mb-4">Praia do Norte</h3>
                    <p class="text-gray-600 mb-6">@lang('entities.praiaDoNorte.description')</p>
                    <x-ui.button variant="outline" :href="route('sobre', ['locale' => $locale])">
                        @lang('common.learnMore')
                    </x-ui.button>
                </div>

                {{-- Carsurf --}}
                <div class="text-center p-8 rounded-lg bg-performance-50 border border-performance-100">
                    <h3 class="font-display text-2xl font-bold text-performance-900 mb-4">Carsurf</h3>
                    <p class="text-gray-600 mb-6">@lang('entities.carsurf.description')</p>
                    <x-ui.button variant="outline" :href="route('carsurf.index', ['locale' => $locale])">
                        @lang('common.learnMore')
                    </x-ui.button>
                </div>

                {{-- Nazaré Qualifica --}}
                <div class="text-center p-8 rounded-lg bg-institutional-50 border border-institutional-100">
                    <h3 class="font-display text-2xl font-bold text-institutional-900 mb-4">Nazaré Qualifica</h3>
                    <p class="text-gray-600 mb-6">@lang('entities.nazareQualifica.description')</p>
                    <x-ui.button variant="outline" :href="route('nq.sobre', ['locale' => $locale])">
                        @lang('common.learnMore')
                    </x-ui.button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
```

---

### 2.4 Rotas

**`routes/web.php`**:

```php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\SurferController;
use App\Http\Controllers\ForecastController;
use App\Http\Controllers\CarsurfController;
use App\Http\Controllers\NazareQualificaController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;

// Redirect root to default locale
Route::get('/', fn() => redirect('/pt'));

// Localized routes
Route::prefix('{locale}')
    ->where(['locale' => 'pt|en'])
    ->middleware(['web', 'localization'])
    ->group(function () {

        // Homepage
        Route::get('/', [HomeController::class, 'index'])->name('home');

        // Notícias
        Route::get('/noticias', [NoticiaController::class, 'index'])->name('noticias.index');
        Route::get('/noticias/{slug}', [NoticiaController::class, 'show'])->name('noticias.show');

        // Eventos
        Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
        Route::get('/eventos/{slug}', [EventoController::class, 'show'])->name('eventos.show');

        // Surfer Wall
        Route::get('/surfer-wall', [SurferController::class, 'index'])->name('surfers.index');
        Route::get('/surfer-wall/{slug}', [SurferController::class, 'show'])->name('surfers.show');

        // Previsões
        Route::get('/previsoes', [ForecastController::class, 'index'])->name('forecast');

        // Carsurf
        Route::prefix('carsurf')->name('carsurf.')->group(function () {
            Route::get('/', [CarsurfController::class, 'index'])->name('index');
            Route::get('/sobre', [CarsurfController::class, 'sobre'])->name('sobre');
            Route::get('/programas', [CarsurfController::class, 'programas'])->name('programas');
        });

        // Nazaré Qualifica
        Route::prefix('nazare-qualifica')->name('nq.')->group(function () {
            Route::get('/sobre', [NazareQualificaController::class, 'sobre'])->name('sobre');
            Route::get('/servicos', [NazareQualificaController::class, 'servicos'])->name('servicos');
        });

        // Outras páginas
        Route::get('/sobre', [PageController::class, 'sobre'])->name('sobre');
        Route::get('/contacto', [ContactController::class, 'index'])->name('contacto');
        Route::post('/contacto', [ContactController::class, 'send'])->name('contacto.send');
    });
```

---

### 2.5 Traduções Homepage

**`lang/pt/home.php`**:

```php
<?php

return [
    'hero' => [
        'title' => 'Praia do Norte',
        'subtitle' => 'Onde as ondas gigantes ganham vida',
        'cta' => 'Explorar',
    ],
    'latestNews' => 'Últimas Notícias',
    'surferWall' => 'Surfer Wall',
    'upcomingEvents' => 'Próximos Eventos',
];
```

**`lang/en/home.php`**:

```php
<?php

return [
    'hero' => [
        'title' => 'Praia do Norte',
        'subtitle' => 'Where giant waves come to life',
        'cta' => 'Explore',
    ],
    'latestNews' => 'Latest News',
    'surferWall' => 'Surfer Wall',
    'upcomingEvents' => 'Upcoming Events',
];
```

---

## Entregáveis ✅

- [x] Homepage completa com todas as secções
- [x] Hero section com vídeo YouTube
- [x] Secção últimas notícias (3 cards)
- [x] Secção Surfer Wall (4 surfistas)
- [x] Secção próximos eventos (2 cards)
- [x] Cards das 3 entidades
- [x] i18n funcionando (PT/EN)
- [x] Rotas localizadas

---

## Critérios de Conclusão ✅

1. Homepage carrega em menos de 3s
2. Dados vêm da base de dados (Eloquent)
3. Troca de idioma funciona correctamente
4. Design responsivo em todos os breakpoints

---

## Próxima Fase

→ [Fase 3: Conteúdo Dinâmico](./FASE_03_CONTEUDO.md)

---

*Actualizado: 11 Dezembro 2025 - Arquitectura monolítica*
