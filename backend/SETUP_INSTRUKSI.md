# Instruksi Setup Backend Laravel

## Cara 1: Menggunakan Script Otomatis (Recommended)

### Windows (PowerShell):
```powershell
cd backend
.\setup.ps1
```

### Windows (Command Prompt):
```cmd
cd backend
setup.bat
```

## Cara 2: Manual Step by Step

### Step 1: Install Dependencies
```bash
cd backend
composer install
```

### Step 2: Install Laravel Sanctum
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### Step 3: Setup Environment File
```bash
# Copy .env.example ke .env (jika belum ada)
copy .env.example .env

# Edit .env dan set database:
# DB_DATABASE=penilaian_poin
# DB_USERNAME=root
# DB_PASSWORD=your_password
```

### Step 4: Generate Application Key
```bash
php artisan key:generate
```

### Step 5: Buat Database
Buat database MySQL dengan nama `penilaian_poin` (atau sesuai yang di .env)

### Step 6: Run Migrations
```bash
php artisan migrate
```

### Step 7: Seed Database
```bash
php artisan db:seed
```

### Step 8: Start Server
```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

## Data Login Setelah Seeding

### Admin:
- Username: `adminmt2`
- Password: `adminpass`

- Username: `operator`
- Password: `op123`

### Guru:
- NIP: `1234`
- Password: `guru123`

- NIP: `5678`
- Password: `guru456`

## API Base URL
```
http://localhost:8000/api
```

## Troubleshooting

### Error: Database connection failed
- Pastikan MySQL sudah running
- Cek konfigurasi di `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- Pastikan database sudah dibuat

### Error: Class 'Laravel\Sanctum\HasApiTokens' not found
- Jalankan: `composer require laravel/sanctum`
- Pastikan `composer install` sudah dijalankan

### Error: Migration failed
- Pastikan database sudah dibuat
- Cek koneksi database di `.env`
- Pastikan user database memiliki permission untuk create table

