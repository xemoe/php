#!/bin/bash

if ! type realpath &>/dev/null
then
    sudo apt-get update && sudo apt-get install realpath -y;
fi

SCRIPT_PATH=$(dirname $(realpath -s $0))

PHPDBG=/usr/bin/phpdbg
PHPUNIT=${SCRIPT_PATH}/vendor/bin/phpunit

if php -v | grep -i "php 7" &>/dev/null
then
    ${PHPDBG} -qrr ${PHPUNIT} "$@"
else
    ${PHPUNIT} "$@"
fi

