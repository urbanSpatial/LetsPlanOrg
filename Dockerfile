FROM php:7.3-fpm-alpine3.13

# install additional system dependencies
RUN apk --update add --no-cache \
        bash \
        su-exec \
        multirun \
        nginx \
        postgresql-dev \
    && mkdir -p /run/nginx \
    && adduser nginx www-data \
    && docker-php-ext-install pdo_pgsql

COPY --from=ghcr.io/jarvusinnovations/tippecanoe-alpine:1.36.0 /usr/local/bin /usr/local/bin

# deploy application to /srv/app
WORKDIR /srv/app

# export app's default HTTP port
EXPOSE 80

# use multirun to manage multiple processes
ENTRYPOINT [ "/docker-entrypoint.sh" ]

# execute Laravel schedule runner every minute
RUN echo "* * * * * cd /srv/app && su-exec www-data php artisan schedule:run --verbose --no-interaction" > /etc/crontabs/root

# copy app source folder (less .dockerignore exclusions)
COPY --chown=root:www-data . /srv/app

# ensure permissions on writable directories
RUN chmod -R 775 storage bootstrap/cache \
    && chmod -R g+s storage bootstrap/cache

# interim mitigation for PHP CVE-2026-109619 (php:7.3 is EOL and unpatched)
RUN echo 'session.upload_progress.enabled = Off' > /usr/local/etc/php/conf.d/zz-cve-2026-109619.ini

# install nginx configuration and entrypoint
RUN mv /srv/app/nginx.conf /etc/nginx/conf.d/default.conf \
    && mv /srv/app/docker-entrypoint.sh /docker-entrypoint.sh
