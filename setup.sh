#!/bin/bash

# Health Card System Setup Script
# This script helps set up the Health Card Management System

echo "🏥 Health Card Management System Setup"
echo "======================================"

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.2 or higher."
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "✅ PHP version: $PHP_VERSION"

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer."
    exit 1
fi

echo "✅ Composer is installed"

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "❌ Node.js is not installed. Please install Node.js."
    exit 1
fi

echo "✅ Node.js is installed"

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
npm install

# Check if .env file exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "✅ .env file created. Please update database credentials."
else
    echo "✅ .env file already exists"
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link

# Ask about database setup
echo ""
echo "🗄️  Database Setup"
echo "=================="
read -p "Do you want to run migrations and seeders? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🔄 Running migrations..."
    php artisan migrate
    
    echo "🌱 Running seeders..."
    php artisan db:seed
    
    echo "✅ Database setup completed!"
    echo ""
    echo "🔐 Default Login Credentials:"
    echo "============================="
    echo "Admin: admin@healthcardsystem.com / admin123"
    echo "Staff: john.smith@healthcardsystem.com / staff123"
    echo "Hospital: info@apollohospitals.com / apollo123"
    echo "User: test@example.com / password"
else
    echo "⏭️  Skipping database setup. Run 'php artisan migrate --seed' when ready."
fi

# Compile assets
echo ""
echo "🎨 Compiling assets..."
npm run build

echo ""
echo "🎉 Setup completed successfully!"
echo ""
echo "🚀 To start the development server, run:"
echo "   php artisan serve"
echo ""
echo "🌐 Then visit: http://localhost:8000"
echo ""
echo "📚 For more information, check the README.md file"
