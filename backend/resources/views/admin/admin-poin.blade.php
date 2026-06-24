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
            <a href="{{ route('admin.tahunajaran.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-calendar-alt mr-4 text-sm"></i> <span class="font-medium">Tahun Ajaran</span>
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
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Status: Administrator</p>
                    </div>

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
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdownMenu"
                    class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden z-50 transform origin-top-right transition-all duration-200 opacity-0 scale-95">
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

                <form id="poinForm" class="space-y-6" onsubmit="submitPoinForm(event)" enctype="multipart/form-data">
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

                    <!-- BAGIAN FITUR KAMERA / UPLOAD FOTO (Baru) -->
                    <div class="mt-4 border-t border-gray-50 pt-5 md:pt-6">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Unggah Bukti Foto
                            (Opsional)</label>
                        <div class="flex items-center gap-3 md:gap-4">
                            <div class="relative flex-1">
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

                    <div class="flex gap-4 pt-4 border-t border-gray-50 mt-4">
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

                <div class="overflow-x-auto border border-gray-50 rounded-xl">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead class="bg-[#005c4b] text-white text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="py-4 pl-6 rounded-tl-xl w-40">Waktu Masuk</th>
                                <th class="py-4">NISN / Nama Siswa</th>
                                <th class="py-4 text-center">Kelas</th>
                                <th class="py-4">Keterangan Pelanggaran</th>
                                <th class="py-4 text-center">Foto Bukti</th> <!-- Kolom Baru -->
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
    </main>

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
        // FUNGSI PREVIEW FOTO
        // ==========================================
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

                            let fotoPreview = row.foto_bukti ?
                                `<button type="button" onclick="openFotoBuktiModal('/storage/${row.foto_bukti}')" class="inline-flex bg-green-50 text-green-600 px-2 py-1.5 rounded-lg text-[9px] font-bold hover:bg-green-100 transition items-center gap-1 border border-green-100 shadow-sm cursor-pointer whitespace-nowrap"><i class="fas fa-search-plus"></i> Lihat Foto</button>` :
                                '<span class="text-[10px] text-gray-400 italic font-medium bg-gray-50 px-2 py-1 rounded-lg border border-gray-100">Tanpa Bukti</span>';

                            let badgeClass = row.jenis === 'Tambah' ?
                                'bg-red-50 text-red-600 border border-red-200' :
                                'bg-green-50 text-green-600 border border-green-200';
                            let prefixSign = row.jenis === 'Tambah' ? '+' : '-';

                            tr.innerHTML = `
                                <td class="py-5 pl-6 italic text-gray-400 font-medium whitespace-nowrap">${dateFormatted}</td>
                                <td class="py-5">
                                    <p class="font-black text-gray-700">${row.nama}</p>
                                    <p class="text-[10px] text-gray-400 uppercase">${row.nisn}</p>
                                </td>
                                <td class="py-5 text-center font-bold text-gray-500">${row.kelas}</td>
                                <td class="py-5 font-bold text-gray-600 max-w-xs">${row.ket}</td>
                                <td class="py-5 text-center">${fotoPreview}</td>
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
                            '<tr><td colspan="7" class="py-10 text-center text-gray-400 font-bold">Belum ada riwayat pelanggaran.</td></tr>';
                    }
                })
                .catch(err => {
                    console.error("Gagal memuat riwayat:", err);
                    const tbody = document.getElementById('riwayatTableBody');
                    tbody.innerHTML =
                        '<tr><td colspan="7" class="py-10 text-center text-red-400 font-bold">Gagal mengambil data riwayat. Cek Console.</td></tr>';
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
            const fileInput = document.getElementById('p_foto_bukti');

            if (!nisn || !jumlah || !ket) {
                showAlert('error',
                    'Data belum lengkap! Pastikan Anda telah memilih siswa dan jenis pelanggaran dari daftar dropdown.');
                return;
            }

            const formData = new FormData();
            formData.append('nisn', nisn);
            formData.append('jumlah', jumlah);
            formData.append('ket', ket);
            if (fileInput && fileInput.files[0]) {
                formData.append('foto_bukti', fileInput.files[0]);
            }

            fetch('/admin/poin/add', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        // Menghapus 'Content-Type' agar browser otomatis menyetel multipart/form-data
                    },
                    body: formData
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
        // FUNGSI BANTUAN & MODAL POPUP
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

            // Reset preview foto
            const previewContainer = document.getElementById('imagePreviewContainer');
            const previewImage = document.getElementById('imagePreview');
            if (previewImage) previewImage.src = "#";
            if (previewContainer) previewContainer.classList.add('hidden');
        }

        window.openFotoBuktiModal = function(url) {
            const modal = document.getElementById('fotoBuktiModal');
            const content = document.getElementById('fotoBuktiContent');
            const img = document.getElementById('fotoBuktiImage');
            const loading = document.getElementById('fotoBuktiLoading');
            const downloadBtn = document.getElementById('downloadFotoBtn');

            img.classList.add('hidden');
            loading.classList.remove('hidden');

            img.src = url;
            downloadBtn.href = url;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

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
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    img.src = "";
                }, 200);
            }
        };

        // Esc key to close modal
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                const fotoModal = document.getElementById('fotoBuktiModal');
                if (fotoModal && !fotoModal.classList.contains('hidden')) {
                    closeFotoBuktiModal();
                }
            }
        });

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
        });
    </script>
</body>

</html>
