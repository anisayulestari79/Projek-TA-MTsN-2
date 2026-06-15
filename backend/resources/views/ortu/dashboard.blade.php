<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <!-- Meta Viewport Penting untuk Responsif -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard Orang Tua - Kondisi Anak</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .sidebar-item.active {
            background-color: white;
            color: #10b981;
            border-radius: 10px 0 0 10px;
            font-weight: 800;
        }

        .circle-progress {
            transition: stroke-dashoffset 1s ease-out;
        }

        .view-section {
            display: none;
        }

        .view-section.active {
            display: block;
        }

        /* Custom scrollbar for better mobile feel */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-[#f4f7f6] font-sans flex text-gray-800 overflow-x-hidden">

    <!-- OVERLAY UNTUK MOBILE SIDEBAR -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity" onclick="toggleSidebar()">
    </div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="w-72 min-h-screen bg-[#10b981] text-white flex flex-col fixed shadow-xl z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="p-6 md:p-8 border-b border-white/10 relative">
            <button onclick="toggleSidebar()" class="md:hidden absolute top-4 right-4 text-white/80 hover:text-white">
                <i class="fas fa-times text-2xl"></i>
            </button>
            <div class="flex items-center gap-3 mb-2 mt-2 md:mt-0">
                <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                    class="w-10 drop-shadow-md" alt="Logo Kemenag">
                <h1 class="font-bold text-lg md:text-xl leading-tight tracking-tight uppercase">Monitoring <br> Wali
                    Murid</h1>
            </div>
            <p class="text-[10px] opacity-80 font-bold tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
        </div>

        <nav class="mt-6 flex-grow pl-6 space-y-1">
            <a href="#" onclick="showView('dashboard'); closeSidebarOnMobile();" id="nav-dashboard"
                class="sidebar-item active flex items-center px-6 py-4 transition shadow-sm duration-300">
                <i class="fas fa-child mr-4 text-lg opacity-80"></i> <span class="font-medium tracking-wide">Kondisi
                    Anak</span>
            </a>
            <a href="{{ route('ortu.konsultasi') }}" id="nav-konsultasi"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl duration-300">
                <i class="fas fa-comments mr-4 text-lg opacity-80"></i> <span
                    class="font-medium tracking-wide">Konsultasi BK</span>
            </a>
            <div class="mt-auto pt-20 pb-10">
                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">@csrf</form>
            </div>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 md:ml-72 p-4 md:p-10 min-h-screen flex flex-col w-full transition-all duration-300 relative">

        @php
            $poinAktif = $siswaAktif->poin ?? 0;
            $persenPoin = min($poinAktif / 100, 1);
            $dashoffset = 477 - 477 * $persenPoin;

            $statusWarna = 'green';
            $statusTeks = 'Aman & Disiplin';
            if ($poinAktif >= 100) {
                $statusWarna = 'red';
                $statusTeks = 'Dikeluarkan (DO)';
            } elseif ($poinAktif >= 50) {
                $statusWarna = 'orange';
                $statusTeks = 'Panggilan Tahap II';
            } elseif ($poinAktif >= 25) {
                $statusWarna = 'yellow';
                $statusTeks = 'Waspada Panggilan I';
            }

            $user = Auth::user();
        @endphp

        <!-- GLOBAL HEADER -->
        <header class="flex justify-between items-center mb-6 md:mb-10 mt-2 md:mt-0">
            <div class="flex items-center gap-3 md:gap-4">
                <button onclick="toggleSidebar()"
                    class="md:hidden text-gray-600 hover:text-[#10b981] focus:outline-none shrink-0">
                    <i class="fas fa-bars text-xl bg-white p-2 rounded-lg shadow-sm border border-gray-100"></i>
                </button>
                <div>
                    <nav
                        class="text-[9px] md:text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1 hidden md:block">
                        Home / <span id="breadcrumb-active" class="text-gray-600">Kondisi Anak</span>
                    </nav>
                    <h2 id="view-title"
                        class="text-xl md:text-3xl font-black text-gray-700 uppercase tracking-tighter italic leading-none">
                        Kondisi Anak</h2>
                </div>
            </div>

            <div class="relative shrink-0">
                <!-- Tombol Profile (Responsif) -->
                <button id="profileDropdownBtn"
                    class="flex items-center gap-2 md:gap-4 bg-white p-1.5 pr-3 md:px-6 md:py-2.5 rounded-full shadow-sm border border-gray-100 transition hover:shadow-md focus:outline-none">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] md:text-xs font-black text-[#10b981] capitalize leading-none">
                            {{ $user->name ?? 'Wali Murid' }}</p>
                        <p class="text-[9px] md:text-[10px] text-gray-400 font-bold uppercase mt-1">Wali Dari
                            {{ isset($daftarAnak) ? count($daftarAnak) : 0 }} Anak</p>
                    </div>
                    <img src="{{ isset($user->photo) && $user->photo ? (filter_var($user->photo, FILTER_VALIDATE_URL) ? $user->photo : asset('storage/' . $user->photo)) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'Wali Murid') . '&background=10b981&color=fff' }}"
                        class="w-8 h-8 md:w-10 md:h-10 rounded-full border-2 border-green-50 shadow-sm object-cover"
                        alt="Profile">
                    <i class="fas fa-chevron-down text-gray-400 text-xs ml-1 md:ml-0"></i>
                </button>

                <div id="profileDropdownMenu"
                    class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden z-50 transform origin-top-right transition-all duration-200 opacity-0 scale-95">
                    <div class="py-2">
                        <!-- LINK INLINE SPA KE VIEW PROFILE DI BAWAH -->
                        <button type="button"
                            onclick="showView('profile'); document.getElementById('profileDropdownMenu').classList.add('hidden');"
                            class="w-full text-left px-6 py-3 text-xs font-bold text-gray-700 hover:bg-green-50 hover:text-[#10b981] transition flex items-center gap-3">
                            <i class="fas fa-user-circle"></i> Profil Anda
                        </button>

                        @if (isset($daftarAnak) && count($daftarAnak) > 0)
                            <button type="button"
                                onclick="showView('profil-siswa'); document.getElementById('profileDropdownMenu').classList.add('hidden');"
                                class="w-full text-left px-6 py-3 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-3">
                                <i class="fas fa-user-graduate"></i> Profil Anak
                            </button>
                        @endif
                        <div class="border-t border-gray-100 my-1"></div>
                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="block w-full text-left px-6 py-3 text-xs font-bold text-red-600 hover:bg-red-50 transition flex items-center gap-3">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Custom Alert Container (Pengganti window.alert) -->
        <div id="customAlertBox"
            class="hidden fixed top-5 left-1/2 transform -translate-x-1/2 z-[100] w-[90%] max-w-sm">
            <div id="customAlertContent"
                class="bg-white rounded-2xl shadow-2xl border-l-4 p-4 flex items-start gap-3 transition-all duration-300 translate-y-[-20px] opacity-0">
                <div id="customAlertIcon" class="mt-0.5 text-lg"></div>
                <div class="flex-1">
                    <h4 id="customAlertTitle" class="text-sm font-black text-gray-800 mb-0.5">Notifikasi</h4>
                    <p id="customAlertMessage" class="text-xs text-gray-600 font-medium"></p>
                </div>
                <button onclick="hideCustomAlert()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times"></i></button>
            </div>
        </div>

        @if (session('success'))
            <div
                class="bg-green-100 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-6 md:mb-8 shadow-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-lg md:text-xl"></i>
                <span class="font-bold text-xs md:text-sm tracking-wide">{{ session('success') }}</span>
            </div>
        @endif

        @if (isset($siswaAktif) && $poinAktif >= 25)
            <div id="peringatanPoin"
                class="bg-red-50 border-l-4 border-red-500 p-4 md:p-5 mb-6 md:mb-8 rounded-r-2xl shadow-md flex justify-between items-start md:items-center relative animate-pulse">
                <div class="flex items-start md:items-center gap-3 md:gap-4">
                    <div
                        class="w-10 h-10 md:w-12 md:h-12 bg-red-100 text-red-500 rounded-full flex items-center justify-center text-xl shrink-0">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-red-700 text-sm md:text-base tracking-wide">PEMBERITAHUAN PENTING!
                        </h4>
                        <p class="text-xs md:text-sm text-red-600 font-medium mt-1">
                            Ananda <strong class="capitalize">{{ $siswaAktif->nama }}</strong> saat ini masuk ke dalam
                            <strong class="bg-red-200 px-2 py-0.5 rounded text-red-800">
                                @if ($poinAktif >= 100)
                                    Tahap Drop Out (DO)
                                @elseif($poinAktif >= 50)
                                    Tahap Panggilan Ortu II
                                @else
                                    Tahap Panggilan Ortu I
                                @endif
                            </strong>
                            karena memiliki {{ $poinAktif }} Poin.
                        </p>
                    </div>
                </div>
                <button onclick="document.getElementById('peringatanPoin').style.display='none'"
                    class="text-red-400 hover:text-red-600 p-1"><i class="fas fa-times text-lg"></i></button>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- VIEW: DASHBOARD KONDISI ANAK -->
        <!-- ============================================== -->
        <div id="view-dashboard" class="view-section active">
            @if (isset($daftarAnak) && count($daftarAnak) > 1)
                <div class="mb-6 md:mb-8">
                    <h3 class="font-black text-gray-400 text-[10px] uppercase tracking-widest mb-3">Pilih Anak Anda
                    </h3>
                    <div class="flex gap-3 md:gap-4 overflow-x-auto pb-2 scrollbar-hide">
                        @foreach ($daftarAnak as $anak)
                            <a href="?siswa_id={{ $anak->id }}"
                                class="flex items-center gap-2 md:gap-3 px-4 md:px-6 py-2 md:py-3 rounded-2xl shadow-sm border transition whitespace-nowrap {{ isset($siswaAktif) && $siswaAktif->id == $anak->id ? 'bg-[#10b981] text-white border-green-500' : 'bg-white text-gray-600 border-gray-100 hover:bg-gray-50' }}">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($anak->nama) }}&background={{ isset($siswaAktif) && $siswaAktif->id == $anak->id ? 'fff' : '10b981' }}&color={{ isset($siswaAktif) && $siswaAktif->id == $anak->id ? '10b981' : 'fff' }}"
                                    class="w-6 h-6 md:w-8 md:h-8 rounded-full border-2 {{ isset($siswaAktif) && $siswaAktif->id == $anak->id ? 'border-white/50' : 'border-green-50' }}">
                                <div class="text-left">
                                    <p class="text-[10px] md:text-xs font-bold capitalize tracking-wider">
                                        {{ $anak->nama }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mb-6 md:mb-10">
                @if (isset($siswaAktif) && $siswaAktif)
                    <div
                        class="bg-white p-6 md:p-10 rounded-3xl md:rounded-[40px] shadow-sm border border-gray-50 flex flex-col md:flex-row items-center gap-6 md:gap-12 relative overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-64 h-64 bg-gray-50 rounded-full opacity-50 blur-3xl">
                        </div>
                        <div class="relative flex items-center justify-center shrink-0 z-10 w-32 h-32 md:w-44 md:h-44">
                            <svg class="w-full h-full transform -rotate-90">
                                <circle class="text-gray-100" stroke-width="12" stroke="currentColor"
                                    fill="transparent" r="45%" cx="50%" cy="50%" />
                                <circle class="text-{{ $statusWarna }}-500 circle-progress" stroke-width="12"
                                    stroke-dasharray="283" stroke-dashoffset="{{ 283 - 283 * $persenPoin }}"
                                    stroke-linecap="round" stroke="currentColor" fill="transparent" r="45%"
                                    cx="50%" cy="50%" />
                            </svg>
                            <div class="absolute flex flex-col items-center">
                                <span
                                    class="text-3xl md:text-5xl font-black text-gray-800 leading-none">{{ $poinAktif }}</span>
                                <span
                                    class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1 md:mt-2">Poin</span>
                            </div>
                        </div>

                        <div class="flex-1 relative z-10 text-center md:text-left w-full">
                            <div class="flex flex-wrap gap-2 mb-4 justify-center md:justify-start">
                                <div
                                    class="inline-block px-3 py-1 md:px-4 md:py-1.5 bg-{{ $statusWarna }}-100 text-{{ $statusWarna }}-700 rounded-full text-[9px] md:text-[10px] font-black uppercase tracking-widest border border-{{ $statusWarna }}-200">
                                    Status: {{ $statusTeks }}
                                </div>
                                @php
                                    $statusPendidikan = $siswaAktif->status ?? 'Aktif';
                                    $bgStatus =
                                        $statusPendidikan == 'Aktif'
                                            ? 'bg-blue-100 text-blue-700 border-blue-200'
                                            : ($statusPendidikan == 'Lulus'
                                                ? 'bg-indigo-100 text-indigo-700 border-indigo-200'
                                                : 'bg-red-100 text-red-700 border-red-200');
                                @endphp
                                <div
                                    class="inline-block px-3 py-1 md:px-4 md:py-1.5 {{ $bgStatus }} rounded-full text-[9px] md:text-[10px] font-black uppercase tracking-widest border">
                                    Siswa {{ $statusPendidikan }}
                                </div>
                            </div>
                            <h3 class="text-xl md:text-2xl font-black text-gray-700 tracking-tight mb-2 md:mb-3">
                                <span class="uppercase">Laporan</span> <span
                                    class="text-[#10b981] capitalize">{{ $siswaAktif->nama }}</span>
                            </h3>
                            <p
                                class="text-gray-500 text-xs md:text-sm font-medium leading-relaxed mb-6 max-w-lg mx-auto md:mx-0">
                                Saat ini memiliki <strong class="text-{{ $statusWarna }}-600">{{ $poinAktif }}
                                    Poin
                                    Pelanggaran</strong>.
                                <br><br>
                                @if ($poinAktif >= 100)
                                    <span class="text-red-500 font-bold">Batas poin terlampaui. Segera ke ruang
                                        BK.</span>
                                @elseif($poinAktif >= 25)
                                    Mohon bimbingan disiplin ananda di rumah diperketat.
                                @else
                                    Perilaku terpantau baik. Pertahankan!
                                @endif
                            </p>
                            <button type="button" onclick="showView('detail-riwayat')"
                                class="w-full md:w-auto inline-flex bg-[#10b981] hover:bg-green-600 transition text-white px-6 py-3 rounded-2xl text-[10px] md:text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 items-center justify-center cursor-pointer">
                                <i class="fas fa-list-ul mr-2"></i> Lihat Riwayat Pelanggaran
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 md:mt-10">
                        <h3
                            class="font-black text-gray-700 text-xs md:text-sm uppercase tracking-widest mb-4 md:mb-6 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-3 text-red-500"></i> Peringatan Sanksi
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 relative">
                            <!-- Tahap I -->
                            <div
                                class="p-6 md:p-8 rounded-3xl md:rounded-[30px] {{ $poinAktif >= 25 ? 'bg-red-50 border border-red-200 shadow-sm' : 'bg-white border border-gray-100 opacity-80' }} relative overflow-hidden flex flex-col justify-between transition-all">
                                <div class="relative z-10">
                                    <div class="flex justify-between items-center mb-4 md:mb-6">
                                        <p
                                            class="text-[9px] md:text-[10px] font-black {{ $poinAktif >= 25 ? 'text-red-600' : 'text-gray-400' }} uppercase tracking-widest">
                                            Tahap I</p>
                                        <span
                                            class="{{ $poinAktif >= 25 ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-500' }} px-2 md:px-3 py-1 rounded-lg font-black text-[9px] md:text-[10px]">Batas
                                            25 Poin</span>
                                    </div>
                                    <h4
                                        class="text-base md:text-lg font-black {{ $poinAktif >= 25 ? 'text-red-800' : 'text-gray-700' }} uppercase mb-1 md:mb-2 leading-tight">
                                        Panggilan Ortu I</h4>
                                    <p
                                        class="text-[10px] md:text-xs {{ $poinAktif >= 25 ? 'text-red-600' : 'text-gray-500' }} font-medium">
                                        Pembinaan intensif siswa.</p>
                                </div>
                                <div
                                    class="mt-4 md:mt-8 pt-4 border-t {{ $poinAktif >= 25 ? 'border-red-200/50' : 'border-gray-100' }} relative z-10 flex items-center gap-2">
                                    @if ($poinAktif >= 25)
                                        <i class="fas fa-times-circle text-red-500 text-base md:text-lg"></i> <span
                                            class="text-[9px] md:text-[10px] font-bold text-red-700 uppercase tracking-wider">Telah
                                            Terlewati</span>
                                    @else
                                        <i class="fas fa-check-circle text-green-500 text-base md:text-lg"></i> <span
                                            class="text-[9px] md:text-[10px] font-bold text-green-600 uppercase tracking-wider">Status
                                            Aman</span>
                                    @endif
                                </div>
                            </div>
                            <!-- Tahap II -->
                            <div
                                class="p-6 md:p-8 rounded-3xl md:rounded-[30px] {{ $poinAktif >= 50 ? 'bg-red-50 border border-red-200 shadow-sm' : ($poinAktif >= 25 ? 'bg-white border-2 border-yellow-400 shadow-lg' : 'bg-white border border-gray-100 opacity-80') }} relative overflow-hidden flex flex-col justify-between transition-all">
                                <div class="relative z-10">
                                    <div class="flex justify-between items-center mb-4 md:mb-6">
                                        <p
                                            class="text-[9px] md:text-[10px] font-black {{ $poinAktif >= 50 ? 'text-red-600' : ($poinAktif >= 25 ? 'text-yellow-600' : 'text-gray-400') }} uppercase tracking-widest">
                                            Tahap II</p>
                                        <span
                                            class="{{ $poinAktif >= 50 ? 'bg-red-600 text-white' : ($poinAktif >= 25 ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-500') }} px-2 md:px-3 py-1 rounded-lg font-black text-[9px] md:text-[10px]">Batas
                                            50 Poin</span>
                                    </div>
                                    <h4
                                        class="text-base md:text-lg font-black {{ $poinAktif >= 50 ? 'text-red-800' : 'text-gray-800' }} uppercase mb-1 md:mb-2 leading-tight">
                                        Panggilan Ortu II</h4>
                                    <p
                                        class="text-[10px] md:text-xs {{ $poinAktif >= 50 ? 'text-red-600' : 'text-gray-500' }} font-medium">
                                        Surat perjanjian & skorsing.</p>
                                </div>
                                @if ($poinAktif >= 50)
                                    <div
                                        class="mt-4 md:mt-8 pt-4 border-t border-red-200/50 relative z-10 flex items-center gap-2">
                                        <i class="fas fa-times-circle text-red-500 text-base md:text-lg"></i> <span
                                            class="text-[9px] md:text-[10px] font-bold text-red-700 uppercase tracking-wider">Telah
                                            Terlewati</span>
                                    </div>
                                @elseif($poinAktif >= 25)
                                    <div
                                        class="mt-4 md:mt-8 bg-yellow-50 p-3 md:p-4 rounded-xl md:rounded-2xl border border-yellow-200 relative z-10 flex items-center justify-between">
                                        <div><span
                                                class="block text-[8px] md:text-[9px] font-black text-yellow-600 uppercase tracking-wider mb-0.5 md:mb-1">Ambang
                                                Terdekat</span></div>
                                        <span
                                            class="text-sm md:text-xl font-black text-red-500 animate-pulse">{{ 50 - $poinAktif }}
                                            Poin</span>
                                    </div>
                                @else
                                    <div
                                        class="mt-4 md:mt-8 pt-4 border-t border-gray-100 relative z-10 flex items-center gap-2">
                                        <i class="fas fa-shield-alt text-green-500 text-base md:text-lg"></i> <span
                                            class="text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-wider">Jarak
                                            Masih Aman</span>
                                    </div>
                                @endif
                            </div>
                            <!-- Tahap III -->
                            <div
                                class="p-6 md:p-8 rounded-3xl md:rounded-[30px] {{ $poinAktif >= 100 ? 'bg-red-900 border-red-900 shadow-xl' : ($poinAktif >= 50 ? 'bg-white border-2 border-orange-400 shadow-lg' : 'bg-white border border-gray-100 opacity-80') }} relative overflow-hidden flex flex-col justify-between transition-all sm:col-span-2 lg:col-span-1">
                                <div class="relative z-10">
                                    <div class="flex justify-between items-center mb-4 md:mb-6">
                                        <p
                                            class="text-[9px] md:text-[10px] font-black {{ $poinAktif >= 100 ? 'text-red-300' : ($poinAktif >= 50 ? 'text-orange-600' : 'text-gray-400') }} uppercase tracking-widest">
                                            Tahap III</p>
                                        <span
                                            class="{{ $poinAktif >= 100 ? 'bg-red-500 text-white' : ($poinAktif >= 50 ? 'bg-orange-500 text-white' : 'bg-gray-800 text-white') }} px-2 md:px-3 py-1 rounded-lg font-black text-[9px] md:text-[10px]">Batas
                                            100 Poin</span>
                                    </div>
                                    <h4
                                        class="text-base md:text-lg font-black {{ $poinAktif >= 100 ? 'text-white' : 'text-gray-800' }} uppercase mb-1 md:mb-2 leading-tight">
                                        Dikeluarkan <br> Dari Sekolah</h4>
                                </div>
                                @if ($poinAktif >= 100)
                                    <div
                                        class="mt-4 md:mt-8 pt-4 border-t border-red-700 relative z-10 flex items-center gap-2">
                                        <i class="fas fa-ban text-red-400 text-base md:text-lg"></i> <span
                                            class="text-[9px] md:text-[10px] font-bold text-red-200 uppercase tracking-wider">Drop
                                            Out Terjadi</span>
                                    </div>
                                @elseif($poinAktif >= 50)
                                    <div
                                        class="mt-4 md:mt-8 bg-orange-50 p-3 md:p-4 rounded-xl md:rounded-2xl border border-orange-200 relative z-10 flex items-center justify-between">
                                        <div><span
                                                class="block text-[8px] md:text-[9px] font-black text-orange-600 uppercase tracking-wider mb-0.5 md:mb-1">Sanksi
                                                Keras Terakhir</span></div>
                                        <span
                                            class="text-sm md:text-xl font-black text-red-600 animate-pulse">{{ 100 - $poinAktif }}
                                            Poin</span>
                                    </div>
                                @else
                                    <div
                                        class="mt-4 md:mt-8 pt-4 border-t border-gray-100 relative z-10 flex items-center gap-2">
                                        <i class="fas fa-shield-alt text-green-500 text-base md:text-lg"></i> <span
                                            class="text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-wider">Jarak
                                            Masih Aman</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div
                        class="bg-white p-8 md:p-12 rounded-[30px] md:rounded-[40px] shadow-sm border border-gray-50 text-center flex flex-col items-center justify-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6"><i
                                class="fas fa-user-slash text-gray-300 text-4xl"></i></div>
                        <h3 class="text-xl font-black text-gray-700 mb-2">Belum Ada Data Anak</h3>
                        <p class="text-gray-500 text-sm max-w-sm mx-auto">Akun Anda belum dikaitkan dengan profil siswa
                            mana pun.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ============================================== -->
        <!-- VIEW: DETAIL RIWAYAT POIN -->
        <!-- ============================================== -->
        <div id="view-detail-riwayat" class="view-section">
            <div class="bg-white p-6 md:p-8 rounded-3xl md:rounded-[30px] shadow-sm border border-gray-50">
                <div
                    class="flex flex-col md:flex-row justify-between md:items-center mb-6 md:mb-8 border-b pb-4 md:pb-6 gap-4">
                    <div>
                        <h3 class="font-black text-gray-700 text-base md:text-lg uppercase tracking-widest">Detail
                            Riwayat Pelanggaran</h3>
                        <p class="text-[10px] md:text-xs text-gray-400 mt-1">Rekapitulasi poin dan sanksi ananda
                            tercinta</p>
                    </div>
                    <button type="button" onclick="showView('dashboard')"
                        class="w-full md:w-auto justify-center bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl text-xs font-bold uppercase hover:bg-gray-200 transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                </div>

                @if (isset($siswaAktif))
                    <div
                        class="bg-blue-50/50 p-4 md:p-6 rounded-2xl border border-blue-100 mb-6 md:mb-8 grid grid-cols-2 md:grid-cols-4 gap-4 text-center md:text-left">
                        <div class="bg-white/60 p-3 rounded-xl border border-white shadow-sm">
                            <p class="text-[9px] md:text-[10px] text-blue-500 font-bold uppercase tracking-widest">Nama
                                Anak</p>
                            <p class="text-xs md:text-sm font-black text-gray-800 capitalize mt-1">
                                {{ $siswaAktif->nama }}</p>
                        </div>
                        <div class="bg-white/60 p-3 rounded-xl border border-white shadow-sm">
                            <p class="text-[9px] md:text-[10px] text-blue-500 font-bold uppercase tracking-widest">NISN
                            </p>
                            <p class="text-xs md:text-sm font-black text-gray-800 uppercase mt-1">
                                {{ $siswaAktif->nisn }}</p>
                        </div>
                        <div class="bg-white/60 p-3 rounded-xl border border-white shadow-sm">
                            <p class="text-[9px] md:text-[10px] text-blue-500 font-bold uppercase tracking-widest">
                                Kelas</p>
                            <p class="text-xs md:text-sm font-black text-gray-800 uppercase mt-1">
                                {{ $siswaAktif->kelas }}</p>
                        </div>
                        <div class="bg-white/60 p-3 rounded-xl border border-white shadow-sm">
                            <p class="text-[9px] md:text-[10px] text-blue-500 font-bold uppercase tracking-widest">
                                Total Poin</p>
                            <p class="text-lg md:text-xl font-black text-red-600 uppercase mt-1">
                                {{ $siswaAktif->poin }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse min-w-[500px] md:min-w-[600px]">
                            <thead
                                class="bg-[#005c4b] text-white text-[9px] md:text-[10px] font-black uppercase tracking-widest">
                                <tr>
                                    <th class="py-3 md:py-4 pl-4 md:pl-6 rounded-tl-xl">Tanggal & Waktu</th>
                                    <th class="py-3 md:py-4">Jenis Pelanggaran</th>
                                    <th class="py-3 md:py-4 text-center">Poin</th>
                                    <th class="py-3 md:py-4 pr-4 md:pr-6 rounded-tr-xl">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-riwayat-body" class="text-[10px] md:text-xs divide-y divide-gray-100">
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-400 font-bold"><i
                                            class="fas fa-spinner fa-spin mr-2"></i> Memuat data riwayat...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- ============================================== -->
        <!-- VIEW: PROFIL SISWA (DAFTAR ANAK) -->
        <!-- ============================================== -->
        @if (isset($daftarAnak) && count($daftarAnak) > 0)
            <div id="view-profil-siswa" class="view-section">
                <div class="flex justify-between items-center mb-6 md:mb-8 border-b border-gray-100 pb-4">
                    <h3 class="text-base md:text-lg font-black text-gray-700 uppercase tracking-widest">Profil Anak
                    </h3>
                    <button type="button" onclick="showView('dashboard')"
                        class="text-gray-400 hover:text-red-500 transition"><i
                            class="fas fa-times text-xl"></i></button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($daftarAnak as $anak)
                        <div
                            class="bg-white p-6 rounded-3xl md:rounded-[30px] shadow-sm border border-gray-50 flex flex-col items-center relative">
                            <div
                                class="w-16 h-16 md:w-20 md:h-20 shrink-0 rounded-2xl overflow-hidden border-2 border-blue-50 shadow-sm mb-4">
                                @if ($anak->photo)
                                    <img src="{{ filter_var($anak->photo, FILTER_VALIDATE_URL) ? $anak->photo : asset('storage/' . $anak->photo) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($anak->nama) }}&background=2563eb&color=fff&size=120"
                                        class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="w-full text-center">
                                <h4 class="font-black text-gray-800 text-xs md:text-sm capitalize leading-tight mb-1">
                                    {{ $anak->nama }}</h4>
                                <p
                                    class="text-[9px] md:text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-4">
                                    Kelas {{ $anak->kelas }}</p>
                                <div class="space-y-2 text-left">
                                    <div
                                        class="flex justify-between items-center text-[10px] md:text-xs border-b border-gray-50 pb-2">
                                        <span
                                            class="text-gray-400 font-bold uppercase text-[8px] md:text-[9px]">NISN</span><span
                                            class="font-black text-gray-700">{{ $anak->nisn }}</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center text-[10px] md:text-xs border-b border-gray-50 pb-2">
                                        <span
                                            class="text-gray-400 font-bold uppercase text-[8px] md:text-[9px]">Gender</span><span
                                            class="font-black text-gray-700">{{ $anak->jk ?? '-' }}</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-start text-[10px] md:text-xs border-b border-gray-50 pb-2">
                                        <span
                                            class="text-gray-400 font-bold uppercase text-[8px] md:text-[9px] mt-0.5">Alamat</span><span
                                            class="font-black text-gray-700 text-right max-w-[60%] leading-snug">{{ !empty($anak->alamat) ? $anak->alamat : $user->alamat ?? 'Belum diisi' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <button onclick="openTambahAnakModal()"
                        class="border-2 border-dashed border-gray-300 rounded-3xl md:rounded-[30px] p-6 flex flex-col items-center justify-center hover:bg-gray-50 hover:border-[#10b981] transition text-gray-400 hover:text-[#10b981] min-h-[250px] md:min-h-[300px] group">
                        <div
                            class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-gray-100 group-hover:bg-green-50 flex items-center justify-center mb-3 md:mb-4 transition">
                            <i class="fas fa-user-plus text-xl md:text-2xl"></i>
                        </div>
                        <span class="font-black text-xs md:text-sm uppercase tracking-widest">Kaitkan Anak Lain</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- VIEW: PROFILE PENGGUNA (ORANG TUA) -->
        <!-- ============================================== -->
        <div id="view-profile" class="view-section">
            <div
                class="bg-white p-6 md:p-8 rounded-[30px] shadow-sm border border-gray-50 max-w-md mx-auto w-full relative">
                <div class="flex flex-col items-center mb-6">
                    <div
                        class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-green-50 shadow-md flex items-center justify-center bg-[#10b981] text-white text-3xl font-black mb-4 overflow-hidden relative group">
                        <img src="{{ isset($user->photo) && $user->photo ? (filter_var($user->photo, FILTER_VALIDATE_URL) ? $user->photo : asset('storage/' . $user->photo)) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'Wali Murid') . '&background=10b981&color=fff' }}"
                            class="w-full h-full object-cover" id="mainProfilePic">
                    </div>
                    <h3 class="text-lg md:text-xl font-black text-gray-800 capitalize text-center leading-tight">
                        {{ $user->name ?? 'Wali Murid' }}</h3>
                    <p class="text-[10px] md:text-xs font-bold text-[#10b981] uppercase tracking-widest mt-1">ORANG TUA
                        / WALI SISWA</p>
                </div>

                <div class="space-y-3 w-full">
                    <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-4 border border-gray-100">
                        <div class="w-8 flex justify-center"><i class="fas fa-id-badge text-[#10b981] text-lg"></i>
                        </div>
                        <div class="truncate">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Username / Email
                            </p>
                            <p class="text-sm font-black text-gray-700 truncate">
                                {{ $user->username ?? ($user->email ?? '-') }}</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-4 border border-gray-100">
                        <div class="w-8 flex justify-center"><i class="fas fa-phone-alt text-[#10b981] text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">No. Telepon</p>
                            <p class="text-sm font-black text-gray-700">{{ $user->phone ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-4 border border-gray-100">
                        <div class="w-8 flex justify-center"><i class="fas fa-briefcase text-[#10b981] text-lg"></i>
                        </div>
                        <div class="truncate">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Pekerjaan</p>
                            <p class="text-sm font-black text-gray-700 truncate">{{ $user->pekerjaan ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl flex items-start gap-4 border border-gray-100">
                        <div class="w-8 flex justify-center mt-1"><i
                                class="fas fa-map-marker-alt text-[#10b981] text-lg"></i></div>
                        <div class="flex-1">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Alamat Lengkap</p>
                            <p class="text-sm font-black text-gray-700 leading-snug">{{ $user->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <button onclick="document.getElementById('editModal').classList.remove('hidden')"
                        class="flex-1 bg-[#10b981] text-white py-3 rounded-xl font-bold text-[10px] md:text-xs uppercase tracking-wider shadow-lg shadow-green-100 hover:bg-green-600 transition flex items-center justify-center gap-2"><i
                            class="fas fa-user-edit"></i> Edit Profil</button>
                    <button onclick="showView('dashboard')"
                        class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold text-[10px] md:text-xs uppercase tracking-wider hover:bg-gray-200 transition flex items-center justify-center gap-2 border border-gray-200"><i
                            class="fas fa-arrow-left"></i> Ke Dashboard</button>
                </div>
            </div>
        </div>
    </main>

    <!-- MODAL EDIT PROFIL ORTU -->
    <div id="editModal"
        class="fixed inset-0 bg-gray-900/80 hidden z-[60] flex items-center justify-center backdrop-blur-sm p-4">
        <div
            class="bg-white rounded-[30px] p-6 md:p-8 max-w-md w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <button onclick="document.getElementById('editModal').classList.add('hidden')"
                class="absolute top-6 right-6 text-gray-400 hover:text-red-500"><i
                    class="fas fa-times text-xl"></i></button>
            <h3 class="text-lg font-black text-gray-800 uppercase tracking-widest mb-6 border-b pb-4">Edit Data Diri
            </h3>

            <form id="profileForm" action="{{ route('profile.update') }}" method="POST"
                enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Unggah Foto
                        Baru</label>
                    <input type="file" name="photo" accept="image/*"
                        class="w-full text-xs file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-50 file:text-[#10b981] border border-gray-100 rounded-xl bg-gray-50">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Nama
                        Lengkap</label>
                    <input type="text" name="name" value="{{ $user->name ?? '' }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#10b981]">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Gender</label>
                    <select name="gender"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#10b981]">
                        <option value="Laki-laki" {{ ($user->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki</option>
                        <option value="Perempuan" {{ ($user->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">No.
                        Telepon</label>
                    <input type="tel" name="phone" value="{{ $user->phone ?? '' }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#10b981]">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Pekerjaan</label>
                    <input type="text" name="pekerjaan" value="{{ $user->pekerjaan ?? '' }}"
                        placeholder="Contoh: Wiraswasta"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#10b981]">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Alamat
                        Lengkap</label>
                    <textarea name="alamat" rows="2" placeholder="Contoh: Jl. Mawar No. 5"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#10b981] resize-none">{{ $user->alamat ?? '' }}</textarea>
                </div>
                <div class="pt-4 mt-2 border-t border-gray-100">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Ganti
                        Password (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#10b981] transition">
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-[#10b981] text-white py-3 rounded-xl text-xs font-bold uppercase shadow-lg shadow-green-100 hover:bg-green-600 transition flex items-center justify-center"><i
                            class="fas fa-save mr-2"></i> Simpan</button>
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl text-xs font-bold uppercase hover:bg-gray-200 transition flex items-center justify-center">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL TAMBAH ANAK LAIN -->
    <div id="tambahAnakModal" class="fixed inset-0 bg-black/50 hidden z-[60] flex items-center justify-center p-4">
        <div class="bg-white rounded-[30px] w-full max-w-md overflow-hidden shadow-2xl p-6 md:p-8 relative">
            <button onclick="closeTambahAnakModal()"
                class="absolute top-4 right-4 md:top-6 md:right-6 text-gray-400 hover:text-red-500 transition"><i
                    class="fas fa-times text-xl"></i></button>
            <div class="text-center mb-6">
                <div
                    class="w-12 h-12 md:w-16 md:h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-xl md:text-2xl mx-auto mb-4">
                    <i class="fas fa-link"></i>
                </div>
                <h3 class="font-black text-gray-800 text-lg md:text-xl">Kaitkan Anak Lain</h3>
                <p class="text-[10px] md:text-xs text-gray-500 mt-2 px-2 md:px-4">Masukkan NISN anak Anda yang lain
                    agar profilnya terhubung dengan akun ini.</p>
            </div>
            <form action="{{ route('ortu.tambah-anak') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label
                        class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">NISN
                        Anak (10 Angka)</label>
                    <div class="relative">
                        <i class="fas fa-id-card absolute left-4 top-[14px] text-gray-400 text-xs md:text-sm"></i>
                        <input type="text" name="nisn_tambahan" required maxlength="10"
                            placeholder="Masukkan NISN Anak"
                            class="w-full pl-10 pr-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-blue-100 transition">
                    </div>
                </div>
                <button type="submit"
                    class="w-full bg-blue-500 text-white px-6 py-3 md:py-3.5 rounded-xl text-[10px] md:text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-100 hover:bg-blue-600 transition flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Hubungkan Sekarang
                </button>
            </form>
        </div>
    </div>

    <!-- SCRIPT RESPONSIVE & FUNGSI -->
    <script>
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

        function openTambahAnakModal() {
            document.getElementById('tambahAnakModal').classList.remove('hidden');
        }

        function closeTambahAnakModal() {
            document.getElementById('tambahAnakModal').classList.add('hidden');
        }

        function showCustomAlert(title, message, isSuccess = true) {
            const alertBox = document.getElementById('customAlertBox');
            const content = document.getElementById('customAlertContent');
            const icon = document.getElementById('customAlertIcon');

            document.getElementById('customAlertTitle').innerText = title;
            document.getElementById('customAlertMessage').innerText = message;

            if (isSuccess) {
                content.className =
                    'bg-white rounded-2xl shadow-2xl border-l-4 border-[#10b981] p-4 flex items-start gap-3 transition-all duration-300 translate-y-[-20px] opacity-0';
                icon.innerHTML = '<i class="fas fa-check-circle text-[#10b981]"></i>';
            } else {
                content.className =
                    'bg-white rounded-2xl shadow-2xl border-l-4 border-red-500 p-4 flex items-start gap-3 transition-all duration-300 translate-y-[-20px] opacity-0';
                icon.innerHTML = '<i class="fas fa-exclamation-circle text-red-500"></i>';
            }

            alertBox.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('translate-y-[-20px]', 'opacity-0');
                content.classList.add('translate-y-0', 'opacity-100');
            }, 10);

            // Auto hide
            setTimeout(hideCustomAlert, 4000);
        }

        function hideCustomAlert() {
            const alertBox = document.getElementById('customAlertBox');
            const content = document.getElementById('customAlertContent');
            if (content) {
                content.classList.remove('translate-y-0', 'opacity-100');
                content.classList.add('translate-y-[-20px]', 'opacity-0');
                setTimeout(() => alertBox.classList.add('hidden'), 300);
            }
        }

        function showView(viewId) {
            document.querySelectorAll('.view-section').forEach(view => view.classList.remove('active'));
            document.getElementById('view-' + viewId).classList.add('active');

            const titleEl = document.getElementById('view-title');
            const subtitleEl = document.getElementById('view-subtitle');
            const breadcrumbEl = document.getElementById('breadcrumb-active');

            document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));

            if (viewId === 'dashboard') {
                if (titleEl) titleEl.innerText = "Kondisi Anak";
                if (subtitleEl) subtitleEl.innerText = "Monitoring Kedisiplinan Real-Time";
                if (breadcrumbEl) breadcrumbEl.innerText = "Kondisi";
                document.getElementById('nav-dashboard')?.classList.add('active');
            } else if (viewId === 'profile') {
                if (titleEl) titleEl.innerText = "Pengaturan Akun";
                if (subtitleEl) subtitleEl.innerText = "Profil Pribadi Wali Murid";
                if (breadcrumbEl) breadcrumbEl.innerText = "Profil Anda";
                document.getElementById('editModal').classList.add('hidden'); // Tutup modal jika sedang terbuka
            } else if (viewId === 'detail-riwayat') {
                if (titleEl) titleEl.innerText = "Riwayat Poin";
                if (subtitleEl) subtitleEl.innerText = "Detail Rekaman Pelanggaran Ananda";
                if (breadcrumbEl) breadcrumbEl.innerText = "Riwayat";
                @if (isset($siswaAktif))
                    loadRiwayatPoin("{{ $siswaAktif->nisn }}");
                @endif
            } else if (viewId === 'profil-siswa') {
                if (titleEl) titleEl.innerText = "Profil Anak";
                if (subtitleEl) subtitleEl.innerText = "Informasi Lengkap Ananda";
                if (breadcrumbEl) breadcrumbEl.innerText = "Anak";
            }
        }

        async function loadRiwayatPoin(nisn) {
            const tbody = document.getElementById('tabel-riwayat-body');
            try {
                const response = await fetch(`/riwayat-poin/api/${nisn}`);
                const res = await response.json();
                const data = res.data ? res.data : res;

                tbody.innerHTML = '';
                if (!data || data.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="4" class="py-8 text-center text-gray-400 font-bold text-xs md:text-sm">Tidak ada riwayat pelanggaran. Alhamdulillah!</td></tr>';
                    return;
                }

                data.forEach(item => {
                    const dateObj = new Date(item.waktu);
                    const formattedDate = dateObj.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    const isTambah = item.jenis === 'Tambah';
                    const pointColor = isTambah ? 'red' : 'green';
                    const pointSign = isTambah ? '+' : '-';
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50/50 transition border-b border-gray-50';
                    tr.innerHTML = `
                        <td class="py-3 md:py-4 pl-4 md:pl-6 font-bold text-gray-500 whitespace-nowrap">${formattedDate}</td>
                        <td class="py-3 md:py-4 font-black text-gray-800">${item.ket || '-'}</td>
                        <td class="py-3 md:py-4 text-center">
                            <span class="bg-${pointColor}-50 text-${pointColor}-600 border border-${pointColor}-200 px-2 py-1 rounded font-black text-[9px] md:text-[10px]">
                                ${pointSign}${item.jumlah}
                            </span>
                        </td>
                        <td class="py-3 md:py-4 pr-4 md:pr-6 text-gray-500 font-medium">Sistem</td>
                    `;
                    tbody.appendChild(tr);
                });
            } catch (error) {
                tbody.innerHTML =
                    '<tr><td colspan="4" class="py-8 text-center text-red-500 font-bold text-xs"><i class="fas fa-exclamation-triangle mr-1"></i> Gagal memuat data.</td></tr>';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const circle = document.querySelector('.circle-progress');
            if (circle) setTimeout(() => {
                circle.style.strokeDashoffset = "{{ $dashoffset ?? 477 }}";
            }, 100);

            const profileBtn = document.getElementById('profileDropdownBtn');
            const profileMenu = document.getElementById('profileDropdownMenu');
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', e => {
                    e.stopPropagation();
                    if (profileMenu.classList.contains('hidden')) {
                        profileMenu.classList.remove('hidden');
                        setTimeout(() => {
                            profileMenu.classList.remove('opacity-0', 'scale-95');
                            profileMenu.classList.add('opacity-100', 'scale-100');
                        }, 10);
                    } else {
                        profileMenu.classList.remove('opacity-100', 'scale-100');
                        profileMenu.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => profileMenu.classList.add('hidden'), 200);
                    }
                });
                document.addEventListener('click', e => {
                    if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                        profileMenu.classList.remove('opacity-100', 'scale-100');
                        profileMenu.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => profileMenu.classList.add('hidden'), 200);
                    }
                });
            }

            const profileForm = document.getElementById('profileForm');
            if (profileForm) {
                profileForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                    submitBtn.disabled = true;

                    try {
                        const formData = new FormData(this);
                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        const result = await response.json();

                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;

                        if (response.ok && result.success) {
                            showCustomAlert('Berhasil!', result.message ||
                                'Profil berhasil diperbarui.', true);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showCustomAlert('Gagal', result.message ||
                                'Terjadi kesalahan saat menyimpan.', false);
                        }
                    } catch (error) {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        showCustomAlert('Koneksi Terputus', 'Gagal terhubung ke server.', false);
                    }
                });
            }
        });
    </script>
</body>

</html>
