FROM php:8.0.8-fpm-alpine

RUN apk update && apk add composer && apk add bash && apk add yarn && apk add php8-pdo_mysql && echo "extension=/usr/lib/php8/modules/pdo_mysql.so" >> /usr/local/etc/php/php.ini && echo "display_errors=On" >> /usr/local/etc/php/php.ini

COPY ./entry.sh /entry.sh
RUN chmod +x /entry.sh

WORKDIR /var/www/html

ENTRYPOINT ["bash", "/entry.sh"]