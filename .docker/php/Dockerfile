FROM php:8.0-fpm
MAINTAINER Thibaut Cholley <thibaut.cholley@omnilog.fr>

RUN apt-get update

# Enable php dev configuration
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini \
    && sed -i 's/memory_limit = 128M/memory_limit = -1/' /usr/local/etc/php/php.ini

# Add xdebug apcu opcache
RUN pecl install xdebug apcu \
    && docker-php-ext-enable xdebug apcu \
    && docker-php-ext-install opcache

# Add Composer
RUN apt-get install -y git unzip \
    && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Add pdo_mysql
RUN docker-php-ext-install pdo_mysql

# Add intl
RUN apt-get install -y zlib1g-dev libicu-dev g++ \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Add zip
RUN apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    && docker-php-ext-install zip

# Create dev user for specified uid:gid
ARG USER_ID=1000
ARG GROUP_ID=1000

RUN groupadd devdocker -g ${GROUP_ID} \
    && useradd -m -u ${USER_ID} -g ${GROUP_ID} dev

USER dev
