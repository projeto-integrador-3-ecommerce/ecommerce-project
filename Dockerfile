FROM node:24-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

FROM php:8.3-apache-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libcurl4-openssl-dev \
        libonig-dev \
        libpq-dev \
        libxml2-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        ctype \
        curl \
        dom \
        fileinfo \
        mbstring \
        pdo_pgsql \
        xml \
        zip \
    && a2enmod headers rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .
COPY --from=frontend /app/public/build ./public/build
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

RUN composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!<Directory /var/www/>!<Directory /var/www/html/public/>!' /etc/apache2/apache2.conf \
    && sed -ri '/<Directory \/var\/www\/html\/public\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf \
    && chmod +x /usr/local/bin/docker-entrypoint.sh \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 10000

CMD ["/usr/local/bin/docker-entrypoint.sh"]
