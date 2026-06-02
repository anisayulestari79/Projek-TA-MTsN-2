<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi Guru BK - Wali Murid</title>
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
            <a href="{{ route('ortu.dashboard') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl duration-300">
                <i class="fas fa-child mr-4 text-lg opacity-80"></i> <span class="font-medium tracking-wide">Kondisi
                    Anak</span>
            </a>

            <!-- Menu Konsultasi Aktif -->
            <a href="#" onclick="showView('konsultasi')" id="nav-konsultasi"
                class="sidebar-item active flex items-center px-6 py-4 transition shadow-sm duration-300">
                <i class="fas fa-comments mr-4 text-lg"></i> <span class="font-bold tracking-wide">Konsultasi BK</span>
            </a>
        </nav>

        <div class="mt-auto pt-20 pb-10">
            <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">
                @csrf
            </form>
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 ml-72 p-10 min-h-screen flex flex-col">
        <!-- GLOBAL HEADER -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                    Home / Anak / <span id="breadcrumb-active" class="text-gray-600">Konsultasi BK</span>
                </nav>
                <h2 id="view-title" class="text-3xl font-black text-gray-700 uppercase tracking-tighter italic">Layanan
                    Konsultasi</h2>
                <p id="view-subtitle" class="text-[10px] text-gray-400 font-bold uppercase mt-1">Komunikasi Langsung
                    dengan Pihak Sekolah</p>
            </div>

            <!-- User Profile & Dropdown -->
            <div class="relative">
                <button id="profileDropdownBtn"
                    class="flex items-center gap-4 bg-white px-6 py-2.5 rounded-full shadow-sm border border-gray-100 transition hover:shadow-md focus:outline-none">
                    <div class="text-right">
                        <p class="text-xs font-black text-[#10b981] uppercase leading-none">
                            {{ Auth::user()->name ?? 'Wali Murid' }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Status: Orang Tua</p>
                    </div>
                    <!-- PERBAIKAN: Memastikan foto yang dikirim dari database (jika ada) menggunakan asset() jika berupa path lokal -->
                    <img src="{{ isset(Auth::user()->photo) && Auth::user()->photo ? (filter_var(Auth::user()->photo, FILTER_VALIDATE_URL) ? Auth::user()->photo : asset('storage/' . Auth::user()->photo)) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'Wali Murid') . '&background=10b981&color=fff' }}"
                        class="w-10 h-10 rounded-full border-2 border-green-50 shadow-sm object-cover" alt="Profile">
                    <i class="fas fa-chevron-down text-gray-400 text-xs ml-1"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdownMenu"
                    class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden z-50 transform origin-top-right transition-all duration-200 opacity-0 scale-95">
                    <div class="py-2">
                        <!-- Mengubah dari href="#" menjadi onclick untuk memanggil view Profil -->
                        <button type="button"
                            onclick="showView('profile'); document.getElementById('profileDropdownMenu').classList.add('hidden');"
                            class="w-full text-left px-6 py-3 text-xs font-bold text-gray-700 hover:bg-green-50 hover:text-[#10b981] transition flex items-center gap-3">
                            <i class="fas fa-user"></i> Profil Anda
                        </button>
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

        <!-- ============================================== -->
        <!-- VIEW: DASHBOARD KONSULTASI -->
        <!-- ============================================== -->
        <div id="view-konsultasi" class="view-section active">
            <!-- ITEMS-START menjaga form tidak memanjang memenuhi layar secara paksa -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <!-- FORM KIRIM PESAN (h-fit agar menyesuaikan kontennya) -->
                <div
                    class="lg:col-span-1 bg-white p-8 rounded-[30px] shadow-sm border border-gray-50 h-fit sticky top-10">
                    <h3
                        class="font-black text-gray-700 text-sm uppercase tracking-widest mb-6 border-b pb-4 flex items-center">
                        <div
                            class="w-8 h-8 bg-green-50 text-[#10b981] rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        Kirim Pesan Baru
                    </h3>

                    <form action="{{ route('ortu.konsultasi.kirim') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Pilih
                                Anak</label>
                            <div class="relative">
                                <i class="fas fa-user-graduate absolute left-4 top-3.5 text-gray-400 text-xs"></i>
                                <select name="siswa_id" required
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition appearance-none">
                                    @foreach ($daftarAnak ?? [] as $anak)
                                        <option value="{{ $anak->id }}">{{ $anak->nama }} (Kelas
                                            {{ $anak->kelas }})</option>
                                    @endforeach
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Topik /
                                Subjek</label>
                            <input type="text" name="topik" required
                                placeholder="Contoh: Klarifikasi Poin Pelanggaran"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Isi
                                Pesan</label>
                            <textarea name="pesan" rows="5" required
                                placeholder="Tuliskan pertanyaan atau keluhan Anda di sini secara detail..."
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-green-100 transition resize-none"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-[#10b981] text-white px-6 py-4 rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:bg-green-600 hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                            Kirim Sekarang <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>

                <!-- TABEL RIWAYAT KONSULTASI -->
                <div class="lg:col-span-2 bg-white p-8 rounded-[40px] shadow-sm border border-gray-50">
                    <h3
                        class="font-black text-gray-700 text-sm uppercase tracking-widest mb-8 border-b pb-4 flex items-center">
                        <div class="w-8 h-8 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-history"></i>
                        </div>
                        Riwayat Konsultasi Anda
                    </h3>

                    <div class="space-y-6">
                        @forelse($riwayatKonsultasi ?? [] as $riwayat)
                            <div
                                class="p-6 border border-gray-100 rounded-[24px] transition-all hover:shadow-md {{ $riwayat->status == 'dibalas' ? 'bg-green-50/20' : 'bg-gray-50/50' }}">

                                <!-- Header Riwayat -->
                                <div
                                    class="flex flex-wrap gap-3 justify-between items-start mb-4 border-b border-gray-100 pb-4">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="text-[9px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest shadow-sm
                                            {{ $riwayat->status == 'menunggu' ? 'bg-orange-100 text-orange-600' : ($riwayat->status == 'dibalas' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600') }}">
                                            {{ ucfirst($riwayat->status) }}
                                        </span>
                                        <span class="text-xs text-gray-400 font-bold">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $riwayat->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                    <span
                                        class="text-xs font-bold text-gray-600 bg-white px-4 py-1.5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-2">
                                        <i class="fas fa-child text-[#10b981]"></i>
                                        {{ $riwayat->student->nama ?? 'Siswa' }}
                                    </span>
                                </div>

                                <!-- Body Pesan Orang Tua -->
                                <div class="mb-5">
                                    <h4 class="font-black text-gray-800 text-sm mb-2 uppercase tracking-tight">
                                        {{ $riwayat->topic }}</h4>
                                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm relative">
                                        <div
                                            class="absolute w-3 h-3 bg-white border-t border-l border-gray-100 transform -rotate-45 -top-1.5 left-6">
                                        </div>
                                        <p class="text-xs text-gray-600 leading-relaxed font-medium relative z-10">
                                            {{ $riwayat->message }}</p>
                                    </div>
                                </div>

                                <!-- Balasan Guru BK/Admin -->
                                @if ($riwayat->reply)
                                    <div class="pl-6 border-l-2 border-[#10b981] relative mt-2">
                                        <div
                                            class="absolute -left-2 top-0 w-3.5 h-3.5 bg-[#10b981] rounded-full border-2 border-white">
                                        </div>
                                        <p
                                            class="text-[10px] font-black text-[#10b981] uppercase tracking-widest mb-2 flex items-center gap-2">
                                            <i class="fas fa-reply"></i> Balasan Sekolah
                                        </p>
                                        <div class="bg-green-50/50 p-4 rounded-2xl border border-green-100/50">
                                            <p class="text-xs text-gray-700 font-medium leading-relaxed">
                                                {{ $riwayat->reply }}</p>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @empty
                            <!-- Empty State -->
                            <div
                                class="text-center p-12 border-2 border-dashed border-gray-200 rounded-[30px] bg-gray-50/50">
                                <div
                                    class="w-20 h-20 bg-white shadow-sm border border-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-comment-slash text-3xl text-gray-300"></i>
                                </div>
                                <h4 class="text-base font-black text-gray-700 mb-1 tracking-tight">Belum ada konsultasi
                                </h4>
                                <p class="text-xs text-gray-500 font-medium max-w-xs mx-auto">Gunakan formulir di
                                    samping untuk memulai percakapan atau menanyakan perkembangan anak Anda.</p>
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
            <div class="bg-white p-8 rounded-[40px] shadow-sm border border-gray-50 max-w-2xl mx-auto">
                <!-- Profile Display -->
                <div id="profileView" class="flex flex-col items-center">
                    <div
                        class="w-32 h-32 rounded-full overflow-hidden mb-4 border-4 border-green-50 shadow-sm relative group">
                        <!-- PERBAIKAN: Menggunakan urlencode pada gambar profil besar -->
                        @if (isset(Auth::user()->photo) && Auth::user()->photo)
                            <img src="{{ filter_var(Auth::user()->photo, FILTER_VALIDATE_URL) ? Auth::user()->photo : asset('storage/' . Auth::user()->photo) }}"
                                class="w-full h-full object-cover" id="mainProfilePic" alt="Profile Picture">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Wali Murid') }}&background=10b981&color=fff&size=128"
                                class="w-full h-full object-cover" id="mainProfilePic" alt="Profile Picture">
                        @endif
                    </div>
                    <h3 class="text-2xl font-black text-gray-800 uppercase">{{ Auth::user()->name ?? 'Wali Murid' }}
                    </h3>
                    <p class="text-xs font-bold text-[#10b981] uppercase tracking-widest mb-8">Orang Tua / Wali Siswa
                    </p>

                    @php
                        $user = Auth::user();
                    @endphp
                    <div class="w-full space-y-4">
                        <div class="flex items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-envelope text-[#10b981] w-10 text-center text-xl"></i>
                            <div class="ml-4">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Username /
                                    Email</p>
                                <p class="text-sm font-black text-gray-700">
                                    {{ $user->username ?? ($user->email ?? '-') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-venus-mars text-[#10b981] w-10 text-center text-xl"></i>
                            <div class="ml-4">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Gender</p>
                                <p class="text-sm font-black text-gray-700">{{ $user->gender ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-phone-alt text-[#10b981] w-10 text-center text-xl"></i>
                            <div class="ml-4">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No. Telepon /
                                    WhatsApp</p>
                                <p class="text-sm font-black text-gray-700">{{ $user->phone ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="toggleEditProfile(true)"
                        class="mt-8 bg-[#10b981] text-white px-8 py-4 rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-105 transition w-full">
                        <i class="fas fa-user-edit mr-2"></i> Edit Profil
                    </button>
                </div>

                <!-- Profile Form -->
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
                            <input type="text" name="name" value="{{ $user->name ?? '' }}" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Username
                                / Email</label>
                            <input type="text" value="{{ $user->username ?? ($user->email ?? '') }}" disabled
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Gender</label>
                            <select name="gender"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                                <option value="">Pilih Gender</option>
                                <option value="Laki-laki"
                                    {{ ($user->gender ?? '') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan"
                                    {{ ($user->gender ?? '') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">No.
                                Telepon / WhatsApp</label>
                            <input type="tel" name="phone" value="{{ $user->phone ?? '' }}"
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

    <!-- SCRIPT (Logika Toggle & Detail Riwayat) -->
    <script>
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
                toggleEditProfile(false); // Pastikan mode view (bukan form) saat pertama dibuka
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

        // Fungsi bantuan untuk alert notifikasi profile
        function showAlert(type, message) {
            const alertBox = document.getElementById('liveAlert');
            if (!alertBox) return; // Mencegah error jika div tidak ada

            alertBox.classList.remove('hidden', 'bg-green-100', 'text-green-700', 'bg-red-100', 'text-red-700');

            if (type === 'success') {
                alertBox.classList.add('bg-green-100', 'text-green-700');
                alertBox.innerHTML = `<i class="fas fa-check-circle mr-2"></i> ${message}`;
            } else {
                alertBox.classList.add('bg-red-100', 'text-red-700');
                alertBox.innerHTML = `<i class="fas fa-exclamation-triangle mr-2"></i> ${message}`;
            }
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
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

            // ==========================================
            // LOGIKA SUBMIT FORM PROFIL VIA AJAX
            // ==========================================
            const profileForm = document.getElementById('profileForm');
            if (profileForm) {
                profileForm.addEventListener('submit', async function(e) {
                    e.preventDefault(); // Mencegah form memuat ulang halaman secara paksa

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
