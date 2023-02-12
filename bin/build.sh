#!/bin/sh

folder=$(git rev-parse --show-toplevel)

npm i
npm run css
composer u

cd "$folder"/src/server || exit
go mod download
go mod tidy
