<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Sistem Pelanggaran Poin</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar-item.active {
            background-color: white;
            color: #10b981;
            border-radius: 10px 0 0 10px;
            font-weight: 800;
        }

        /* Tambahan untuk sistem View / Tab Profil */
        .view-section {
            display: none;
        }

        .view-section.active {
            display: block;
        }
    </style>
</head>

<body class="bg-[#f4f7f6] font-sans flex text-gray-800">

    <!-- SIDEBAR -->
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
            <a href="{{ route('admin.guru.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-chalkboard-teacher mr-4 text-sm"></i> <span class="font-medium">Data Guru</span>
            </a>
            <!-- MENU DATA SISWA MENGGUNAKAN ONCLICK SHOWVIEW -->
            <a href="#" onclick="showView('data-siswa')" id="nav-data-siswa"
                class="sidebar-item active flex items-center px-6 py-4 transition">
                <i class="fas fa-users mr-4 text-sm"></i> <span>Data Siswa</span>
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

    <!-- MAIN CONTENT -->
    <main class="flex-1 ml-72 p-10 relative">
        <!-- GLOBAL HEADER -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                    Home / <span id="breadcrumb-active">Data Siswa</span>
                </nav>
                <h2 id="view-title" class="text-2xl font-black text-gray-700 uppercase tracking-tighter italic">Data
                    Master Siswa</h2>
            </div>

            <!-- User Profile & Dropdown -->
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

                <!-- Dropdown Menu -->
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

        <!-- ALERTS -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-2xl relative mb-4 shadow-sm"
                role="alert">
                <span class="block sm:inline font-bold"><i
                        class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl relative mb-4 shadow-sm"
                role="alert">
                <span class="block sm:inline font-bold"><i
                        class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl relative mb-4 shadow-sm"
                role="alert">
                <p class="font-bold mb-1"><i class="fas fa-times-circle mr-2"></i>Terdapat kesalahan:</p>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm font-medium">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- SECTION: DATA SISWA -->
        <!-- ============================================== -->
        <div id="view-data-siswa" class="view-section active">
            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50">
                <div class="flex justify-between items-center mb-8 border-b pb-6">
                    <div>
                        <h3 class="font-black text-gray-700 text-lg uppercase tracking-widest">Daftar Siswa</h3>
                        <p class="text-xs text-gray-400 mt-1">Manajemen data siswa dan poin kedisiplinan</p>
                    </div>
                    <div class="flex gap-3">
                        <button
                            class="bg-[#1e293b] text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase hover:bg-gray-800 transition flex items-center gap-2 shadow-sm shadow-gray-200"
                            onclick="openImportModal()">
                            <i class="fas fa-file-excel text-green-400"></i> Import
                        </button>
                        <button
                            class="bg-[#10b981] text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase hover:bg-green-600 transition flex items-center gap-2 shadow-sm shadow-green-100"
                            onclick="openAddSiswaModal()">
                            <i class="fas fa-user-plus"></i> Tambah Siswa
                        </button>
                    </div>
                </div>

                <!-- FORM FILTER & PENCARIAN -->
                <form action="{{ route('admin.siswa.index') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="md:col-span-2 relative">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Cari Nama/NISN</label>
                        <i class="fas fa-search absolute left-4 top-[34px] text-gray-300 text-xs"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Ketik nama atau NISN..."
                            class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium focus:ring-2 focus:ring-green-100 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Tingkat</label>
                        <select name="tingkat"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-600 outline-none focus:ring-2 focus:ring-green-100 transition appearance-none">
                            <option value="">Semua Tingkat</option>
                            <option value="VII" {{ request('tingkat') == 'VII' ? 'selected' : '' }}>Kelas VII
                            </option>
                            <option value="VIII" {{ request('tingkat') == 'VIII' ? 'selected' : '' }}>Kelas VIII
                            </option>
                            <option value="IX" {{ request('tingkat') == 'IX' ? 'selected' : '' }}>Kelas IX
                            </option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Kelas</label>
                            <select name="kelas"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-600 outline-none focus:ring-2 focus:ring-green-100 transition appearance-none">
                                <option value="">Semua Kelas</option>
                                <option value="A" {{ request('kelas') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ request('kelas') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ request('kelas') == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ request('kelas') == 'D' ? 'selected' : '' }}>D</option>
                            </select>
                        </div>
                        <button type="submit"
                            class="bg-blue-50 text-blue-600 px-4 py-2.5 rounded-xl text-xs font-bold uppercase hover:bg-blue-100 transition h-[42px]">
                            Filter
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead class="bg-[#005c4b] text-white text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="py-4 pl-6 rounded-tl-xl">NISN</th>
                                <th class="py-4">Nama Siswa</th>
                                <th class="py-4 text-center">Kelas</th>
                                <th class="py-4 text-center">Kontak Ortu</th>
                                <th class="py-4 text-center">Poin</th>
                                <th class="py-4 pr-6 rounded-tr-xl text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-gray-100">
                            <!-- DATA SISWA DINAMIS -->
                            @forelse($dataSiswa ?? [] as $siswa)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-5 pl-6 text-gray-500 font-medium">{{ $siswa->nisn }}</td>
                                    <td class="py-5 font-bold text-gray-700">
                                        <div class="flex items-center gap-3">
                                            @if ($siswa->photo)
                                                <img src="{{ asset('storage/' . $siswa->photo) }}"
                                                    class="w-8 h-8 rounded-full object-cover shadow-sm">
                                            @else
                                                <div
                                                    class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold">
                                                    {{ substr($siswa->nama, 0, 1) }}</div>
                                            @endif
                                            <span>{{ $siswa->nama }}</span>
                                        </div>
                                    </td>
                                    <td class="py-5 text-center font-bold text-[#10b981]">{{ $siswa->kelas ?? '-' }}
                                    </td>
                                    <td class="py-5 text-center text-gray-500">{{ $siswa->kontak_ortu ?? '-' }}</td>
                                    <td class="py-5 text-center">
                                        @if (($siswa->poin ?? 0) >= 100)
                                            <span
                                                class="bg-red-100 text-red-700 px-3 py-1 rounded-full font-black">{{ $siswa->poin ?? 0 }}</span>
                                        @elseif(($siswa->poin ?? 0) >= 50)
                                            <span
                                                class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full font-black">{{ $siswa->poin ?? 0 }}</span>
                                        @elseif(($siswa->poin ?? 0) >= 25)
                                            <span
                                                class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full font-black">{{ $siswa->poin ?? 0 }}</span>
                                        @else
                                            <span
                                                class="bg-green-50 text-green-600 px-3 py-1 rounded-full font-black">{{ $siswa->poin ?? 0 }}</span>
                                        @endif
                                    </td>
                                    <td class="py-5 pr-6 text-right">
                                        <!-- Tombol Edit dgn Custom Data Attr (Ditambahkan Alamat dan Ortu) -->
                                        <button onclick="openEditSiswaModal(this)" data-nisn="{{ $siswa->nisn }}"
                                            data-nama="{{ $siswa->nama }}" data-jk="{{ $siswa->jk }}"
                                            data-kelas="{{ $siswa->kelas }}" data-kontak="{{ $siswa->kontak_ortu }}"
                                            data-alamat="{{ $siswa->alamat }}" data-ortu="{{ $siswa->ortu_id }}"
                                            class="text-blue-500 hover:text-blue-700 mx-1 transition p-2 bg-blue-50 rounded-lg hover:bg-blue-100"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Tombol Hapus dgn Custom Data Attr -->
                                        <button onclick="openDeleteSiswaModal(this)" data-nisn="{{ $siswa->nisn }}"
                                            data-nama="{{ $siswa->nama }}"
                                            class="text-red-500 hover:text-red-700 mx-1 transition p-2 bg-red-50 rounded-lg hover:bg-red-100"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-10 text-center text-gray-400 font-bold">
                                        <i class="fas fa-users text-2xl mb-2 block text-gray-300"></i>
                                        Tidak ada data siswa yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    <!-- Paginasi Laravel -->
                    {{ $dataSiswa->links() ?? '' }}
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- SECTION: PROFILE PENGGUNA -->
        <!-- ============================================== -->
        <div id="view-profile" class="view-section">
            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50 max-w-2xl mx-auto">
                <!-- Profile Display -->
                <div id="profileView" class="flex flex-col items-center">
                    <!-- Image -->
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

                <!-- Profile Form (Hidden by default) -->
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
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-50 file:text-[#10b981] hover:file:bg-green-100 transition border border-gray-100 rounded-xl bg-gray-50 cursor-pointer">
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
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition appearance-none">
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
    <!-- MODAL IMPORT EXCEL -->
    <!-- ============================================== -->
    <div id="importSiswaModal"
        class="fixed inset-0 bg-black/50 hidden z-[60] flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl p-6 text-center transform transition-all scale-95 opacity-0 duration-200"
            id="importModalContent">
            <div
                class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="fas fa-file-excel"></i>
            </div>
            <h3 class="font-black text-gray-800 text-xl mb-2">Import Data via Excel</h3>
            <p class="text-xs text-gray-500 mb-4 px-4">Unggah file .xlsx atau .xls Anda di sini. Pastikan urutan
                kolomnya sesuai dengan format sistem.</p>

            <div
                class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-[10px] text-left text-gray-600 mb-6 shadow-inner">
                <p class="font-bold mb-1 text-gray-800"><i class="fas fa-info-circle mr-1"></i> Format Kolom Baris 1
                    (Header):</p>
                <ol class="list-decimal pl-4 space-y-1">
                    <li><span class="font-bold text-gray-800">NISN</span> (Wajib, Angka 10 Digit)</li>
                    <li><span class="font-bold text-gray-800">Nama</span> (Wajib)</li>
                    <li><span class="font-bold text-gray-800">Jenis Kelamin</span> (Laki-laki/Perempuan)</li>
                    <li><span class="font-bold text-gray-800">Kelas</span> (Wajib, Cth: VII A)</li>
                    <li><span class="font-bold text-gray-800">Kontak Ortu</span> (Opsional)</li>
                </ol>
            </div>

            <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col items-center">
                @csrf
                <div class="w-full mb-6">
                    <input type="file" name="file_excel" accept=".xlsx, .xls" required
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 border border-gray-200 rounded-xl cursor-pointer">
                </div>
                <div class="flex justify-center gap-3 w-full">
                    <button type="button" onclick="closeModals()"
                        class="flex-1 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-xs hover:bg-gray-200">Batal</button>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-[#10b981] text-white rounded-xl font-bold text-xs hover:bg-green-600 shadow-lg shadow-green-100 flex items-center justify-center gap-2">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL TAMBAH SISWA (Diperbarui dengan Alamat, Foto, dan Ortu) -->
    <!-- ============================================== -->
    <div id="addSiswaModal"
        class="fixed inset-0 bg-black/50 hidden z-[60] flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl transform transition-all scale-95 opacity-0 duration-200"
            id="addModalContent">
            <div class="px-6 py-4 bg-[#10b981] text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Tambah Data Siswa</h3>
                <button onclick="closeModals()"
                    class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form action="{{ route('admin.siswa.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">NISN <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nisn" required
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981]"
                            placeholder="10 digit angka">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama" required
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Jenis Kelamin</label>
                        <select name="jk"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] appearance-none bg-white">
                            <option value=""> Pilih </option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <!-- DROPDOWN KELAS (DARI CONTROLLER) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Kelas <span
                                class="text-red-500">*</span></label>
                        <select name="kelas" required
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] appearance-none bg-white">
                            <option value=""> Pilih Kelas </option>
                            @foreach ($daftarKelas ?? [] as $kelasItem)
                                <option value="{{ $kelasItem }}">{{ $kelasItem }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Kontak Orang Tua / Wali (No.
                            WA)</label>
                        <input type="text" name="kontak_ortu"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981]"
                            placeholder="Contoh: 08123456789">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Alamat (Opsional)</label>
                        <input type="text" name="alamat"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981]"
                            placeholder="Contoh: Jl. Mawar No. 2">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Akun Orang Tua Terhubung</label>
                        <select name="ortu_id"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] appearance-none bg-white">
                            <option value="">-- Belum ada / Pilih Ortu --</option>
                            @foreach ($daftarOrtu ?? [] as $ortu)
                                <option value="{{ $ortu->id }}">{{ $ortu->name }} ({{ $ortu->email }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[9px] text-gray-400 mt-1">Data akun wali murid terdaftar.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Foto Profil Siswa</label>
                        <input type="file" name="photo" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-50 file:text-[#10b981] hover:file:bg-green-100 border border-gray-200 rounded-xl cursor-pointer">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
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
    <!-- MODAL EDIT SISWA (Diperbarui dengan Alamat, Foto, dan Ortu) -->
    <!-- ============================================== -->
    <div id="editSiswaModal"
        class="fixed inset-0 bg-black/50 hidden z-[60] flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl transform transition-all scale-95 opacity-0 duration-200"
            id="editModalContent">
            <div class="px-6 py-4 bg-blue-500 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Edit Data Siswa</h3>
                <button onclick="closeModals()"
                    class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form id="editSiswaForm" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">NISN (Tidak bisa diubah)</label>
                        <input type="text" name="nisn" id="edit_nisn" readonly
                            class="w-full border bg-gray-100 rounded-xl px-3 py-2 text-sm focus:outline-none text-gray-500 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="edit_nama" required
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Jenis Kelamin</label>
                        <select name="jk" id="edit_jk"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none bg-white">
                            <option value=""> Pilih </option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <!-- DROPDOWN KELAS (DARI CONTROLLER) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Kelas <span
                                class="text-red-500">*</span></label>
                        <select name="kelas" id="edit_kelas" required
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none bg-white">
                            <option value=""> Pilih Kelas </option>
                            @foreach ($daftarKelas ?? [] as $kelasItem)
                                <option value="{{ $kelasItem }}">{{ $kelasItem }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Kontak Orang Tua / Wali (No.
                            WA)</label>
                        <input type="text" name="kontak_ortu" id="edit_kontak_ortu"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Alamat (Opsional)</label>
                        <input type="text" name="alamat" id="edit_alamat"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Akun Orang Tua Terhubung</label>
                        <select name="ortu_id" id="edit_ortu_id"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none bg-white">
                            <option value="">-- Tidak ada --</option>
                            @foreach ($daftarOrtu ?? [] as $ortu)
                                <option value="{{ $ortu->id }}">{{ $ortu->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Ganti Foto Profil</label>
                        <input type="file" name="photo" id="edit_photo" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-xl cursor-pointer">
                        <p class="text-[9px] text-gray-400 mt-1">*Kosongkan jika tidak ingin mengubah foto.</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModals()"
                        class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-xs hover:bg-gray-200">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-500 text-white rounded-xl font-bold text-xs hover:bg-blue-600 shadow-lg shadow-blue-100">Update
                        Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL HAPUS SISWA -->
    <!-- ============================================== -->
    <div id="deleteSiswaModal"
        class="fixed inset-0 bg-black/50 hidden z-[60] flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl p-6 text-center transform transition-all scale-95 opacity-0 duration-200"
            id="deleteModalContent">
            <div
                class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="font-black text-gray-800 text-xl mb-2">Hapus Siswa?</h3>
            <p class="text-xs text-gray-500 mb-6">Anda yakin ingin menghapus data <strong id="delete_nama_siswa"
                    class="text-gray-800"></strong>? Aksi ini tidak dapat dibatalkan.</p>

            <form id="deleteSiswaForm" method="POST" class="flex justify-center gap-3">
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

    <!-- Script Kontrol View & Modal -->
    <script>
        // Logika Pindah View Menu (Profil & Data Siswa)
        function showView(viewId) {
            document.querySelectorAll('.view-section').forEach(view => view.classList.remove('active'));
            document.getElementById('view-' + viewId).classList.add('active');

            const titleEl = document.getElementById('view-title');
            const breadcrumbEl = document.getElementById('breadcrumb-active');

            document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));

            if (viewId === 'data-siswa') {
                if (titleEl) titleEl.innerText = "Data Master Siswa";
                if (breadcrumbEl) breadcrumbEl.innerText = "Data Siswa";
                document.getElementById('nav-data-siswa')?.classList.add('active');
            } else if (viewId === 'profile') {
                if (titleEl) titleEl.innerText = "Profil Pengguna";
                if (breadcrumbEl) breadcrumbEl.innerText = "Home / Profil";
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

        function closeModals() {
            // Animasi tutup modal
            const contents = ['importModalContent', 'addModalContent', 'editModalContent', 'deleteModalContent'];
            contents.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.classList.remove('scale-100', 'opacity-100');
                    el.classList.add('scale-95', 'opacity-0');
                }
            });

            setTimeout(() => {
                document.getElementById('addSiswaModal')?.classList.add('hidden');
                document.getElementById('editSiswaModal')?.classList.add('hidden');
                document.getElementById('deleteSiswaModal')?.classList.add('hidden');
                document.getElementById('importSiswaModal')?.classList.add('hidden');
            }, 200);
        }

        function openModalWithAnimation(modalId, contentId) {
            closeModals();
            setTimeout(() => {
                document.getElementById(modalId)?.classList.remove('hidden');
                document.getElementById(modalId)?.classList.add('flex');
                setTimeout(() => {
                    const content = document.getElementById(contentId);
                    if (content) {
                        content.classList.remove('scale-95', 'opacity-0');
                        content.classList.add('scale-100', 'opacity-100');
                    }
                }, 10);
            }, 210);
        }

        function openImportModal() {
            openModalWithAnimation('importSiswaModal', 'importModalContent');
        }

        function openAddSiswaModal() {
            openModalWithAnimation('addSiswaModal', 'addModalContent');
        }

        function openEditSiswaModal(btn) {
            openModalWithAnimation('editSiswaModal', 'editModalContent');

            // Ambil data dengan metode getAttribute
            let nisn = btn.getAttribute('data-nisn');
            let nama = btn.getAttribute('data-nama');
            let jk = btn.getAttribute('data-jk');
            let kelas = btn.getAttribute('data-kelas');
            let kontak = btn.getAttribute('data-kontak');
            // Data baru dari database:
            let alamat = btn.getAttribute('data-alamat');
            let ortu = btn.getAttribute('data-ortu');

            // Gunakan metode replace agar rute Laravel akurat
            let actionUrl = "{{ route('admin.siswa.update', ':nisn') }}".replace(':nisn', nisn);
            document.getElementById('editSiswaForm').action = actionUrl;

            document.getElementById('edit_nisn').value = nisn || '';
            document.getElementById('edit_nama').value = nama || '';
            document.getElementById('edit_jk').value = jk || '';
            document.getElementById('edit_kelas').value = kelas || '';
            document.getElementById('edit_kontak_ortu').value = kontak || '';
            // Isi input baru
            document.getElementById('edit_alamat').value = alamat || '';
            document.getElementById('edit_ortu_id').value = ortu || '';
            // Kosongkan file foto saat mengedit (karena alasan keamanan browser)
            document.getElementById('edit_photo').value = '';
        }

        function openDeleteSiswaModal(btn) {
            openModalWithAnimation('deleteSiswaModal', 'deleteModalContent');

            let nisn = btn.getAttribute('data-nisn');
            let nama = btn.getAttribute('data-nama');

            document.getElementById('delete_nama_siswa').innerText = nama;

            let actionUrl = "{{ route('admin.siswa.destroy', ':nisn') }}".replace(':nisn', nisn);
            document.getElementById('deleteSiswaForm').action = actionUrl;
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

            // ==========================================
            // LOGIKA SUBMIT FORM PROFIL VIA AJAX
            // ==========================================
            const profileForm = document.getElementById('profileForm');
            if (profileForm) {
                profileForm.addEventListener('submit', async function(e) {
                    e.preventDefault(); // Mencegah form memuat ulang halaman

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerText;

                    // Ubah teks tombol jadi proses loading
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                    submitBtn.disabled = true;

                    try {
                        const formData = new FormData(this);

                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest' // Penting agar Laravel tahu ini request AJAX
                            }
                        });

                        const result = await response.json();

                        if (response.ok && result.success) {
                            alert(result.message || 'Profil berhasil diperbarui!');
                            // Refresh halaman agar data terbaru langsung tampil
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
