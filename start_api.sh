#!/bin/bash

env="local"
port=$(awk 'BEGIN {FS="="}; /API_PORT/ {print $2}' .env)

rm -rf _logs/*/*
rm -f _data/db/*-slow.log

docker compose down --remove-orphans
docker compose up -d db phpmyadmin

cd src/api
rm -f storage/logs/*.log
rm -f storage/logs/*.log.gz
php artisan migrate:refresh --seed --env=$env
php artisan view:clear --env=$env
php artisan cache:clear --env=$env
php artisan serve --env=$env --port=$port
