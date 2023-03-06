FROM php:8.1-fpm

RUN apt-get update && apt-get install -y supervisor git libpq-dev

# clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install pcntl

# postgres
RUN docker-php-ext-install pdo pdo_pgsql

# redis
RUN pecl install redis \
    && docker-php-ext-enable redis

RUN supervisord -c /etc/supervisor/supervisord.conf

