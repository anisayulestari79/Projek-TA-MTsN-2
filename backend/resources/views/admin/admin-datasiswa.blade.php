<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Siswa - Admin panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
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

<body class="bg-[#f4f7f6] font-sans flex text-gray-800 h-screen overflow-hidden relative">

    <!-- Overlay backdrop for mobile sidebar -->
    <div id="mobile-sidebar-backdrop"
        class="fixed inset-0 bg-gray-900/50 z-[60] hidden md:hidden backdrop-blur-sm transition-opacity opacity-0 duration-300"
        onclick="toggleSidebar()"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="w-72 bg-[#10b981] text-white flex flex-col shadow-xl z-[70] shrink-0 h-full fixed md:relative transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="p-8 relative">
            <div class="flex items-center gap-3 mb-2">
                <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                    class="w-10 drop-shadow-md" alt="Logo Kemenag">
                <h1 class="font-bold text-2xl leading-tight tracking-tight uppercase">Sistem<br>Pelanggaran<br>Poin
                    Siswa
                </h1>
            </div>
            <p class="text-[10px] opacity-80 font-bold tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
        </div>

        <nav class="mt-2 flex-grow pl-6 overflow-y-auto pr-2 space-y-1 pb-10">
            <a href="{{ route('admin.dashboard') ?? '#' }}"
                class="sidebar-item flex items-center px-6 py-4 transition hover:bg-white/10 rounded-l-xl">
                <i class="fas fa-th-large mr-4 text-sm opacity-80"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.guru.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-chalkboard-teacher mr-4 text-sm opacity-80"></i> <span class="font-medium">Data
                    Guru</span>
            </a>
            <a href="#" onclick="showView('data-siswa')" id="nav-data-siswa"
                class="sidebar-item active flex items-center px-6 py-4 transition">
                <i class="fas fa-users mr-4 text-sm opacity-80"></i> <span>Data Siswa</span>
            </a>
            <a href="{{ route('admin.konsultasi.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-comments mr-4 text-sm opacity-80"></i> <span class="font-medium">Konsultasi BK</span>
            </a>
            <a href="{{ route('admin.poin.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-star mr-4 text-sm opacity-80"></i> <span class="font-medium">Poin Siswa</span>
            </a>
            <a href="{{ route('admin.tahunajaran.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-calendar-alt mr-4 text-sm"></i> <span class="font-medium">Tahun Ajaran</span>
            </a>
            <a href="{{ route('admin.audit.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-history mr-4 text-sm opacity-80"></i> <span class="font-medium">Audit Log</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6 md:p-10 overflow-y-auto h-full relative w-full">

        <!-- HEADER -->
        <header class="flex justify-between items-start md:items-center mb-6 md:mb-8 gap-4">
            <div class="flex items-center gap-3 md:gap-0">
                <!-- Hamburger Button (Mobile Only) -->
                <button onclick="toggleSidebar()"
                    class="md:hidden text-gray-500 hover:text-[#10b981] transition focus:outline-none bg-white w-10 h-10 flex items-center justify-center rounded-xl shadow-sm border border-gray-100 shrink-0">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div>
                    <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-0.5 md:mb-1">
                        Home / <span id="breadcrumb-active">Data Siswa</span>
                    </nav>
                    <h2 id="view-title"
                        class="text-xl md:text-3xl font-black text-gray-700 uppercase tracking-tighter italic leading-tight line-clamp-1">
                        DATA MASTER SISWA
                    </h2>
                </div>
            </div>

            <!-- Profile Badge -->
            <div class="relative flex items-center gap-3 bg-white px-4 md:px-6 py-2 rounded-full shadow-sm border border-gray-100 cursor-pointer"
                id="profileDropdownBtn">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-black text-[#10b981] uppercase leading-none">{{ $user['name'] ?? 'Admin' }}
                    </p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Status: Administrator</p>
                </div>
                <!-- PERBAIKAN GAMBAR PROFIL -->
                @php
                    $avatarUrl =
                        'https://ui-avatars.com/api/?name=' .
                        urlencode($user['name'] ?? 'Admin') .
                        '&background=10b981&color=fff';
                    $photoPath =
                        isset($user['photo']) && $user['photo']
                            ? (str_starts_with($user['photo'], 'http')
                                ? $user['photo']
                                : asset('storage/' . $user['photo']))
                            : $avatarUrl;
                @endphp

                <img src="{{ $photoPath }}" onerror="this.src='{{ $avatarUrl }}'"
                    class="w-10 h-10 rounded-full border-2 border-green-50 object-cover shadow-sm" alt="Profile">
                <i class="fas fa-chevron-down text-gray-400 text-xs ml-1"></i>

                <!-- Dropdown Menu -->
                <div id="profileDropdownMenu"
                    class="absolute right-0 top-full mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden z-50 transform origin-top-right transition-all duration-200 opacity-0 scale-95">
                    <div class="py-2">
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
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-2xl relative mb-6 shadow-sm flex items-start gap-2"
                id="alert-success">
                <i class="fas fa-check-circle mt-1"></i>
                <span class="block sm:inline font-bold text-sm">{{ session('success') }}</span>
                <button onclick="document.getElementById('alert-success').style.display='none'"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3"><i class="fas fa-times"></i></button>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl relative mb-6 shadow-sm flex items-start gap-2"
                id="alert-error">
                <i class="fas fa-exclamation-triangle mt-1"></i>
                <span class="block sm:inline font-bold text-sm">{{ session('error') }}</span>
                <button onclick="document.getElementById('alert-error').style.display='none'"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3"><i class="fas fa-times"></i></button>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- VIEW: DATA SISWA -->
        <!-- ============================================== -->
        <div id="view-data-siswa" class="view-section active">
            <div class="bg-white p-6 md:p-8 rounded-[30px] shadow-sm border border-gray-50">

                <div
                    class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 mb-8 border-b border-gray-100 pb-6">
                    <div class="w-full xl:w-auto">
                        <h3 class="font-black text-gray-800 text-lg uppercase tracking-widest">Daftar Siswa</h3>
                        <p class="text-xs text-gray-400 mt-1 font-medium">Manajemen data siswa, status, dan poin
                            kedisiplinan</p>
                    </div>

                    <div class="flex flex-col sm:flex-row flex-wrap gap-2 w-full xl:w-auto">
                        <button type="button" onclick="openTutupTahunModal()"
                            class="w-full sm:w-auto justify-center bg-purple-600 text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-purple-700 transition flex items-center gap-2 shadow-sm shadow-purple-200">
                            <i class="fas fa-level-up-alt"></i> Tutup Tahun
                        </button>
                        <button onclick="openImportModal()"
                            class="w-full sm:w-auto justify-center bg-gray-800 text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-900 transition flex items-center gap-2 shadow-sm shadow-gray-200">
                            <i class="fas fa-file-excel text-green-400"></i> Import
                        </button>
                        <button onclick="openAddSiswaModal()"
                            class="w-full sm:w-auto justify-center bg-[#10b981] text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-green-600 transition flex items-center gap-2 shadow-sm shadow-green-200">
                            <i class="fas fa-user-plus"></i> Tambah Siswa
                        </button>
                    </div>
                </div>

                @php
                    $tingkatMaster = [];
                    $suffixMaster = [];

                    if (isset($dataSiswa)) {
                        foreach ($dataSiswa as $siswa) {
                            $parts = explode(' ', str_replace('-', ' ', $siswa->kelas));
                            if (isset($parts[0]) && !in_array($parts[0], $tingkatMaster)) {
                                $tingkatMaster[] = $parts[0];
                            }
                            if (isset($parts[1]) && !in_array($parts[1], $suffixMaster)) {
                                $suffixMaster[] = $parts[1];
                            }
                        }
                    }

                    sort($suffixMaster);

                    $groupedSiswa = collect([]);
                    if (isset($dataSiswa)) {
                        $groupedSiswa = collect($dataSiswa->items())
                            ->sortBy(function ($siswa) {
                                $parts = explode(' ', str_replace('-', ' ', $siswa->kelas));
                                $tingkat = $parts[0] ?? '';
                                $suffix = $parts[1] ?? '';

                                $tingkatOrder = ['VII' => 1, 'VIII' => 2, 'IX' => 3];
                                $order = $tingkatOrder[strtoupper($tingkat)] ?? 99;

                                return $order . '-' . $suffix;
                            })
                            ->groupBy('kelas');
                    }
                @endphp

                <div class="flex gap-6 border-b border-gray-100 mb-6 overflow-x-auto whitespace-nowrap">
                    <a href="{{ route('admin.siswa.index', array_merge(request()->query(), ['status' => 'Aktif'])) }}"
                        class="pb-3 px-2 text-[11px] uppercase tracking-wider font-black transition-all {{ request('status', 'Aktif') == 'Aktif' ? 'text-[#10b981] border-b-2 border-[#10b981]' : 'text-gray-400 hover:text-gray-600' }}">
                        <i class="fas fa-user-check mr-1"></i> Siswa Aktif
                    </a>
                    <a href="{{ route('admin.siswa.index', array_merge(request()->query(), ['status' => 'Lulus'])) }}"
                        class="pb-3 px-2 text-[11px] uppercase tracking-wider font-black transition-all {{ request('status') == 'Lulus' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
                        <i class="fas fa-user-graduate mr-1"></i> Alumni (Lulus)
                    </a>
                    <a href="{{ route('admin.siswa.index', array_merge(request()->query(), ['status' => 'Dikeluarkan'])) }}"
                        class="pb-3 px-2 text-[11px] uppercase tracking-wider font-black transition-all {{ request('status') == 'Dikeluarkan' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-400 hover:text-gray-600' }}">
                        <i class="fas fa-user-times mr-1"></i> Dikeluarkan
                    </a>
                    <a href="{{ route('admin.siswa.index', array_merge(request()->query(), ['status' => 'all'])) }}"
                        class="pb-3 px-2 text-[11px] uppercase tracking-wider font-black transition-all {{ request('status') == 'all' ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-400 hover:text-gray-600' }}">
                        <i class="fas fa-users mr-1"></i> Semua Data
                    </a>
                </div>

                <div class="flex flex-col lg:flex-row gap-3 mb-6 items-end">
                    <div class="flex-1 w-full relative">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mb-1.5 block">Cari
                            Nama/NISN</label>
                        <i class="fas fa-search absolute left-4 top-[35px] text-gray-300 text-xs"></i>
                        <input type="text" id="search-siswa" placeholder="Ketik nama atau NISN..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#10b981] outline-none transition"
                            oninput="filterSiswaAdmin()">
                    </div>

                    <div class="w-full lg:w-36">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mb-1.5 block">Tingkat</label>
                        <select id="filter-tingkat"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold text-gray-600 outline-none focus:ring-2 focus:ring-[#10b981] cursor-pointer appearance-none transition"
                            onchange="filterSiswaAdmin()">
                            <option value="">Semua Tingkat</option>
                            @foreach ($tingkatMaster as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full lg:w-36">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mb-1.5 block">Kelas</label>
                        <select id="filter-kelas"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold text-gray-600 outline-none focus:ring-2 focus:ring-[#10b981] cursor-pointer appearance-none transition"
                            onchange="filterSiswaAdmin()">
                            <option value="">Semua Kelas</option>
                            @foreach ($suffixMaster as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full lg:w-36">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mb-1.5 block">Status</label>
                        <select id="filter-status"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold text-gray-600 outline-none focus:ring-2 focus:ring-[#10b981] cursor-pointer appearance-none transition"
                            onchange="filterSiswaAdmin()">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="lulus">Lulus</option>
                            <option value="dikeluarkan">Dikeluarkan</option>
                        </select>
                    </div>

                    <div class="w-full lg:w-40">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mb-1.5 block">Tahun Masuk</label>
                        <select id="filter-tahun"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold text-gray-600 outline-none focus:ring-2 focus:ring-[#10b981] cursor-pointer appearance-none transition"
                            onchange="filterSiswaAdmin()">
                            <option value="">Semua Angkatan</option>
                            @php
                                $daftarTahun = \App\Models\TahunAjaran::orderBy('nama', 'desc')->get();
                            @endphp
                            @foreach ($daftarTahun as $thn)
                                <option value="{{ $thn->id }}">{{ $thn->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button
                        class="bg-blue-50 text-blue-600 px-5 py-2.5 rounded-xl font-bold text-xs hover:bg-blue-100 transition border border-blue-100 hidden lg:block"
                        onclick="filterSiswaAdmin()">
                        FILTER
                    </button>
                </div>

                <div class="overflow-x-auto border border-gray-50 rounded-2xl">
                    <table class="w-full text-left border-collapse min-w-[1000px]">
                        <thead class="bg-[#005c4b] text-white text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="p-4 rounded-tl-2xl w-32">NISN</th>
                                <th class="p-4">NAMA SISWA</th>
                                <th class="p-4 text-center w-24">KELAS</th>
                                <th class="p-4 text-center">KONTAK ORTU</th>
                                <th class="p-4 text-center w-24">POIN</th>
                                <th class="p-4 text-center w-32">STATUS / ANGKATAN</th>
                                <th class="p-4 text-center rounded-tr-2xl w-36">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs md:text-sm bg-white">

                            @forelse($groupedSiswa as $namaKelas => $siswas)
                                <tr class="kelas-group-header bg-green-50/50 border-y border-green-100"
                                    data-group-kelas="{{ $namaKelas }}">
                                    <td colspan="7"
                                        class="p-3 md:p-4 font-black text-[#10b981] text-xs uppercase tracking-widest shadow-sm">
                                        <i class="fas fa-layer-group mr-2 opacity-70"></i> KELAS
                                        {{ $namaKelas ?? 'Belum Ditentukan' }}
                                        <span
                                            class="ml-2 text-[9px] font-bold text-gray-400 bg-white px-2 py-0.5 rounded-md border border-gray-100">{{ count($siswas) }}
                                            Siswa</span>
                                    </td>
                                </tr>

                                @foreach ($siswas as $siswa)
                                    @php
                                        $parts = explode(' ', str_replace('-', ' ', $siswa->kelas));
                                        $rowTingkat = $parts[0] ?? '';
                                        $rowSuffix = $parts[1] ?? '';
                                        $status = $siswa->status ?? 'Aktif';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition row-data-siswa"
                                        data-tingkat="{{ $rowTingkat }}" data-suffix="{{ $rowSuffix }}"
                                        data-status="{{ strtolower($status) }}" data-group="{{ $namaKelas }}"
                                        data-tahun="{{ $siswa->tahun_masuk_id ?? '' }}">

                                        <td class="p-4 text-gray-500 font-medium nisn-col">{{ $siswa->nisn }}</td>

                                        <td class="p-4 font-bold text-gray-800 flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold text-xs shrink-0 shadow-inner overflow-hidden">
                                                @if ($siswa->photo)
                                                    <img src="{{ filter_var($siswa->photo, FILTER_VALIDATE_URL) ? $siswa->photo : asset('storage/' . $siswa->photo) }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                                @endif
                                            </div>
                                            <span class="nama-siswa-col">{{ $siswa->nama }}</span>
                                        </td>

                                        <td class="p-4 text-center text-[#10b981] font-bold">{{ $siswa->kelas }}</td>
                                        <td class="p-4 text-center text-gray-500">{{ $siswa->kontak_ortu ?? '-' }}
                                        </td>

                                        <td class="p-4 text-center font-black">
                                            @if (($siswa->poin ?? 0) >= 100)
                                                <span class="text-red-600">{{ $siswa->poin ?? 0 }}</span>
                                            @elseif(($siswa->poin ?? 0) >= 50)
                                                <span class="text-orange-500">{{ $siswa->poin ?? 0 }}</span>
                                            @else
                                                <span class="text-green-600">{{ $siswa->poin ?? 0 }}</span>
                                            @endif
                                        </td>

                                        <td class="p-4 text-center">
                                            @if (strtolower($status) == 'aktif')
                                                <span
                                                    class="bg-green-100 text-green-700 px-3 py-1.5 rounded-lg font-black text-[9px] uppercase tracking-wider border border-green-200">Aktif</span>
                                            @elseif(strtolower($status) == 'lulus')
                                                <span
                                                    class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg font-black text-[9px] uppercase tracking-wider border border-blue-200">Lulus</span>
                                            @else
                                                <span
                                                    class="bg-red-100 text-red-700 px-3 py-1.5 rounded-lg font-black text-[9px] uppercase tracking-wider border border-red-200">{{ $status }}</span>
                                            @endif
                                            <p class="text-[9px] font-bold text-gray-400 uppercase mt-1.5">
                                                {{ $siswa->tahunMasuk->nama ?? 'Belum Diatur' }}
                                            </p>
                                        </td>

                                        <td class="p-4 text-center whitespace-nowrap">
                                            <button onclick="openDetailSiswaModal(this)"
                                                data-nisn="{{ $siswa->nisn }}" data-nama="{{ $siswa->nama }}"
                                                data-kelas="{{ $siswa->kelas }}" data-jk="{{ $siswa->jk }}"
                                                data-kontak="{{ $siswa->kontak_ortu }}"
                                                data-alamat="{{ $siswa->alamat }}" data-ortu="{{ $siswa->ortu_id }}"
                                                data-ortu-nama="{{ $siswa->ortu->name ?? 'Belum Ditautkan' }}"
                                                data-poin="{{ $siswa->poin ?? 0 }}"
                                                data-status="{{ $status }}"
                                                data-tahun-nama="{{ $siswa->tahunMasuk->nama ?? 'Belum Diatur' }}"
                                                data-photo="{{ $siswa->photo ? (filter_var($siswa->photo, FILTER_VALIDATE_URL) ? $siswa->photo : asset('storage/' . $siswa->photo)) : '' }}"
                                                class="text-green-500 hover:text-green-700 mx-0.5 bg-green-50 hover:bg-green-100 p-2 rounded-lg transition shadow-sm"
                                                title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button onclick="openEditSiswaModal(this)"
                                                data-nisn="{{ $siswa->nisn }}" data-nama="{{ $siswa->nama }}"
                                                data-jk="{{ $siswa->jk }}" data-kelas="{{ $siswa->kelas }}"
                                                data-kontak="{{ $siswa->kontak_ortu }}"
                                                data-alamat="{{ $siswa->alamat }}" data-ortu="{{ $siswa->ortu_id }}"
                                                data-status="{{ $status }}"
                                                class="text-blue-500 hover:text-blue-700 mx-0.5 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition shadow-sm"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="openDeleteSiswaModal(this)"
                                                data-nisn="{{ $siswa->nisn }}" data-nama="{{ $siswa->nama }}"
                                                class="text-red-500 hover:text-red-700 mx-0.5 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition shadow-sm"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="7" class="p-10 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-folder-open text-4xl mb-3 opacity-50"></i>
                                            <p class="font-bold">Belum ada data siswa.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $dataSiswa->links() ?? '' }}
                </div>
            </div>
        </div>

    </main>

    <!-- ============================================== -->
    <!-- MODAL DETAIL SISWA -->
    <!-- ============================================== -->
    <div id="detailSiswaModal"
        class="fixed inset-0 bg-gray-900/80 hidden z-[100] items-center justify-center backdrop-blur-sm p-4 transition-opacity opacity-0 duration-200">
        <div class="bg-white rounded-[24px] p-5 md:p-6 max-w-sm w-full mx-4 shadow-2xl transform scale-95 opacity-0 transition-all duration-200"
            id="detailSiswaContent">
            <div class="flex justify-between items-center mb-4 md:mb-5 border-b pb-3 md:pb-4">
                <h3 class="text-base md:text-lg font-black text-gray-800 uppercase tracking-wider">Detail Profil Siswa
                </h3>
                <button onclick="closeDetailSiswaModal()"
                    class="text-gray-400 hover:text-red-500 transition focus:outline-none">
                    <i class="fas fa-times text-lg md:text-xl"></i>
                </button>
            </div>

            <div class="flex flex-col items-center mb-5 md:mb-6">
                <div id="detailProfilePic"
                    class="w-16 h-16 md:w-20 md:h-20 bg-green-100 rounded-full mb-3 flex items-center justify-center text-[#10b981] text-2xl font-bold border-4 border-green-50 shadow-sm overflow-hidden">
                </div>
                <h4 id="detailProfileNama"
                    class="text-sm md:text-base font-black text-gray-800 uppercase text-center leading-tight">-</h4>
                <div class="flex items-center justify-center flex-wrap gap-2 mt-1 md:mt-2">
                    <span id="detailProfileNisn"
                        class="text-[9px] md:text-[10px] font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">-</span>
                    <span id="detailProfileStatus"
                        class="text-[9px] md:text-[10px] font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full uppercase">-</span>
                    <span id="detailProfileTahun"
                        class="text-[9px] md:text-[10px] font-bold text-blue-700 bg-blue-100 px-3 py-1 rounded-full uppercase">-</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-center">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kelas
                    </p>
                    <p id="detailProfileKelas" class="text-xs font-bold text-gray-700">-</p>
                </div>
                <div class="bg-red-50 p-3 rounded-xl border border-red-100 text-center">
                    <p class="text-[9px] font-bold text-red-400 uppercase tracking-widest mb-1">Total
                        Poin</p>
                    <p id="detailProfilePoin" class="font-black text-red-600 text-base md:text-lg">0</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-center">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Gender
                    </p>
                    <p id="detailProfileJk" class="text-xs font-bold text-gray-700">-</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-center overflow-hidden">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Wali
                        Murid</p>
                    <p id="detailProfileOrtu" class="text-[10px] font-bold text-gray-700 truncate" title="">-
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="col-span-2 bg-gray-50 p-3 rounded-xl border border-gray-100 text-center">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Alamat
                        Tinggal</p>
                    <p id="detailProfileAlamat" class="text-[10px] font-bold text-gray-700">-</p>
                </div>
                <div class="col-span-2 bg-gray-50 p-3 rounded-xl border border-gray-100 text-center">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kontak
                        Ortu / WA</p>
                    <p id="detailProfileKontak" class="text-[10px] font-bold text-gray-700">-</p>
                </div>
            </div>

            <div class="mt-5 md:mt-6 flex justify-center">
                <button onclick="closeDetailSiswaModal()"
                    class="w-full py-2.5 md:py-3 bg-[#10b981] text-white shadow-lg shadow-green-100 font-bold text-[10px] md:text-xs uppercase tracking-wider rounded-xl hover:bg-green-600 transition">
                    Tutup Detail
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL TAMBAH SISWA -->
    <!-- ============================================== -->
    <div id="addSiswaModal"
        class="fixed inset-0 bg-black/50 hidden z-[100] items-center justify-center backdrop-blur-sm transition-opacity opacity-0 duration-200">
        <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl transform transition-all scale-95 opacity-0 duration-200 mx-4"
            id="addModalContent">
            <div class="px-6 py-4 bg-[#10b981] text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Tambah Data Siswa</h3>
                <button onclick="closeModals()"
                    class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form action="{{ route('admin.siswa.store') }}" method="POST" enctype="multipart/form-data"
                class="p-4 md:p-6 max-h-[75vh] overflow-y-auto">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">NISN <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nisn" required
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981]"
                            placeholder="Angka NISN">
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
                        <label class="block text-xs font-bold text-gray-600 mb-1">Status Pendidikan</label>
                        <select name="status"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] appearance-none bg-white font-bold text-[#10b981]">
                            <option value="Aktif" selected>Aktif</option>
                            <option value="Lulus">Lulus</option>
                            <option value="Dikeluarkan">Dikeluarkan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Akun Orang Tua Terhubung</label>
                        <select name="ortu_id"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#10b981] focus:ring-1 focus:ring-[#10b981] appearance-none bg-white">
                            <option value=""> Belum ada / Pilih Ortu </option>
                            @foreach ($daftarOrtu ?? [] as $ortu)
                                <option value="{{ $ortu->id }}">{{ $ortu->name }} ({{ $ortu->email }})
                                </option>
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

                    <div class="col-span-1 md:col-span-2">
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
    <!-- MODAL EDIT SISWA -->
    <!-- ============================================== -->
    <div id="editSiswaModal"
        class="fixed inset-0 bg-black/50 hidden z-[100] items-center justify-center backdrop-blur-sm transition-opacity opacity-0 duration-200">
        <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl transform transition-all scale-95 opacity-0 duration-200 mx-4"
            id="editModalContent">
            <div class="px-6 py-4 bg-blue-500 text-white flex justify-between items-center">
                <h3 class="font-bold text-lg">Edit Data Siswa</h3>
                <button onclick="closeModals()"
                    class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form id="editSiswaForm" method="POST" enctype="multipart/form-data"
                class="p-4 md:p-6 max-h-[75vh] overflow-y-auto">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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

                    <!-- DROPDOWN KELAS -->
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
                        <label class="block text-xs font-bold text-gray-600 mb-1">Status Pendidikan</label>
                        <select name="status" id="edit_status"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 appearance-none bg-white font-bold text-blue-600">
                            <option value="Aktif">Aktif</option>
                            <option value="Lulus">Lulus</option>
                            <option value="Dikeluarkan">Dikeluarkan</option>
                        </select>
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
                        <label class="block text-xs font-bold text-gray-600 mb-1">Kontak Orang Tua / Wali</label>
                        <input type="text" name="kontak_ortu" id="edit_kontak_ortu"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Alamat (Opsional)</label>
                        <input type="text" name="alamat" id="edit_alamat"
                            class="w-full border rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>

                    <div class="col-span-1 md:col-span-2">
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
        class="fixed inset-0 bg-black/50 hidden z-[100] items-center justify-center backdrop-blur-sm transition-opacity opacity-0 duration-200">
        <div class="bg-white rounded-3xl w-full max-w-sm mx-4 overflow-hidden shadow-2xl p-6 text-center transform transition-all scale-95 opacity-0 duration-200"
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

    <!-- ============================================== -->
    <!-- MODAL IMPORT EXCEL -->
    <!-- ============================================== -->
    <div id="importSiswaModal"
        class="fixed inset-0 bg-black/50 hidden z-[100] items-center justify-center backdrop-blur-sm transition-opacity opacity-0 duration-200">
        <div class="bg-white rounded-3xl w-full max-w-sm mx-4 overflow-hidden shadow-2xl p-5 md:p-6 text-center transform transition-transform scale-95 opacity-0 duration-200"
            id="importModalContent">

            <div class="flex justify-end mb-1">
                <button onclick="closeImportModal()"
                    class="text-gray-400 hover:text-red-500 transition focus:outline-none">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <div
                class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center text-xl mx-auto mb-3 border-4 border-green-100">
                <i class="fas fa-file-excel"></i>
            </div>
            <h3 class="font-black text-gray-800 text-lg mb-1 tracking-tight">Import Data Excel</h3>
            <p class="text-[10px] md:text-[11px] text-gray-500 mb-4 px-2">Unggah file <strong
                    class="text-gray-700">.xlsx</strong> atau <strong class="text-gray-700">.xls</strong> Anda.
                Pastikan urutan kolom sesuai standar sistem.</p>

            <div
                class="bg-blue-50/50 border border-blue-100 rounded-2xl p-3 md:p-4 text-[9px] md:text-[10px] text-left text-gray-600 mb-5 shadow-inner relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-10 h-10 bg-blue-100 rounded-full opacity-50 blur-md"></div>
                <div class="absolute -left-4 -bottom-4 w-10 h-10 bg-green-100 rounded-full opacity-50 blur-md"></div>

                <p class="font-black mb-1.5 text-gray-800 flex items-center gap-2"><i
                        class="fas fa-info-circle text-blue-500 text-xs"></i> Format Baris 1 (Header):</p>
                <ol class="list-decimal pl-4 space-y-0.5 font-medium relative z-10">
                    <li><span class="font-bold text-gray-800">NISN</span> <span class="text-gray-400">(Wajib)</span>
                    </li>
                    <li><span class="font-bold text-gray-800">Nama</span> <span class="text-gray-400">(Wajib)</span>
                    </li>
                    <li><span class="font-bold text-gray-800">Jenis Kelamin</span> <span
                            class="text-gray-400">(L/P)</span></li>
                    <li><span class="font-bold text-gray-800">Kelas</span> <span class="text-gray-400">(Wajib, cth:
                            VII A)</span></li>
                    <li><span class="font-bold text-gray-800">Kontak Ortu</span> <span
                            class="text-gray-400">(Opsional)</span></li>
                    <li><span class="font-bold text-gray-800">Alamat</span> <span
                            class="text-gray-400">(Opsional)</span></li>
                    <li><span class="font-bold text-gray-800">Tahun Masuk</span> <span
                            class="text-[8px] md:text-[9px] text-gray-400 leading-tight block mt-0.5">Kosongkan jika
                            menggunakan Tahun Ajaran aktif saat ini.</span></li>
                </ol>
            </div>

            <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col items-center w-full">
                @csrf
                <div class="w-full mb-5 relative group">
                    <label for="file_excel"
                        class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer bg-gray-50 hover:bg-green-50 hover:border-[#10b981] transition-all duration-300">
                        <div class="flex flex-col items-center justify-center pt-3 pb-4">
                            <i
                                class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2 group-hover:text-[#10b981] transition-colors"></i>
                            <p class="mb-0.5 text-[11px] text-gray-500 font-bold"><span
                                    class="font-black text-gray-700 group-hover:text-[#10b981]">Klik upload</span> atau
                                drag & drop</p>
                            <p class="text-[9px] text-gray-400 font-medium">XLSX, XLS (Max. 5MB)</p>
                        </div>
                        <input id="file_excel" type="file" name="file_excel" accept=".xlsx, .xls" required
                            class="hidden" onchange="updateFileName(this)" />
                    </label>
                    <div id="file-name-display"
                        class="absolute -bottom-5 left-0 right-0 text-center text-[9px] font-bold text-[#10b981] truncate px-4 hidden">
                    </div>
                </div>

                <div class="flex justify-center gap-3 w-full mt-2">
                    <button type="button" onclick="closeImportModal()"
                        class="flex-1 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-[10px] uppercase tracking-wider hover:bg-gray-200 transition">Batal</button>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-[#10b981] text-white rounded-xl font-bold text-[10px] uppercase tracking-wider shadow-lg shadow-green-100 hover:bg-green-600 transition flex items-center justify-center gap-2">
                        <i class="fas fa-upload"></i> Proses
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL TUTUP TAHUN (KENAIKAN KELAS) -->
    <!-- ============================================== -->
    <div id="tutupTahunModal"
        class="fixed inset-0 bg-gray-900/80 hidden z-[100] items-center justify-center backdrop-blur-sm transition-opacity opacity-0 duration-200 p-4">
        <div class="bg-white rounded-[30px] p-6 md:p-8 max-w-md w-full shadow-2xl transform scale-95 opacity-0 transition-transform duration-200"
            id="tutupTahunContent">

            <div
                class="w-20 h-20 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-5 border-4 border-purple-50 shadow-inner">
                <i class="fas fa-level-up-alt"></i>
            </div>

            <h3 class="text-xl font-black text-gray-800 uppercase tracking-wider text-center mb-2">Tutup Tahun Ajaran?
            </h3>

            <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 mb-6">
                <p class="text-sm text-orange-800 font-medium mb-2 text-center">Proses ini akan melakukan hal berikut
                    secara otomatis:</p>
                <ul class="text-xs text-orange-700 list-disc pl-5 space-y-1.5 font-medium">
                    <li>Siswa Kelas <strong class="font-black">VII</strong> otomatis naik ke Kelas <strong
                            class="font-black">VIII</strong>.</li>
                    <li>Siswa Kelas <strong class="font-black">VIII</strong> otomatis naik ke Kelas <strong
                            class="font-black">IX</strong>.</li>
                    <li>Siswa Kelas <strong class="font-black">IX</strong> statusnya diubah menjadi <strong
                            class="text-blue-600">LULUS</strong>.</li>
                    <li>Poin pelanggaran <strong class="font-black text-red-600">TIDAK AKAN DIHAPUS</strong> (Berlanjut
                        secara kumulatif).</li>
                </ul>
            </div>

            <p class="text-xs text-center text-gray-500 mb-6 font-bold italic">Peringatan: Lakukan ini hanya jika
                pembagian raport akhir semester genap telah selesai dibagikan.</p>

            <form action="{{ route('admin.siswa.kenaikan') ?? '#' }}" method="POST"
                class="flex flex-col sm:flex-row justify-center gap-3">
                @csrf
                <button type="button" onclick="closeTutupTahunModal()"
                    class="w-full sm:w-1/2 px-6 py-3.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit"
                    class="w-full sm:w-1/2 px-6 py-3.5 bg-purple-600 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-lg shadow-purple-200 hover:bg-purple-700 transition flex justify-center items-center gap-2">
                    <i class="fas fa-bolt"></i> Proses
                </button>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // ==========================================
        // FUNGSI ANIMASI MODAL TERPUSAT (PENTING!)
        // ==========================================
        function openModalAnimation(modalId, contentId) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(contentId);
            if (!modal || !content) return;

            // Hapus class hidden, tambahkan flex agar elemen ada di DOM
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Beri sedikit waktu untuk merender CSS sebelum trigger animasi opacity & scale
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');

                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModalAnimation(modalId, contentId) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(contentId);
            if (!modal || !content) return;

            // Hapus class animasi aktif, kembalikan ke state awal (kecil & transparan)
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');

            // Tunggu durasi transisi Tailwind (200ms) sebelum menghilangkan dari DOM
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 200);
        }

        // ==========================================
        // PENERAPAN ANIMASI KE SEMUA MODAL
        // ==========================================
        function openDetailSiswaModal(btn) {
            let nisn = btn.getAttribute('data-nisn');
            let nama = btn.getAttribute('data-nama');
            let jk = btn.getAttribute('data-jk');
            let kelas = btn.getAttribute('data-kelas');
            let kontak = btn.getAttribute('data-kontak');
            let alamat = btn.getAttribute('data-alamat');
            let ortuNama = btn.getAttribute('data-ortu-nama');
            let status = btn.getAttribute('data-status');
            let poin = btn.getAttribute('data-poin');
            let tahunNama = btn.getAttribute('data-tahun-nama');
            let photo = btn.getAttribute('data-photo');

            document.getElementById('detailProfileNama').innerText = nama || '-';
            document.getElementById('detailProfileTahun').innerText = "Angkatan: " + (tahunNama || '-');
            document.getElementById('detailProfileNisn').innerText = nisn || '-';
            document.getElementById('detailProfileKelas').innerText = kelas || '-';
            document.getElementById('detailProfileJk').innerText = jk || '-';
            document.getElementById('detailProfileKontak').innerText = kontak || '-';
            document.getElementById('detailProfileAlamat').innerText = alamat || 'Belum diisi';
            document.getElementById('detailProfileOrtu').innerText = ortuNama || 'Belum Ditautkan';
            document.getElementById('detailProfileOrtu').title = ortuNama || 'Belum Ditautkan';
            document.getElementById('detailProfilePoin').innerText = poin || '0';

            let statusBadge = document.getElementById('detailProfileStatus');
            statusBadge.innerText = status || 'Aktif';
            if ((status || '').toLowerCase() === 'aktif') {
                statusBadge.className =
                    'text-[10px] md:text-[10px] font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full uppercase border border-green-200';
            } else if ((status || '').toLowerCase() === 'lulus') {
                statusBadge.className =
                    'text-[10px] md:text-[10px] font-bold text-blue-700 bg-blue-100 px-3 py-1 rounded-full uppercase border border-blue-200';
            } else {
                statusBadge.className =
                    'text-[10px] md:text-[10px] font-bold text-red-700 bg-red-100 px-3 py-1 rounded-full uppercase border border-red-200';
            }

            let picContainer = document.getElementById('detailProfilePic');
            if (photo && photo !== '') {
                picContainer.innerHTML = `<img src="${photo}" style="width: 100%; height: 100%; object-fit: cover;">`;
            } else {
                picContainer.innerHTML = nama ? nama.charAt(0).toUpperCase() : 'S';
            }

            openModalAnimation('detailSiswaModal', 'detailSiswaContent');
        }

        function closeDetailSiswaModal() {
            closeModalAnimation('detailSiswaModal', 'detailSiswaContent');
        }

        function openAddSiswaModal() {
            openModalAnimation('addSiswaModal', 'addModalContent');
        }

        function openEditSiswaModal(btn) {
            // Ambil data dari tombol
            let nisn = btn.getAttribute('data-nisn');
            let nama = btn.getAttribute('data-nama');
            let jk = btn.getAttribute('data-jk');
            let kelas = btn.getAttribute('data-kelas');
            let kontak = btn.getAttribute('data-kontak');
            let alamat = btn.getAttribute('data-alamat');
            let ortu = btn.getAttribute('data-ortu');
            let status = btn.getAttribute('data-status');

            // Set Form action dinamis
            let actionUrl = "{{ route('admin.siswa.update', ':nisn') }}".replace(':nisn', nisn);
            document.getElementById('editSiswaForm').action = actionUrl;

            // Set value input
            document.getElementById('edit_nisn').value = nisn || '';
            document.getElementById('edit_nama').value = nama || '';
            document.getElementById('edit_jk').value = jk || '';
            document.getElementById('edit_kelas').value = kelas || '';
            document.getElementById('edit_kontak_ortu').value = kontak || '';
            document.getElementById('edit_alamat').value = alamat || '';
            document.getElementById('edit_ortu_id').value = ortu || '';

            if (status) {
                let statusKapital = status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
                document.getElementById('edit_status').value = statusKapital;
            } else {
                document.getElementById('edit_status').value = 'Aktif';
            }

            document.getElementById('edit_photo').value = '';

            openModalAnimation('editSiswaModal', 'editModalContent');
        }

        function openDeleteSiswaModal(btn) {
            let nisn = btn.getAttribute('data-nisn');
            let nama = btn.getAttribute('data-nama');

            document.getElementById('delete_nama_siswa').innerText = nama;
            let actionUrl = "{{ route('admin.siswa.destroy', ':nisn') }}".replace(':nisn', nisn);
            document.getElementById('deleteSiswaForm').action = actionUrl;

            openModalAnimation('deleteSiswaModal', 'deleteModalContent');
        }

        function openImportModal() {
            // Reset input file saat dibuka kembali
            document.getElementById('file_excel').value = '';
            document.getElementById('file-name-display').classList.add('hidden');

            openModalAnimation('importSiswaModal', 'importModalContent');
        }

        function closeImportModal() {
            closeModalAnimation('importSiswaModal', 'importModalContent');
        }

        function openTutupTahunModal() {
            openModalAnimation('tutupTahunModal', 'tutupTahunContent');
        }

        function closeTutupTahunModal() {
            closeModalAnimation('tutupTahunModal', 'tutupTahunContent');
        }

        function closeModals() {
            // Tutup semua modal menggunakan sistem animasi baru
            closeModalAnimation('addSiswaModal', 'addModalContent');
            closeModalAnimation('editSiswaModal', 'editModalContent');
            closeModalAnimation('deleteSiswaModal', 'deleteModalContent');
            closeImportModal();
            closeTutupTahunModal();
        }

        // ==========================================
        // LOGIKA MOBILE SIDEBAR, SPA, & LAINNYA
        // ==========================================
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('opacity-0');
                setTimeout(() => backdrop.classList.add('hidden'), 300);
            }
        }

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
            }
        }

        function filterSiswaAdmin() {
            const search = document.getElementById('search-siswa').value.toLowerCase().trim();
            const tingkat = document.getElementById('filter-tingkat').value;
            const suffix = document.getElementById('filter-kelas').value;
            const status = document.getElementById('filter-status').value.toLowerCase();
            const tahun = document.getElementById('filter-tahun') ? document.getElementById('filter-tahun').value : '';

            const rows = document.querySelectorAll('.row-data-siswa');
            const visibleGroups = {};

            rows.forEach(row => {
                const nama = row.querySelector('.nama-siswa-col').innerText.toLowerCase();
                const nisn = row.querySelector('.nisn-col').innerText.toLowerCase();

                const rowTingkat = row.getAttribute('data-tingkat');
                const rowSuffix = row.getAttribute('data-suffix');
                const rowStatus = row.getAttribute('data-status');
                const rowTahun = row.getAttribute('data-tahun') || '';
                const groupName = row.getAttribute('data-group');

                const matchSearch = nama.includes(search) || nisn.includes(search);
                const matchTingkat = tingkat === '' || rowTingkat === tingkat;
                const matchSuffix = suffix === '' || rowSuffix === suffix;
                const matchStatus = status === '' || rowStatus === status;
                const matchTahun = tahun === '' || rowTahun === tahun;

                if (matchSearch && matchTingkat && matchSuffix && matchStatus && matchTahun) {
                    row.style.display = '';
                    visibleGroups[groupName] = true;
                } else {
                    row.style.display = 'none';
                }
            });

            const groupHeaders = document.querySelectorAll('.kelas-group-header');
            groupHeaders.forEach(header => {
                const groupName = header.getAttribute('data-group-kelas');
                if (visibleGroups[groupName]) {
                    header.style.display = '';
                } else {
                    header.style.display = 'none';
                }
            });
        }

        function updateFileName(input) {
            const display = document.getElementById('file-name-display');
            if (input.files && input.files.length > 0) {
                display.innerText = "File: " + input.files[0].name;
                display.classList.remove('hidden');
            } else {
                display.classList.add('hidden');
            }
        }

        // ==========================================
        // EVENT LISTENERS & DROPDOWN PROFIL
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>

</html>
