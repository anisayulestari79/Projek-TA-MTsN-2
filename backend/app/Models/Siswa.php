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
        'jk',
        'kelas',
        'kontak_ortu',
        'poin',
        'photo',
        'alamat',
        'ortu_id',
    ];

    /**
     * Relasi balik ke model User (Orang Tua)
     * Menghubungkan setiap siswa dengan satu akun Wali Murid di tabel users
     */
    public function orangTua()
    {
        return $this->belongsTo(User::class, 'ortu_id');
    }

    public function ortu()
    {
        return $this->belongsTo(User::class, 'ortu_id', 'id');
    }

    public function riwayatPoin() // Pastikan 'riwayatPoin' bukan 'RiwayatPoin' atau lainnya
    {
        return $this->hasMany(RiwayatPoin::class, 'nisn', 'nisn');
    }
}
