# Dev image: php artisan serve. Code is bind-mounted by docker-compose.yml,
# not baked in here — run `composer install` inside the container after `up`.
FROM php:8.2-cli-bookworm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libzip-dev libpq-dev libicu-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j$(nproc) intl pdo_pgsql mbstring zip exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# PHP's compiled-in defaults (post_max_size=8M, max_file_uploads=20) are too
# small for a vendor uploading a batch of portfolio photos — the request
# silently drops the whole $_FILES/$_POST payload past that size, with no
# error surfaced to the app, which looked like "uploads over ~10 images
# just break." Raised comfortably above what a full-quality photo batch needs.
RUN { \
    echo 'upload_max_filesize=20M'; \
    echo 'post_max_size=100M'; \
    echo 'max_file_uploads=30'; \
    } > /usr/local/etc/php/conf.d/uploads.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 8000
