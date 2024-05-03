FROM php:8.0-apache

RUN apt-get update

RUN apt-get install -y libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql

COPY ./ /var/www/html/

RUN mkdir -p /var/www/html/uploads && chmod -R 777 /var/www/html/uploads

EXPOSE 80