FROM php:8.3.0-fpm-alpine

RUN apk add --no-cache \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
