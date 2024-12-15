FROM shinsenter/php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y git && \
    apt-get install -y libssl-dev zlib1g-dev curl git unzip libxml2-dev libpq-dev libzip-dev

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create user www-data and set permissions
USER root
RUN usermod -u 1000 www-data
RUN mkdir -p var/cache/prod var/cache/dev var/cache/test var/log \
   && chown -R www-data:www-data var/ \
   && chmod -R ug+rwX var/

# Set working directory
WORKDIR /var/www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
