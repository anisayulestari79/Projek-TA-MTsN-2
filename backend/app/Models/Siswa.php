<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    
    protected $fillable = [
        'nisn',
        'nama',
        'jk',
        'kelas',
        'kontak_ortu',
        'poin',
        'catatan',
        'photo',
    ];

    protected $casts = [
        'poin' => 'integer',
    ];

    public function riwayatPoin()
    {
        return $this->hasMany(RiwayatPoin::class, 'nisn', 'nisn');
    }
}

