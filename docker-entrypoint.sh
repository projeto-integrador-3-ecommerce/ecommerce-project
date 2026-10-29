#!/bin/sh
set -eu

port="${PORT:-10000}"

sed -ri "s/Listen 80/Listen ${port}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \\*:80>/<VirtualHost *:${port}>/" /etc/apache2/sites-available/000-default.conf

mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache

php artisan storage:link --force
php artisan migrate --force
php artisan config:cache

exec apache2-foreground
