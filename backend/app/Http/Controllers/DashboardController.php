<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\AuditLogin;
use App\Models\RiwayatPoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function admin()
    {
        if (!Session::has('auth_token') || !Session::has('user')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $sessionUser = Session::get('user');
        if ($sessionUser['role'] !== 'admin') {
            return redirect()->route('index')->with('error', 'Akses ditolak');
        }

        // Load fresh user data from database to get latest photo
        $userModel = User::find($sessionUser['id']);
        if ($userModel) {
            $user = [
                'id' => $userModel->id,
                'name' => $userModel->name,
                'email' => $userModel->email,
                'username' => $userModel->username,
                'role' => $userModel->role,
                'nip' => $userModel->nip,
                'gender' => $userModel->gender,
                'phone' => $userModel->phone,
                'photo' => $userModel->photo,
            ];
            // Update session with fresh data
            Session::put('user', $user);
        } else {
            $user = $sessionUser;
        }

        // ========================================================
        // DATA UNTUK GRAFIK & TABEL DI ADMIN DASHBOARD
        // ========================================================

        // 1. Data Monitoring Sanksi
        $countPanggilan1 = Siswa::whereBetween('poin', [25, 49])->count();
        $countPanggilan2 = Siswa::whereBetween('poin', [50, 99])->count();
        $countDropOut    = Siswa::where('poin', '>=', 100)->count();

        // 2. Data Grafik Diagram Batang
        $currentYear = Carbon::now()->year;
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartData   = [];

        for ($month = 1; $month <= 12; $month++) {
            $count = \App\Models\RiwayatPoin::whereYear('waktu', $currentYear)
                ->whereMonth('waktu', $month)
                ->where('jenis', 'Tambah')
                ->count();
            $chartData[] = $count;
        }

        // 3. Ambil data siswa dengan poin tertinggi untuk di tabel dashboard
        $siswaPelanggaran = Siswa::where('poin', '>', 0)->orderBy('poin', 'desc')->take(10)->get();

        // PASTIKAN MENGARAH KE VIEW ADMIN, BUKAN GURU
        return view('admin.admin-dashboard', compact(
            'user',
            'countPanggilan1',
            'countPanggilan2',
            'countDropOut',
            'chartLabels',
            'chartData',
            'siswaPelanggaran'
        ));
    }

    public function guru()
    {
        if (!Session::has('auth_token') || !Session::has('user')) {
            return redirect()->route('guru.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $sessionUser = Session::get('user');

        if (!in_array($sessionUser['role'], ['guru', 'bk', 'guru_bk'])) {
            return redirect()->route('index')->with('error', 'Akses ditolak');
        }

        // Load fresh user data from database to get latest photo
        $userModel = User::find($sessionUser['id']);
        if ($userModel) {
            $user = [
                'id' => $userModel->id,
                'name' => $userModel->name,
                'email' => $userModel->email,
                'username' => $userModel->username,
                'role' => $userModel->role,
                'nip' => $userModel->nip,
                'gender' => $userModel->gender,
                'phone' => $userModel->phone,
                'photo' => $userModel->photo,
            ];
            // Update session with fresh data
            Session::put('user', $user);
        } else {
            $user = $sessionUser;
        }

        // ========================================================
        // TAMBAHAN DATA UNTUK DASHBOARD GURU
        // ========================================================

        // 1. Ambil SEMUA data siswa untuk menu "Data Master Siswa" (Tanpa filter kelas binaan)
        $dataSiswa = \App\Models\Siswa::with('ortu')->orderBy('kelas', 'asc')->orderBy('nama', 'asc')->get();
        $totalSiswa = $dataSiswa->count();

        // 2. Ambil Referensi Pelanggaran
        $dataPelanggaran = \App\Models\Pelanggaran::all();

        // 3. Mengambil daftar kelas
        try {
            $daftarKelas = \App\Models\Kelas::orderBy('nama_kelas')->pluck('nama_kelas');
        } catch (\Exception $e) {
            $daftarKelas = \App\Models\Siswa::select('kelas')->distinct()->pluck('kelas')->filter()->sort()->values();
        }

        // 4. Hitung Input Poin Hari Ini
        $inputHariIni = \App\Models\RiwayatPoin::whereDate('waktu', \Carbon\Carbon::today())->count();


        // === LOGIKA KHUSUS GURU BK (Mencari Siswa Binaan & Konsultasinya) ===
        $guruDb = \App\Models\Guru::where('nip', $user['nip'])->first();
        $siswaBinaan = collect([]);
        $konsultasi = collect([]);

        // Cek apakah yang login adalah BK dan memiliki kelas binaan
        if (in_array($user['role'], ['bk', 'guru_bk']) && $guruDb && $guruDb->kelas_binaan) {
            $kelasArray = json_decode($guruDb->kelas_binaan, true);

            if (is_array($kelasArray) && count($kelasArray) > 0) {
                // HANYA Ambil Siswa Binaan untuk menu "Data Kelas Binaan"
                $siswaBinaan = \App\Models\Siswa::with('ortu')->whereIn('kelas', $kelasArray)->orderBy('nama', 'asc')->get();

                // Ambil daftar konsultasi HANYA untuk siswa-siswa binaan tersebut
                $siswaIds = $siswaBinaan->pluck('id')->toArray();
                $konsultasi = \App\Models\Consultation::with(['student', 'parent'])
                    ->whereIn('student_id', $siswaIds)
                    ->latest()
                    ->get();
            }
        }

        // Mengambil data Riwayat Laporan yang dikirim oleh Guru BK yang sedang login
        $riwayatLaporan = \App\Models\ArsipLaporan::where('user_id', $user['id'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Tampilkan Dashboard Guru/BK dengan data yang sudah di-filter
        return view('guru.dashboard', compact(
            'user',
            'dataSiswa',
            'totalSiswa',
            'dataPelanggaran',
            'daftarKelas',
            'inputHariIni',
            'siswaBinaan',
            'konsultasi',
            'riwayatLaporan'
        ));
    }

    // ========================================================
    // FUNGSI UNTUK CETAK LAPORAN FORMAL (GURU/BK)
    // ========================================================
    public function cetakLaporan(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('guru.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Session::get('user');
        $guruDb = \App\Models\Guru::where('nip', $user['nip'])->first();

        $querySiswa = \App\Models\Siswa::with('ortu')->orderBy('kelas', 'asc')->orderBy('nama', 'asc');

        // Menangkap parameter dari URL
        $type = $request->query('type', 'binaan');
        $tingkat = $request->query('tingkat', '');
        $kelas = $request->query('kelas', '');

        // 1. Filter Dasarnya (Apakah Binaan BK atau Semua Anak)
        if ($type === 'binaan' && in_array($user['role'], ['bk', 'guru_bk']) && $guruDb && $guruDb->kelas_binaan) {
            $kelasArray = json_decode($guruDb->kelas_binaan, true);
            if (is_array($kelasArray) && count($kelasArray) > 0) {
                $querySiswa->whereIn('kelas', $kelasArray);
            }
        }

        // 2. Terapkan Filter Tingkat Tambahan (Jika Diisi di UI)
        if (!empty($tingkat)) {
            $querySiswa->where('kelas', 'LIKE', $tingkat . '%');
        }

        // 3. Terapkan Filter Kelas Tambahan (Jika Diisi di UI)
        if (!empty($kelas)) {
            $querySiswa->where('kelas', 'LIKE', '%' . $kelas);
        }

        // 4. Logika Penulisan Judul Laporan Agar Estetik & Dinamis
        $filterText = "";
        if (!empty($tingkat) && !empty($kelas)) {
            $filterText = "Kelas " . $tingkat . " " . $kelas;
        } elseif (!empty($tingkat)) {
            $filterText = "Tingkat " . $tingkat;
        } elseif (!empty($kelas)) {
            $filterText = "Ruang Kelas " . $kelas;
        }

        if ($type === 'binaan') {
            $judulKategori = 'Kelas Binaan';
            if ($filterText) $judulKategori .= ' (' . $filterText . ')';
        } else {
            $judulKategori = 'Seluruh Siswa';
            if ($filterText) $judulKategori .= ' (' . $filterText . ')';
        }

        $dataSiswa = $querySiswa->get();

        return view('guru.cetak-laporan', compact('user', 'dataSiswa', 'judulKategori'));
    }

    // ========================================================
    // FUNGSI UNTUK MENAMPILKAN HALAMAN AUDIT LOG
    // ========================================================
    public function auditLog()
    {
        // 1. Cek Autentikasi (Hanya Admin yang boleh melihat Log)
        if (!Session::has('auth_token') || !Session::has('user')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $sessionUser = Session::get('user');
        if ($sessionUser['role'] !== 'admin') {
            return redirect()->route('index')->with('error', 'Akses ditolak');
        }

        // 2. Load fresh user data (seperti di method admin())
        $userModel = User::find($sessionUser['id']);
        $user = $userModel ? $userModel->toArray() : $sessionUser;

        // 3. Mengambil data log terbaru beserta relasi data user-nya, dibatasi 15 per halaman
        $logs = AuditLogin::with('user')->orderBy('login_at', 'desc')->paginate(15);

        // 4. Tampilkan View
        return view('admin.admin-audit', compact('user', 'logs'));
    }

    // ========================================================
    // FUNGSI UNTUK MENAMPILKAN HALAMAN DASHBOARD KAMAD
    // ========================================================
    public function kamad()
    {
        if (!Session::has('auth_token') || !Session::has('user')) {
            return redirect()->route('kamad.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $sessionUser = Session::get('user');
        // Pastikan hanya role kamad yang bisa mengakses
        if ($sessionUser['role'] !== 'kamad') {
            return redirect()->route('index')->with('error', 'Akses ditolak');
        }

        $userModel = \App\Models\User::find($sessionUser['id']);
        $user = $userModel ? $userModel->toArray() : $sessionUser;

        // 1. Data Ringkasan (Real-time dari Database)
        $totalPelanggaran = \App\Models\RiwayatPoin::count();
        $waspada = \App\Models\Siswa::whereBetween('poin', [50, 99])->count();
        $dropOut = \App\Models\Siswa::where('poin', '>=', 100)->count();

        // 2. Data Grafik Bulanan (Sama seperti Admin)
        $currentYear = \Carbon\Carbon::now()->year;
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartData   = [];

        for ($month = 1; $month <= 12; $month++) {
            $count = \App\Models\RiwayatPoin::whereYear('waktu', $currentYear)
                ->whereMonth('waktu', $month)
                ->where('jenis', 'Tambah')
                ->count();
            $chartData[] = $count;
        }

        // 3. Tambahan Data Laporan Terbaru
        // Mengambil 3 data terakhir dari tabel arsip_laporans
        $laporanTerbaru = \App\Models\ArsipLaporan::with('user')->latest()->take(3)->get();

        // Tampilkan ke view kamad/kamad-dashboard.blade.php
        return view('kamad.kamad-dashboard', compact(
            'user',
            'totalPelanggaran',
            'waspada',
            'dropOut',
            'chartLabels',
            'chartData',
            'laporanTerbaru'
        ));
    }
}
