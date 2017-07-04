#!/bin/bash

printf "\nRunning unit tests in PHP ${PHP_VERSION}\n"
${PHP_VERSION} ./bin/phpspec run
