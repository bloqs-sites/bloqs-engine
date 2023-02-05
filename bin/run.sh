#!/bin/sh

CONF=$(mktemp)

printf "[dn]\nCN=localhost\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:localhost\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth" > "$CONF"

openssl req -x509 -out localhost.crt -keyout localhost.key \
  -newkey rsa:2048 -nodes -sha256 \
  -subj '/CN=localhost' -extensions EXT -config "$CONF"

rm "$CONF"

port=8000
ip=$(ip -o -4 a | awk '/^[0-9]+: (?:e|w)/' | sed 1q | awk '{print $4}' | cut -d/ -f1)
folder=$(git rev-parse --show-toplevel)

npm i
npm run css
composer u

cd "$folder"/src/server || exit
go mod download
go mod tidy
go run server.go &
cd "$folder" || exit
php -S "$ip":"$port" -t "$folder"/public
# php -S "$ip":"$port" "$folder"/public/index.php
