<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Pelanggaran Poin Siswa - Guru</title>
    <!-- Tailwind CSS (Kunci Desain) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }

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
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .autocomplete-dropdown::-webkit-scrollbar {
            width: 6px;
        }

        .autocomplete-dropdown::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 8px;
        }

        .autocomplete-dropdown::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }
    </style>
</head>

<body class="bg-[#f4f7f6] font-sans flex text-gray-800 h-screen overflow-hidden relative">

    <!-- BACKDROP UNTUK MOBILE SIDEBAR -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()"
        class="fixed inset-0 bg-gray-900/50 z-40 hidden md:hidden backdrop-blur-sm transition-opacity opacity-0"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="fixed md:relative inset-y-0 left-0 w-72 bg-[#10b981] text-white flex flex-col shadow-xl z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out shrink-0">
        <div class="p-8 relative">
            <!-- Tombol Close Sidebar (Hanya Mobile) -->
            <button onclick="toggleSidebar()"
                class="md:hidden absolute top-6 right-6 text-white/80 hover:text-white focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>

            <div class="flex items-center gap-3 mb-2">
                <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                    class="w-10 drop-shadow-md" alt="Logo Kemenag">
                <h1 class="font-bold text-xl leading-tight tracking-tight uppercase">Panel <br> Guru & BK</h1>
            </div>
            <p class="text-[10px] opacity-80 font-medium tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
        </div>

        <nav class="mt-4 flex-grow pl-6 overflow-y-auto pr-2 space-y-1 pb-10">
            <a href="#" onclick="showView('dashboard')" id="nav-dashboard"
                class="sidebar-item nav-btn active flex items-center px-6 py-4 transition" data-view="dashboard">
                <i class="fas fa-th-large mr-4 text-sm opacity-80"></i> <span>Dashboard</span>
            </a>
            <a href="#" onclick="showView('poin')" id="nav-poin"
                class="sidebar-item nav-btn flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl"
                data-view="poin">
                <i class="fas fa-edit mr-4 text-sm opacity-80"></i> <span class="font-medium">Input Poin Siswa</span>
            </a>
            <a href="#" onclick="showView('data-siswa')" id="nav-data-siswa"
                class="sidebar-item nav-btn flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl"
                data-view="data-siswa">
                <i class="fas fa-users mr-4 text-sm opacity-80"></i> <span class="font-medium">Data Siswa</span>
            </a>

            <!-- HANYA MUNCUL JIKA ROLE ADALAH GURU BK -->
            @if (isset($user['role']) && in_array($user['role'], ['bk', 'guru_bk']))
                <a href="#" onclick="showView('data-kelas')" id="nav-data-kelas"
                    class="sidebar-item nav-btn flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl"
                    data-view="data-kelas">
                    <i class="fas fa-chalkboard-teacher mr-4 text-sm opacity-80"></i> <span class="font-medium">Data
                        Kelas Binaan</span>
                </a>
                <a href="#" onclick="showView('konsultasi')" id="nav-konsultasi"
                    class="sidebar-item nav-btn flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl"
                    data-view="konsultasi">
                    <i class="fas fa-comments mr-4 text-sm opacity-80"></i> <span class="font-medium">Konsultasi
                        Ortu</span>
                </a>
                <a href="#" onclick="showView('laporan')" id="nav-laporan"
                    class="sidebar-item nav-btn flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl"
                    data-view="laporan">
                    <i class="fas fa-file-contract mr-4 text-sm opacity-80"></i> <span class="font-medium">Kirim
                        Laporan</span>
                </a>
            @endif
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-4 md:p-10 overflow-y-auto h-full relative w-full">

        <!-- GLOBAL HEADER RESPONSIVE -->
        <header class="flex justify-between items-center mb-8 md:mb-10 pt-2 md:pt-0">
            <div class="flex items-center gap-3">
                <!-- Hamburger Menu Button (Mobile Only) -->
                <button onclick="toggleSidebar()"
                    class="md:hidden text-gray-500 hover:text-[#10b981] focus:outline-none bg-white p-2 rounded-xl shadow-sm border border-gray-100">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <div>
                    <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1 hidden sm:block">
                        Home / <span id="breadcrumb-active">Dashboard</span>
                    </nav>
                    <h2 id="view-title"
                        class="text-lg md:text-2xl font-black text-gray-700 uppercase tracking-tighter italic leading-tight">
                        Selamat Datang!
                    </h2>
                    <p class="text-[9px] md:text-[10px] text-gray-400 font-bold uppercase mt-1 hidden sm:block">Sistem
                        Input Kedisiplinan Siswa</p>
                </div>
            </div>

            <!-- User Profile & Dropdown -->
            <div class="relative">
                <button id="profileDropdownBtn"
                    class="flex items-center gap-2 md:gap-4 bg-white p-2 md:px-6 md:py-2 rounded-full shadow-sm border border-gray-100 hover:bg-gray-50 transition focus:outline-none">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-black text-[#10b981] uppercase leading-none">
                            {{ $user['name'] ?? 'Bapak/Ibu Guru' }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">NIP:
                            {{ $user['nip'] ?? '-' }}</p>
                    </div>

                    @php
                        $avatarUrl =
                            'https://ui-avatars.com/api/?name=' .
                            urlencode($user['name'] ?? 'Guru') .
                            '&background=10b981&color=fff';
                        $photoPath =
                            isset($user['photo']) && $user['photo']
                                ? (str_starts_with($user['photo'], 'http')
                                    ? $user['photo']
                                    : asset('storage/' . $user['photo']))
                                : $avatarUrl;
                    @endphp
                    <img src="{{ $photoPath }}" onerror="this.src='{{ $avatarUrl }}'"
                        class="w-8 h-8 md:w-10 md:h-10 rounded-full border-2 border-green-50 object-cover shadow-sm"
                        alt="Profile">
                    <i
                        class="fas fa-chevron-down text-gray-400 text-[10px] md:text-xs ml-1 mr-2 md:mr-0 hidden sm:block"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdownMenu"
                    class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden z-50 transform origin-top-right transition-all duration-200 opacity-0 scale-95 profile-dropdown">
                    <div class="py-2 profile-dropdown-content">
                        <!-- Langsung diarahkan ke halaman profil Laravel Anda -->
                        <a href="{{ route('profile.index') }}"
                            class="block w-full text-left px-6 py-3 text-xs font-bold text-gray-700 hover:bg-green-50 hover:text-[#10b981] transition flex items-center gap-3">
                            <i class="fas fa-user-edit"></i> Edit Profil
                        </a>
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
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-2xl relative mb-6 md:mb-8 shadow-sm flex items-start gap-2"
                id="globalAlert">
                <i class="fas fa-check-circle mt-1"></i>
                <span class="block sm:inline font-bold text-sm">{{ session('success') }}</span>
                <button onclick="document.getElementById('globalAlert').style.display='none'"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3 text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl relative mb-6 md:mb-8 shadow-sm flex items-start gap-2"
                id="globalAlertErr">
                <i class="fas fa-exclamation-triangle mt-1"></i>
                <span class="block sm:inline font-bold text-sm">{{ session('error') }}</span>
                <button onclick="document.getElementById('globalAlertErr').style.display='none'"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3 text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <div id="liveAlert"
            class="hidden px-4 py-3 rounded-2xl relative mb-6 shadow-sm font-bold text-sm flex items-start gap-2">
        </div>

        <!-- ============================================== -->
        <!-- VIEW: DASHBOARD (Lebar Penuh & Responsive)     -->
        <!-- ============================================== -->
        <div id="view-dashboard" class="view-section active">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-10">
                <div class="bg-white p-5 md:p-6 rounded-[20px] md:rounded-[30px] shadow-sm border border-gray-100 flex items-center gap-4 md:gap-6 relative overflow-hidden group hover:border-green-200 transition-all cursor-pointer"
                    onclick="showView('poin')">
                    <div
                        class="absolute -right-6 -bottom-6 text-green-50 opacity-50 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-edit text-7xl md:text-9xl"></i>
                    </div>
                    <div
                        class="w-12 h-12 md:w-16 md:h-16 bg-green-100 text-[#10b981] rounded-2xl flex items-center justify-center text-xl md:text-2xl z-10 shadow-inner shrink-0">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="z-10">
                        <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">
                            Input Hari Ini</p>
                        <h3 class="text-2xl md:text-3xl font-black text-gray-800">{{ $inputHariIni ?? 0 }} <span
                                class="text-xs md:text-sm font-bold text-gray-400">Poin</span></h3>
                    </div>
                </div>

                <div class="bg-white p-5 md:p-6 rounded-[20px] md:rounded-[30px] shadow-sm border border-gray-100 flex items-center gap-4 md:gap-6 relative overflow-hidden group hover:border-blue-200 transition-all cursor-pointer"
                    onclick="showView('data-siswa')">
                    <div
                        class="absolute -right-6 -bottom-6 text-blue-50 opacity-50 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-users text-7xl md:text-9xl"></i>
                    </div>
                    <div
                        class="w-12 h-12 md:w-16 md:h-16 bg-blue-100 text-blue-500 rounded-2xl flex items-center justify-center text-xl md:text-2xl z-10 shadow-inner shrink-0">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="z-10">
                        <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">
                            Total Siswa Terdaftar</p>
                        <h3 class="text-2xl md:text-3xl font-black text-gray-800">
                            {{ number_format($totalSiswa ?? 0) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Poin (Lebar Penuh) -->
            <div
                class="bg-white p-5 md:p-8 rounded-[20px] md:rounded-[30px] shadow-sm border border-gray-100 h-full w-full">
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6 border-b border-gray-50 pb-4">
                    <h3 class="font-black text-gray-700 uppercase tracking-widest text-sm md:text-base"><i
                            class="fas fa-list-ul mr-2 text-[#10b981]"></i> Daftar Poin Keseluruhan</h3>
                    <button type="button" onclick="showView('data-siswa')"
                        class="text-[9px] md:text-[10px] font-bold text-[#10b981] uppercase hover:underline bg-green-50 px-3 py-1.5 rounded-lg border border-green-100 whitespace-nowrap">Lihat
                        Semua Data</button>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 mb-6">
                    <div class="flex-1 relative">
                        <i class="fas fa-search absolute left-4 top-3.5 text-gray-300 text-xs"></i>
                        <input type="text" id="filter-nama" placeholder="Cari Nama/NISN..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-medium focus:ring-2 focus:ring-green-100 outline-none transition"
                            oninput="filterDashboard()">
                    </div>
                    <div class="w-full sm:w-48 md:w-64">
                        <select id="filter-kelas-dashboard"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-600 outline-none focus:ring-2 focus:ring-green-100 transition appearance-none cursor-pointer"
                            onchange="filterDashboard()">
                            <option value="">Semua Kelas</option>
                            @foreach ($daftarKelas ?? [] as $kelas)
                                <option value="{{ $kelas }}">{{ $kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div
                    class="overflow-x-auto max-h-[400px] overflow-y-auto custom-scrollbar border border-gray-50 rounded-xl">
                    <table class="w-full text-left text-xs md:text-sm border-collapse min-w-[500px]">
                        <thead class="sticky top-0 bg-white z-10 shadow-sm">
                            <tr
                                class="bg-[#005c4b] text-white text-[9px] md:text-[10px] font-black uppercase tracking-widest">
                                <th class="p-3 md:p-4 rounded-tl-lg w-24 md:w-32">NISN</th>
                                <th class="p-3 md:p-4">Nama Siswa</th>
                                <th class="p-3 md:p-4 text-center w-24 md:w-32">Kelas</th>
                                <th class="p-3 md:p-4 text-center w-32 md:w-40 rounded-tr-lg">Poin Kedisiplinan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100" id="tbl-poin-keseluruhan">
                            @forelse($dataSiswa ?? [] as $siswa)
                                <tr class="hover:bg-gray-50 transition row-dashboard-siswa"
                                    data-kelas="{{ $siswa->kelas }}">
                                    <td class="p-3 md:p-4 font-medium text-gray-500 nisn-col">{{ $siswa->nisn }}</td>
                                    <td class="p-3 md:p-4 font-bold text-gray-800 nama-col">{{ $siswa->nama }}</td>
                                    <td class="p-3 md:p-4 text-center text-[#10b981] font-bold">{{ $siswa->kelas }}
                                    </td>
                                    <td class="p-3 md:p-4 text-center">
                                        @if (($siswa->poin ?? 0) >= 100)
                                            <span
                                                class="bg-red-100 text-red-700 px-3 md:px-4 py-1 md:py-1.5 rounded-full font-black text-[10px] md:text-xs">{{ $siswa->poin ?? 0 }}</span>
                                        @elseif(($siswa->poin ?? 0) >= 50)
                                            <span
                                                class="bg-orange-100 text-orange-700 px-3 md:px-4 py-1 md:py-1.5 rounded-full font-black text-[10px] md:text-xs">{{ $siswa->poin ?? 0 }}</span>
                                        @elseif(($siswa->poin ?? 0) >= 25)
                                            <span
                                                class="bg-yellow-100 text-yellow-700 px-3 md:px-4 py-1 md:py-1.5 rounded-full font-black text-[10px] md:text-xs">{{ $siswa->poin ?? 0 }}</span>
                                        @else
                                            <span
                                                class="bg-green-50 text-green-600 px-3 md:px-4 py-1 md:py-1.5 rounded-full font-black text-[10px] md:text-xs">{{ $siswa->poin ?? 0 }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 md:p-10 text-center text-gray-400 font-bold">
                                        <i class="fas fa-folder-open text-2xl mb-2 text-gray-300 block"></i>
                                        Tidak ada data siswa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- VIEW: INPUT POIN -->
        <!-- ============================================== -->
        <div id="view-poin" class="view-section">
            <div
                class="bg-white p-5 md:p-8 rounded-[20px] md:rounded-[30px] shadow-sm border border-gray-50 mb-6 md:mb-8 relative z-30">
                <div class="mb-6 md:mb-8 border-b pb-4 md:pb-6 flex items-center gap-3">
                    <div
                        class="w-8 h-8 bg-green-50 text-[#10b981] rounded-xl flex items-center justify-center shrink-0">
                        <i class="fas fa-plus"></i>
                    </div>
                    <h3 class="font-black text-gray-700 text-sm md:text-lg uppercase tracking-widest leading-tight">
                        Tambah Poin Pelanggaran
                    </h3>
                </div>

                <form id="poinForm" class="space-y-5 md:space-y-6" onsubmit="submitPoinForm(event)">
                    <!-- Grid Pencarian Siswa & Kelas -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-6">
                        <div class="relative md:col-span-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Cari Nama / NISN
                                Siswa *</label>
                            <div class="relative">
                                <i class="fas fa-search absolute left-4 top-3.5 text-gray-300 text-xs"></i>
                                <input type="text" id="p_nama" placeholder="Ketik nama atau NISN siswa..."
                                    autocomplete="off"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl md:rounded-2xl text-xs md:text-sm font-medium focus:ring-2 focus:ring-green-100 outline-none transition"
                                    required>
                            </div>
                            <div id="nama-list"
                                class="autocomplete-dropdown absolute w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 max-h-48 overflow-y-auto hidden">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Kelas</label>
                            <input type="text" id="p_kelas_display" readonly placeholder="Terisi otomatis"
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-100 rounded-xl md:rounded-2xl text-xs md:text-sm text-gray-500 font-bold cursor-not-allowed">
                        </div>
                    </div>

                    <!-- Grid Pelanggaran & Poin -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 md:gap-6 relative">
                        <div class="md:col-span-3 relative z-40">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Cari & Pilih
                                Pelanggaran *</label>
                            <div class="relative">
                                <i
                                    class="fas fa-exclamation-circle absolute left-4 top-3.5 text-orange-400 text-xs"></i>
                                <input type="text" id="p_search_pelanggaran"
                                    placeholder="Ketik jenis pelanggaran..." autocomplete="off"
                                    class="w-full pl-10 pr-4 py-3 bg-orange-50 border border-orange-100 rounded-xl md:rounded-2xl text-xs md:text-sm font-medium focus:ring-2 focus:ring-orange-200 outline-none transition"
                                    required>
                            </div>
                            <div id="pelanggaran-list"
                                class="autocomplete-dropdown absolute w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 max-h-60 overflow-y-auto hidden">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Skor Poin</label>
                            <div class="relative">
                                <i class="fas fa-hashtag absolute left-4 top-3.5 text-blue-400 text-xs"></i>
                                <input type="number" id="p_jumlah_display" readonly placeholder="Otomatis"
                                    class="w-full pl-10 pr-4 py-3 bg-blue-50 border border-blue-100 rounded-xl md:rounded-2xl text-xs md:text-sm text-blue-700 font-black cursor-not-allowed">
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN FITUR KAMERA / UPLOAD FOTO -->
                    <div class="mt-4 border-t border-gray-50 pt-5 md:pt-6">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Unggah Bukti Foto
                            (Opsional)</label>
                        <div class="flex items-center gap-3 md:gap-4">
                            <div class="relative flex-1">
                                <!-- Memungkinkan user memilih kamera atau file dari galeri -->
                                <input type="file" id="p_foto_bukti" name="foto_bukti" accept="image/*"
                                    class="block w-full text-xs md:text-sm text-gray-500 file:mr-2 md:file:mr-4 file:py-2 md:file:py-3 file:px-3 md:file:px-4 file:rounded-xl file:border-0 file:text-[10px] md:file:text-xs file:font-bold file:bg-green-50 file:text-[#10b981] hover:file:bg-green-100 border border-gray-100 rounded-xl bg-gray-50 cursor-pointer"
                                    onchange="previewImage(event)">
                            </div>
                            <div id="imagePreviewContainer"
                                class="hidden w-12 h-12 md:w-16 md:h-16 rounded-xl border-2 border-dashed border-green-200 overflow-hidden shrink-0 shadow-inner">
                                <img id="imagePreview" src="#" alt="Preview"
                                    class="w-full h-full object-cover">
                            </div>
                        </div>
                        <p class="text-[9px] text-gray-400 mt-2 italic">*Pilih dari galeri atau gunakan kamera HP untuk
                            bukti pelanggaran.</p>
                    </div>

                    <input type="hidden" id="p_nisn" name="nisn" required>
                    <input type="hidden" id="p_keterangan_pelanggaran" name="ket" required>
                    <input type="hidden" id="p_jumlah_poin" name="jumlah" required>

                    <!-- Tombol Aksi Responsive -->
                    <div class="flex flex-col sm:flex-row gap-3 md:gap-4 pt-4 border-t border-gray-50 mt-5 md:mt-6">
                        <button type="submit"
                            class="bg-[#10b981] text-white px-6 md:px-8 py-3.5 rounded-xl md:rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-105 transition flex items-center justify-center gap-2 flex-1">
                            <i class="fas fa-save"></i> Simpan Poin
                        </button>
                        <button type="button" onclick="resetForm()"
                            class="bg-gray-100 text-gray-600 px-6 md:px-8 py-3.5 rounded-xl md:rounded-2xl text-xs font-bold uppercase hover:bg-gray-200 transition flex items-center justify-center gap-2 flex-1 sm:flex-none">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Riwayat Terbaru (Input Poin View) -->
            <div
                class="bg-white p-5 md:p-8 rounded-[20px] md:rounded-[30px] shadow-sm border border-gray-50 relative z-10">
                <div class="flex justify-between items-center mb-6 md:mb-8 border-b pb-4 md:pb-6">
                    <h3
                        class="font-black text-gray-700 text-sm md:text-lg uppercase tracking-widest flex items-center gap-3">
                        <div
                            class="w-8 h-8 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fas fa-history"></i>
                        </div>
                        Riwayat Penambahan Poin
                    </h3>
                </div>

                <div class="overflow-x-auto border border-gray-50 rounded-xl">
                    <table class="w-full text-left border-collapse min-w-[600px] md:min-w-[800px]">
                        <thead
                            class="bg-[#005c4b] text-white text-[9px] md:text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="p-3 md:p-4 rounded-tl-xl w-32 md:w-48">Waktu Masuk</th>
                                <th class="p-3 md:p-4">NISN / Nama Siswa</th>
                                <th class="p-3 md:p-4 text-center">Kelas</th>
                                <th class="p-3 md:p-4">Keterangan Pelanggaran</th>
                                <th class="p-3 md:p-4 text-center">Foto Bukti</th>
                                <th class="p-3 md:p-4 text-center rounded-tr-xl">Poin</th>
                            </tr>
                        </thead>
                        <tbody id="riwayatTableBody" class="text-xs divide-y divide-gray-100">
                            <!-- Diisi via Fetch AJAX JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- VIEW: DATA KELAS BINAAN (Khusus Guru BK)       -->
        <!-- ============================================== -->
        @if (isset($user['role']) && in_array($user['role'], ['bk', 'guru_bk']))
            <div id="view-data-kelas" class="view-section">
                <div class="bg-white p-5 md:p-8 rounded-[20px] md:rounded-[30px] shadow-sm border border-gray-50">
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 border-b pb-4 md:pb-6 gap-3">
                        <div>
                            <h3 class="font-black text-gray-700 text-base md:text-lg uppercase tracking-widest">Data
                                Kelas Binaan</h3>
                            <p class="text-[10px] md:text-xs text-gray-400 mt-1">Daftar siswa yang menjadi tanggung
                                jawab bimbingan Anda.</p>
                        </div>

                        <!-- TOMBOL CETAK KELAS BINAAN -->
                        <button onclick="cetakLaporanOtomatis('binaan')"
                            class="bg-[#10b981] text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-green-600 transition flex items-center gap-2 shadow-sm shadow-green-100 w-full md:w-auto justify-center md:justify-start">
                            <i class="fas fa-print"></i> Cetak Rekap Binaan
                        </button>
                    </div>

                    <!-- Ekstraksi Filter Otomatis Berdasarkan Data Binaan -->
                    @php
                        $tingkatBinaan = [];
                        $suffixBinaan = [];
                        if (isset($siswaBinaan)) {
                            foreach ($siswaBinaan as $sb) {
                                // Memisahkan string kelas (Misal: "VII A" -> "VII" dan "A")
                                $parts = explode(' ', str_replace('-', ' ', $sb->kelas));
                                if (isset($parts[0]) && !in_array($parts[0], $tingkatBinaan)) {
                                    $tingkatBinaan[] = $parts[0];
                                }
                                if (isset($parts[1]) && !in_array($parts[1], $suffixBinaan)) {
                                    $suffixBinaan[] = $parts[1];
                                }
                            }
                        }
                        sort($tingkatBinaan);
                        sort($suffixBinaan);
                    @endphp

                    <!-- FILTER RESPONSIVE DATA BINAAN -->
                    <div class="flex flex-col sm:flex-row gap-3 md:gap-4 mb-6">
                        <div class="flex-1 relative">
                            <i class="fas fa-search absolute left-4 top-3.5 text-gray-300 text-xs"></i>
                            <input type="text" id="search-siswa-binaan"
                                placeholder="Cari Nama/NISN Siswa Binaan..."
                                class="w-full pl-10 pr-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-medium focus:ring-2 focus:ring-green-100 outline-none transition"
                                oninput="filterSiswaBinaan()">
                        </div>
                        <div class="w-full sm:w-32 md:w-40">
                            <select id="filter-tingkat-binaan"
                                class="w-full px-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-600 outline-none focus:ring-2 focus:ring-green-100 transition appearance-none cursor-pointer"
                                onchange="filterSiswaBinaan()">
                                <option value="">Semua Tingkat</option>
                                @foreach ($tingkatBinaan as $tingkat)
                                    <option value="{{ $tingkat }}">{{ $tingkat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-32 md:w-40">
                            <select id="filter-kelas-binaan"
                                class="w-full px-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-600 outline-none focus:ring-2 focus:ring-green-100 transition appearance-none cursor-pointer"
                                onchange="filterSiswaBinaan()">
                                <option value="">Semua Kelas</option>
                                @foreach ($suffixBinaan as $suffix)
                                    <option value="{{ $suffix }}">{{ $suffix }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-gray-50 rounded-xl">
                        <table class="w-full text-left border-collapse min-w-[700px] md:min-w-[800px]">
                            <thead
                                class="bg-[#005c4b] text-white text-[9px] md:text-[10px] font-black uppercase tracking-widest">
                                <tr>
                                    <th class="p-3 md:p-4 rounded-tl-xl">NISN</th>
                                    <th class="p-3 md:p-4">Nama Siswa</th>
                                    <th class="p-3 md:p-4 text-center">Kelas</th>
                                    <th class="p-3 md:p-4">Nama Wali / Ortu</th>
                                    <th class="p-3 md:p-4 text-center">Poin</th>
                                    <th class="p-3 md:p-4 text-center rounded-tr-xl">Profil</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs md:text-sm">
                                @forelse($siswaBinaan ?? [] as $siswa)
                                    <tr class="hover:bg-gray-50 transition row-data-binaan"
                                        data-kelas="{{ $siswa->kelas }}">
                                        <td class="p-3 md:p-4 font-medium text-gray-500 row-nisn">{{ $siswa->nisn }}
                                        </td>
                                        <td class="p-3 md:p-4 font-bold text-gray-800 row-nama">{{ $siswa->nama }}
                                        </td>
                                        <td class="p-3 md:p-4 text-center text-[#10b981] font-bold">
                                            {{ $siswa->kelas ?? '-' }}</td>
                                        <td class="p-3 md:p-4 text-gray-600">
                                            {{ $siswa->ortu->name ?? 'Belum Ditautkan' }} <br>
                                            <span class="text-[9px] text-gray-400 font-bold"><i
                                                    class="fas fa-phone mr-1"></i>
                                                {{ $siswa->kontak_ortu ?? '-' }}</span>
                                        </td>
                                        <td class="p-3 md:p-4 text-center">
                                            @if (($siswa->poin ?? 0) >= 100)
                                                <span
                                                    class="bg-red-100 text-red-700 px-3 py-1 md:py-1.5 rounded-full font-black text-[10px] md:text-xs">{{ $siswa->poin ?? 0 }}</span>
                                            @elseif(($siswa->poin ?? 0) >= 50)
                                                <span
                                                    class="bg-orange-100 text-orange-700 px-3 py-1 md:py-1.5 rounded-full font-black text-[10px] md:text-xs">{{ $siswa->poin ?? 0 }}</span>
                                            @elseif(($siswa->poin ?? 0) >= 25)
                                                <span
                                                    class="bg-yellow-100 text-yellow-700 px-3 py-1 md:py-1.5 rounded-full font-black text-[10px] md:text-xs">{{ $siswa->poin ?? 0 }}</span>
                                            @else
                                                <span
                                                    class="bg-green-50 text-green-600 px-3 py-1 md:py-1.5 rounded-full font-black text-[10px] md:text-xs">{{ $siswa->poin ?? 0 }}</span>
                                            @endif
                                        </td>
                                        <td class="p-3 md:p-4 text-center">
                                            <button
                                                class="text-[#10b981] hover:text-white hover:bg-[#10b981] transition px-3 md:px-4 py-1.5 bg-green-50 rounded-lg text-[10px] md:text-xs font-bold"
                                                onclick="viewSiswaModal('{{ $siswa->nisn }}')"
                                                title="Lihat Detail Profil">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-8 md:p-10 text-center text-gray-400 font-bold">
                                            Belum ada data siswa binaan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- VIEW: DATA SISWA (MASTER)                      -->
        <!-- ============================================== -->
        <div id="view-data-siswa" class="view-section">
            <div class="bg-white p-5 md:p-8 rounded-[20px] md:rounded-[30px] shadow-sm border border-gray-50">
                <div
                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 border-b pb-4 md:pb-6 gap-2">
                    <div>
                        <h3 class="font-black text-gray-700 text-base md:text-lg uppercase tracking-widest">Data Master
                            Siswa</h3>
                        <p class="text-[10px] md:text-xs text-gray-400 mt-1">Lihat profil, kontak wali murid, dan
                            akumulasi poin seluruh siswa madrasah.</p>
                    </div>

                    <!-- TOMBOL CETAK SEMUA SISWA (HANYA MUNCUL JIKA GURU BK) -->
                    @if (isset($user['role']) && in_array($user['role'], ['bk', 'guru_bk']))
                        <button onclick="cetakLaporanOtomatis('all')"
                            class="bg-blue-500 text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-blue-600 transition flex items-center gap-2 shadow-sm shadow-blue-100 w-full md:w-auto justify-center md:justify-start">
                            <i class="fas fa-print"></i> Cetak Seluruh Data
                        </button>
                    @endif
                </div>

                <!-- Ekstraksi Filter Otomatis Berdasarkan Database Kelas -->
                @php
                    $tingkatMaster = [];
                    $suffixMaster = [];
                    if (isset($daftarKelas)) {
                        foreach ($daftarKelas as $dk) {
                            $parts = explode(' ', str_replace('-', ' ', $dk));
                            if (isset($parts[0]) && !in_array($parts[0], $tingkatMaster)) {
                                $tingkatMaster[] = $parts[0];
                            }
                            if (isset($parts[1]) && !in_array($parts[1], $suffixMaster)) {
                                $suffixMaster[] = $parts[1];
                            }
                        }
                    }
                    sort($suffixMaster); // Abjad A-Z (Otomatis mencakup A sampai K dst)
                    // Tingkat (VII, VIII, IX) tidak di-sort ulang agar mengikuti urutan asli dari database (Romawi)
                @endphp

                <!-- Filter Responsif Data Master -->
                <div class="flex flex-col sm:flex-row gap-3 md:gap-4 mb-6">
                    <div class="flex-1 relative">
                        <i class="fas fa-search absolute left-4 top-3.5 text-gray-300 text-xs"></i>
                        <input type="text" id="search-siswa" placeholder="Cari Nama/NISN..."
                            class="w-full pl-10 pr-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-medium focus:ring-2 focus:ring-green-100 outline-none transition"
                            oninput="filterSiswa()">
                    </div>
                    <div class="w-full sm:w-32 md:w-40">
                        <select id="filter-tingkat-siswa"
                            class="w-full px-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-600 outline-none focus:ring-2 focus:ring-green-100 transition appearance-none cursor-pointer"
                            onchange="filterSiswa()">
                            <option value="">Semua Tingkat</option>
                            @foreach ($tingkatMaster as $tingkat)
                                <option value="{{ $tingkat }}">Kelas {{ $tingkat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full sm:w-32 md:w-40">
                        <select id="filter-kelas-siswa"
                            class="w-full px-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-600 outline-none focus:ring-2 focus:ring-green-100 transition appearance-none cursor-pointer"
                            onchange="filterSiswa()">
                            <option value="">Semua Kelas</option>
                            @foreach ($suffixMaster as $suffix)
                                <option value="{{ $suffix }}">{{ $suffix }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto border border-gray-50 rounded-xl">
                    <table class="w-full text-left border-collapse min-w-[700px] md:min-w-[800px]">
                        <thead
                            class="bg-[#005c4b] text-white text-[9px] md:text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="p-3 md:p-4 rounded-tl-xl">NISN</th>
                                <th class="p-3 md:p-4">Nama Siswa</th>
                                <th class="p-3 md:p-4 text-center">Kelas</th>
                                <th class="p-3 md:p-4">Nama Wali / Ortu</th>
                                <th class="p-3 md:p-4 text-center">Poin</th>
                                <th class="p-3 md:p-4 text-center rounded-tr-xl">Profil</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs md:text-sm">
                            @forelse($dataSiswa ?? [] as $siswa)
                                <tr class="hover:bg-gray-50 transition row-data-siswa"
                                    data-kelas="{{ $siswa->kelas }}">
                                    <td class="p-3 md:p-4 font-medium text-gray-500 row-nisn">{{ $siswa->nisn }}</td>
                                    <td class="p-3 md:p-4 font-bold text-gray-800 row-nama">{{ $siswa->nama }}</td>
                                    <td class="p-3 md:p-4 text-center text-[#10b981] font-bold">
                                        {{ $siswa->kelas ?? '-' }}</td>
                                    <td class="p-3 md:p-4 text-gray-600">
                                        {{ $siswa->ortu->name ?? 'Belum Ditautkan' }} <br>
                                        <span class="text-[9px] text-gray-400 font-bold"><i
                                                class="fas fa-phone mr-1"></i> {{ $siswa->kontak_ortu ?? '-' }}</span>
                                    </td>
                                    <td class="p-3 md:p-4 text-center">
                                        @if (($siswa->poin ?? 0) >= 100)
                                            <span
                                                class="bg-red-100 text-red-700 px-3 py-1 md:py-1.5 rounded-full font-black text-[10px] md:text-xs">{{ $siswa->poin ?? 0 }}</span>
                                        @elseif(($siswa->poin ?? 0) >= 50)
                                            <span
                                                class="bg-orange-100 text-orange-700 px-3 py-1 md:py-1.5 rounded-full font-black text-[10px] md:text-xs">{{ $siswa->poin ?? 0 }}</span>
                                        @elseif(($siswa->poin ?? 0) >= 25)
                                            <span
                                                class="bg-yellow-100 text-yellow-700 px-3 py-1 md:py-1.5 rounded-full font-black text-[10px] md:text-xs">{{ $siswa->poin ?? 0 }}</span>
                                        @else
                                            <span
                                                class="bg-green-50 text-green-600 px-3 py-1 md:py-1.5 rounded-full font-black text-[10px] md:text-xs">{{ $siswa->poin ?? 0 }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3 md:p-4 text-center">
                                        <button
                                            class="text-[#10b981] hover:text-white hover:bg-[#10b981] transition px-3 md:px-4 py-1.5 bg-green-50 rounded-lg text-[10px] md:text-xs font-bold"
                                            onclick="viewSiswaModal('{{ $siswa->nisn }}')"
                                            title="Lihat Detail Profil">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 md:p-10 text-center text-gray-400 font-bold">Belum
                                        ada data siswa untuk ditampilkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- VIEW: KONSULTASI BK (Khusus Guru BK)           -->
        <!-- ============================================== -->
        @if (isset($user['role']) && in_array($user['role'], ['bk', 'guru_bk']))
            <div id="view-konsultasi" class="view-section">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 items-start">

                    <!-- FORM KIRIM PESAN -->
                    <div
                        class="lg:col-span-1 bg-white p-5 md:p-8 rounded-[20px] md:rounded-[30px] shadow-sm border border-gray-50 h-fit lg:sticky top-10">
                        <h3
                            class="font-black text-gray-700 text-xs md:text-sm uppercase tracking-widest mb-5 md:mb-6 border-b pb-3 md:pb-4 flex items-center">
                            <div
                                class="w-7 h-7 md:w-8 md:h-8 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center mr-3 shrink-0">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            Kirim Pesan ke Ortu
                        </h3>

                        <form action="{{ route('guru.konsultasi.kirim') }}" method="POST"
                            class="space-y-4 md:space-y-5">
                            @csrf

                            <div>
                                <label
                                    class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Tahun
                                    Ajaran</label>
                                <div class="relative">
                                    <i
                                        class="fas fa-calendar-alt absolute left-3.5 md:left-4 top-3 md:top-3.5 text-gray-400 text-xs"></i>
                                    <select name="academic_period" required
                                        class="w-full pl-9 md:pl-10 pr-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-blue-100 transition appearance-none">
                                        @php $currentYear = \Carbon\Carbon::now()->year; @endphp
                                        <option value="{{ $currentYear }}/{{ $currentYear + 1 }} Genap">
                                            {{ $currentYear }}/{{ $currentYear + 1 }} Genap</option>
                                        <option value="{{ $currentYear }}/{{ $currentYear + 1 }} Ganjil">
                                            {{ $currentYear }}/{{ $currentYear + 1 }} Ganjil</option>
                                        <option value="{{ $currentYear - 1 }}/{{ $currentYear }} Genap">
                                            {{ $currentYear - 1 }}/{{ $currentYear }} Genap</option>
                                        <option value="{{ $currentYear - 1 }}/{{ $currentYear }} Ganjil">
                                            {{ $currentYear - 1 }}/{{ $currentYear }} Ganjil</option>
                                    </select>
                                    <i
                                        class="fas fa-chevron-down absolute right-3 md:right-4 top-3.5 text-gray-400 text-[10px] md:text-xs pointer-events-none"></i>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Pilih
                                    Siswa (Binaan)</label>
                                <div class="relative">
                                    <i
                                        class="fas fa-user-graduate absolute left-3.5 md:left-4 top-3 md:top-3.5 text-gray-400 text-xs"></i>
                                    <select name="siswa_id" required
                                        class="w-full pl-9 md:pl-10 pr-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-blue-100 transition appearance-none">
                                        <option value=""> Pilih Siswa </option>
                                        @foreach ($siswaBinaan ?? [] as $sb)
                                            <option value="{{ $sb->id }}">{{ $sb->nama }} (Kelas
                                                {{ $sb->kelas }})</option>
                                        @endforeach
                                    </select>
                                    <i
                                        class="fas fa-chevron-down absolute right-3 md:right-4 top-3.5 text-gray-400 text-[10px] md:text-xs pointer-events-none"></i>
                                </div>
                                <p class="text-[8px] md:text-[9px] text-gray-400 mt-1 italic">*Hanya menampilkan siswa
                                    dari kelas binaan Anda.</p>
                            </div>

                            <div>
                                <label
                                    class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Topik
                                    Pesan</label>
                                <input type="text" name="topik" required
                                    placeholder="Contoh: Panggilan Wali Murid"
                                    class="w-full px-3 md:px-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-medium outline-none focus:ring-2 focus:ring-blue-100 transition">
                            </div>

                            <div>
                                <label
                                    class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Isi
                                    Pesan / Keterangan</label>
                                <textarea name="pesan" rows="4" required
                                    placeholder="Tuliskan tujuan pemanggilan atau pesan Anda secara detail..."
                                    class="w-full px-3 md:px-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-medium outline-none focus:ring-2 focus:ring-blue-100 transition resize-none"></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-500 text-white px-4 md:px-6 py-3 md:py-4 rounded-xl text-[10px] md:text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-100 hover:bg-blue-600 hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                                Kirim Pesan <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>

                    <!-- RIWAYAT KONSULTASI -->
                    <div
                        class="lg:col-span-2 bg-white p-5 md:p-8 rounded-[20px] md:rounded-[40px] shadow-sm border border-gray-50">
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 border-b pb-4 gap-2">
                            <div>
                                <h3
                                    class="font-black text-gray-700 text-base md:text-lg uppercase tracking-widest flex items-center gap-2 md:gap-3">
                                    <div
                                        class="w-7 h-7 md:w-8 md:h-8 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center shrink-0">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    Riwayat Pesan
                                </h3>
                                <p class="text-[10px] md:text-xs text-gray-400 mt-1 md:mt-2">Daftar riwayat percakapan
                                    Anda dengan Orang Tua siswa binaan.</p>
                            </div>
                        </div>

                        <div class="space-y-4 md:space-y-6">
                            @forelse($konsultasi ?? [] as $kon)
                                <div
                                    class="border border-gray-100 rounded-[16px] md:rounded-[24px] p-4 md:p-6 transition-all hover:shadow-md {{ ($kon->status ?? 'menunggu') == 'menunggu' ? 'bg-orange-50/30' : 'bg-gray-50/50' }}">

                                    @if (($kon->pengirim ?? 'ortu') == 'bk')
                                        <div
                                            class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-between items-start mb-3 md:mb-4 border-b border-gray-100 pb-3 md:pb-4">
                                            <div class="flex flex-wrap items-center gap-2 md:gap-3">
                                                <span
                                                    class="bg-blue-100 text-blue-700 px-2 md:px-3 py-1 md:py-1.5 rounded-lg text-[8px] md:text-[9px] font-black uppercase tracking-widest shadow-sm">
                                                    Pesan Keluar
                                                </span>
                                                <span class="text-[10px] md:text-xs text-gray-400 font-bold">
                                                    <i class="far fa-clock mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($kon->created_at)->format('d M Y, H:i') }}
                                                </span>
                                            </div>
                                            <span
                                                class="text-[10px] md:text-xs font-bold text-gray-600 bg-white px-3 py-1 md:px-4 md:py-1.5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-2">
                                                <i class="fas fa-child text-blue-500"></i>
                                                {{ $kon->student->nama ?? 'Siswa' }}
                                            </span>
                                        </div>

                                        <div class="mb-4 md:mb-5">
                                            <h4 class="font-bold text-gray-800 text-xs md:text-sm uppercase mb-1">
                                                {{ $kon->topic }}</h4>
                                            <p
                                                class="text-[9px] md:text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-2">
                                                Ditujukan Ke: Ortu {{ $kon->student->nama ?? 'Siswa' }}</p>
                                            <div
                                                class="bg-blue-50/50 p-3 md:p-4 rounded-xl md:rounded-2xl border border-blue-100 shadow-sm relative">
                                                <div
                                                    class="absolute w-2 h-2 md:w-3 md:h-3 bg-blue-50/50 border-t border-l border-blue-100 transform -rotate-45 -top-1 md:-top-1.5 left-4 md:left-6">
                                                </div>
                                                <p
                                                    class="text-[10px] md:text-xs text-gray-700 leading-relaxed font-medium relative z-10">
                                                    "{{ $kon->message }}"</p>
                                            </div>
                                        </div>

                                        @if ($kon->reply)
                                            <div class="pl-4 md:pl-6 border-l-2 border-[#10b981] relative mt-2">
                                                <div
                                                    class="absolute -left-[5px] md:-left-2 top-0 w-2.5 h-2.5 md:w-3.5 md:h-3.5 bg-[#10b981] rounded-full border border-white md:border-2">
                                                </div>
                                                <p
                                                    class="text-[9px] md:text-[10px] font-black text-[#10b981] uppercase tracking-widest mb-1.5 md:mb-2 flex items-center gap-1.5 md:gap-2">
                                                    <i class="fas fa-reply"></i> Balasan Orang Tua
                                                </p>
                                                <div
                                                    class="bg-green-50/50 p-3 md:p-4 rounded-xl md:rounded-2xl border border-green-100/50">
                                                    <p
                                                        class="text-[10px] md:text-xs text-gray-700 font-medium leading-relaxed">
                                                        {{ $kon->reply }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-[9px] md:text-[10px] text-gray-400 font-bold italic mt-2"><i
                                                    class="fas fa-clock mr-1"></i> Menunggu balasan dari Orang Tua
                                                siswa...</p>
                                        @endif
                                    @else
                                        <div
                                            class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-between items-start mb-3 md:mb-4 border-b border-gray-100 pb-3 md:pb-4">
                                            <div class="flex flex-wrap items-center gap-2 md:gap-3">
                                                @if (($kon->status ?? 'menunggu') == 'menunggu')
                                                    <span
                                                        class="bg-orange-100 text-orange-600 px-2 md:px-3 py-1 md:py-1.5 rounded-lg text-[8px] md:text-[9px] font-black uppercase tracking-widest shadow-sm">Perlu
                                                        Dibalas</span>
                                                @else
                                                    <span
                                                        class="bg-green-100 text-green-700 px-2 md:px-3 py-1 md:py-1.5 rounded-lg text-[8px] md:text-[9px] font-black uppercase tracking-widest shadow-sm">Sudah
                                                        Dibalas</span>
                                                @endif
                                                <span class="text-[10px] md:text-xs text-gray-400 font-bold">
                                                    <i class="far fa-clock mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($kon->created_at)->format('d M Y, H:i') }}
                                                </span>
                                            </div>
                                            <span
                                                class="text-[10px] md:text-xs font-bold text-gray-600 bg-white px-3 py-1 md:px-4 md:py-1.5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-2">
                                                <i class="fas fa-child text-[#10b981]"></i>
                                                {{ $kon->student->nama ?? 'Siswa' }}
                                            </span>
                                        </div>

                                        <div class="mb-4 md:mb-5">
                                            <h4 class="font-bold text-gray-800 text-xs md:text-sm uppercase mb-1">
                                                {{ $kon->topic ?? 'Tanpa Topik' }}</h4>
                                            <p
                                                class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                                                Dari: {{ $kon->parent->name ?? 'Orang Tua' }}</p>
                                            <div
                                                class="bg-white p-3 md:p-4 rounded-xl md:rounded-2xl border border-gray-100 shadow-sm relative">
                                                <div
                                                    class="absolute w-2 h-2 md:w-3 md:h-3 bg-white border-t border-l border-gray-100 transform -rotate-45 -top-1 md:-top-1.5 left-4 md:left-6">
                                                </div>
                                                <p
                                                    class="text-[10px] md:text-xs text-gray-600 leading-relaxed font-medium relative z-10">
                                                    "{{ $kon->message ?? '-' }}"</p>
                                            </div>
                                        </div>

                                        @if (($kon->status ?? 'menunggu') == 'menunggu')
                                            <!-- Form Balasan BK -->
                                            <form action="{{ route('guru.konsultasi.balas', $kon->id) }}"
                                                method="POST" class="mt-4">
                                                @csrf
                                                <label
                                                    class="block text-[9px] md:text-[10px] font-bold text-[#10b981] uppercase tracking-widest mb-1.5 md:mb-2">Tulis
                                                    Balasan Anda:</label>
                                                <textarea name="balasan" rows="3" required placeholder="Ketik balasan untuk orang tua di sini..."
                                                    class="w-full px-3 md:px-4 py-2.5 md:py-3 bg-white border border-gray-200 rounded-xl text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-green-100 transition mb-2 md:mb-3"></textarea>
                                                <button type="submit"
                                                    class="bg-[#10b981] text-white px-4 md:px-6 py-2 md:py-2.5 rounded-xl text-[10px] md:text-xs font-bold uppercase hover:bg-green-600 transition shadow-md shadow-green-100 w-full sm:w-auto">
                                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Balasan
                                                </button>
                                            </form>
                                        @else
                                            <div class="pl-4 md:pl-6 border-l-2 border-[#10b981] relative mt-2">
                                                <div
                                                    class="absolute -left-[5px] md:-left-2 top-0 w-2.5 h-2.5 md:w-3.5 md:h-3.5 bg-[#10b981] rounded-full border border-white md:border-2">
                                                </div>
                                                <p
                                                    class="text-[9px] md:text-[10px] font-black text-[#10b981] uppercase tracking-widest mb-1.5 md:mb-2 flex items-center gap-1.5 md:gap-2">
                                                    <i class="fas fa-reply"></i> Balasan Anda
                                                </p>
                                                <div
                                                    class="bg-green-50/50 p-3 md:p-4 rounded-xl md:rounded-2xl border border-green-100/50">
                                                    <p
                                                        class="text-[10px] md:text-xs text-gray-700 font-medium leading-relaxed">
                                                        {{ $kon->reply ?? '-' }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @empty
                                <div
                                    class="text-center py-8 md:py-10 border-2 border-dashed border-gray-200 rounded-[20px] md:rounded-[30px] bg-gray-50/50">
                                    <div
                                        class="w-16 h-16 md:w-20 md:h-20 bg-white shadow-sm border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 md:mb-4">
                                        <i class="fas fa-comment-slash text-2xl md:text-3xl text-gray-300"></i>
                                    </div>
                                    <h4 class="text-sm md:text-base font-black text-gray-700 mb-1 tracking-tight">Belum
                                        ada riwayat pesan.</h4>
                                    <p
                                        class="text-[10px] md:text-xs text-gray-500 font-medium max-w-[200px] md:max-w-xs mx-auto">
                                        Gunakan form di samping untuk mengirim panggilan/pesan baru ke orang tua siswa
                                        binaan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- VIEW: KIRIM LAPORAN (Khusus Guru BK)           -->
        <!-- ============================================== -->
        @if (isset($user['role']) && in_array($user['role'], ['bk', 'guru_bk']))
            <div id="view-laporan" class="view-section">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 items-start">

                    <!-- FORM UNGGAH LAPORAN -->
                    <div
                        class="lg:col-span-1 bg-white p-5 md:p-8 rounded-[20px] md:rounded-[30px] shadow-sm border border-gray-50 h-fit lg:sticky top-10">
                        <h3
                            class="font-black text-gray-700 text-xs md:text-sm uppercase tracking-widest mb-5 md:mb-6 border-b pb-3 md:pb-4 flex items-center">
                            <div
                                class="w-7 h-7 md:w-8 md:h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center mr-3 shrink-0">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            Unggah Laporan PDF
                        </h3>

                        <form action="{{ route('laporan.kirim') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4 md:space-y-5">
                            @csrf
                            <div>
                                <label
                                    class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Judul
                                    Laporan</label>
                                <input type="text" name="judul" required
                                    placeholder="Contoh: Laporan BK Kelas IX - April"
                                    class="w-full px-3 md:px-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-medium outline-none focus:ring-2 focus:ring-red-100 transition">
                            </div>

                            <div>
                                <label
                                    class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Kategori</label>
                                <div class="relative">
                                    <i
                                        class="fas fa-tags absolute left-3.5 md:left-4 top-3 md:top-3.5 text-gray-400 text-xs"></i>
                                    <select name="kategori" required
                                        class="w-full pl-9 md:pl-10 pr-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-red-100 transition appearance-none">
                                        <option value="bulanan">Rekapitulasi Bulanan</option>
                                        <option value="kelas">Laporan Khusus Kelas</option>
                                    </select>
                                    <i
                                        class="fas fa-chevron-down absolute right-3 md:right-4 top-3.5 text-gray-400 text-[10px] md:text-xs pointer-events-none"></i>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Pilih
                                    File Laporan (Wajib PDF)</label>
                                <input type="file" name="file_laporan" accept=".pdf" required
                                    class="block w-full text-xs md:text-sm text-gray-500 file:mr-2 md:file:mr-4 file:py-2 md:file:py-3 file:px-3 md:file:px-4 file:rounded-xl file:border-0 file:text-[10px] md:file:text-xs file:font-bold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 border border-gray-100 rounded-xl bg-gray-50 cursor-pointer">
                                <p class="text-[8px] md:text-[9px] text-gray-400 mt-1 italic">*Maksimal 5MB.</p>
                            </div>

                            <button type="submit"
                                class="w-full bg-red-500 text-white px-4 md:px-6 py-3 md:py-4 rounded-xl text-[10px] md:text-xs font-bold uppercase tracking-wider shadow-lg shadow-red-100 hover:bg-red-600 hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                                Kirim Ke Pimpinan <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>

                    <!-- TABEL RIWAYAT LAPORAN -->
                    <div
                        class="lg:col-span-2 bg-white p-5 md:p-8 rounded-[20px] md:rounded-[40px] shadow-sm border border-gray-50">
                        <div
                            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 border-b pb-4 gap-2">
                            <h3
                                class="font-black text-gray-700 text-base md:text-lg uppercase tracking-widest flex items-center gap-2 md:gap-3">
                                <div
                                    class="w-7 h-7 md:w-8 md:h-8 bg-gray-100 text-gray-600 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fas fa-history"></i>
                                </div>
                                Riwayat Laporan Terkirim
                            </h3>
                        </div>

                        <div class="overflow-x-auto border border-gray-50 rounded-xl">
                            <table class="w-full text-left border-collapse min-w-[500px]">
                                <thead
                                    class="bg-[#005c4b] text-white text-[9px] md:text-[10px] font-black uppercase tracking-widest">
                                    <tr>
                                        <th class="p-3 md:p-4 rounded-tl-xl w-24 md:w-32">Tanggal</th>
                                        <th class="p-3 md:p-4">Judul Laporan</th>
                                        <th class="p-3 md:p-4 text-center">Kategori</th>
                                        <th class="p-3 md:p-4 text-center rounded-tr-xl">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs divide-y divide-gray-100">
                                    @forelse($riwayatLaporan ?? [] as $lap)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="p-3 md:p-4 font-medium text-gray-500 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($lap->created_at)->format('d M Y') }}</td>
                                            <td class="p-3 md:p-4 font-bold text-gray-800">{{ $lap->judul }}</td>
                                            <td class="p-3 md:p-4 text-center">
                                                <span
                                                    class="bg-blue-50 text-blue-600 px-2 md:px-3 py-1 rounded-lg font-black text-[9px] md:text-[10px] uppercase border border-blue-100">{{ $lap->kategori }}</span>
                                            </td>
                                            <td class="p-3 md:p-4 text-center whitespace-nowrap">
                                                <a href="{{ asset('storage/' . $lap->file_path) }}" target="_blank"
                                                    class="inline-flex bg-blue-50 text-blue-600 px-3 md:px-4 py-1.5 rounded-lg text-[9px] md:text-[10px] font-bold hover:bg-blue-100 transition items-center gap-1 md:gap-2 border border-blue-100">
                                                    <i class="fas fa-file-pdf"></i> <span
                                                        class="hidden sm:inline">Lihat</span>
                                                </a>
                                                <!-- TOMBOL TARIK/HAPUS LAPORAN YANG DIPERBARUI -->
                                                <form action="{{ route('laporan.hapus', $lap->id) }}" method="POST"
                                                    class="inline-block ml-1 form-tarik-laporan">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="openKonfirmasiTarik(this)"
                                                        class="inline-flex bg-red-50 text-red-600 px-3 md:px-4 py-1.5 rounded-lg text-[9px] md:text-[10px] font-bold hover:bg-red-100 transition items-center gap-1 md:gap-2 border border-red-100">
                                                        <i class="fas fa-undo"></i> <span
                                                            class="hidden sm:inline">Tarik</span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="p-6 md:p-10 text-center text-gray-400 font-bold">Belum ada
                                                laporan yang Anda kirim ke Pimpinan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </main>

    <!-- ============================================== -->
    <!-- MODAL PROFIL SISWA (LOKAL TANPA FETCH)         -->
    <!-- ============================================== -->
    <div id="siswaProfileModal"
        class="fixed inset-0 bg-gray-900/80 hidden z-[60] flex items-center justify-center backdrop-blur-sm transition-opacity opacity-0 duration-200 p-4">
        <div class="bg-white rounded-[20px] md:rounded-[30px] p-6 md:p-8 max-w-lg w-full shadow-2xl transform scale-95 transition-transform duration-200"
            id="siswaProfileContent">
            <div class="flex justify-between items-center mb-5 md:mb-6 border-b pb-3 md:pb-4">
                <h3 class="text-lg md:text-xl font-black text-gray-800 uppercase tracking-wider">Detail Profil Siswa
                </h3>
                <button onclick="closeSiswaProfileModal()"
                    class="text-gray-400 hover:text-red-500 transition focus:outline-none"><i
                        class="fas fa-times text-xl md:text-2xl"></i></button>
            </div>

            <div class="flex flex-col items-center mb-6 md:mb-8">
                <div id="siswaProfilePic"
                    class="w-20 h-20 md:w-24 md:h-24 bg-green-100 rounded-full mb-3 flex items-center justify-center text-[#10b981] text-2xl md:text-3xl font-bold border-4 border-green-50 shadow-sm overflow-hidden">
                </div>
                <h4 id="siswaProfileNama"
                    class="text-base md:text-lg font-black text-gray-800 uppercase text-center leading-tight">-</h4>
                <span id="siswaProfileNisn"
                    class="text-[10px] md:text-xs font-bold text-gray-500 mt-1 md:mt-2 bg-gray-100 px-3 py-1 rounded-full">-</span>
            </div>

            <div class="grid grid-cols-2 gap-3 md:gap-4 mb-4">
                <div class="bg-gray-50 p-3 md:p-4 rounded-xl md:rounded-2xl border border-gray-100 text-center">
                    <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kelas
                    </p>
                    <p id="siswaProfileKelas" class="text-xs md:text-sm font-bold text-gray-700">-</p>
                </div>
                <div class="bg-red-50 p-3 md:p-4 rounded-xl md:rounded-2xl border border-red-100 text-center">
                    <p class="text-[9px] md:text-[10px] font-bold text-red-400 uppercase tracking-widest mb-1">Total
                        Poin</p>
                    <p id="siswaProfilePoin" class="font-black text-red-600 text-lg md:text-xl">0</p>
                </div>
                <div class="bg-gray-50 p-3 md:p-4 rounded-xl md:rounded-2xl border border-gray-100 text-center">
                    <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Gender
                    </p>
                    <p id="siswaProfileJk" class="text-xs md:text-sm font-bold text-gray-700">-</p>
                </div>
                <div class="bg-gray-50 p-3 md:p-4 rounded-xl md:rounded-2xl border border-gray-100 text-center">
                    <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Wali
                        Murid / Ortu</p>
                    <p id="siswaProfileOrtu" class="text-xs md:text-sm font-bold text-gray-700">-</p>
                </div>
            </div>

            <div class="bg-gray-50 p-3 md:p-4 rounded-xl md:rounded-2xl border border-gray-100 text-center mb-4">
                <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Alamat
                    Tinggal</p>
                <p id="siswaProfileAlamat" class="text-xs font-bold text-gray-700">-</p>
            </div>

            <div class="mt-6 md:mt-8 flex justify-center">
                <button onclick="closeSiswaProfileModal()"
                    class="w-full py-3 md:py-3.5 bg-[#10b981] text-white shadow-lg shadow-green-100 font-bold text-[10px] md:text-xs uppercase tracking-wider rounded-xl hover:bg-green-600 transition">
                    Tutup Detail
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL FOTO BUKTI PELANGGARAN -->
    <div id="fotoBuktiModal"
        class="fixed inset-0 bg-gray-900/90 hidden z-[70] flex items-center justify-center backdrop-blur-sm transition-opacity opacity-0 duration-200 p-4">
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl relative max-w-3xl w-full transform scale-95 transition-transform duration-200"
            id="fotoBuktiContent">

            <!-- Header Modal -->
            <div class="px-4 md:px-6 py-3 md:py-4 bg-gray-900 text-white flex justify-between items-center">
                <h3 class="font-bold text-xs md:text-sm uppercase tracking-wider"><i class="fas fa-image mr-2"></i>
                    Bukti Pelanggaran</h3>
                <button onclick="closeFotoBuktiModal()"
                    class="text-gray-400 hover:text-white transition focus:outline-none p-1">
                    <i class="fas fa-times text-lg md:text-xl"></i>
                </button>
            </div>

            <!-- Tempat Foto -->
            <div class="p-2 bg-gray-100 flex justify-center items-center relative"
                style="min-height: 250px; max-height: 70vh;">
                <div id="fotoBuktiLoading"
                    class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 z-0">
                    <i class="fas fa-spinner fa-spin text-2xl md:text-3xl mb-2"></i>
                    <span class="text-[10px] md:text-xs font-bold">Memuat gambar...</span>
                </div>

                <img id="fotoBuktiImage" src="" alt="Bukti Pelanggaran"
                    class="max-w-full max-h-[60vh] md:max-h-[70vh] object-contain rounded-lg shadow-sm relative z-10 hidden"
                    onload="document.getElementById('fotoBuktiLoading').classList.add('hidden'); this.classList.remove('hidden');">
            </div>

            <!-- Footer Bantuan -->
            <div
                class="px-4 md:px-6 py-3 bg-gray-50 border-t border-gray-200 text-center flex justify-center items-center gap-4">
                <a id="downloadFotoBtn" href="#" download
                    class="text-[10px] md:text-xs font-bold text-gray-600 hover:text-blue-600 transition flex items-center gap-1.5 p-2">
                    <i class="fas fa-download"></i> Unduh
                </a>
                <span class="text-gray-300">|</span>
                <button onclick="closeFotoBuktiModal()"
                    class="text-[10px] md:text-xs font-bold text-gray-600 hover:text-gray-900 transition flex items-center gap-1.5 p-2">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI TARIK LAPORAN KHUSUS GURU/BK -->
    <div id="modalKonfirmasiTarik"
        class="fixed inset-0 bg-gray-900/90 hidden z-[80] flex items-center justify-center backdrop-blur-sm transition-opacity opacity-0 duration-200 p-4">
        <div class="bg-white rounded-[30px] p-6 md:p-8 max-w-sm w-full shadow-2xl text-center transform scale-95 transition-transform duration-200"
            id="contentKonfirmasiTarik">
            <div
                class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-5 shadow-inner">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-xl font-black text-gray-800 uppercase tracking-wider mb-2">Tarik Laporan?</h3>
            <p class="text-sm text-gray-600 mb-8 font-medium">Apakah Bapak/Ibu yakin ingin menarik kembali laporan ini?
                <br><br> <span class="text-red-500 font-bold">File PDF laporan ini akan dihapus permanen dari sistem
                    madrasah.</span>
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button onclick="closeKonfirmasiTarik()"
                    class="w-full sm:w-auto px-6 py-3.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-gray-200 transition shadow-sm">
                    Batal
                </button>
                <button onclick="submitTarikLaporan()" id="btnSubmitTarik"
                    class="w-full sm:w-auto px-6 py-3.5 bg-red-500 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-lg shadow-red-200 hover:bg-red-600 transition">
                    Ya, Tarik Laporan
                </button>
            </div>
        </div>
    </div>

    <!-- KONFIGURASI JAVASCRIPT GLOBAL -->
    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const allSiswaData = @json($dataSiswa ?? []);
        const allPelanggaranData = @json($dataPelanggaran ?? []);
    </script>

    <!-- SCRIPT JAVASCRIPT -->
    <script>
        // ==========================================
        // LOGIKA CETAK LAPORAN (TANPA PINDAH HALAMAN)
        // ==========================================
        function cetakLaporanOtomatis(tipe) {
            showAlert('success', 'Menyiapkan dokumen, jendela cetak akan segera muncul...');

            let printFrame = document.getElementById('iframeCetak');
            if (!printFrame) {
                printFrame = document.createElement('iframe');
                printFrame.id = 'iframeCetak';
                printFrame.style.display = 'none'; // Sembunyikan iframe
                document.body.appendChild(printFrame);
            }

            // Panggil URL cetak beserta parameternya ke dalam iframe
            printFrame.src = `/guru/cetak-laporan?type=${tipe}`;
        }

        // Preview Gambar Bukti Foto
        function previewImage(event) {
            const previewContainer = document.getElementById('imagePreviewContainer');
            const previewImage = document.getElementById('imagePreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                previewImage.src = "#";
                previewContainer.classList.add('hidden');
            }
        }

        // ==========================================
        // LOGIKA MOBILE SIDEBAR TOGGLE
        // ==========================================
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            sidebar.classList.toggle('-translate-x-full');

            if (sidebar.classList.contains('-translate-x-full')) {
                backdrop.classList.add('opacity-0');
                setTimeout(() => backdrop.classList.add('hidden'), 300);
            } else {
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
            }
        }

        // ==========================================
        // LOGIKA NAVIGASI VIEW TUNGGAL
        // ==========================================
        function showView(viewId) {
            document.querySelectorAll('.view-section').forEach(view => view.classList.remove('active'));
            document.getElementById('view-' + viewId).classList.add('active');

            const titleEl = document.getElementById('view-title');
            const breadcrumbEl = document.getElementById('breadcrumb-active');

            document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));

            if (viewId === 'dashboard') {
                titleEl.innerText = "Selamat Datang!";
                breadcrumbEl.innerText = "Dashboard";
                document.getElementById('nav-dashboard').classList.add('active');
            } else if (viewId === 'poin') {
                titleEl.innerText = "Input Pelanggaran";
                breadcrumbEl.innerText = "Input Poin";
                document.getElementById('nav-poin').classList.add('active');
                setTimeout(() => document.getElementById('p_nama')?.focus(), 100);
            } else if (viewId === 'data-siswa') {
                titleEl.innerText = "Data Master Siswa";
                breadcrumbEl.innerText = "Data Siswa";
                document.getElementById('nav-data-siswa').classList.add('active');
            } else if (viewId === 'data-kelas') {
                titleEl.innerText = "Data Kelas Binaan";
                breadcrumbEl.innerText = "Data Kelas";
                document.getElementById('nav-data-kelas')?.classList.add('active');
            } else if (viewId === 'konsultasi') {
                titleEl.innerText = "Konsultasi Wali Murid";
                breadcrumbEl.innerText = "Konsultasi";
                document.getElementById('nav-konsultasi')?.classList.add('active');
            } else if (viewId === 'laporan') {
                titleEl.innerText = "Kirim Laporan";
                breadcrumbEl.innerText = "Laporan";
                document.getElementById('nav-laporan')?.classList.add('active');
            }

            // Tutup sidebar otomatis di mobile setelah pindah halaman
            if (window.innerWidth < 768) {
                const sidebar = document.getElementById('sidebar');
                if (!sidebar.classList.contains('-translate-x-full')) {
                    toggleSidebar();
                }
            }
        }

        // ==========================================
        // ALERTS & RESET FORM
        // ==========================================
        function showAlert(type, message) {
            const alertBox = document.getElementById('liveAlert');
            if (!alertBox) {
                alert(message);
                return;
            }

            alertBox.classList.remove('hidden', 'bg-green-100', 'text-green-700', 'bg-red-100', 'text-red-700');

            if (type === 'success') {
                alertBox.classList.add('bg-green-100', 'text-green-700');
                alertBox.innerHTML =
                    `<i class="fas fa-check-circle mt-1"></i>
                     <span class="block sm:inline font-bold">${message}</span>
                     <button onclick="document.getElementById('liveAlert').classList.add('hidden')" class="absolute top-0 bottom-0 right-0 px-4 py-3"><i class="fas fa-times"></i></button>`;
            } else {
                alertBox.classList.add('bg-red-100', 'text-red-700');
                alertBox.innerHTML =
                    `<i class="fas fa-exclamation-triangle mt-1"></i>
                     <span class="block sm:inline font-bold">${message}</span>
                     <button onclick="document.getElementById('liveAlert').classList.add('hidden')" class="absolute top-0 bottom-0 right-0 px-4 py-3"><i class="fas fa-times"></i></button>`;
            }

            // Scroll ke atas agar notifikasi terlihat
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            const poinView = document.getElementById('view-poin');
            if (poinView && !poinView.classList.contains('active')) poinView.scrollIntoView({
                behavior: 'smooth'
            });
        }

        function resetForm() {
            const form = document.getElementById('poinForm');
            if (form) form.reset();
            document.getElementById('p_nisn').value = '';
            document.getElementById('p_keterangan_pelanggaran').value = '';
            document.getElementById('p_jumlah_poin').value = '';
            document.getElementById('p_kelas_display').value = '';

            // Reset preview foto
            document.getElementById('imagePreview').src = "#";
            document.getElementById('imagePreviewContainer').classList.add('hidden');
        }

        // ==========================================
        // FITUR PENCARIAN & FILTER REAL-TIME 
        // ==========================================
        window.filterDashboard = function() {
            const searchName = document.getElementById('filter-nama')?.value.toLowerCase().trim() || '';
            const filterKelas = document.getElementById('filter-kelas-dashboard')?.value || '';
            const rows = document.querySelectorAll('.row-dashboard-siswa');
            rows.forEach(row => {
                const nama = row.querySelector('.nama-col').innerText.toLowerCase();
                const nisn = row.querySelector('.nisn-col').innerText.toLowerCase();
                const rowKelas = row.getAttribute('data-kelas');
                const matchName = nama.includes(searchName) || nisn.includes(searchName);
                const matchKelas = filterKelas === '' || rowKelas === filterKelas;
                if (matchName && matchKelas) row.style.display = '';
                else row.style.display = 'none';
            });
        };

        // Filter untuk Data Master Siswa
        window.filterSiswa = function() {
            const searchTerm = document.getElementById('search-siswa')?.value.toLowerCase().trim() || '';
            const tingkat = document.getElementById('filter-tingkat-siswa')?.value || '';
            const kelas = document.getElementById('filter-kelas-siswa')?.value || '';

            const rows = document.querySelectorAll('.row-data-siswa');
            rows.forEach(row => {
                const nama = row.querySelector('.row-nama').innerText.toLowerCase();
                const nisn = row.querySelector('.row-nisn').innerText.toLowerCase();
                const rowKelas = row.getAttribute('data-kelas'); // Cth: "VII A" atau "VII-A"

                // Pisahkan string kelas menjadi array untuk pengecekan akurat
                const kelasArr = rowKelas.replace('-', ' ').split(' ');
                const rowTingkat = kelasArr[0] || '';
                const rowSuffix = kelasArr[1] || '';

                const matchName = nama.includes(searchTerm) || nisn.includes(searchTerm);
                const matchTingkat = tingkat === '' || rowTingkat === tingkat;
                const matchKelas = kelas === '' || rowSuffix === kelas;

                if (matchName && matchTingkat && matchKelas) row.style.display = '';
                else row.style.display = 'none';
            });
        };

        // Filter khusus Data Siswa Binaan
        window.filterSiswaBinaan = function() {
            const searchTerm = document.getElementById('search-siswa-binaan')?.value.toLowerCase().trim() || '';
            const tingkat = document.getElementById('filter-tingkat-binaan')?.value || '';
            const kelas = document.getElementById('filter-kelas-binaan')?.value || '';

            const rows = document.querySelectorAll('.row-data-binaan');
            rows.forEach(row => {
                const nama = row.querySelector('.row-nama').innerText.toLowerCase();
                const nisn = row.querySelector('.row-nisn').innerText.toLowerCase();
                const rowKelas = row.getAttribute('data-kelas');

                // Pisahkan string kelas menjadi array untuk pengecekan akurat
                const kelasArr = rowKelas.replace('-', ' ').split(' ');
                const rowTingkat = kelasArr[0] || '';
                const rowSuffix = kelasArr[1] || '';

                const matchName = nama.includes(searchTerm) || nisn.includes(searchTerm);
                const matchTingkat = tingkat === '' || rowTingkat === tingkat;
                const matchKelas = kelas === '' || rowSuffix === kelas;

                if (matchName && matchTingkat && matchKelas) row.style.display = '';
                else row.style.display = 'none';
            });
        };

        // ==========================================
        // MODAL PROFIL SISWA 
        // ==========================================
        window.viewSiswaModal = function(nisn) {
            const siswa = allSiswaData.find(s => s.nisn == nisn);
            if (siswa) {
                const picContainer = document.getElementById('siswaProfilePic');
                if (picContainer) {
                    let fotoUrl = '';
                    // Cek apakah siswa punya foto dan formatnya
                    if (siswa.photo) {
                        fotoUrl = siswa.photo.startsWith('http') ? siswa.photo : '/storage/' + siswa.photo;
                    } else {
                        // Jika tidak ada foto, gunakan inisial nama
                        fotoUrl = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(siswa.nama || 'S') +
                            '&background=10b981&color=fff&size=150';
                    }

                    // Masukkan ke dalam HTML dengan fitur "onerror" (jika gambar gagal dimuat, kembali ke inisial nama)
                    picContainer.innerHTML =
                        `<img src="${fotoUrl}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(siswa.nama || 'S')}&background=10b981&color=fff&size=150'">`;
                }

                document.getElementById('siswaProfileNama').textContent = siswa.nama || '-';
                document.getElementById('siswaProfileNisn').textContent = siswa.nisn || '-';
                document.getElementById('siswaProfileJk').textContent = siswa.jk || '-';
                document.getElementById('siswaProfileKelas').textContent = siswa.kelas || '-';
                document.getElementById('siswaProfilePoin').textContent = siswa.poin || 0;

                document.getElementById('siswaProfileOrtu').textContent = (siswa.ortu && siswa.ortu.name) ? siswa.ortu
                    .name : 'Belum Ditautkan';
                document.getElementById('siswaProfileAlamat').textContent = siswa.alamat || 'Belum diisi';

                const modal = document.getElementById('siswaProfileModal');
                const content = document.getElementById('siswaProfileContent');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modal.classList.add('opacity-100');
                    content.classList.remove('scale-95');
                    content.classList.add('scale-100');
                }, 10);
            }
        };

        window.closeSiswaProfileModal = function() {
            const modal = document.getElementById('siswaProfileModal');
            const content = document.getElementById('siswaProfileContent');
            if (modal && content) {
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 200);
            }
        }

        // ==========================================
        // MODAL FOTO BUKTI
        // ==========================================
        window.openFotoBuktiModal = function(url) {
            const modal = document.getElementById('fotoBuktiModal');
            const content = document.getElementById('fotoBuktiContent');
            const img = document.getElementById('fotoBuktiImage');
            const loading = document.getElementById('fotoBuktiLoading');
            const downloadBtn = document.getElementById('downloadFotoBtn');

            // Tampilkan loading, sembunyikan gambar lama
            img.classList.add('hidden');
            loading.classList.remove('hidden');

            // Set source gambar
            img.src = url;
            downloadBtn.href = url;

            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Animasi masuk
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        };

        window.closeFotoBuktiModal = function() {
            const modal = document.getElementById('fotoBuktiModal');
            const content = document.getElementById('fotoBuktiContent');
            const img = document.getElementById('fotoBuktiImage');

            if (modal && content) {
                // Animasi keluar
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    img.src = ""; // Bersihkan src
                }, 200);
            }
        };

        // ==========================================
        // LOGIKA MODAL KONFIRMASI TARIK LAPORAN
        // ==========================================
        let formLaporanYangAkanDitarik = null;

        window.openKonfirmasiTarik = function(button) {
            // Menyimpan elemen <form> ke dalam memori
            formLaporanYangAkanDitarik = button.closest('form');

            const modal = document.getElementById('modalKonfirmasiTarik');
            const content = document.getElementById('contentKonfirmasiTarik');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        };

        window.closeKonfirmasiTarik = function() {
            const modal = document.getElementById('modalKonfirmasiTarik');
            const content = document.getElementById('contentKonfirmasiTarik');

            if (modal && content) {
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    formLaporanYangAkanDitarik = null; // Bersihkan memori form
                }, 200);
            }
        };

        window.submitTarikLaporan = function() {
            if (formLaporanYangAkanDitarik) {
                // Tampilkan status "Menarik..." dan bekukan tombol agar tidak ditekan 2 kali
                const btn = document.getElementById('btnSubmitTarik');
                if (btn) {
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menarik...';
                    btn.classList.remove('bg-red-500', 'hover:bg-red-600');
                    btn.classList.add('bg-red-400', 'cursor-not-allowed');
                    btn.disabled = true;
                }

                // Eksekusi pengiriman data (submit form hapus) ke Controller
                formLaporanYangAkanDitarik.submit();
            }
        };

        // Tutup modal jika area luar (backdrop) diklik
        document.addEventListener('click', function(event) {
            const modalFoto = document.getElementById('fotoBuktiModal');
            const modalSiswa = document.getElementById('siswaProfileModal');
            const modalTarik = document.getElementById('modalKonfirmasiTarik');

            if (event.target === modalFoto) {
                closeFotoBuktiModal();
            } else if (event.target === modalSiswa) {
                closeSiswaProfileModal();
            } else if (event.target === modalTarik) {
                closeKonfirmasiTarik();
            }
        });

        // ==========================================
        // AUTOCOMPLETE INPUT POIN 
        // ==========================================
        function setupSiswaAutocomplete() {
            const newSearchEl = document.getElementById('p_nama');
            const siswaList = document.getElementById('nama-list');
            if (!newSearchEl || !siswaList) return;

            newSearchEl.addEventListener('input', function() {
                let query = this.value.toLowerCase().trim();
                siswaList.innerHTML = '';
                if (query.length > 0) {
                    const filtered = allSiswaData.filter(s => s.nama.toLowerCase().includes(query) || s.nisn
                        .includes(query)).slice(0, 10);
                    if (filtered.length > 0) {
                        siswaList.classList.remove('hidden');
                        filtered.forEach(siswa => {
                            let div = document.createElement('div');
                            div.className =
                                'px-4 py-3 hover:bg-green-50 cursor-pointer text-xs md:text-sm border-b border-gray-50 flex flex-col transition';
                            div.innerHTML =
                                `<span class="font-bold text-gray-700">${siswa.nama}</span> <span class="text-[9px] md:text-[10px] text-gray-400">NISN: ${siswa.nisn} | Kelas: ${siswa.kelas}</span>`;
                            div.onclick = function() {
                                document.getElementById('p_nama').value = siswa.nama;
                                document.getElementById('p_nisn').value = siswa.nisn;
                                document.getElementById('p_kelas_display').value = siswa.kelas;
                                siswaList.classList.add('hidden');
                            };
                            siswaList.appendChild(div);
                        });
                    } else {
                        siswaList.innerHTML =
                            '<div class="px-4 py-3 text-xs md:text-sm text-gray-400 italic">Siswa tidak ditemukan</div>';
                        siswaList.classList.remove('hidden');
                    }
                } else {
                    siswaList.classList.add('hidden');
                }
            });

            document.addEventListener('click', function(e) {
                if (!newSearchEl.contains(e.target) && !siswaList.contains(e.target)) {
                    siswaList.classList.add('hidden');
                }
            });
        }

        function setupPelanggaranAutocomplete() {
            const searchPelanggaran = document.getElementById('p_search_pelanggaran');
            const pelanggaranList = document.getElementById('pelanggaran-list');
            if (!searchPelanggaran || !pelanggaranList) return;

            function renderPelanggaran(data) {
                pelanggaranList.innerHTML = '';
                if (data.length > 0) {
                    pelanggaranList.classList.remove('hidden');
                    data.forEach(item => {
                        let div = document.createElement('div');
                        div.className =
                            'px-4 py-3 hover:bg-orange-50 cursor-pointer border-b border-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center transition gap-2 sm:gap-4';
                        const jenis = item.jenis || item.ket || 'Pelanggaran';
                        const poin = item.skor_poin || item.poin || 0;
                        const sanksi = item.sanksi || 'Teguran';
                        div.innerHTML = `
                            <div class="flex flex-col">
                                <span class="text-xs md:text-sm font-bold text-gray-700">${jenis}</span>
                                <span class="text-[9px] md:text-[10px] text-gray-500 mt-1"><i class="fas fa-gavel text-red-400 mr-1"></i> Sanksi: ${sanksi}</span>
                            </div>
                            <span class="text-[10px] md:text-xs font-black bg-red-100 text-red-600 px-2.5 md:px-3 py-1 md:py-1.5 rounded-lg shrink-0 border border-red-200">+${poin} Poin</span>
                        `;
                        div.onclick = function() {
                            searchPelanggaran.value = jenis;
                            document.getElementById('p_jumlah_display').value = poin;
                            document.getElementById('p_keterangan_pelanggaran').value = jenis;
                            document.getElementById('p_jumlah_poin').value = poin;
                            pelanggaranList.classList.add('hidden');
                        };
                        pelanggaranList.appendChild(div);
                    });
                } else {
                    pelanggaranList.classList.add('hidden');
                }
            }

            searchPelanggaran.addEventListener('focus', function() {
                if (this.value === '') renderPelanggaran(allPelanggaranData);
            });

            searchPelanggaran.addEventListener('input', function() {
                let query = this.value.toLowerCase().trim();
                document.getElementById('p_keterangan_pelanggaran').value = this.value;
                if (query.length > 0) {
                    let filtered = allPelanggaranData.filter(item => {
                        let ket = item.jenis || item.ket || '';
                        return ket.toLowerCase().includes(query);
                    });
                    renderPelanggaran(filtered);
                } else {
                    pelanggaranList.classList.add('hidden');
                    document.getElementById('p_jumlah_display').value = '';
                    document.getElementById('p_jumlah_poin').value = '';
                }
            });

            document.addEventListener('click', function(e) {
                if (!searchPelanggaran.contains(e.target) && !pelanggaranList.contains(e.target)) {
                    pelanggaranList.classList.add('hidden');
                }
            });
        }

        // ==========================================
        // FETCH RIWAYAT POIN & SIMPAN DATA
        // ==========================================
        async function loadRiwayatPoin() {
            try {
                const response = await fetch('/admin/poin/riwayat-data', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();

                if (response.ok && data.success) {
                    const tbody = document.getElementById('riwayatTableBody');
                    tbody.innerHTML = '';
                    if (data.data.length > 0) {
                        data.data.forEach(row => {
                            let tr = document.createElement('tr');
                            tr.className = 'hover:bg-gray-50 transition';
                            let dateFormatted = new Date(row.waktu).toLocaleString('id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            let fotoPreview = row.foto_bukti ?
                                `<button type="button" onclick="openFotoBuktiModal('/storage/${row.foto_bukti}')" class="inline-flex bg-green-50 text-green-600 px-2 md:px-3 py-1.5 rounded-lg text-[9px] md:text-[10px] font-bold hover:bg-green-100 transition items-center gap-1 border border-green-100 shadow-sm cursor-pointer whitespace-nowrap"><i class="fas fa-search-plus"></i> Lihat Foto</button>` :
                                '<span class="text-[9px] md:text-[10px] text-gray-400 italic font-medium bg-gray-50 px-2 md:px-3 py-1 rounded-lg border border-gray-100">Tanpa Bukti</span>';

                            let badgeClass = row.jenis === 'Tambah' ?
                                'bg-red-50 text-red-600 border border-red-200' :
                                'bg-green-50 text-green-600 border border-green-200';
                            let prefixSign = row.jenis === 'Tambah' ? '+' : '-';

                            tr.innerHTML = `
                                <td class="p-3 md:p-4 pl-4 md:pl-6 italic text-gray-400 font-medium whitespace-nowrap text-[10px] md:text-xs">${dateFormatted}</td>
                                <td class="p-3 md:p-4">
                                    <p class="font-black text-gray-700 leading-tight text-xs md:text-sm">${row.nama}</p>
                                    <p class="text-[9px] md:text-[10px] text-gray-400 uppercase mt-0.5">${row.nisn}</p>
                                </td>
                                <td class="p-3 md:p-4 text-center font-bold text-gray-500 text-xs md:text-sm">${row.kelas}</td>
                                <td class="p-3 md:p-4 font-bold text-gray-600 max-w-[150px] md:max-w-xs text-xs md:text-sm line-clamp-2 md:line-clamp-none">${row.ket}</td>
                                <td class="p-3 md:p-4 text-center">${fotoPreview}</td>
                                <td class="p-3 md:p-4 text-center pr-4 md:pr-6">
                                    <span class="${badgeClass} px-2 md:px-3 py-1 md:py-1.5 rounded-lg font-black text-[10px] md:text-xs whitespace-nowrap">${prefixSign}${row.jumlah}</span>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        tbody.innerHTML =
                            '<tr><td colspan="6" class="p-6 md:p-10 text-center text-gray-400 font-bold text-xs md:text-sm">Belum ada riwayat pelanggaran.</td></tr>';
                    }
                }
            } catch (error) {
                console.error('Error loading riwayat poin:', error);
            }
        }

        window.submitPoinForm = async function(e) {
            e.preventDefault();
            const nisn = document.getElementById('p_nisn')?.value.trim();
            const jumlahInput = document.getElementById('p_jumlah_poin')?.value.trim();
            const ketInput = document.getElementById('p_keterangan_pelanggaran')?.value.trim();
            const fileInput = document.getElementById('p_foto_bukti');

            if (!nisn || !jumlahInput || !ketInput) {
                showAlert('error',
                    'Data belum lengkap! Pastikan Anda telah mencari dan memilih nama Siswa serta Jenis Pelanggaran dari saran yang muncul.'
                );
                return;
            }

            try {
                const submitBtn = e.target.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                submitBtn.disabled = true;

                const formData = new FormData();
                formData.append('nisn', nisn);
                formData.append('jumlah', jumlahInput);
                formData.append('ket', ketInput);
                formData.append('_token', CSRF_TOKEN);

                if (fileInput && fileInput.files[0]) {
                    formData.append('foto_bukti', fileInput.files[0]);
                }

                const response = await fetch('/admin/poin/add', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                if (response.ok) {
                    showAlert('success', 'Poin pelanggaran berhasil ditambahkan!');
                    resetForm();
                    loadRiwayatPoin();
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showAlert('error', data.message || 'Gagal menyimpan poin. Cek kembali isian form.');
                }
            } catch (error) {
                showAlert('error', 'Terjadi kesalahan koneksi server saat memproses data.');
                const submitBtn = e.target.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Poin';
                    submitBtn.disabled = false;
                }
            }
        };

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            const profileDropdown = document.getElementById('profileDropdownMenu');
            const profileButton = document.getElementById('profileDropdownBtn');
            if (profileButton && profileDropdown) {
                profileButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = profileDropdown.classList.contains('hidden');
                    if (isHidden) {
                        profileDropdown.classList.remove('hidden');
                        setTimeout(() => {
                            profileDropdown.classList.remove('opacity-0', 'scale-95');
                            profileDropdown.classList.add('opacity-100', 'scale-100');
                        }, 10);
                    } else {
                        profileDropdown.classList.remove('opacity-100', 'scale-100');
                        profileDropdown.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => profileDropdown.classList.add('hidden'), 200);
                    }
                });
            }
            document.addEventListener('click', function(e) {
                if (profileDropdown && !profileDropdown.contains(e.target) && profileButton && !
                    profileButton.contains(e.target)) {
                    profileDropdown.classList.remove('opacity-100', 'scale-100');
                    profileDropdown.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => profileDropdown.classList.add('hidden'), 200);
                }
            });

            const urlParams = new URLSearchParams(window.location.search);
            const targetView = urlParams.get('view');
            if (targetView) {
                showView(targetView);
            }

            setupSiswaAutocomplete();
            setupPelanggaranAutocomplete();
            loadRiwayatPoin();

            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape") {
                    const fotoModal = document.getElementById('fotoBuktiModal');
                    const siswaModal = document.getElementById('siswaProfileModal');
                    const tarikModal = document.getElementById('modalKonfirmasiTarik');

                    if (fotoModal && !fotoModal.classList.contains('hidden')) {
                        closeFotoBuktiModal();
                    } else if (siswaModal && !siswaModal.classList.contains('hidden')) {
                        closeSiswaProfileModal();
                    } else if (tarikModal && !tarikModal.classList.contains('hidden')) {
                        closeKonfirmasiTarik();
                    }
                }
            });
        });
    </script>
</body>

</html>
