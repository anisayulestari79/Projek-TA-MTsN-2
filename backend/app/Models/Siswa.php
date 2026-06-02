<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    // Menegaskan nama tabel sesuai dengan yang ada di database Anda
    protected $table = 'siswa';

    // Kolom-kolom yang dapat diisi secara massal (mass assignment)
    protected $fillable = [
        'nisn',
        'nama',
        'jk',            // <-- DITAMBAHKAN: Agar Jenis Kelamin bisa tersimpan
        'kelas',
        'kontak_ortu',   // <-- DITAMBAHKAN: Agar No WA Ortu bisa tersimpan
        'poin',
        'photo',         // <-- DITAMBAHKAN: Agar foto profil (jika ada) bisa tersimpan
        'ortu_id',       // Kolom asing (Foreign Key) wajib didaftarkan di sini agar bisa diisi saat register
    ];

    /**
     * Relasi balik ke model User (Orang Tua)
     * Menghubungkan setiap siswa dengan satu akun Wali Murid di tabel users
     */
    public function orangTua()
    {
        return $this->belongsTo(User::class, 'ortu_id');
    }

    public function riwayatPoin() // Pastikan 'riwayatPoin' bukan 'RiwayatPoin' atau lainnya
    {
        return $this->hasMany(RiwayatPoin::class, 'nisn', 'nisn');
    }
}
