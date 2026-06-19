<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('siswa', 'status')) {
                $table->enum('status', ['Aktif', 'Lulus', 'Pindah', 'Dikeluarkan', 'Wafat'])->default('Aktif')->after('poin');
            }

            $table->unsignedBigInteger('tahun_masuk_id')->nullable()->after('status');
            $table->unsignedBigInteger('tahun_keluar_id')->nullable()->after('tahun_masuk_id');

            // Set Relasi (Foreign Key)
            $table->foreign('tahun_masuk_id')->references('id')->on('tahun_ajarans')->onDelete('set null');
            $table->foreign('tahun_keluar_id')->references('id')->on('tahun_ajarans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign(['tahun_masuk_id']);
            $table->dropForeign(['tahun_keluar_id']);
            $table->dropColumn(['status', 'tahun_masuk_id', 'tahun_keluar_id']);
        });
    }
};
