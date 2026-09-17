# Step 1: Build frontend assets using Node
FROM node:20 AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Step 2: Configure PHP 8.4 & Apache for Laravel
FROM php:8.4-apache

# Install system dependencies & PHP extensions needed for Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    sqlite3 \
    libsqlite3-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_sqlite pdo_mysql zip

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy built frontend assets from step 1
COPY --from=frontend /app/public/build ./public/build

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Configure Apache DocumentRoot to point to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80 8080 10000

CMD ["sh", "-c", "PORT=${PORT:-8080} && sed -i \"s/80/$PORT/g\" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && mkdir -p database storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache && touch database/database.sqlite && chown -R www-data:www-data /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache && chmod -R 777 /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache && if [ -z \"$APP_KEY\" ]; then php artisan key:generate --force; fi && php artisan optimize:clear && php artisan migrate --force && php artisan db:seed --force && apache2-foreground"]
