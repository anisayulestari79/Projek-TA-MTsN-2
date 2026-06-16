<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * 1. Menampilkan halaman profil (Universal Web)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Fallback ke Session manual jika Auth bawaan Laravel kosong
        if (!$user && session()->has('user')) {
            $user = (object) session('user');
        }

        // Jika belum login, tendang ke halaman login
        if (!$user) {
            return redirect('/')->with('error', 'Akses ditolak! Silakan login terlebih dahulu.');
        }

        // Memanggil file view profil universal
        return view('profile.index', compact('user'));
    }

    /**
     * 2. Memproses update data profil (Mendukung Web Biasa & AJAX/API)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user && session()->has('user')) {
            $user = User::find(session('user')['id']);
        }

        // Jika user tidak ditemukan
        if (!$user) {
            if ($request->wantsJson() || $request->is('api/*') || $request->header('Accept') == 'application/json') {
                return response()->json(['success' => false, 'message' => 'Sesi habis, silakan login ulang.'], 401);
            }
            return redirect('/')->with('error', 'Sesi Anda telah habis. Silakan login kembali.');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name'      => 'sometimes|required|string|max:255',
            'gender'    => 'nullable|string',
            'phone'     => 'nullable|string|max:20',
            'pekerjaan' => 'nullable|string|max:255',
            'alamat'    => 'nullable|string',
            'photo'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password'  => 'nullable|string|min:6',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            if ($request->wantsJson() || $request->is('api/*') || $request->header('Accept') == 'application/json') {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal, periksa isian Anda.',
                    'errors'  => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal memperbarui profil. Periksa isian Anda.');
        }

        // Persiapkan data yang akan diupdate
        $updateData = [];

        if ($request->filled('name')) $updateData['name'] = $request->name;
        if ($request->filled('gender')) $updateData['gender'] = $request->gender;
        if ($request->filled('phone')) $updateData['phone'] = $request->phone;
        if ($request->filled('pekerjaan')) $updateData['pekerjaan'] = $request->pekerjaan;
        if ($request->filled('alamat')) $updateData['alamat'] = $request->alamat;

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Proses Upload Foto Baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada (dan pastikan bukan URL default awalan http)
            if ($user->photo && !str_starts_with($user->photo, 'http') && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Simpan foto baru ke folder profiles
            $path = $request->file('photo')->store('profiles', 'public');
            $updateData['photo'] = $path;
        }

        // Eksekusi Simpan ke Database
        $user->update($updateData);

        // Perbarui Session manual jika menggunakan sistem session array
        if (session()->has('user')) {
            session(['user' => $user->toArray()]);
        }

        // RESPON AJAX/API (Jika form dikirim dari Modal Dasbor Ortu menggunakan Fetch JS)
        if ($request->wantsJson() || $request->is('api/*') || $request->header('Accept') == 'application/json') {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'data'    => $user
            ]);
        }

        // RESPON WEB (Jika form dikirim dari Halaman Universal Profil)
        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * 3. Menampilkan data user spesifik (Khusus Request API GET)
     */
    public function show(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $request->user()
        ]);
    }
}
