<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipLaporan;
use Illuminate\Support\Facades\Auth;

class KamadController extends Controller
{
    public function laporanMasuk(Request $request)
    {
        // Mengambil data user yang sedang login (Kamad)
        $user = Auth::user();

        // Mengambil semua daftar laporan, diurutkan dari yang terbaru, 
        // beserta data relasi pengirimnya.
        $listLaporan = ArsipLaporan::with('pengirim')->latest()->get();

        // Mengirim data ke file view blade
        return view('kamad.kamad-laporan', compact('user', 'listLaporan'));
    }

    public function poinKeseluruhan(Request $request)
    {
        // 1. Ambil pilihan filter dari form (jika ada)
        $filterKelas = $request->input('kelas');

        // 2. Buat kerangka query untuk Siswa
        $query = \App\Models\Siswa::query();

        // 3. Jika Kamad memilih kelas tertentu, filter datanya
        if ($filterKelas) {
            $query->where('kelas', $filterKelas);
        }

        // 4. Ambil datanya dan urutkan berdasarkan poin tertinggi
        $siswaPelanggaran = $query->orderBy('poin', 'desc')->get();

        // 5. MENGGENERATE DAFTAR KELAS A SAMPAI K SECARA OTOMATIS
        $daftarKelas = [];
        $tingkatan = ['VII', 'VIII', 'IX']; // Kelas 7, 8, dan 9 (MTs)
        $abjad = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

        foreach ($tingkatan as $tingkat) {
            foreach ($abjad as $huruf) {
                // Menghasilkan format "VII.A", "VII.B", sesuai dengan database Anda
                $daftarKelas[] = $tingkat . '.' . $huruf;
            }
        }

        // 6. Kirim data ke tampilan
        return view('kamad.kamad-poin', compact('siswaPelanggaran', 'daftarKelas', 'filterKelas'));
    }
}
