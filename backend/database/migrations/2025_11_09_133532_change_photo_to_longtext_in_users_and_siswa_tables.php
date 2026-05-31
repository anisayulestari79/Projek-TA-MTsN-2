<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom photo di tabel users menjadi longText
        Schema::table('users', function (Blueprint $table) {
            $table->longText('photo')->nullable()->change();
        });

        // Ubah kolom photo di tabel siswa menjadi longText
        Schema::table('siswa', function (Blueprint $table) {
            $table->longText('photo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan kolom photo di tabel users menjadi text
        Schema::table('users', function (Blueprint $table) {
            $table->text('photo')->nullable()->change();
        });

        // Kembalikan kolom photo di tabel siswa menjadi text
        Schema::table('siswa', function (Blueprint $table) {
            $table->text('photo')->nullable()->change();
        });
    }
};
