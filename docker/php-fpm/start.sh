#!/bin/bash
set -e

DB_HOST=${DB_HOST:-db}
DB_PORT=${DB_PORT:-3306}

if [ -f /usr/local/bin/wait-for-it ]; then
    wait-for-it "${DB_HOST}":"${DB_PORT}" -t 30 || true
fi

mkdir -p /app/storage/logs
chown -R www-data:www-data /app/storage /app/bootstrap/cache 2>/dev/null || true

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
