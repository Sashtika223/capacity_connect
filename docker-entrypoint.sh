#!/bin/bash

# Default PORT if not provided by Render
PORT="${PORT:-8080}"
echo "Configuring Apache to listen on port ${PORT}..."

# Update Apache port configuration
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-available/000-default.conf || true
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf || true

# Ensure required directories exist and have proper permissions
mkdir -p database storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
touch database/database.sqlite
chown -R www-data:www-data /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache

# Generate APP_KEY if missing
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY is missing. Generating application key..."
    php artisan key:generate --force || true
fi

# Clear any cached configuration/routes/views
echo "Clearing Laravel caches..."
php artisan optimize:clear || true

# Run database migrations and seeders
echo "Running database migrations..."
php artisan migrate --force || true

echo "Running database seeders..."
php artisan db:seed --force || true

# Start Apache in foreground
echo "Starting Apache web server on port ${PORT}..."
exec apache2-foreground
