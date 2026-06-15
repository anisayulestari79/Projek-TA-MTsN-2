<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipLaporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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

            // Cek session login untuk mendapatkan ID User (bisa dari Session atau Auth)
            $userId = Session::has('user') ? Session::get('user.id') : Auth::id();

            // 3. Masukkan catatan ke Database
            ArsipLaporan::create([
                'judul'       => $request->judul,
                'kategori'    => $request->kategori,
                'file_path'   => $filePath,
                'user_id'     => $userId, // PERBAIKAN: Diubah dari pengirim_id menjadi user_id sesuai dengan kolom database
            ]);

            // 4. Jika berhasil, beri tahu Admin/Guru bahwa laporan telah terkirim
            return redirect()->back()->with('success', 'Laporan PDF berhasil dikirim ke Pimpinan!');
        } catch (\Exception $e) {
            // Jika gagal, tampilkan pesan error
            return redirect()->back()->with('error', 'Gagal mengirim laporan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus (Menarik kembali) laporan yang sudah dikirim
     */
    public function destroy($id)
    {
        try {
            $laporan = ArsipLaporan::findOrFail($id);

            // Keamanan: Cek apakah user yang login adalah pengirim laporan ini
            $userId = Session::has('user') ? Session::get('user.id') : Auth::id();

            // Hapus file fisik PDF dari folder storage agar tidak membuang ruang penyimpanan
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($laporan->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->file_path);
            }

            // Hapus rekam jejaknya dari database
            $laporan->delete();

            return redirect()->back()->with('success', 'Laporan berhasil ditarik dan dihapus dari sistem!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }
}
