<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa; // Tambahan untuk memanggil model Siswa
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // ==========================================================
    // LOGIN ADMIN
    // ==========================================================
    public function adminLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $user = User::where('username', $request->username)
                ->where('role', 'admin')
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return back()->with('error', 'Username atau password salah');
            }

            // Mendaftarkan sesi login ke sistem keamanan inti Laravel
            Auth::login($user);

            // Buat token untuk session API (jika digunakan)
            $token = $user->createToken('auth_token')->plainTextToken;

            // Simpan token dan user di session manual
            Session::put('auth_token', $token);
            Session::put('user', [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'username' => $user->username,
                'role'     => $user->role,
                'nip'      => $user->nip,
                'gender'   => $user->gender,
                'phone'    => $user->phone,
                'photo'    => $user->photo,
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // LOGIN KAMAD
    // ==========================================================
    public function kamadLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string', // Namanya tetap 'username' sesuai name input di HTML
            'password' => 'required|string',
        ]);

        try {
            // Cek login menggunakan username ATAU nip ATAU email
            $user = User::where('role', 'kamad')
                ->where(function ($query) use ($request) {
                    $query->where('username', $request->username)
                        ->orWhere('nip', $request->username)
                        ->orWhere('email', $request->username);
                })
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return back()->with('error', 'Username/NIP atau password salah');
            }

            // Mendaftarkan sesi login ke sistem keamanan inti Laravel
            Auth::login($user);

            // Buat token untuk session
            $token = $user->createToken('auth_token')->plainTextToken;

            // Simpan token dan user di session manual
            Session::put('auth_token', $token);
            Session::put('user', [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'username' => $user->username,
                'role'     => $user->role,
                'nip'      => $user->nip,
                'gender'   => $user->gender,
                'phone'    => $user->phone,
                'photo'    => $user->photo,
            ]);

            return redirect()->route('kamad.dashboard')->with('success', 'Selamat datang di Panel Pimpinan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // LOGIN GURU
    // ==========================================================
    public function guruLogin(Request $request)
    {
        $request->validate([
            'nip'      => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            // Cari di tabel users terlebih dahulu
            $user = User::where('nip', $request->nip)
                ->where('role', 'guru')
                ->first();

            // Jika tidak ditemukan di users, cek di tabel guru (integrasi otomatis)
            if (!$user) {
                // Cari guru berdasarkan NIP
                $guru = Guru::where('nip', $request->nip)->first();

                if ($guru && $guru->nip) {
                    // Verifikasi password (bisa plain text atau hashed)
                    if ($guru->password === $request->password || Hash::check($request->password, $guru->password)) {
                        $loginId = $guru->nip;
                        $email = $loginId . '@mtsn2bjm.sch.id';

                        // Cegah duplikasi email
                        $existingEmail = User::where('email', $email)->first();
                        if ($existingEmail) {
                            $email = $loginId . '_' . time() . '@mtsn2bjm.sch.id';
                        }

                        // Buat user di tabel users secara otomatis
                        $user = User::create([
                            'name'     => $guru->nama,
                            'email'    => $email,
                            'nip'      => $loginId,
                            'role'     => 'guru',
                            'gender'   => $guru->jk,
                            'password' => Hash::make($guru->password), // Hash password dari guru
                        ]);
                    } else {
                        return back()->with('error', 'NIP atau password salah');
                    }
                } else {
                    return back()->with('error', 'NIP atau password salah');
                }
            } else {
                // User ditemukan, verifikasi password
                if (!Hash::check($request->password, $user->password)) {
                    return back()->with('error', 'NIP atau password salah');
                }
            }

            // Mendaftarkan sesi login ke sistem keamanan inti Laravel
            Auth::login($user);

            // Buat token untuk session
            $token = $user->createToken('auth_token')->plainTextToken;

            // Simpan token dan user di session manual
            Session::put('auth_token', $token);
            Session::put('user', [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'nip'      => $user->nip,
                'role'     => $user->role,
                'gender'   => $user->gender,
                'phone'    => $user->phone,
                'photo'    => $user->photo,
            ]);

            return redirect()->route('guru.dashboard')->with('success', 'Login berhasil!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // LOGIN ORANG TUA
    // ==========================================================
    public function ortuLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|string', // Bisa menggunakan email atau username
            'password' => 'required|string',
        ]);

        try {
            // Cek login menggunakan email ATAU username
            $user = User::where('role', 'ortu')
                ->where(function ($query) use ($request) {
                    $query->where('email', $request->email)
                        ->orWhere('username', $request->email);
                })
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return back()->with('error', 'Email/Username atau password salah');
            }

            // Mendaftarkan sesi login ke sistem keamanan inti Laravel
            Auth::login($user);

            // Buat token untuk session
            $token = $user->createToken('auth_token')->plainTextToken;

            // Simpan token dan user di session manual
            Session::put('auth_token', $token);
            Session::put('user', [
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'username' => $user->username,
                'role'     => $user->role,
            ]);

            return redirect()->route('ortu.dashboard')->with('success', 'Login berhasil!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ==========================================================
    // REGISTER ADMIN
    // ==========================================================
    public function registerAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            User::create([
                'name'     => $request->name,
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'admin',
            ]);

            return redirect()->route('admin.login')->with('success', 'Pendaftaran berhasil! Silakan login dengan username dan password Anda.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    // ==========================================================
    // REGISTER ORANG TUA (DENGAN SINKRONISASI NISN OTOMATIS)
    // ==========================================================
    public function registerOrtu(Request $request)
    {
        // 1. Validasi Input, termasuk NISN yang wajib 10 angka
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'nisn'     => 'required|numeric|digits:10', // Wajib 10 digit angka
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nisn.digits' => 'NISN harus berisi tepat 10 angka.',
            'nisn.numeric' => 'NISN hanya boleh berisi angka.'
        ]);

        try {
            // 2. Cek apakah NISN tersebut benar-benar ada di tabel siswa
            $siswa = Siswa::where('nisn', $request->nisn)->first();

            if (!$siswa) {
                // Jika NISN tidak ditemukan di database, tolak pendaftaran
                return back()->withErrors(['nisn' => 'NISN tidak ditemukan di sistem madrasah. Pastikan 10 digit NISN anak Anda benar.'])->withInput();
            }

            // 3. Jika NISN valid, Buat akun Orang Tua
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'ortu',
                // Otomatis generate username dari email (sebelum @)
                'username' => explode('@', $request->email)[0] . rand(100, 999),
            ]);

            // 4. Hubungkan secara OTOMATIS: Update kolom ortu_id pada data siswa dengan ID user orang tua yang baru dibuat
            $siswa->update([
                'ortu_id' => $user->id
            ]);

            // 5. Redirect ke halaman login dengan pesan sukses yang menyertakan nama anak
            return redirect()->route('ortu.login')->with('success', 'Pendaftaran berhasil! Akun Anda telah otomatis terhubung dengan data ananda ' . $siswa->nama . '. Silakan masuk.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    // ==========================================================
    // LOGOUT (Keluarga Besar)
    // ==========================================================
    public function logout(Request $request)
    {
        // 1. Hapus token API/Sanctum dari database jika ada
        $token = Session::get('auth_token');
        if ($token) {
            try {
                $user = Session::get('user');
                if ($user) {
                    $userModel = User::find($user['id']);
                    if ($userModel) {
                        $userModel->tokens()->delete();
                    }
                }
            } catch (\Exception $e) {
                // Ignore error on logout
            }
        }

        // 2. Logout dari sistem Auth inti Laravel
        Auth::logout();

        // 3. Bersihkan seluruh session manual secara tuntas & aman
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index')->with('success', 'Logout berhasil');
    }
}
