<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi BK - Sistem Pelanggaran Poin</title>
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
            <a href="{{ route('admin.siswa.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-users mr-4 text-sm"></i> <span class="font-medium">Data Siswa</span>
            </a>
            <!-- MENU KONSULTASI AKTIF MENGGUNAKAN ONCLICK SHOWVIEW -->
            <a href="#" onclick="showView('konsultasi')" id="nav-konsultasi"
                class="sidebar-item active flex items-center px-6 py-4 transition">
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
                    Home / <span id="breadcrumb-active">Konsultasi BK</span>
                </nav>
                <h2 id="view-title" class="text-2xl font-black text-gray-700 uppercase tracking-tighter italic">
                    Monitoring Konsultasi
                </h2>
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
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                role="alert">
                <span class="block sm:inline font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- ============================================== -->
        <!-- SECTION: DATA KONSULTASI -->
        <!-- ============================================== -->
        <div id="view-konsultasi" class="view-section active">
            <!-- STATISTIK KONSULTASI -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 flex items-center gap-5">
                    <div
                        class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-xl">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">
                            Total Konsultasi</p>
                        <!-- DATA ASLI DARI CONTROLLER -->
                        <p class="text-2xl font-black text-gray-700">{{ $totalKonsultasi ?? 0 }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 flex items-center gap-5">
                    <div
                        class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-xl">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">
                            Menunggu Respon</p>
                        <!-- DATA ASLI DARI CONTROLLER -->
                        <p class="text-2xl font-black text-gray-700">{{ $menungguRespon ?? 0 }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 flex items-center gap-5">
                    <div
                        class="w-12 h-12 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center text-xl">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">
                            Selesai / Dibalas</p>
                        <!-- DATA ASLI DARI CONTROLLER -->
                        <p class="text-2xl font-black text-gray-700">{{ $konsultasiSelesai ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- FILTER DAN PENCARIAN -->
            <form action="{{ route('admin.konsultasi.index') }}" method="GET" class="mb-8">
                <div
                    class="bg-white p-6 rounded-3xl shadow-sm border border-gray-50 flex flex-wrap gap-4 items-center justify-between">
                    <div class="flex gap-4 flex-1 min-w-[300px]">
                        <select name="status"
                            class="bg-gray-50 border border-gray-100 px-4 py-3 rounded-2xl text-xs font-bold text-gray-500 outline-none focus:ring-2 focus:ring-green-100">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu
                                Respon</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                            </option>
                        </select>

                        <div class="relative flex-1">
                            <i class="fas fa-search absolute left-4 top-3.5 text-gray-300 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama siswa atau topik..."
                                class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="bg-blue-50 text-blue-600 px-6 py-3 rounded-2xl text-xs font-bold uppercase hover:bg-blue-100 transition shadow-sm">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <button type="button"
                            class="bg-[#10b981] text-white px-6 py-3 rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-105 transition flex items-center gap-2">
                            <i class="fas fa-print"></i> Cetak Rekap
                        </button>
                    </div>
                </div>
            </form>

            <!-- TABEL DATA KONSULTASI -->
            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50">
                <table class="w-full text-left">
                    <thead class="bg-[#005c4b] text-white text-[10px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="py-4 pl-6 rounded-tl-xl">Waktu Masuk</th>
                            <th class="py-4">Siswa</th>
                            <th class="py-4">Topik Konsultasi</th>
                            <th class="py-4">Ditujukan Ke</th>
                            <th class="py-4 text-center">Status</th>
                            <th class="py-4 text-right pr-6 rounded-tr-xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100">
                        @forelse($dataKonsultasi ?? [] as $konsultasi)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-5 pl-6 italic text-gray-400 font-medium">
                                    {{ \Carbon\Carbon::parse($konsultasi->created_at)->format('d-m-Y H:i') }}
                                </td>
                                <td class="py-5">
                                    <p class="font-black text-gray-800 leading-none">
                                        {{ $konsultasi->siswa->nama ?? 'Siswa Terhapus' }}</p>
                                    <p class="text-[9px] text-gray-400 mt-1 uppercase">Ortu:
                                        {{ $konsultasi->ortu->name ?? '-' }}</p>
                                </td>
                                <td class="py-5 font-bold text-gray-600">{{ $konsultasi->topik }}</td>
                                <td class="py-5 font-medium text-gray-500">{{ $konsultasi->guru->nama ?? 'Guru BK' }}
                                </td>
                                <td class="py-5 text-center">
                                    @if ($konsultasi->status == 'menunggu')
                                        <span
                                            class="bg-orange-50 text-orange-600 px-3 py-1 rounded-lg font-black text-[10px] uppercase">Menunggu</span>
                                    @else
                                        <span
                                            class="bg-green-50 text-green-600 px-3 py-1 rounded-lg font-black text-[10px] uppercase">Selesai</span>
                                    @endif
                                </td>
                                <td class="py-5 text-right pr-6">
                                    <!-- Tombol menuju halaman detail konsultasi (layout terpisah) -->
                                    <a href="{{ route('admin.konsultasi.show', $konsultasi->id) }}"
                                        class="inline-flex w-8 h-8 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition items-center justify-center"
                                        title="Detail & Balas">
                                        <i class="fas fa-search-plus"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-gray-400 font-bold text-sm">
                                    <i class="fas fa-inbox text-3xl mb-3 text-gray-200 block"></i>
                                    Belum ada data konsultasi yang masuk.
                                </td>
                            </tr>
                        @endforelse

                        <!-- DATA DUMMY UNTUK PREVIEW CANVAS (Hapus bagian ini saat diimplementasikan ke Laravel jika $dataKonsultasi sudah ada) -->
                        @if (!isset($dataKonsultasi))
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-5 pl-6 italic text-gray-400 font-medium">12-05-2026 09:00</td>
                                <td class="py-5">
                                    <p class="font-black text-gray-800 leading-none">Anis Ayu Lestari</p>
                                    <p class="text-[9px] text-gray-400 mt-1 uppercase">Ortu: Bpk. Budi Santoso</p>
                                </td>
                                <td class="py-5 font-bold text-gray-600">Klarifikasi Poin Terlambat</td>
                                <td class="py-5 font-medium text-gray-500">Isna Wardiah, S.Pd.</td>
                                <td class="py-5 text-center">
                                    <span
                                        class="bg-orange-50 text-orange-600 px-3 py-1 rounded-lg font-black text-[10px] uppercase">Menunggu</span>
                                </td>
                                <td class="py-5 text-right pr-6">
                                    <a href="#"
                                        class="inline-flex w-8 h-8 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition items-center justify-center">
                                        <i class="fas fa-search-plus"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <!-- Paginasi -->
                <div class="mt-6">
                    {{ $dataKonsultasi->links() ?? '' }}
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

    <!-- Script Kontrol View & Modal -->
    <script>
        // Logika Pindah View Menu (Profil & Data Konsultasi)
        function showView(viewId) {
            document.querySelectorAll('.view-section').forEach(view => view.classList.remove('active'));
            document.getElementById('view-' + viewId).classList.add('active');

            const titleEl = document.getElementById('view-title');
            const breadcrumbEl = document.getElementById('breadcrumb-active');

            document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));

            if (viewId === 'konsultasi') {
                if (titleEl) titleEl.innerText = "Monitoring Konsultasi";
                if (breadcrumbEl) breadcrumbEl.innerText = "Konsultasi BK";
                document.getElementById('nav-konsultasi')?.classList.add('active');
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
