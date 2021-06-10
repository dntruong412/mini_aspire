#!/bin/bash

env="local"

cd src/api
php artisan test --env=$env
