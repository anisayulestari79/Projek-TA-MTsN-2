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

        if (!$user) {
            return redirect()->route('ortu.login')->with('error', 'Silakan login kembali.');
        }

        // 2. Ambil data anak berdasarkan relasi database
        $daftarAnak = $user->siswa;

        // 3. Cek apakah orang tua ini memiliki anak yang terdaftar di sistem
        if (!$daftarAnak || $daftarAnak->isEmpty()) {
            return view('ortu.dashboard', [
                'daftarAnak' => collect([]),
                'siswaAktif' => null
            ]);
        }

        // 4. Tentukan profil anak mana yang sedang aktif / dilihat
        $siswaAktif = null;

        if ($request->has('siswa_id')) {
            $siswaAktif = $daftarAnak->where('id', $request->siswa_id)->first();
        }

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
        $user = \Illuminate\Support\Facades\Auth::user();

        // 1. Ambil daftar anak yang terhubung dengan akun Wali Murid ini
        $daftarAnak = \App\Models\Siswa::where('ortu_id', $user->id)->get();

        // 2. LOGIKA BARU: Cari Guru BK kelas binaan untuk masing-masing anak secara dinamis
        foreach ($daftarAnak as $anak) {
            // Cari data guru BK yang kelas binaannya (JSON) mengandung kelas anak saat ini
            $guruBk = \App\Models\Guru::where('role', 'bk')
                ->whereJsonContains('kelas_binaan', $anak->kelas)
                ->first();

            if ($guruBk) {
                // Temukan User ID Guru BK tersebut di tabel users berdasarkan NIP (untuk pengiriman pesan)
                $userBk = \App\Models\User::where('nip', $guruBk->nip)->first();

                $anak->guru_bk_id = $userBk ? $userBk->id : null;
                $anak->guru_bk_nama = "Guru BK Kelas " . $anak->kelas . " - " . $guruBk->nama;
            } else {
                $anak->guru_bk_id = null;
                $anak->guru_bk_nama = "Guru BK Kelas " . $anak->kelas . " (Belum Ditentukan)";
            }
        }

        // 3. Ambil riwayat konsultasi (baik yang dikirim Ortu maupun BK)
        $siswaIds = $daftarAnak->pluck('id')->toArray();

        $query = \App\Models\Consultation::with(['student', 'bk'])
            ->whereIn('student_id', $siswaIds)
            ->orderBy('created_at', 'desc');

        if ($request->has('tahun_akademik') && $request->tahun_akademik != '') {
            $query->where('academic_period', $request->tahun_akademik);
        }

        $riwayatKonsultasi = $query->get();

        return view('ortu.konsultasi', compact('daftarAnak', 'riwayatKonsultasi'));
    }

    /**
     * Menyimpan pesan Konsultasi baru dari Orang Tua
     */
    public function kirimKonsultasi(Request $request)
    {
        $request->validate([
            'siswa_id'        => 'required|exists:siswa,id',
            'academic_period' => 'required|string',
            'topik'           => 'required|string|max:255',
            'pesan'           => 'required|string',
        ]);

        $siswa = Siswa::findOrFail($request->siswa_id);

        // Cari guru BK berdasarkan kelas anak tersebut untuk mendapatkan bk_id
        $guruBk = \App\Models\Guru::where('role', 'bk')
            ->whereJsonContains('kelas_binaan', $siswa->kelas)
            ->first();

        if (!$guruBk) {
            return redirect()->back()->with('error', 'Guru BK untuk kelas ananda belum ditentukan oleh pihak sekolah.');
        }

        $userBk = \App\Models\User::where('nip', $guruBk->nip)->first();
        $bkId = $userBk ? $userBk->id : null;

        // Simpan pesan konsultasi ke database
        Consultation::create([
            'parent_id'       => Auth::id(),
            'student_id'      => $siswa->id,
            'bk_id'           => $bkId,
            'academic_period' => $request->academic_period,
            'topic'           => $request->topik,
            'message'         => $request->pesan,
            'status'          => 'menunggu',
            'pengirim'        => 'ortu' // Penanda dikirim oleh Orang Tua
        ]);

        // REDIRECT AMAN: Diarahkan ke rute GET agar terhindar dari MethodNotAllowedHttpException
        return redirect()->route('ortu.konsultasi')->with('success', 'Pesan Anda berhasil dikirim ke Guru BK!');
    }

    /**
     * Mengaitkan (Menambah) Anak ke Akun Orang Tua
     */
    public function tambahAnak(Request $request)
    {
        $request->validate([
            // Validasi: pastikan nisn diinput, bertipe string, dan panjangnya maksimal sesuai format NISN
            'nisn_tambahan' => 'required|string|max:20',
        ], [
            'nisn_tambahan.required' => 'NISN Anak wajib diisi.',
        ]);

        // Cari siswa berdasarkan NISN yang diinputkan
        $siswaBaru = Siswa::where('nisn', $request->nisn_tambahan)->first();

        // 1. Cek apakah NISN terdaftar di database sekolah
        if (!$siswaBaru) {
            return back()->with('error', 'Gagal: NISN tidak ditemukan di sistem madrasah. Silakan periksa kembali angka yang Anda masukkan.');
        }

        // 2. Cek apakah siswa ini sudah dikaitkan ke orang tua lain
        if ($siswaBaru->ortu_id !== null) {
            // Jika ortu_id sama dengan ID user yang sedang login
            if ($siswaBaru->ortu_id === Auth::id()) {
                return back()->with('success', 'Siswa ini memang sudah terhubung dengan akun Anda.');
            }
            // Jika ortu_id sudah terisi orang lain
            return back()->with('error', 'Gagal: Siswa ini sudah dikaitkan dengan akun Wali Murid lain. Hubungi Admin jika ini adalah kesalahan.');
        }

        // 3. Jika aman, hubungkan siswa ini ke akun orang tua yang sedang login
        $siswaBaru->ortu_id = Auth::id();
        $siswaBaru->save();

        return back()->with('success', 'Berhasil! Profil ananda ' . $siswaBaru->nama . ' telah ditambahkan ke akun Anda.');
    }

    /**
     * Menyimpan balasan/konfirmasi dari Orang Tua ke Guru BK
     */
    public function balasKonsultasi(Request $request, $id)
    {
        $request->validate([
            'balasan' => 'required|string'
        ]);

        $konsultasi = Consultation::findOrFail($id);

        $konsultasi->update([
            'reply'  => $request->balasan,
            'status' => 'dibalas' // Status otomatis berubah menjadi dibalas
        ]);

        return redirect()->back()->with('success', 'Balasan/Konfirmasi Anda berhasil dikirim ke pihak madrasah!');
    }
}
