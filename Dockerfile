FROM php:8.1.6-fpm-alpine as base
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN apk update && apk upgrade && apk add --no-cache libxml2-dev bzip2-dev libpng-dev libpq curl mariadb ${PHPIZE_DEPS}
RUN docker-php-ext-install dom
RUN docker-php-ext-install bz2
RUN docker-php-ext-install gd
RUN docker-php-ext-install fileinfo
RUN docker-php-ext-install xml
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install pdo
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql