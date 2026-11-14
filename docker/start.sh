#!/usr/bin/env bash
set -e

# Render can generate a raw base64 256-bit value; Laravel expects the "base64:" prefix.
if [ -n "${APP_KEY:-}" ] && [ "${APP_KEY#base64:}" = "${APP_KEY}" ]; then
    export APP_KEY="base64:${APP_KEY}"
fi

# Render forwards traffic to $PORT (10000 by default).
export PORT="${PORT:-10000}"
sed "s/__PORT__/${PORT}/g" /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

cd /var/www/html

# SQLite needs an existing file (useful for local Docker runs and disk-backed deploys).
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ] && [ "${DB_DATABASE:-}" != ":memory:" ]; then
    db_path="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
    touch "$db_path"
    chown www-data:www-data "$db_path"
    chmod 664 "$db_path"
    chmod 775 "$(dirname "$db_path")"
fi

php artisan config:cache
php artisan view:cache

# Cached files are written as root; hand them back to php-fpm.
chown -R www-data:www-data storage bootstrap/cache

# Wait for the database to accept connections, then migrate.
attempt=0
until php artisan migrate --force; do
    attempt=$((attempt + 1))
    if [ "$attempt" -ge 30 ]; then
        echo "Database unreachable after 30 attempts, giving up." >&2
        exit 1
    fi
    echo "Waiting for the database (attempt ${attempt}/30)..."
    sleep 3
done

# Seed demo data only when the database is still empty.
user_count="$(php artisan tinker --execute='echo \App\Models\User::count();' 2>/dev/null | tr -dc '0-9')"
if [ "${user_count:-0}" = "0" ]; then
    php artisan db:seed --force
fi

exec /usr/bin/supervisord -n -c /etc/supervisord.conf
