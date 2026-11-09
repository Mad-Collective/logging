FROM php:8.4-fpm-alpine

RUN apk add --no-cache --virtual .build-deps \
        linux-headers \
    && docker-php-ext-install -j$(nproc) \
        sockets \
    && apk del .build-deps

RUN apk add --no-cache \
        curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

WORKDIR /app/logging
