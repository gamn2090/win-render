#!/bin/sh
set -e

echo "Linking storage..."
php artisan storage:link || true

echo "Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running migrations..."
php artisan deploy:fresh-seed

echo "Starting Apache..."
exec apache2-foreground