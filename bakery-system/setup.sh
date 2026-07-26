#!/bin/bash

# Setup Script untuk Sistem Manajemen Toko Roti
# Jalankan dengan: bash setup.sh

echo "🍞 Setting up Bakery Management System..."
echo ""

# Check if composer is installed
if ! command -v composer &> /dev/null
then
    echo "❌ Composer not found. Please install Composer first."
    exit 1
fi

# Check if npm is installed
if ! command -v npm &> /dev/null
then
    echo "❌ NPM not found. Please install Node.js and NPM first."
    exit 1
fi

echo "✅ Composer and NPM found"
echo ""

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Install NPM dependencies
echo "📦 Installing NPM dependencies..."
npm install

# Copy .env if not exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Run migrations and seeders
echo "🗄️  Running migrations and seeders..."
php artisan migrate:fresh --seed

# Build assets
echo "🎨 Building frontend assets..."
npm run build

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo ""
echo "✨ Setup completed successfully!"
echo ""
echo "📋 Default Accounts:"
echo "   Owner: owner@bakery.com / password"
echo "   Employee: employee@bakery.com / password"
echo ""
echo "🚀 To start the development server, run:"
echo "   php artisan serve"
echo ""
echo "🌐 Then open http://localhost:8000 in your browser"
echo ""
