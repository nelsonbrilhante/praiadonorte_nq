# Plano de Migração para Produção

**Projeto:** Plataforma Unificada Praia do Norte
**Versão:** 2.0
**Data:** 2 de Março de 2026
**Infraestrutura alvo:** Coolify (VPS Ubuntu 24.04)

---

## Índice

1. [Visão Geral da Arquitetura](#1-visão-geral-da-arquitetura)
2. [Stack Técnico](#2-stack-técnico)
3. [Requisitos de Instalação](#3-requisitos-de-instalação)
4. [Infraestrutura Coolify](#4-infraestrutura-coolify)
5. [Deploy via Coolify](#5-deploy-via-coolify)
6. [Dockerfile](#6-dockerfile)
7. [Variáveis de Ambiente](#7-variáveis-de-ambiente)
8. [DNS e Cloudflare](#8-dns-e-cloudflare)
9. [Segurança](#9-segurança)
10. [Backups](#10-backups)
11. [CI/CD](#11-cicd)
12. [Rollback](#12-rollback)
13. [Checklist Pós-Migração](#13-checklist-pós-migração)

---

## 1. Visão Geral da Arquitetura

### Arquitetura atual

A plataforma é **monolítica** — Laravel serve HTML via Blade (SSR), não existe SPA nem frontend separado. O Filament serve como CMS admin. Não há API pública, Aimeos, ou Vercel.

### Diagrama de Infraestrutura

```
                         Utilizadores
                              │
                              ▼
                 ┌──────────────────────┐
                 │   CLOUDFLARE (CDN)   │
                 │  - WAF / DDoS        │
                 │  - SSL Termination   │
                 │  - DNS               │
                 └──────────┬───────────┘
                            │ HTTPS
                            ▼
              ┌──────────────────────────┐
              │  VPS 176.126.87.120      │
              │  Ubuntu 24.04 LTS        │
              │                          │
              │  ┌────────────────────┐  │
              │  │     COOLIFY        │  │
              │  │  (Orquestrador)    │  │
              │  └────────┬───────────┘  │
              │           │              │
              │  ┌────────┴───────────┐  │
              │  │     TRAEFIK        │  │
              │  │  (Reverse Proxy)   │  │
              │  │  - SSL (Let's      │  │
              │  │    Encrypt)        │  │
              │  │  - Routing         │  │
              │  └────────┬───────────┘  │
              │           │              │
              │     ┌─────┴──────┐       │
              │     ▼            ▼       │
              │ ┌─────────┐ ┌────────┐   │
              │ │ Laravel  │ │ MySQL  │   │
              │ │ PHP-FPM  │ │  8.0   │   │
              │ │ + Nginx  │ │        │   │
              │ │ (Docker) │ │(Docker)│   │
              │ └─────────┘ └────────┘   │
              │     │            │        │
              │     ▼            ▼        │
              │  [storage]   [db-data]    │
              │  (volume)    (volume)     │
              └──────────────────────────┘
```

### Domínios e Responsabilidades

**Domínios finais (produção):**

| Domínio | Destino | Função |
|---------|---------|--------|
| `praiadonortenazare.pt` | VPS (Coolify/Traefik) | Plataforma Laravel (Blade SSR) |
| `www.praiadonortenazare.pt` | Cloudflare redirect | Redirect 301 → root |
| `carsurf.nazare.pt` | Cloudflare redirect | Redirect 301 → praiadonortenazare.pt |
| `nazarequalifica.pt` | Cloudflare redirect | Redirect 301 → praiadonortenazare.pt |

**Domínios temporários (staging / desenvolvimento):**

| Domínio | Projeto | Função |
|---------|---------|--------|
| `pn.nelsonbrilhante.com` | Website Praia do Norte | Staging do site institucional |
| `storepn.nelsonbrilhante.com` | Loja online | Loja temporária operacional |
| `devpn.nelsonbrilhante.com` | Loja (cópia) | Integração Sage pela empresa parceira |

---

## 2. Stack Técnico

| Tecnologia | Versão | Notas |
|-----------|--------|-------|
| **PHP** | 8.3 | FPM, com extensões: pdo_mysql, mbstring, gd, intl, bcmath, zip, xml, fileinfo, curl |
| **Laravel** | 12.x | Framework principal |
| **Filament** | 4.x | CMS admin (`/admin`) |
| **Livewire** | 3.7+ | Apenas para `LanguageSwitcher` |
| **Alpine.js** | 3.15+ | Interatividade frontend (spotlight, dropdowns, dark mode) |
| **Tailwind CSS** | 4.x | CSS via `@tailwindcss/vite` plugin (sem `tailwind.config.js`) |
| **Vite** | 7.x | Build tool, 3 entry points |
| **MySQL** | 8.0 | Base de dados principal |
| **Node.js** | 20 LTS | Apenas para build de assets (Vite) |
| **mcamara/laravel-localization** | 2.3+ | Routing i18n (`/pt`, `/en`) |

### Entry Points do Vite

```
resources/css/app.css          → Frontend público
resources/js/app.js            → Frontend público (Alpine.js)
resources/css/filament/admin.css → Tema customizado do Filament admin
```

---

## 3. Requisitos de Instalação

Passos genéricos para instalar o projeto em qualquer ambiente (local, staging, ou produção).

### Pré-requisitos

- PHP 8.3 com extensões: pdo_mysql, mbstring, gd, intl, bcmath, zip, xml, fileinfo, curl, openssl
- Composer 2.x
- Node.js 20 LTS + npm
- MySQL 8.0
- Git

### Passos de Instalação

```bash
# 1. Clonar o repositório
git clone git@github.com:nelsonbrilhante/praia-do-norte.git
cd praia-do-norte/backend

# 2. Instalar dependências PHP
composer install

# 3. Copiar e configurar .env
cp .env.example .env
# Editar .env com credenciais da base de dados, APP_URL, etc.

# 4. Gerar chave da aplicação
php artisan key:generate

# 5. Executar migrações e seeders
php artisan migrate --seed

# 6. Criar link simbólico para storage
php artisan storage:link

# 7. Instalar dependências Node e compilar assets
npm install
npm run build

# 8. (Produção) Otimizar caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Servidor de Desenvolvimento

```bash
# Inicia 4 processos: serve, queue, logs, vite
composer dev

# Ou individualmente:
php artisan serve    # localhost:8000
npm run dev          # Vite dev server
```

**URLs de desenvolvimento:** `localhost:8000/pt`, `localhost:8000/en`, `localhost:8000/admin`

---

## 4. Infraestrutura Coolify

### O que é o Coolify

[Coolify](https://coolify.io) é uma plataforma self-hosted de PaaS (semelhante a Heroku/Vercel). Funciona em cima de Docker e fornece:

- UI web para gerir aplicações, bases de dados e serviços
- Deploy automático via Git webhook
- Gestão de variáveis de ambiente
- Traefik como reverse proxy (com SSL automático via Let's Encrypt)
- Gestão de volumes Docker persistentes
- Rollback via imagens Docker versionadas

### Instâncias Disponíveis

| Instância | URL | Uso |
|-----------|-----|-----|
| **coolify.nelsonbrilhante.com** | Dashboard principal | Apps de produção (Estadia, futuro Praia do Norte) |
| **176.126.87.120:8000** | Dashboard de segurança | Serviços de segurança (Wazuh, CrowdSec) |

**Credenciais Coolify:** `zumuha@gmail.com` + password conhecida.

### Recursos do VPS

| Recurso | Especificação |
|---------|--------------|
| **IP** | 176.126.87.120 |
| **SO** | Ubuntu 24.04 LTS |
| **RAM** | Partilhada (verificar `free -h` e `docker stats` antes de deploy) |
| **Docker** | Gerido pelo Coolify |

> **ATENÇÃO — RAM partilhada:** Este VPS já corre outras aplicações (Estadia, Wazuh, CrowdSec). Antes de fazer deploy, verificar recursos disponíveis:
> ```bash
> ssh root@176.126.87.120
> free -h
> docker stats --no-stream
> ```
> O container Laravel + Nginx tipicamente necessita 256-512MB. O MySQL adiciona ~256-512MB. Garantir pelo menos 1GB livre.

### Ambientes e Domínios Temporários

Existem 3 ambientes no Coolify, cada um como aplicação independente:

| Ambiente | Domínio | Projeto | Propósito |
|----------|---------|---------|-----------|
| **Website** | `pn.nelsonbrilhante.com` | Praia do Norte (Laravel + Blade) | Site institucional, notícias, surfers, NQ |
| **Loja** | `storepn.nelsonbrilhante.com` | Loja online | Loja temporária operacional |
| **Dev Sage** | `devpn.nelsonbrilhante.com` | Cópia da loja | Ambiente para a empresa integradora desenvolver a integração com o Sage |

**Fluxo previsto:**

```
pn.nelsonbrilhante.com          → praiadonortenazare.pt (quando pronto)
storepn.nelsonbrilhante.com     → loja temporária (operacional)
devpn.nelsonbrilhante.com       → integração Sage pela empresa parceira
                                   └→ quando completa, merge para storepn
                                      └→ eventualmente → domínio final da loja
```

**No Coolify, isto traduz-se em:**

- 3 aplicações Docker separadas (podem partilhar o mesmo MySQL ou ter DBs separados)
- Cada uma com o seu domínio configurado no Traefik
- Cada uma com as suas variáveis de ambiente (mesmo `.env`, URLs diferentes)
- A empresa integradora recebe acesso apenas ao `devpn` (credenciais Filament ou SSH limitado)

> **Nota sobre recursos:** 3 containers Laravel + 1-3 MySQL = estimativa ~2-3GB RAM. Verificar capacidade do VPS antes de levantar os 3 ambientes em simultâneo.

---

## 5. Deploy via Coolify

### 5.1. Criar Base de Dados MySQL

1. No dashboard Coolify → **Resources** → **New** → **Database**
2. Selecionar **MySQL 8.0**
3. Configurar:
   - **Name:** `pdn-mysql`
   - **Database:** `praia_do_norte`
   - **Username:** `pdn_app`
   - **Password:** Gerar password forte (20+ caracteres)
   - **Root Password:** Gerar outra password forte
4. Em **Storages**, confirmar que o volume de dados é persistente (path: `/var/lib/mysql`)
5. Clicar **Deploy**
6. Anotar o **Internal URL** (algo como `mysql://pdn_app:PASSWORD@pdn-mysql:3306/praia_do_norte`) — será usado nas variáveis de ambiente da aplicação

### 5.2. Criar Aplicação Laravel

1. No dashboard Coolify → **Resources** → **New** → **Application**
2. Selecionar **GitHub (Private Repository with Deploy Key)** ou usar o token existente
3. Configurar fonte:
   - **Repository:** `nelsonbrilhante/praia-do-norte`
   - **Branch:** `main`
   - **Build Pack:** Docker
4. Configurar build:
   - **Dockerfile Location:** `/Dockerfile` (raiz do projeto)
   - **Docker Context:** `/` (raiz, para aceder a `backend/`)
5. Em **General**:
   - **Name:** `praia-do-norte`
   - **Domains:** `praiadonortenazare.pt`

### 5.3. Configurar Variáveis de Ambiente

No painel da aplicação → **Environment Variables**, adicionar todas as variáveis listadas na [Secção 7](#7-variáveis-de-ambiente).

### 5.4. Configurar Volumes Persistentes

No painel da aplicação → **Storages**, adicionar:

| Container Path | Descrição |
|---------------|-----------|
| `/var/www/html/storage/app` | Uploads de ficheiros (PDFs, imagens) |
| `/var/www/html/storage/logs` | Logs Laravel |

> **IMPORTANTE:** Sem o volume de `storage/app`, todos os uploads de ficheiros (documentos NQ, imagens de notícias, fotos de surfers) serão perdidos quando o container for recriado.

### 5.5. Configurar Domínio

1. No painel da aplicação → **General** → **Domains**: `praiadonortenazare.pt`
2. O Traefik gera automaticamente certificado SSL via Let's Encrypt
3. Confirmar que o DNS do Cloudflare aponta para o IP do VPS (ver [Secção 8](#8-dns-e-cloudflare))

### 5.6. Primeiro Deploy

1. Clicar **Deploy** no painel Coolify
2. Monitorizar logs do build na UI do Coolify
3. Após deploy bem-sucedido, executar comandos iniciais via Coolify → **Terminal** (ou SSH):

```bash
# Dentro do container (via Coolify Execute Command ou docker exec)
php artisan key:generate
php artisan migrate --seed --force
php artisan storage:link
```

> Nota: `composer install`, `npm run build`, e os caches de otimização são executados pelo Dockerfile automaticamente.

---

## 6. Dockerfile

Criar na **raiz do projeto** (não dentro de `backend/`):

```dockerfile
# ============================================================
# Stage 1: Build de assets (Node.js)
# ============================================================
FROM node:20-alpine AS node-builder

WORKDIR /build

# Copiar ficheiros de dependências Node
COPY backend/package.json backend/package-lock.json* ./

# Instalar dependências Node
RUN npm ci

# Copiar código-fonte necessário para o build
COPY backend/vite.config.js ./
COPY backend/resources ./resources
# Tailwind v4 scan — precisa dos ficheiros Blade para detectar classes
COPY backend/app ./app

# Build de produção (CSS + JS)
RUN npm run build

# ============================================================
# Stage 2: Dependências PHP (Composer)
# ============================================================
FROM composer:2 AS composer-builder

WORKDIR /build

COPY backend/composer.json backend/composer.lock* ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# ============================================================
# Stage 3: Runtime (PHP-FPM + Nginx)
# ============================================================
FROM php:8.3-fpm-alpine

# Instalar extensões PHP necessárias
RUN apk add --no-cache \
        nginx \
        supervisor \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        libxml2-dev \
        curl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        gd \
        intl \
        bcmath \
        zip \
        xml \
        fileinfo \
        curl \
        opcache \
    && rm -rf /var/cache/apk/*

# Configuração PHP para produção
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY <<'EOF' /usr/local/etc/php/conf.d/custom.ini
memory_limit = 256M
max_execution_time = 120
post_max_size = 64M
upload_max_filesize = 32M
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 10000
opcache.validate_timestamps = 0
EOF

# Configuração Nginx
COPY <<'NGINX' /etc/nginx/http.d/default.conf
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php;

    client_max_body_size 64M;

    # Assets com cache longo (versionados pelo Vite)
    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    # Ficheiros estáticos
    location ~* \.(jpg|jpeg|png|gif|ico|svg|webp|woff2|woff|ttf|css|js|pdf)$ {
        expires 7d;
        add_header Cache-Control "public";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_buffering off;
    }

    location ~ /\.(?!well-known) {
        deny all;
    }
}
NGINX

# Configuração Supervisor (PHP-FPM + Nginx + Queue Worker)
COPY <<'SUPERVISOR' /etc/supervisord.conf
[supervisord]
nodaemon=true
logfile=/dev/stdout
logfile_maxbytes=0
pidfile=/var/run/supervisord.pid

[program:php-fpm]
command=php-fpm -F
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:nginx]
command=nginx -g 'daemon off;'
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:queue-worker]
command=php /var/www/html/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
SUPERVISOR

WORKDIR /var/www/html

# Copiar código-fonte Laravel
COPY backend/ .

# Copiar dependências do Composer
COPY --from=composer-builder /build/vendor ./vendor

# Copiar assets compilados do Node
COPY --from=node-builder /build/public/build ./public/build

# Permissões para storage e cache
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Otimizar Laravel para produção
RUN php artisan config:cache || true \
    && php artisan route:cache || true \
    && php artisan view:cache || true \
    && php artisan event:cache || true

# Criar storage link
RUN php artisan storage:link || true

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
```

### Notas sobre o Dockerfile

- **Multi-stage build**: Node apenas no stage 1, Composer no stage 2. A imagem final não contém Node nem Composer, reduzindo o tamanho.
- **Tailwind v4 scan**: O stage Node precisa dos ficheiros Blade/PHP para o Tailwind detectar classes CSS usadas. Copiar `app/` e `resources/` é suficiente.
- **Supervisor**: Gere 3 processos — PHP-FPM, Nginx, e Queue Worker — num único container.
- **Opcache**: Ativado com `validate_timestamps=0` (produção). Ficheiros PHP compilados ficam em cache permanente.
- **Cache headers**: Assets do Vite (`/build/`) têm cache de 1 ano (são versionados com hash).

---

## 7. Variáveis de Ambiente

Template `.env` de produção para configurar no Coolify:

```env
# ── Aplicação ──────────────────────────────────────────────
APP_NAME="Praia do Norte"
APP_ENV=production
APP_KEY=base64:GERAR_COM_php_artisan_key:generate
APP_DEBUG=false
APP_URL=https://praiadonortenazare.pt

# ── Localização ───────────────────────────────────────────
APP_LOCALE=pt
APP_FALLBACK_LOCALE=pt
APP_FAKER_LOCALE=pt_PT

# ── Logging ───────────────────────────────────────────────
LOG_CHANNEL=daily
LOG_LEVEL=warning

# ── Base de Dados ─────────────────────────────────────────
DB_CONNECTION=mysql
DB_HOST=pdn-mysql
DB_PORT=3306
DB_DATABASE=praia_do_norte
DB_USERNAME=pdn_app
DB_PASSWORD=SUA_PASSWORD_FORTE_AQUI

# ── Cache, Sessão e Filas ─────────────────────────────────
CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public

# ── Cron / Scheduler ─────────────────────────────────────
# Se necessário, adicionar cron ao Supervisor ou usar
# Coolify scheduled task para: php artisan schedule:run

# ── Email (configurar quando necessário) ──────────────────
MAIL_MAILER=smtp
MAIL_HOST=smtp.exemplo.pt
MAIL_PORT=587
MAIL_USERNAME=noreply@praiadonortenazare.pt
MAIL_PASSWORD=PASSWORD_EMAIL
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@praiadonortenazare.pt
MAIL_FROM_NAME="${APP_NAME}"

# ── Easypay (futuro — pagamentos) ─────────────────────────
# EASYPAY_ACCOUNT_ID=SEU_ACCOUNT_ID
# EASYPAY_API_KEY=SUA_API_KEY
# EASYPAY_BASE_URL=https://api.prod.easypay.pt/2.0
# EASYPAY_WEBHOOK_SECRET=SEU_WEBHOOK_SECRET

# ── Vite ──────────────────────────────────────────────────
VITE_APP_NAME="${APP_NAME}"
```

### Notas sobre variáveis

- **DB_HOST=pdn-mysql**: Nome do container MySQL no Docker network do Coolify (não `localhost` nem `127.0.0.1`).
- **CACHE_STORE=file / SESSION_DRIVER=file**: Simplificação — sem Redis. Suficiente para o volume de tráfego esperado.
- **Easypay**: Comentado para ativar quando o módulo de pagamentos estiver implementado.
- **Sem referências a**: Sanctum, Aimeos, Vercel, Cloudinary, ou CORS cross-origin (já não aplicável em arquitetura monolítica).

---

## 8. DNS e Cloudflare

### 8.1. Adicionar Domínio ao Cloudflare

1. Login em [dash.cloudflare.com](https://dash.cloudflare.com)
2. **Add a Site** → inserir `praiadonortenazare.pt`
3. Selecionar plano **Free**
4. Atualizar nameservers no registo de domínio para os fornecidos pelo Cloudflare

### 8.2. Configurar Registos DNS

| Tipo | Nome | Conteúdo | Proxy | TTL |
|------|------|----------|-------|-----|
| A | `@` | `176.126.87.120` | Proxied | Auto |
| CNAME | `www` | `praiadonortenazare.pt` | Proxied | Auto |

> **Nota:** O A record aponta diretamente para o VPS Coolify. Traefik encaminha o tráfego para o container Laravel com base no domínio.

### 8.3. Configurar Redirects

**Para `www.praiadonortenazare.pt`:**

Cloudflare → Rules → Redirect Rules:
- When: `(http.host eq "www.praiadonortenazare.pt")`
- Then: Dynamic redirect → `https://praiadonortenazare.pt${http.request.uri.path}`
- Status: 301

**Para `carsurf.nazare.pt`:**

1. Adicionar `carsurf.nazare.pt` ao Cloudflare (A record → `176.126.87.120`, Proxied)
2. Redirect Rule:
   - When: `(http.host eq "carsurf.nazare.pt")`
   - Then: `https://praiadonortenazare.pt${http.request.uri.path}`
   - Status: 301

**Para `nazarequalifica.pt`:**

Repetir o processo para `nazarequalifica.pt`.

### 8.4. Configurações de Segurança Cloudflare

**SSL/TLS:**
- Encryption mode: **Full (strict)** (Traefik gera certificado Let's Encrypt no servidor)
- Always Use HTTPS: **On**
- Minimum TLS Version: **1.2**
- TLS 1.3: **On**

**Speed → Optimization:**
- Brotli: **On**

**Security:**
- Security Level: **Medium**
- Browser Integrity Check: **On**
- WAF Managed Rules: **Ativar** (OWASP Core Ruleset)

### 8.5. Cloudflare + Traefik — Notas sobre SSL

Com **Full (strict)**, o Cloudflare exige certificado válido no servidor origin. Traefik (via Coolify) gera automaticamente certificado Let's Encrypt. Confirmar no Coolify que:

1. O domínio `praiadonortenazare.pt` está configurado na aplicação
2. O Traefik tem acesso à porta 80 (para challenge HTTP-01)
3. O certificado foi emitido (verificar em Coolify → Settings → SSL)

Se usar proxy Cloudflare (Proxied), pode alternativamente usar **Full** (não strict) e deixar o Traefik com certificado self-signed.

---

## 9. Segurança

### 9.1. Security Headers (via Nginx no container)

Os headers são configurados no Nginx dentro do Dockerfile. Adicionar ao bloco `server`:

```nginx
# Já incluído no Dockerfile - verificar se presentes
add_header X-Content-Type-Options "nosniff" always;
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-XSS-Protection "0" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Permissions-Policy "camera=(), microphone=(), geolocation=()" always;
```

> **Nota:** `X-Frame-Options: SAMEORIGIN` (não `DENY`) para permitir embeds do Filament admin. `X-XSS-Protection: 0` é a recomendação atual (o header é deprecated e pode causar XSS em browsers antigos).

### 9.2. Checklist de Segurança Laravel

- [ ] `APP_DEBUG=false` em produção
- [ ] `APP_ENV=production`
- [ ] `APP_KEY` única e secreta (nunca reutilizar a de desenvolvimento)
- [ ] Passwords de DB com 20+ caracteres
- [ ] Rate limiting ativo (Laravel throttle middleware)
- [ ] CSRF ativo em todas as forms Blade (automático no Laravel)
- [ ] File uploads validados (tipo, tamanho, extensão)
- [ ] Filament admin protegido por autenticação
- [ ] `storage/` e `bootstrap/cache/` não acessíveis via web

### 9.3. Segurança Docker

- Container corre como user `www-data` (não root)
- Imagem base Alpine (superfície de ataque mínima)
- Sem SSH no container (acesso via Coolify Terminal)
- Volumes montados com permissões restritivas

### 9.4. WAF e Proteção

- **Cloudflare WAF**: OWASP Core Ruleset ativado
- **CrowdSec** (na instância de segurança `176.126.87.120:8000`): Proteção adicional a nível do VPS
- **Wazuh** (mesma instância): Monitorização de intrusão

---

## 10. Backups

### 10.1. Backup da Base de Dados

**Opção A — Script cron no VPS host:**

```bash
#!/bin/bash
# /root/scripts/backup-pdn-db.sh

CONTAINER="pdn-mysql"  # Nome do container MySQL no Coolify
BACKUP_DIR="/root/backups/pdn"
DATE=$(date +%Y%m%d_%H%M)
RETENTION_DAYS=30

mkdir -p "$BACKUP_DIR"

# Dump via docker exec
docker exec "$CONTAINER" mysqldump \
    -u pdn_app \
    -p"$DB_PASSWORD" \
    praia_do_norte \
    | gzip > "$BACKUP_DIR/db_${DATE}.sql.gz"

# Limpar backups antigos
find "$BACKUP_DIR" -name "db_*.sql.gz" -mtime +$RETENTION_DAYS -delete

echo "[$(date)] Backup DB completo: db_${DATE}.sql.gz"
```

**Cron (no host VPS):**

```bash
# Backup diário às 3h
0 3 * * * /root/scripts/backup-pdn-db.sh >> /var/log/pdn-backup.log 2>&1
```

**Opção B — Coolify Scheduled Task:**

Coolify permite criar scheduled commands. Configurar um backup automático diretamente na UI.

### 10.2. Backup do Storage (Uploads)

```bash
#!/bin/bash
# /root/scripts/backup-pdn-storage.sh

# Path do volume Docker de storage (verificar com docker inspect)
STORAGE_PATH=$(docker inspect pdn-laravel --format '{{ range .Mounts }}{{ if eq .Destination "/var/www/html/storage/app" }}{{ .Source }}{{ end }}{{ end }}')
BACKUP_DIR="/root/backups/pdn"
DATE=$(date +%Y%m%d)

mkdir -p "$BACKUP_DIR"

tar -czf "$BACKUP_DIR/storage_${DATE}.tar.gz" -C "$STORAGE_PATH" .

# Manter últimos 4 backups semanais
ls -t "$BACKUP_DIR"/storage_*.tar.gz | tail -n +5 | xargs -r rm

echo "[$(date)] Backup storage completo: storage_${DATE}.tar.gz"
```

**Cron:** Semanal, domingos às 4h.

### 10.3. Backup Offsite (Recomendado)

Enviar backups para localização remota:

```bash
# Exemplo com rclone para S3/Backblaze B2
rclone copy /root/backups/pdn remote:pdn-backups/ --max-age 7d
```

### 10.4. Testar Restauro

Periodicamente (mensal), testar restauro do backup:

```bash
# Restaurar DB num container temporário
gunzip -k db_20260301_0300.sql.gz
docker exec -i pdn-mysql mysql -u root -p"$ROOT_PASSWORD" praia_do_norte_test < db_20260301_0300.sql
```

---

## 11. CI/CD

### Estratégia: GitHub Webhook → Coolify Auto-Deploy

Coolify suporta deploy automático via webhook do GitHub. Não são necessários GitHub Actions.

### 11.1. Configurar Webhook no GitHub

1. No Coolify, ir ao painel da aplicação `praia-do-norte`
2. Em **General**, copiar o **Webhook URL** fornecido pelo Coolify
3. No GitHub → Repository → Settings → Webhooks → Add webhook:
   - **Payload URL:** URL copiada do Coolify
   - **Content type:** `application/json`
   - **Secret:** O secret fornecido pelo Coolify
   - **Events:** Just the push event
4. Testar com "Recent Deliveries"

### 11.2. Fluxo de Deploy

```
git push origin main
       │
       ▼
GitHub envia webhook POST
       │
       ▼
Coolify recebe webhook
       │
       ▼
Coolify inicia build Docker
  1. Stage Node: npm ci + npm run build
  2. Stage Composer: composer install --no-dev
  3. Stage Runtime: copiar tudo para imagem PHP-FPM
       │
       ▼
Coolify faz deploy do novo container
  - Traefik redireciona tráfego (zero-downtime se configurado)
       │
       ▼
Executar pós-deploy (migrations, etc.)
```

### 11.3. Comandos Pós-Deploy

No Coolify → Application → **General** → **Post Deployment Command**:

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 11.4. Branching Strategy

| Branch | Deploy automático? | Destino |
|--------|-------------------|---------|
| `main` | Sim (webhook) | Produção |
| `feature/*` | Não | Apenas local |
| `fix/*` | Não | Apenas local |

> O deploy só é acionado em pushes para `main`. Feature branches são mergeadas via PR no GitHub.

---

## 12. Rollback

### 12.1. Rollback via Coolify

O Coolify mantém imagens Docker de deploys anteriores:

1. No dashboard Coolify → Aplicação → **Deployments**
2. Localizar o deploy anterior estável
3. Clicar **Redeploy** nessa versão
4. O Traefik redireciona automaticamente para o container anterior

### 12.2. Rollback de Migrações

Se uma migração causou problemas:

```bash
# Via Coolify Terminal (ou docker exec)
php artisan migrate:rollback --step=1
```

### 12.3. Rollback via Git

Se necessário reverter código:

```bash
# Localmente
git revert HEAD --no-edit
git push origin main
# → Webhook aciona novo deploy automaticamente
```

### 12.4. Restaurar Base de Dados

Em caso de corrupção de dados:

```bash
# No VPS host
gunzip -k /root/backups/pdn/db_YYYYMMDD_HHMM.sql.gz

# ATENÇÃO: substitui todos os dados atuais
docker exec -i pdn-mysql mysql \
    -u root -p"$ROOT_PASSWORD" \
    praia_do_norte < /root/backups/pdn/db_YYYYMMDD_HHMM.sql
```

---

## 13. Checklist Pós-Migração

Após deploy em produção, verificar todos os pontos:

### Funcionalidade Core

- [ ] Homepage carrega em `/pt` (português)
- [ ] Homepage carrega em `/en` (inglês)
- [ ] Root `/` redireciona para `/pt`
- [ ] Mudança de idioma funciona (LanguageSwitcher)
- [ ] Menu de navegação completo e funcional
- [ ] Footer com links funcionais
- [ ] Search spotlight (`Cmd+K`) funciona

### Conteúdo por Entidade

- [ ] **Praia do Norte**: Notícias, eventos, surfers, previsão
- [ ] **Carsurf**: Páginas de surf training
- [ ] **Nazaré Qualifica**: Documentos, corpos sociais, contraordenações, estacionamento

### Admin Panel (Filament)

- [ ] Login em `/admin` funciona (`admin@nazarequalifica.pt`)
- [ ] Criar/editar notícia
- [ ] Upload de imagens funciona e são visíveis no frontend
- [ ] Upload de documentos PDF funciona
- [ ] Gerir surfers e surfboards
- [ ] Gerir hero slides

### DNS e Redirects

- [ ] `praiadonortenazare.pt` → funciona (HTTPS)
- [ ] `www.praiadonortenazare.pt` → redirect 301 para root
- [ ] `carsurf.nazare.pt` → redirect 301 para site principal
- [ ] `nazarequalifica.pt` → redirect 301 para site principal

### SSL e Segurança

- [ ] Certificado SSL válido (verificar no browser)
- [ ] Sem mixed content warnings
- [ ] Security headers presentes (verificar com `curl -I`)
- [ ] WAF Cloudflare ativo
- [ ] `APP_DEBUG=false` confirmado

### Performance

- [ ] Lighthouse Performance > 90
- [ ] Lighthouse Accessibility > 95
- [ ] Lighthouse SEO > 95
- [ ] LCP < 2.5s
- [ ] TTFB < 200ms
- [ ] Assets Vite servidos com cache headers (`Cache-Control: public, immutable`)

### Persistência

- [ ] Volumes Docker montados (storage, logs)
- [ ] Upload de ficheiro no admin persiste após redeploy
- [ ] Base de dados persiste após redeploy
- [ ] Queue worker processa jobs

### Backups

- [ ] Script de backup DB funciona
- [ ] Script de backup storage funciona
- [ ] Restauro de backup testado com sucesso

---

## Potenciais Obstáculos

| Obstáculo | Mitigação |
|-----------|-----------|
| **RAM insuficiente** | Verificar `free -h` e `docker stats` antes. Considerar aumentar swap ou VPS. |
| **MySQL sem volume** | **Obrigatório** configurar volume persistente no Coolify. Sem volume, dados perdem-se em redeploy. |
| **Vite build sem Node** | Resolvido pelo multi-stage Dockerfile — Node apenas no build stage. |
| **Storage uploads perdidos** | **Obrigatório** volume Docker para `/var/www/html/storage/app`. |
| **Cron/Scheduler** | Adicionar `cron` ao Supervisor no Dockerfile, ou usar Coolify Scheduled Tasks. |
| **Tailwind não detecta classes** | O stage Node precisa de copiar `app/` e `resources/` para o scan funcionar. |
| **Cloudflare Full (strict) + self-signed** | Usar Let's Encrypt no Traefik, ou mudar Cloudflare para Full (não strict). |
| **Container sem acesso à rede Docker** | Confirmar que app e MySQL estão na mesma Docker network no Coolify. |

---

## Contactos de Emergência

| Função | Contacto |
|--------|----------|
| **Responsável Técnico** | Nelson Brilhante |
| **Suporte Coolify** | [coolify.io/docs](https://coolify.io/docs) / Discord |
| **Suporte Cloudflare** | Via dashboard (ticket) |
| **Suporte Easypay** (futuro) | suporte@easypay.pt |

---

## Histórico de Versões

| Versão | Data | Alterações |
|--------|------|------------|
| 1.0 | 2025-11-25 | Documento inicial (arquitetura Next.js + Vercel + cPanel) |
| 2.0 | 2026-03-02 | Reescrita completa — arquitetura monolítica Laravel, deploy via Coolify (Docker) |

---

*Documento atualizado como parte do projeto Praia do Norte Unified Platform*
