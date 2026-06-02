<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data user yang sedang login untuk Header
        $user = Session::get('user');

        // Load siswa beserta jumlah riwayat poin-nya
        $query = Siswa::withCount('riwayatPoin');

        // Filter by tingkat
        if ($request->has('tingkat') && $request->tingkat != '') {
            $query->where('kelas', 'like', $request->tingkat . '%');
        }

        // Filter by kelas (A, B, C, dll)
        if ($request->has('kelas') && $request->kelas != '') {
            $query->where('kelas', 'like', '%' . $request->kelas);
        }

        // Search by nama atau NISN
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }

        // Gunakan paginate() agar tabelnya rapi di web
        $siswa = $query->orderBy('nama')->paginate(10)->withQueryString();

        // ========================================================
        // TAMBAHAN: Generate Daftar Kelas Otomatis (VII.A s/d IX.K)
        // ========================================================
        $tingkatList = ['VII', 'VIII', 'IX'];
        $abjadList = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];
        $daftarKelas = [];

        foreach ($tingkatList as $tingkat) {
            foreach ($abjadList as $abjad) {
                $daftarKelas[] = $tingkat . '.' . $abjad;
            }
        }

        // Jika dipanggil lewat API, kembalikan JSON
        if ($request->wantsJson() || $request->is('api/*')) {
            // Untuk API, jika ingin mengembalikan semua data tanpa pagination bisa disesuaikan
            return response()->json([
                'success' => true,
                'data' => $siswa
            ]);
        }

        // Jika dipanggil lewat Browser Web, kembalikan tampilan Blade beserta daftarKelas
        return view('admin.admin-datasiswa', [
            'dataSiswa' => $siswa,
            'user' => $user,
            'daftarKelas' => $daftarKelas // <-- Variabel ini dikirim ke tampilan
        ]);
    }

    public function show($nisn)
    {
        // Sertakan jumlah riwayat poin untuk detail siswa
        $siswa = Siswa::withCount('riwayatPoin')
            ->where('nisn', $nisn)
            ->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $siswa
        ]);
    }

    public function store(Request $request)
    {
        // PERBAIKAN VALIDASI: Memaksa NISN harus angka (numeric) dan pas 10 digit (digits:10)
        $validator = Validator::make($request->all(), [
            'nisn' => 'required|numeric|digits:10|unique:siswa,nisn', // Atau unique:siswas,nisn (sesuaikan nama tabel di DB)
            'nama' => 'required|string',
            'jk' => 'nullable|in:Laki-laki,Perempuan',
            'kelas' => 'required|string',
            'kontak_ortu' => 'nullable|string',
            'photo' => 'nullable|string',
        ], [
            // Custom Error Messages agar tampilannya ramah pengguna
            'nisn.required' => 'NISN wajib diisi!',
            'nisn.numeric'  => 'NISN hanya boleh berisi angka!',
            'nisn.digits'   => 'NISN harus berjumlah persis 10 angka!',
            'nisn.unique'   => 'NISN ini sudah terdaftar di dalam sistem!'
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal menambah data! Periksa kembali isian Anda.');
        }

        $siswa = Siswa::create([
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'jk' => $request->jk,
            'kelas' => $request->kelas,
            'kontak_ortu' => $request->kontak_ortu,
            'poin' => 0,
            'photo' => $request->photo,
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan',
                'data' => $siswa
            ], 201);
        }

        return redirect()->back()->with('success', 'Data Siswa berhasil ditambahkan!');
    }

    public function update(Request $request, $nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->first();

        if (!$siswa) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan'
                ], 404);
            }
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'jk' => 'nullable|in:Laki-laki,Perempuan',
            'kelas' => 'required|string',
            'kontak_ortu' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal mengupdate data! Periksa isian Anda.');
        }

        $siswa->update([
            'nama' => $request->nama,
            'jk' => $request->jk ?? $siswa->jk,
            'kelas' => $request->kelas,
            'kontak_ortu' => $request->kontak_ortu,
            'photo' => $request->photo ?? $siswa->photo,
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil diupdate',
                'data' => $siswa
            ]);
        }

        return redirect()->back()->with('success', 'Data Siswa berhasil diperbarui!');
    }

    public function destroy(Request $request, $nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->first();

        if (!$siswa) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan'
                ], 404);
            }
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $siswa->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil dihapus'
            ]);
        }

        return redirect()->back()->with('success', 'Data Siswa berhasil dihapus!');
    }

    // ... function importExcel biarkan seperti sedia kala ...
}
