#!/bin/sh
set -eu

cd /app

if [ -z "${APP_KEY:-}" ]; then
    export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
    echo "APP_KEY was not set. Generated an ephemeral key for this container."
    echo "Set a persistent APP_KEY in your deploy environment to keep sessions stable across restarts."
fi

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true

if [ ! -L public/storage ]; then
    php artisan storage:link || true
fi

if [ "${DB_CONNECTION:-}" = "mysql" ] && [ -n "${DB_HOST:-}" ]; then
    echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT:-3306}..."
    i=1
    while [ "$i" -le 24 ]; do
        if php artisan migrate --force; then
            break
        fi

        if [ "$i" -eq 24 ]; then
            echo "MySQL did not become ready in time."
            exit 1
        fi

        echo "MySQL not ready yet, retrying in 5 seconds..."
        i=$((i + 1))
        sleep 5
    done
fi

php artisan config:cache

if ! php artisan route:cache; then
    echo "Route cache skipped. This app still has at least one closure route."
    php artisan route:clear
fi

php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
