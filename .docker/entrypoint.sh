#!/bin/sh
set -e

php artisan optimize

if [ "$RUN_MIGRATIONS" = "true" ]; then
    php artisan migrate --force
fi

exec "$@"
