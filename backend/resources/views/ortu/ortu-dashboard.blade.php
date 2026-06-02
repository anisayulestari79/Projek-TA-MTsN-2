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
    </style>
</head>

<body class="bg-[#f4f7f6] font-sans flex text-gray-800">

    <!-- SIDEBAR -->
    <aside class="w-72 min-h-screen bg-[#10b981] text-white flex flex-col fixed shadow-xl z-50">
        <div class="p-8">
            <div class="flex items-center gap-3 mb-2">
                <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                    class="w-10 drop-shadow-md" alt="Logo Kemenag">
                <h1 class="font-bold text-xl leading-tight tracking-tight uppercase">Monitoring <br> Wali Murid</h1>
            </div>
            <p class="text-[10px] opacity-80 font-medium tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
        </div>

        <nav class="mt-4 flex-grow pl-6">
            <a href="#" class="sidebar-item active flex items-center px-6 py-4 transition">
                <i class="fas fa-child mr-4 text-sm"></i> <span>Kondisi Anak</span>
            </a>
            <a href="#" class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-history mr-4 text-sm"></i> <span class="font-medium">Riwayat Poin</span>
            </a>
            <!-- Spasi untuk mendorong tombol keluar ke bawah -->
            <div class="mt-auto mb-10">
                <form action="{{ route('ortu.logout') }}" method="POST" id="logout-form" class="hidden">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                   class="sidebar-item flex items-center px-6 py-4 hover:bg-red-500/50 transition rounded-l-xl text-red-100">
                    <i class="fas fa-sign-out-alt mr-4 text-sm"></i> <span class="font-medium">Keluar</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 ml-72 p-10">
        @php
            // Menghitung status dan visualisasi lingkaran poin secara dinamis
            $poinAktif = $siswaAktif->poin ?? 0;
            
            // Persentase lingkaran (Radius = 76, Keliling = 2 * pi * r = ~477)
            $persenPoin = min($poinAktif / 100, 1);
            $dashoffset = 477 - (477 * $persenPoin); 

            // Logika Status Sanksi Dinamis
            $statusWarna = 'green';
            $statusTeks = 'Aman & Disiplin';
            if($poinAktif >= 100) { $statusWarna = 'red'; $statusTeks = 'Dikeluarkan (DO)'; }
            elseif($poinAktif >= 50) { $statusWarna = 'orange'; $statusTeks = 'Panggilan Tahap II'; }
            elseif($poinAktif >= 25) { $statusWarna = 'yellow'; $statusTeks = 'Waspada Panggilan I'; }
        @endphp

        <!-- GLOBAL HEADER -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                    Home / Anak / <span class="text-gray-600">Kondisi</span>
                </nav>
                <h2 class="text-2xl font-black text-gray-700 uppercase tracking-tighter italic">
                    Kondisi Anak
                </h2>
                <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Monitoring Kedisiplinan Real-Time</p>
            </div>
            
            <!-- User Profile & Dropdown -->
            <div class="relative">
                <button id="profileDropdownBtn"
                    class="flex items-center gap-4 bg-white px-6 py-2 rounded-full shadow-sm border border-gray-100 hover:bg-gray-50 transition focus:outline-none">
                    <div class="text-right">
                        <p class="text-xs font-black text-[#10b981] uppercase leading-none">{{ Auth::user()->name ?? 'Wali Murid' }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Wali Dari {{ count($daftarAnak) }} Anak</p>
                    </div>
                    <img src="{{ Auth::user()->photo ?? 'https://ui-avatars.com/api/?name=Wali+Murid&background=10b981&color=fff' }}"
                        class="w-10 h-10 rounded-full border-2 border-green-50 shadow-sm" alt="Profile">
                    <i class="fas fa-chevron-down text-gray-400 text-xs ml-1"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdownMenu"
                    class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden z-50 transform origin-top-right transition-all duration-200 opacity-0 scale-95">
                    <div class="py-2">
                        <a href="#" class="w-full text-left px-6 py-3 text-xs font-bold text-gray-700 hover:bg-green-50 hover:text-[#10b981] transition flex items-center gap-3">
                            <i class="fas fa-user"></i> Profil Siswa
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                           class="w-full text-left px-6 py-3 text-xs font-bold text-red-600 hover:bg-red-50 transition flex items-center gap-3">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- TAB PILIH ANAK (Akan Muncul Jika Anak > 1 di Database) -->
        @if(isset($daftarAnak) && count($daftarAnak) > 1)
        <div class="mb-8">
            <h3 class="font-black text-gray-400 text-[10px] uppercase tracking-widest mb-3">Pilih Profil Anak Anda</h3>
            <div class="flex gap-4 overflow-x-auto pb-2">
                @foreach($daftarAnak as $anak)
                <a href="?siswa_id={{ $anak->id }}" 
                   class="flex items-center gap-3 px-6 py-3 rounded-2xl shadow-sm border transition whitespace-nowrap {{ $siswaAktif->id == $anak->id ? 'bg-[#10b981] text-white border-green-500' : 'bg-white text-gray-600 border-gray-100 hover:bg-gray-50' }}">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($anak->nama) }}&background={{ $siswaAktif->id == $anak->id ? 'fff' : '10b981' }}&color={{ $siswaAktif->id == $anak->id ? '10b981' : 'fff' }}" 
                         class="w-8 h-8 rounded-full border-2 {{ $siswaAktif->id == $anak->id ? 'border-white/50' : 'border-green-50' }}" alt="Siswa">
                    <div class="text-left">
                        <p class="text-xs font-bold uppercase tracking-wider">{{ $anak->nama }}</p>
                        <p class="text-[10px] {{ $siswaAktif->id == $anak->id ? 'text-green-100' : 'text-gray-400' }} uppercase">{{ $anak->kelas }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- SECTION: STATUS KEDISIPLINAN -->
        <div class="mb-10">
            @if($siswaAktif)
            <!-- Hero Card Info Poin -->
            <div class="bg-white p-10 rounded-[40px] shadow-sm border border-gray-50 flex flex-col md:flex-row items-center gap-12 relative overflow-hidden">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-gray-50 rounded-full opacity-50 blur-3xl"></div>

                <!-- Lingkaran Angka Poin -->
                <div class="relative flex items-center justify-center shrink-0 z-10">
                    <svg class="w-44 h-44 transform -rotate-90">
                        <circle class="text-gray-100" stroke-width="14" stroke="currentColor" fill="transparent" r="76" cx="88" cy="88" />
                        <circle class="text-{{ $statusWarna }}-500 transition-all duration-1000 ease-out" stroke-width="14" stroke-dasharray="477" stroke-dashoffset="{{ $dashoffset }}" stroke-linecap="round" stroke="currentColor" fill="transparent" r="76" cx="88" cy="88" />
                    </svg>
                    <div class="absolute flex flex-col items-center">
                        <span class="text-5xl font-black text-gray-800 leading-none">{{ $poinAktif }}</span>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-2">Poin Total</span>
                    </div>
                </div>

                <!-- Deskripsi Status -->
                <div class="flex-1 relative z-10 text-center md:text-left">
                    <div class="inline-block px-4 py-1.5 bg-{{ $statusWarna }}-100 text-{{ $statusWarna }}-700 rounded-full text-[10px] font-black uppercase tracking-widest mb-4 border border-{{ $statusWarna }}-200">
                        Status: {{ $statusTeks }}
                    </div>
                    <h3 class="text-2xl font-black text-gray-700 uppercase tracking-tight mb-3">
                        Laporan Ananda <span class="text-[#10b981]">{{ $siswaAktif->nama }}</span>
                    </h3>
                    <p class="text-gray-500 text-sm font-medium leading-relaxed mb-6 max-w-lg">
                        Berdasarkan rekaman kedisiplinan sekolah, ananda saat ini memiliki <strong class="text-{{ $statusWarna }}-600">{{ $poinAktif }} Poin Pelanggaran</strong>. 
                        @if($poinAktif >= 100)
                            Batas maksimal poin telah terlampaui. Mohon segera menghadap ke ruang BK/Kepala Madrasah.
                        @elseif($poinAktif >= 25)
                            Kami mengharapkan perhatian dan kerjasama Bapak/Ibu untuk membimbing perilaku disiplin ananda di rumah.
                        @else
                            Perilaku ananda di sekolah terpantau sangat baik. Pertahankan terus akhlak mulia ini!
                        @endif
                    </p>
                    <button class="bg-[#10b981] hover:bg-green-600 transition text-white px-6 py-3 rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100">
                        <i class="fas fa-list-ul mr-2"></i> Lihat Detail Riwayat
                    </button>
                </div>
            </div>
            @else
            <div class="bg-white p-10 rounded-[40px] shadow-sm border border-gray-50 text-center">
                <i class="fas fa-user-slash text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500 font-bold">Belum ada siswa yang dikaitkan dengan akun Anda.</p>
            </div>
            @endif
        </div>

        <!-- SECTION: TAHAPAN SANKSI / PERINGATAN -->
        @if($siswaAktif)
        <h3 class="font-black text-gray-700 text-sm uppercase tracking-widest mb-6 flex items-center">
            <i class="fas fa-exclamation-triangle mr-3 text-red-500"></i> Peringatan Ambang Batas Sanksi
        </h3>

        <!-- Grid 3 Kartu Sanksi -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 relative">
            
            <!-- Tahap I (25 Poin) -->
            <div class="p-8 rounded-[30px] {{ $poinAktif >= 25 ? 'bg-red-50 border border-red-200 shadow-sm' : 'bg-white border border-gray-100 opacity-80' }} relative overflow-hidden flex flex-col justify-between transition-all">
                @if($poinAktif >= 25)
                    <div class="absolute top-0 right-0 w-24 h-24 bg-red-100 rounded-bl-full opacity-50"></div>
                @endif
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-[10px] font-black {{ $poinAktif >= 25 ? 'text-red-600' : 'text-gray-400' }} uppercase tracking-widest">Tahap I</p>
                        <span class="{{ $poinAktif >= 25 ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-500' }} px-3 py-1 rounded-lg font-black text-[10px]">Batas 25 Poin</span>
                    </div>
                    <h4 class="text-lg font-black {{ $poinAktif >= 25 ? 'text-red-800' : 'text-gray-700' }} uppercase mb-2 leading-tight">Panggilan <br> Orang Tua I</h4>
                    <p class="text-xs {{ $poinAktif >= 25 ? 'text-red-600' : 'text-gray-500' }} font-medium">Pemanggilan pertama wali murid untuk pembinaan intensif siswa.</p>
                </div>
                <div class="mt-8 pt-4 border-t {{ $poinAktif >= 25 ? 'border-red-200/50' : 'border-gray-100' }} relative z-10 flex items-center gap-2">
                    @if($poinAktif >= 25)
                        <i class="fas fa-times-circle text-red-500 text-lg"></i>
                        <span class="text-[10px] font-bold text-red-700 uppercase tracking-wider">Telah Terlewati</span>
                    @else
                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        <span class="text-[10px] font-bold text-green-600 uppercase tracking-wider">Status Aman</span>
                    @endif
                </div>
            </div>

            <!-- Tahap II (50 Poin) -->
            <div class="p-8 rounded-[30px] {{ $poinAktif >= 50 ? 'bg-red-50 border border-red-200' : ($poinAktif >= 25 ? 'bg-white border-2 border-yellow-400 shadow-xl' : 'bg-white border border-gray-100 opacity-80') }} relative overflow-hidden flex flex-col justify-between transition-all">
                @if($poinAktif >= 25 && $poinAktif < 50)
                    <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-50 rounded-bl-full"></div>
                @endif
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-[10px] font-black {{ $poinAktif >= 50 ? 'text-red-600' : ($poinAktif >= 25 ? 'text-yellow-600' : 'text-gray-400') }} uppercase tracking-widest">Tahap II</p>
                        <span class="{{ $poinAktif >= 50 ? 'bg-red-600 text-white' : ($poinAktif >= 25 ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-500') }} px-3 py-1 rounded-lg font-black text-[10px]">Batas 50 Poin</span>
                    </div>
                    <h4 class="text-xl font-black {{ $poinAktif >= 50 ? 'text-red-800' : 'text-gray-800' }} uppercase mb-2 leading-tight">Panggilan <br> Orang Tua II</h4>
                    <p class="text-xs {{ $poinAktif >= 50 ? 'text-red-600' : 'text-gray-500' }} font-medium">Surat perjanjian materai dan skorsing tertulis.</p>
                </div>
                
                @if($poinAktif >= 50)
                    <div class="mt-8 pt-4 border-t border-red-200/50 relative z-10 flex items-center gap-2">
                        <i class="fas fa-times-circle text-red-500 text-lg"></i>
                        <span class="text-[10px] font-bold text-red-700 uppercase tracking-wider">Telah Terlewati</span>
                    </div>
                @elseif($poinAktif >= 25)
                    <div class="mt-8 bg-yellow-50 p-4 rounded-2xl border border-yellow-200 relative z-10 flex items-center justify-between">
                        <div>
                            <span class="block text-[9px] font-black text-yellow-600 uppercase tracking-wider mb-1">Ambang Sanksi Terdekat</span>
                            <span class="text-[10px] font-bold text-gray-500">Tersisa</span>
                        </div>
                        <span class="text-xl font-black text-red-500 animate-pulse">{{ 50 - $poinAktif }} Poin</span>
                    </div>
                @else
                    <div class="mt-8 pt-4 border-t border-gray-100 relative z-10 flex items-center gap-2">
                        <i class="fas fa-shield-alt text-green-500 text-lg"></i>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Jarak Masih Aman</span>
                    </div>
                @endif
            </div>

            <!-- Tahap III (100 Poin) -->
            <div class="p-8 rounded-[30px] {{ $poinAktif >= 100 ? 'bg-red-900 border-red-900 shadow-xl' : ($poinAktif >= 50 ? 'bg-white border-2 border-orange-400 shadow-xl' : 'bg-white border border-gray-100 opacity-80') }} relative overflow-hidden flex flex-col justify-between transition-all">
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-[10px] font-black {{ $poinAktif >= 100 ? 'text-red-300' : ($poinAktif >= 50 ? 'text-orange-600' : 'text-gray-400') }} uppercase tracking-widest">Tahap III</p>
                        <span class="{{ $poinAktif >= 100 ? 'bg-red-500 text-white' : ($poinAktif >= 50 ? 'bg-orange-500 text-white' : 'bg-gray-800 text-white') }} px-3 py-1 rounded-lg font-black text-[10px]">Batas 100 Poin</span>
                    </div>
                    <h4 class="text-lg font-black {{ $poinAktif >= 100 ? 'text-white' : 'text-gray-800' }} uppercase mb-2 leading-tight">Dikeluarkan <br> Dari Sekolah</h4>
                    <p class="text-xs {{ $poinAktif >= 100 ? 'text-red-200' : 'text-gray-500' }} font-medium">Siswa dikembalikan seutuhnya ke pengawasan Orang Tua.</p>
                </div>
                
                @if($poinAktif >= 100)
                    <div class="mt-8 pt-4 border-t border-red-700 relative z-10 flex items-center gap-2">
                        <i class="fas fa-ban text-red-400 text-lg"></i>
                        <span class="text-[10px] font-bold text-red-200 uppercase tracking-wider">Drop Out Terjadi</span>
                    </div>
                @elseif($poinAktif >= 50)
                    <div class="mt-8 bg-orange-50 p-4 rounded-2xl border border-orange-200 relative z-10 flex items-center justify-between">
                        <div>
                            <span class="block text-[9px] font-black text-orange-600 uppercase tracking-wider mb-1">Sanksi Keras Terakhir</span>
                            <span class="text-[10px] font-bold text-gray-500">Tersisa</span>
                        </div>
                        <span class="text-xl font-black text-red-600 animate-pulse">{{ 100 - $poinAktif }} Poin</span>
                    </div>
                @else
                    <div class="mt-8 pt-4 border-t border-gray-100 relative z-10 flex items-center gap-2">
                        <i class="fas fa-shield-alt text-green-500 text-lg"></i>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Jarak Masih Aman</span>
                    </div>
                @endif
            </div>

        </div>
        @endif
    </main>

    <script>
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