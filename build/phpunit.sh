#!/bin/bash

if ! type realpath &>/dev/null
then
    sudo apt-get update && sudo apt-get install realpath -y;
fi

SCRIPT_PATH=$(dirname $(realpath -s $0))

PHPDBG=/usr/bin/phpdbg
PHPUNIT=${SCRIPT_PATH}/vendor/bin/phpunit

${PHPDBG} -qrr ${PHPUNIT} "$@"
