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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('academic_period'); // Contoh: "2025 Genap"

            // Diarahkan ke tabel 'siswa' sesuai dengan database kamu (bukan 'students')
            $table->foreignId('student_id')->constrained('siswa')->onDelete('cascade');

            // Diarahkan ke tabel 'users' untuk data Orang Tua (parent_id) dan Guru BK (bk_id)
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bk_id')->constrained('users')->onDelete('cascade');

            $table->string('topic');
            $table->text('message'); // Pesan awal dari orang tua
            $table->text('reply')->nullable(); // Tanggapan dari BK / Admin
            $table->enum('status', ['menunggu', 'dibalas', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
