<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Orang Tua - Kondisi Anak</title>
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

        /* Styling tambahan untuk indikator poin berjalan */
        .circle-progress {
            transition: stroke-dashoffset 1s ease-out;
        }

        /* Styling untuk Sistem View SPA */
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
        <div class="p-8 border-b border-white/10">
            <div class="flex items-center gap-3 mb-2">
                <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                    class="w-10 drop-shadow-md" alt="Logo Kemenag">
                <h1 class="font-bold text-xl leading-tight tracking-tight uppercase">Monitoring <br> Wali Murid</h1>
            </div>
            <p class="text-[10px] opacity-80 font-bold tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
        </div>

        <nav class="mt-6 flex-grow pl-6 space-y-1">
            <a href="#" onclick="showView('dashboard')" id="nav-dashboard"
                class="sidebar-item active flex items-center px-6 py-4 transition shadow-sm duration-300">
                <i class="fas fa-child mr-4 text-lg opacity-80"></i> <span class="font-medium tracking-wide">Kondisi
                    Anak</span>
            </a>

            <!-- Menu Konsultasi BK -->
            <a href="{{ route('ortu.konsultasi') }}" id="nav-konsultasi"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl duration-300">
                <i class="fas fa-comments mr-4 text-lg opacity-80"></i> <span
                    class="font-medium tracking-wide">Konsultasi BK</span>
            </a>

            <div class="mt-auto pt-20 pb-10">
                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">
                    @csrf
                </form>
            </div>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 ml-72 p-10 min-h-screen flex flex-col">

        @php
            // Menghitung status dan visualisasi lingkaran poin secara dinamis
            $poinAktif = $siswaAktif->poin ?? 0;

            // Persentase lingkaran (Radius = 76, Keliling = 2 * pi * r = ~477)
            $persenPoin = min($poinAktif / 100, 1);
            $dashoffset = 477 - 477 * $persenPoin;

            // Logika Status Sanksi Dinamis
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
        <header class="flex justify-between items-center mb-10">
            <div>
                <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                    Home / Anak / <span id="breadcrumb-active" class="text-gray-600">Kondisi</span>
                </nav>
                <h2 id="view-title" class="text-3xl font-black text-gray-700 uppercase tracking-tighter italic">Kondisi
                    Anak</h2>
                <p id="view-subtitle" class="text-[10px] text-gray-400 font-bold uppercase mt-1">Monitoring Kedisiplinan
                    Real-Time</p>
            </div>

            <!-- User Profile & Dropdown -->
            <div class="relative">
                <button id="profileDropdownBtn"
                    class="flex items-center gap-4 bg-white px-6 py-2.5 rounded-full shadow-sm border border-gray-100 transition hover:shadow-md focus:outline-none">
                    <div class="text-right">
                        <p class="text-xs font-black text-[#10b981] uppercase leading-none">
                            {{ $user->name ?? 'Wali Murid' }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">
                            Wali Dari {{ isset($daftarAnak) ? count($daftarAnak) : 0 }} Anak
                        </p>
                    </div>

                    <img src="{{ isset($user->photo) && $user->photo ? (filter_var($user->photo, FILTER_VALIDATE_URL) ? $user->photo : asset('storage/' . $user->photo)) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'Wali Murid') . '&background=10b981&color=fff' }}"
                        class="w-10 h-10 rounded-full border-2 border-green-50 shadow-sm object-cover" alt="Profile">
                    <i class="fas fa-chevron-down text-gray-400 text-xs ml-1"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdownMenu"
                    class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden z-50 transform origin-top-right transition-all duration-200 opacity-0 scale-95">
                    <div class="py-2">
                        <button type="button"
                            onclick="showView('profile'); document.getElementById('profileDropdownMenu').classList.add('hidden');"
                            class="w-full text-left px-6 py-3 text-xs font-bold text-gray-700 hover:bg-green-50 hover:text-[#10b981] transition flex items-center gap-3">
                            <i class="fas fa-user"></i> Profil Anda
                        </button>

                        @if (isset($daftarAnak) && count($daftarAnak) > 0)
                            <button type="button"
                                onclick="showView('profil-siswa'); document.getElementById('profileDropdownMenu').classList.add('hidden');"
                                class="w-full text-left px-6 py-3 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-3">
                                <i class="fas fa-user-graduate"></i> Profil Siswa
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

        <!-- ALERTS -->
        <div id="liveAlert"
            class="hidden px-5 py-4 rounded-2xl mb-8 shadow-sm flex items-center gap-3 font-bold text-sm tracking-wide">
        </div>

        @if (session('success'))
            <div
                class="bg-green-100 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-8 shadow-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-bold text-sm tracking-wide">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div
                class="bg-red-100 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-8 shadow-sm flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-xl"></i>
                <span class="font-bold text-sm tracking-wide">{{ session('error') }}</span>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- VIEW: DASHBOARD KONDISI ANAK -->
        <!-- ============================================== -->
        <div id="view-dashboard" class="view-section active">
            <!-- TAB PILIH ANAK (Akan Muncul Jika Anak > 1 di Database) -->
            @if (isset($daftarAnak) && count($daftarAnak) > 1)
                <div class="mb-8">
                    <h3 class="font-black text-gray-400 text-[10px] uppercase tracking-widest mb-3">Pilih Profil Anak
                        Anda</h3>
                    <div class="flex gap-4 overflow-x-auto pb-2">
                        @foreach ($daftarAnak as $anak)
                            <a href="?siswa_id={{ $anak->id }}"
                                class="flex items-center gap-3 px-6 py-3 rounded-2xl shadow-sm border transition whitespace-nowrap {{ isset($siswaAktif) && $siswaAktif->id == $anak->id ? 'bg-[#10b981] text-white border-green-500' : 'bg-white text-gray-600 border-gray-100 hover:bg-gray-50' }}">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($anak->nama) }}&background={{ isset($siswaAktif) && $siswaAktif->id == $anak->id ? 'fff' : '10b981' }}&color={{ isset($siswaAktif) && $siswaAktif->id == $anak->id ? '10b981' : 'fff' }}"
                                    class="w-8 h-8 rounded-full border-2 {{ isset($siswaAktif) && $siswaAktif->id == $anak->id ? 'border-white/50' : 'border-green-50' }}"
                                    alt="Siswa">
                                <div class="text-left">
                                    <p class="text-xs font-bold uppercase tracking-wider">{{ $anak->nama }}</p>
                                    <p
                                        class="text-[10px] {{ isset($siswaAktif) && $siswaAktif->id == $anak->id ? 'text-green-100' : 'text-gray-400' }} uppercase">
                                        {{ $anak->kelas }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- SECTION: STATUS KEDISIPLINAN -->
            <div class="mb-10">
                @if (isset($siswaAktif) && $siswaAktif)
                    <!-- Hero Card Info Poin -->
                    <div
                        class="bg-white p-10 rounded-[40px] shadow-sm border border-gray-50 flex flex-col md:flex-row items-center gap-12 relative overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-64 h-64 bg-gray-50 rounded-full opacity-50 blur-3xl">
                        </div>

                        <!-- Lingkaran Angka Poin -->
                        <div class="relative flex items-center justify-center shrink-0 z-10">
                            <svg class="w-44 h-44 transform -rotate-90">
                                <circle class="text-gray-100" stroke-width="14" stroke="currentColor" fill="transparent"
                                    r="76" cx="88" cy="88" />
                                <circle class="text-{{ $statusWarna }}-500 circle-progress" stroke-width="14"
                                    stroke-dasharray="477" stroke-dashoffset="{{ $dashoffset }}"
                                    stroke-linecap="round" stroke="currentColor" fill="transparent" r="76"
                                    cx="88" cy="88" />
                            </svg>
                            <div class="absolute flex flex-col items-center">
                                <span
                                    class="text-5xl font-black text-gray-800 leading-none">{{ $poinAktif }}</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-2">Poin
                                    Total</span>
                            </div>
                        </div>

                        <!-- Deskripsi Status -->
                        <div class="flex-1 relative z-10 text-center md:text-left">
                            <div class="flex gap-2 mb-4 justify-center md:justify-start">
                                <div
                                    class="inline-block px-4 py-1.5 bg-{{ $statusWarna }}-100 text-{{ $statusWarna }}-700 rounded-full text-[10px] font-black uppercase tracking-widest border border-{{ $statusWarna }}-200">
                                    Status: {{ $statusTeks }}
                                </div>

                                <!-- Indikator Siswa Aktif / Lulus -->
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
                                    class="inline-block px-4 py-1.5 {{ $bgStatus }} rounded-full text-[10px] font-black uppercase tracking-widest border">
                                    Siswa {{ $statusPendidikan }}
                                </div>
                            </div>

                            <h3 class="text-2xl font-black text-gray-700 uppercase tracking-tight mb-3">
                                Laporan Ananda <span class="text-[#10b981]">{{ $siswaAktif->nama }}</span>
                            </h3>
                            <p class="text-gray-500 text-sm font-medium leading-relaxed mb-6 max-w-lg mx-auto md:mx-0">
                                Berdasarkan rekaman kedisiplinan sekolah, ananda saat ini memiliki <strong
                                    class="text-{{ $statusWarna }}-600">{{ $poinAktif }} Poin
                                    Pelanggaran</strong>.
                                <br><br>
                                @if ($poinAktif >= 100)
                                    <span class="text-red-500 font-bold">Batas maksimal poin telah terlampaui. Mohon
                                        segera menghadap ke ruang BK/Kepala Madrasah.</span>
                                @elseif($poinAktif >= 25)
                                    Kami mengharapkan perhatian dan kerjasama Bapak/Ibu untuk membimbing perilaku
                                    disiplin ananda di rumah.
                                @else
                                    Perilaku ananda di sekolah terpantau sangat baik. Pertahankan terus akhlak mulia
                                    ini!
                                @endif
                            </p>

                            <!-- TOMBOL LIHAT DETAIL RIWAYAT -->
                            <button type="button" onclick="showView('detail-riwayat')"
                                class="inline-flex bg-[#10b981] hover:bg-green-600 transition text-white px-6 py-3 rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 items-center justify-center cursor-pointer">
                                <i class="fas fa-list-ul mr-2"></i> Lihat Detail Riwayat
                            </button>
                        </div>
                    </div>

                    <!-- SECTION: TAHAPAN SANKSI / PERINGATAN -->
                    <div class="mt-10">
                        <h3 class="font-black text-gray-700 text-sm uppercase tracking-widest mb-6 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-3 text-red-500"></i> Peringatan Ambang Batas
                            Sanksi
                        </h3>

                        <!-- Grid 3 Kartu Sanksi -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 relative">

                            <!-- Tahap I -->
                            <div
                                class="p-8 rounded-[30px] {{ $poinAktif >= 25 ? 'bg-red-50 border border-red-200 shadow-sm' : 'bg-white border border-gray-100 opacity-80' }} relative overflow-hidden flex flex-col justify-between transition-all">
                                @if ($poinAktif >= 25)
                                    <div
                                        class="absolute top-0 right-0 w-24 h-24 bg-red-100 rounded-bl-full opacity-50">
                                    </div>
                                @endif
                                <div class="relative z-10">
                                    <div class="flex justify-between items-center mb-6">
                                        <p
                                            class="text-[10px] font-black {{ $poinAktif >= 25 ? 'text-red-600' : 'text-gray-400' }} uppercase tracking-widest">
                                            Tahap I</p>
                                        <span
                                            class="{{ $poinAktif >= 25 ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-500' }} px-3 py-1 rounded-lg font-black text-[10px]">Batas
                                            25 Poin</span>
                                    </div>
                                    <h4
                                        class="text-lg font-black {{ $poinAktif >= 25 ? 'text-red-800' : 'text-gray-700' }} uppercase mb-2 leading-tight">
                                        Panggilan <br> Orang Tua I</h4>
                                    <p
                                        class="text-xs {{ $poinAktif >= 25 ? 'text-red-600' : 'text-gray-500' }} font-medium">
                                        Pemanggilan pertama wali murid untuk pembinaan intensif siswa.</p>
                                </div>
                                <div
                                    class="mt-8 pt-4 border-t {{ $poinAktif >= 25 ? 'border-red-200/50' : 'border-gray-100' }} relative z-10 flex items-center gap-2">
                                    @if ($poinAktif >= 25)
                                        <i class="fas fa-times-circle text-red-500 text-lg"></i>
                                        <span class="text-[10px] font-bold text-red-700 uppercase tracking-wider">Telah
                                            Terlewati</span>
                                    @else
                                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                        <span
                                            class="text-[10px] font-bold text-green-600 uppercase tracking-wider">Status
                                            Aman</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Tahap II -->
                            <div
                                class="p-8 rounded-[30px] {{ $poinAktif >= 50 ? 'bg-red-50 border border-red-200 shadow-sm' : ($poinAktif >= 25 ? 'bg-white border-2 border-yellow-400 shadow-xl' : 'bg-white border border-gray-100 opacity-80') }} relative overflow-hidden flex flex-col justify-between transition-all">
                                @if ($poinAktif >= 25 && $poinAktif < 50)
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-50 rounded-bl-full"></div>
                                @endif
                                <div class="relative z-10">
                                    <div class="flex justify-between items-center mb-6">
                                        <p
                                            class="text-[10px] font-black {{ $poinAktif >= 50 ? 'text-red-600' : ($poinAktif >= 25 ? 'text-yellow-600' : 'text-gray-400') }} uppercase tracking-widest">
                                            Tahap II</p>
                                        <span
                                            class="{{ $poinAktif >= 50 ? 'bg-red-600 text-white' : ($poinAktif >= 25 ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-500') }} px-3 py-1 rounded-lg font-black text-[10px]">Batas
                                            50 Poin</span>
                                    </div>
                                    <h4
                                        class="text-xl font-black {{ $poinAktif >= 50 ? 'text-red-800' : 'text-gray-800' }} uppercase mb-2 leading-tight">
                                        Panggilan <br> Orang Tua II</h4>
                                    <p
                                        class="text-xs {{ $poinAktif >= 50 ? 'text-red-600' : 'text-gray-500' }} font-medium">
                                        Surat perjanjian materai dan skorsing tertulis.</p>
                                </div>

                                @if ($poinAktif >= 50)
                                    <div
                                        class="mt-8 pt-4 border-t border-red-200/50 relative z-10 flex items-center gap-2">
                                        <i class="fas fa-times-circle text-red-500 text-lg"></i>
                                        <span class="text-[10px] font-bold text-red-700 uppercase tracking-wider">Telah
                                            Terlewati</span>
                                    </div>
                                @elseif($poinAktif >= 25)
                                    <div
                                        class="mt-8 bg-yellow-50 p-4 rounded-2xl border border-yellow-200 relative z-10 flex items-center justify-between">
                                        <div>
                                            <span
                                                class="block text-[9px] font-black text-yellow-600 uppercase tracking-wider mb-1">Ambang
                                                Sanksi Terdekat</span>
                                            <span class="text-[10px] font-bold text-gray-500">Tersisa</span>
                                        </div>
                                        <span
                                            class="text-xl font-black text-red-500 animate-pulse">{{ 50 - $poinAktif }}
                                            Poin</span>
                                    </div>
                                @else
                                    <div
                                        class="mt-8 pt-4 border-t border-gray-100 relative z-10 flex items-center gap-2">
                                        <i class="fas fa-shield-alt text-green-500 text-lg"></i>
                                        <span
                                            class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Jarak
                                            Masih Aman</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Tahap III -->
                            <div
                                class="p-8 rounded-[30px] {{ $poinAktif >= 100 ? 'bg-red-900 border-red-900 shadow-xl' : ($poinAktif >= 50 ? 'bg-white border-2 border-orange-400 shadow-xl' : 'bg-white border border-gray-100 opacity-80') }} relative overflow-hidden flex flex-col justify-between transition-all">
                                <div class="relative z-10">
                                    <div class="flex justify-between items-center mb-6">
                                        <p
                                            class="text-[10px] font-black {{ $poinAktif >= 100 ? 'text-red-300' : ($poinAktif >= 50 ? 'text-orange-600' : 'text-gray-400') }} uppercase tracking-widest">
                                            Tahap III</p>
                                        <span
                                            class="{{ $poinAktif >= 100 ? 'bg-red-500 text-white' : ($poinAktif >= 50 ? 'bg-orange-500 text-white' : 'bg-gray-800 text-white') }} px-3 py-1 rounded-lg font-black text-[10px]">Batas
                                            100 Poin</span>
                                    </div>
                                    <h4
                                        class="text-lg font-black {{ $poinAktif >= 100 ? 'text-white' : 'text-gray-800' }} uppercase mb-2 leading-tight">
                                        Dikeluarkan <br> Dari Sekolah</h4>
                                    <p
                                        class="text-xs {{ $poinAktif >= 100 ? 'text-red-200' : 'text-gray-500' }} font-medium">
                                        Siswa dikembalikan seutuhnya ke pengawasan Orang Tua.</p>
                                </div>

                                @if ($poinAktif >= 100)
                                    <div
                                        class="mt-8 pt-4 border-t border-red-700 relative z-10 flex items-center gap-2">
                                        <i class="fas fa-ban text-red-400 text-lg"></i>
                                        <span class="text-[10px] font-bold text-red-200 uppercase tracking-wider">Drop
                                            Out Terjadi</span>
                                    </div>
                                @elseif($poinAktif >= 50)
                                    <div
                                        class="mt-8 bg-orange-50 p-4 rounded-2xl border border-orange-200 relative z-10 flex items-center justify-between">
                                        <div>
                                            <span
                                                class="block text-[9px] font-black text-orange-600 uppercase tracking-wider mb-1">Sanksi
                                                Keras Terakhir</span>
                                            <span class="text-[10px] font-bold text-gray-500">Tersisa</span>
                                        </div>
                                        <span
                                            class="text-xl font-black text-red-600 animate-pulse">{{ 100 - $poinAktif }}
                                            Poin</span>
                                    </div>
                                @else
                                    <div
                                        class="mt-8 pt-4 border-t border-gray-100 relative z-10 flex items-center gap-2">
                                        <i class="fas fa-shield-alt text-green-500 text-lg"></i>
                                        <span
                                            class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Jarak
                                            Masih Aman</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div
                        class="bg-white p-12 rounded-[40px] shadow-sm border border-gray-50 text-center flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-user-slash text-gray-300 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-700 mb-2">Belum Ada Data Anak</h3>
                        <p class="text-gray-500 text-sm max-w-sm mx-auto">Akun Anda saat ini belum dikaitkan dengan
                            profil siswa mana pun. Silakan hubungi pihak Tata Usaha (TU) sekolah untuk proses
                            sinkronisasi data.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- ============================================== -->
        <!-- VIEW: DETAIL RIWAYAT POIN -->
        <!-- ============================================== -->
        <div id="view-detail-riwayat" class="view-section">
            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50">
                <div class="flex justify-between items-center mb-8 border-b pb-6">
                    <div>
                        <h3 class="font-black text-gray-700 text-lg uppercase tracking-widest">Detail Riwayat
                            Pelanggaran</h3>
                        <p class="text-xs text-gray-400 mt-1">Rekapitulasi poin dan sanksi ananda tercinta</p>
                    </div>
                    <button type="button" onclick="showView('dashboard')"
                        class="bg-gray-100 text-gray-600 px-6 py-2.5 rounded-xl text-xs font-bold uppercase hover:bg-gray-200 transition flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                </div>

                @if (isset($siswaAktif))
                    <div
                        class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100 mb-8 flex flex-wrap gap-x-12 gap-y-4">
                        <div>
                            <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest">Nama Anak</p>
                            <p class="text-sm font-black text-gray-800 uppercase mt-1">{{ $siswaAktif->nama }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest">NISN</p>
                            <p class="text-sm font-black text-gray-800 uppercase mt-1">{{ $siswaAktif->nisn }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest">Kelas</p>
                            <p class="text-sm font-black text-gray-800 uppercase mt-1">{{ $siswaAktif->kelas }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest">Total Poin Saat
                                Ini</p>
                            <p class="text-xl font-black text-red-600 uppercase mt-1">{{ $siswaAktif->poin }}</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[600px]">
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
                                    <td colspan="4" class="py-8 text-center text-gray-400 font-bold">
                                        <i class="fas fa-spinner fa-spin mr-2"></i> Memuat data riwayat...
                                    </td>
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
                <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
                    <h3 class="text-lg font-black text-gray-700 uppercase tracking-widest">Profil Siswa (Anak)</h3>
                    <button type="button" onclick="showView('dashboard')"
                        class="text-gray-400 hover:text-red-500 transition"><i
                            class="fas fa-times text-xl"></i></button>
                </div>

                <!-- Grid untuk menampilkan semua anak -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($daftarAnak as $anak)
                        <div
                            class="bg-white p-6 rounded-[30px] shadow-sm border border-gray-50 flex flex-col items-center relative">
                            <!-- Foto Siswa (Diperkecil) -->
                            <div
                                class="w-20 h-20 shrink-0 rounded-2xl overflow-hidden border-2 border-blue-50 shadow-sm mb-4">
                                @if ($anak->photo)
                                    <img src="{{ filter_var($anak->photo, FILTER_VALIDATE_URL) ? $anak->photo : asset('storage/' . $anak->photo) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($anak->nama) }}&background=2563eb&color=fff&size=120"
                                        class="w-full h-full object-cover">
                                @endif
                            </div>

                            <!-- Detail Data Anak -->
                            <div class="w-full text-center">
                                <h4 class="font-black text-gray-800 text-sm uppercase leading-tight mb-1">
                                    {{ $anak->nama }}</h4>
                                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-4">Kelas
                                    {{ $anak->kelas }}</p>

                                <div class="space-y-2 text-left">
                                    <div
                                        class="flex justify-between items-center text-xs border-b border-gray-50 pb-2">
                                        <span class="text-gray-400 font-bold uppercase text-[9px]">NISN</span>
                                        <span class="font-black text-gray-700">{{ $anak->nisn }}</span>
                                    </div>
                                    <div
                                        class="flex justify-between items-center text-xs border-b border-gray-50 pb-2">
                                        <span class="text-gray-400 font-bold uppercase text-[9px]">Gender</span>
                                        <span class="font-black text-gray-700">{{ $anak->jk ?? '-' }}</span>
                                    </div>

                                    <!-- TAMBAHAN: Nama Orang Tua / Wali -->
                                    <div
                                        class="flex justify-between items-center text-xs border-b border-gray-50 pb-2">
                                        <span class="text-gray-400 font-bold uppercase text-[9px]">Wali</span>
                                        <span class="font-black text-gray-700">{{ $user->name ?? '-' }}</span>
                                    </div>

                                    <!-- TAMBAHAN: Alamat -->
                                    <div class="flex justify-between items-start text-xs border-b border-gray-50 pb-2">
                                        <span class="text-gray-400 font-bold uppercase text-[9px] mt-0.5">Alamat</span>
                                        <span
                                            class="font-black text-gray-700 text-right max-w-[60%] leading-snug">{{ $anak->alamat ?? 'Belum diisi' }}</span>
                                    </div>

                                    <!-- Status Aktif / Lulus -->
                                    <div class="flex justify-between items-center pt-2">
                                        <span class="text-gray-400 font-bold uppercase text-[9px]">Status</span>
                                        @php
                                            $statusPendidikan = $anak->status ?? 'Aktif';
                                            $warnaStatus =
                                                $statusPendidikan == 'Aktif'
                                                    ? 'bg-blue-50 text-blue-600 border-blue-100'
                                                    : ($statusPendidikan == 'Lulus'
                                                        ? 'bg-indigo-50 text-indigo-600 border-indigo-100'
                                                        : 'bg-red-50 text-red-600 border-red-100');
                                        @endphp
                                        <span
                                            class="px-2 py-1 rounded-md border font-black text-[9px] uppercase {{ $warnaStatus }}">
                                            {{ $statusPendidikan }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- KARTU TAMBAH ANAK LAIN -->
                    <button onclick="openTambahAnakModal()"
                        class="border-2 border-dashed border-gray-300 rounded-[30px] p-6 flex flex-col items-center justify-center hover:bg-gray-50 hover:border-[#10b981] transition text-gray-400 hover:text-[#10b981] min-h-[300px] group">
                        <div
                            class="w-16 h-16 rounded-full bg-gray-100 group-hover:bg-green-50 flex items-center justify-center mb-4 transition">
                            <i class="fas fa-user-plus text-2xl"></i>
                        </div>
                        <span class="font-black text-sm uppercase tracking-widest">Kaitkan Anak Lain</span>
                        <span class="text-[10px] text-gray-400 mt-2 text-center px-4">Masukkan NISN anak Anda yang lain
                            untuk menambahkannya.</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- VIEW: PROFILE PENGGUNA (ORANG TUA) -->
        <!-- ============================================== -->
        <div id="view-profile" class="view-section">
            <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-50 max-w-xl mx-auto relative">

                <!-- Profile Display -->
                <div id="profileView" class="flex flex-col items-center">
                    <!-- Foto Profil Ortu Diperkecil Sedikit -->
                    <div
                        class="w-24 h-24 rounded-full overflow-hidden mb-4 border-4 border-green-50 shadow-sm relative group">
                        @if (isset($user->photo) && $user->photo)
                            <img src="{{ filter_var($user->photo, FILTER_VALIDATE_URL) ? $user->photo : asset('storage/' . $user->photo) }}"
                                class="w-full h-full object-cover" id="mainProfilePic" alt="Profile Picture">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'Wali Murid') }}&background=10b981&color=fff&size=128"
                                class="w-full h-full object-cover" id="mainProfilePic" alt="Profile Picture">
                        @endif
                    </div>
                    <h3 class="text-xl font-black text-gray-800 uppercase">{{ $user->name ?? 'Wali Murid' }}
                    </h3>
                    <p class="text-[10px] font-bold text-[#10b981] uppercase tracking-widest mb-8">Orang Tua / Wali
                        Siswa
                    </p>

                    <div class="w-full space-y-3">
                        <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-envelope text-[#10b981] w-8 text-center text-lg"></i>
                            <div class="ml-3">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Username /
                                    Email
                                </p>
                                <p class="text-sm font-black text-gray-700">
                                    {{ $user->username ?? ($user->email ?? '-') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-venus-mars text-[#10b981] w-8 text-center text-lg"></i>
                            <div class="ml-3">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Gender</p>
                                <p class="text-sm font-black text-gray-700">{{ $user->gender ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-phone-alt text-[#10b981] w-8 text-center text-lg"></i>
                            <div class="ml-3">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">No. Telepon</p>
                                <p class="text-sm font-black text-gray-700">{{ $user->phone ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="toggleEditProfile(true)"
                        class="mt-6 bg-[#10b981] text-white px-8 py-3 rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-105 transition w-full">
                        <i class="fas fa-user-edit mr-2"></i> Edit Profil
                    </button>
                </div>

                <!-- Profile Form -->
                <form id="profileForm" class="hidden flex flex-col" action="{{ route('profile.update') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                        <h3 class="text-base font-black text-gray-700 uppercase tracking-widest">Edit Profil</h3>
                        <button type="button" onclick="toggleEditProfile(false)"
                            class="text-gray-400 hover:text-red-500 transition"><i
                                class="fas fa-times text-lg"></i></button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Unggah
                                Foto</label>
                            <input type="file" name="photo" accept="image/*"
                                class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-50 file:text-[#10b981] hover:file:bg-green-100 transition border border-gray-100 rounded-xl bg-gray-50">
                        </div>
                        <div>
                            <label
                                class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Nama
                                Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name ?? '' }}" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>
                        <div>
                            <label
                                class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Username
                                / Email</label>
                            <input type="text" value="{{ $user->username ?? ($user->email ?? '') }}" disabled
                                class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label
                                class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Gender</label>
                            <select name="gender"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                                <option value="">Pilih Gender</option>
                                <option value="Laki-laki"
                                    {{ ($user->gender ?? '') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan"
                                    {{ ($user->gender ?? '') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">No.
                                Telepon</label>
                            <input type="tel" name="phone" value="{{ $user->phone ?? '' }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button type="submit"
                            class="flex-1 bg-[#10b981] text-white px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:bg-green-600 transition">Simpan</button>
                        <button type="button" onclick="toggleEditProfile(false)"
                            class="flex-1 bg-gray-100 text-gray-600 px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-200 transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <!-- ============================================== -->
    <!-- MODAL TAMBAH ANAK LAIN -->
    <!-- ============================================== -->
    <div id="tambahAnakModal" class="fixed inset-0 bg-black/50 hidden z-[60] flex items-center justify-center">
        <div class="bg-white rounded-[30px] w-full max-w-md overflow-hidden shadow-2xl p-8 relative">
            <button onclick="closeTambahAnakModal()"
                class="absolute top-6 right-6 text-gray-400 hover:text-red-500 transition"><i
                    class="fas fa-times text-xl"></i></button>

            <div class="text-center mb-6">
                <div
                    class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="fas fa-link"></i>
                </div>
                <h3 class="font-black text-gray-800 text-xl">Kaitkan Anak Lain</h3>
                <p class="text-xs text-gray-500 mt-2 px-4">Masukkan NISN anak Anda yang lain agar profilnya terhubung
                    dengan akun ini.</p>
            </div>

            <!-- Form ini akan diarahkan ke controller untuk memproses penambahan anak -->
            <form action="{{ route('ortu.tambah-anak') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">NISN Anak
                        (10 Angka)</label>
                    <div class="relative">
                        <i class="fas fa-id-card absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                        <input type="text" name="nisn_tambahan" required maxlength="10"
                            placeholder="Masukkan NISN Anak"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-blue-100 transition">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-blue-500 text-white px-6 py-3.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-100 hover:bg-blue-600 transition flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Hubungkan Sekarang
                </button>
            </form>
        </div>
    </div>

    <script>
        // Logika Modal Tambah Anak
        function openTambahAnakModal() {
            document.getElementById('tambahAnakModal').classList.remove('hidden');
        }

        function closeTambahAnakModal() {
            document.getElementById('tambahAnakModal').classList.add('hidden');
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
                toggleEditProfile(false);
            } else if (viewId === 'detail-riwayat') {
                if (titleEl) titleEl.innerText = "Riwayat Poin";
                if (subtitleEl) subtitleEl.innerText = "Detail Rekaman Pelanggaran Ananda";
                if (breadcrumbEl) breadcrumbEl.innerText = "Riwayat";
                @if (isset($siswaAktif))
                    loadRiwayatPoin("{{ $siswaAktif->nisn }}");
                @endif
            } else if (viewId === 'profil-siswa') {
                if (titleEl) titleEl.innerText = "Profil Siswa";
                if (subtitleEl) subtitleEl.innerText = "Informasi Lengkap Pendidikan Ananda";
                if (breadcrumbEl) breadcrumbEl.innerText = "Siswa";
            }
        }

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

        async function loadRiwayatPoin(nisn) {
            const tbody = document.getElementById('tabel-riwayat-body');
            try {
                const response = await fetch(`/admin/riwayat/api/${nisn}`);
                const res = await response.json();
                const data = res.data ? res.data : res;

                tbody.innerHTML = '';
                if (!data || data.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="4" class="py-8 text-center text-gray-400 font-bold text-sm">Tidak ada riwayat pelanggaran untuk anak ini. Alhamdulillah!</td></tr>';
                    return;
                }

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
                        <td class="py-5 pl-6 font-bold text-gray-500">${formattedDate}</td>
                        <td class="py-5 font-black text-gray-800">${item.ket || '-'}</td>
                        <td class="py-5 text-center">
                            <span class="bg-${pointColor}-50 text-${pointColor}-600 border border-${pointColor}-200 px-3 py-1 rounded-lg font-black text-[10px]">
                                ${pointSign}${item.jumlah}
                            </span>
                        </td>
                        <td class="py-5 pr-6 text-gray-500 font-medium">Sistem Kedisiplinan</td>
                    `;
                    tbody.appendChild(tr);
                });
            } catch (error) {
                tbody.innerHTML =
                    '<tr><td colspan="4" class="py-8 text-center text-red-500 font-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Gagal memuat data. Periksa koneksi.</td></tr>';
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
                document.addEventListener('click', e => {
                    if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
                        profileMenu.classList.remove('opacity-100', 'scale-100');
                        profileMenu.classList.add('opacity-0', 'scale-95');
                        setTimeout(() => {
                            profileMenu.classList.add('hidden');
                        }, 200);
                    }
                });
            }

            const profileForm = document.getElementById('profileForm');
            if (profileForm) {
                profileForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerText;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                    submitBtn.disabled = true;

                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: new FormData(this),
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
