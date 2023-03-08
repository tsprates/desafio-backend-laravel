FROM php:8.1-fpm

# dependencies
RUN apt-get update && apt-get install -y git \
    zip \
    libpq-dev \
    supervisor

# composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install pcntl

# postgres
RUN docker-php-ext-install pdo pdo_pgsql

# redis
RUN pecl install redis && docker-php-ext-enable redis
