#!/bin/sh
set -eu

cd /app

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true

if [ ! -L public/storage ]; then
    php artisan storage:link || true
fi

php artisan config:cache

if ! php artisan route:cache; then
    echo "Route cache skipped. This app still has at least one closure route."
    php artisan route:clear
fi

php artisan view:cache
php artisan migrate --force

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
