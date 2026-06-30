<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard Kepala Madrasah - Monitoring Sistem</title>
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

<body class="bg-[#f4f7f6] font-sans flex text-gray-800 overflow-x-hidden">

    <!-- OVERLAY UNTUK MOBILE SIDEBAR -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity" onclick="toggleSidebar()">
    </div>

    <!-- SIDEBAR (Ditambahkan fitur slide responsif) -->
    <aside id="sidebar"
        class="w-72 min-h-screen bg-[#10b981] text-white flex flex-col fixed shadow-xl z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="p-6 md:p-8 relative">
            <button onclick="toggleSidebar()" class="md:hidden absolute top-4 right-4 text-white/80 hover:text-white">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <div class="flex items-center gap-3 mb-2 mt-2 md:mt-0">
                <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                    class="w-10 drop-shadow-md" alt="Logo Kemenag">
                <h1 class="font-bold text-xl md:text-2xl leading-tight tracking-tight uppercase">Panel <br> Pimpinan
                    <br> Madrasah
                </h1>
            </div>
            <p class="text-[10px] opacity-80 font-medium tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
        </div>

        <nav class="mt-4 flex-grow pl-6">
            <a href="{{ route('kamad.kamad-dashboard') }}"
                onclick="event.preventDefault(); showView('dashboard'); closeSidebarOnMobile();" id="nav-dashboard"
                class="sidebar-item active flex items-center px-6 py-4 transition">
                <i class="fas fa-th-large mr-4 text-sm"></i> <span>Ringkasan</span>
            </a>
            <a href="{{ route('kamad.kamad-laporan') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-file-contract mr-4 text-sm"></i> <span class="font-medium">Laporan Masuk</span>
            </a>
            <a href="{{ route('kamad.kamad-poin') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-users mr-4 text-sm"></i> <span class="font-medium">Poin Keseluruhan</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 md:ml-72 p-4 md:p-10 w-full transition-all duration-300 min-h-screen flex flex-col">
        <!-- GLOBAL HEADER -->
        <header class="flex justify-between items-center mb-6 md:mb-10 mt-2 md:mt-0">
            <div class="flex items-center gap-3 md:gap-4">
                <button onclick="toggleSidebar()"
                    class="md:hidden text-gray-600 hover:text-[#10b981] focus:outline-none shrink-0">
                    <i class="fas fa-bars text-xl bg-white p-2 rounded-lg shadow-sm border border-gray-100"></i>
                </button>
                <div>
                    <nav
                        class="text-[9px] md:text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1 hidden sm:block">
                        Home / <span id="breadcrumb-active">Ringkasan</span>
                    </nav>
                    <h2 id="view-title"
                        class="text-xl md:text-2xl font-black text-gray-700 uppercase tracking-tighter italic">
                        Monitoring Kedisiplinan
                    </h2>
                </div>
            </div>

            <!-- User Profile & Dropdown -->
            <div class="relative shrink-0">
                <button id="profileDropdownBtn"
                    class="flex items-center gap-2 md:gap-4 bg-white p-1.5 pr-3 md:px-6 md:py-2 rounded-full shadow-sm border border-gray-100 hover:bg-gray-50 transition focus:outline-none">
                    <div class="text-right hidden md:block">
                        <p class="text-xs font-black text-[#10b981] uppercase leading-none">
                            {{ Auth::user()->name ?? 'Kepala Madrasah' }}
                        </p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Akses: Pimpinan</p>
                    </div>

                    @php
                        $avatarUrl =
                            'https://ui-avatars.com/api/?name=' .
                            urlencode(Auth::user()->name ?? 'Kepala Madrasah') .
                            '&background=10b981&color=fff';
                        $photoPath = Auth::user()->photo
                            ? (str_starts_with(Auth::user()->photo, 'http')
                                ? Auth::user()->photo
                                : asset('storage/' . Auth::user()->photo))
                            : $avatarUrl;
                    @endphp

                    <img src="{{ $photoPath }}" onerror="this.src='{{ $avatarUrl }}'"
                        class="w-8 h-8 md:w-10 md:h-10 rounded-full border-2 border-green-50 object-cover shadow-sm"
                        alt="Profile">

                    <i class="fas fa-chevron-down text-gray-400 text-xs ml-1 md:ml-0"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdownMenu"
                    class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden z-50 transform origin-top-right transition-all duration-200 opacity-0 scale-95">
                    <div class="py-2">
                        <!-- Mengarah ke file index profil mandiri Anda -->
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

        <!-- SECTION: DASHBOARD HOME -->
        <div id="view-dashboard" class="view-section active">
            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 mb-8 md:mb-10">
                <!-- CHART SECTION -->
                <div
                    class="lg:col-span-2 bg-white p-6 md:p-8 rounded-[30px] shadow-sm border border-gray-50 overflow-hidden">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-3">
                        <h3 class="font-black text-gray-700 text-sm uppercase tracking-widest flex items-center">
                            <i class="fas fa-chart-line mr-3 text-[#10b981]"></i> Analisis Kedisiplinan Bulanan
                        </h3>
                        <button
                            class="px-3 py-1.5 bg-green-50 text-[#10b981] text-[10px] font-bold rounded-lg uppercase w-fit">Tahun
                            Ini</button>
                    </div>
                    <div class="w-full overflow-x-auto">
                        <div class="min-w-[400px]">
                            <canvas id="bigChart" height="150"></canvas>
                        </div>
                    </div>
                </div>

                <!-- STATISTIK SECTION -->
                <div class="bg-white p-6 md:p-8 rounded-[30px] shadow-sm border border-gray-50">
                    <h3 class="font-black text-gray-700 text-sm uppercase tracking-widest mb-6">Ringkasan Sistem</h3>
                    <div class="space-y-4">
                        <div
                            class="p-4 bg-blue-50 rounded-2xl border border-blue-100 flex justify-between items-center">
                            <span class="text-[10px] md:text-xs font-bold text-blue-700 uppercase">Total
                                Pelanggaran</span>
                            <span
                                class="text-lg md:text-xl font-black text-blue-700">{{ number_format($totalPelanggaran ?? 0) }}</span>
                        </div>
                        <div
                            class="p-4 bg-yellow-50 rounded-2xl border border-yellow-100 flex justify-between items-center">
                            <span class="text-[10px] md:text-xs font-bold text-yellow-700 uppercase">Siswa Waspada <br
                                    class="md:hidden">(50+
                                Poin)</span>
                            <span
                                class="text-lg md:text-xl font-black text-yellow-700">{{ number_format($waspada ?? 0) }}</span>
                        </div>
                        <div class="p-4 bg-red-50 rounded-2xl border border-red-100 flex justify-between items-center">
                            <span class="text-[10px] md:text-xs font-bold text-red-700 uppercase">Tindakan DO <br
                                    class="md:hidden">(100+ Poin)</span>
                            <span
                                class="text-lg md:text-xl font-black text-red-700">{{ number_format($dropOut ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LAPORAN TERBARU SECTION -->
            <div class="bg-white p-6 md:p-8 rounded-[30px] shadow-sm border border-gray-50">
                <div class="flex justify-between items-center mb-6 md:mb-8 border-b pb-4">
                    <h3 class="font-black text-gray-700 text-xs md:text-sm uppercase tracking-widest">
                        <i class="fas fa-bell text-orange-400 mr-2"></i> Laporan Terbaru
                    </h3>
                    <a href="{{ route('kamad.kamad-laporan') }}"
                        class="text-[9px] md:text-[10px] font-bold text-[#10b981] hover:underline uppercase">Lihat
                        Semua</a>
                </div>

                <div class="space-y-4 text-xs">
                    <!-- Looping Laporan Terbaru dari Database -->
                    @forelse($laporanTerbaru ?? [] as $laporan)
                        <div
                            class="p-4 md:p-5 bg-gray-50 hover:bg-gray-100 rounded-2xl flex flex-col sm:flex-row justify-between sm:items-center gap-4 transition border border-gray-100">
                            <div class="flex items-start sm:items-center gap-3 md:gap-4">
                                <div
                                    class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-red-100 text-red-500 flex items-center justify-center text-lg md:text-xl shrink-0">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div>
                                    <p class="font-black text-gray-800 uppercase tracking-tight text-xs md:text-sm">
                                        {{ $laporan->judul ?? 'Laporan Tanpa Judul' }}</p>
                                    <p class="text-gray-400 mt-1 font-medium italic text-[10px] md:text-xs"><i
                                            class="fas fa-user-shield mr-1"></i>
                                        Dikirim oleh: {{ $laporan->pengirim->name ?? 'Administrator Sistem' }}</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $laporan->file_path) }}" download
                                class="w-full sm:w-auto text-center bg-[#10b981] text-white px-6 py-2.5 rounded-xl font-bold uppercase tracking-tighter text-[10px] hover:scale-105 shadow-sm shadow-green-100 transition">
                                <i class="fas fa-download mr-1"></i> Unduh
                            </a>
                        </div>
                    @empty
                        <div
                            class="text-center p-6 text-gray-400 font-medium text-sm border-2 border-dashed border-gray-100 rounded-2xl">
                            Belum ada laporan terbaru yang masuk.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- SECTION: PROFILE PENGGUNA -->
        <div id="view-profile" class="view-section">
            <div class="bg-white p-6 md:p-8 rounded-[30px] shadow-sm border border-gray-50 max-w-2xl mx-auto w-full">
                <!-- Profile Display -->
                <div id="profileView" class="flex flex-col items-center transition-all duration-300">
                    <!-- Image -->
                    <div
                        class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden mb-4 border-4 border-green-50 shadow-sm relative group">
                        <img src="{{ $photoPath }}" onerror="this.src='{{ $avatarUrl }}'"
                            class="w-full h-full object-cover" id="mainProfilePic" alt="Profile Picture">
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-gray-800 uppercase text-center">
                        {{ Auth::user()->name ?? 'Nama Pimpinan' }}</h3>
                    <p
                        class="text-[10px] md:text-xs font-bold text-[#10b981] uppercase tracking-widest mb-6 md:mb-8 text-center">
                        {{ ucfirst(Auth::user()->role ?? 'Kepala Madrasah') }}</p>

                    <div class="w-full space-y-3 md:space-y-4">
                        <div class="flex items-center p-4 md:p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-id-badge text-[#10b981] w-8 md:w-10 text-center text-lg md:text-xl"></i>
                            <div class="ml-3 md:ml-4">
                                <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    NIP / Username
                                </p>
                                <p class="text-xs md:text-sm font-black text-gray-700">
                                    {{ Auth::user()->nip ?? (Auth::user()->username ?? '-') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 md:p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-venus-mars text-[#10b981] w-8 md:w-10 text-center text-lg md:text-xl"></i>
                            <div class="ml-3 md:ml-4">
                                <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    Gender</p>
                                <p class="text-xs md:text-sm font-black text-gray-700">
                                    {{ Auth::user()->gender ?? (Auth::user()->jk ?? '-') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 md:p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-phone-alt text-[#10b981] w-8 md:w-10 text-center text-lg md:text-xl"></i>
                            <div class="ml-3 md:ml-4">
                                <p class="text-[9px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    No. Telepon
                                </p>
                                <p class="text-xs md:text-sm font-black text-gray-700">
                                    {{ Auth::user()->phone ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="toggleEditProfile(true)"
                        class="mt-6 md:mt-8 bg-[#10b981] text-white px-8 py-3.5 md:py-4 rounded-2xl text-[10px] md:text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-105 transition w-full">
                        <i class="fas fa-user-edit mr-2"></i> Edit Profil
                    </button>
                </div>

                <!-- Profile Form -->
                <form id="profileForm" class="hidden flex-col transition-all duration-300"
                    action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex justify-between items-center mb-6 md:mb-8 border-b border-gray-100 pb-4">
                        <h3 class="text-base md:text-lg font-black text-gray-700 uppercase tracking-widest">Edit Profil
                        </h3>
                        <button type="button" onclick="toggleEditProfile(false)"
                            class="text-gray-400 hover:text-red-500 transition"><i
                                class="fas fa-times text-lg md:text-xl"></i></button>
                    </div>

                    <div class="space-y-4 md:space-y-5">
                        <div>
                            <label
                                class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Unggah
                                Foto Profil Baru</label>
                            <input type="file" name="photo" accept="image/*"
                                class="w-full text-xs md:text-sm text-gray-500 file:mr-3 md:file:mr-4 file:py-2 md:file:py-3 file:px-3 md:file:px-4 file:rounded-xl file:border-0 file:text-[10px] md:file:text-xs file:font-bold file:bg-green-50 file:text-[#10b981] hover:file:bg-green-100 transition border border-gray-100 rounded-xl bg-gray-50 cursor-pointer">
                        </div>
                        <div>
                            <label
                                class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Nama
                                Lengkap</label>
                            <input type="text" name="name" value="{{ Auth::user()->name ?? '' }}" required
                                class="w-full px-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>
                        <div>
                            <label
                                class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">NIP
                                / Username</label>
                            <input type="text" value="{{ Auth::user()->nip ?? (Auth::user()->username ?? '') }}"
                                disabled
                                class="w-full px-4 py-2.5 md:py-3 bg-gray-100 border border-gray-200 rounded-xl text-xs md:text-sm font-bold text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label
                                class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Gender</label>
                            <select name="gender"
                                class="w-full px-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition appearance-none">
                                <option value="">Pilih Gender</option>
                                <option value="Laki-laki"
                                    {{ (Auth::user()->gender ?? (Auth::user()->jk ?? '')) === 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan"
                                    {{ (Auth::user()->gender ?? (Auth::user()->jk ?? '')) === 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">No.
                                Telepon</label>
                            <input type="tel" name="phone" value="{{ Auth::user()->phone ?? '' }}"
                                class="w-full px-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>

                        <!-- Input Ganti Password (Opsional) -->
                        <div class="pt-4 mt-2 border-t border-gray-100">
                            <label
                                class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Ganti
                                Password (Opsional)</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah"
                                class="w-full px-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 md:gap-4 mt-6 md:mt-8">
                        <button type="submit"
                            class="w-full sm:flex-1 bg-[#10b981] text-white px-6 py-3.5 md:py-4 rounded-xl text-[10px] md:text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-105 transition">Simpan
                            Perubahan</button>
                        <button type="button" onclick="toggleEditProfile(false)"
                            class="w-full sm:flex-1 bg-gray-100 text-gray-600 px-6 py-3.5 md:py-4 rounded-xl text-[10px] md:text-xs font-bold uppercase tracking-wider hover:bg-gray-200 transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <!-- SCRIPT (Chart.js, Toggle Mobile, & Fitur View) -->
    <script>
        // Logika Toggle Sidebar Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            if (overlay.classList.contains('hidden')) {
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.add('opacity-100'), 10);
            } else {
                overlay.classList.remove('opacity-100');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        function closeSidebarOnMobile() {
            if (window.innerWidth < 768) {
                toggleSidebar();
            }
        }

        // 1. Logika View SPA
        function showView(viewId) {
            document.querySelectorAll('.view-section').forEach(view => view.classList.remove('active'));
            document.getElementById('view-' + viewId).classList.add('active');

            const titleEl = document.getElementById('view-title');
            const breadcrumbEl = document.getElementById('breadcrumb-active');

            document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));

            if (viewId === 'dashboard') {
                titleEl.innerText = "Monitoring Kedisiplinan";
                breadcrumbEl.innerText = "Ringkasan";
                document.getElementById('nav-dashboard').classList.add('active');
            } else if (viewId === 'profile') {
                titleEl.innerText = "Profil Pimpinan";
                breadcrumbEl.innerText = "Home / Profil";
            }
        }

        // 2. Logika Toggle View Profil vs Form Edit
        function toggleEditProfile(showForm) {
            const view = document.getElementById('profileView');
            const form = document.getElementById('profileForm');

            if (showForm) {
                view.classList.add('hidden');
                view.classList.remove('flex');
                form.classList.remove('hidden');
                form.classList.add('flex');
            } else {
                view.classList.remove('hidden');
                view.classList.add('flex');
                form.classList.add('hidden');
                form.classList.remove('flex');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // 3. Logika Chart.js
            const ctxBesar = document.getElementById('bigChart');
            if (ctxBesar) {
                const chartLabels = {!! json_encode($chartLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']) !!};
                const chartData = {!! json_encode($chartData ?? [10, 25, 15, 30, 20, 10]) !!};

                new Chart(ctxBesar.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'Jumlah Pelanggaran',
                            data: chartData,
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                            borderWidth: 0,
                            barThickness: window.innerWidth < 768 ? 15 :
                                undefined // Sesuaikan ketebalan bar di HP
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
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
                                },
                                ticks: {
                                    maxRotation: 45, // Putar label jika di HP agar tidak tertumpuk
                                    minRotation: 0
                                }
                            }
                        }
                    }
                });
            }

            // 4. Logika Dropdown Profil
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

            // 5. Logika Submit Form Profil via AJAX
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
