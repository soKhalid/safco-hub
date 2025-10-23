# SAFCO Media Hub - Windows Setup Guide

Complete setup guide for Windows users using PowerShell.

## Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL or MariaDB
- Node.js and NPM
- Git

## Step-by-Step Installation

### 1. Clone or Download Repository

```powershell
cd C:\Users\YourUsername
git clone <repository-url> safco-hub
cd safco-hub
```

### 2. Install Dependencies

```powershell
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup

```powershell
# Copy .env.example to .env
Copy-Item .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

Open `.env` file in a text editor and update:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=safco_media_hub
DB_USERNAME=root
DB_PASSWORD=your_mysql_password_here

# IMPORTANT: Use file cache, not database
CACHE_DRIVER=file
FILESYSTEM_DISK=public
```

### 5. Create Database

**Option A: Using MySQL Command Line**
```powershell
mysql -u root -p
# Enter your password, then:
CREATE DATABASE safco_media_hub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

**Option B: Using phpMyAdmin**
- Open phpMyAdmin
- Click "New" in the left sidebar
- Database name: `safco_media_hub`
- Collation: `utf8mb4_unicode_ci`
- Click "Create"

### 6. Create Required Directories

```powershell
# Create all required Laravel directories
New-Item -ItemType Directory -Force -Path bootstrap\cache
New-Item -ItemType Directory -Force -Path storage\framework\sessions
New-Item -ItemType Directory -Force -Path storage\framework\views
New-Item -ItemType Directory -Force -Path storage\framework\cache\data
New-Item -ItemType Directory -Force -Path storage\logs
New-Item -ItemType Directory -Force -Path storage\app\public\media\images
New-Item -ItemType Directory -Force -Path storage\app\public\media\videos
New-Item -ItemType Directory -Force -Path storage\app\public\media\documents
```

### 7. Run Database Migrations

```powershell
# Create all database tables
php artisan migrate

# Seed database with initial data (admin user + categories)
php artisan db:seed
```

**Default Login Credentials:**
- Admin: `admin@safco.com` / `password`
- User: `user@safco.com` / `password`

### 8. Create Storage Link

```powershell
# Create symbolic link for file uploads
php artisan storage:link
```

**If you get a permission error:**
- Run PowerShell as Administrator
- Or manually create junction: `New-Item -ItemType Junction -Path public\storage -Target storage\app\public`

### 9. Build Frontend Assets

```powershell
# For development
npm run dev

# For production
npm run build
```

### 10. Start Development Server

```powershell
php artisan serve
```

Visit: `http://localhost:8000`

## Common Windows Errors & Fixes

### Error: "bootstrap\cache directory must be present"

**Fix:**
```powershell
New-Item -ItemType Directory -Force -Path bootstrap\cache
php artisan cache:clear
```

### Error: "Base table or view not found: cache"

**Fix:** Update `.env`:
```env
CACHE_DRIVER=file
```
Then run:
```powershell
php artisan config:clear
```

### Error: "symlink() has been disabled"

**Fix:** Run PowerShell as Administrator and use:
```powershell
New-Item -ItemType Junction -Path public\storage -Target storage\app\public
```

### Error: "No application encryption key"

**Fix:**
```powershell
php artisan key:generate
```

### Error: Images not displaying

**Fix:**
```powershell
# Ensure storage link exists
php artisan storage:link

# Check if link was created
Test-Path public\storage
# Should return True

# If False, manually create:
New-Item -ItemType Junction -Path public\storage -Target storage\app\public
```

### Error: Permission denied on storage

**Fix:**
```powershell
# In PowerShell as Administrator:
icacls storage /grant Users:F /t
icacls bootstrap\cache /grant Users:F /t
```

### Error: "Cannot find path... nul"

This error occurs when using PowerShell with CMD commands.

**Wrong (CMD syntax):**
```cmd
type nul > file.txt
```

**Correct (PowerShell syntax):**
```powershell
New-Item -ItemType File -Path file.txt
```

## Production Deployment (Windows Server)

### 1. Update Environment

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 2. Optimize Application

```powershell
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### 3. Set Permissions

```powershell
icacls storage /grant IIS_IUSRS:F /t
icacls bootstrap\cache /grant IIS_IUSRS:F /t
```

### 4. Configure IIS

1. Install IIS with PHP
2. Install URL Rewrite Module
3. Point site to `public` folder
4. Import `.htaccess` rules

## Testing Installation

```powershell
# Check Laravel version
php artisan --version

# Check routes
php artisan route:list

# Clear all caches
php artisan optimize:clear

# Test database connection
php artisan migrate:status
```

## Quick Troubleshooting Checklist

- [ ] `.env` file exists (copied from `.env.example`)
- [ ] `APP_KEY` is set (run `php artisan key:generate`)
- [ ] Database exists and credentials in `.env` are correct
- [ ] `CACHE_DRIVER=file` in `.env`
- [ ] `FILESYSTEM_DISK=public` in `.env`
- [ ] Migrations have been run (`php artisan migrate`)
- [ ] Storage link exists (`php artisan storage:link`)
- [ ] All required directories exist (see step 6)
- [ ] Composer dependencies installed
- [ ] NPM dependencies installed
- [ ] Assets built (`npm run build`)

## Getting Help

If you encounter issues:

1. Check Laravel logs: `storage\logs\laravel.log`
2. Run: `php artisan about` to see system info
3. Clear all caches: `php artisan optimize:clear`
4. Check PHP version: `php -v` (should be 8.1+)
5. Check Composer version: `composer --version`

## Useful Commands

```powershell
# Clear all caches
php artisan optimize:clear

# Clear specific caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Re-run migrations (WARNING: deletes data)
php artisan migrate:fresh --seed

# Check system status
php artisan about

# List all routes
php artisan route:list

# Run database seeders
php artisan db:seed

# Create storage link
php artisan storage:link
```

## Next Steps

1. Login to admin panel: `http://localhost:8000/login`
2. Upload your first media file
3. Create custom categories
4. Explore the gallery

**Enjoy using SAFCO Media Hub!** 🚀
