<?php

namespace App\Http\Controllers;

use App\Models\Consultation; // Sesuaikan jika nama model Anda 'Konsultasi'
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ConsultationController extends Controller
{
    /**
     * Menampilkan halaman Monitoring Konsultasi BK
     */
    public function index(Request $request)
    {
        // 1. Cek Autentikasi Admin
        if (!Session::has('auth_token') || !Session::has('user')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Session::get('user');

        // 2. Hitung Statistik Konsultasi untuk Card di atas
        $totalKonsultasi = Consultation::count();
        $menungguRespon  = Consultation::where('status', 'menunggu')->count();
        $konsultasiSelesai = Consultation::where('status', 'selesai')->count();

        // 3. Ambil data list konsultasi (Eager loading relasi ke siswa, ortu, dan guru)
        $query = Consultation::with(['siswa', 'ortu', 'guru'])->orderBy('created_at', 'desc');

        // 4. Fitur Filter berdasarkan Status (Menunggu/Selesai)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 5. Fitur Pencarian berdasarkan Topik atau Nama Siswa
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Cari di kolom topik
                $q->where('topik', 'LIKE', "%{$search}%")
                    // Atau cari ke tabel siswa berdasarkan namanya
                    ->orWhereHas('siswa', function ($qSiswa) use ($search) {
                        $qSiswa->where('nama', 'LIKE', "%{$search}%");
                    });
            });
        }

        // 6. Jalankan query dengan Pagination (10 data per halaman)
        $dataKonsultasi = $query->paginate(10)->withQueryString();

        // 7. Kirim semua data ke tampilan admin-konsultasi.blade.php
        return view('admin.admin-konsultasi', compact(
            'user',
            'totalKonsultasi',
            'menungguRespon',
            'konsultasiSelesai',
            'dataKonsultasi'
        ));
    }

    /**
     * Menampilkan halaman Detail Konsultasi
     * (Untuk view admin.konsultasi-detail.blade.php yang sebelumnya sudah dibuat)
     */
    public function show($id)
    {
        $user = Session::get('user');

        $consultation = Consultation::with(['siswa', 'ortu', 'guru'])->findOrFail($id);

        return view('admin.konsultasi-detail', compact('user', 'consultation'));
    }

    /**
     * Memproses balasan / tanggapan dari Guru BK/Admin
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|min:5'
        ]);

        $consultation = Consultation::findOrFail($id);
        $consultation->update([
            'reply' => $request->reply,
            'status' => 'selesai' // Otomatis selesai setelah dibalas (opsional)
        ]);

        return redirect()->route('admin.konsultasi.show', $id)
            ->with('success', 'Tanggapan berhasil dikirim!');
    }

    /**
     * Menutup sesi konsultasi (Mark as Complete)
     */
    public function markAsComplete($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->update([
            'status' => 'selesai'
        ]);

        return redirect()->route('admin.konsultasi.show', $id)
            ->with('success', 'Sesi konsultasi ditandai selesai.');
    }
}
