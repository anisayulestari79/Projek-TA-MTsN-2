<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration (Menambah kolom)
     */
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Menambahkan kolom ortu_id bertipe unsignedBigInteger (karena ini Foreign Key untuk 'id' di tabel users)
            // nullable() -> Membolehkan nilai kosong (jika ada siswa yang ortunya belum mendaftar)
            // constrained('users') -> Menghubungkan langsung dengan tabel 'users'
            // onDelete('set null') -> Jika akun ortu dihapus, data siswa tetap aman (ortu_id menjadi null)

            $table->foreignId('ortu_id')->nullable()->after('poin')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Mengembalikan/Membatalkan migration (Menghapus kolom)
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Wajib menghapus Foreign Key constraint terlebih dahulu sebelum menghapus kolom
            $table->dropForeign(['ortu_id']);
            $table->dropColumn('ortu_id');
        });
    }
};
