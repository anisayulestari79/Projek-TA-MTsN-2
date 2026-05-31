<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // 1. Fungsi untuk menampilkan halaman view profil (Blade)
    public function index()
    {
        // 1. Coba ambil dari Auth bawaan Laravel
        $user = Auth::user();

        // 2. Jika Auth kosong, coba ambil dari Session manual (Sesuai dengan sistem Anda)
        if (!$user && session()->has('user')) {
            // Ubah array session menjadi object agar tidak error saat dipanggil $user->role di Blade
            $user = (object) session('user');
        }

        // 3. Jika MASIH kosong, berarti benar-benar belum login. Tendang kembali ke halaman utama!
        if (!$user) {
            return redirect('/')->with('error', 'Akses ditolak! Silakan login terlebih dahulu.');
        }

        // Memanggil file view yang sudah Anda buat tadi
        return view('profile.index', compact('user'));
    }

    // 2. Fungsi untuk menangani update (bisa untuk Web dan API)
    public function update(Request $request)
    {
        // Terapkan logika yang sama untuk update data
        $user = Auth::user();

        if (!$user && session()->has('user')) {
            // Mengambil data user asli dari database berdasarkan ID di session
            // Asumsi model Anda bernama User dan session menyimpan 'id'
            $user = \App\Models\User::find(session('user')['id']);
        }

        if (!$user) {
            return redirect('/')->with('error', 'Sesi Anda telah habis. Silakan login kembali.');
        }

        // Validasi: Perhatikan bahwa 'photo' sekarang memvalidasi file gambar (image/mimes/max)
        $validator = Validator::make($request->all(), [
            'name'     => 'sometimes|required|string|max:255',
            'gender'   => 'nullable|string',
            'phone'    => 'nullable|string',
            'photo'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // File maksimal 2MB
            'password' => 'nullable|string|min:6',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            // Jika request dari API (Postman/Mobile), kembalikan JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validator->errors()
                ], 422);
            }
            // Jika request dari Web (Browser), kembalikan ke halaman sebelumnya beserta error
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [];

        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }

        if ($request->filled('gender')) {
            // Menyimpan ke kolom 'jk' (sesuai database Anda) atau 'gender'
            // Silakan sesuaikan nama kolom ini jika di database Anda namanya 'jk'
            $updateData['jk'] = $request->gender;
        }

        if ($request->filled('phone')) {
            $updateData['phone'] = $request->phone;
        }

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // TANGANI UPLOAD FILE FOTO
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada dan bukan bawaan sistem
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Simpan foto baru ke folder storage/app/public/profiles
            $path = $request->file('photo')->store('profiles', 'public');
            $updateData['photo'] = $path;
        }

        // Eksekusi update ke database
        $user->update($updateData);

        // Perbarui session manual jika Anda menggunakannya
        if (session()->has('user')) {
            session(['user' => $user->toArray()]);
        }

        // Jika request dari API (Postman/Mobile), kembalikan JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data'    => $user
            ]);
        }

        // Jika request dari Web (Browser), reload halaman dengan pesan sukses
        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    // Fungsi show untuk API (tetap dipertahankan)
    public function show(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $request->user()
        ]);
    }
}
