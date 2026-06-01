<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipLaporan;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Menyimpan laporan baru dari Admin/Guru ke dalam database 
     * dan mengunggah file PDF agar bisa dibaca oleh Kamad.
     */
    public function store(Request $request)
    {
        // 1. Validasi Keamanan (Pastikan yang dikirim benar-benar PDF)
        $request->validate([
            'judul'        => 'required|string|max:255',
            'kategori'     => 'required|in:bulanan,kelas',
            'file_laporan' => 'required|mimes:pdf|max:5120', // Wajib PDF, Maksimal 5MB
        ], [
            'file_laporan.mimes' => 'Hanya file PDF yang diperbolehkan!',
            'file_laporan.max'   => 'Ukuran file tidak boleh lebih dari 5 Megabyte.'
        ]);

        try {
            // 2. Simpan file PDF ke folder 'storage/app/public/laporan_masuk'
            $filePath = $request->file('file_laporan')->store('laporan_masuk', 'public');

            // 3. Masukkan catatan ke Database
            ArsipLaporan::create([
                'judul'       => $request->judul,
                'kategori'    => $request->kategori,
                'file_path'   => $filePath,
                'pengirim_id' => Auth::id(), // ID Admin/Guru yang sedang klik tombol kirim
            ]);

            // 4. Jika berhasil, beri tahu Admin/Guru bahwa laporan telah terkirim
            return redirect()->back()->with('success', 'Laporan PDF berhasil dikirim ke Pimpinan!');
        } catch (\Exception $e) {
            // Jika gagal, tampilkan pesan error
            return redirect()->back()->with('error', 'Gagal mengirim laporan: ' . $e->getMessage());
        }
    }
}
