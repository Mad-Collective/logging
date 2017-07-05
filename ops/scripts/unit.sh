#!/bin/bash

printf "\nRunning unit tests in PHP $1\n"
$1 ./bin/phpspec run
