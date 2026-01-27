#!/bin/sh

PHP_CMD="$1"
# Check if php-{version} exists, otherwise use php
if [ -n "$PHP_CMD" ] && ! command -v "$PHP_CMD" > /dev/null 2>&1; then
    PHP_CMD="php"
fi

printf "\Running integration tests in PHP $1\n"
$1 ./bin/behat