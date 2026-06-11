<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $table = 'consultations';

    protected $fillable = [
        'academic_period',
        'student_id',
        'parent_id',
        'bk_id',
        'topic',
        'message',
        'reply',
        'status',
        'pengirim',
    ];

    // =======================================================
    // RELASI DATABASE (Dibuat ganda agar kebal error di semua view)
    // =======================================================

    // Relasi untuk mengetahui anak siapa yang dikonsultasikan
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'student_id');
    }
    public function student()
    {
        return $this->belongsTo(Siswa::class, 'student_id');
    }

    // Relasi untuk mengetahui siapa orang tuanya
    public function ortu()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Relasi ke guru BK
    public function guruBk()
    {
        return $this->belongsTo(User::class, 'bk_id');
    }
    public function bk()
    {
        return $this->belongsTo(User::class, 'bk_id');
    }
    public function guru()
    {
        return $this->belongsTo(User::class, 'bk_id');
    }
}
