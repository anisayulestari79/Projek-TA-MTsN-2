<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('username', $request->username)
            ->where('role', 'admin')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'role' => $user->role,
                    'nip' => $user->nip,
                    'gender' => $user->gender,
                    'phone' => $user->phone,
                    'photo' => $user->photo,
                ],
                'token' => $token
            ]
        ]);
    }

    public function loginGuru(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cari di tabel users terlebih dahulu
        $user = User::where('nip', $request->nip)
            ->where('role', 'guru')
            ->first();

        // Jika tidak ditemukan di users, cek di tabel guru
        if (!$user) {
            // Cari guru berdasarkan NIP
            $guru = Guru::where('nip', $request->nip)->first();

            if ($guru && $guru->nip) {
                // Verifikasi password (password di tabel guru disimpan plain text)
                if ($guru->password === $request->password || Hash::check($request->password, $guru->password)) {
                    // Tentukan login ID (NIP)
                    $loginId = $guru->nip;
                    
                    // Generate email
                    $email = $loginId . '@mtsn2bjm.sch.id';
                    
                    // Check if email already exists
                    $existingEmail = User::where('email', $email)->first();
                    if ($existingEmail) {
                        $email = $loginId . '_' . time() . '@mtsn2bjm.sch.id';
                    }

                    // Buat user di tabel users secara otomatis
                    $user = User::create([
                        'name' => $guru->nama,
                        'email' => $email,
                        'nip' => $loginId,
                        'role' => 'guru',
                        'gender' => $guru->jk,
                        'password' => Hash::make($guru->password), // Hash password dari guru
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'NIP atau password salah'
                    ], 401);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'NIP atau password salah'
                ], 401);
            }
        } else {
            // User ditemukan, verifikasi password
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIP atau password salah'
                ], 401);
            }
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'nip' => $user->nip,
                    'role' => $user->role,
                    'gender' => $user->gender,
                    'phone' => $user->phone,
                    'photo' => $user->photo,
                ],
                'token' => $token
            ]
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru', // Default role
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => $user
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }
}

