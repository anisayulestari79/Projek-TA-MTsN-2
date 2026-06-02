<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa; // Sesuaikan dengan nama model Siswa Anda
use App\Models\User;

class OrtuController extends Controller
{
    /**
     * Menampilkan Dashboard Utama Orang Tua
     */
    public function index(Request $request)
    {
        // 1. Ambil data user (orang tua) yang sedang login
        $user = Auth::user();

        // 2. Ambil data anak berdasarkan relasi database
        // Asumsi: Di file Model User.php Anda sudah membuat relasi hasMany ke model Siswa
        // Contoh relasi di User.php: public function siswa() { return $this->hasMany(Siswa::class, 'ortu_id'); }
        $daftarAnak = $user->siswa; 

        // 3. Cek apakah orang tua ini memiliki anak yang terdaftar di sistem
        if (!$daftarAnak || $daftarAnak->isEmpty()) {
            // Jika belum ada anak yang di-assign, kirim data kosong agar view tidak error
            return view('dashboard-ortu', [
                'daftarAnak' => collect([]),
                'siswaAktif' => null
            ]);
        }

        // 4. Tentukan profil anak mana yang sedang aktif / dilihat
        $siswaAktif = null;
        
        // Jika orang tua mengklik tab anak tertentu (URL misal: ?siswa_id=2)
        if ($request->has('siswa_id')) {
            $siswaAktif = $daftarAnak->where('id', $request->siswa_id)->first();
        }
        
        // Jika tidak ada parameter URL (pertama kali buka), jadikan anak pertama sebagai default
        if (!$siswaAktif) {
            $siswaAktif = $daftarAnak->first();
        }

        // 5. Kembalikan ke view dashboard-ortu (file blade) dengan membawa variabel
        return view('dashboard-ortu', compact('daftarAnak', 'siswaAktif'));
    }

    /**
     * Menampilkan Halaman Riwayat Poin (Bisa Anda kembangkan nanti)
     */
    public function riwayatPoin(Request $request)
    {
        // Logika untuk menampilkan tabel riwayat poin detail
        // ...
    }
}