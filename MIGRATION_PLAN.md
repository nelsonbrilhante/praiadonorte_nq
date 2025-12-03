# Plano de Migração para Produção

**Projeto:** Plataforma Unificada Praia do Norte
**Versão:** 1.0
**Data:** 25 de Novembro de 2025

---

## Índice

1. [Visão Geral da Arquitetura](#1-visão-geral-da-arquitetura)
2. [Checklist de Pré-Migração](#2-checklist-de-pré-migração)
3. [Configuração DNS e Cloudflare](#3-configuração-dns-e-cloudflare)
4. [Preparação do VPS (cPanel)](#4-preparação-do-vps-cpanel)
5. [Deploy do Backend (Laravel + Aimeos)](#5-deploy-do-backend-laravel--aimeos)
6. [Deploy do Frontend (Next.js + Vercel)](#6-deploy-do-frontend-nextjs--vercel)
7. [CI/CD com GitHub Actions](#7-cicd-com-github-actions)
8. [Configuração de Webhooks Easypay](#8-configuração-de-webhooks-easypay)
9. [Segurança e Hardening](#9-segurança-e-hardening)
10. [Monitorização e Logs](#10-monitorização-e-logs)
11. [Estratégia de Backup](#11-estratégia-de-backup)
12. [Procedimentos de Rollback](#12-procedimentos-de-rollback)
13. [Checklist de Testes Pós-Migração](#13-checklist-de-testes-pós-migração)

---

## 1. Visão Geral da Arquitetura

### Diagrama de Infraestrutura

```
                    ┌─────────────────────────────────────┐
                    │         CLOUDFLARE (CDN/SSL)        │
                    │    - WAF (Web Application Firewall) │
                    │    - DDoS Protection                │
                    │    - SSL/TLS Termination            │
                    └──────────────┬──────────────────────┘
                                   │
            ┌──────────────────────┼──────────────────────┐
            │                      │                      │
            ▼                      ▼                      ▼
┌───────────────────┐  ┌───────────────────┐  ┌───────────────────┐
│ praiadonortenazare│  │api.praiadonorte   │  │ Redirects (301)   │
│       .pt         │  │   nazare.pt       │  │                   │
│                   │  │                   │  │ carsurf.nazare.pt │
│   VERCEL          │  │   VPS (cPanel)    │  │ nazarequalifica.pt│
│   (Next.js 15)    │  │   (Laravel 12)    │  │     → main site   │
└───────────────────┘  └───────────────────┘  └───────────────────┘
            │                      │
            │                      │
            │   REST API (HTTPS)   │
            └──────────────────────┘
                      │
                      ▼
            ┌───────────────────┐
            │   MySQL 8.0       │
            │   (No mesmo VPS)  │
            │                   │
            │ - Produtos        │
            │ - Encomendas      │
            │ - Clientes        │
            └───────────────────┘
```

### Domínios e Responsabilidades

| Domínio | Destino | Função |
|---------|---------|--------|
| `praiadonortenazare.pt` | Vercel | Frontend Next.js (SSR) |
| `www.praiadonortenazare.pt` | Vercel | Redirect para root |
| `api.praiadonortenazare.pt` | VPS | Backend Laravel + Aimeos |
| `carsurf.nazare.pt` | Cloudflare | Redirect 301 → praiadonortenazare.pt |
| `nazarequalifica.pt` | Cloudflare | Redirect 301 → praiadonortenazare.pt |

### Especificações do VPS

| Recurso | Especificação |
|---------|--------------|
| **Servidor** | vm01.cm-nazare.pt |
| **CPU** | 4 vCPUs @ 2.1GHz |
| **RAM** | 4GB (limitação - swap esgotada) |
| **Armazenamento** | 114GB livre |
| **SO** | CentOS 7 (EOL - ver aviso abaixo) |
| **PHP** | 8.3 com FPM (requer upgrade de 8.1 via EasyApache 4) |
| **MySQL** | 8.0.42 |
| **Servidor Web** | Apache 2.4 |
| **Painel** | cPanel 110.0.50 |

> **AVISO CRÍTICO: CentOS 7 EOL**
>
> O CentOS 7 atingiu o fim de vida (EOL) em 30 de Junho de 2024. Isto significa:
> - Sem mais atualizações de segurança
> - Risco acrescido de vulnerabilidades
>
> **Mitigações Recomendadas:**
> 1. Cloudflare WAF ativado com regras rigorosas
> 2. Firewall do cPanel configurado restritivamente
> 3. Monitorização de logs ativa
> 4. Planear migração para AlmaLinux 8/9 ou Rocky Linux nos próximos 6 meses

---

## 2. Checklist de Pré-Migração

Antes de iniciar qualquer processo de migração, confirmar:

### Acessos e Credenciais

- [ ] Acesso ao registo de domínios (praiadonortenazare.pt)
- [ ] Conta Cloudflare criada e verificada
- [ ] Acesso SSH ao VPS (vm01.cm-nazare.pt)
- [ ] Acesso ao cPanel do VPS
- [ ] Conta GitHub com acesso ao repositório
- [ ] Conta Vercel criada (pode usar GitHub OAuth)
- [ ] Credenciais de produção Easypay obtidas

### Infraestrutura

- [ ] VPS com PHP 8.3 ativo (upgrade de 8.1 via EasyApache 4)
- [ ] MySQL 8.0 acessível
- [ ] Espaço em disco suficiente (mínimo 10GB livres)
- [ ] Extensões PHP necessárias instaladas

### Documentação Pronta

- [ ] Variáveis de ambiente documentadas
- [ ] Contactos de emergência definidos
- [ ] Procedimento de rollback testado

---

## 3. Configuração DNS e Cloudflare

### 3.1. Adicionar Domínio ao Cloudflare

1. Fazer login em [dash.cloudflare.com](https://dash.cloudflare.com)
2. Clicar "Add a Site" → inserir `praiadonortenazare.pt`
3. Selecionar plano **Free**
4. Cloudflare irá detetar registos DNS existentes
5. Atualizar nameservers no registo de domínio:
   - `ns1.cloudflare.com` (ou os fornecidos)
   - `ns2.cloudflare.com`

### 3.2. Configurar Registos DNS

No painel Cloudflare → DNS → Records:

| Tipo | Nome | Conteúdo | Proxy | TTL |
|------|------|----------|-------|-----|
| A | `@` | `76.76.21.21` (Vercel) | Proxied | Auto |
| A | `api` | `[IP_DO_VPS]` | Proxied | Auto |
| CNAME | `www` | `praiadonortenazare.pt` | Proxied | Auto |

> **Nota:** O IP da Vercel (`76.76.21.21`) pode variar. Confirmar em Vercel → Settings → Domains.

### 3.3. Configurar Redirects (Page Rules ou Redirect Rules)

**Para carsurf.nazare.pt:**

1. Adicionar o domínio `carsurf.nazare.pt` ao Cloudflare
2. Ir a Rules → Redirect Rules → Create Rule
3. Configurar:
   - Nome: "Redirect Carsurf to Main"
   - When: `(http.host eq "carsurf.nazare.pt")`
   - Then: Dynamic redirect → `https://praiadonortenazare.pt${http.request.uri.path}`
   - Status code: 301 (Permanent)

**Para nazarequalifica.pt:**

Repetir o processo acima para `nazarequalifica.pt`.

### 3.4. Configurações de Segurança Cloudflare

**SSL/TLS → Overview:**
- Encryption mode: **Full (strict)**

**SSL/TLS → Edge Certificates:**
- Always Use HTTPS: **On**
- Minimum TLS Version: **1.2**
- Opportunistic Encryption: **On**
- TLS 1.3: **On**

**Speed → Optimization:**
- Auto Minify: CSS, JavaScript, HTML → **On**
- Brotli: **On**

**Security → Settings:**
- Security Level: **Medium**
- Challenge Passage: **30 minutes**
- Browser Integrity Check: **On**

**Security → WAF:**
- Managed Rules: **Ativar** (OWASP Core Ruleset)

---

## 4. Preparação do VPS (cPanel)

### 4.1. Criar Base de Dados MySQL

**Via cPanel → MySQL Databases:**

1. **Criar Base de Dados:**
   - Nome: `praia_do_norte_prod`
   - Collation: `utf8mb4_unicode_ci`

2. **Criar Utilizador:**
   - Username: `pdn_app`
   - Password: (gerar password forte, 20+ caracteres)

3. **Adicionar Utilizador à Base de Dados:**
   - Selecionar apenas privilégios necessários:
     - SELECT, INSERT, UPDATE, DELETE
     - CREATE, ALTER, DROP, INDEX
     - REFERENCES

### 4.2. Verificar Extensões PHP

**Via cPanel → Select PHP Version:**

Confirmar que as seguintes extensões estão ativas:

```
✓ pdo_mysql
✓ mbstring
✓ openssl
✓ json
✓ curl
✓ gd
✓ intl
✓ bcmath
✓ zip
✓ xml
✓ fileinfo
```

**Configurações PHP recomendadas:**

```ini
memory_limit = 256M
max_execution_time = 120
post_max_size = 64M
upload_max_filesize = 32M
```

### 4.3. Estrutura de Diretórios

**Via cPanel → File Manager ou SSH:**

```bash
# Criar estrutura para o backend
mkdir -p ~/api.praiadonortenazare.pt

# O Laravel será colocado aqui com a seguinte estrutura:
# api.praiadonortenazare.pt/
# ├── app/
# ├── bootstrap/
# ├── config/
# ├── database/
# ├── public/          ← DocumentRoot do Apache
# ├── resources/
# ├── routes/
# ├── storage/
# │   ├── app/
# │   ├── framework/
# │   └── logs/
# └── vendor/
```

### 4.4. Configurar Subdomínio no cPanel

**Via cPanel → Domains → Create a New Domain:**

1. Domain: `api.praiadonortenazare.pt`
2. Document Root: `/home/[user]/api.praiadonortenazare.pt/public`
3. PHP Version: 8.3

### 4.5. Permissões de Diretórios

```bash
# Após upload do Laravel
cd ~/api.praiadonortenazare.pt

# Permissões para storage e cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Proprietário correto (substituir USER pelo user do cPanel)
chown -R USER:USER storage
chown -R USER:USER bootstrap/cache
```

---

## 5. Deploy do Backend (Laravel + Aimeos)

### 5.1. Ficheiro .env de Produção

Criar ficheiro `.env` no servidor (nunca commitar no Git):

```env
# Application
APP_NAME="Praia do Norte"
APP_ENV=production
APP_KEY=base64:GERAR_COM_php_artisan_key:generate
APP_DEBUG=false
APP_URL=https://api.praiadonortenazare.pt

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=warning

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=praia_do_norte_prod
DB_USERNAME=pdn_app
DB_PASSWORD=SUA_PASSWORD_FORTE_AQUI

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database

# Easypay (PRODUÇÃO)
EASYPAY_ACCOUNT_ID=SEU_ACCOUNT_ID_PRODUCAO
EASYPAY_API_KEY=SUA_API_KEY_PRODUCAO
EASYPAY_BASE_URL=https://api.prod.easypay.pt/2.0
EASYPAY_WEBHOOK_SECRET=SEU_WEBHOOK_SECRET

# Laravel Sanctum (CORS)
SANCTUM_STATEFUL_DOMAINS=praiadonortenazare.pt,www.praiadonortenazare.pt
SESSION_DOMAIN=.praiadonortenazare.pt

# Aimeos
SHOP_MULTILOCALE=1
SHOP_CURRENCY=EUR
SHOP_LOCALE=pt
```

> **IMPORTANTE:** Nunca versionar o ficheiro `.env`. Adicionar ao `.gitignore`.

### 5.2. Deploy Inicial (Manual)

Para o primeiro deploy ou emergências:

```bash
# 1. SSH para o servidor
ssh user@vm01.cm-nazare.pt

# 2. Navegar para o diretório
cd ~/api.praiadonortenazare.pt

# 3. Clonar repositório (primeiro deploy)
git clone git@github.com:SEU_USER/praia-do-norte.git .

# 4. Instalar dependências (sem dev)
composer install --no-dev --optimize-autoloader

# 5. Gerar chave da aplicação (apenas primeiro deploy)
php artisan key:generate

# 6. Executar migrações
php artisan migrate --force

# 7. Setup do Aimeos
php artisan aimeos:setup

# 8. Otimizar para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 9. Criar link simbólico para storage
php artisan storage:link
```

### 5.3. Configuração do Apache (.htaccess)

No diretório `public/`, criar ou verificar `.htaccess`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "DENY"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>
```

---

## 6. Deploy do Frontend (Next.js + Vercel)

### 6.1. Criar Projeto na Vercel

1. Ir a [vercel.com](https://vercel.com) → Sign in com GitHub
2. "Add New Project" → Import repositório GitHub
3. Configurar:
   - Framework Preset: **Next.js**
   - Root Directory: `frontend/` (se monorepo)
   - Build Command: `npm run build`
   - Output Directory: `.next`

### 6.2. Variáveis de Ambiente (Vercel)

**Settings → Environment Variables:**

| Variável | Valor | Environments |
|----------|-------|--------------|
| `NEXT_PUBLIC_API_URL` | `https://api.praiadonortenazare.pt` | Production |
| `NEXT_PUBLIC_SITE_URL` | `https://praiadonortenazare.pt` | Production |
| `NEXT_PUBLIC_CLOUDINARY_CLOUD_NAME` | `[seu_cloud_name]` | Production |

### 6.3. Configurar Domínio na Vercel

**Settings → Domains:**

1. Add domain: `praiadonortenazare.pt`
2. Vercel fornecerá instruções de DNS
3. Como estamos a usar Cloudflare:
   - Não usar verificação via DNS TXT
   - Apontar A record para IP da Vercel
4. Add domain: `www.praiadonortenazare.pt` (redirect para root)

### 6.4. Configurações de Build

**vercel.json** (na raiz do projeto frontend):

```json
{
  "framework": "nextjs",
  "regions": ["cdg1"],
  "headers": [
    {
      "source": "/(.*)",
      "headers": [
        {
          "key": "X-Content-Type-Options",
          "value": "nosniff"
        },
        {
          "key": "X-Frame-Options",
          "value": "DENY"
        },
        {
          "key": "X-XSS-Protection",
          "value": "1; mode=block"
        }
      ]
    }
  ]
}
```

> **Nota:** `cdg1` é a região de Paris (mais próxima de Portugal). Alternativas: `fra1` (Frankfurt).

---

## 7. CI/CD com GitHub Actions

### 7.1. Workflow de Deploy do Backend

Criar ficheiro `.github/workflows/deploy-backend.yml`:

```yaml
name: Deploy Backend to VPS

on:
  push:
    branches:
      - main
    paths:
      - 'backend/**'
      - '.github/workflows/deploy-backend.yml'

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Deploy to VPS via SSH
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.VPS_HOST }}
          username: ${{ secrets.VPS_USERNAME }}
          key: ${{ secrets.VPS_SSH_KEY }}
          script: |
            cd ~/api.praiadonortenazare.pt

            # Pull latest changes
            git pull origin main

            # Install dependencies
            composer install --no-dev --optimize-autoloader

            # Run migrations
            php artisan migrate --force

            # Clear and rebuild caches
            php artisan config:cache
            php artisan route:cache
            php artisan view:cache
            php artisan event:cache

            # Restart PHP-FPM (se tiver acesso)
            # sudo systemctl restart php-fpm

            echo "Deploy completed at $(date)"

      - name: Notify on failure
        if: failure()
        run: echo "Deploy failed! Check the logs."
```

### 7.2. Secrets do GitHub

**Repository → Settings → Secrets → Actions:**

| Nome | Descrição |
|------|-----------|
| `VPS_HOST` | `vm01.cm-nazare.pt` |
| `VPS_USERNAME` | Username SSH do cPanel |
| `VPS_SSH_KEY` | Chave privada SSH (gerar com `ssh-keygen`) |

### 7.3. Configurar Chave SSH no VPS

```bash
# No VPS, adicionar chave pública
nano ~/.ssh/authorized_keys
# Colar a chave pública correspondente à VPS_SSH_KEY
```

### 7.4. Deploy Automático do Frontend

O Vercel já trata automaticamente:
- Conectado ao GitHub
- Deploy automático em push para `main`
- Preview deployments em pull requests

---

## 8. Configuração de Webhooks Easypay

### 8.1. Configurar Webhook no Painel Easypay

1. Login em [backoffice.easypay.pt](https://backoffice.easypay.pt)
2. Configurações → Webhooks → Adicionar
3. URL: `https://api.praiadonortenazare.pt/api/webhooks/easypay`
4. Eventos a subscrever:
   - `payment.success`
   - `payment.failed`
   - `payment.pending`
   - `refund.success`

### 8.2. Whitelist de IPs Easypay no Cloudflare

**Security → WAF → Tools → IP Access Rules:**

Adicionar IPs da Easypay como "Allow":
- Consultar documentação Easypay para lista atualizada de IPs

### 8.3. Implementar Validação de Webhook

No Laravel, criar middleware para validar assinatura:

```php
// app/Http/Middleware/ValidateEasypayWebhook.php

public function handle($request, Closure $next)
{
    $signature = $request->header('X-Easypay-Signature');
    $payload = $request->getContent();
    $secret = config('services.easypay.webhook_secret');

    $expectedSignature = hash_hmac('sha256', $payload, $secret);

    if (!hash_equals($expectedSignature, $signature)) {
        Log::warning('Invalid Easypay webhook signature', [
            'ip' => $request->ip(),
        ]);
        abort(401, 'Invalid signature');
    }

    return $next($request);
}
```

---

## 9. Segurança e Hardening

### 9.1. Checklist de Segurança

**Laravel/Backend:**

- [ ] `APP_DEBUG=false` em produção
- [ ] `APP_ENV=production`
- [ ] Chave APP_KEY única e secreta
- [ ] Todas as passwords com 20+ caracteres
- [ ] Rate limiting ativo nas rotas de API
- [ ] CORS configurado apenas para domínio autorizado
- [ ] Validação server-side de todos os preços
- [ ] Logs de auditoria para transações

**Frontend/Vercel:**

- [ ] Sem credenciais expostas no código cliente
- [ ] Variáveis sensíveis apenas no servidor
- [ ] Headers de segurança configurados
- [ ] `npm audit` sem vulnerabilidades críticas

**Cloudflare:**

- [ ] WAF ativado com OWASP rules
- [ ] Rate limiting configurado
- [ ] Bot management ativo
- [ ] SSL Mode: Full (Strict)

### 9.2. Rate Limiting no Laravel

```php
// routes/api.php

Route::middleware(['throttle:api'])->group(function () {
    // Rotas de autenticação: 10 requests/minuto
    Route::middleware(['throttle:10,1'])->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
    });

    // Rotas de checkout: 30 requests/minuto
    Route::middleware(['throttle:30,1'])->group(function () {
        Route::post('/checkout', [CheckoutController::class, 'process']);
    });
});
```

### 9.3. CORS Configuration

```php
// config/cors.php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'https://praiadonortenazare.pt',
        'https://www.praiadonortenazare.pt',
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

---

## 10. Monitorização e Logs

### 10.1. Cloudflare Analytics

- Tráfego em tempo real
- Ameaças bloqueadas
- Performance (cache hit ratio)
- Web Analytics (alternativa ao Google Analytics)

### 10.2. Laravel Logs

**Localização:** `storage/logs/laravel.log`

**Configuração recomendada (LOG_LEVEL=warning):**

```php
// config/logging.php

'daily' => [
    'driver' => 'daily',
    'path' => storage_path('logs/laravel.log'),
    'level' => env('LOG_LEVEL', 'warning'),
    'days' => 14, // Manter logs por 14 dias
],
```

### 10.3. MySQL Slow Query Log

**Via cPanel → MySQL → Slow Query Log:**

1. Ativar slow query log
2. Threshold: 2 segundos
3. Analisar queries lentas semanalmente

### 10.4. Vercel Logs

- **Build Logs:** Erros de compilação
- **Function Logs:** Erros de runtime
- **Analytics:** Performance e usage

---

## 11. Estratégia de Backup

### 11.1. Backup da Base de Dados

**Via cPanel → Backup Wizard:**

1. Configurar backup automático diário
2. Destino: local + remoto (se disponível)
3. Retenção: 30 dias

**Backup manual via SSH:**

```bash
# Criar backup
mysqldump -u pdn_app -p praia_do_norte_prod > backup_$(date +%Y%m%d).sql

# Comprimir
gzip backup_$(date +%Y%m%d).sql
```

### 11.2. Backup do Storage Laravel

**Script de backup semanal:**

```bash
#!/bin/bash
# backup-storage.sh

BACKUP_DIR=~/backups
DATE=$(date +%Y%m%d)
SOURCE=~/api.praiadonortenazare.pt/storage/app/public

mkdir -p $BACKUP_DIR

tar -czf $BACKUP_DIR/storage_$DATE.tar.gz $SOURCE

# Manter apenas últimos 4 backups
ls -t $BACKUP_DIR/storage_*.tar.gz | tail -n +5 | xargs -r rm

echo "Backup completed: storage_$DATE.tar.gz"
```

**Cron job (via cPanel → Cron Jobs):**

```
0 3 * * 0 /home/USER/scripts/backup-storage.sh
```

### 11.3. Código Fonte

- Git é o backup do código
- Branches protegidos
- Não há dados críticos no código

---

## 12. Procedimentos de Rollback

### 12.1. Rollback do Backend

```bash
# SSH para o servidor
ssh user@vm01.cm-nazare.pt

cd ~/api.praiadonortenazare.pt

# Ver commits recentes
git log --oneline -10

# Reverter para commit anterior
git revert HEAD --no-edit

# Ou checkout de versão específica
git checkout [COMMIT_HASH]

# Reinstalar dependências
composer install --no-dev --optimize-autoloader

# Reverter migrações (se necessário)
php artisan migrate:rollback --step=1

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 12.2. Rollback do Frontend (Vercel)

1. Ir a Vercel → Deployments
2. Encontrar deployment anterior estável
3. Clicar "..." → "Promote to Production"
4. Confirmação instantânea

### 12.3. Restaurar Base de Dados

```bash
# Descomprir backup
gunzip backup_20251125.sql.gz

# Restaurar (CUIDADO: apaga dados atuais)
mysql -u pdn_app -p praia_do_norte_prod < backup_20251125.sql
```

---

## 13. Checklist de Testes Pós-Migração

Após completar a migração, verificar todos os seguintes pontos:

### Testes Funcionais

- [ ] Homepage carrega corretamente (PT)
- [ ] Homepage carrega corretamente (EN)
- [ ] Mudança de idioma funciona
- [ ] Menu de navegação completo
- [ ] Footer com links funcionais

### E-commerce

- [ ] Catálogo de produtos exibido
- [ ] Páginas de produto individuais
- [ ] Filtros e pesquisa funcionam
- [ ] Adicionar ao carrinho funciona
- [ ] Carrinho persiste entre páginas
- [ ] Checkout flow completo
- [ ] Formulário de checkout valida dados

### Pagamentos (Modo Teste)

- [ ] Integração Easypay configurada
- [ ] Cartão de teste aceite
- [ ] MB WAY de teste funciona
- [ ] Webhook recebido após pagamento
- [ ] Encomenda criada corretamente
- [ ] Email de confirmação enviado

### Autenticação

- [ ] Registo de novo utilizador
- [ ] Login funciona
- [ ] Recuperação de password
- [ ] Área de cliente acessível
- [ ] Histórico de encomendas visível

### Admin Panel (Aimeos)

- [ ] Login no admin funciona
- [ ] Listar produtos
- [ ] Editar produto
- [ ] Ver encomendas
- [ ] Alterar estado de encomenda

### Redirects e DNS

- [ ] praiadonortenazare.pt → funciona
- [ ] www.praiadonortenazare.pt → redirect para root
- [ ] api.praiadonortenazare.pt → responde (health check)
- [ ] carsurf.nazare.pt → redirect 301 para main
- [ ] nazarequalifica.pt → redirect 301 para main

### SSL e Segurança

- [ ] Certificado SSL válido (verificar em browser)
- [ ] HSTS ativo
- [ ] Sem mixed content warnings
- [ ] Headers de segurança presentes
- [ ] WAF Cloudflare ativo

### Performance

- [ ] Lighthouse Performance > 90
- [ ] LCP < 2.5s
- [ ] Imagens otimizadas
- [ ] Caching funcional

---

## Contactos de Emergência

| Função | Contacto |
|--------|----------|
| **Responsável Técnico** | [A definir] |
| **Suporte VPS** | Contactar via cPanel ticket |
| **Suporte Easypay** | suporte@easypay.pt |
| **Suporte Cloudflare** | Via dashboard (ticket) |
| **Suporte Vercel** | Via dashboard (ticket) |

---

## Histórico de Versões

| Versão | Data | Alterações |
|--------|------|------------|
| 1.0 | 2025-11-25 | Documento inicial |

---

*Documento criado como parte do projeto Praia do Norte Unified Platform*
