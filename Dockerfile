# =========================
# Stage 1: Build frontend
# =========================
FROM node:22 AS frontend

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY resources ./resources
COPY vite.config.js ./
COPY postcss.config.js ./
COPY tailwind.config.js ./

RUN npm run build


# =========================
# Stage 2: Laravel + Apache
# =========================
FROM php:8.3-apache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# PHP dependencies
RUN apt-get update \
    && apt-get install -y libzip-dev unzip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Apache rewrite
RUN a2enmod rewrite \
    && a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork

# Disable opcache timestamp revalidation
COPY docker/opcache.ini /usr/local/etc/php/conf.d/zz-snapbook-opcache.ini

# Raise file upload limits
COPY docker/uploads.ini /usr/local/etc/php/conf.d/zz-snapbook-uploads.ini
COPY docker/entrypoint.sh /usr/local/bin/snapbook-entrypoint
RUN chmod +x /usr/local/bin/snapbook-entrypoint
WORKDIR /var/www/html

# Copy Laravel application
COPY . .

# Copy production Vite assets
COPY --from=frontend /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Laravel public directory
RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

    ENTRYPOINT ["snapbook-entrypoint"]
    CMD ["apache2-foreground"]

EXPOSE 80