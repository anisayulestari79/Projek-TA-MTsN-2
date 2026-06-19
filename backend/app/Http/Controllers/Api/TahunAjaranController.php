<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TahunAjaranController extends Controller
{
    /**
     * Menampilkan Halaman Manajemen Tahun Ajaran
     */
    public function index()
    {
        $user = Session::get('user');

        // Mengambil semua tahun ajaran diurutkan dari yang terbaru
        $tahunAjarans = TahunAjaran::orderBy('nama', 'desc')->orderBy('semester', 'desc')->get();

        return view('admin.admin-tahunajaran', compact('user', 'tahunAjarans'));
    }

    /**
     * Menyimpan Tahun Ajaran Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50', // Format: 2024/2025
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        // Cek apakah sudah ada yang persis sama
        $exists = TahunAjaran::where('nama', $request->nama)
            ->where('semester', $request->semester)
            ->first();

        if ($exists) {
            return redirect()->back()->with('error', 'Tahun Ajaran tersebut sudah ada di sistem!');
        }

        // Cek apakah ini tahun ajaran pertama yang dibuat
        $isFirst = TahunAjaran::count() === 0;

        TahunAjaran::create([
            'nama' => $request->nama,
            'semester' => $request->semester,
            // Jika ini yang pertama, otomatis jadikan aktif
            'is_active' => $isFirst ? true : false,
        ]);

        return redirect()->back()->with('success', 'Tahun Ajaran berhasil ditambahkan!');
    }

    /**
     * Menjadikan Tahun Ajaran Tertentu Sebagai "Aktif"
     */
    public function setAktif($id)
    {
        // 1. Matikan semua tahun ajaran yang ada
        TahunAjaran::query()->update(['is_active' => false]);

        // 2. Aktifkan hanya tahun ajaran yang dipilih
        $ta = TahunAjaran::findOrFail($id);
        $ta->is_active = true;
        $ta->save();

        return redirect()->back()->with('success', "Tahun Ajaran {$ta->nama} Semester {$ta->semester} berhasil ditetapkan sebagai Tahun Ajaran Aktif!");
    }

    /**
     * Menghapus Tahun Ajaran
     */
    public function destroy($id)
    {
        $ta = TahunAjaran::findOrFail($id);

        if ($ta->is_active) {
            return redirect()->back()->with('error', 'Gagal! Tidak dapat menghapus Tahun Ajaran yang sedang aktif beroperasi.');
        }

        $ta->delete();

        return redirect()->back()->with('success', 'Data Tahun Ajaran berhasil dihapus.');
    }
}
