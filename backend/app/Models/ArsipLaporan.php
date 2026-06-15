<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipLaporan extends Model
{
    use HasFactory;

    // Menghubungkan ke tabel yang ada di gambar Anda
    protected $table = 'arsip_laporans';

    protected $fillable = [
        'judul',
        'kategori',
        'file_path',
        'user_id'
    ];

    // Relasi untuk mengetahui siapa yang mengunggah laporan
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
