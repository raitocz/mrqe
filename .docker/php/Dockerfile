FROM php:8.0.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y git

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install

# Set working directory
WORKDIR /app