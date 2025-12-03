# Fase 0: Preparação e Setup

**Duração Estimada**: 1 semana
**Dependências**: Nenhuma
**Bloco**: 1 - Fundações

---

## Objetivos

- Atualizar PHP no VPS para 8.3 (requisito Laravel 12)
- Configurar ambiente de desenvolvimento
- Inicializar repositórios
- Configurar CI/CD

> **Nota**: A instalação do Aimeos foi movida para a Fase 6 (E-commerce Setup), aguardando decisão sobre integração com API SAGE.

---

## Tarefas

### 0.0 Atualização PHP no VPS (Pré-requisito)

> **IMPORTANTE**: Laravel 12 requer PHP 8.2+. O VPS requer upgrade para PHP 8.3 via EasyApache 4.
> Esta tarefa deve ser executada ANTES de prosseguir com o setup do backend.

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

# Instalar Laravel Sanctum (para API auth)
php artisan install:api
```

### 0.2 Setup do Frontend (Next.js)

```bash
# Criar projeto Next.js
npx create-next-app@latest frontend --typescript --tailwind --eslint --app

cd frontend

# Instalar dependências
npm install zustand next-intl zod @hookform/resolvers react-hook-form

# Instalar shadcn/ui
npx shadcn@latest init
npx shadcn@latest add button card input label form
```

### 0.3 Estrutura Git

```bash
# Inicializar repositório
git init
git remote add origin git@github.com:nazare-qualifica/praia-do-norte.git

# Branches
# main          - produção
# develop       - desenvolvimento
# feature/*     - novas funcionalidades
```

### 0.4 CI/CD GitHub Actions

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

      - name: Deploy to VPS
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.VPS_HOST }}
          username: ${{ secrets.VPS_USER }}
          key: ${{ secrets.VPS_SSH_KEY }}
          script: |
            cd ~/api.praiadonortenazare.pt
            git pull origin main
            composer install --no-dev --optimize-autoloader
            php artisan migrate --force
            php artisan config:cache
            php artisan route:cache
            php artisan view:cache
```

---

## Entregáveis

- [ ] PHP 8.3 ativo no VPS (via EasyApache 4)
- [ ] Laravel 12 instalado e configurado
- [ ] Next.js 15 com App Router configurado
- [ ] Repositório Git com branches definidas
- [ ] GitHub Actions para deploy automático
- [ ] Variáveis de ambiente documentadas

---

## Critérios de Conclusão

1. `php artisan serve` inicia sem erros
2. `npm run dev` inicia sem erros
3. API básica responde em `/api/health`
4. CI/CD executa com sucesso (mesmo que dry-run)

---

## Próxima Fase

→ [Fase 1: Design System](./FASE_01_DESIGN.md)
