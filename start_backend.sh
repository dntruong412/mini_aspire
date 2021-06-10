#!/bin/bash

port=$(awk 'BEGIN {FS="="}; /BACKEND_PORT/ {print $2}' .env)

cd src/backend

npm run serve -- --port $port
