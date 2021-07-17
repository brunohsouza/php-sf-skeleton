FROM php:8.0-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y git && \
    apt-get install -y libssl-dev zlib1g-dev curl git unzip netcat libxml2-dev libpq-dev libzip-dev && \
    pecl install apcu && \
    docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install -j$(nproc) zip opcache intl pdo_pgsql pgsql bcmath && \
    docker-php-ext-enable apcu pdo_pgsql sodium && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

EXPOSE 9000
