# STAGE 1: Build stage
FROM composer:2 AS build

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of the application
COPY . .

# STAGE 2: Runtime stage
FROM php:8.2-fpm

WORKDIR /app

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip git libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring gd zip bcmath

# Copy built Laravel files
COPY --from=build /app /app

# Expose port
EXPOSE 8000

# Start Laravel server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
