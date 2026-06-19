<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sistem Pelanggaran Poin</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar-item.active {
            background-color: white;
            color: #10b981;
            border-radius: 10px 0 0 10px;
            font-weight: 800;
        }

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
            <a href="{{ route('admin.dashboard') }}" class="sidebar-item active flex items-center px-6 py-4 transition">
                <i class="fas fa-th-large mr-4 text-sm"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.guru.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-chalkboard-teacher mr-4 text-sm"></i> <span class="font-medium">Data Guru</span>
            </a>
            <a href="{{ route('admin.siswa.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-users mr-4 text-sm"></i> <span class="font-medium">Data Siswa</span>
            </a>
            <a href="{{ route('admin.konsultasi.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl {{ request()->routeIs('admin.konsultasi.*') ? 'active' : '' }}">
                <i class="fas fa-comments mr-4 text-sm"></i>
                <span class="font-medium">Konsultasi BK</span>
            </a>
            <a href="{{ route('admin.poin.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-star mr-4 text-sm"></i> <span class="font-medium">Poin Siswa</span>
            </a>

            <a href="{{ route('admin.tahunajaran.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-calendar-alt mr-4 text-sm"></i> <span class="font-medium">Tahun Ajaran</span>
            </a>

            <a href="{{ route('admin.audit.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-history mr-4 text-sm"></i> <span class="font-medium">Audit Log</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 ml-72 p-10">
        <!-- GLOBAL HEADER -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                    Home / <span id="breadcrumb-active">Dashboard</span>
                </nav>
                <h2 id="view-title" class="text-2xl font-black text-gray-700 uppercase tracking-tighter italic">
                    Dashboard Admin</h2>
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

        <!-- SECTION: DASHBOARD HOME -->
        <div id="view-dashboard" class="view-section active">
            <!-- Menampilkan Notifikasi Sukses/Error -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-2xl mb-6 relative">
                    <span class="block sm:inline font-bold"><i
                            class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <div class="lg:col-span-2 bg-white p-8 rounded-[30px] shadow-sm border border-gray-50">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-black text-gray-700 text-sm uppercase tracking-widest flex items-center">
                            <i class="fas fa-chart-line mr-3 text-[#10b981]"></i> Tren Pelanggaran Sekolah
                        </h3>
                        <button
                            class="px-3 py-1 bg-green-50 text-[#10b981] text-[10px] font-bold rounded-lg uppercase">Tahun
                            Ini</button>
                    </div>
                    <canvas id="mainChart" height="110"></canvas>
                </div>

                <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50">
                    <h3 class="font-black text-gray-700 text-sm uppercase tracking-widest mb-6">Monitoring Sanksi</h3>
                    <div class="space-y-4">
                        <div
                            class="p-4 bg-yellow-50 rounded-2xl border border-yellow-100 flex justify-between items-center">
                            <span class="text-[10px] font-bold text-yellow-700 uppercase">Panggilan I (25 Poin)</span>
                            <span class="text-lg font-black text-yellow-700">{{ $countPanggilan1 ?? 0 }}</span>
                        </div>
                        <div
                            class="p-4 bg-orange-50 rounded-2xl border border-orange-100 flex justify-between items-center">
                            <span class="text-[10px] font-bold text-orange-700 uppercase">Panggilan II (50 Poin)</span>
                            <span class="text-lg font-black text-orange-700">{{ $countPanggilan2 ?? 0 }}</span>
                        </div>
                        <div class="p-4 bg-red-50 rounded-2xl border border-red-100 flex justify-between items-center">
                            <span class="text-[10px] font-bold text-red-700 uppercase">Drop Out (100 Poin)</span>
                            <span class="text-lg font-black text-red-700">{{ $countDropOut ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Poin -->
            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50">
                <h3 class="font-black text-gray-700 text-sm uppercase tracking-widest mb-8 border-b pb-4">Tabel Poin
                    Keseluruhan</h3>
                <div class="flex gap-6 mb-8">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-4 top-3.5 text-gray-300 text-xs"></i>
                        <input type="text" placeholder="Cari nama siswa..."
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-sm outline-none focus:ring-2 focus:ring-green-100 transition">
                    </div>

                    <!-- Tombol Memicu Modal Kirim Laporan ke Kamad -->
                    <button type="button" onclick="toggleModalLaporan(true)"
                        class="bg-[#10b981] text-white px-6 py-3 rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-105 transition">
                        <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan ke Kamad
                    </button>
                </div>

                <!-- TABLE ASLI MENGGUNAKAN LOOPING BLADE -->
                <table class="w-full text-left">
                    <thead
                        class="text-gray-400 text-[10px] font-black uppercase tracking-widest border-b border-gray-50">
                        <tr>
                            <th class="pb-5 pl-4">NISN</th>
                            <th class="pb-5">Nama</th>
                            <th class="pb-5">Kelas</th>
                            <th class="pb-5 text-center">Poin</th>
                            <th class="pb-5 text-right pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-50">
                        <!-- Looping data dari Controller -->
                        @forelse($siswaPelanggaran ?? [] as $siswa)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-5 pl-4 font-bold text-gray-500">{{ $siswa->nisn }}</td>
                                <td class="py-5 font-black text-gray-800">{{ $siswa->nama }}</td>
                                <td class="py-5 font-bold text-gray-400">{{ $siswa->kelas }}</td>
                                <td class="py-5 text-center">
                                    @if (($siswa->poin ?? 0) >= 100)
                                        <span
                                            class="bg-red-100 text-red-700 px-4 py-1.5 rounded-full font-black">{{ $siswa->poin }}</span>
                                    @elseif(($siswa->poin ?? 0) >= 50)
                                        <span
                                            class="bg-orange-100 text-orange-700 px-4 py-1.5 rounded-full font-black">{{ $siswa->poin }}</span>
                                    @elseif(($siswa->poin ?? 0) >= 25)
                                        <span
                                            class="bg-yellow-100 text-yellow-700 px-4 py-1.5 rounded-full font-black">{{ $siswa->poin }}</span>
                                    @else
                                        <span
                                            class="bg-green-50 text-green-600 px-4 py-1.5 rounded-full font-black">{{ $siswa->poin ?? 0 }}</span>
                                    @endif
                                </td>
                                <td class="py-5 text-right pr-4">
                                    <button type="button" onclick="showDetailRiwayat(this)"
                                        data-nisn="{{ $siswa->nisn }}" data-nama="{{ $siswa->nama }}"
                                        data-kelas="{{ $siswa->kelas }}" data-poin="{{ $siswa->poin ?? 0 }}"
                                        class="text-[#10b981] font-black uppercase text-[10px] tracking-tighter hover:underline cursor-pointer">
                                        Detail Riwayat
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <!-- Tampilan jika data tidak ada -->
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-400 font-bold text-sm">Tidak ada
                                    data pelanggaran siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECTION: DETAIL RIWAYAT SISWA -->
        <div id="view-detail-riwayat" class="view-section">
            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50">
                <div class="flex justify-between items-center mb-8 border-b pb-6">
                    <div>
                        <h3 class="font-black text-gray-700 text-lg uppercase tracking-widest">Detail Riwayat
                            Pelanggaran</h3>
                        <p class="text-xs text-gray-400 mt-1">Rekapitulasi poin dan sanksi siswa terpilih</p>
                    </div>
                    <button onclick="showView('dashboard')"
                        class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl text-xs font-bold uppercase hover:bg-gray-200 transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                </div>

                <!-- Info Siswa -->
                <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100 mb-8 flex flex-wrap gap-x-12 gap-y-4">
                    <div>
                        <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest">Nama Lengkap</p>
                        <p id="detail-nama" class="text-sm font-black text-gray-800 uppercase mt-1">-</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest">NISN</p>
                        <p id="detail-nisn" class="text-sm font-black text-gray-800 uppercase mt-1">-</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest">Kelas</p>
                        <p id="detail-kelas" class="text-sm font-black text-gray-800 uppercase mt-1">-</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest">Total Poin Saat Ini
                        </p>
                        <p id="detail-poin" class="text-xl font-black text-red-600 uppercase mt-1">0</p>
                    </div>
                </div>

                <!-- Tabel Riwayat -->
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#005c4b] text-white text-[10px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="py-4 pl-6 rounded-tl-xl">Tanggal & Waktu</th>
                            <th class="py-4">Jenis Pelanggaran</th>
                            <th class="py-4 text-center">Poin</th>
                            <th class="py-4 pr-6 rounded-tr-xl">Pelapor/Tindakan</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-riwayat-body" class="text-xs divide-y divide-gray-100">
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400 font-bold text-sm">Pilih data
                                siswa untuk memuat riwayat.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECTION: PROFILE PENGGUNA -->
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
    <!-- MODAL KIRIM LAPORAN KE KAMAD                   -->
    <!-- ============================================== -->
    <div id="modalKirimLaporan"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
        <div class="bg-white rounded-[30px] w-full max-w-lg shadow-2xl transform transition-all scale-95 opacity-0"
            id="modalKirimContent">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="font-black text-gray-800 text-xl uppercase tracking-widest">Kirim Laporan</h3>
                        <p class="text-xs text-gray-500 font-medium mt-1">Laporan PDF akan langsung masuk ke Dasbor
                            Kamad</p>
                    </div>
                    <button onclick="toggleModalLaporan(false)"
                        class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 hover:bg-red-100 hover:text-red-500 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('admin.laporan.kirim') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Judul
                            Laporan</label>
                        <input type="text" name="judul" required
                            placeholder="Contoh: Rekapitulasi Pelanggaran Bulan Juni"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Kategori
                            Laporan</label>
                        <select name="kategori" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                            <option value="bulanan">Rekap Bulanan</option>
                            <option value="kelas">Rekap Per Kelas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Upload
                            File (PDF)</label>
                        <input type="file" name="file_laporan" accept=".pdf" required
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-50 file:text-[#10b981] hover:file:bg-green-100 transition border border-gray-100 rounded-xl bg-gray-50">
                        <p class="text-[10px] text-gray-400 mt-2 font-medium"><i class="fas fa-info-circle mr-1"></i>
                            File PDF maksimal 5MB.</p>
                    </div>

                    <button type="submit"
                        class="w-full mt-4 bg-[#10b981] text-white px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-green-100 hover:scale-105 transition flex items-center justify-center gap-2">
                        Kirim Laporan Sekarang <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Logika Pindah View
        function showView(viewId) {
            document.querySelectorAll('.view-section').forEach(view => view.classList.remove('active'));
            document.getElementById('view-' + viewId).classList.add('active');

            const titleEl = document.getElementById('view-title');
            const breadcrumbEl = document.getElementById('breadcrumb-active');

            document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));

            if (viewId === 'dashboard') {
                titleEl.innerText = "Dashboard Admin";
                breadcrumbEl.innerText = "Dashboard";
                document.getElementById('nav-dashboard').classList.add('active');
            } else if (viewId === 'detail-riwayat') {
                titleEl.innerText = "Riwayat Pelanggaran";
                breadcrumbEl.innerText = "Siswa / Riwayat";
            } else if (viewId === 'profile') {
                titleEl.innerText = "Profil Pengguna";
                breadcrumbEl.innerText = "Home / Profil";
            } else {
                titleEl.innerText = "Monitoring Konsultasi BK";
                breadcrumbEl.innerText = "Konsultasi";
                document.getElementById('nav-konsultasi')?.classList.add('active');
            }
        }

        // Logika Modal Laporan
        function toggleModalLaporan(show) {
            const modal = document.getElementById('modalKirimLaporan');
            const content = document.getElementById('modalKirimContent');
            if (show) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                }, 200);
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

        // Logika Ambil Detail Riwayat (AJAX)
        async function showDetailRiwayat(el) {
            // Pindah View
            document.querySelectorAll('.view-section').forEach(view => view.classList.remove('active'));
            document.getElementById('view-detail-riwayat').classList.add('active');

            // Set Data Info Siswa
            const nisn = el.getAttribute('data-nisn');
            document.getElementById('detail-nisn').innerText = nisn || '-';
            document.getElementById('detail-nama').innerText = el.getAttribute('data-nama') || '-';
            document.getElementById('detail-kelas').innerText = el.getAttribute('data-kelas') || '-';
            document.getElementById('detail-poin').innerText = el.getAttribute('data-poin') || '0';

            // Ambil elemen tabel
            const tbody = document.getElementById('tabel-riwayat-body');

            // Tampilkan Loading
            tbody.innerHTML =
                '<tr><td colspan="4" class="py-8 text-center text-gray-400 font-bold"><i class="fas fa-spinner fa-spin mr-2"></i> Memuat data riwayat...</td></tr>';

            try {
                // Panggil API Laravel
                const response = await fetch(`/admin/riwayat/api/${nisn}`);
                const data = await response.json();

                tbody.innerHTML = ''; // Bersihkan loading

                if (data.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="4" class="py-8 text-center text-gray-400 font-bold text-sm">Tidak ada riwayat untuk siswa ini.</td></tr>';
                    return;
                }

                // Masukkan data satu persatu
                data.forEach(item => {
                    const dateObj = new Date(item.waktu);
                    const formattedDate = dateObj.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const isTambah = item.jenis === 'Tambah';
                    const pointColor = isTambah ? 'red' : 'green';
                    const pointSign = isTambah ? '+' : '-';

                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50/50 transition';
                    tr.innerHTML = `
                        <td class="py-4 pl-6 font-bold text-gray-600">${formattedDate}</td>
                        <td class="py-4 font-black text-gray-800">${item.ket || '-'}</td>
                        <td class="py-4 text-center">
                            <span class="bg-${pointColor}-100 text-${pointColor}-600 px-2 py-1 rounded font-bold">
                                ${pointSign}${item.jumlah}
                            </span>
                        </td>
                        <td class="py-4 pr-6 text-gray-500">Sistem</td>
                    `;
                    tbody.appendChild(tr);
                });

            } catch (error) {
                console.error("Gagal mengambil data:", error);
                tbody.innerHTML =
                    '<tr><td colspan="4" class="py-8 text-center text-red-500 font-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Gagal memuat data dari server.</td></tr>';
            }
        }

        // ==========================================
        // DIBUNGKUS DOMContentLoaded AGAR AMAN
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            // LOGIKA CHART.JS (Grafik Tren Bulanan)
            const ctxBesar = document.getElementById('mainChart');
            if (ctxBesar) {
                const chartLabels = {!! json_encode(
                    $chartLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                ) !!};
                const chartData = {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]) !!};

                new Chart(ctxBesar.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'Jumlah Pelanggaran',
                            data: chartData,
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f3f4f6'
                                },
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

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
