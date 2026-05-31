# Setup Backend Laravel - Langkah Demi Langkah

## ⚠️ PENTING: Pastikan sudah terinstall
- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Git (opsional)

## 📋 Langkah-langkah Setup

### Langkah 1: Install Dependencies Composer
Buka terminal/command prompt di folder `backend` dan jalankan:
```bash
composer install
```
**Catatan:** Proses ini mungkin memakan waktu beberapa menit untuk mengunduh semua package.

### Langkah 2: Install Laravel Sanctum
Setelah composer install selesai, jalankan:
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### Langkah 3: Setup File .env
1. Pastikan file `.env` sudah ada (jika belum, copy dari `.env.example`)
2. Edit file `.env` dan set konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=penilaian_poin
DB_USERNAME=root
DB_PASSWORD=
```
**Ganti `DB_PASSWORD` dengan password MySQL Anda jika ada.**

### Langkah 4: Buat Database MySQL
Buka MySQL (phpMyAdmin, MySQL Workbench, atau command line) dan buat database:
```sql
CREATE DATABASE penilaian_poin;
```

### Langkah 5: Generate Application Key
```bash
php artisan key:generate
```

### Langkah 6: Jalankan Migrations
```bash
php artisan migrate
```
Ini akan membuat semua tabel di database.

### Langkah 7: Seed Database (Data Awal)
```bash
php artisan db:seed
```
Ini akan mengisi database dengan data awal (admin, guru, siswa, pelanggaran).

### Langkah 8: Start Server
```bash
php artisan serve
```
Server akan berjalan di `http://localhost:8000`

## ✅ Verifikasi Setup

Setelah semua langkah selesai, coba akses:
- API Base: `http://localhost:8000/api`
- Health Check: `http://localhost:8000/up`

## 🔑 Data Login Setelah Seeding

### Admin:
- **Username:** `adminmt2`
- **Password:** `adminpass`

- **Username:** `operator`  
- **Password:** `op123`

### Guru:
- **NIP:** `1234`
- **Password:** `guru123`

- **NIP:** `5678`
- **Password:** `guru456`

## 🚀 Alternatif: Gunakan Script Otomatis

Jika ingin setup otomatis, jalankan salah satu script:

### PowerShell:
```powershell
cd backend
.\setup.ps1
```

### Command Prompt:
```cmd
cd backend
setup.bat
```

## 🐛 Troubleshooting

### Error: "vendor/autoload.php not found"
**Solusi:** Jalankan `composer install` terlebih dahulu

### Error: "Database connection failed"
**Solusi:** 
- Pastikan MySQL service sudah running
- Cek konfigurasi di `.env`
- Pastikan database sudah dibuat

### Error: "Class 'Laravel\Sanctum\HasApiTokens' not found"
**Solusi:** Jalankan `composer require laravel/sanctum`

### Error: "Migration failed"
**Solusi:**
- Pastikan database sudah dibuat
- Cek koneksi database
- Pastikan user database punya permission

## 📝 Catatan Penting

1. **Jangan lupa** untuk mengubah password default di production
2. File `.env` jangan di-commit ke git (sudah ada di .gitignore)
3. Untuk development, bisa gunakan `php artisan serve`
4. Untuk production, gunakan web server seperti Apache/Nginx

## 🔗 API Endpoints

Setelah setup selesai, API tersedia di:
- Base URL: `http://localhost:8000/api`
- Dokumentasi lengkap ada di `README.md`

