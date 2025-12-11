# Fase 3: Conteúdo Dinâmico

**Status**: 🔄 Em Migração para Blade
**Dependências**: Fase 2
**Bloco**: 2 - Institucional

---

## Objetivos

- Implementar secção de notícias em Blade
- Criar Surfer Wall com perfis
- Integrar eventos
- Página de previsões marítimas

---

## Tarefas

### 3.1 Página de Notícias (Listagem)

**Controller**: `app/Http/Controllers/NoticiaController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoticiaController extends Controller
{
    public function index(Request $request): View
    {
        $locale = app()->getLocale();
        $entity = $request->query('entity');

        $query = Noticia::where('published', true)
            ->orderByDesc('published_at');

        if ($entity) {
            $query->where('entity', $entity);
        }

        $noticias = $query->paginate(9);

        return view('pages.noticias.index', compact('noticias', 'locale', 'entity'));
    }

    public function show(string $locale, string $slug): View
    {
        $noticia = Noticia::where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        return view('pages.noticias.show', compact('noticia', 'locale'));
    }
}
```

**View Listagem**: `resources/views/pages/noticias/index.blade.php`

```blade
<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        <h1 class="font-display text-4xl font-bold mb-8">@lang('news.title')</h1>

        {{-- Filter by Entity --}}
        <livewire:news-filter :current-entity="$entity" />

        {{-- News Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
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

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $noticias->links() }}
        </div>
    </div>
</x-layouts.app>
```

**View Detalhe**: `resources/views/pages/noticias/show.blade.php`

```blade
<x-layouts.app>
    <article class="container mx-auto px-4 py-8">
        {{-- Breadcrumbs --}}
        <nav class="text-sm text-gray-500 mb-8">
            <a href="{{ route('home', ['locale' => $locale]) }}" class="hover:text-ocean-500">
                @lang('navigation.home')
            </a>
            <span class="mx-2">/</span>
            <a href="{{ route('noticias.index', ['locale' => $locale]) }}" class="hover:text-ocean-500">
                @lang('navigation.news')
            </a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ Str::limit($noticia->getTranslation('title', $locale), 50) }}</span>
        </nav>

        {{-- Cover Image --}}
        <img
            src="{{ Storage::url($noticia->cover_image) }}"
            alt="{{ $noticia->getTranslation('title', $locale) }}"
            class="w-full h-[400px] object-cover rounded-lg mb-8"
        >

        {{-- Header --}}
        <header class="max-w-3xl mx-auto mb-8">
            <x-ui.badge :variant="$noticia->entity" class="mb-4">
                {{ ucfirst(str_replace('-', ' ', $noticia->entity)) }}
            </x-ui.badge>

            <h1 class="font-display text-4xl font-bold mb-4">
                {{ $noticia->getTranslation('title', $locale) }}
            </h1>

            <div class="flex items-center text-gray-500 text-sm">
                <span>{{ $noticia->published_at->format('d M Y') }}</span>
                @if($noticia->author)
                    <span class="mx-2">•</span>
                    <span>{{ $noticia->author }}</span>
                @endif
            </div>
        </header>

        {{-- Content --}}
        <div class="max-w-3xl mx-auto prose prose-lg">
            {!! $noticia->getTranslation('content', $locale) !!}
        </div>

        {{-- Tags --}}
        @if($noticia->tags && count($noticia->tags) > 0)
            <div class="max-w-3xl mx-auto mt-8 pt-8 border-t">
                <div class="flex flex-wrap gap-2">
                    @foreach($noticia->tags as $tag)
                        <x-ui.badge variant="secondary">{{ $tag }}</x-ui.badge>
                    @endforeach
                </div>
            </div>
        @endif
    </article>
</x-layouts.app>
```

---

### 3.2 Livewire: News Filter

**Componente**: `app/Livewire/NewsFilter.php`

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class NewsFilter extends Component
{
    public ?string $currentEntity = null;

    public function mount(?string $currentEntity = null)
    {
        $this->currentEntity = $currentEntity;
    }

    public function setEntity(?string $entity)
    {
        $locale = app()->getLocale();
        $url = route('noticias.index', ['locale' => $locale]);

        if ($entity) {
            $url .= '?entity=' . $entity;
        }

        return redirect($url);
    }

    public function render()
    {
        return view('livewire.news-filter');
    }
}
```

**View**: `resources/views/livewire/news-filter.blade.php`

```blade
<div class="flex flex-wrap gap-2">
    <button
        wire:click="setEntity(null)"
        class="rounded-full px-4 py-2 text-sm font-medium transition
            {{ !$currentEntity ? 'bg-ocean-500 text-white' : 'bg-gray-100 hover:bg-gray-200' }}"
    >
        @lang('news.filters.all')
    </button>
    <button
        wire:click="setEntity('praia-norte')"
        class="rounded-full px-4 py-2 text-sm font-medium transition
            {{ $currentEntity === 'praia-norte' ? 'bg-ocean-500 text-white' : 'bg-gray-100 hover:bg-gray-200' }}"
    >
        Praia do Norte
    </button>
    <button
        wire:click="setEntity('carsurf')"
        class="rounded-full px-4 py-2 text-sm font-medium transition
            {{ $currentEntity === 'carsurf' ? 'bg-performance-500 text-white' : 'bg-gray-100 hover:bg-gray-200' }}"
    >
        Carsurf
    </button>
    <button
        wire:click="setEntity('nazare-qualifica')"
        class="rounded-full px-4 py-2 text-sm font-medium transition
            {{ $currentEntity === 'nazare-qualifica' ? 'bg-institutional-500 text-white' : 'bg-gray-100 hover:bg-gray-200' }}"
    >
        Nazaré Qualifica
    </button>
</div>
```

---

### 3.3 Surfer Wall

**Controller**: `app/Http/Controllers/SurferController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Surfer;
use Illuminate\View\View;

class SurferController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        $surfers = Surfer::orderByDesc('featured')
            ->orderBy('name')
            ->get();

        return view('pages.surfer-wall.index', compact('surfers', 'locale'));
    }

    public function show(string $locale, string $slug): View
    {
        $surfer = Surfer::where('slug', $slug)
            ->with('surfboards')
            ->firstOrFail();

        return view('pages.surfer-wall.show', compact('surfer', 'locale'));
    }
}
```

**View Listagem**: `resources/views/pages/surfer-wall/index.blade.php`

```blade
<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        <h1 class="font-display text-4xl font-bold mb-4">Surfer Wall</h1>
        <p class="text-gray-600 mb-8 max-w-2xl">
            @lang('surfers.subtitle')
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($surfers as $surfer)
                <x-ui.card :href="route('surfers.show', ['locale' => $locale, 'slug' => $surfer->slug])">
                    <div class="relative aspect-[3/4] overflow-hidden">
                        <img
                            src="{{ Storage::url($surfer->photo) }}"
                            alt="{{ $surfer->name }}"
                            class="w-full h-full object-cover"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <h3 class="font-display text-xl font-bold">{{ $surfer->name }}</h3>
                            <p class="text-sm opacity-80">{{ $surfer->nationality }}</p>
                        </div>
                        @if($surfer->featured)
                            <div class="absolute top-4 right-4">
                                <x-ui.badge variant="default">Featured</x-ui.badge>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <p class="text-sm text-gray-600 line-clamp-2">
                            {{ Str::limit(strip_tags($surfer->getTranslation('bio', $locale)), 120) }}
                        </p>
                        @if($surfer->achievements && count($surfer->achievements) > 0)
                            <div class="flex flex-wrap gap-1 mt-3">
                                @foreach(array_slice($surfer->achievements, 0, 3) as $achievement)
                                    <x-ui.badge variant="secondary">{{ $achievement }}</x-ui.badge>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    </div>
</x-layouts.app>
```

**View Detalhe**: `resources/views/pages/surfer-wall/show.blade.php`

```blade
<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        {{-- Breadcrumbs --}}
        <nav class="text-sm text-gray-500 mb-8">
            <a href="{{ route('home', ['locale' => $locale]) }}" class="hover:text-ocean-500">
                @lang('navigation.home')
            </a>
            <span class="mx-2">/</span>
            <a href="{{ route('surfers.index', ['locale' => $locale]) }}" class="hover:text-ocean-500">
                Surfer Wall
            </a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $surfer->name }}</span>
        </nav>

        <div class="grid md:grid-cols-2 gap-8">
            {{-- Photo --}}
            <div>
                <img
                    src="{{ Storage::url($surfer->photo) }}"
                    alt="{{ $surfer->name }}"
                    class="w-full rounded-lg"
                >
            </div>

            {{-- Info --}}
            <div>
                <h1 class="font-display text-4xl font-bold mb-2">{{ $surfer->name }}</h1>
                <p class="text-xl text-gray-500 mb-6">{{ $surfer->nationality }}</p>

                <div class="prose">
                    {!! $surfer->getTranslation('bio', $locale) !!}
                </div>

                @if($surfer->achievements && count($surfer->achievements) > 0)
                    <h2 class="font-display text-2xl font-bold mt-8 mb-4">@lang('surfers.achievements')</h2>
                    <ul class="list-disc pl-6 space-y-1">
                        @foreach($surfer->achievements as $achievement)
                            <li>{{ $achievement }}</li>
                        @endforeach
                    </ul>
                @endif

                @if($surfer->social_media)
                    <div class="flex gap-4 mt-8">
                        @if(isset($surfer->social_media['instagram']))
                            <a href="{{ $surfer->social_media['instagram'] }}" target="_blank" class="text-gray-500 hover:text-ocean-500">
                                Instagram
                            </a>
                        @endif
                        @if(isset($surfer->social_media['facebook']))
                            <a href="{{ $surfer->social_media['facebook'] }}" target="_blank" class="text-gray-500 hover:text-ocean-500">
                                Facebook
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Surfboards --}}
        @if($surfer->surfboards->count() > 0)
            <section class="mt-12">
                <h2 class="font-display text-2xl font-bold mb-6">@lang('surfers.surfboards')</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($surfer->surfboards as $surfboard)
                        <div class="bg-gray-50 rounded-lg p-4">
                            @if($surfboard->image)
                                <img
                                    src="{{ Storage::url($surfboard->image) }}"
                                    alt="{{ $surfboard->brand }} {{ $surfboard->model }}"
                                    class="w-full h-32 object-contain mb-4"
                                >
                            @endif
                            <h3 class="font-semibold">{{ $surfboard->brand }}</h3>
                            <p class="text-sm text-gray-500">{{ $surfboard->model }}</p>
                            <p class="text-sm text-gray-500">{{ $surfboard->length }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-layouts.app>
```

---

### 3.4 Página de Previsões

**Controller**: `app/Http/Controllers/ForecastController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Services\ForecastService;
use Illuminate\View\View;

class ForecastController extends Controller
{
    public function __construct(
        private ForecastService $forecastService
    ) {}

    public function index(): View
    {
        $locale = app()->getLocale();
        $forecast = $this->forecastService->getMarineForecast();
        $weather = $this->forecastService->getWeatherForecast();

        return view('pages.previsoes', compact('forecast', 'weather', 'locale'));
    }
}
```

**Service**: `app/Services/ForecastService.php`

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ForecastService
{
    private const LATITUDE = 39.6024;
    private const LONGITUDE = -9.0712;

    public function getMarineForecast(): array
    {
        return Cache::remember('marine_forecast', 1800, function () {
            $response = Http::get('https://marine-api.open-meteo.com/v1/marine', [
                'latitude' => self::LATITUDE,
                'longitude' => self::LONGITUDE,
                'current' => 'wave_height,wave_direction,wave_period,swell_wave_height,swell_wave_direction,swell_wave_period',
                'hourly' => 'wave_height,wave_direction,wave_period',
                'daily' => 'wave_height_max,wave_period_max',
                'timezone' => 'Europe/Lisbon',
            ]);

            return $response->json();
        });
    }

    public function getWeatherForecast(): array
    {
        return Cache::remember('weather_forecast', 1800, function () {
            $response = Http::get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => self::LATITUDE,
                'longitude' => self::LONGITUDE,
                'current' => 'temperature_2m,wind_speed_10m,wind_direction_10m,wind_gusts_10m',
                'hourly' => 'temperature_2m,wind_speed_10m,wind_direction_10m',
                'daily' => 'temperature_2m_max,temperature_2m_min,wind_speed_10m_max',
                'timezone' => 'Europe/Lisbon',
            ]);

            return $response->json();
        });
    }
}
```

---

## Entregáveis

- [x] Listagem de notícias com filtros Livewire
- [x] Página de artigo individual
- [x] Surfer Wall com grid de perfis
- [x] Página de surfista individual com pranchas
- [x] Calendário de eventos (listagem)
- [x] Página de evento individual
- [x] Previsões marítimas (Open-Meteo API)
- [ ] **A MIGRAR**: Views para Blade (em curso)

---

## Critérios de Conclusão

1. Notícias carregam da base de dados MySQL
2. Filtros por entidade funcionam via Livewire
3. Surfer Wall mostra todos os surfistas
4. Perfil de surfista inclui pranchas
5. Previsões actualizam automaticamente (cache 30min)

---

## Próxima Fase

→ [Fase 4: SEO e Performance](./FASE_04_SEO.md)

---

*Actualizado: 11 Dezembro 2025 - Arquitectura monolítica*
