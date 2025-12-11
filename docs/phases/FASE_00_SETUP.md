# Fase 0: Preparação e Setup

**Status**: ✅ Completo
**Bloco**: 1 - Fundações

---

## Objetivos

- Atualizar PHP no VPS para 8.3 (requisito Laravel 12)
- Configurar ambiente de desenvolvimento Laravel + Blade + Livewire
- Inicializar repositório
- Configurar CI/CD

> **Nota**: Arquitectura monolítica (Laravel + Blade + Livewire) definida em 11 Dez 2025.

---

## Tarefas

### 0.0 Atualização PHP no VPS (Pré-requisito)

> **IMPORTANTE**: Laravel 12 requer PHP 8.2+. O VPS requer upgrade para PHP 8.3 via EasyApache 4.

**Via cPanel WHM (EasyApache 4):**

1. Login no WHM (WebHost Manager)
2. Navegar para: Software → EasyApache 4
3. Clicar "Customize" no perfil atual
4. Em PHP Versions:
   - Ativar PHP 8.3
   - Definir como default PHP handler
5. Garantir extensões ativas:
   - pdo_mysql, mbstring, openssl, json, curl
   - gd, intl, bcmath, zip, xml, fileinfo
6. Provisionar o perfil

**Verificação:**

```bash
php -v  # Deve mostrar 8.3.x
php -m  # Verificar extensões
```

---

### 0.1 Setup do Backend (Laravel 12)

```bash
# Criar projeto Laravel
composer create-project laravel/laravel backend

cd backend

# Configurar .env
cp .env.example .env
php artisan key:generate

# Configurar MySQL (em .env)
# DB_CONNECTION=mysql
# DB_HOST=localhost
# DB_DATABASE=praia_do_norte
# DB_USERNAME=pdn_app
# DB_PASSWORD=sua_password

# Executar migrations
php artisan migrate
```

---

### 0.2 Instalar Filament (Admin Panel)

```bash
cd backend

# Instalar Filament
composer require filament/filament

# Criar painel admin
php artisan filament:install --panels

# Criar utilizador admin
php artisan make:filament-user
```

---

### 0.3 Instalar Livewire + Dependências

```bash
cd backend

# Livewire para componentes interactivos
composer require livewire/livewire

# Laravel Localization para i18n
composer require mcamara/laravel-localization

# Publicar configs
php artisan vendor:publish --tag=livewire:config
php artisan vendor:publish --provider="Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider"
```

---

### 0.4 Configurar Tailwind CSS + Vite

```bash
cd backend

# Instalar dependências npm
npm install -D tailwindcss postcss autoprefixer
npm install @tailwindcss/typography

# Inicializar Tailwind
npx tailwindcss init -p
```

**`tailwind.config.js`**:

```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './vendor/livewire/**/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        'ocean': {
          50: '#e6f3ff',
          500: '#0066cc',
          900: '#003366',
        },
        'institutional': {
          50: '#fff4e6',
          500: '#ffa500',
          900: '#cc6600',
        },
        'performance': {
          50: '#e6fff5',
          500: '#00cc66',
          900: '#008844',
        },
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        display: ['Montserrat', 'sans-serif'],
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
```

**`resources/css/app.css`**:

```css
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap');

@tailwind base;
@tailwind components;
@tailwind utilities;

/* Custom utilities */
@layer utilities {
  .text-balance {
    text-wrap: balance;
  }
}
```

---

### 0.5 Configurar Localization (i18n)

**`config/laravellocalization.php`**:

```php
return [
    'supportedLocales' => [
        'pt' => ['name' => 'Portuguese', 'script' => 'Latn', 'native' => 'Português', 'regional' => 'pt_PT'],
        'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English', 'regional' => 'en_GB'],
    ],

    'useAcceptLanguageHeader' => true,
    'hideDefaultLocaleInURL' => false,
    'localesOrder' => ['pt', 'en'],
    'localeMapping' => [],
    'utf8suffix' => '.UTF-8',
    'urlsIgnored' => ['/admin', '/admin/*'],
];
```

**`app/Http/Kernel.php`** (ou bootstrap/app.php em Laravel 11+):

```php
// Adicionar middleware de localização
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
    ]);
})
```

---

### 0.6 Estrutura Git

```bash
# Inicializar repositório
git init
git remote add origin git@github.com:nazare-qualifica/praia-do-norte.git

# Branches
# main          - produção
# develop       - desenvolvimento
# feature/*     - novas funcionalidades
```

---

### 0.7 CI/CD GitHub Actions

**`.github/workflows/deploy-backend.yml`**:

```yaml
name: Deploy Backend

on:
  push:
    branches: [main]
    paths: ['backend/**']

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'

      - name: Install Dependencies
        run: |
          cd backend
          composer install --no-dev --optimize-autoloader

      - name: Build Assets
        run: |
          cd backend
          npm ci
          npm run build

      - name: Deploy to VPS
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.VPS_HOST }}
          username: ${{ secrets.VPS_USER }}
          key: ${{ secrets.VPS_SSH_KEY }}
          script: |
            cd ~/praiadonortenazare.pt
            git pull origin main
            composer install --no-dev --optimize-autoloader
            php artisan migrate --force
            php artisan config:cache
            php artisan route:cache
            php artisan view:cache
            npm ci && npm run build
```

---

## Entregáveis ✅

- [x] PHP 8.3 ativo no VPS (via EasyApache 4)
- [x] Laravel 12 instalado e configurado
- [x] Filament 4.x instalado (admin panel)
- [x] Livewire instalado
- [x] Tailwind CSS + Vite configurados
- [x] Laravel Localization configurado (PT/EN)
- [x] Repositório Git com branches definidas
- [x] GitHub Actions para deploy automático
- [x] Variáveis de ambiente documentadas

---

## Critérios de Conclusão ✅

1. `php artisan serve` inicia sem erros
2. `npm run dev` compila assets sem erros
3. Filament admin acessível em `/admin`
4. Livewire funcional
5. CI/CD executa com sucesso

---

## Próxima Fase

→ [Fase 1: Design System](./FASE_01_DESIGN.md)

---

*Actualizado: 11 Dezembro 2025 - Arquitectura monolítica*
