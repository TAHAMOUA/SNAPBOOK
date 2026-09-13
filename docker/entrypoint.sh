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

exec docker-php-entrypoint "$@"