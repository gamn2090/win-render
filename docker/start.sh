#!/bin/sh
set -e

echo "Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running migrations..."
php artisan migrate:fresh --force
php artisan db:seed --force

echo "Starting Apache..."
exec apache2-foreground