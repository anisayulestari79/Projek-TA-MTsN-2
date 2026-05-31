# Cara Membuat Akun Admin Setelah Migrate Fresh

Dokumen ini menjelaskan cara membuat akun admin setelah menjalankan `php artisan migrate:fresh`.

## Metode 1: Menggunakan Seeder (Recommended)

Cara termudah adalah dengan menjalankan seeder yang sudah disediakan. Seeder akan membuat akun admin dan data awal lainnya.

### Langkah-langkah:

1. **Jalankan migrate fresh dengan seeder:**
   ```bash
   php artisan migrate:fresh --seed
   ```

   Atau jika sudah menjalankan `migrate:fresh` sebelumnya, jalankan seeder saja:
   ```bash
   php artisan db:seed
   ```

2. **Akun admin yang dibuat otomatis:**

   **Akun Admin 1:**
   - **Username:** `adminmt2`
   - **Password:** `adminpass`
   - **Email:** `admin@mtsn2bjm.sch.id`
   - **Nama:** Kepala Tata Usaha

   **Akun Admin 2:**
   - **Username:** `operator`
   - **Password:** `op123`
   - **Email:** `operator@mtsn2bjm.sch.id`
   - **Nama:** Operator Sekolah

3. **Login ke sistem:**
   - Buka browser dan akses: `http://127.0.0.1:8000/admin/login`
   - Masukkan username dan password salah satu akun di atas

---

## Metode 2: Membuat Akun Admin Manual Menggunakan Tinker

Jika Anda ingin membuat akun admin secara manual atau membuat akun baru, gunakan Laravel Tinker.

### Langkah-langkah:

1. **Buka Laravel Tinker:**
   ```bash
   php artisan tinker
   ```

2. **Buat akun admin baru:**
   ```php
   use App\Models\User;
   use Illuminate\Support\Facades\Hash;

   User::create([
       'name' => 'Nama Admin Anda',
       'email' => 'admin@example.com',
       'username' => 'admin',
       'nip' => '123456789',
       'role' => 'admin',
       'gender' => 'Laki-laki',
       'phone' => '081234567890',
       'password' => Hash::make('password123'),
   ]);
   ```

3. **Keluar dari Tinker:**
   Tekan `Ctrl + C` atau ketik `exit`

4. **Login dengan akun yang baru dibuat:**
   - Username: `admin`
   - Password: `password123`

---

## Metode 3: Membuat Akun Admin dengan Command (Opsional)

Anda juga bisa membuat command khusus untuk membuat akun admin. Tapi untuk kebutuhan cepat, metode 1 dan 2 sudah cukup.

---

## Catatan Penting

### ⚠️ Keamanan

1. **Ubah password default setelah login pertama kali!**
   - Password default yang dibuat oleh seeder adalah untuk development/testing
   - Untuk production, pastikan untuk mengubah semua password default

2. **Jangan commit password ke repository:**
   - Pastikan file `.env` tidak di-commit ke Git
   - Gunakan password yang kuat untuk production

### 📝 Informasi Tambahan

**Data yang dibuat oleh seeder:**
- ✅ 2 akun Admin
- ✅ 2 akun Guru
- ✅ 12 data Siswa
- ✅ 3 data Guru (tabel `guru`)
- ✅ 90 jenis Pelanggaran

**File seeder yang dijalankan:**
- `UserSeeder.php` - Membuat akun admin dan guru
- `GuruSeeder.php` - Membuat data guru
- `SiswaSeeder.php` - Membuat data siswa
- `PelanggaranSeeder.php` - Membuat jenis pelanggaran

---

## Troubleshooting

### Masalah: "Class 'User' not found" saat menggunakan Tinker

**Solusi:**
Pastikan Anda sudah menjalankan `composer dump-autoload`:
```bash
composer dump-autoload
php artisan tinker
```

### Masalah: Seeder tidak berjalan

**Solusi:**
1. Pastikan semua migration sudah berjalan:
   ```bash
   php artisan migrate
   ```

2. Pastikan file seeder ada di folder `database/seeders/`

3. Cek file `DatabaseSeeder.php` untuk memastikan seeder dipanggil

### Masalah: Password tidak bisa login

**Solusi:**
1. Pastikan password di-hash dengan benar menggunakan `Hash::make()`
2. Pastikan role user adalah `'admin'` (bukan `'guru'`)
3. Untuk login admin, gunakan **username**, bukan email atau NIP

---

## Quick Reference

### Command yang sering digunakan:

```bash
# Migrate fresh dengan seeder (semua data dihapus dan dibuat ulang)
php artisan migrate:fresh --seed

# Hanya menjalankan seeder (tanpa menghapus data)
php artisan db:seed

# Menjalankan seeder tertentu saja
php artisan db:seed --class=UserSeeder

# Membuka Tinker
php artisan tinker
```

### Kredensial Default (Development Only):

**Admin:**
- Username: `adminmt2` | Password: `adminpass`
- Username: `operator` | Password: `op123`

**Guru:**
- NIP: `1234` | Password: `guru123`
- NIP: `5678` | Password: `guru456`

---

## Kontak & Support

Jika mengalami masalah, pastikan:
1. ✅ Database sudah dikonfigurasi dengan benar di `.env`
2. ✅ Semua dependency sudah terinstall (`composer install`)
3. ✅ Server Laravel berjalan (`php artisan serve`)

---

**Terakhir diupdate:** 2024-01-01

