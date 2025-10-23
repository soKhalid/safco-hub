#!/bin/bash

# SAFCO Media Hub Setup Script
# This script helps set up the application after installation

echo "======================================"
echo "SAFCO Media Hub - Setup Script"
echo "======================================"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
    echo "✓ .env file created"
else
    echo "✓ .env file already exists"
fi

# Install Composer dependencies
if [ -d "vendor" ]; then
    echo "✓ Vendor directory exists"
else
    echo "Installing Composer dependencies..."
    composer install
fi

# Install NPM dependencies
if [ -d "node_modules" ]; then
    echo "✓ Node modules exist"
else
    echo "Installing NPM dependencies..."
    npm install
fi

# Generate application key
echo ""
echo "Generating application key..."
php artisan key:generate

# Create storage link
echo ""
echo "Creating storage symbolic link..."
if [ -L "public/storage" ]; then
    echo "✓ Storage link already exists"
else
    php artisan storage:link
    echo "✓ Storage link created"
fi

# Create storage directories
echo ""
echo "Creating storage directories..."
mkdir -p storage/app/public/media/images
mkdir -p storage/app/public/media/videos
mkdir -p storage/app/public/media/documents
echo "✓ Storage directories created"

# Set permissions
echo ""
echo "Setting permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo "✓ Permissions set"

# Run migrations
echo ""
read -p "Do you want to run database migrations? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate
    echo "✓ Migrations completed"
fi

# Run seeders
echo ""
read -p "Do you want to seed the database with sample data? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan db:seed
    echo "✓ Database seeded"
    echo ""
    echo "Default login credentials:"
    echo "Admin: admin@safco.com / password"
    echo "User: user@safco.com / password"
fi

# Build assets
echo ""
read -p "Do you want to build frontend assets? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    npm run build
    echo "✓ Assets built"
fi

echo ""
echo "======================================"
echo "Setup Complete!"
echo "======================================"
echo ""
echo "Next steps:"
echo "1. Configure your database in .env"
echo "2. Run: php artisan serve"
echo "3. Visit: http://localhost:8000"
echo ""
echo "For manual storage link creation, run:"
echo "  php artisan storage:link"
echo ""
