# ============================================================
# Praia do Norte — Production Dockerfile
# Multi-stage: Composer (PHP deps) → Node (assets) → Runtime
# ============================================================

# ── Stage 1: Install PHP dependencies ───────────────────────
FROM composer:2 AS composer-builder

# Install intl extension required by Filament
RUN apk add --no-cache icu-dev \
    && docker-php-ext-install intl

WORKDIR /build

COPY backend/composer.json backend/composer.lock* ./

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# ── Stage 2: Build Vite assets ──────────────────────────────
FROM node:20-alpine AS node-builder

WORKDIR /build

COPY backend/package.json backend/package-lock.json* ./
RUN npm ci

COPY backend/vite.config.js ./
COPY backend/resources ./resources
# Tailwind v4 scans PHP/Blade files for class detection
COPY backend/app ./app

# Filament admin CSS imports from vendor — copy from composer stage
COPY --from=composer-builder /build/vendor ./vendor

RUN npm run build

# ── Stage 3: Production runtime ─────────────────────────────
FROM php:8.4-fpm-alpine

# Install system dependencies + PHP extensions
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

# PHP production config
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

# Nginx config
COPY <<'NGINX' /etc/nginx/http.d/default.conf
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php;

    client_max_body_size 64M;

    # Security headers
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "0" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Permissions-Policy "camera=(), microphone=(), geolocation=()" always;

    # Vite-built assets (hash-versioned, cache forever)
    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    # Livewire assets — try static first, fallback to PHP route
    # ^~ prevents regex locations (like the static file handler) from overriding
    location ^~ /livewire/ {
        try_files $uri /index.php?$query_string;
    }

    # Static files
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

    # Block hidden files (except .well-known for Let's Encrypt)
    location ~ /\.(?!well-known) {
        deny all;
    }
}
NGINX

# Supervisor config (PHP-FPM + Nginx + Queue Worker)
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

[program:db-seed]
command=php /var/www/html/artisan db:seed --force --no-interaction
autostart=true
autorestart=false
exitcodes=0,1
startsecs=0
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:content-download]
command=sh -c "sleep 30 && php /var/www/html/artisan app:download-documents --no-interaction && php /var/www/html/artisan app:download-corporate-bodies --no-interaction"
autostart=true
autorestart=false
exitcodes=0,1
startsecs=0
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
SUPERVISOR

WORKDIR /var/www/html

# Copy Laravel source
COPY backend/ .

# Copy Composer dependencies
COPY --from=composer-builder /build/vendor ./vendor

# Copy compiled Vite assets
COPY --from=node-builder /build/public/build ./public/build

# Note: livewire:publish --assets runs in entrypoint (needs .env/APP_KEY)

# Set permissions for storage and cache
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

# Entrypoint: create .env from Docker env vars before starting services
# Coolify injects env vars via Docker, but artisan commands need .env file
COPY <<'ENTRYPOINT' /usr/local/bin/entrypoint.sh
#!/bin/sh
set -e

echo "==> Creating .env from Docker environment variables..."
printenv | grep -E '^(APP_|DB_|LOG_|CACHE_|SESSION_|QUEUE_|FILESYSTEM_|VITE_|MAIL_|REDIS_|BROADCAST_|WOOCOMMERCE_)' | sort | sed 's/=\(.*\)/="\1"/' > /var/www/html/.env

echo "==> Fixing storage permissions after volume mount..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "==> Running database migrations..."
php /var/www/html/artisan migrate --force --no-interaction 2>&1 || {
  echo "WARNING: Migration failed, retrying in 5s..."
  sleep 5
  php /var/www/html/artisan migrate --force --no-interaction 2>&1 || echo "ERROR: Migration failed after retry"
}

echo "==> Ensuring maintenance mode is active..."
php /var/www/html/artisan tinker --execute="\\App\\Models\\SiteSetting::firstOrCreate(['key' => 'maintenance_mode'], ['value' => '1']);" 2>&1 || true

echo "==> Creating storage symlink..."
php /var/www/html/artisan storage:link --force 2>&1 || true

echo "==> Caching configuration..."
php /var/www/html/artisan config:cache 2>&1 || true
php /var/www/html/artisan event:cache 2>&1 || true
php /var/www/html/artisan view:cache 2>&1 || true

echo "==> Publishing Livewire assets..."
php /var/www/html/artisan livewire:publish --assets 2>&1 || true

echo "==> Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
ENTRYPOINT
RUN chmod +x /usr/local/bin/entrypoint.sh

CMD ["/usr/local/bin/entrypoint.sh"]
