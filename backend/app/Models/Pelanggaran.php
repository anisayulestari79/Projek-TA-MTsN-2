<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    use HasFactory;

    protected $table = 'pelanggaran';
    
    protected $fillable = [
        'jenis',
        'sanksi',
        'skor_poin',
    ];

    protected $casts = [
        'skor_poin' => 'integer',
    ];
}

