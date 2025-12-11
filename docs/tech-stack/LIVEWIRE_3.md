# Livewire 3.x - Guia de Referência

## Praia do Norte Unified Platform

Este guia documenta a utilização do Livewire 3 no projecto.

---

## Instalação

```bash
cd backend
composer require livewire/livewire
```

O Livewire 3 inclui automaticamente os scripts necessários. Adicionar ao layout:

```blade
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{ $slot }}

    @livewireScripts
</body>
</html>
```

---

## Criar Componentes

### Via Artisan

```bash
php artisan make:livewire LanguageSwitcher
# Cria: app/Livewire/LanguageSwitcher.php
# Cria: resources/views/livewire/language-switcher.blade.php

php artisan make:livewire NewsFilter
php artisan make:livewire EventsFilter
php artisan make:livewire ContactForm
php artisan make:livewire MobileMenu
```

---

## Componentes do Projecto

### 1. LanguageSwitcher

**Ficheiro**: `app/Livewire/LanguageSwitcher.php`

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
        // Redirect to same page with new locale
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
        class="{{ $currentLocale === 'pt' ? 'font-bold text-ocean' : 'text-gray-500 hover:text-ocean' }}"
    >
        PT
    </button>
    <span class="text-gray-300">|</span>
    <button
        wire:click="switchTo('en')"
        class="{{ $currentLocale === 'en' ? 'font-bold text-ocean' : 'text-gray-500 hover:text-ocean' }}"
    >
        EN
    </button>
</div>
```

### 2. NewsFilter

**Ficheiro**: `app/Livewire/NewsFilter.php`

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Noticia;

class NewsFilter extends Component
{
    public ?string $entity = null;

    public function setEntity(?string $entity)
    {
        $this->entity = $entity;
    }

    public function render()
    {
        $query = Noticia::query()
            ->where('published', true)
            ->orderByDesc('published_at');

        if ($this->entity) {
            $query->where('entity', $this->entity);
        }

        return view('livewire.news-filter', [
            'noticias' => $query->paginate(9),
        ]);
    }
}
```

**View**: `resources/views/livewire/news-filter.blade.php`

```blade
<div>
    <!-- Filter Buttons -->
    <div class="mb-8 flex flex-wrap gap-2">
        <button
            wire:click="setEntity(null)"
            class="rounded-full px-4 py-2 text-sm font-medium transition
                {{ !$entity ? 'bg-ocean text-white' : 'bg-gray-100 hover:bg-gray-200' }}"
        >
            @lang('news.filters.all')
        </button>
        <button
            wire:click="setEntity('praia-norte')"
            class="rounded-full px-4 py-2 text-sm font-medium transition
                {{ $entity === 'praia-norte' ? 'bg-ocean text-white' : 'bg-gray-100 hover:bg-gray-200' }}"
        >
            @lang('news.filters.praiaDoNorte')
        </button>
        <button
            wire:click="setEntity('carsurf')"
            class="rounded-full px-4 py-2 text-sm font-medium transition
                {{ $entity === 'carsurf' ? 'bg-performance text-white' : 'bg-gray-100 hover:bg-gray-200' }}"
        >
            @lang('news.filters.carsurf')
        </button>
        <button
            wire:click="setEntity('nazare-qualifica')"
            class="rounded-full px-4 py-2 text-sm font-medium transition
                {{ $entity === 'nazare-qualifica' ? 'bg-institutional text-white' : 'bg-gray-100 hover:bg-gray-200' }}"
        >
            @lang('news.filters.nazareQualifica')
        </button>
    </div>

    <!-- News Grid -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($noticias as $noticia)
            <x-ui.card :href="route('noticias.show', ['locale' => app()->getLocale(), 'slug' => $noticia->slug])">
                <!-- Card content -->
            </x-ui.card>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $noticias->links() }}
    </div>
</div>
```

### 3. ContactForm

**Ficheiro**: `app/Livewire/ContactForm.php`

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class ContactForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $subject = '';
    public string $message = '';

    public bool $submitted = false;

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'email' => 'required|email',
        'subject' => 'required|min:5|max:200',
        'message' => 'required|min:10|max:5000',
    ];

    public function submit()
    {
        $this->validate();

        // Send email logic here
        // Mail::to('geral@nazarequalifica.pt')->send(...)

        $this->submitted = true;
        $this->reset(['name', 'email', 'subject', 'message']);
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
```

### 4. MobileMenu

**Ficheiro**: `app/Livewire/MobileMenu.php`

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class MobileMenu extends Component
{
    public bool $open = false;

    public function toggle()
    {
        $this->open = !$this->open;
    }

    public function close()
    {
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.mobile-menu');
    }
}
```

---

## Usar Componentes nas Views

```blade
<!-- No header -->
<livewire:language-switcher />

<!-- Na página de notícias -->
<livewire:news-filter />

<!-- Na página de contacto -->
<livewire:contact-form />

<!-- Menu mobile -->
<livewire:mobile-menu />
```

---

## Boas Práticas

### 1. Loading States

```blade
<button wire:click="submit" wire:loading.attr="disabled">
    <span wire:loading.remove>Enviar</span>
    <span wire:loading>A enviar...</span>
</button>
```

### 2. Confirmação

```blade
<button wire:click="delete" wire:confirm="Tens a certeza?">
    Eliminar
</button>
```

### 3. Debounce

```blade
<input type="text" wire:model.live.debounce.300ms="search">
```

### 4. Lazy Loading

```blade
<livewire:heavy-component lazy />
```

---

## Integração com Tailwind

O Livewire 3 funciona perfeitamente com Tailwind. As classes de estado como `wire:loading` podem usar classes Tailwind:

```blade
<div wire:loading.class="opacity-50">
    <!-- Conteúdo -->
</div>

<button wire:loading.class.remove="bg-ocean" wire:loading.class="bg-gray-400">
    Submit
</button>
```

---

## Eventos e Comunicação

### Emitir Evento

```php
// No componente
$this->dispatch('noticia-created');
```

### Escutar Evento

```php
// Noutro componente
#[On('noticia-created')]
public function refresh()
{
    // Actualizar dados
}
```

### JavaScript

```blade
<script>
    Livewire.on('noticia-created', () => {
        // JavaScript callback
    });
</script>
```

---

## Testes

```php
use Livewire\Livewire;

test('can filter news by entity', function () {
    Livewire::test(NewsFilter::class)
        ->call('setEntity', 'praia-norte')
        ->assertSet('entity', 'praia-norte');
});

test('can submit contact form', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'João Silva')
        ->set('email', 'joao@example.com')
        ->set('subject', 'Assunto de teste')
        ->set('message', 'Mensagem de teste com mais de 10 caracteres')
        ->call('submit')
        ->assertSet('submitted', true);
});
```

---

## Documentação Oficial

- [Livewire 3 Docs](https://livewire.laravel.com/docs)
- [Livewire Actions](https://livewire.laravel.com/docs/actions)
- [Livewire Forms](https://livewire.laravel.com/docs/forms)
- [Livewire Validation](https://livewire.laravel.com/docs/validation)

---

*Criado: 11 Dezembro 2025*
