# Setup Log - Praia do Norte

> Registo de instalação e configuração do ambiente de desenvolvimento.

---

## Data: 2025-12-05

---

## Versões Instaladas

### Backend (Laravel + Filament)

| Package | Versão | Notas |
|---------|--------|-------|
| **PHP** | 8.5.0 | Versão local (VPS usa 8.3) |
| **Laravel** | 12.41.1 | Via `composer create-project` |
| **Filament** | 4.2.4 | Latest stable |
| **Livewire** | 3.7.1 | Dependência do Filament |
| **MySQL** | 8.0 | SQLite usado localmente |

### Frontend (Next.js)

| Package | Versão | Notas |
|---------|--------|-------|
| **Next.js** | 16.0.7 | Versão mais recente |
| **React** | 19.2.0 | React 19 (nova versão) |
| **Tailwind CSS** | 4.x | Nova versão major |
| **TypeScript** | 5.x | TypeScript enabled |

---

## Comandos de Instalação Utilizados

### Backend

```bash
# Laravel
composer create-project laravel/laravel backend --prefer-dist

# Filament 4.x
composer require filament/filament:"^4.0" -W --ignore-platform-req=ext-intl
php artisan filament:install --panels
```

### Frontend

```bash
npx create-next-app@latest frontend --typescript --tailwind --app --src-dir --eslint --use-npm --no-git
```

---

## Problemas Encontrados e Soluções

### 1. Extensão PHP `intl` (Filament 4.x)

**Problema**: Filament 4.x requer a extensão `intl` do PHP, que não está disponível no PHP 8.5.0 (versão muito recente instalada via Homebrew).

**Solução temporária**:
```bash
composer require filament/filament:"^4.0" -W --ignore-platform-req=ext-intl
```

**Nota para produção**: O VPS utiliza PHP 8.3, onde a extensão `intl` está disponível. Este problema é apenas local.

**Ficheiro afetado**: `/opt/homebrew/etc/php/8.5/php.ini`
- A linha `;extension=intl` está comentada porque o binário não existe

### 2. Deprecação PDO MySQL (PHP 8.5)

**Warnings observados**:
```
Deprecated: Constant PDO::MYSQL_ATTR_SSL_CA is deprecated since 8.5
```

**Impacto**: Apenas warnings, não afeta funcionamento. Será corrigido em futuras versões do Laravel.

**Localização**: `backend/config/database.php` linhas 62 e 82

---

## Estrutura Criada

```
praiadonorte_nq/
├── backend/                    # Laravel 12 + Filament 4.x
│   ├── app/
│   │   ├── Filament/           # Filament resources (vazio)
│   │   ├── Models/
│   │   └── Providers/
│   │       └── Filament/
│   │           └── AdminPanelProvider.php
│   ├── database/
│   │   └── database.sqlite     # BD local
│   └── ...
│
└── frontend/                   # Next.js 16 + React 19
    ├── src/
    │   └── app/
    │       ├── layout.tsx
    │       ├── page.tsx
    │       └── globals.css
    └── ...
```

---

## Próximos Passos

1. [ ] Configurar `.env` do backend com dados de produção
2. [ ] Configurar `.env.local` do frontend com API URL
3. [ ] Instalar `next-intl` para i18n
4. [ ] Instalar `shadcn/ui` components
5. [ ] Criar primeiro Filament Resource (Noticia)

---

## URLs de Desenvolvimento

| Serviço | URL | Comando |
|---------|-----|---------|
| **Frontend** | http://localhost:3000 | `npm run dev` (no frontend/) |
| **Backend API** | http://localhost:8000/api | `php artisan serve` (no backend/) |
| **Filament Admin** | http://localhost:8000/admin | `php artisan serve` (no backend/) |

---

## Notas Importantes para Produção

1. **VPS requer PHP 8.3** - extensão `intl` disponível nativamente
2. **Frontend no Vercel** - deploy automático via Git
3. **Backend no VPS** - deploy via GitHub Actions + SSH
4. **MySQL 8.0** - substitui SQLite em produção
