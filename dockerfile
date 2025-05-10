FROM php:8.4-apache

# Install PDO MySQL driver
RUN docker-php-ext-install pdo_mysql