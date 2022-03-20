#!/bin/sh

echo "Run composer"
composer install

echo "Node packages install"
yarn install

php vendor/bin/doctrine orm:schema-tool:update -f
php vendor/bin/doctrine orm:generate-proxies

php-fpm