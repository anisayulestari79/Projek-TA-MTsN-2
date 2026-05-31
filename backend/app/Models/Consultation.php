<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_period',
        'student_id',
        'parent_id',
        'bk_id',
        'topic',
        'message',
        'reply',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_nisn', 'nisn'); // Sesuaikan foreign key Anda
    }
    public function ortu()
    {
        return $this->belongsTo(User::class, 'ortu_id');
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
