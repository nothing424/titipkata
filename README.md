# TitipKata — Installation Guide

**Tempat menitipkan cerita, pesan, dan perasaan.**

## Quick Start

### Requirements
- PHP 8.2+
- MySQL 5.7+ / MariaDB 10.3+
- Composer
- Laravel 12

---

## Installation Steps

### 1. Upload Files
Upload all files to your hosting's `public_html` folder (or a subdirectory).

### 2. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database credentials:
```
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
APP_URL=https://titipkata.my.id
```

### 3. Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### 4. Run Migrations & Seed
```bash
php artisan migrate --seed
```

### 5. Set Permissions
```bash
chmod -R 755 storage bootstrap/cache
php artisan storage:link
```

### 6. Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Default Admin Credentials

After running seeds:
- **Email:** admin@titipkata.my.id
- **Password:** titipkata2024

⚠️ **Change the password immediately after first login!**

---

## Shared Hosting (cPanel) Setup

1. Upload all files to `public_html`
2. Move the contents of `public/` to `public_html/` root
3. Edit `public/index.php` → update paths to `../` prefix for Laravel root
4. Create MySQL database in cPanel → run SQL from migration files
5. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`

### Alternative: Subdomain Setup
1. Create subdomain `titipkata.my.id` → point to `public_html/titipkata/public`
2. Upload all TitipKata files to `public_html/titipkata/`
3. Configure `.env` accordingly

---

## Admin Panel

**URL:** `https://titipkata.my.id/admin`

### Features:
- 📊 Dashboard with statistics
- 🏠 Room management (create, edit, delete)
- 📬 Submission moderation (approve, reject, mark posted)
- 🎨 Quote image generator for TikTok content
- 🔗 Shareable room links + QR codes

---

## Room System

### Creating a Room:
1. Go to Admin → Buat Room
2. Fill in title, description, category
3. Set expiry date (optional)
4. Copy the generated link/code
5. Share with participants via TikTok, WhatsApp, etc.

### Room Statuses:
- **Pending** (Yellow) — awaiting review
- **Approved** (Green) — ready to use
- **Rejected** (Red) — declined
- **Posted** (Blue) — already used on TikTok

---

## Tech Stack

- **Framework:** Laravel 12
- **Database:** MySQL
- **Frontend:** Blade + TailwindCSS (CDN)
- **Auth:** Laravel Session Auth (admin guard)
- **Quote Images:** HTML Canvas (browser-based, no server deps)
- **QR Codes:** Google Chart API

---

## File Structure

```
titipkata/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php
│   │   │   ├── RoomController.php
│   │   │   └── Admin/
│   │   │       ├── AuthController.php
│   │   │       ├── DashboardController.php
│   │   │       ├── RoomController.php
│   │   │       └── SubmissionController.php
│   │   └── Middleware/
│   │       └── AdminAuthenticate.php
│   ├── Models/
│   │   ├── Admin.php
│   │   ├── Room.php
│   │   └── Submission.php
│   └── helpers.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   └── admin.blade.php
│   ├── home/
│   │   ├── private.blade.php
│   │   └── public.blade.php
│   ├── rooms/
│   │   └── show.blade.php
│   └── admin/
│       ├── login.blade.php
│       ├── dashboard.blade.php
│       ├── rooms/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       └── submissions/
│           └── quote-image.blade.php
├── routes/web.php
├── config/auth.php
├── bootstrap/app.php
├── .env.example
└── README.md
```

---

## Security Notes

- CSRF protection: enabled by default (Laravel)
- XSS protection: all content passes through `strip_tags()`
- Rate limiting: max 10 submissions per IP per room per hour
- Admin auth: separate guard with session authentication
- Input validation: all inputs validated before processing

---

Made with ❤️ for TitipKata — titipkata.my.id
