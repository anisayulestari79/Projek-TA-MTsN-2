<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <!-- Meta Viewport Penting untuk Responsif -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Konsultasi Guru BK - Wali Murid</title>
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

        /* Styling untuk Sistem View SPA */
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
            <a href="{{ route('ortu.dashboard') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl duration-300">
                <i class="fas fa-child mr-4 text-lg opacity-80"></i> <span class="font-medium tracking-wide">Kondisi
                    Anak</span>
            </a>

            <!-- Menu Konsultasi Aktif -->
            <a href="#" onclick="showView('konsultasi'); closeSidebarOnMobile();" id="nav-konsultasi"
                class="sidebar-item active flex items-center px-6 py-4 transition shadow-sm duration-300">
                <i class="fas fa-comments mr-4 text-lg"></i> <span class="font-bold tracking-wide">Konsultasi BK</span>
            </a>

            <div class="mt-auto pt-20 pb-10">
                <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">@csrf</form>
            </div>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 md:ml-72 p-4 md:p-10 min-h-screen flex flex-col w-full transition-all duration-300 relative">

        @php
            $user = Auth::user();
        @endphp

        <!-- GLOBAL HEADER (Diperbarui agar selaras dengan ortu-dashboard) -->
        <header class="flex justify-between items-center mb-6 md:mb-10 mt-2 md:mt-0">
            <div class="flex items-center gap-3 md:gap-4">
                <button onclick="toggleSidebar()"
                    class="md:hidden text-gray-600 hover:text-[#10b981] focus:outline-none shrink-0">
                    <i class="fas fa-bars text-xl bg-white p-2 rounded-lg shadow-sm border border-gray-100"></i>
                </button>
                <div>
                    <nav
                        class="text-[9px] md:text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1 hidden md:block">
                        Home / Anak / <span id="breadcrumb-active" class="text-gray-600">Konsultasi BK</span>
                    </nav>
                    <h2 id="view-title"
                        class="text-xl md:text-3xl font-black text-gray-700 uppercase tracking-tighter italic leading-none">
                        Layanan Konsultasi</h2>
                    <p id="view-subtitle"
                        class="text-[9px] md:text-[10px] text-gray-400 font-bold uppercase mt-1 hidden sm:block">
                        Komunikasi Langsung dengan Pihak Sekolah</p>
                </div>
            </div>

            <!-- User Profile & Dropdown -->
            <div class="relative shrink-0">
                <button id="profileDropdownBtn"
                    class="flex items-center gap-2 md:gap-4 bg-white p-1.5 md:px-6 md:py-2.5 rounded-full md:rounded-full shadow-sm border border-gray-100 transition hover:shadow-md focus:outline-none">
                    <div class="text-right hidden md:block">
                        <p class="text-xs font-black text-[#10b981] capitalize leading-none">
                            {{ $user->name ?? 'Wali Murid' }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">
                            Wali Dari {{ count($daftarAnak ?? []) }} Anak
                        </p>
                    </div>
                    <img src="{{ isset($user->photo) && $user->photo ? (filter_var($user->photo, FILTER_VALIDATE_URL) ? $user->photo : asset('storage/' . $user->photo)) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'Wali Murid') . '&background=10b981&color=fff' }}"
                        class="w-10 h-10 rounded-full border-2 border-green-50 shadow-sm object-cover" alt="Profile">
                    <i class="fas fa-chevron-down text-gray-400 text-xs ml-1 md:ml-0 hidden md:inline-block"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdownMenu"
                    class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden z-50 transform origin-top-right transition-all duration-200 opacity-0 scale-95">
                    <div class="py-2">
                        <button type="button"
                            onclick="showView('profile'); document.getElementById('profileDropdownMenu').classList.add('hidden');"
                            class="w-full text-left px-6 py-3 text-xs font-bold text-gray-700 hover:bg-green-50 hover:text-[#10b981] transition flex items-center gap-3">
                            <i class="fas fa-user-circle"></i> Profil Anda
                        </button>

                        @if (isset($daftarAnak) && count($daftarAnak) > 0)
                            <a href="{{ route('ortu.dashboard') }}?view=profil-siswa"
                                class="block w-full text-left px-6 py-3 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-3">
                                <i class="fas fa-user-graduate"></i> Profil Siswa
                            </a>
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

        <!-- ALERTS -->
        @if (session('success'))
            <div
                class="bg-green-100 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-6 md:mb-8 shadow-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-lg md:text-xl"></i>
                <span class="font-bold text-xs md:text-sm tracking-wide">{{ session('success') }}</span>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- VIEW: DASHBOARD KONSULTASI -->
        <!-- ============================================== -->
        <div id="view-konsultasi" class="view-section active">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 items-start">

                <!-- FORM KIRIM PESAN -->
                <div
                    class="lg:col-span-1 bg-white p-6 md:p-8 rounded-3xl md:rounded-[30px] shadow-sm border border-gray-50 h-fit lg:sticky lg:top-10">
                    <h3
                        class="font-black text-gray-700 text-xs md:text-sm uppercase tracking-widest mb-6 border-b pb-4 flex items-center">
                        <div
                            class="w-8 h-8 bg-green-50 text-[#10b981] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        Kirim Pesan Baru
                    </h3>

                    <form action="{{ route('ortu.konsultasi.kirim') }}" method="POST"
                        class="space-y-4 md:space-y-5">
                        @csrf
                        <div>
                            <label
                                class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Tahun
                                Ajaran</label>
                            <div class="relative">
                                <i
                                    class="fas fa-calendar-alt absolute left-4 top-3 md:top-3.5 text-gray-400 text-xs"></i>
                                <select name="academic_period" required
                                    class="w-full pl-10 pr-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition appearance-none cursor-pointer">
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
                                    class="fas fa-chevron-down absolute right-4 top-3.5 md:top-4 text-gray-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Pilih
                                Anak</label>
                            <div class="relative">
                                <i
                                    class="fas fa-user-graduate absolute left-4 top-3 md:top-3.5 text-gray-400 text-xs"></i>
                                <select name="siswa_id" id="pilihSiswa" onchange="updateGuruBK()" required
                                    class="w-full pl-10 pr-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition appearance-none cursor-pointer capitalize">
                                    @foreach ($daftarAnak ?? [] as $anak)
                                        <option value="{{ $anak->id }}"
                                            data-bk-id="{{ $anak->guru_bk_id ?? '' }}"
                                            data-bk-nama="{{ $anak->guru_bk_nama ?? 'Guru BK Kelas ' . ($anak->kelas ?? 'Terkait') }}">
                                            {{ $anak->nama }} (Kelas {{ $anak->kelas }})
                                        </option>
                                    @endforeach
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-4 top-3.5 md:top-4 text-gray-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Ditujukan
                                Ke (Guru BK)</label>
                            <div class="relative">
                                <i
                                    class="fas fa-chalkboard-teacher absolute left-4 top-3 md:top-3.5 text-[#10b981] text-sm"></i>
                                <input type="text" id="namaGuruBK" readonly placeholder="Otomatis terisi..."
                                    class="w-full pl-10 pr-4 py-2.5 md:py-3 bg-green-50 border border-green-100 rounded-xl text-xs md:text-sm font-bold text-green-700 outline-none cursor-not-allowed capitalize">
                            </div>
                            <input type="hidden" name="bk_id" id="hiddenBkId">
                            <p class="text-[8px] md:text-[9px] text-gray-400 mt-1 italic">*Guru BK menyesuaikan kelas
                                anak otomatis.</p>
                        </div>

                        <div>
                            <label
                                class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Topik
                                / Subjek</label>
                            <input type="text" name="topik" required
                                placeholder="Contoh: Klarifikasi Poin Pelanggaran"
                                class="w-full px-4 py-2.5 md:py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-medium outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>

                        <div>
                            <label
                                class="block text-[9px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 md:mb-2">Isi
                                Pesan</label>
                            <textarea name="pesan" rows="4" required
                                placeholder="Tuliskan pertanyaan atau keluhan Anda di sini secara detail..."
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs md:text-sm font-medium outline-none focus:ring-2 focus:ring-green-100 transition resize-none"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-[#10b981] text-white px-6 py-3 md:py-4 rounded-xl text-[10px] md:text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:bg-green-600 hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                            Kirim Sekarang <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>

                <!-- TABEL RIWAYAT KONSULTASI -->
                <div
                    class="lg:col-span-2 bg-white p-6 md:p-8 rounded-3xl md:rounded-[40px] shadow-sm border border-gray-50">
                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 md:mb-8 border-b pb-4 gap-4">
                        <h3
                            class="font-black text-gray-700 text-xs md:text-sm uppercase tracking-widest flex items-center">
                            <div
                                class="w-8 h-8 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center mr-3 shrink-0">
                                <i class="fas fa-history"></i>
                            </div>
                            Riwayat Konsultasi Anda
                        </h3>

                        <!-- Filter Tahun Akademik -->
                        <form action="{{ route('ortu.konsultasi') }}" method="GET" class="w-full md:w-auto">
                            <select name="tahun_akademik" onchange="this.form.submit()"
                                class="w-full md:w-auto bg-gray-50 border border-gray-100 text-[10px] md:text-xs font-bold text-gray-600 rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-green-100 cursor-pointer appearance-none">
                                <option value="">Semua Tahun Ajaran</option>
                                @php $cy = \Carbon\Carbon::now()->year; @endphp
                                <option value="{{ $cy }}/{{ $cy + 1 }} Genap"
                                    {{ request('tahun_akademik') == "$cy/" . ($cy + 1) . ' Genap' ? 'selected' : '' }}>
                                    {{ $cy }}/{{ $cy + 1 }} Genap</option>
                                <option value="{{ $cy }}/{{ $cy + 1 }} Ganjil"
                                    {{ request('tahun_akademik') == "$cy/" . ($cy + 1) . ' Ganjil' ? 'selected' : '' }}>
                                    {{ $cy }}/{{ $cy + 1 }} Ganjil</option>
                                <option value="{{ $cy - 1 }}/{{ $cy }} Genap"
                                    {{ request('tahun_akademik') == $cy - 1 . "/{$cy} Genap" ? 'selected' : '' }}>
                                    {{ $cy - 1 }}/{{ $cy }} Genap</option>
                                <option value="{{ $cy - 1 }}/{{ $cy }} Ganjil"
                                    {{ request('tahun_akademik') == $cy - 1 . "/{$cy} Ganjil" ? 'selected' : '' }}>
                                    {{ $cy - 1 }}/{{ $cy }} Ganjil</option>
                            </select>
                        </form>
                    </div>

                    <div class="space-y-4 md:space-y-6">
                        @forelse($riwayatKonsultasi ?? [] as $riwayat)
                            <div
                                class="p-5 md:p-6 border border-gray-100 rounded-2xl md:rounded-[24px] transition-all hover:shadow-md {{ $riwayat->status == 'dibalas' || $riwayat->status == 'selesai' ? 'bg-green-50/20' : 'bg-gray-50/50' }}">

                                <!-- LOGIKA JIKA PENGIRIM = BK -->
                                @if (($riwayat->pengirim ?? 'ortu') == 'bk')
                                    <div
                                        class="flex flex-wrap gap-2 md:gap-3 justify-between items-start mb-4 border-b border-gray-100 pb-3 md:pb-4">
                                        <div class="flex flex-wrap items-center gap-2 md:gap-3">
                                            @if (($riwayat->status ?? 'menunggu') == 'menunggu')
                                                <span
                                                    class="text-[8px] md:text-[9px] font-black px-2 md:px-3 py-1 md:py-1.5 rounded-lg uppercase tracking-widest shadow-sm bg-orange-100 text-orange-600 whitespace-nowrap">
                                                    Perlu Konfirmasi
                                                </span>
                                            @else
                                                <span
                                                    class="text-[8px] md:text-[9px] font-black px-2 md:px-3 py-1 md:py-1.5 rounded-lg uppercase tracking-widest shadow-sm bg-green-100 text-green-700 whitespace-nowrap">
                                                    Telah Dikonfirmasi
                                                </span>
                                            @endif
                                            <span
                                                class="text-[10px] md:text-xs text-gray-400 font-bold whitespace-nowrap">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $riwayat->created_at->format('d M Y, H:i') }}
                                            </span>
                                        </div>
                                        <span
                                            class="text-[10px] md:text-xs font-bold text-gray-600 bg-white px-3 md:px-4 py-1.5 rounded-xl border border-blue-200 shadow-sm flex items-center gap-2 shrink-0">
                                            <i class="fas fa-bullhorn text-blue-500"></i> <span
                                                class="hidden sm:inline">Panggilan Sekolah</span>
                                        </span>
                                    </div>

                                    <div class="mb-4 md:mb-5">
                                        <h4
                                            class="font-black text-gray-800 text-xs md:text-sm mb-1.5 uppercase tracking-tight">
                                            {{ $riwayat->topic }}</h4>
                                        <p class="text-[9px] md:text-[10px] text-gray-500 font-bold mb-3"><i
                                                class="fas fa-user-tie mr-1"></i> Dari: <span
                                                class="capitalize">{{ $riwayat->bk->name ?? 'Guru BK' }}</span> (Untuk
                                            <span class="capitalize">{{ $riwayat->student->nama ?? 'Ananda' }}</span>)
                                        </p>
                                        <div
                                            class="bg-blue-50/50 p-3 md:p-4 rounded-xl md:rounded-2xl border border-blue-100 shadow-sm relative">
                                            <div
                                                class="absolute w-3 h-3 bg-blue-50/50 border-t border-l border-blue-100 transform -rotate-45 -top-1.5 left-6">
                                            </div>
                                            <p
                                                class="text-[11px] md:text-xs text-gray-700 leading-relaxed font-medium relative z-10">
                                                {{ $riwayat->message }}</p>
                                        </div>
                                    </div>

                                    @if (($riwayat->status ?? 'menunggu') == 'menunggu')
                                        <form action="{{ route('ortu.konsultasi.balas', $riwayat->id) }}"
                                            method="POST" class="mt-3 md:mt-4">
                                            @csrf
                                            <label
                                                class="block text-[9px] md:text-[10px] font-bold text-[#10b981] uppercase tracking-widest mb-1.5">Konfirmasi
                                                / Balasan Anda:</label>
                                            <textarea name="balasan" rows="2" required placeholder="Ketik kesediaan hadir..."
                                                class="w-full px-4 py-2.5 md:py-3 bg-white border border-gray-200 rounded-xl text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-green-100 transition mb-3"></textarea>
                                            <button type="submit"
                                                class="w-full md:w-auto bg-[#10b981] text-white px-6 py-2.5 rounded-xl text-[10px] md:text-xs font-bold uppercase hover:bg-green-600 transition shadow-md shadow-green-100 flex items-center justify-center">
                                                <i class="fas fa-paper-plane mr-2"></i> Kirim Konfirmasi
                                            </button>
                                        </form>
                                    @else
                                        <div class="pl-4 md:pl-6 border-l-2 border-[#10b981] relative mt-2">
                                            <div
                                                class="absolute -left-2 top-0 w-3 md:w-3.5 h-3 md:h-3.5 bg-[#10b981] rounded-full border-2 border-white">
                                            </div>
                                            <p
                                                class="text-[9px] md:text-[10px] font-black text-[#10b981] uppercase tracking-widest mb-1.5 flex items-center gap-2">
                                                <i class="fas fa-reply"></i> Balasan Anda
                                            </p>
                                            <div
                                                class="bg-green-50/50 p-3 md:p-4 rounded-xl md:rounded-2xl border border-green-100/50">
                                                <p
                                                    class="text-[11px] md:text-xs text-gray-700 font-medium leading-relaxed">
                                                    {{ $riwayat->reply ?? '-' }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- LOGIKA JIKA PENGIRIM = ORTU -->
                                @else
                                    <div
                                        class="flex flex-wrap gap-2 md:gap-3 justify-between items-start mb-4 border-b border-gray-100 pb-3 md:pb-4">
                                        <div class="flex flex-wrap items-center gap-2 md:gap-3">
                                            <span
                                                class="text-[8px] md:text-[9px] font-black px-2 md:px-3 py-1 md:py-1.5 rounded-lg uppercase tracking-widest shadow-sm {{ $riwayat->status == 'menunggu' ? 'bg-orange-100 text-orange-600' : 'bg-green-100 text-green-700' }} whitespace-nowrap">
                                                {{ ucfirst($riwayat->status) }}
                                            </span>
                                            <span
                                                class="text-[10px] md:text-xs text-gray-400 font-bold whitespace-nowrap">
                                                <i class="far fa-clock mr-1"></i>
                                                {{ $riwayat->created_at->format('d M Y, H:i') }}
                                            </span>
                                        </div>
                                        <span
                                            class="text-[10px] md:text-xs font-bold text-gray-600 bg-white px-3 md:px-4 py-1.5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-2 shrink-0 capitalize">
                                            <i class="fas fa-child text-[#10b981]"></i> <span
                                                class="hidden sm:inline">{{ $riwayat->student->nama ?? 'Siswa' }}</span>
                                        </span>
                                    </div>

                                    <div class="mb-4 md:mb-5">
                                        <h4
                                            class="font-black text-gray-800 text-xs md:text-sm mb-2 uppercase tracking-tight">
                                            {{ $riwayat->topic }}</h4>
                                        <div
                                            class="bg-white p-3 md:p-4 rounded-xl md:rounded-2xl border border-gray-100 shadow-sm relative">
                                            <div
                                                class="absolute w-3 h-3 bg-white border-t border-l border-gray-100 transform -rotate-45 -top-1.5 left-6">
                                            </div>
                                            <p
                                                class="text-[11px] md:text-xs text-gray-600 leading-relaxed font-medium relative z-10">
                                                {{ $riwayat->message }}</p>
                                        </div>
                                    </div>

                                    @if ($riwayat->reply)
                                        <div class="pl-4 md:pl-6 border-l-2 border-[#10b981] relative mt-2">
                                            <div
                                                class="absolute -left-2 top-0 w-3 md:w-3.5 h-3 md:h-3.5 bg-[#10b981] rounded-full border-2 border-white">
                                            </div>
                                            <p
                                                class="text-[9px] md:text-[10px] font-black text-[#10b981] uppercase tracking-widest mb-1.5 flex items-center gap-2">
                                                <i class="fas fa-reply"></i> Balasan Sekolah (<span
                                                    class="capitalize">{{ $riwayat->bk->name ?? 'Admin' }}</span>)
                                            </p>
                                            <div
                                                class="bg-green-50/50 p-3 md:p-4 rounded-xl md:rounded-2xl border border-green-100/50">
                                                <p
                                                    class="text-[11px] md:text-xs text-gray-700 font-medium leading-relaxed">
                                                    {{ $riwayat->reply }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                            </div>
                        @empty
                            <div
                                class="text-center p-8 md:p-12 border-2 border-dashed border-gray-200 rounded-3xl md:rounded-[30px] bg-gray-50/50">
                                <div
                                    class="w-16 h-16 md:w-20 md:h-20 bg-white shadow-sm border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-comment-slash text-2xl md:text-3xl text-gray-300"></i>
                                </div>
                                <h4 class="text-sm md:text-base font-black text-gray-700 mb-1 tracking-tight">Belum ada
                                    konsultasi</h4>
                                <p class="text-[10px] md:text-xs text-gray-500 font-medium max-w-xs mx-auto px-4">
                                    Gunakan formulir di samping untuk memulai percakapan atau menanyakan perkembangan
                                    anak Anda.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

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
                    <button onclick="showView('konsultasi')"
                        class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold text-[10px] md:text-xs uppercase tracking-wider hover:bg-gray-200 transition flex items-center justify-center gap-2 border border-gray-200"><i
                            class="fas fa-arrow-left"></i> Kembali</button>
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

    <!-- SCRIPT -->
    <script>
        // Logika Sidebar Mobile Responsif
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

        // LOGIKA UPDATE GURU BK OTOMATIS
        function updateGuruBK() {
            const select = document.getElementById('pilihSiswa');
            if (select && select.options.length > 0) {
                const selectedOption = select.options[select.selectedIndex];
                const bkNama = selectedOption.getAttribute('data-bk-nama');
                const bkId = selectedOption.getAttribute('data-bk-id');

                const namaGuruEl = document.getElementById('namaGuruBK');
                const idGuruEl = document.getElementById('hiddenBkId');

                if (namaGuruEl) namaGuruEl.value = bkNama;
                if (idGuruEl) idGuruEl.value = bkId;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateGuruBK();
        });

        // LOGIKA CUSTOM ALERT
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

        // Logika Pindah View
        function showView(viewId) {
            document.querySelectorAll('.view-section').forEach(view => view.classList.remove('active'));
            document.getElementById('view-' + viewId).classList.add('active');

            const titleEl = document.getElementById('view-title');
            const subtitleEl = document.getElementById('view-subtitle');
            const breadcrumbEl = document.getElementById('breadcrumb-active');

            document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));

            if (viewId === 'konsultasi') {
                if (titleEl) titleEl.innerText = "Layanan Konsultasi";
                if (subtitleEl) subtitleEl.innerText = "Komunikasi Langsung dengan Pihak Sekolah";
                if (breadcrumbEl) breadcrumbEl.innerText = "Konsultasi BK";
                document.getElementById('nav-konsultasi')?.classList.add('active');
            } else if (viewId === 'profile') {
                if (titleEl) titleEl.innerText = "Pengaturan Akun";
                if (subtitleEl) subtitleEl.innerText = "Profil Pribadi Wali Murid";
                if (breadcrumbEl) breadcrumbEl.innerText = "Profil Anda";
                document.getElementById('editModal').classList.add('hidden'); // Tutup modal jika sedang terbuka
            }
        }

        // LOGIKA DROPDOWN PROFIL & AJAX
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
                        console.error('Error:', error);
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
