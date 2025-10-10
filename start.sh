#!/bin/bash

echo "Starting Health Card Management System..."
echo

# Check if .env file exists
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    php artisan key:generate
fi

# Install dependencies
echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Seed database
echo "Seeding database..."
php artisan db:seed --force

# Clear caches
echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo
echo "Starting Laravel development server..."
echo "Application will be available at: http://127.0.0.1:8000"
echo
echo "Press Ctrl+C to stop the server"
echo

php artisan serve --host=127.0.0.1 --port=8000
