FROM php:8.4-apache

# Install PDO MySQL driver
RUN docker-php-ext-install pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install tools needed by Composer
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    git