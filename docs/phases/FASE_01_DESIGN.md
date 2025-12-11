# Fase 1: Design System e Componentes Base

**Status**: ✅ Completo
**Dependências**: Fase 0
**Bloco**: 1 - Fundações

---

## Objetivos

- Implementar design system completo em Blade
- Criar componentes reutilizáveis
- Configurar tipografia e cores
- Criar layout master

---

## Tarefas

### 1.1 Layout Master

**`resources/views/layouts/app.blade.php`**:

```blade
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Praia do Norte' }}</title>

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

### 1.2 Componentes de Layout

**Header**: `resources/views/components/layout/header.blade.php`

```blade
<header class="sticky top-0 z-50 w-full border-b bg-white/95 backdrop-blur supports-[backdrop-filter]:bg-white/60">
    <div class="container mx-auto flex h-16 items-center justify-between px-4">
        <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="font-display text-xl font-bold text-ocean-900">
            Praia do Norte
        </a>

        <nav class="hidden md:flex items-center gap-6">
            <a href="{{ route('noticias.index', ['locale' => app()->getLocale()]) }}"
               class="text-sm font-medium text-gray-600 hover:text-ocean-500">
                @lang('navigation.news')
            </a>
            <a href="{{ route('eventos.index', ['locale' => app()->getLocale()]) }}"
               class="text-sm font-medium text-gray-600 hover:text-ocean-500">
                @lang('navigation.events')
            </a>
            <a href="{{ route('surfers.index', ['locale' => app()->getLocale()]) }}"
               class="text-sm font-medium text-gray-600 hover:text-ocean-500">
                @lang('navigation.surferWall')
            </a>
            <a href="{{ route('forecast', ['locale' => app()->getLocale()]) }}"
               class="text-sm font-medium text-gray-600 hover:text-ocean-500">
                @lang('navigation.forecast')
            </a>
        </nav>

        <div class="flex items-center gap-4">
            <livewire:language-switcher />
        </div>
    </div>
</header>
```

**Footer**: `resources/views/components/layout/footer.blade.php`

```blade
<footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Praia do Norte -->
            <div>
                <h3 class="font-display text-lg font-bold text-ocean-500 mb-4">Praia do Norte</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm">@lang('navigation.news')</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm">@lang('navigation.surferWall')</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm">@lang('navigation.forecast')</a></li>
                </ul>
            </div>

            <!-- Carsurf -->
            <div>
                <h3 class="font-display text-lg font-bold text-performance-500 mb-4">Carsurf</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm">@lang('navigation.about')</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm">@lang('navigation.programs')</a></li>
                </ul>
            </div>

            <!-- Nazaré Qualifica -->
            <div>
                <h3 class="font-display text-lg font-bold text-institutional-500 mb-4">Nazaré Qualifica</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm">@lang('navigation.about')</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm">@lang('navigation.services')</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm">@lang('navigation.contact')</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Nazaré Qualifica. @lang('common.allRightsReserved')
        </div>
    </div>
</footer>
```

---

### 1.3 Componentes UI Base

**Button**: `resources/views/components/ui/button.blade.php`

```blade
@props([
    'variant' => 'default',
    'size' => 'default',
    'href' => null,
])

@php
$baseClasses = 'inline-flex items-center justify-center rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50';

$variants = [
    'default' => 'bg-ocean-500 text-white hover:bg-ocean-600',
    'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200',
    'outline' => 'border border-gray-300 bg-transparent hover:bg-gray-100',
    'ghost' => 'hover:bg-gray-100',
    'destructive' => 'bg-red-500 text-white hover:bg-red-600',
];

$sizes = [
    'default' => 'h-10 px-4 py-2',
    'sm' => 'h-9 px-3 text-sm',
    'lg' => 'h-11 px-8',
    'icon' => 'h-10 w-10',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
```

**Card**: `resources/views/components/ui/card.blade.php`

```blade
@props([
    'href' => null,
])

@php
$classes = 'rounded-lg border bg-white text-gray-900 shadow-sm';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes . ' block hover:shadow-md transition-shadow']) }}>
        {{ $slot }}
    </a>
@else
    <div {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </div>
@endif
```

**Badge**: `resources/views/components/ui/badge.blade.php`

```blade
@props([
    'variant' => 'default',
])

@php
$variants = [
    'default' => 'bg-ocean-100 text-ocean-800',
    'secondary' => 'bg-gray-100 text-gray-800',
    'outline' => 'border border-gray-300 text-gray-700',
    'praia-norte' => 'bg-ocean-100 text-ocean-800',
    'carsurf' => 'bg-performance-100 text-performance-800',
    'nazare-qualifica' => 'bg-institutional-100 text-institutional-800',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ' . $variants[$variant]]) }}>
    {{ $slot }}
</span>
```

**Input**: `resources/views/components/ui/input.blade.php`

```blade
@props([
    'type' => 'text',
    'error' => null,
])

<input
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'flex h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm ring-offset-white file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-gray-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ocean-500 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50'
        . ($error ? ' border-red-500' : '')
    ]) }}
>

@if($error)
    <p class="mt-1 text-sm text-red-500">{{ $error }}</p>
@endif
```

---

### 1.4 Livewire: Language Switcher

**Componente**: `app/Livewire/LanguageSwitcher.php`

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;

class LanguageSwitcher extends Component
{
    public string $currentLocale;

    public function mount()
    {
        $this->currentLocale = App::getLocale();
    }

    public function switchTo(string $locale)
    {
        $path = request()->path();
        $newPath = preg_replace('/^(pt|en)/', $locale, $path);

        return redirect($newPath);
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
```

**View**: `resources/views/livewire/language-switcher.blade.php`

```blade
<div class="flex items-center gap-2">
    <button
        wire:click="switchTo('pt')"
        class="{{ $currentLocale === 'pt' ? 'font-bold text-ocean-500' : 'text-gray-500 hover:text-ocean-500' }}"
    >
        PT
    </button>
    <span class="text-gray-300">|</span>
    <button
        wire:click="switchTo('en')"
        class="{{ $currentLocale === 'en' ? 'font-bold text-ocean-500' : 'text-gray-500 hover:text-ocean-500' }}"
    >
        EN
    </button>
</div>
```

---

### 1.5 Ficheiros de Tradução

**`lang/pt/navigation.php`**:

```php
<?php

return [
    'home' => 'Início',
    'news' => 'Notícias',
    'events' => 'Eventos',
    'surferWall' => 'Surfer Wall',
    'forecast' => 'Previsões',
    'about' => 'Sobre',
    'contact' => 'Contacto',
    'programs' => 'Programas',
    'services' => 'Serviços',
];
```

**`lang/en/navigation.php`**:

```php
<?php

return [
    'home' => 'Home',
    'news' => 'News',
    'events' => 'Events',
    'surferWall' => 'Surfer Wall',
    'forecast' => 'Forecast',
    'about' => 'About',
    'contact' => 'Contact',
    'programs' => 'Programs',
    'services' => 'Services',
];
```

---

## Entregáveis ✅

- [x] Layout master Blade com Livewire
- [x] Tailwind configurado com cores das 3 entidades
- [x] Tipografia (Montserrat + Inter) implementada
- [x] Header responsivo com navegação
- [x] Footer com 3 colunas (uma por entidade)
- [x] Componentes UI base (button, card, badge, input)
- [x] Livewire Language Switcher
- [x] Ficheiros de tradução (PT/EN)

---

## Critérios de Conclusão ✅

1. Design system documentado com exemplos
2. Componentes de layout funcionam em mobile/desktop
3. Cores e tipografia consistentes em toda a app
4. Navegação funcional entre secções
5. Troca de idioma funciona via Livewire

---

## Próxima Fase

→ [Fase 2: Homepage](./FASE_02_HOMEPAGE.md)

---

*Actualizado: 11 Dezembro 2025 - Arquitectura monolítica*
