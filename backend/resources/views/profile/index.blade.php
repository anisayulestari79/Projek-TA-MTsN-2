<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - Sistem Pelanggaran Poin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }

        .sidebar-item.active {
            background-color: white;
            color: #10b981;
            border-radius: 10px 0 0 10px;
            font-weight: 800;
        }
    </style>
</head>

<body class="flex text-gray-800 h-screen overflow-hidden">

    @php
        $user = Auth::user();

        // Logika dinamis untuk tombol "Kembali ke Dashboard" sesuai Role
        // PERBAIKAN: Menambahkan 'guru_bk' agar tidak terlempar ke halaman utama (ter-logout)
        // Telah dihapus bagian route untuk 'ortu' sesuai permintaan
        $dashRoute = '/';
        if ($user->role === 'admin') {
            $dashRoute = route('admin.dashboard');
        } elseif ($user->role === 'kamad') {
            $dashRoute = route('kamad.kamad-dashboard');
        } elseif (in_array($user->role, ['guru', 'bk', 'guru_bk'])) {
            $dashRoute = route('guru.dashboard');
        }
    @endphp

    <!-- BACKDROP MOBILE -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()"
        class="fixed inset-0 bg-gray-900/50 z-40 hidden md:hidden backdrop-blur-sm transition-opacity opacity-0"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="fixed md:relative inset-y-0 left-0 w-72 bg-[#10b981] text-white flex flex-col shadow-xl z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out shrink-0">
        <div class="p-8 relative border-b border-white/10">
            <button onclick="toggleSidebar()" class="md:hidden absolute top-6 right-6 text-white/80 hover:text-white"><i
                    class="fas fa-times text-xl"></i></button>
            <div class="flex items-center gap-3 mb-2">
                <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                    class="w-10 drop-shadow-md" alt="Logo">
                <h1 class="font-bold text-lg leading-tight tracking-tight uppercase">Panel <br> Pengaturan</h1>
            </div>
            <p class="text-[10px] opacity-80 font-medium tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
        </div>

        <nav class="mt-6 flex-grow pl-6 space-y-2">
            <a href="#" class="sidebar-item active flex items-center px-6 py-4 transition shadow-sm">
                <i class="fas fa-user-circle mr-4 text-sm"></i> <span class="font-bold">Profil Akun</span>
            </a>
        </nav>

        <div class="p-6 mt-auto">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-3 bg-white/10 hover:bg-red-500 text-white px-4 py-3 rounded-xl transition text-sm font-bold shadow-sm">
                    <i class="fas fa-sign-out-alt"></i> Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-4 md:p-10 overflow-y-auto relative w-full">
        <!-- HEADER -->
        <header class="flex justify-between items-center mb-8 md:mb-10 pt-2 md:pt-0">
            <div class="flex items-center gap-3">
                <!-- Hamburger Menu Button untuk HP -->
                <button onclick="toggleSidebar()"
                    class="md:hidden bg-white p-2 rounded-xl shadow-sm text-gray-500 hover:text-[#10b981] border border-gray-100">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div>
                    <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1 hidden sm:block">Home
                        / Profil</nav>
                    <h2 class="text-xl md:text-2xl font-black text-gray-700 uppercase tracking-tighter italic">
                        Pengaturan Akun</h2>
                </div>
            </div>

            <div
                class="hidden md:flex items-center gap-4 bg-white px-6 py-2 rounded-full shadow-sm border border-gray-100">
                <div class="text-right">
                    <p class="text-xs font-black text-[#10b981] uppercase leading-none">{{ $user->name }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Status: {{ strtoupper($user->role) }}
                    </p>
                </div>
                @php
                    $avatarUrl =
                        'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=10b981&color=fff';
                    $photoPath = $user->photo
                        ? (str_starts_with($user->photo, 'http')
                            ? $user->photo
                            : asset('storage/' . $user->photo))
                        : $avatarUrl;
                @endphp
                <img src="{{ $photoPath }}" onerror="this.src='{{ $avatarUrl }}'"
                    class="w-10 h-10 rounded-full border-2 border-green-50 object-cover shadow-sm" alt="Profile">
            </div>
        </header>

        <!-- ALERTS -->
        @if (session('success'))
            <div
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-2xl mb-6 shadow-sm flex items-center gap-2 max-w-md mx-auto">
                <i class="fas fa-check-circle"></i> <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl mb-6 shadow-sm flex items-center gap-2 max-w-md mx-auto">
                <i class="fas fa-exclamation-triangle"></i> <span
                    class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <!-- CARD PROFIL -->
        <div class="bg-white p-6 md:p-8 rounded-[30px] shadow-sm border border-gray-50 max-w-md mx-auto w-full">

            <div class="flex flex-col items-center mb-6">
                <!-- Avatar -->
                <div
                    class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-green-50 shadow-md flex items-center justify-center bg-[#10b981] text-white text-3xl font-black mb-4 overflow-hidden relative group">
                    <img src="{{ $photoPath }}" onerror="this.src='{{ $avatarUrl }}'"
                        class="w-full h-full object-cover">
                </div>

                <h3 class="text-lg md:text-xl font-black text-gray-800 uppercase text-center leading-tight">
                    {{ $user->name }}</h3>
                <p class="text-[10px] md:text-xs font-bold text-[#10b981] uppercase tracking-widest mt-1">
                    {{ strtoupper($user->role) }}</p>
            </div>

            <!-- Info List -->
            <div class="space-y-3 w-full">
                <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-4 border border-gray-100">
                    <div class="w-8 flex justify-center"><i class="fas fa-id-badge text-[#10b981] text-lg"></i></div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">NIP / Username</p>
                        <p class="text-sm font-black text-gray-700">{{ $user->nip ?? ($user->username ?? '-') }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-4 border border-gray-100">
                    <div class="w-8 flex justify-center"><i class="fas fa-venus-mars text-[#10b981] text-lg"></i></div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Gender</p>
                        <p class="text-sm font-black text-gray-700">{{ $user->gender ?? '-' }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-4 border border-gray-100">
                    <div class="w-8 flex justify-center"><i class="fas fa-phone-alt text-[#10b981] text-lg"></i></div>
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">No. Telepon</p>
                        <p class="text-sm font-black text-gray-700">{{ $user->phone ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Navigasi Bawah -->
            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <button onclick="document.getElementById('editModal').classList.remove('hidden')"
                    class="flex-1 bg-[#10b981] text-white py-3 rounded-xl font-bold text-[10px] md:text-xs uppercase tracking-wider shadow-lg shadow-green-100 hover:bg-green-600 transition flex items-center justify-center gap-2">
                    <i class="fas fa-user-edit"></i> Edit Profil
                </button>
                <a href="{{ $dashRoute }}"
                    class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold text-[10px] md:text-xs uppercase tracking-wider hover:bg-gray-200 transition flex items-center justify-center gap-2 border border-gray-200">
                    <i class="fas fa-home"></i> Ke Dashboard
                </a>
            </div>
        </div>
    </main>

    <!-- MODAL EDIT PROFIL (Pop Up) -->
    <div id="editModal"
        class="fixed inset-0 bg-gray-900/80 hidden z-[60] flex items-center justify-center backdrop-blur-sm p-4">
        <div class="bg-white rounded-[30px] p-6 md:p-8 max-w-md w-full shadow-2xl relative">
            <button onclick="document.getElementById('editModal').classList.add('hidden')"
                class="absolute top-6 right-6 text-gray-400 hover:text-red-500"><i
                    class="fas fa-times text-xl"></i></button>
            <h3 class="text-lg font-black text-gray-800 uppercase tracking-widest mb-6 border-b pb-4">Edit Data Diri
            </h3>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
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
                    <input type="text" name="name" value="{{ $user->name }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#10b981]">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Gender</label>
                    <select name="gender"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#10b981] appearance-none">
                        <option value="Laki-laki" {{ $user->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="Perempuan" {{ $user->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">No.
                        Telepon</label>
                    <input type="tel" name="phone" value="{{ $user->phone }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#10b981]">
                </div>
                <div class="pt-2 border-t border-gray-100 mt-2">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Ganti
                        Password (Opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#10b981] transition">
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-[#10b981] text-white py-3 rounded-xl text-xs font-bold uppercase shadow-lg shadow-green-100 hover:bg-green-600 transition">Simpan</button>
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl text-xs font-bold uppercase hover:bg-gray-200 transition">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.classList.toggle('-translate-x-full');
            if (sidebar.classList.contains('-translate-x-full')) {
                backdrop.classList.add('opacity-0');
                setTimeout(() => backdrop.classList.add('hidden'), 300);
            } else {
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
            }
        }
    </script>
</body>

</html>
