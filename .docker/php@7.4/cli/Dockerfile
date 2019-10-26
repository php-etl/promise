FROM kiboko/php:7.4-cli-blackfire

LABEL maintainer="Grégory Planchat <gregory@kiboko.fr>"

RUN set -ex \
    && docker-php-ext-configure pcntl \
    && docker-php-ext-install pcntl

COPY config/security.ini /usr/local/etc/php/conf.d/security.ini

WORKDIR /var/www/html
