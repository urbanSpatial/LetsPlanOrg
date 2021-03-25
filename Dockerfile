FROM php:7.3-fpm-alpine3.13

# install additional system dependencies
RUN echo "http://dl-cdn.alpinelinux.org/alpine/edge/testing" >> /etc/apk/repositories \
    && apk --update add --no-cache \
        bash \
        gosu \
        multirun \
        nginx \
        postgresql-dev \
    && mkdir -p /run/nginx \
    && adduser nginx www-data \
    && docker-php-ext-install pdo_pgsql

# deploy application to /srv/app
WORKDIR /srv/app

# export app's default HTTP port
EXPOSE 80

# use multirun to manage multiple processes
ENTRYPOINT [ "/docker-entrypoint.sh" ]

# execute Laravel schedule runner every minute
RUN echo "* * * * * cd /srv/app && gosu www-data php artisan schedule:run --verbose --no-interaction" > /etc/crontabs/root

# copy app source folder (less .dockerignore exclusions)
COPY --chown=root:www-data . /srv/app

# ensure permissions on writable directories
RUN chmod -R 775 storage bootstrap/cache \
    && chmod -R g+s storage bootstrap/cache

# install nginx configuration and entrypoint
RUN mv /srv/app/nginx.conf /etc/nginx/conf.d/default.conf \
    && mv /srv/app/docker-entrypoint.sh /docker-entrypoint.sh
