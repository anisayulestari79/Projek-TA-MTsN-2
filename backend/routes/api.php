<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\GuruController;
use App\Http\Controllers\Api\PoinController;
use App\Http\Controllers\Api\PelanggaranController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login/admin', [AuthController::class, 'loginAdmin']);
Route::post('/login/guru', [AuthController::class, 'loginGuru']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // Siswa routes
    Route::get('/siswa', [SiswaController::class, 'index']);
    Route::get('/siswa/{nisn}', [SiswaController::class, 'show']);
    Route::post('/siswa', [SiswaController::class, 'store']);
    Route::post('/siswa/import', [SiswaController::class, 'importExcel']);
    Route::put('/siswa/{nisn}', [SiswaController::class, 'update']);
    Route::delete('/siswa/{nisn}', [SiswaController::class, 'destroy']);

    // Guru routes (admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/guru', [GuruController::class, 'index']);
        Route::get('/guru/{id}', [GuruController::class, 'show']);
        Route::post('/guru', [GuruController::class, 'store']);
        Route::post('/guru/import', [GuruController::class, 'importExcel']);
        Route::put('/guru/{id}', [GuruController::class, 'update']);
        Route::delete('/guru/{id}', [GuruController::class, 'destroy']);
    });

    // Poin routes
    Route::get('/poin/dashboard', [PoinController::class, 'getDashboard']);
    Route::post('/poin', [PoinController::class, 'addPoin']);
    Route::get('/poin/riwayat', [PoinController::class, 'getRiwayat']);
    Route::delete('/poin/riwayat/{id}', [PoinController::class, 'deleteRiwayat']);
    Route::delete('/poin/riwayat', [PoinController::class, 'deleteAllRiwayat']);

    // Pelanggaran routes
    Route::get('/pelanggaran', [PelanggaranController::class, 'index']);
    Route::get('/pelanggaran/{id}', [PelanggaranController::class, 'show']);
});

