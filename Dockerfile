FROM php:8.3-apache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# PHP dependencies
RUN apt-get update \
    && apt-get install -y libzip-dev unzip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Apache rewrite
RUN a2enmod rewrite

# Disable opcache timestamp revalidation (slow per-request stat on Windows bind mounts)
COPY docker/opcache.ini /usr/local/etc/php/conf.d/zz-snapbook-opcache.ini

# Raise file upload limits (5MB per file, 64MB per request body)
COPY docker/uploads.ini /usr/local/etc/php/conf.d/zz-snapbook-uploads.ini

WORKDIR /var/www/html

# Copy Laravel application
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Laravel public directory
RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80