#!/bin/sh

set -xe

folder=$(git rev-parse --show-toplevel)

if [ "$1" = "--init" ]; then
CONF=$(mktemp)

printf "[dn]\nCN=localhost\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:localhost\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth" > "$CONF"

openssl req -x509 -out "$folder"/localhost.crt -keyout "$folder"/localhost.key \
  -newkey rsa:2048 -nodes -sha256 \
  -subj '/CN=localhost' -extensions EXT -config "$CONF"

rm "$CONF"

pnpx npm-check-updates -u
pnpm up
pnpm css
composer u
fi

port=8000
ip=$(ip -o -4 a | awk '/^[0-9]+: (?:e|w)/' | sed 1q | awk '{print $4}' | cut -d/ -f1)

cd "$folder" || exit
php -S "$ip":"$port" -t "$folder"/public
# php -S "$ip":"$port" "$folder"/public/index.php
