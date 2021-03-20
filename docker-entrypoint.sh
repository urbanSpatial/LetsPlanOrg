#!/bin/bash

# initialize Laraver config cache from runtime environment
gosu www-data php artisan config:cache

# each arg to multirun is a command to run in parallel:
COMMANDS=("php-fpm")

# disable ngin's daemon mode so multirun can manage it
COMMANDS+=("nginx -g 'daemon off;'")

# use gosu to run Laravel worker as www-data user and not mangle file ownership
COMMANDS+=("gosu www-data php artisan queue:work --tries=3")

# run crond in foreground so multirun can manage it
COMMANDS+=("crond -f -l 5")

# use multirun to manage multiple processes
multirun -v "${COMMANDS[@]}"
