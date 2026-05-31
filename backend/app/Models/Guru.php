<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';
    
    protected $fillable = [
        'nip',
        'nama',
        'jk',
        'pendidikan',
        'tempat_lahir',
        'tanggal_lahir',
        'password',
        'wali_kelas',
    ];

    protected $hidden = [
        // Password tidak disembunyikan agar bisa ditampilkan di admin dashboard
    ];

    public function user()
    {
        // Relasi dengan user berdasarkan NIP
        if ($this->nip) {
            return User::where('nip', $this->nip)->where('role', 'guru')->first();
        }
        return null;
    }
}

