<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KamadController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Api\GuruController;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\PoinController;
use App\Http\Controllers\OrtuController;
use App\Http\Controllers\OrtuAuthController;

// =======================================================
// 1. PUBLIC ROUTES (Halaman Utama)
// =======================================================
Route::get('/', [PageController::class, 'index'])->name('index');

// =======================================================
// 2. AUTHENTICATION ROUTES (Login & Register)
// =======================================================
// --- Admin ---
Route::get('/admin/login', [PageController::class, 'adminLogin'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'adminLogin'])->name('admin.login.submit');
Route::get('/admin/register', [PageController::class, 'adminRegister'])->name('admin.register');
Route::post('/admin/register', [LoginController::class, 'registerAdmin'])->name('admin.register.submit');

// --- Guru ---
Route::get('/guru/login', [PageController::class, 'guruLogin'])->name('guru.login');
Route::post('/guru/login', [LoginController::class, 'guruLogin'])->name('guru.login.submit');

// --- Kepala Madrasah (Kamad) ---
Route::get('/kamad/login', function () {
    return view('kamad.login_kamad');
})->name('kamad.login');
Route::post('/kamad/login', [LoginController::class, 'kamadLogin'])->name('kamad.login.submit');

// --- Orang Tua (Ortu) ---
Route::get('/ortu/login', function () {
    return view('ortu.login');
})->name('ortu.login');
Route::post('/ortu/login', [LoginController::class, 'ortuLogin'])->name('ortu.login.submit');
Route::get('/ortu/register', function () {
    return view('ortu.register');
})->name('ortu.register');
Route::post('/ortu/register', [LoginController::class, 'registerOrtu'])->name('ortu.register.submit');

// --- Umum / Default ---
Route::get('/register', [PageController::class, 'register'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.submit');

// --- Logout ---
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// =======================================================
// 3. PROTECTED ROUTES (Membutuhkan Login)
// =======================================================
Route::middleware('web')->group(function () {

    // --- DASHBOARD UTAMA ---
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/guru/dashboard', [DashboardController::class, 'guru'])->name('guru.dashboard');

    // 👇 NAMA ROUTE DISERAGAMKAN MENJADI 'kamad.dashboard'
    Route::get('/kamad/dashboard', [DashboardController::class, 'kamad'])->name('kamad.kamad-dashboard');

    // 👇 NAMA ROUTE DISERAGAMKAN MENJADI 'kamad.laporan'
    Route::get('/kamad/laporan-masuk', [KamadController::class, 'laporanMasuk'])->name('kamad.kamad-laporan');

    Route::get('/kamad/poin', [KamadController::class, 'poinKeseluruhan'])->name('kamad.kamad-poin');

    // 👇 ROUTE PROFIL DITAMBAHKAN KEMBALI
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // =======================================================
    // 4. RUTE KHUSUS ADMIN (Prefix: /admin, Name: admin.)
    // =======================================================
    Route::prefix('admin')->name('admin.')->group(function () {

        // --- DATA GURU ---
        Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
        Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
        Route::put('/guru/{id}', [GuruController::class, 'update'])->name('guru.update');
        Route::delete('/guru/{id}', [GuruController::class, 'destroy'])->name('guru.destroy');

        // --- DATA SISWA ---
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::post('/siswa/import', [SiswaController::class, 'importExcel'])->name('siswa.import');
        Route::put('/siswa/{nisn}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{nisn}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

        // --- DATA KONSULTASI BK ---
        Route::get('/konsultasi', [ConsultationController::class, 'index'])->name('konsultasi.index');
        Route::get('/konsultasi/{id}', [ConsultationController::class, 'show'])->name('konsultasi.show');
        Route::put('/konsultasi/{id}/reply', [ConsultationController::class, 'reply'])->name('konsultasi.reply');
        Route::patch('/konsultasi/{id}/complete', [ConsultationController::class, 'markAsComplete'])->name('konsultasi.complete');

        // --- DATA POIN SISWA ---
        Route::get('/poin', function () {
            return view('admin.admin-poin', [
                'user' => session('user'),
                'dataPelanggaran' => \App\Models\Pelanggaran::all()
            ]);
        })->name('poin.index');

        Route::get('/poin/siswa-data', [PoinController::class, 'getDashboard'])->name('poin.getDashboard');
        Route::get('/poin/search-siswa', [PoinController::class, 'searchSiswa'])->name('poin.searchSiswa');

        Route::post('/poin/add', [PoinController::class, 'store'])->name('poin.addPoin');
        Route::get('/poin/riwayat-data', [PoinController::class, 'getRiwayat'])->name('poin.getRiwayat');
        Route::delete('/poin/riwayat/{id}', [PoinController::class, 'deleteRiwayat'])->name('poin.deleteRiwayat');
        Route::delete('/poin/riwayat-clear', [PoinController::class, 'deleteAllRiwayat'])->name('poin.deleteAllRiwayat');
        Route::get('/riwayat/api/{nisn}', [PoinController::class, 'getRiwayatApi'])->name('riwayat.api');

        // --- KIRIM LAPORAN KE KAMAD ---
        Route::post('/laporan/kirim', [LaporanController::class, 'store'])->name('laporan.kirim');

        // --- AUDIT LOG ---
        Route::get('/audit-log', [DashboardController::class, 'auditLog'])->name('audit.index');
    });

    Route::middleware(['auth', 'role:ortu'])->group(function () {
        Route::get('/dashboard-ortu', [OrtuController::class, 'index'])->name('ortu.dashboard');
    });

    // Grup rute untuk tamu (belum login)
    Route::middleware('guest')->group(function () {
        Route::get('/login-ortu', [OrtuAuthController::class, 'showLogin'])->name('ortu.login');
        Route::post('/login-ortu', [OrtuAuthController::class, 'login'])->name('ortu.login.submit');

        Route::get('/register-ortu', [OrtuAuthController::class, 'showRegister'])->name('ortu.register');
        Route::post('/register-ortu', [OrtuAuthController::class, 'register'])->name('ortu.register.submit');
    });

    // Grup rute yang harus masuk (sudah login)
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard-ortu', [OrtuController::class, 'index'])->name('ortu.dashboard');
        Route::post('/logout-ortu', [OrtuAuthController::class, 'logout'])->name('ortu.logout');
    });

    // --- DASHBOARD & FITUR ORANG TUA ---
    Route::get('/ortu/dashboard', [App\Http\Controllers\OrtuController::class, 'index'])->name('ortu.dashboard');
    Route::get('/ortu/konsultasi', [App\Http\Controllers\OrtuController::class, 'konsultasi'])->name('ortu.konsultasi');
    Route::post('/ortu/konsultasi/kirim', [App\Http\Controllers\OrtuController::class, 'kirimKonsultasi'])->name('ortu.konsultasi.kirim');
});
