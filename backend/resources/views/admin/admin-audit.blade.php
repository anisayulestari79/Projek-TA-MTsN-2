<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Log - Sistem Pelanggaran Poin</title>
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

    <!-- OVERLAY UNTUK MOBILE (Muncul saat sidebar dibuka di HP) -->
    <div id="sidebarOverlay" onclick="toggleSidebar()"
        class="fixed inset-0 bg-gray-900/50 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"></div>

    <!-- SIDEBAR -->
    <!-- Tambahan: id="sidebar", transform, -translate-x-full, md:translate-x-0, transition -->
    <aside id="sidebar"
        class="w-72 min-h-screen bg-[#10b981] text-white flex flex-col fixed shadow-xl z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
        <div class="p-8 flex justify-between items-start">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                        class="w-10 drop-shadow-md" alt="Logo Kemenag">
                    <h1 class="font-bold text-2xl leading-tight tracking-tight uppercase">Sistem <br> Pelanggaran <br>
                        Poin
                        Siswa</h1>
                </div>
                <p class="text-[10px] opacity-80 font-medium tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
            </div>
            <!-- Tombol Tutup Sidebar untuk Mobile -->
            <button onclick="toggleSidebar()" class="md:hidden text-white hover:text-gray-200 focus:outline-none">
                <i class="fas fa-times text-2xl"></i>
            </button>
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
            <a href="{{ route('admin.poin.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-star mr-4 text-sm"></i> <span class="font-medium">Poin Siswa</span>
            </a>

            <a href="{{ route('admin.tahunajaran.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-calendar-alt mr-4 text-sm"></i> <span class="font-medium">Tahun Ajaran</span>
            </a>

            <!-- MENU AUDIT LOG AKTIF MENGGUNAKAN ONCLICK SHOWVIEW -->
            <a href="#" onclick="showView('audit')" id="nav-audit"
                class="sidebar-item active flex items-center px-6 py-4 transition">
                <i class="fas fa-history mr-4 text-sm"></i> <span>Audit Log</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <!-- Tambahan: md:ml-72 (sebelumnya ml-72 fixed), p-6 untuk mobile agar tidak terlalu rapat -->
    <main class="flex-1 md:ml-72 p-6 md:p-10 relative w-full overflow-x-hidden transition-all duration-300">
        <!-- GLOBAL HEADER -->
        <header class="flex justify-between items-center mb-10">
            <div class="flex items-center gap-4">
                <!-- Tombol Hamburger (Hanya muncul di Mobile) -->
                <button onclick="toggleSidebar()"
                    class="md:hidden text-gray-500 hover:text-[#10b981] focus:outline-none transition">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <div>
                    <nav id="breadcrumb-active"
                        class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                        Home / Audit Log
                    </nav>
                    <h2 id="view-title"
                        class="text-xl md:text-2xl font-black text-gray-700 uppercase tracking-tighter italic">Log
                        Aktivitas Sistem
                    </h2>
                </div>
            </div>

            <!-- User Profile & Dropdown -->
            <div class="relative">
                <button id="profileDropdownBtn"
                    class="flex items-center gap-2 md:gap-4 bg-white px-4 md:px-6 py-2 rounded-full shadow-sm border border-gray-100 hover:bg-gray-50 transition focus:outline-none">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-black text-[#10b981] uppercase leading-none">
                            {{ $user['name'] ?? 'Admin User' }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Status: Administrator</p>
                    </div>
                    <!-- PERBAIKAN GAMBAR PROFIL -->
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
        <!-- SECTION: DATA AUDIT LOG -->
        <!-- ============================================== -->
        <div id="view-audit" class="view-section active">
            <div class="bg-white p-6 md:p-8 rounded-[30px] shadow-sm border border-gray-50 relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b pb-6 gap-4">
                    <div class="w-full text-center md:text-left">
                        <h3
                            class="font-black text-gray-700 text-lg uppercase tracking-widest flex items-center justify-center md:justify-start gap-3">
                            <div class="w-8 h-8 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center"><i
                                    class="fas fa-shield-alt"></i></div>
                            Riwayat Login Pengguna
                        </h3>
                        <p class="text-xs text-gray-400 mt-2 md:ml-11">Pemantauan aktivitas login sistem untuk keamanan
                            madrasah</p>
                    </div>
                </div>

                <!-- TABEL RIWAYAT LOGIN -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead class="bg-[#005c4b] text-white text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="py-4 pl-6 rounded-tl-xl">Waktu Login</th>
                                <th class="py-4">Pengguna</th>
                                <th class="py-4 text-center">Hak Akses</th>
                                <th class="py-4">Alamat IP</th>
                                <th class="py-4 pr-6 rounded-tr-xl">Info Perangkat (User Agent)</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-gray-100">
                            @forelse($logs ?? [] as $log)
                                <tr class="hover:bg-gray-50 transition">
                                    <!-- Waktu Login -->
                                    <td class="py-5 pl-6 italic text-gray-500 font-medium whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($log->login_at)->format('d F Y, H:i:s') }}
                                    </td>

                                    <!-- Nama Pengguna -->
                                    <td class="py-5">
                                        <p class="font-black text-gray-700 leading-tight">
                                            {{ $log->user->name ?? 'User Terhapus' }}</p>
                                        <p class="text-[10px] text-gray-400 mt-1">{{ $log->user->email ?? '-' }}</p>
                                    </td>

                                    <!-- Role / Hak Akses -->
                                    <td class="py-5 text-center">
                                        @if (isset($log->user) && strtolower($log->user->role) == 'admin')
                                            <span
                                                class="bg-purple-50 text-purple-600 px-3 py-1 rounded-lg font-black text-[10px] uppercase border border-purple-100">Admin</span>
                                        @elseif(isset($log->user) && strtolower($log->user->role) == 'guru')
                                            <span
                                                class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg font-black text-[10px] uppercase border border-blue-100">Guru</span>
                                        @else
                                            <span
                                                class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg font-black text-[10px] uppercase border border-gray-200">
                                                {{ $log->user->role ?? 'Unknown' }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- IP Address -->
                                    <td class="py-5 font-bold text-gray-600">
                                        <span
                                            class="bg-gray-50 px-2 py-1 rounded border border-gray-100 shadow-inner font-mono text-[10px]">
                                            <i class="fas fa-network-wired text-green-400 mr-1"></i>
                                            {{ $log->ip_address ?? 'Unknown' }}
                                        </span>
                                    </td>

                                    <!-- Device Info / User Agent -->
                                    <td class="py-5 pr-6 text-gray-500 text-[10px]">
                                        <div class="max-w-[200px] sm:max-w-xs md:max-w-md truncate"
                                            title="{{ $log->user_agent }}">
                                            {{ $log->user_agent ?? 'Unknown Device' }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-gray-400 font-bold">
                                        <i class="fas fa-history text-3xl mb-3 text-gray-200 block"></i>
                                        Belum ada data aktivitas login yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginasi -->
                <div class="mt-6">
                    {{ $logs->links() ?? '' }}
                </div>
            </div>
        </div>
    </main>

    <script>
        // ==========================================
        // FUNGSI TOGGLE SIDEBAR MOBILE
        // ==========================================
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
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

            if (viewId === 'audit') {
                if (titleEl) titleEl.innerText = "Log Aktivitas Sistem";
                if (breadcrumbEl) breadcrumbEl.innerText = "Home / Audit Log";
                document.getElementById('nav-audit')?.classList.add('active');
            }
            // Tutup sidebar otomatis jika diklik di HP
            if (window.innerWidth < 768) {
                const sidebar = document.getElementById('sidebar');
                if (!sidebar.classList.contains('-translate-x-full')) {
                    toggleSidebar();
                }
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
