#!/bin/bash

printf "\Running integration tests in PHP ${PHP_VERSION}\n"
${PHP_VERSION} ./bin/behat
