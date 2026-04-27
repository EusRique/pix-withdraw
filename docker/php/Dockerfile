FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    && docker-php-ext-install pdo pdo_mysql pcntl

# Instala Swoole
RUN pecl install swoole && docker-php-ext-enable swoole

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

CMD ["tail", "-f", "/dev/null"]