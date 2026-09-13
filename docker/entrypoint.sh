#!/bin/sh

set -e

if [ -n "${AIVEN_CA_CERT:-}" ]; then
    printf '%s\n' "$AIVEN_CA_CERT" > /tmp/aiven-ca.pem
    chmod 644 /tmp/aiven-ca.pem
fi

exec docker-php-entrypoint "$@"