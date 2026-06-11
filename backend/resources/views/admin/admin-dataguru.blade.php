<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Guru - Sistem Pelanggaran Poin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar-item.active {
            background-color: white;
            color: #10b981;
            border-radius: 10px 0 0 10px;
            font-weight: 800;
        }

        /* Tambahan untuk sistem View / Tab */
        .view-section {
            display: none;
        }

        .view-section.active {
            display: block;
        }

        /* Custom Scrollbar untuk kotak checkbox kelas */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-[#f4f7f6] font-sans flex text-gray-800">
    <aside class="w-72 min-h-screen bg-[#10b981] text-white flex flex-col fixed shadow-xl z-50">
        <div class="p-8">
            <div class="flex items-center gap-3 mb-2">
                <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                    class="w-10 drop-shadow-md" alt="Logo Kemenag">
                <h1 class="font-bold text-2xl leading-tight tracking-tight uppercase">Sistem <br> Pelanggaran <br> Poin
                    Siswa</h1>
            </div>
            <p class="text-[10px] opacity-80 font-medium tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
        </div>

        <nav class="mt-4 flex-grow pl-6">
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-th-large mr-4 text-sm"></i> <span class="font-medium">Dashboard</span>
            </a>

            <a href="#" onclick="showView('data-guru')" id="nav-data-guru"
                class="sidebar-item active flex items-center px-6 py-4 transition">
                <i class="fas fa-chalkboard-teacher mr-4 text-sm"></i> <span>Data Guru</span>
            </a>

            <a href="{{ route('admin.siswa.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-users mr-4 text-sm"></i> <span class="font-medium">Data Siswa</span>
            </a>

            <a href="{{ route('admin.konsultasi.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-comments mr-4 text-sm"></i> <span class="font-medium">Konsultasi BK</span>
            </a>

            <a href="{{ route('admin.poin.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-star mr-4 text-sm"></i> <span class="font-medium">Poin Siswa</span>
            </a>

            <a href="{{ route('admin.audit.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-history mr-4 text-sm"></i> <span class="font-medium">Audit Log</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-72 p-10 relative">
        <header class="flex justify-between items-center mb-10">
            <div>
                <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                    Home / <span id="breadcrumb-active">Data Guru</span>
                </nav>
                <h2 id="view-title" class="text-2xl font-black text-gray-700 uppercase tracking-tighter italic">Data
                    Master Guru</h2>
            </div>

            <div class="relative">
                <button id="profileDropdownBtn"
                    class="flex items-center gap-4 bg-white px-6 py-2 rounded-full shadow-sm border border-gray-100 hover:bg-gray-50 transition focus:outline-none">
                    <div class="text-right">
                        <p class="text-xs font-black text-[#10b981] uppercase leading-none">
                            {{ $user['name'] ?? 'Admin User' }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Status: Administrator</p>
                    </div>
                    <img src="{{ $user['photo'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user['name'] ?? 'Admin') . '&background=10b981&color=fff' }}"
                        class="w-10 h-10 rounded-full border-2 border-green-50 shadow-sm" alt="Profile">
                    <i class="fas fa-chevron-down text-gray-400 text-xs ml-1"></i>
                </button>

                <div id="profileDropdownMenu"
                    class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden z-50 transform origin-top-right transition-all duration-200 opacity-0 scale-95">
                    <div class="py-2">
                        <button type="button"
                            onclick="showView('profile'); document.getElementById('profileDropdownMenu').classList.add('hidden');"
                            class="w-full text-left px-6 py-3 text-xs font-bold text-gray-700 hover:bg-green-50 hover:text-[#10b981] transition flex items-center gap-3">
                            <i class="fas fa-user-edit"></i> Edit Profil
                        </button>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-6 py-3 text-xs font-bold text-red-600 hover:bg-red-50 transition flex items-center gap-3">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline font-bold">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline font-bold">{{ session('error') }}</span>
            </div>
        @endif

        <div id="view-data-guru" class="view-section active">
            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50">
                <div class="flex justify-between items-center mb-8 border-b pb-6">
                    <div>
                        <h3 class="font-black text-gray-700 text-lg uppercase tracking-widest">Daftar Guru</h3>
                        <p class="text-xs text-gray-400 mt-1">Manajemen data master guru dan wali kelas</p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="openAddModal()"
                            class="bg-[#10b981] text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase hover:bg-green-600 transition flex items-center gap-2">
                            <i class="fas fa-plus"></i> Tambah Guru
                        </button>
                    </div>
                </div>

                <form action="{{ route('admin.guru.index') }}" method="GET" class="mb-6 flex gap-4">
                    <div class="relative flex-1 max-w-md">
                        <i class="fas fa-search absolute left-4 top-3.5 text-gray-300 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau NIP..."
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-sm outline-none focus:ring-2 focus:ring-green-100 transition">
                    </div>
                    <button type="submit"
                        class="bg-blue-50 text-blue-600 px-6 py-3 rounded-2xl text-xs font-bold uppercase hover:bg-blue-100 transition">
                        Cari Data
                    </button>
                </form>

                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#005c4b] text-white text-[10px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="py-4 pl-6 rounded-tl-xl">NIP</th>
                            <th class="py-4">Nama</th>
                            <th class="py-4">Peran</th>
                            <th class="py-4 text-center">Tugas Tambahan</th>
                            <th class="py-4 pr-6 rounded-tr-xl text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100">
                        @forelse($dataGuru ?? [] as $guru)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-5 pl-6 text-gray-500 font-medium">{{ $guru->nip ?? '-' }}</td>
                                <td class="py-5 font-bold text-gray-700">{{ $guru->nama }}</td>
                                <td class="py-5">
                                    @if ($guru->role == 'bk')
                                        <span
                                            class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-[10px] font-bold">Guru
                                            BK</span>
                                    @else
                                        <span
                                            class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-[10px] font-bold">Guru
                                            Umum</span>
                                    @endif
                                </td>
                                <td class="py-5 text-center font-bold text-[#10b981]">
                                    @if ($guru->role == 'bk')
                                        <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">Kelas
                                            Binaan BK</span>
                                    @else
                                        {{ $guru->wali_kelas ?? 'Bukan Wali Kelas' }}
                                    @endif
                                </td>
                                <td class="py-5 pr-6 text-right">
                                    <button onclick='openEditModal(@json($guru))'
                                        class="text-blue-500 hover:text-blue-700 mx-1 transition p-2 bg-blue-50 rounded-lg"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button
                                        onclick="openDeleteModal({{ $guru->id }}, '{{ addslashes($guru->nama) }}')"
                                        class="text-red-500 hover:text-red-700 mx-1 transition p-2 bg-red-50 rounded-lg"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-gray-400 font-bold">
                                    Tidak ada data guru yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-6">
                    {{ $dataGuru->links() ?? '' }}
                </div>
            </div>
        </div>

        <div id="view-profile" class="view-section">
            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50 max-w-2xl mx-auto">
                <div id="profileView" class="flex flex-col items-center">
                    <div
                        class="w-32 h-32 rounded-full overflow-hidden mb-4 border-4 border-green-50 shadow-sm relative group">
                        @if (isset($user['photo']) && $user['photo'])
                            <img src="{{ $user['photo'] }}" class="w-full h-full object-cover" id="mainProfilePic"
                                alt="Profile Picture">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user['name'] ?? 'Admin') }}&background=10b981&color=fff&size=128"
                                class="w-full h-full object-cover" id="mainProfilePic" alt="Profile Picture">
                        @endif
                    </div>
                    <h3 class="text-2xl font-black text-gray-800 uppercase">{{ $user['name'] ?? 'Nama Pengguna' }}
                    </h3>
                    <p class="text-xs font-bold text-[#10b981] uppercase tracking-widest mb-8">
                        {{ ucfirst($user['role'] ?? 'Administrator') }}</p>

                    <div class="w-full space-y-4">
                        <div class="flex items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-envelope text-[#10b981] w-10 text-center text-xl"></i>
                            <div class="ml-4">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    {{ ($user['role'] ?? '') === 'admin' ? 'Username' : 'NIP' }}</p>
                                <p class="text-sm font-black text-gray-700">
                                    {{ $user['username'] ?? ($user['nip'] ?? '-') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-venus-mars text-[#10b981] w-10 text-center text-xl"></i>
                            <div class="ml-4">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Gender</p>
                                <p class="text-sm font-black text-gray-700">{{ $user['gender'] ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-phone-alt text-[#10b981] w-10 text-center text-xl"></i>
                            <div class="ml-4">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No. Telepon
                                </p>
                                <p class="text-sm font-black text-gray-700">{{ $user['phone'] ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="toggleEditProfile(true)"
                        class="mt-8 bg-[#10b981] text-white px-8 py-4 rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-105 transition w-full">
                        <i class="fas fa-user-edit mr-2"></i> Edit Profil
                    </button>
                </div>

                <form id="profileForm" class="hidden flex flex-col" action="{{ route('profile.update') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
                        <h3 class="text-lg font-black text-gray-700 uppercase tracking-widest">Edit Profil</h3>
                        <button type="button" onclick="toggleEditProfile(false)"
                            class="text-gray-400 hover:text-red-500 transition"><i
                                class="fas fa-times text-xl"></i></button>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Unggah
                                Foto Profil Baru</label>
                            <input type="file" name="photo" accept="image/*"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-50 file:text-[#10b981] hover:file:bg-green-100 transition border border-gray-100 rounded-xl bg-gray-50">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Nama
                                Lengkap</label>
                            <input type="text" name="name" value="{{ $user['name'] ?? '' }}" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">{{ ($user['role'] ?? '') === 'admin' ? 'Username' : 'NIP' }}</label>
                            <input type="text" value="{{ $user['username'] ?? ($user['nip'] ?? '') }}" disabled
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Gender</label>
                            <select name="gender"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                                <option value="">Pilih Gender</option>
                                <option value="Laki-laki"
                                    {{ ($user['gender'] ?? '') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan"
                                    {{ ($user['gender'] ?? '') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">No.
                                Telepon</label>
                            <input type="tel" name="phone" value="{{ $user['phone'] ?? '' }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>
                    </div>

                    <div class="flex gap-4 mt-8">
                        <button type="submit"
                            class="flex-1 bg-[#10b981] text-white px-6 py-4 rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:bg-green-600 transition">Simpan
                            Perubahan</button>
                        <button type="button" onclick="toggleEditProfile(false)"
                            class="flex-1 bg-gray-100 text-gray-600 px-6 py-4 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-200 transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <!-- ============================================== -->
    <!-- MODAL TAMBAH GURU -->
    <!-- ============================================== -->
    <div id="addModal" class="fixed inset-0 bg-black/50 hidden z-[60] flex items-center justify-center">
        <div class="bg-white rounded-3xl w-full max-w-3xl overflow-hidden shadow-2xl">
            <div class="px-6 py-4 bg-[#10b981] text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Tambah Data Guru</h3>
                <button onclick="closeModals()"
                    class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form action="{{ route('admin.guru.store') }}" method="POST" class="p-6 overflow-y-auto max-h-[80vh]">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">NIP</label>
                        <input type="text" name="nip"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama" required
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Jenis Kelamin</label>
                        <select name="jk"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981]">
                            <option value=""> Pilih </option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Pendidikan</label>
                        <select name="pendidikan"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981]">
                            <option value=""> Pilih Pendidikan </option>
                            <option value="Sarjana (S1)">Sarjana (S1)</option>
                            <option value="Magister (S2)">Magister (S2)</option>
                            <option value="Doktor (S3)">Doktor (S3)</option>
                        </select>
                    </div>

                    <!-- PERAN / TUGAS KHUSUS (Dipindah ke atas) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Peran / Tugas Khusus <span
                                class="text-red-500">*</span></label>
                        <select name="role" id="add_role" required onchange="toggleRoleOptions('add')"
                            class="w-full border border-green-200 bg-green-50 text-green-700 rounded-xl px-3 py-2 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-[#10b981]">
                            <option value="guru">Guru Mata Pelajaran / Wali Kelas</option>
                            <option value="bk">Guru Bimbingan Konseling (BK)</option>
                        </select>
                    </div>

                    <!-- Password (Dibuat di baris yang sama dengan Peran agar rapi) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Password Login</label>
                        <input type="text" name="password"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981]"
                            placeholder="Default: mtsn02">
                    </div>

                    <!-- WALI KELAS (Khusus Guru) -->
                    <div id="add_wali_kelas_container" class="col-span-2">
                        <label class="block text-xs font-bold text-gray-600 mb-1">Wali Kelas (Khusus Guru Mata
                            Pelajaran)</label>
                        <select name="wali_kelas"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981]">
                            <option value=""> Bukan Wali Kelas </option>
                            @foreach ($dataKelas ?? [] as $tingkat => $kelasGroup)
                                <optgroup label="Kelas {{ $tingkat }}">
                                    @foreach ($kelasGroup as $kelas)
                                        <option value="{{ $kelas->nama_kelas }}">
                                            {{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <!-- KELAS BINAAN BK (Khusus Guru BK, Multiple Checkbox) -->
                    <div id="add_kelas_binaan_container" class="col-span-2 hidden">
                        <label class="block text-xs font-bold text-blue-600 mb-1">Kelas Binaan (Khusus Guru BK - Boleh
                            pilih lebih dari 1)</label>
                        <div
                            class="grid grid-cols-2 md:grid-cols-4 gap-3 p-4 bg-blue-50/50 border border-blue-100 rounded-xl max-h-48 overflow-y-auto custom-scrollbar">
                            @foreach ($dataKelas ?? [] as $tingkat => $kelasGroup)
                                <div class="col-span-full mt-2 mb-1">
                                    <span
                                        class="text-[10px] font-black text-blue-800 uppercase tracking-widest border-b border-blue-200 pb-1 block">Tingkat
                                        {{ $tingkat }}</span>
                                </div>
                                @foreach ($kelasGroup as $kelas)
                                    <label class="flex items-center space-x-2 cursor-pointer group">
                                        <!-- name="kelas_binaan[]" merupakan array untuk menampung banyak pilihan -->
                                        <input type="checkbox" name="kelas_binaan[]"
                                            value="{{ $kelas->nama_kelas }}"
                                            class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer">
                                        <span
                                            class="text-xs font-bold text-gray-700 group-hover:text-blue-600 transition">{{ $kelas->nama_kelas }}</span>
                                    </label>
                                @endforeach
                            @endforeach
                        </div>
                        <p class="text-[9px] text-gray-400 mt-1 italic">*Centang kelas-kelas yang menjadi tanggung
                            jawab konseling Guru ini.</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 border-t pt-4">
                    <button type="button" onclick="closeModals()"
                        class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-xs hover:bg-gray-200">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-[#10b981] text-white rounded-xl font-bold text-xs hover:bg-green-600 shadow-lg shadow-green-100">Simpan
                        Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL EDIT GURU -->
    <!-- ============================================== -->
    <div id="editModal" class="fixed inset-0 bg-black/50 hidden z-[60] flex items-center justify-center">
        <div class="bg-white rounded-3xl w-full max-w-3xl overflow-hidden shadow-2xl">
            <div class="px-6 py-4 bg-blue-500 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Edit Data Guru</h3>
                <button onclick="closeModals()"
                    class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form id="editForm" method="POST" class="p-6 overflow-y-auto max-h-[80vh]">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">NIP</label>
                        <input type="text" name="nip" id="edit_nip"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="edit_nama" required
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Jenis Kelamin</label>
                        <select name="jk" id="edit_jk"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Pendidikan</label>
                        <select name="pendidikan" id="edit_pendidikan"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                            <option value="">Pilih Pendidikan</option>
                            <option value="Sarjana (S1)">Sarjana (S1)</option>
                            <option value="Magister (S2)">Magister (S2)</option>
                            <option value="Doktor (S3)">Doktor (S3)</option>
                        </select>
                    </div>

                    <!-- Peran Guru Edit -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Peran / Tugas Khusus <span
                                class="text-red-500">*</span></label>
                        <select name="role" id="edit_role" required onchange="toggleRoleOptions('edit')"
                            class="w-full border rounded-xl px-3 py-2 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="guru">Guru Mata Pelajaran / Wali Kelas</option>
                            <option value="bk">Guru Bimbingan Konseling (BK)</option>
                        </select>
                    </div>

                    <!-- Password Baru (Edit) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Password Baru (Opsional)</label>
                        <input type="text" name="password"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500"
                            placeholder="Isi jika ingin ubah password">
                    </div>

                    <!-- Wali Kelas Edit (Khusus Guru) -->
                    <div id="edit_wali_kelas_container" class="col-span-2">
                        <label class="block text-xs font-bold text-gray-600 mb-1">Wali Kelas (Khusus Guru Mata
                            Pelajaran)</label>
                        <select name="wali_kelas" id="edit_wali_kelas"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                            <option value="">Kosongkan Jika Bukan Wali Kelas</option>
                            @foreach ($dataKelas ?? [] as $tingkat => $kelasGroup)
                                <optgroup label="Kelas {{ $tingkat }}">
                                    @foreach ($kelasGroup as $kelas)
                                        <option value="{{ $kelas->nama_kelas }}">
                                            {{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kelas Binaan Edit (Khusus Guru BK) -->
                    <div id="edit_kelas_binaan_container" class="col-span-2 hidden">
                        <label class="block text-xs font-bold text-blue-600 mb-1">Kelas Binaan (Khusus Guru BK - Boleh
                            pilih lebih dari 1)</label>
                        <div
                            class="grid grid-cols-2 md:grid-cols-4 gap-3 p-4 bg-blue-50 border border-blue-100 rounded-xl max-h-48 overflow-y-auto custom-scrollbar">
                            @foreach ($dataKelas ?? [] as $tingkat => $kelasGroup)
                                <div class="col-span-full mt-2 mb-1">
                                    <span
                                        class="text-[10px] font-black text-blue-800 uppercase tracking-widest border-b border-blue-200 pb-1 block">Tingkat
                                        {{ $tingkat }}</span>
                                </div>
                                @foreach ($kelasGroup as $kelas)
                                    <label class="flex items-center space-x-2 cursor-pointer group">
                                        <input type="checkbox" name="kelas_binaan[]"
                                            id="edit_binaan_{{ str_replace(' ', '_', $kelas->nama_kelas) }}"
                                            value="{{ $kelas->nama_kelas }}"
                                            class="edit-binaan-checkbox w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer">
                                        <span
                                            class="text-xs font-bold text-gray-700 group-hover:text-blue-600 transition">{{ $kelas->nama_kelas }}</span>
                                    </label>
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-6 border-t pt-4">
                    <button type="button" onclick="closeModals()"
                        class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-xs hover:bg-gray-200">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-500 text-white rounded-xl font-bold text-xs hover:bg-blue-600 shadow-lg shadow-blue-100">Update
                        Data</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 bg-black/50 hidden z-[60] flex items-center justify-center">
        <div class="bg-white rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl p-6 text-center">
            <div
                class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="font-black text-gray-800 text-xl mb-2">Hapus Guru?</h3>
            <p class="text-xs text-gray-500 mb-6">Anda yakin ingin menghapus data <strong id="delete_nama"
                    class="text-gray-800"></strong>? Aksi ini tidak dapat dibatalkan.</p>

            <form id="deleteForm" method="POST" class="flex justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModals()"
                    class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-xs hover:bg-gray-200">Batal</button>
                <button type="submit"
                    class="px-6 py-2.5 bg-red-500 text-white rounded-xl font-bold text-xs hover:bg-red-600 shadow-lg shadow-red-100">Ya,
                    Hapus!</button>
            </form>
        </div>
    </div>

    <script>
        // Logika Menyembunyikan/Menampilkan Fitur BK vs Guru
        function toggleRoleOptions(modalType) {
            const roleSelect = document.getElementById(modalType + '_role');
            const waliKelasContainer = document.getElementById(modalType + '_wali_kelas_container');
            const kelasBinaanContainer = document.getElementById(modalType + '_kelas_binaan_container');

            if (roleSelect.value === 'bk') {
                // Sembunyikan Wali Kelas, Tampilkan Kelas Binaan
                waliKelasContainer.classList.add('hidden');
                kelasBinaanContainer.classList.remove('hidden');

                // Pastikan opsi wali_kelas kosong jika beralih ke BK (Mencegah salah data)
                if (modalType === 'add') {
                    document.querySelector(`select[name="wali_kelas"]`).value = "";
                } else {
                    document.getElementById('edit_wali_kelas').value = "";
                }
            } else {
                // Tampilkan Wali Kelas, Sembunyikan Kelas Binaan
                waliKelasContainer.classList.remove('hidden');
                kelasBinaanContainer.classList.add('hidden');
            }
        }


        // Logika Pindah View Menu (Profil & Data Guru)
        function showView(viewId) {
            document.querySelectorAll('.view-section').forEach(view => view.classList.remove('active'));
            document.getElementById('view-' + viewId).classList.add('active');

            const titleEl = document.getElementById('view-title');
            const breadcrumbEl = document.getElementById('breadcrumb-active');

            document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));

            if (viewId === 'data-guru') {
                titleEl.innerText = "Data Master Guru";
                breadcrumbEl.innerText = "Data Guru";
                document.getElementById('nav-data-guru').classList.add('active');
            } else if (viewId === 'profile') {
                titleEl.innerText = "Profil Pengguna";
                breadcrumbEl.innerText = "Home / Profil";
                toggleEditProfile(false);
            }
        }

        // Logika Toggle View Profil vs Form Edit
        function toggleEditProfile(showForm) {
            const view = document.getElementById('profileView');
            const form = document.getElementById('profileForm');

            if (showForm) {
                view.classList.add('hidden');
                form.classList.remove('hidden');
            } else {
                view.classList.remove('hidden');
                form.classList.add('hidden');
            }
        }

        // Fungsi Tutup Semua Modal Guru
        function closeModals() {
            document.getElementById('addModal').classList.add('hidden');
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Buka Modal Tambah
        function openAddModal() {
            closeModals();
            document.getElementById('addModal').classList.remove('hidden');
            // Reset state saat modal tambah dibuka
            document.getElementById('add_role').value = 'guru';
            toggleRoleOptions('add');

            // Uncheck semua checkbox binaan
            document.querySelectorAll('#addModal input[type="checkbox"]').forEach(cb => cb.checked = false);
        }

        // Buka Modal Edit & Isi Datanya
        function openEditModal(guru) {
            closeModals();
            document.getElementById('editModal').classList.remove('hidden');

            let baseUrl = "{{ url('admin/guru') }}";
            document.getElementById('editForm').action = baseUrl + "/" + guru.id;

            // Isi form text / dropdown standar
            document.getElementById('edit_nip').value = guru.nip || '';
            document.getElementById('edit_nama').value = guru.nama || '';
            document.getElementById('edit_jk').value = guru.jk || '';
            document.getElementById('edit_pendidikan').value = guru.pendidikan || '';
            document.getElementById('edit_wali_kelas').value = guru.wali_kelas || '';
            document.getElementById('edit_role').value = guru.role || 'guru';

            // Panggil toggle untuk mengatur visibilitas div
            toggleRoleOptions('edit');

            // Reset semua checkbox kelas binaan terlebih dahulu
            const checkboxes = document.querySelectorAll('.edit-binaan-checkbox');
            checkboxes.forEach(cb => cb.checked = false);

            // Jika guru adalah BK, centang kelas binaan yang sesuai
            if (guru.role === 'bk' && guru.kelas_binaan) {
                try {
                    let kelasArray = typeof guru.kelas_binaan === 'string' ? JSON.parse(guru.kelas_binaan) : guru
                        .kelas_binaan;

                    if (Array.isArray(kelasArray)) {
                        kelasArray.forEach(kelasName => {
                            // Mencari checkbox dengan value yang sama dengan nama kelas
                            // Gunakan ID yang diformat agar aman dari spasi
                            let cleanId = "edit_binaan_" + kelasName.replace(/\s+/g, '_');
                            let checkbox = document.getElementById(cleanId);
                            if (checkbox) checkbox.checked = true;
                        });
                    }
                } catch (e) {
                    console.error("Format data kelas binaan tidak valid", e);
                }
            }
        }

        // Buka Modal Hapus
        function openDeleteModal(id, nama) {
            closeModals();
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('delete_nama').innerText = nama;

            let baseUrl = "{{ url('admin/guru') }}";
            document.getElementById('deleteForm').action = baseUrl + "/" + id;
        }

        // Script untuk Dropdown Header & AJAX Form Edit Profil
        document.addEventListener('DOMContentLoaded', function() {
            // LOGIKA DROPDOWN PROFIL
            const profileBtn = document.getElementById('profileDropdownBtn');
            const profileMenu = document.getElementById('profileDropdownMenu');

            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = profileMenu.classList.contains('hidden');

                    if (isHidden) {
                        profileMenu.classList.remove('hidden');
                        setTimeout(() => {
                            profileMenu.classList.remove('opacity-0', 'scale-95');
                            profileMenu.classList.add('opacity-100', 'scale-100');
                        }, 10);
                    } else {
                        profileMenu.classList.remove('opacity-100', 'scale-100');
                        profileMenu.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            profileMenu.classList.add('hidden');
                        }, 200);
                    }
                });

                document.addEventListener('click', function(e) {
                    if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                        profileMenu.classList.remove('opacity-100', 'scale-100');
                        profileMenu.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            profileMenu.classList.add('hidden');
                        }, 200);
                    }
                });
            }

            // LOGIKA SUBMIT FORM PROFIL VIA AJAX
            const profileForm = document.getElementById('profileForm');
            if (profileForm) {
                profileForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerText;

                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                    submitBtn.disabled = true;

                    try {
                        const formData = new FormData(this);

                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const result = await response.json();

                        if (response.ok && result.success) {
                            alert(result.message || 'Profil berhasil diperbarui!');
                            window.location.reload();
                        } else {
                            alert(result.message || 'Terjadi kesalahan saat menyimpan profil.');
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Gagal terhubung ke server.');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });
            }
        });
    </script>
</body>

</html>
