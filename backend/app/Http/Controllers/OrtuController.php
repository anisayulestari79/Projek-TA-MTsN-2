<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa; // Sesuaikan dengan nama model Siswa Anda
use App\Models\User;
use App\Models\Consultation; // Pastikan model Consultation dipanggil

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
            return view('ortu.dashboard', [
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
        return view('ortu.dashboard', compact('daftarAnak', 'siswaAktif'));
    }

    /**
     * Menampilkan Halaman Konsultasi BK untuk Orang Tua
     */
    public function konsultasi(Request $request)
    {
        $user = Auth::user();

        // Ambil daftar anak untuk pilihan di form dropdown
        $daftarAnak = $user->siswa;

        // Ambil riwayat konsultasi ortu ini dari tabel consultations, urutkan dari yang terbaru
        // Ganti with('siswa') menjadi with('student') sesuai dengan nama fungsi relasi di model Consultation Anda
        $riwayatKonsultasi = Consultation::with('student')
            ->where('parent_id', $user->id)
            ->latest()
            ->get();

        return view('ortu.konsultasi', compact('user', 'daftarAnak', 'riwayatKonsultasi'));
    }

    /**
     * Menyimpan pesan Konsultasi baru dari Orang Tua
     */
    public function kirimKonsultasi(Request $request)
    {
        // Validasi input dari form HTML
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'topik'    => 'required|string|max:255',
            'pesan'    => 'required|string',
        ]);

        // Simpan data ke tabel consultations menyesuaikan nama kolom di database Anda
        Consultation::create([
            'parent_id'  => Auth::id(),
            'student_id' => $request->siswa_id,
            'topic'      => $request->topik,
            'message'    => $request->pesan,
            'status'     => 'menunggu'
            // 'academic_period' => '...', // Tambahkan jika kolom ini wajib (NOT NULL) di database Anda
            // 'bk_id' => null, // Akan diisi saat guru BK merespon
        ]);

        return redirect()->back()->with('success', 'Pesan konsultasi berhasil dikirim ke Guru BK. Mohon menunggu balasan.');
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
