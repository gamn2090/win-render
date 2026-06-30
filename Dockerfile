# Laravel 10 + Vite para Render cuando Language no ofrece PHP nativo (usar Language = Docker).
FROM php:8.2-cli-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libzip-dev libpq-dev libicu-dev libpng-dev libonig-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl pdo_pgsql mbstring zip exif pcntl bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# vendor/ debe existir antes de artisan (hay que instalar sin scripts que invoquen artisan sin APP_KEY).
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

RUN cp .env.example .env \
    && php artisan key:generate --force

RUN php artisan package:discover --ansi || true

RUN npm ci \
    && npm run build \
    && rm -f public/hot

# Perfiles usan asset('/storage/images/...'): hace falta el symlink public/storage y un user.jpg por defecto (no está en Git por .gitignore).
RUN mkdir -p storage/app/public/images \
    && php -r 'file_put_contents("storage/app/public/images/user.jpg", base64_decode("/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDAREAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAX/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAGf/AP/Z"));' \
    && php artisan storage:link \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache \
    && rm -f .env

ENV PORT=10000
EXPOSE 10000

# SnapshotSeeder es idempotente (registra el hash del manifest en
# snapshot_imports), por lo que es seguro correrlo en cada boot del container.
# Solo importa cuando el snapshot cambia. Para forzar reimportacion, definir
# SNAPSHOT_FORCE_REIMPORT=true en Environment.
CMD sh -c "php artisan storage:link 2>/dev/null; php artisan migrate --force && echo '>> Running SnapshotSeeder...' && php artisan db:seed --class=SnapshotSeeder --force && php artisan serve --host=0.0.0.0 --port=${PORT}"
