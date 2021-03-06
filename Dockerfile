FROM php:7.4.19-fpm-alpine3.13

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --no-cache tzdata

ENV TZ Asia/Karachi

WORKDIR /var/www/html/

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

COPY . .

RUN composer install