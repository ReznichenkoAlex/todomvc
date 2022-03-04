FROM php:8.0.8-fpm-alpine

RUN apk update && apk add composer && apk add bash && apk add yarn

WORKDIR /var/www/html

CMD ["php-fpm"]