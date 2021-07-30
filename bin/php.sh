#!/bin/bash

USER_ID=${USER_ID:-`id -u`}
GROUP_ID=${GROUP_ID:-`id -g`}

docker run --rm -d \
  --volume $PWD:/app \
  --user ${USER_ID}:${GROUP_ID} \
  -p 8080:8080 \
  php:7.4 php "$@"
