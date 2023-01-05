#!/bin/sh

port=8000
ip=$(ip -o -4 a | awk '/^[0-9]+: (?:e|w)/' | sed 1q | awk '{print $4}' | cut -d/ -f1)
folder=$(git rev-parse --show-toplevel)

composer u

cd "$folder"/src/server || exit
go mod tidy
go run server.go &
cd "$folder" || exit
php -S "$ip":"$port" -t "$folder"/public
