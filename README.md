# SAFCO Media Hub

A comprehensive Laravel-based media management system for displaying and managing organization videos, images, and documents with a beautiful admin panel.

![Laravel](https://img.shields.io/badge/Laravel-10.x-red)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple)
![License](https://img.shields.io/badge/License-MIT-green)

## Features

### Frontend Features
- Beautiful, responsive media gallery
- Category-based filtering
- Advanced search functionality
- Modal viewer for images
- Video player support
- Document download functionality
- Mobile-responsive design with Bootstrap 5
- Smooth animations and transitions

### Admin Panel Features
- Comprehensive dashboard with statistics
- Single and bulk media upload
- Drag & drop file upload
- Category management
- Media management (CRUD operations)
- User management
- File validation and security
- Featured media support
- Active/Inactive status management
- View and download tracking

### Supported Media Types
- **Images:** JPEG, PNG, GIF, WebP
- **Videos:** MP4, WebM, MOV
- **Documents:** PDF, DOC, DOCX, PPT, PPTX

## Screenshots

### Frontend Gallery
Beautiful media gallery with category filters and search functionality.

### Admin Dashboard
Comprehensive dashboard with statistics, recent media, and analytics.

### Media Upload
Drag & drop interface for easy media uploads with real-time preview.

## Requirements

- PHP >= 8.1
- Composer
- MySQL >= 5.7 or MariaDB >= 10.3
- Node.js & NPM (for asset compilation)
- Apache/Nginx web server

## Installation Guide

### Quick Setup (Automated)

We provide a setup script that automates the installation:

```bash
cd safco-hub
chmod +x setup.sh
./setup.sh
```

This script will:
- Create `.env` file
- Install dependencies
- Generate application key
- Create storage link
- Set up directories and permissions
- Optionally run migrations and seeders

### Manual Setup (Step by Step)

#### Step 1: Clone or Download

```bash
cd /path/to/your/projects
git clone <repository-url> safco-hub
cd safco-hub
```

#### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

#### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### Step 4: Configure Database

Edit `.env` file and update database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=safco_media_hub
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### Step 5: Create Database

```bash
# Create database (or create manually via phpMyAdmin)
mysql -u your_username -p
CREATE DATABASE safco_media_hub;
exit;
```

#### Step 6: Run Migrations & Seeders

```bash
# Run migrations
php artisan migrate

# Seed database with initial data
php artisan db:seed
```

This will create:
- Admin user: `admin@safco.com` / `password`
- Regular user: `user@safco.com` / `password`
- 8 default categories

#### Step 7: Create Storage Link ⚠️ IMPORTANT

```bash
php artisan storage:link
```

**This step is CRITICAL!** Without it, uploaded images won't display.

If you have issues, run:
```bash
chmod +x fix-storage.sh
./fix-storage.sh
```

#### Step 8: Set Permissions

```bash
# Set proper permissions for storage and cache
chmod -R 775 storage bootstrap/cache
```

#### Step 9: Compile Assets

```bash
# Development
npm run dev

# Production
npm run build
```

#### Step 10: Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` to see your media hub!

## Configuration

### Media Settings

Edit `.env` file to customize media settings:

```env
# Maximum file size in KB (default: 10MB)
MEDIA_MAX_FILE_SIZE=10240

# Allowed file types
MEDIA_ALLOWED_IMAGE_TYPES=jpeg,jpg,png,gif,webp
MEDIA_ALLOWED_VIDEO_TYPES=mp4,webm,mov
MEDIA_ALLOWED_DOCUMENT_TYPES=pdf,doc,docx,ppt,pptx
```

### Storage Configuration

By default, media files are stored in `storage/app/public/media/`.

To use cloud storage (S3, etc.), update `config/filesystems.php`.

## User Guide

### Admin Panel Access

1. Navigate to `/login`
2. Login with admin credentials: `admin@safco.com` / `password`
3. You'll be redirected to the admin dashboard

### Uploading Media

1. Go to **Admin Panel > Upload Media**
2. Select a category
3. Enter title and description
4. Drag & drop files or click "Browse Files"
5. Select multiple files if needed
6. Toggle "Featured" or "Active" status
7. Click "Upload Media"

### Managing Categories

1. Go to **Admin Panel > Categories**
2. Click "Add Category" to create new categories
3. Edit existing categories by clicking the edit icon
4. Categories can have icons, descriptions, and custom ordering

### Viewing Statistics

The admin dashboard shows:
- Total media count by type
- Total views and downloads
- Storage usage
- Recent uploads
- Popular media
- Media distribution by category

### Frontend Gallery

- **Browse:** Navigate through all media
- **Filter:** Use category tabs to filter by category
- **Search:** Use the search bar to find specific media
- **Sort:** Sort by latest, most popular, or most downloaded
- **View:** Click on any media to view details
- **Download:** Download media files directly

## Directory Structure

```
safco-hub/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── Auth/           # Authentication
│   │   │   └── MediaGalleryController.php
│   │   ├── Middleware/
│   │   └── Requests/
│   └── Models/
│       ├── User.php
│       ├── Media.php
│       └── Category.php
├── config/
│   └── media.php               # Media configuration
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── public/
│   └── storage/                # Public storage link
├── resources/
│   └── views/
│       ├── admin/              # Admin panel views
│       ├── gallery/            # Frontend gallery views
│       ├── auth/               # Authentication views
│       └── layouts/            # Layout templates
├── routes/
│   └── web.php                 # Web routes
└── storage/
    └── app/
        └── public/
            └── media/          # Uploaded media files
```

## API Endpoints

### Public Routes
- `GET /` - Home page (gallery)
- `GET /gallery` - Media gallery
- `GET /gallery/search?q={query}` - Search media
- `GET /gallery/type/{type}` - Filter by type
- `GET /gallery/category/{slug}` - Filter by category
- `GET /media/{id}` - View media details
- `GET /media/{id}/download` - Download media

### Admin Routes (Requires Authentication)
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/media` - List all media
- `POST /admin/media` - Upload media
- `GET /admin/media/{id}/edit` - Edit media
- `DELETE /admin/media/{id}` - Delete media
- `GET /admin/categories` - List categories
- `POST /admin/categories` - Create category

## Security Features

- CSRF protection on all forms
- File type validation
- File size validation
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade templating)
- Authentication & authorization
- Secure file storage

## Troubleshooting

### Issue: Images not displaying (MOST COMMON)

**Symptoms:**
- Files upload successfully
- You can see the filename/text
- But no image preview shows
- 404 errors in browser console for images

**Solution:**
The storage symbolic link is missing! Run:

```bash
php artisan storage:link
```

Or use our fix script:
```bash
chmod +x fix-storage.sh
./fix-storage.sh
```

**What this does:** Creates a symbolic link from `public/storage` → `storage/app/public` so uploaded files are accessible via web URLs.

### Issue: Upload fails

**Solution:** Check file permissions:
```bash
chmod -R 775 storage
```

### Issue: Database connection error

**Solution:** Verify database credentials in `.env` file and ensure database exists.

### Issue: 404 errors on routes

**Solution:** Configure your web server properly:

**Apache (.htaccess):**
```apache
RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]
```

**Nginx:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## Deployment

### Production Checklist

1. Update `.env` for production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

2. Optimize application:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

3. Set proper permissions:
```bash
chmod -R 755 storage bootstrap/cache
```

4. Configure web server (Apache/Nginx)
5. Set up SSL certificate
6. Configure backup system
7. Set up monitoring

## Technologies Used

- **Backend:** Laravel 10.x, PHP 8.1+
- **Frontend:** Bootstrap 5.3, HTML5, CSS3, JavaScript
- **Database:** MySQL/MariaDB
- **Icons:** Bootstrap Icons
- **Animations:** Animate.css
- **Additional:** jQuery, Dropzone.js, SweetAlert2

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, email support@safco.com or create an issue in the repository.

## Credits

Developed with by the SAFCO Team.

---

**Enjoy using SAFCO Media Hub!**
