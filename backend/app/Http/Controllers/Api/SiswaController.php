<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data user yang sedang login untuk Header
        $user = Session::get('user');

        // Load siswa beserta jumlah riwayat poin-nya
        $query = Siswa::withCount('riwayatPoin');

        // Filter by tingkat (Mungkin string ini perlu disesuaikan jika inputnya "VII" bukan "7")
        if ($request->has('tingkat') && $request->tingkat != '') {
            $query->where('kelas', 'like', $request->tingkat . '%');
        }

        // Filter by kelas (Misal: A, B, C)
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
        // PERBAIKAN: Mengambil Daftar Kelas langsung dari Database Master
        // ========================================================
        // Menggunakan pluck() agar hasilnya langsung berupa daftar teks (array of strings)
        // Contoh output: ['VII A', 'VII B', 'VIII A', ...]
        $daftarKelas = Kelas::orderBy('tingkat', 'asc')
            ->orderBy('nama_kelas', 'asc')
            ->pluck('nama_kelas')
            ->toArray();

        // ========================================================
        // TAMBAHAN: Mengambil Daftar Orang Tua (role ortu) dari tabel users
        // ========================================================
        $daftarOrtu = User::where('role', 'ortu')->orderBy('name', 'asc')->get();

        // Jika dipanggil lewat API, kembalikan JSON
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $siswa
            ]);
        }

        // Jika dipanggil lewat Browser Web, kembalikan tampilan Blade beserta daftarKelas dan daftarOrtu
        return view('admin.admin-datasiswa', [
            'dataSiswa' => $siswa,
            'user' => $user,
            'daftarKelas' => $daftarKelas, // <-- Variabel ini dikirim ke tampilan
            'daftarOrtu' => $daftarOrtu    // <-- Variabel ini dikirim ke tampilan (BARU)
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
        // PERBAIKAN VALIDASI: Memaksa NISN harus angka (numeric) TAPI BEBAS JUMLAH DIGIT
        // DITAMBAHKAN: Validasi alamat, ortu_id, dan file foto
        $validator = Validator::make($request->all(), [
            'nisn' => 'required|numeric|unique:siswa,nisn', // <-- PERBAIKAN: Hapus batas 10 digit (digits:10)
            'nama' => 'required|string',
            'jk' => 'nullable|in:Laki-laki,Perempuan',
            'kelas' => 'required|string',
            'alamat' => 'nullable|string', // <-- DITAMBAHKAN
            'kontak_ortu' => 'nullable|string',
            'ortu_id' => 'nullable|exists:users,id', // <-- DITAMBAHKAN
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // <-- DITAMBAHKAN (Validasi foto maks 2MB)
        ], [
            // Custom Error Messages agar tampilannya ramah pengguna
            'nisn.required' => 'NISN wajib diisi!',
            'nisn.numeric'  => 'NISN hanya boleh berisi angka!',
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

        // TAMBAHAN: Proses Upload Foto (Jika ada file yang diunggah)
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('siswa_photos', 'public');
        }

        $siswa = Siswa::create([
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'jk' => $request->jk,
            'kelas' => $request->kelas,
            'alamat' => $request->alamat, // <-- DITAMBAHKAN
            'kontak_ortu' => $request->kontak_ortu,
            'ortu_id' => $request->ortu_id, // <-- DITAMBAHKAN
            'poin' => 0,
            'photo' => $photoPath, // <-- DITAMBAHKAN
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

        // DITAMBAHKAN: Validasi alamat, ortu_id, dan file foto
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'jk' => 'nullable|in:Laki-laki,Perempuan',
            'kelas' => 'required|string',
            'alamat' => 'nullable|string', // <-- DITAMBAHKAN
            'kontak_ortu' => 'nullable|string',
            'ortu_id' => 'nullable|exists:users,id', // <-- DITAMBAHKAN
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // <-- DITAMBAHKAN
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

        // Menyiapkan data update utama
        $updateData = [
            'nama' => $request->nama,
            'jk' => $request->jk ?? $siswa->jk,
            'kelas' => $request->kelas,
            'alamat' => $request->alamat ?? $siswa->alamat, // <-- DITAMBAHKAN
            'kontak_ortu' => $request->kontak_ortu,
            'ortu_id' => $request->ortu_id, // <-- DITAMBAHKAN
        ];

        // TAMBAHAN: Proses Update Foto
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($siswa->photo && Storage::disk('public')->exists($siswa->photo)) {
                Storage::disk('public')->delete($siswa->photo);
            }
            // Simpan foto baru dan tambahkan ke array $updateData
            $updateData['photo'] = $request->file('photo')->store('siswa_photos', 'public');
        }

        $siswa->update($updateData);

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

        // TAMBAHAN: Hapus file foto dari server jika siswa dihapus agar penyimpanan tidak penuh
        if ($siswa->photo && Storage::disk('public')->exists($siswa->photo)) {
            Storage::disk('public')->delete($siswa->photo);
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

    // ========================================================
    // FUNGSI IMPORT EXCEL
    // ========================================================
    public function importExcel(Request $request)
    {
        // 1. Validasi File Excel
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls|max:5120'
        ], [
            'file_excel.mimes' => 'File harus berformat Excel (.xlsx atau .xls)',
            'file_excel.max' => 'Ukuran file maksimal 5MB'
        ]);

        try {
            // 2. Baca isi file Excel menggunakan PhpSpreadsheet
            $file = $request->file('file_excel');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Hapus baris pertama karena itu adalah baris Judul Kolom (Header)
            unset($rows[0]);

            // 3. Ambil ID Tahun Ajaran yang sedang Aktif saat ini sebagai Default (Jaga-jaga jika di Excel kosong)
            $activeYearId = \App\Models\TahunAjaran::where('is_active', true)->value('id');

            $berhasil = 0;

            // 4. Looping setiap baris di Excel
            foreach ($rows as $row) {
                // Mapping index kolom Excel berdasarkan format:
                // 0:NISN | 1:Nama | 2:JK | 3:Kelas | 4:Kontak | 5:Alamat | 6:Tahun Masuk
                $nisn   = isset($row[0]) ? trim($row[0]) : null;
                $nama   = isset($row[1]) ? trim($row[1]) : null;
                $jk     = isset($row[2]) ? trim($row[2]) : null;
                $kelas  = isset($row[3]) ? trim($row[3]) : null;
                $kontak = isset($row[4]) ? trim($row[4]) : null;
                $alamat = isset($row[5]) ? trim($row[5]) : null;
                $thn_excel = isset($row[6]) ? trim($row[6]) : null;

                // Wajib ada NISN dan Nama, jika kosong lewati baris ini
                if (empty($nisn) || empty($nama)) {
                    continue;
                }

                // ==========================================
                // LOGIKA CERDAS PENCARIAN TAHUN MASUK
                // ==========================================
                $tahunMasukId = $activeYearId; // Default

                if (!empty($thn_excel)) {
                    // Cari di database tabel tahun_ajarans yang namanya mirip dengan ketikan di Excel
                    // Contoh: Excel ngetik "2024/2025", maka cari yg namanya mengandung teks itu
                    $cariTahun = \App\Models\TahunAjaran::where('nama', 'LIKE', "%{$thn_excel}%")->first();

                    if ($cariTahun) {
                        $tahunMasukId = $cariTahun->id;
                    }
                }

                // ==========================================
                // SIMPAN KE DATABASE
                // ==========================================
                // updateOrCreate akan mencari NISN. 
                // Jika sudah ada, datanya diupdate. Jika belum ada, dibuat siswa baru.
                \App\Models\Siswa::updateOrCreate(
                    ['nisn' => $nisn],
                    [
                        'nama'           => $nama,
                        'jk'             => $jk,
                        'kelas'          => $kelas,
                        'kontak_ortu'    => $kontak,
                        'alamat'         => $alamat,
                        'tahun_masuk_id' => $tahunMasukId,
                        'status'         => 'Aktif' // Anak yang baru diimport pasti berstatus Aktif
                    ]
                );

                $berhasil++;
            }

            return redirect()->back()->with('success', "Sukses! $berhasil data siswa berhasil ditambahkan/diperbarui dari Excel.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses file Excel: Pastikan format kolom sesuai panduan. Detail error: ' . $e->getMessage());
        }
    }

    // ========================================================
    // FITUR TUTUP TAHUN (KENAIKAN KELAS & KELULUSAN)
    // ========================================================
    public function prosesKenaikanKelas(Request $request)
    {
        // Keamanan: Pastikan hanya admin yang bisa menjalankan ini
        $user = Session::get('user');
        if (!$user || $user['role'] !== 'admin') {
            return redirect()->back()->with('error', 'Hanya Administrator yang memiliki akses untuk proses ini.');
        }

        try {
            // Ambil semua siswa yang masih berstatus "Aktif"
            // Asumsi: di database Anda memiliki kolom 'status' (Aktif, Lulus, Dikeluarkan, dll)
            // Jika tidak ada kolom status, hapus ->where('status', 'Aktif') atau ganti sesuai kolom Anda.
            $semuaSiswaAktif = Siswa::where(function ($query) {
                $query->where('status', 'Aktif')
                    ->orWhereNull('status'); // Jaga-jaga jika kolom status ada yang NULL
            })->get();

            $jumlahLulus = 0;
            $jumlahNaik = 0;

            // Gunakan Transaction agar jika di tengah jalan ada error, database di-rollback (dibatalkan)
            \Illuminate\Support\Facades\DB::transaction(function () use ($semuaSiswaAktif, &$jumlahLulus, &$jumlahNaik) {
                foreach ($semuaSiswaAktif as $siswa) {
                    // Ekstrak Kelas (Contoh: dari "VII A" menjadi "VII" dan "A")
                    $parts = explode(' ', str_replace('-', ' ', $siswa->kelas));
                    $tingkat = $parts[0] ?? '';
                    $abjad = isset($parts[1]) ? $parts[1] : '';

                    $kelasBaru = $siswa->kelas;
                    $statusBaru = $siswa->status ?? 'Aktif';

                    // Logika Kenaikan & Kelulusan
                    if ($tingkat === 'IX') {
                        // KELAS 9 -> LULUS
                        $statusBaru = 'Lulus';
                        $jumlahLulus++;
                    } elseif ($tingkat === 'VIII') {
                        // KELAS 8 -> KELAS 9
                        $kelasBaru = 'IX ' . $abjad;
                        $jumlahNaik++;
                    } elseif ($tingkat === 'VII') {
                        // KELAS 7 -> KELAS 8
                        $kelasBaru = 'VIII ' . $abjad;
                        $jumlahNaik++;
                    }

                    // Update data ke database
                    // PERHATIAN: Baris "'poin' => 0" TELAH DIHAPUS. Poin akan tetap seperti semula.
                    $siswa->update([
                        'kelas'  => trim($kelasBaru),
                        'status' => $statusBaru
                    ]);
                }
            });

            return redirect()->back()->with('success', "Proses Tutup Tahun Ajaran berhasil! Sebanyak $jumlahNaik siswa naik kelas dan $jumlahLulus siswa dinyatakan Lulus. Akumulasi poin siswa tidak dirubah.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses kenaikan kelas: ' . $e->getMessage());
        }
    }
}
