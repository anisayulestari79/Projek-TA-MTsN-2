<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipLaporan;
use Illuminate\Support\Facades\Auth;

class KamadController extends Controller
{
    public function laporanMasuk(Request $request)
    {
        // Mengambil data user yang sedang login (Kamad)
        $user = Auth::user();

        // Mengambil semua daftar laporan, diurutkan dari yang terbaru, 
        // beserta data relasi pengirimnya.
        $listLaporan = ArsipLaporan::with('pengirim')->latest()->get();

        // Mengirim data ke file view blade
        return view('kamad.kamad-laporan', compact('user', 'listLaporan'));
    }
}
