<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poin Siswa - Sistem Pelanggaran Poin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar-item.active {
            background-color: white;
            color: #10b981;
            border-radius: 10px 0 0 10px;
            font-weight: 800;
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
            <a href="{{ route('admin.konsultasi.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-comments mr-4 text-sm"></i> <span class="font-medium">Konsultasi BK</span>
            </a>
            <!-- MENU POIN SISWA MENGGUNAKAN ONCLICK SHOWVIEW -->
            <a href="#" onclick="showView('poin')" id="nav-poin"
                class="sidebar-item active flex items-center px-6 py-4 transition">
                <i class="fas fa-star mr-4 text-sm"></i> <span>Poin Siswa</span>
            </a>
            <a href="{{ route('admin.audit.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-history mr-4 text-sm"></i> <span class="font-medium">Audit Log</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-72 p-10 relative">
        <header class="flex justify-between items-center mb-10">
            <div>
                <nav id="breadcrumb-active" class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                    Home / Poin Siswa</nav>
                <h2 id="view-title" class="text-2xl font-black text-gray-700 uppercase tracking-tighter italic">Poin
                    Kedisiplinan Siswa
                </h2>
            </div>

            <!-- User Profile & Dropdown -->
            <div class="relative">
                <button id="profileDropdownBtn"
                    class="flex items-center gap-4 bg-white px-6 py-2 rounded-full shadow-sm border border-gray-100 hover:bg-gray-50 transition focus:outline-none">
                    <div class="text-right">
                        <p class="text-xs font-black text-[#10b981] uppercase leading-none">
                            {{ $user['name'] ?? 'Admin User' }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Status:
                            {{ ucfirst($user['role'] ?? 'Administrator') }}</p>
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

        <!-- ============================================== -->
        <!-- SECTION: DATA POIN -->
        <!-- ============================================== -->
        <div id="view-poin" class="view-section active">
            <div id="liveAlert" class="hidden px-4 py-3 rounded-2xl relative mb-6 shadow-sm font-bold"></div>

            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50 mb-8 relative z-30">
                <div class="mb-8 border-b pb-6">
                    <h3 class="font-black text-gray-700 text-lg uppercase tracking-widest flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-50 text-[#10b981] rounded-xl flex items-center justify-center">
                            <i class="fas fa-plus"></i>
                        </div>
                        Tambah Poin Pelanggaran siswa
                    </h3>
                </div>

                <form id="poinForm" class="space-y-6" onsubmit="submitPoinForm(event)">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="relative md:col-span-2">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Cari Nama Siswa
                                *</label>
                            <div class="relative">
                                <i class="fas fa-search absolute left-4 top-3.5 text-gray-300 text-xs"></i>
                                <input type="text" id="p_nama" placeholder="Ketik nama siswa..."
                                    autocomplete="off"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:ring-2 focus:ring-green-100 outline-none transition"
                                    required>
                            </div>
                            <div id="nama-list"
                                class="autocomplete-dropdown absolute w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 max-h-48 overflow-y-auto hidden">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Kelas</label>
                            <input type="text" id="p_kelas_display" readonly placeholder="Terisi otomatis"
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-100 rounded-2xl text-sm text-gray-500 cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
                        <div class="md:col-span-3 relative z-40">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Cari & Pilih
                                Pelanggaran
                                *</label>
                            <div class="relative">
                                <i
                                    class="fas fa-exclamation-circle absolute left-4 top-3.5 text-orange-400 text-xs"></i>
                                <input type="text" id="p_search_pelanggaran"
                                    placeholder="Ketik jenis pelanggaran dari database..." autocomplete="off"
                                    class="w-full pl-10 pr-4 py-3 bg-orange-50 border border-orange-100 rounded-2xl text-sm focus:ring-2 focus:ring-orange-200 outline-none transition"
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
                                    class="w-full pl-10 pr-4 py-3 bg-blue-50 border border-blue-100 rounded-2xl text-sm text-blue-700 font-black cursor-not-allowed">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="p_nisn" name="nisn" required>
                    <input type="hidden" id="p_keterangan_pelanggaran" name="ket" required>
                    <input type="hidden" id="p_jumlah_poin" name="jumlah" required>

                    <div class="flex gap-4 pt-4">
                        <button type="submit"
                            class="bg-[#10b981] text-white px-8 py-3 rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-105 transition flex items-center justify-center gap-2 flex-1 md:flex-none">
                            <i class="fas fa-save"></i> Simpan Poin
                        </button>
                        <button type="button" onclick="resetForm()"
                            class="bg-gray-100 text-gray-600 px-8 py-3 rounded-2xl text-xs font-bold uppercase hover:bg-gray-200 transition flex items-center justify-center gap-2">
                            <i class="fas fa-undo"></i> Reset Form
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50 relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b pb-6 gap-4">
                    <div>
                        <h3 class="font-black text-gray-700 text-lg uppercase tracking-widest flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-history"></i>
                            </div>
                            Riwayat Penambahan Poin
                        </h3>
                    </div>

                    @if (($user['role'] ?? 'admin') === 'admin')
                        <button onclick="clearAllRiwayat()"
                            class="bg-red-50 text-red-600 px-6 py-2.5 rounded-xl text-xs font-bold uppercase hover:bg-red-100 transition flex items-center gap-2 border border-red-100">
                            <i class="fas fa-trash-alt"></i> Hapus Semua Riwayat
                        </button>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead class="bg-[#005c4b] text-white text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="py-4 pl-6 rounded-tl-xl">Waktu Masuk</th>
                                <th class="py-4">NISN / Nama Siswa</th>
                                <th class="py-4 text-center">Kelas</th>
                                <th class="py-4">Keterangan Pelanggaran</th>
                                <th class="py-4 text-center">Poin</th>
                                <th class="py-4 pr-6 rounded-tr-xl text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="riwayatTableBody" class="text-xs divide-y divide-gray-100">
                            <!-- Data dimasukkan secara dinamis via JavaScript -->
                        </tbody>
                    </table>
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

    <script>
        // Data referensi pelanggaran dari database
        const rawPelanggaran = @json($dataPelanggaran ?? []);
        const dataPelanggaran = rawPelanggaran.map(item => ({
            poin: item.skor_poin,
            ket: item.jenis,
            sanksi: item.sanksi
        }));

        document.addEventListener("DOMContentLoaded", function() {
            loadRiwayatTable();

            // ==========================================
            // AUTOCOMPLETE PENCARIAN SISWA
            // ==========================================
            const searchSiswa = document.getElementById('p_nama');
            const siswaList = document.getElementById('nama-list');

            searchSiswa.addEventListener('input', function() {
                let query = this.value;

                if (query.length < 2) {
                    siswaList.classList.add('hidden');
                    return;
                }

                fetch(`/admin/poin/search-siswa?q=${query}`)
                    .then(res => res.json())
                    .then(data => {
                        siswaList.innerHTML = '';

                        if (data && data.length > 0) {
                            siswaList.classList.remove('hidden');

                            data.forEach(siswa => {
                                let div = document.createElement('div');
                                div.className =
                                    'px-4 py-3 hover:bg-green-50 cursor-pointer text-sm border-b border-gray-50 flex flex-col transition';
                                div.innerHTML = `
                                    <span class="font-bold text-gray-700">${siswa.nama}</span> 
                                    <span class="text-[10px] text-gray-400">NISN: ${siswa.nisn} | Kelas: ${siswa.kelas}</span>
                                `;

                                div.onclick = function() {
                                    searchSiswa.value = siswa.nama;
                                    document.getElementById('p_nisn').value = siswa.nisn;
                                    document.getElementById('p_kelas_display').value = siswa
                                        .kelas;
                                    siswaList.classList.add('hidden');
                                };
                                siswaList.appendChild(div);
                            });
                        } else {
                            siswaList.innerHTML =
                                '<div class="px-4 py-3 text-sm text-gray-400 italic">Siswa tidak ditemukan</div>';
                            siswaList.classList.remove('hidden');
                        }
                    })
                    .catch(err => console.error("Error fetching siswa:", err));
            });

            // ==========================================
            // AUTOCOMPLETE PENCARIAN PELANGGARAN
            // ==========================================
            const searchPelanggaran = document.getElementById('p_search_pelanggaran');
            const pelanggaranList = document.getElementById('pelanggaran-list');

            function renderPelanggaran(data) {
                pelanggaranList.innerHTML = '';

                if (data.length > 0) {
                    pelanggaranList.classList.remove('hidden');

                    data.forEach(item => {
                        let div = document.createElement('div');
                        div.className =
                            'px-4 py-3 hover:bg-orange-50 cursor-pointer border-b border-gray-50 flex justify-between items-center transition gap-4';
                        div.innerHTML = `
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-700">${item.ket}</span>
                                <span class="text-[10px] text-gray-500 mt-1">
                                    <i class="fas fa-gavel text-red-400 mr-1"></i> Sanksi: ${item.sanksi ?? 'Teguran'}
                                </span>
                            </div>
                            <span class="text-xs font-black bg-red-100 text-red-600 px-3 py-1.5 rounded-lg shrink-0 border border-red-200">+${item.poin} Poin</span>
                        `;

                        div.onclick = function() {
                            searchPelanggaran.value = item.ket;
                            document.getElementById('p_jumlah_display').value = item.poin;
                            document.getElementById('p_keterangan_pelanggaran').value = item.ket;
                            document.getElementById('p_jumlah_poin').value = item.poin;
                            pelanggaranList.classList.add('hidden');
                        };
                        pelanggaranList.appendChild(div);
                    });
                }
            }

            searchPelanggaran.addEventListener('focus', function() {
                if (this.value === '') renderPelanggaran(dataPelanggaran);
            });

            searchPelanggaran.addEventListener('input', function() {
                let query = this.value.toLowerCase();
                let filtered = dataPelanggaran.filter(item => item.ket.toLowerCase().includes(query));
                renderPelanggaran(filtered);
            });

            // Sembunyikan dropdown jika klik di luar area
            document.addEventListener('click', function(e) {
                if (!searchSiswa.contains(e.target) && !siswaList.contains(e.target)) {
                    siswaList.classList.add('hidden');
                }
                if (!searchPelanggaran.contains(e.target) && !pelanggaranList.contains(e.target)) {
                    pelanggaranList.classList.add('hidden');
                }
            });
        });

        // ==========================================
        // FUNGSI AJAX: MUAT RIWAYAT
        // ==========================================
        function loadRiwayatTable() {
            // Menggunakan URL langsung
            fetch(`/admin/poin/riwayat-data`)
                .then(res => res.json())
                .then(res => {
                    const tbody = document.getElementById('riwayatTableBody');
                    tbody.innerHTML = '';

                    if (res.success && res.data.length > 0) {
                        res.data.forEach(row => {
                            let tr = document.createElement('tr');
                            tr.className = 'hover:bg-gray-50 transition';

                            let dateFormatted = new Date(row.waktu).toLocaleString('id-ID', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            let badgeClass = row.jenis === 'Tambah' ? 'bg-red-50 text-red-600' :
                                'bg-green-50 text-green-600';
                            let prefixSign = row.jenis === 'Tambah' ? '+' : '-';

                            tr.innerHTML = `
                                <td class="py-5 pl-6 italic text-gray-400 font-medium whitespace-nowrap">${dateFormatted}</td>
                                <td class="py-5">
                                    <p class="font-black text-gray-700">${row.nama}</p>
                                    <p class="text-[10px] text-gray-400 uppercase">${row.nisn}</p>
                                </td>
                                <td class="py-5 text-center font-bold text-gray-500">${row.kelas}</td>
                                <td class="py-5 font-bold text-gray-600 max-w-xs">${row.ket}</td>
                                <td class="py-5 text-center">
                                    <span class="${badgeClass} px-3 py-1 rounded-lg font-black text-[10px]">${prefixSign}${row.jumlah}</span>
                                </td>
                                <td class="py-5 text-right pr-6">
                                    <button onclick="deleteRiwayatItem(${row.id})" class="text-red-400 hover:text-red-600 p-2 bg-red-50 rounded-lg hover:bg-red-100">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        });
                    } else {
                        tbody.innerHTML =
                            '<tr><td colspan="6" class="py-10 text-center text-gray-400 font-bold">Belum ada riwayat pelanggaran.</td></tr>';
                    }
                })
                .catch(err => {
                    console.error("Gagal memuat riwayat:", err);
                    const tbody = document.getElementById('riwayatTableBody');
                    tbody.innerHTML =
                        '<tr><td colspan="6" class="py-10 text-center text-red-400 font-bold">Gagal mengambil data riwayat. Cek Console.</td></tr>';
                });
        }

        // ==========================================
        // FUNGSI AJAX: SIMPAN POIN
        // ==========================================
        function submitPoinForm(e) {
            e.preventDefault();

            const nisn = document.getElementById('p_nisn').value;
            const jumlah = document.getElementById('p_jumlah_poin').value;
            const ket = document.getElementById('p_keterangan_pelanggaran').value;

            if (!nisn || !jumlah || !ket) {
                showAlert('error',
                    'Data belum lengkap! Pastikan Anda telah memilih siswa dan jenis pelanggaran dari daftar dropdown.');
                return;
            }

            const formData = {
                nisn: nisn,
                jumlah: jumlah,
                ket: ket
            };

            fetch('/admin/poin/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        showAlert('success', res.message);
                        resetForm();
                        loadRiwayatTable();
                    } else {
                        showAlert('error', res.message || 'Gagal menyimpan data poin.');
                    }
                })
                .catch(err => {
                    console.error("Error Simpan Poin:", err);
                    showAlert('error', 'Terjadi kesalahan koneksi sistem saat menyimpan data.');
                });
        }

        // ==========================================
        // FUNGSI AJAX: HAPUS ITEM RIWAYAT
        // ==========================================
        function deleteRiwayatItem(id) {
            if (!confirm('Batalkan poin ini? Poin siswa akan dikembalikan otomatis.')) return;

            // Menggunakan URL langsung dan Token CSRF di header
            fetch(`/admin/poin/riwayat/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        showAlert('success', res.message);
                        loadRiwayatTable();
                    } else {
                        showAlert('error', res.message || 'Gagal menghapus riwayat.');
                    }
                })
                .catch(err => {
                    console.error("Error Hapus Riwayat:", err);
                    showAlert('error', 'Terjadi kesalahan sistem saat menghapus data.');
                });
        }

        // ==========================================
        // FUNGSI AJAX: HAPUS SEMUA RIWAYAT
        // ==========================================
        function clearAllRiwayat() {
            if (!confirm('Yakin menghapus SELURUH log riwayat di madrasah?')) return;

            // Menggunakan URL langsung dan Token CSRF di header
            fetch(`/admin/poin/riwayat-clear`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        showAlert('success', res.message);
                        loadRiwayatTable();
                    } else {
                        showAlert('error', res.message || 'Gagal menghapus seluruh riwayat.');
                    }
                })
                .catch(err => {
                    console.error("Error Hapus Semua Riwayat:", err);
                    showAlert('error', 'Terjadi kesalahan sistem saat menghapus seluruh data.');
                });
        }

        // ==========================================
        // FUNGSI BANTUAN
        // ==========================================
        function showAlert(type, message) {
            const alertBox = document.getElementById('liveAlert');
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

        function resetForm() {
            document.getElementById('poinForm').reset();
            document.getElementById('p_nisn').value = '';
            document.getElementById('p_keterangan_pelanggaran').value = '';
            document.getElementById('p_jumlah_poin').value = '';
        }

        // ==========================================
        // SCRIPT KONTROL VIEW & MODAL PROFIL
        // ==========================================
        function showView(viewId) {
            document.querySelectorAll('.view-section').forEach(view => view.classList.remove('active'));
            document.getElementById('view-' + viewId).classList.add('active');

            const titleEl = document.getElementById('view-title');
            const breadcrumbEl = document.getElementById('breadcrumb-active');

            document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));

            if (viewId === 'poin') {
                if (titleEl) titleEl.innerText = "Poin Kedisiplinan Siswa";
                if (breadcrumbEl) breadcrumbEl.innerText = "Home / Poin Siswa";
                document.getElementById('nav-poin')?.classList.add('active');
            } else if (viewId === 'profile') {
                if (titleEl) titleEl.innerText = "Profil Pengguna";
                if (breadcrumbEl) breadcrumbEl.innerText = "Home / Profil";
                toggleEditProfile(false);
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
