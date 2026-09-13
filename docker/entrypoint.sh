#!/bin/sh

set -e

# Fix Apache MPM conflict on Railway
a2dismod mpm_event 2>/dev/null || true
a2dismod mpm_worker 2>/dev/null || true
a2dismod mpm_prefork 2>/dev/null || true

rm -f /etc/apache2/mods-enabled/mpm_event.*
rm -f /etc/apache2/mods-enabled/mpm_worker.*
rm -f /etc/apache2/mods-enabled/mpm_prefork.*

a2enmod mpm_prefork

# Create Aiven CA certificate
if [ -n "${AIVEN_CA_CERT:-}" ]; then
    printf '%s\n' "$AIVEN_CA_CERT" > /tmp/aiven-ca.pem
    chmod 644 /tmp/aiven-ca.pem
fi

# Production deploy init: framework caches + public storage symlink.
# Runs only in the real app container (Apache start) under APP_ENV=production.
# Local docker compose never exports APP_ENV, so dev startup is unchanged.
if [ "$APP_ENV" = "production" ] && [ "$1" = apache2-foreground ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan storage:link
    chown -R www-data:www-data /var/www/html/storage/app/public
    chmod -R 775 /var/www/html/storage/app/public
fi

exec docker-php-entrypoint "$@"