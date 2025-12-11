FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY ./www /var/www/html

RUN composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data var/www/html
	
CMD ["php-fpm"]
