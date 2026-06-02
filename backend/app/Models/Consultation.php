<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit (opsional, karena Laravel akan menebak 'consultations' secara otomatis dari nama model)
    protected $table = 'consultations';

    protected $fillable = [
        'academic_period_id', // Jika ini wajib diisi, pastikan di controller juga disiapkan datanya
        'student_id',
        'parent_id',
        'bk_id', // Boleh kosong jika pesan baru dikirim (belum dibaca guru BK tertentu)
        'topic',
        'message',
        'reply',
        'status'
    ];

    // Relasi untuk mengetahui siapa orang tuanya
    public function ortu()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // Relasi untuk mengetahui anak siapa yang dikonsultasikan
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'student_id');
    }

    // Relasi ke guru BK (jika diperlukan)
    public function guruBk()
    {
        return $this->belongsTo(User::class, 'bk_id');
    }
}
