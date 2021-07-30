#!/bin/bash

USER_ID=${USER_ID:-`id -u`}
GROUP_ID=${GROUP_ID:-`id -g`}

docker run -it --rm \
  --name async-test \
  --volume $PWD:/app \
  --user ${USER_ID}:${GROUP_ID} \
  -p 8080:8080 \
  local_php php /app/index.php
