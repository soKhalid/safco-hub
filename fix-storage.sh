#!/bin/bash

# Quick fix for storage link issue
echo "SAFCO Media Hub - Storage Link Fix"
echo "===================================="
echo ""

# Remove existing link if it exists
if [ -L "public/storage" ]; then
    echo "Removing existing storage link..."
    rm public/storage
fi

# Create storage link
echo "Creating storage symbolic link..."
php artisan storage:link

# Create directories
echo "Creating storage directories..."
mkdir -p storage/app/public/media/images
mkdir -p storage/app/public/media/videos
mkdir -p storage/app/public/media/documents

# Set permissions
echo "Setting permissions..."
chmod -R 775 storage/app/public

echo ""
echo "✓ Storage link fixed!"
echo ""
echo "Your uploaded images should now be visible."
echo ""
