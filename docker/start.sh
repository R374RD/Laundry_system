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

php artisan config:cache

if ! php artisan route:cache; then
    echo "Route cache skipped. This app still has at least one closure route."
    php artisan route:clear
fi

php artisan view:cache

attempt=1
max_attempts="${DB_WAIT_MAX_ATTEMPTS:-20}"

until php artisan migrate --force; do
    if [ "$attempt" -ge "$max_attempts" ]; then
        echo "Database migrations failed after ${max_attempts} attempts."
        exit 1
    fi

    echo "Migration attempt ${attempt}/${max_attempts} failed. Retrying in 3 seconds..."
    attempt=$((attempt + 1))
    sleep 3
done

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
