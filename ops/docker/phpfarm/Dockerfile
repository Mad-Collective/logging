FROM splitbrain/phpfarm:latest

WORKDIR /app/logging
VOLUME ["/app/logging"]

RUN curl -sS https://getcomposer.org/installer | php-5.5 && mv /app/logging/composer.phar /usr/bin/composer