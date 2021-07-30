#!/bin/bash

USER_ID=${USER_ID:-`id -u`}
GROUP_ID=${GROUP_ID:-`id -g`}

docker run --rm --interactive --tty \
  --volume $PWD:/app \
  --user ${USER_ID}:${GROUP_ID} \
  php:7.4 php "$@"
