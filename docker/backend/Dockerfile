FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpq-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./backend /var/www/html

COPY ./docker/backend/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

RUN composer install

CMD ["sh", "/usr/local/bin/entrypoint.sh"]
