FROM php:7.3.12-apache

RUN apt-get update \
    && apt-get install -y zip unzip libzip-dev \
    && docker-php-ext-install zip mysqli pdo pdo_mysql \
    && a2enmod rewrite