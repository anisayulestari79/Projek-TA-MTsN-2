<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;

class OrtuAuthController extends Controller
{
    /**
     * Menampilkan halaman login khusus Wali Murid
     */
    public function showLogin()
    {
        return view('auth.login-ortu');
    }

    /**
     * Memproses masuk (Login) akun Wali Murid
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginField = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginField => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();

            if ($user->role === 'ortu') {
                $request->session()->regenerate();
                return redirect()->intended(route('ortu.dashboard'))
                    ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            }

            Auth::logout();
            return back()->withErrors([
                'username' => 'Akses ditolak. Portal login ini khusus untuk akun Wali Murid.',
            ])->withInput($request->only('username'));
        }

        return back()->withErrors([
            'username' => 'Username/email atau kata sandi Anda salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Menampilkan halaman registrasi Wali Murid
     */
    public function showRegister()
    {
        return view('auth.register-ortu');
    }

    /**
     * Memproses pendaftaran akun Wali Murid baru
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'nisn_anak' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan orang lain.',
            'email.unique' => 'Email ini sudah terdaftar dalam sistem.',
            'password.confirmed' => 'Konfirmasi kata sandi Anda tidak cocok.',
            'password.min' => 'Kata sandi minimal harus 8 karakter.',
            'nisn_anak.required' => 'NISN anak wajib diisi untuk verifikasi siswa.',
        ]);

        $siswa = Siswa::where('nisn', $request->nisn_anak)->first();

        if (!$siswa) {
            return back()->withErrors([
                'nisn_anak' => 'NISN Anak tidak ditemukan dalam sistem sekolah. Silakan hubungi TU/Admin sekolah.',
            ])->withInput();
        }

        if ($siswa->ortu_id !== null) {
            return back()->withErrors([
                'nisn_anak' => 'Siswa dengan NISN ini sudah memiliki akun Wali Murid terdaftar.',
            ])->withInput();
        }

        $parent = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'ortu',
            'phone' => $request->phone,
            'gender' => $request->gender,
        ]);

        $siswa->ortu_id = $parent->id;
        $siswa->save();

        Auth::login($parent);

        return redirect()->route('ortu.dashboard')
            ->with('success', 'Akun berhasil dibuat! Selamat memantau perkembangan ananda ' . $siswa->nama);
    }

    /**
     * Memproses keluar (Logout) akun Wali Murid
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('ortu.login')->with('success', 'Anda telah keluar dari portal sistem.');
    }
}
