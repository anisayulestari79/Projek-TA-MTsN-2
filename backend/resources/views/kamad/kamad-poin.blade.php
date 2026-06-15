<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Poin Siswa - Kepala Madrasah</title>
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

        /* Pengaturan Khusus Print CSS tambahan agar margin kertas rapi */
        @media print {
            @page {
                margin: 1.5cm;
            }

            body {
                background-color: white !important;
            }
        }
    </style>
</head>

<body class="bg-[#f4f7f6] font-sans flex text-gray-800 print:bg-white print:text-black">

    <!-- SIDEBAR (Disembunyikan saat di-print) -->
    <aside class="w-72 min-h-screen bg-[#10b981] text-white flex flex-col fixed shadow-xl z-50 print:hidden">
        <div class="p-8">
            <div class="flex items-center gap-3 mb-2">
                <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                    class="w-10 drop-shadow-md" alt="Logo Kemenag">
                <h1 class="font-bold text-2xl leading-tight tracking-tight uppercase">Panel <br> Pimpinan <br> Madrasah
                </h1>
            </div>
            <p class="text-[10px] opacity-80 font-medium tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
        </div>

        <nav class="mt-4 flex-grow pl-6">
            <a href="{{ route('kamad.kamad-dashboard') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-th-large mr-4 text-sm"></i> <span class="font-medium">Ringkasan</span>
            </a>

            <a href="{{ route('kamad.kamad-laporan') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-file-contract mr-4 text-sm"></i> <span>Laporan Masuk</span>
            </a>

            <!-- Menu Poin Aktif di halaman ini -->
            <a href="{{ route('kamad.kamad-poin') }}"
                class="sidebar-item active flex items-center px-6 py-4 transition">
                <i class="fas fa-users mr-4 text-sm"></i> <span class="font-medium">Poin Keseluruhan</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT (Margin kiri di-reset jadi 0 saat print) -->
    <main class="flex-1 ml-72 p-10 print:ml-0 print:p-0">

        <!-- GLOBAL HEADER (Disembunyikan saat di-print) -->
        <header class="flex justify-between items-center mb-10 print:hidden">
            <div>
                <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                    Home / <span>Poin Keseluruhan</span>
                </nav>
                <h2 class="text-2xl font-black text-gray-700 uppercase tracking-tighter italic">
                    Data Poin Siswa
                </h2>
            </div>

            <!-- User Profile & Dropdown -->
            <div class="relative">
                <button id="profileDropdownBtn"
                    class="flex items-center gap-4 bg-white px-6 py-2 rounded-full shadow-sm border border-gray-100 hover:bg-gray-50 transition focus:outline-none">
                    <div class="text-right">
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
                        class="w-10 h-10 rounded-full border-2 border-green-50 object-cover shadow-sm" alt="Profile">

                    <i class="fas fa-chevron-down text-gray-400 text-xs ml-1"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdownMenu"
                    class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden z-50 transform origin-top-right transition-all duration-200 opacity-0 scale-95">
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

        <!-- KOP SURAT (Hanya muncul saat di-print) -->
        <div class="hidden print:flex items-center border-b-4 border-double border-gray-800 pb-4 mb-6">
            <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                class="w-20 h-20 object-contain mr-6" alt="Logo">
            <div class="text-center flex-1 pr-20">
                <h1 class="text-xl font-bold uppercase tracking-widest text-gray-900">Kementerian Agama Republik
                    Indonesia</h1>
                <h2 class="text-2xl font-black uppercase text-gray-900 mt-1">Madrasah Tsanawiyah Negeri 2 Kota
                    Banjarmasin</h2>
                <p class="text-xs text-gray-700 mt-1">Jl. Mahligai, Kota Banjarmasin, Kalimantan Selatan</p>
            </div>
        </div>

        <!-- KONTEN POIN KESELURUHAN -->
        <div
            class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50 min-h-[70vh] print:shadow-none print:border-none print:p-0">
            <div
                class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 border-b border-gray-100 pb-6 gap-4 print:border-b-0 print:mb-4 print:pb-0">
                <div class="print:text-center print:w-full">
                    <h3 class="font-black text-gray-700 text-lg uppercase tracking-widest print:text-xl">Rekapitulasi
                        Poin Kedisiplinan Siswa</h3>
                    <p class="text-xs text-gray-400 mt-1 font-bold print:text-gray-600 print:text-sm">Dicetak pada:
                        {{ \Carbon\Carbon::now()->translatedFormat('d F Y \P\u\k\u\l H:i') }}</p>
                </div>
            </div>

            <!-- Area Search, Filter, dan Tombol Cetak (Disembunyikan saat di-print) -->
            <div class="flex flex-col md:flex-row gap-4 mb-8 print:hidden">

                <!-- Kotak Pencarian Nama/NISN -->
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-4 top-3.5 text-gray-300 text-xs"></i>
                    <input type="text" id="searchPoinSiswa" onkeyup="searchSiswaPoin()"
                        placeholder="Cari nama atau NISN secara langsung..."
                        class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium outline-none focus:ring-2 focus:ring-green-100 transition">
                </div>

                <!-- Form Filter Kelas -->
                <form action="{{ route('kamad.kamad-poin') }}" method="GET" class="flex gap-4">
                    <div class="relative">
                        <i class="fas fa-filter absolute left-4 top-3.5 text-gray-300 text-xs"></i>
                        <select name="kelas" onchange="this.form.submit()"
                            class="pl-10 pr-8 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold text-gray-600 outline-none focus:ring-2 focus:ring-green-100 transition cursor-pointer appearance-none">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelasList ?? [] as $kelas)
                                <option value="{{ $kelas }}"
                                    {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                    Kelas {{ $kelas }}
                                </option>
                            @endforeach
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>

                    <!-- Tombol Reset Filter (Muncul hanya jika filter sedang aktif) -->
                    @if (request('kelas'))
                        <a href="{{ route('kamad.kamad-poin') }}"
                            class="flex items-center justify-center bg-red-50 text-red-500 px-4 py-3 rounded-2xl text-xs font-bold hover:bg-red-100 transition"
                            title="Hapus Filter">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>

                <button onclick="window.print()"
                    class="bg-[#10b981] text-white px-6 py-3 rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-105 transition flex items-center whitespace-nowrap">
                    <i class="fas fa-print mr-2"></i> Cetak Dokumen
                </button>
            </div>

            <div class="overflow-x-auto print:overflow-visible">
                <!-- Tambahkan class print:text-black dan print:border untuk hasil cetak yang tajam -->
                <table class="w-full text-left print:border-collapse" id="tabelPoin">
                    <thead
                        class="text-gray-400 text-[10px] font-black uppercase tracking-widest border-b border-gray-50 print:text-black print:border-b-2 print:border-gray-800">
                        <tr>
                            <th class="pb-5 pl-4 w-16 print:py-3 print:border print:border-gray-400">No</th>
                            <th class="pb-5 print:py-3 print:pl-4 print:border print:border-gray-400">Waktu / Tanggal
                            </th>
                            <th class="pb-5 print:py-3 print:pl-4 print:border print:border-gray-400">NISN / Nama
                                Lengkap Siswa</th>
                            <th class="pb-5 text-center print:py-3 print:border print:border-gray-400">Kelas</th>
                            <th class="pb-5 print:py-3 print:pl-4 print:border print:border-gray-400">Keterangan
                                Pelanggaran</th>
                            <th class="pb-5 text-center print:py-3 print:border print:border-gray-400">Foto Bukti</th>
                            <th class="pb-5 text-center print:py-3 print:border print:border-gray-400">Poin Masuk</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-50 print:divide-none">
                        @forelse($laporan ?? [] as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition row-siswa print:text-black">
                                <td
                                    class="py-5 pl-4 font-bold text-gray-400 print:py-2 print:text-black print:border print:border-gray-400">
                                    {{ $index + 1 }}</td>
                                <td
                                    class="py-5 italic text-gray-400 font-medium whitespace-nowrap print:py-2 print:text-black print:border print:border-gray-400">
                                    {{ \Carbon\Carbon::parse($item->waktu)->format('d M Y, H:i') }}
                                </td>
                                <td
                                    class="py-5 print:py-2 print:pl-4 print:text-black print:border print:border-gray-400">
                                    <p class="font-black text-gray-800 nama-col print:text-black">{{ $item->nama }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 uppercase nisn-col print:text-gray-700">
                                        {{ $item->nisn }}</p>
                                </td>
                                <td
                                    class="py-5 text-center font-bold text-[#10b981] print:py-2 print:text-black print:border print:border-gray-400">
                                    {{ $item->kelas }}
                                </td>
                                <td class="py-5 text-gray-600 max-w-[300px] truncate print:whitespace-normal print:max-w-none print:py-2 print:text-black print:border print:border-gray-400"
                                    title="{{ $item->ket }}">
                                    {{ $item->ket }}
                                </td>
                                <td
                                    class="py-5 text-center print:py-2 print:text-black print:border print:border-gray-400">
                                    @if (isset($item->foto_bukti) && $item->foto_bukti)
                                        <button type="button"
                                            onclick="openFotoBuktiModal('{{ asset('storage/' . $item->foto_bukti) }}')"
                                            class="print:hidden inline-flex bg-green-50 text-green-600 px-3 py-1.5 rounded-lg text-[10px] font-bold hover:bg-green-100 transition items-center gap-1 border border-green-100 shadow-sm cursor-pointer">
                                            <i class="fas fa-search-plus"></i> Lihat Foto
                                        </button>
                                        <span
                                            class="hidden print:inline text-[10px] text-gray-500 italic">Terlampir</span>
                                    @else
                                        <span
                                            class="text-[10px] text-gray-400 italic font-medium bg-gray-50 px-3 py-1 rounded-lg border border-gray-100 print:border-none print:bg-transparent">Tanpa
                                            Bukti</span>
                                    @endif
                                </td>
                                <td
                                    class="py-5 text-center print:py-2 print:text-black print:border print:border-gray-400">
                                    <!-- Menghilangkan warna background saat diprint agar irit tinta dan jelas -->
                                    <span
                                        class="print:hidden bg-red-50 text-red-600 border border-red-100 px-3 py-1 rounded-lg font-black">
                                        +{{ $item->jumlah }}
                                    </span>
                                    <span class="hidden print:inline font-bold">
                                        +{{ $item->jumlah }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="py-8 text-center text-gray-400 font-bold text-sm print:border print:border-gray-400">
                                    Belum ada data riwayat pelanggaran untuk ditampilkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination Laravel -->
                <div class="mt-8 print:hidden">
                    {{ $laporan->links() ?? '' }}
                </div>

                <!-- TTD Kamad (Hanya muncul saat diprint) -->
                <div class="hidden print:flex justify-end mt-16 mr-12">
                    <div class="text-center text-sm text-black">
                        <p>Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        <p class="mt-1">Kepala Madrasah,</p>
                        <br><br><br><br>
                        <p class="font-bold underline">{{ Auth::user()->name ?? 'Kepala Madrasah' }}</p>
                        <p>NIP. {{ Auth::user()->nip ?? '........................' }}</p>
                    </div>
                </div>

            </div>
        </div>

    </main>

    <!-- MODAL FOTO BUKTI PELANGGARAN -->
    <div id="fotoBuktiModal"
        class="fixed inset-0 bg-black/80 hidden z-[70] flex items-center justify-center backdrop-blur-sm transition-opacity opacity-0 duration-200 print:hidden">
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl relative max-w-3xl w-full mx-4 transform scale-95 transition-transform duration-200"
            id="fotoBuktiContent">

            <!-- Header Modal -->
            <div class="px-6 py-4 bg-gray-900 text-white flex justify-between items-center">
                <h3 class="font-bold text-sm uppercase tracking-wider"><i class="fas fa-image mr-2"></i> Foto Bukti
                    Pelanggaran</h3>
                <button onclick="closeFotoBuktiModal()"
                    class="text-gray-400 hover:text-white transition focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Tempat Foto -->
            <div class="p-2 bg-gray-100 flex justify-center items-center relative"
                style="min-height: 300px; max-height: 70vh;">
                <!-- Loading Spinner (terlihat saat gambar dimuat) -->
                <div id="fotoBuktiLoading"
                    class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 z-0">
                    <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                    <span class="text-xs font-bold">Memuat gambar...</span>
                </div>

                <!-- Gambar Asli -->
                <img id="fotoBuktiImage" src="" alt="Bukti Pelanggaran"
                    class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-sm relative z-10 hidden"
                    onload="document.getElementById('fotoBuktiLoading').classList.add('hidden'); this.classList.remove('hidden');">
            </div>

            <!-- Footer Bantuan -->
            <div
                class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-center flex justify-center items-center gap-4">
                <a id="downloadFotoBtn" href="#" download
                    class="text-xs font-bold text-gray-600 hover:text-blue-600 transition flex items-center gap-1">
                    <i class="fas fa-download"></i> Unduh Foto
                </a>
                <span class="text-gray-300">|</span>
                <button onclick="closeFotoBuktiModal()"
                    class="text-xs font-bold text-gray-600 hover:text-gray-900 transition flex items-center gap-1">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- SCRIPT -->
    <script>
        // Logika Dropdown Profil
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

        // Logika Pencarian Siswa Berdasarkan Nama/NISN
        function searchSiswaPoin() {
            let input = document.getElementById("searchPoinSiswa").value.toLowerCase();
            let rows = document.querySelectorAll(".row-siswa");

            rows.forEach(row => {
                let nama = row.querySelector(".nama-col").innerText.toLowerCase();
                let nisn = row.querySelector(".nisn-col").innerText.toLowerCase();

                if (nama.includes(input) || nisn.includes(input)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // ==========================================
        // MODAL FOTO BUKTI
        // ==========================================
        window.openFotoBuktiModal = function(url) {
            const modal = document.getElementById('fotoBuktiModal');
            const content = document.getElementById('fotoBuktiContent');
            const img = document.getElementById('fotoBuktiImage');
            const loading = document.getElementById('fotoBuktiLoading');
            const downloadBtn = document.getElementById('downloadFotoBtn');

            // Tampilkan loading, sembunyikan gambar lama
            img.classList.add('hidden');
            loading.classList.remove('hidden');

            // Set source gambar
            img.src = url;
            downloadBtn.href = url;

            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Animasi masuk
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
                // Animasi keluar
                content.classList.remove('scale-100');
                content.classList.add('scale-95');
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    img.src = ""; // Bersihkan src
                }, 200);
            }
        };

        // Tutup modal jika area luar (backdrop) diklik
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('fotoBuktiModal');
            if (event.target === modal) {
                closeFotoBuktiModal();
            }
        });

        // Tangani tombol ESC untuk menutup modal foto
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                const fotoModal = document.getElementById('fotoBuktiModal');
                if (fotoModal && !fotoModal.classList.contains('hidden')) {
                    closeFotoBuktiModal();
                }
            }
        });
    </script>
</body>

</html>
