#!/bin/bash

docker exec -it safeshare-php bash -c "cd /var/www/web/apps/common && composer $@"