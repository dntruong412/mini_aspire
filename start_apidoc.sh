#!/bin/bash

cd src/api/doc

rm -rf public/* node_modules/*
npm install

npm run build
npm run watch
