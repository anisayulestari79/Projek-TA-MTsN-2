<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPoin extends Model
{
    use HasFactory;

    protected $table = 'riwayat_poin';

    protected $fillable = [
        'nisn',
        'nama',
        'kelas',
        'jenis',
        'jumlah',
        'ket',
        'foto_bukti',
        'waktu',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'waktu' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }
}
