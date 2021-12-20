FROM php:fpm

RUN apt-get update
RUN pecl install xdebug && docker-php-ext-enable xdebug