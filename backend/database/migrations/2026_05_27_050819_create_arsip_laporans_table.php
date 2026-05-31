<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('arsip_laporans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->enum('kategori', ['bulanan', 'kelas']);
            $table->string('file_path');

            // SESUAIKAN DENGAN TABEL USERS ANDA:
            // Ini otomatis terhubung ke kolom 'id' di tabel 'users'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_laporans');
    }
};
