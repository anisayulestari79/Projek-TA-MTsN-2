<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipLaporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'kategori',
        'file_path',
        'user_id',
    ];

    // Relasi langsung ke tabel User Anda
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
