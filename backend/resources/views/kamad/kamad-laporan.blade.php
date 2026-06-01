<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Masuk - Kepala Madrasah</title>
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

        /* Tambahan CSS untuk sistem Tab (View) */
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
                <h1 class="font-bold text-2xl leading-tight tracking-tight uppercase">Panel <br> Pimpinan <br> Madrasah
                </h1>
            </div>
            <p class="text-[10px] opacity-80 font-medium tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
        </div>

        <nav class="mt-4 flex-grow pl-6">
            <!-- NAMA ROUTE DISESUAIKAN KEMBALI KE ASAL -->
            <a href="{{ route('kamad.kamad-dashboard') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-th-large mr-4 text-sm"></i> <span class="font-medium">Ringkasan</span>
            </a>

            <!-- Menu Laporan Masuk Aktif di halaman ini -->
            <a href="{{ route('kamad.kamad-laporan') }}" onclick="showView('laporan')" id="nav-laporan"
                class="sidebar-item active flex items-center px-6 py-4 transition">
                <i class="fas fa-file-contract mr-4 text-sm"></i> <span>Laporan Masuk</span>
            </a>

            <a href="{{ route('kamad.kamad-poin') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-users mr-4 text-sm"></i> <span class="font-medium">Poin Keseluruhan</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 ml-72 p-10">
        <!-- GLOBAL HEADER -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                    Home / <span id="breadcrumb-active">Laporan Masuk</span>
                </nav>
                <h2 id="view-title" class="text-2xl font-black text-gray-700 uppercase tracking-tighter italic">
                    Arsip Laporan Sistem
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

                    <!-- Foto Profil Terhubung dengan Auth -->
                    @if (Auth::check() && Auth::user()->photo)
                        <img src="{{ str_starts_with(Auth::user()->photo, 'http') ? Auth::user()->photo : asset('storage/' . Auth::user()->photo) }}"
                            class="w-10 h-10 rounded-full border-2 border-green-50 object-cover shadow-sm"
                            alt="Profile">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Kepala Madrasah') }}&background=10b981&color=fff"
                            class="w-10 h-10 rounded-full border-2 border-green-50 shadow-sm" alt="Profile">
                    @endif

                    <i class="fas fa-chevron-down text-gray-400 text-xs ml-1"></i>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdownMenu"
                    class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hidden z-50 transform origin-top-right transition-all duration-200 opacity-0 scale-95">
                    <div class="py-2">
                        <!-- UBAH JADI FUNGSI ONCLICK SHOWVIEW -->
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
        <!-- SECTION 1: KONTEN HALAMAN LAPORAN MASUK          -->
        <!-- ============================================== -->
        <div id="view-laporan" class="view-section active">
            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50 min-h-[70vh]">
                <div
                    class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 border-b border-gray-100 pb-6 gap-4">
                    <div>
                        <h3 class="font-black text-gray-700 text-lg uppercase tracking-widest">Daftar Laporan Masuk</h3>
                        <p class="text-xs text-gray-400 mt-1 font-bold">Rekapitulasi data pelanggaran dari Admin & Guru
                            BK</p>
                    </div>

                    <!-- Filter Laporan -->
                    <div class="flex flex-wrap gap-3">
                        <div class="relative">
                            <i class="fas fa-filter absolute left-4 top-3 text-gray-300 text-xs"></i>
                            <select
                                class="pl-10 pr-8 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold text-gray-600 outline-none focus:ring-2 focus:ring-green-100 transition cursor-pointer appearance-none">
                                <option value="">Semua Kategori</option>
                                <option value="bulanan">Rekap Bulanan</option>
                                <option value="kelas">Rekap Per Kelas</option>
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-4 top-3.5 text-gray-400 text-[10px] pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- List Laporan Terhubung dengan Database -->
                <div class="space-y-4">
                    @forelse($listLaporan ?? [] as $laporan)
                        <div
                            class="p-6 bg-gray-50 hover:bg-white hover:shadow-md rounded-2xl flex flex-col lg:flex-row lg:justify-between lg:items-center transition duration-300 border border-gray-100 group">
                            <div class="flex items-center gap-5 mb-4 lg:mb-0">
                                <!-- Icon berubah warna saat di hover -->
                                <div
                                    class="w-14 h-14 rounded-2xl bg-green-50 text-[#10b981] flex items-center justify-center text-2xl group-hover:bg-[#10b981] group-hover:text-white transition duration-300">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="bg-blue-100 text-blue-700 text-[9px] font-black px-2 py-0.5 rounded uppercase tracking-widest">{{ $laporan->kategori ?? 'Bulanan' }}</span>
                                        <span class="text-xs text-gray-400 font-bold"><i
                                                class="far fa-calendar-alt mr-1"></i>
                                            {{ $laporan->created_at ? $laporan->created_at->format('d M Y') : 'Tgl Laporan' }}</span>
                                    </div>
                                    <h4 class="font-black text-gray-800 uppercase tracking-tight text-sm">
                                        {{ $laporan->judul ?? 'Judul Laporan' }}</h4>
                                    <p class="text-gray-400 mt-1 font-medium text-[10px] uppercase tracking-wider"><i
                                            class="fas fa-paper-plane mr-1"></i> Pengirim:
                                        {{ $laporan->pengirim->name ?? 'Admin Sistem' }}</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ asset('storage/' . $laporan->file_path) }}" target="_blank"
                                    class="bg-white border border-gray-200 text-gray-600 px-5 py-2.5 rounded-xl font-bold uppercase tracking-tighter text-[10px] hover:bg-gray-50 transition flex items-center">
                                    <i class="fas fa-eye mr-2"></i> Lihat
                                </a>
                                <a href="{{ asset('storage/' . $laporan->file_path) }}" download
                                    class="bg-[#10b981] text-white px-5 py-2.5 rounded-xl font-bold uppercase tracking-tighter text-[10px] hover:scale-105 shadow-sm shadow-green-100 transition flex items-center">
                                    <i class="fas fa-download mr-2"></i> Unduh
                                </a>
                            </div>
                        </div>
                    @empty
                        <!-- Tampilan jujur jika tidak ada data di database -->
                        <div class="p-8 text-center text-gray-400 font-bold">
                            <div
                                class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-inbox text-3xl text-gray-300"></i>
                            </div>
                            <p class="text-gray-500 uppercase tracking-widest text-xs">Belum ada laporan masuk</p>
                            <p class="font-normal mt-2 text-sm">Semua data laporan yang dikirim admin akan muncul di
                                sini.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination (Jika Anda menggunakan paginate di controller) -->
                @if (isset($listLaporan) && method_exists($listLaporan, 'links'))
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        {{ $listLaporan->links() }}
                    </div>
                @endif

            </div>
        </div>

        <!-- ============================================== -->
        <!-- SECTION 2: PROFILE PENGGUNA (DISEMBUNYIKAN)      -->
        <!-- ============================================== -->
        <div id="view-profile" class="view-section">
            <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50 max-w-2xl mx-auto">
                <!-- Profile Display -->
                <div id="profileView" class="flex flex-col items-center transition-all duration-300">
                    <!-- Image -->
                    <div
                        class="w-32 h-32 rounded-full overflow-hidden mb-4 border-4 border-green-50 shadow-sm relative group">
                        @if (Auth::check() && Auth::user()->photo)
                            <img src="{{ str_starts_with(Auth::user()->photo, 'http') ? Auth::user()->photo : asset('storage/' . Auth::user()->photo) }}"
                                class="w-full h-full object-cover" id="mainProfilePic" alt="Profile Picture">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Kepala Madrasah') }}&background=10b981&color=fff&size=128"
                                class="w-full h-full object-cover" id="mainProfilePic" alt="Profile Picture">
                        @endif
                    </div>
                    <h3 class="text-2xl font-black text-gray-800 uppercase">
                        {{ Auth::user()->name ?? 'Nama Pengguna' }}</h3>
                    <p class="text-xs font-bold text-[#10b981] uppercase tracking-widest mb-8">
                        {{ ucfirst(Auth::user()->role ?? 'Pimpinan') }}</p>

                    <div class="w-full space-y-4">
                        <div class="flex items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-id-badge text-[#10b981] w-10 text-center text-xl"></i>
                            <div class="ml-4">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">NIP / Username
                                </p>
                                <p class="text-sm font-black text-gray-700">
                                    {{ Auth::user()->nip ?? (Auth::user()->username ?? '-') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-venus-mars text-[#10b981] w-10 text-center text-xl"></i>
                            <div class="ml-4">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Gender</p>
                                <p class="text-sm font-black text-gray-700">
                                    {{ Auth::user()->gender ?? (Auth::user()->jk ?? '-') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <i class="fas fa-phone-alt text-[#10b981] w-10 text-center text-xl"></i>
                            <div class="ml-4">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No. Telepon
                                </p>
                                <p class="text-sm font-black text-gray-700">{{ Auth::user()->phone ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="toggleEditProfile(true)"
                        class="mt-8 bg-[#10b981] text-white px-8 py-4 rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-105 transition w-full">
                        <i class="fas fa-user-edit mr-2"></i> Edit Profil
                    </button>
                </div>

                <!-- Profile Form Edit -->
                <form id="profileForm" class="hidden flex-col transition-all duration-300"
                    action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
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
                            <input type="text" name="name" value="{{ Auth::user()->name ?? '' }}" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">NIP
                                / Username</label>
                            <input type="text" value="{{ Auth::user()->nip ?? (Auth::user()->username ?? '') }}"
                                disabled
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold text-gray-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Gender</label>
                            <select name="gender"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
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
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">No.
                                Telepon</label>
                            <input type="tel" name="phone" value="{{ Auth::user()->phone ?? '' }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>
                        <div class="pt-4 mt-2 border-t border-gray-100">
                            <label
                                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Ganti
                                Password (Opsional)</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                        </div>
                    </div>

                    <div class="flex gap-4 mt-8">
                        <button type="submit"
                            class="flex-1 bg-[#10b981] text-white px-6 py-4 rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-105 transition">Simpan
                            Perubahan</button>
                        <button type="button" onclick="toggleEditProfile(false)"
                            class="flex-1 bg-gray-100 text-gray-600 px-6 py-4 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-200 transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <!-- SCRIPT -->
    <script>
        // Logika Tab SPA (Sistem 1 File)
        function showView(viewId) {
            document.querySelectorAll('.view-section').forEach(view => view.classList.remove('active'));
            document.getElementById('view-' + viewId).classList.add('active');

            const titleEl = document.getElementById('view-title');
            const breadcrumbEl = document.getElementById('breadcrumb-active');

            document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));

            if (viewId === 'laporan') {
                titleEl.innerText = "Arsip Laporan Sistem";
                breadcrumbEl.innerText = "Laporan Masuk";
                document.getElementById('nav-laporan').classList.add('active');
            } else if (viewId === 'profile') {
                titleEl.innerText = "Profil Pimpinan";
                breadcrumbEl.innerText = "Home / Profil";
            }
        }

        // Logika Toggle View Profil vs Form Edit
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
            // Logika Dropdown Profil
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

            // Logika Submit Form Profil via AJAX
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
