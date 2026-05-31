<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session; // <-- Tambahan library Session
use PhpOffice\PhpSpreadsheet\IOFactory;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data user yang sedang login untuk header
        $user = Session::get('user');

        // 2. Query dasar (urutkan berdasarkan nama)
        $query = Guru::orderBy('nama');

        // 3. Logika untuk fitur pencarian NIP atau Nama
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama', 'LIKE', "%{$search}%")
                ->orWhere('nip', 'LIKE', "%{$search}%");
        }

        // Eksekusi query dengan pagination (menampilkan 10 data per halaman)
        $guru = $query->paginate(10)->withQueryString();

        // 4. Jika diakses melalui API (Postman/Mobile), kembalikan JSON
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $guru
            ]);
        }

        // 5. Jika diakses lewat Browser Web, kembalikan tampilan Blade HTML
        return view('admin.admin-dataguru', [
            'dataGuru' => $guru,
            'user' => $user
        ]);
    }

    public function show($id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan'
            ], 404);
        }

        // Ambil data user terkait jika ada
        $loginId = $guru->nip;
        $user = User::where('nip', $loginId)->where('role', 'guru')->first();

        $guruData = $guru->toArray();
        if ($user) {
            $guruData['user_account'] = [
                'email' => $user->email,
                'username' => $user->username,
                'phone' => $user->phone,
                'photo' => $user->photo,
            ];
        } else {
            $guruData['user_account'] = null;
        }

        return response()->json([
            'success' => true,
            'data' => $guruData
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'nullable|string',
            'nama' => 'required|string',
            'jk' => 'nullable|in:Laki-laki,Perempuan',
            'pendidikan' => 'nullable|string',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'password' => 'nullable|string',
            'wali_kelas' => 'nullable|string',
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

        // Normalisasi NIP: anggap kosong atau "-" sebagai null
        $normalizedNip = $request->nip;
        if ($normalizedNip === '-' || $normalizedNip === '') {
            $normalizedNip = null;
        }

        // Check if wali kelas already exists
        if ($request->wali_kelas) {
            $existingWali = Guru::where('wali_kelas', $request->wali_kelas)->first();

            if ($existingWali) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => "Kelas {$request->wali_kelas} sudah memiliki wali kelas"
                    ], 422);
                }
                return redirect()->back()->withInput()->with('error', "Kelas {$request->wali_kelas} sudah memiliki wali kelas.");
            }
        }

        // Tentukan ID login (pakai NIP)
        $loginId = $normalizedNip;

        // Check if login ID already exists in users table
        if ($loginId) {
            $existingUser = User::where('nip', $loginId)->first();
            if ($existingUser) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'NIP sudah digunakan oleh user lain'
                    ], 422);
                }
                return redirect()->back()->withInput()->with('error', 'NIP tersebut sudah digunakan oleh user lain.');
            }
        }

        $password = $request->password ?: 'mtsn02';

        // Create guru in guru table
        $guru = Guru::create([
            'nip' => $normalizedNip,
            'nama' => $request->nama,
            'jk' => $request->jk,
            'pendidikan' => $request->pendidikan,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'password' => $password,
            'wali_kelas' => $request->wali_kelas,
        ]);

        // Create user in users table for login
        if ($loginId) {
            // Generate email from login ID if not provided
            $email = $request->email ?? ($loginId . '@mtsn2bjm.sch.id');

            // Check if email already exists
            $existingEmail = User::where('email', $email)->first();
            if ($existingEmail) {
                // If email exists, use NIP + timestamp
                $email = $loginId . '_' . time() . '@mtsn2bjm.sch.id';
            }

            User::create([
                'name' => $request->nama,
                'email' => $email,
                'nip' => $loginId,
                'role' => 'guru',
                'gender' => $request->jk,
                'password' => Hash::make($password),
            ]);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil ditambahkan',
                'data' => $guru
            ], 201);
        }

        return redirect()->back()->with('success', 'Data Guru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru tidak ditemukan'
                ], 404);
            }
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nip' => 'nullable|string',
            'nama' => 'required|string',
            'jk' => 'nullable|in:Laki-laki,Perempuan',
            'pendidikan' => 'nullable|string',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'password' => 'nullable|string',
            'wali_kelas' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal mengupdate data! Periksa kembali isian Anda.');
        }

        // Normalisasi NIP baru: anggap kosong atau "-" sebagai null
        $normalizedNip = $request->nip;
        if ($normalizedNip === '-' || $normalizedNip === '') {
            $normalizedNip = null;
        }

        // Check if wali kelas already exists
        if ($request->wali_kelas && $request->wali_kelas !== $guru->wali_kelas) {
            $existingWali = Guru::where('wali_kelas', $request->wali_kelas)
                ->where('id', '!=', $id)
                ->first();

            if ($existingWali) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => "Kelas {$request->wali_kelas} sudah memiliki wali kelas"
                    ], 422);
                }
                return redirect()->back()->withInput()->with('error', "Kelas {$request->wali_kelas} sudah memiliki wali kelas.");
            }
        }

        // Hitung login ID lama & baru (pakai NIP)
        $oldLoginId = ($guru->nip && $guru->nip !== '-') ? $guru->nip : null;
        $newLoginId = ($normalizedNip && $normalizedNip !== '-') ? $normalizedNip : null;

        // Check if login ID baru sudah dipakai user lain (jika berubah)
        if ($newLoginId && $newLoginId !== $oldLoginId) {
            $existingUser = User::where('nip', $newLoginId)->first();
            if ($existingUser) {
                if ($request->wantsJson() || $request->is('api/*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'NIP sudah digunakan oleh user lain'
                    ], 422);
                }
                return redirect()->back()->withInput()->with('error', 'NIP tersebut sudah digunakan oleh user lain.');
            }
        }

        $updateData = [
            'nip' => $normalizedNip, // Pastikan NIP bisa diubah, termasuk menjadi null
            'nama' => $request->nama,
            'jk' => $request->jk,
            'pendidikan' => $request->pendidikan,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'wali_kelas' => $request->wali_kelas,
        ];

        if ($request->password) {
            $updateData['password'] = $request->password;
        }

        $guru->update($updateData);
        $guru->refresh(); // Refresh to get updated NIP
        // Hitung ulang login ID baru setelah update (bisa saja berubah)
        $newLoginId = ($guru->nip && $guru->nip !== '-') ? $guru->nip : null;

        // Sinkronisasi user di tabel users berdasarkan login ID
        if ($newLoginId) {
            if ($oldLoginId && $oldLoginId !== $newLoginId) {
                // Login ID berubah: update user lama jika ada
                $user = User::where('nip', $oldLoginId)->first();
                if ($user) {
                    $userUpdateData = [
                        'name' => $request->nama,
                        'gender' => $request->jk,
                        'nip' => $newLoginId,
                    ];
                    if ($request->password) {
                        $userUpdateData['password'] = Hash::make($request->password);
                    }
                    $user->update($userUpdateData);
                } else {
                    // Tidak ada user lama, buat baru
                    $email = $request->email ?? ($newLoginId . '@mtsn2bjm.sch.id');
                    $existingEmail = User::where('email', $email)->first();
                    if ($existingEmail) {
                        $email = $newLoginId . '_' . time() . '@mtsn2bjm.sch.id';
                    }

                    User::create([
                        'name' => $request->nama,
                        'email' => $email,
                        'nip' => $newLoginId,
                        'role' => 'guru',
                        'gender' => $request->jk,
                        'password' => Hash::make($request->password ?: $guru->password),
                    ]);
                }
            } else {
                // Login ID tidak berubah: update atau buat user baru
                $user = User::where('nip', $newLoginId)->first();
                if ($user) {
                    $userUpdateData = [
                        'name' => $request->nama,
                        'gender' => $request->jk,
                    ];
                    if ($request->password) {
                        $userUpdateData['password'] = Hash::make($request->password);
                    }
                    $user->update($userUpdateData);
                } else {
                    $email = $request->email ?? ($newLoginId . '@mtsn2bjm.sch.id');
                    $existingEmail = User::where('email', $email)->first();
                    if ($existingEmail) {
                        $email = $newLoginId . '_' . time() . '@mtsn2bjm.sch.id';
                    }

                    User::create([
                        'name' => $request->nama,
                        'email' => $email,
                        'nip' => $newLoginId,
                        'role' => 'guru',
                        'gender' => $request->jk,
                        'password' => Hash::make($request->password ?: $guru->password),
                    ]);
                }
            }
        } else {
            // Tidak ada login ID baru, hapus user berdasarkan login ID lama jika ada
            if ($oldLoginId) {
                $user = User::where('nip', $oldLoginId)->first();
                if ($user) {
                    $user->delete();
                }
            }
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil diupdate',
                'data' => $guru
            ]);
        }

        return redirect()->back()->with('success', 'Data Guru berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        $guru = Guru::find($id);

        if (!$guru) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru tidak ditemukan'
                ], 404);
            }
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // Delete user from users table if exists
        if ($guru->nip) {
            $user = User::where('nip', $guru->nip)->first();
            if ($user) {
                $user->delete();
            }
        }

        $guru->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil dihapus'
            ]);
        }

        return redirect()->back()->with('success', 'Data Guru berhasil dihapus!');
    }

    public function importExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row (row 1)
            $header = array_shift($rows);

            // Expected columns: NIP, Nama, JK, Pendidikan, Tempat Lahir, Tanggal Lahir, Password, Wali Kelas
            $imported = 0;
            $skipped = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 karena header di row 1 dan array 0-indexed

                // Skip empty rows
                if (empty($row[0]) && empty($row[1])) {
                    continue;
                }

                $nip = trim($row[0] ?? '');
                $nama = trim($row[1] ?? '');
                $jk = trim($row[2] ?? '');
                $pendidikan = trim($row[3] ?? '');
                $tempatLahir = trim($row[4] ?? '');
                $tanggalLahir = trim($row[5] ?? '');
                $password = trim($row[6] ?? 'mtsn02');
                $waliKelas = trim($row[7] ?? '');

                // Validate required fields
                if (empty($nama)) {
                    $skipped++;
                    $errors[] = "Baris {$rowNumber}: Nama wajib diisi";
                    continue;
                }

                // Check if wali kelas already exists
                if ($waliKelas) {
                    $existingWali = Guru::where('wali_kelas', $waliKelas)->first();
                    if ($existingWali) {
                        $skipped++;
                        $errors[] = "Baris {$rowNumber}: Kelas {$waliKelas} sudah memiliki wali kelas";
                        continue;
                    }
                }

                // Normalisasi NIP dari file: kosong atau "-" dianggap null
                $normalizedNip = $nip === '-' || $nip === '' ? null : $nip;

                // Tentukan login ID (NIP)
                $loginId = $normalizedNip;

                // Check if login ID already exists in users table
                if ($loginId) {
                    $existingUser = User::where('nip', $loginId)->first();
                    if ($existingUser) {
                        $skipped++;
                        $errors[] = "Baris {$rowNumber}: NIP {$loginId} sudah digunakan";
                        continue;
                    }
                }

                // Validate JK
                if ($jk && !in_array($jk, ['Laki-laki', 'Perempuan'])) {
                    $jk = null;
                }

                // Create guru
                $guru = Guru::create([
                    'nip' => $normalizedNip,
                    'nama' => $nama,
                    'jk' => $jk ?: null,
                    'pendidikan' => $pendidikan ?: null,
                    'tempat_lahir' => $tempatLahir ?: null,
                    'tanggal_lahir' => $tanggalLahir ?: null,
                    'password' => $password,
                    'wali_kelas' => $waliKelas ?: null,
                ]);

                // Create user in users table for login
                if ($loginId) {
                    $email = $loginId . '@mtsn2bjm.sch.id';

                    // Check if email already exists
                    $existingEmail = User::where('email', $email)->first();
                    if ($existingEmail) {
                        $email = $nip . '_' . time() . '@mtsn2bjm.sch.id';
                    }

                    User::create([
                        'name' => $nama,
                        'email' => $email,
                        'nip' => $loginId,
                        'role' => 'guru',
                        'gender' => $jk,
                        'password' => Hash::make($password),
                    ]);
                }

                $imported++;
            }

            return response()->json([
                'success' => true,
                'message' => "Import selesai. {$imported} data berhasil diimport, {$skipped} data dilewati",
                'data' => [
                    'imported' => $imported,
                    'skipped' => $skipped,
                    'errors' => $errors
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saat membaca file Excel: ' . $e->getMessage()
            ], 500);
        }
    }
}
