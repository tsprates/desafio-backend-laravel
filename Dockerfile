FROM php:8.1-fpm-alpine

RUN mkdir -p /var/log/supervisor/

# dependencies
RUN apk update && apk add --no-cache $PHPIZE_DEPS \
    git \
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

# php-gd    
RUN apk add --no-cache zlib-dev libjpeg-turbo-dev libpng-dev freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# start supervisor and php-fpm
ENTRYPOINT supervisord -c /etc/supervisor/supervisord.conf && php-fpm -R -F
