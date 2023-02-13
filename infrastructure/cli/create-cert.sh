#!/bin/bash

set -e

source "../../.env"

DOMAIN=$(echo "$DOMAIN_CURRENT_SITE")

mkcert -install "${DOMAIN_CURRENT_SITE}"

mkdir -p ../certs

find . -type f -name "*.pem" -exec mv {} ../certs \;
