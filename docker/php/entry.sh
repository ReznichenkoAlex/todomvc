#!/bin/sh

echo "Run composer"
composer install

echo "Node packages install"
yarn install

until nc -z -v -w30 mysql 3306
    do
        echo "Waiting for database connection..."
        # wait for 5 seconds before check again
        sleep 5
    done

php vendor/bin/doctrine orm:schema-tool:update -f
php vendor/bin/doctrine orm:generate-proxies

php-fpm