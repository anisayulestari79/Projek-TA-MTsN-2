<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipLaporan;
use App\Models\RiwayatPoin;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class KamadController extends Controller
{
    /**
     * Menampilkan halaman Laporan Masuk (File PDF dari Admin/Guru BK)
     */
    public function laporanMasuk(Request $request)
    {
        // Ambil data user yang sedang login (Kamad)
        $user = Session::has('user') ? Session::get('user') : Auth::user();

        // 💡 PERBAIKAN: Menggunakan 'user' BUKAN 'pengirim'
        $query = ArsipLaporan::with('user')->orderBy('created_at', 'desc');

        // Fitur filter kategori PDF (jika Kepala Madrasah ingin menyaring)
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        $listLaporan = $query->paginate(10)->withQueryString();

        return view('kamad.kamad-laporan', compact('user', 'listLaporan'));
    }

    /**
     * Menampilkan halaman Rekap Poin Keseluruhan
     */
    public function poinKeseluruhan(Request $request)
    {
        $user = Session::has('user') ? Session::get('user') : Auth::user();

        $bulanFilter = $request->input('bulan', date('m'));
        $tahunFilter = $request->input('tahun', date('Y'));
        $kelasFilter = $request->input('kelas', 'Semua');

        $query = RiwayatPoin::whereMonth('waktu', $bulanFilter)
            ->whereYear('waktu', $tahunFilter)
            ->orderBy('waktu', 'desc');

        if ($kelasFilter !== 'Semua') {
            $query->where('kelas', $kelasFilter);
        }

        $totalKasusLaporan = $query->count();
        $laporan = $query->paginate(15)->withQueryString();

        try {
            $kelasList = Kelas::pluck('nama_kelas');
        } catch (\Exception $e) {
            $kelasList = Siswa::select('kelas')->distinct()->pluck('kelas')->filter()->sort()->values();
        }

        return view('kamad.kamad-poin', compact(
            'user',
            'laporan',
            'totalKasusLaporan',
            'bulanFilter',
            'tahunFilter',
            'kelasFilter',
            'kelasList'
        ));
    }
}
