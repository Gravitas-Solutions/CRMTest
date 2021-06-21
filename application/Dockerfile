FROM php:7.2-apache

RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install zip


RUN docker-php-ext-install mysqli pdo_mysql

RUN a2enmod rewrite
RUN service apache2 restart
RUN mkdir /app \
&& mkdir /app/crm_test\
&& mkdir /app/crm_test/www

COPY . /app/crm_test/www/

RUN cp -r /app/crm_test/www/* /var/www/html/.