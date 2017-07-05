#!/bin/bash

printf "\Running integration tests in PHP $1\n"
$1 ./bin/behat
