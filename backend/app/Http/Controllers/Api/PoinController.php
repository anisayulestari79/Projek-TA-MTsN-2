<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\RiwayatPoin;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PoinController extends Controller
{
    // 1. Tampilkan Halaman Utama Poin (Blade)
    public function index(Request $request)
    {
        $user = Session::get('user');
        $dataPelanggaran = Pelanggaran::all();

        return view('admin.admin-poin', compact('user', 'dataPelanggaran'));
    }

    // 2. Pencarian Siswa (AJAX JSON)
    public function searchSiswa(Request $request)
    {
        $search = $request->query('q');
        if (!$search) return response()->json([]);

        $siswa = Siswa::where('nama', 'LIKE', "%{$search}%")
            ->orWhere('nisn', 'LIKE', "%{$search}%")
            ->select('nisn', 'nama', 'kelas', 'poin')
            ->take(5)
            ->get();

        return response()->json($siswa);
    }

    // 3. Ambil Data Riwayat (AJAX JSON)
    public function getRiwayat()
    {
        $data = RiwayatPoin::orderBy('waktu', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // 4. Simpan Poin Baru ke Database (AJAX JSON)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nisn' => 'required|string|exists:siswa,nisn',
            'jumlah' => 'required|integer',
            'ket' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal, pastikan data siswa benar.',
                'errors' => $validator->errors()
            ], 422);
        }

        return DB::transaction(function () use ($request) {
            $siswa = Siswa::where('nisn', $request->nisn)->lockForUpdate()->first();

            // Tambahkan poin ke profil siswa
            $siswa->poin = max(0, $siswa->poin + $request->jumlah);
            $siswa->save();

            // Catat log pelanggarannya ke database riwayat_poin
            $riwayat = RiwayatPoin::create([
                'nisn' => $siswa->nisn,
                'nama' => $siswa->nama,
                'kelas' => $siswa->kelas,
                'jenis' => $request->jumlah > 0 ? 'Tambah' : 'Kurang',
                'jumlah' => abs($request->jumlah),
                'ket' => $request->ket,
                'waktu' => now(), // Menyimpan waktu otomatis dari server
            ]);

            $message = $siswa->poin >= 100
                ? "Poin siswa {$siswa->nama} mencapai {$siswa->poin}! Rekomendasi: DO."
                : "Poin pelanggaran berhasil disimpan. Total Poin: {$siswa->poin}.";

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => ['siswa' => $siswa, 'riwayat' => $riwayat]
            ]);
        });
    }

    // 5. Hapus 1 Item Riwayat (AJAX JSON)
    public function deleteRiwayat($id)
    {
        $riwayat = RiwayatPoin::find($id);
        if (!$riwayat) {
            return response()->json(['success' => false, 'message' => 'Riwayat tidak ditemukan'], 404);
        }

        return DB::transaction(function () use ($riwayat) {
            $siswa = Siswa::where('nisn', $riwayat->nisn)->lockForUpdate()->first();

            // Kembalikan poin siswa ke asal
            if ($siswa) {
                $siswa->poin = ($riwayat->jenis === 'Tambah')
                    ? max(0, $siswa->poin - $riwayat->jumlah)
                    : $siswa->poin + $riwayat->jumlah;
                $siswa->save();
            }

            $riwayat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat dihapus dan poin siswa dikembalikan.'
            ]);
        });
    }

    // 6. Hapus Seluruh Riwayat (AJAX JSON)
    public function deleteAllRiwayat()
    {
        RiwayatPoin::truncate();
        return response()->json(['success' => true, 'message' => 'Semua riwayat berhasil dihapus.']);
    }

    // 7. Ambil Riwayat Spesifik Berdasarkan NISN (Untuk Modal/View Detail di Dashboard)
    public function getRiwayatApi($nisn)
    {
        $riwayat = RiwayatPoin::where('nisn', $nisn)
            ->orderBy('waktu', 'desc')
            ->get();

        return response()->json($riwayat);
    }
}
