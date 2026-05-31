# Backend Laravel - Sistem Penilaian Poin Siswa MTsN 2 Kota Banjarmasin

## Instalasi

1. Install dependencies:
```bash
composer install
```

2. Copy file `.env.example` menjadi `.env`:
```bash
copy .env.example .env
```

3. Generate application key:
```bash
php artisan key:generate
```

4. Setup database di file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password
```

5. Install Laravel Sanctum (untuk authentication):
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

Kemudian tambahkan `\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class` ke middleware group `api` di `bootstrap/app.php` jika diperlukan.

6. Jalankan migrations:
```bash
php artisan migrate
```

7. Jalankan seeders untuk data awal:
```bash
php artisan db:seed
```

8. Jalankan server:
```bash
php artisan serve
```

## API Endpoints

### Authentication

- `POST /api/register` - Registrasi user baru
- `POST /api/login/admin` - Login admin (menggunakan username)
- `POST /api/login/guru` - Login guru (menggunakan NIP)
- `POST /api/logout` - Logout (memerlukan token)
- `GET /api/me` - Get user yang sedang login

### Profile

- `GET /api/profile` - Get profile user
- `PUT /api/profile` - Update profile user

### Siswa

- `GET /api/siswa` - Get semua siswa (dapat difilter dengan query params: tingkat, kelas, search)
- `GET /api/siswa/{nisn}` - Get detail siswa
- `POST /api/siswa` - Tambah siswa baru
- `PUT /api/siswa/{nisn}` - Update data siswa
- `DELETE /api/siswa/{nisn}` - Hapus siswa

### Guru (Admin Only)

- `GET /api/guru` - Get semua guru
- `GET /api/guru/{nuptk}` - Get detail guru
- `POST /api/guru` - Tambah guru baru
- `PUT /api/guru/{nuptk}` - Update data guru
- `DELETE /api/guru/{nuptk}` - Hapus guru

### Poin

- `GET /api/poin/dashboard` - Get data poin untuk dashboard (dapat difilter)
- `POST /api/poin` - Tambah poin siswa
- `GET /api/poin/riwayat` - Get riwayat poin
- `DELETE /api/poin/riwayat/{id}` - Hapus riwayat poin
- `DELETE /api/poin/riwayat` - Hapus semua riwayat poin

### Pelanggaran

- `GET /api/pelanggaran` - Get semua pelanggaran (dapat dicari dengan query param: search)
- `GET /api/pelanggaran/{id}` - Get detail pelanggaran

## Data Awal

Setelah menjalankan seeder, akan tersedia:

### Admin Users:
- Username: `adminmt2`, Password: `adminpass`
- Username: `operator`, Password: `op123`

### Guru Users:
- NIP: `1234`, Password: `guru123`
- NIP: `5678`, Password: `guru456`

### Data Guru:
- 3 data guru dengan NUPTK yang berbeda

### Data Siswa:
- 12 data siswa contoh

### Data Pelanggaran:
- 90 jenis pelanggaran sesuai tata tertib

## Authentication

API menggunakan Laravel Sanctum untuk authentication. Setelah login, gunakan token yang diberikan di header:

```
Authorization: Bearer {token}
```

## CORS

Pastikan untuk mengkonfigurasi CORS di `config/cors.php` jika frontend berada di domain yang berbeda.

## Catatan

- Semua endpoint yang memerlukan authentication harus menyertakan token di header
- Endpoint guru hanya bisa diakses oleh admin (role: admin)
- Password default untuk guru baru adalah `mtsn02` jika tidak diisi
