FROM php:8.0.8-fpm-alpine

RUN apk update && apk add composer && apk add bash && apk add yarn && apk add php8-pdo_mysql && cp /etc/php8/php.ini /usr/local/etc/php/ && echo "extension=/usr/lib/php8/modules/pdo_mysql.so" >> /usr/local/etc/php/php.ini

WORKDIR /var/www/html

CMD ["php-fpm"]