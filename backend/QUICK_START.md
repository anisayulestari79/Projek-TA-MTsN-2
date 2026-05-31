# 🚀 Quick Start Guide

## Setup Cepat (Copy-Paste Semua Perintah)

Buka terminal di folder `backend` dan jalankan perintah berikut **satu per satu**:

```bash
# 1. Install dependencies
composer install

# 2. Install Sanctum
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# 3. Setup .env (edit manual jika perlu)
# Pastikan DB_DATABASE, DB_USERNAME, DB_PASSWORD sudah benar

# 4. Generate key
php artisan key:generate

# 5. Buat database di MySQL (jika belum)
# CREATE DATABASE penilaian_poin;

# 6. Run migrations
php artisan migrate

# 7. Seed data
php artisan db:seed

# 8. Start server
php artisan serve
```

## Atau Gunakan Script Otomatis

**Windows PowerShell:**
```powershell
cd backend
.\setup.ps1
```

**Windows CMD:**
```cmd
cd backend
setup.bat
```

## Test API

Setelah server running, test dengan:
```bash
# Test health
curl http://localhost:8000/up

# Test login admin
curl -X POST http://localhost:8000/api/login/admin \
  -H "Content-Type: application/json" \
  -d "{\"username\":\"adminmt2\",\"password\":\"adminpass\"}"
```

## Selesai! 🎉

Backend sudah siap digunakan di `http://localhost:8000`

