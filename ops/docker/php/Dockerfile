FROM php:8.0-fpm-alpine

RUN docker-php-ext-install -j$(nproc) \
        sockets

RUN apk add --no-cache \
        curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

WORKDIR /app/logging
