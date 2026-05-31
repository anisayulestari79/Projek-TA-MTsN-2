<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna - Sistem Pelanggaran</title>
    <!-- Tailwind CSS Wajib Ada Agar Desain Muncul -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk Ikon -->
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

    <!-- SIDEBAR (Universal untuk Profil) -->
    <aside class="w-72 min-h-screen bg-[#10b981] text-white flex flex-col fixed shadow-xl z-50">
        <div class="p-8">
            <div class="flex items-center gap-3 mb-2">
                <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                    class="w-10 drop-shadow-md" alt="Logo Kemenag">
                <h1 class="font-bold text-2xl leading-tight tracking-tight uppercase">Panel <br> Pengaturan</h1>
            </div>
            <p class="text-[10px] opacity-80 font-medium tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
        </div>

        <nav class="mt-4 flex-grow pl-6">
            <!-- Tombol Kembali Dinamis Sesuai Role -->
            @php
                $dashboardRoute = '/';
                if ($user->role === 'admin') {
                    $dashboardRoute = route('admin.dashboard');
                } elseif ($user->role === 'guru') {
                    $dashboardRoute = route('guru.dashboard');
                } elseif ($user->role === 'kamad') {
                    $dashboardRoute = route('kamad.dashboard');
                }
            @endphp

            <a href="{{ $dashboardRoute }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-arrow-left mr-4 text-sm"></i> <span class="font-medium">Kembali ke Dasbor</span>
            </a>

            <a href="#" class="sidebar-item active flex items-center px-6 py-4 transition rounded-l-xl">
                <i class="fas fa-user-circle mr-4 text-sm"></i> <span class="font-bold">Profil Akun</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 ml-72 p-10">
        <!-- GLOBAL HEADER -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                    Home / <span>Profil</span>
                </nav>
                <h2 class="text-2xl font-black text-gray-700 uppercase tracking-tighter italic">
                    Pengaturan Akun
                </h2>
            </div>
            <!-- User Profile (Kanan Atas) -->
            <div class="relative">
                <div class="flex items-center gap-4 bg-white px-6 py-2 rounded-full shadow-sm border border-gray-100">
                    <div class="text-right">
                        <p class="text-xs font-black text-[#10b981] uppercase leading-none">
                            {{ $user->name ?? 'Pengguna' }}
                        </p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Status:
                            {{ $user->role ?? 'Aktif' }}</p>
                    </div>
                    @if (isset($user->photo) && $user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}"
                            class="w-10 h-10 rounded-full border-2 border-green-50 object-cover shadow-sm"
                            alt="Profile">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&background=10b981&color=fff"
                            class="w-10 h-10 rounded-full border-2 border-green-50 shadow-sm" alt="Profile">
                    @endif
                </div>
            </div>
        </header>

        <!-- KONTEN PROFIL -->
        <div class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-50 max-w-2xl mx-auto">

            <!-- TAMPILAN PROFIL (Default) -->
            <div id="profileView" class="flex flex-col items-center transition-all duration-300">
                <!-- Foto Profil -->
                <div
                    class="w-32 h-32 rounded-full overflow-hidden mb-4 border-4 border-[#10b981]/20 shadow-sm relative group">
                    @if (isset($user->photo) && $user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" class="w-full h-full object-cover"
                            alt="Profile Picture">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'User') }}&background=10b981&color=fff&size=128"
                            class="w-full h-full object-cover" alt="Profile Picture">
                    @endif
                </div>

                <h3 class="text-2xl font-black text-gray-800 uppercase">{{ $user->name ?? 'Nama Pengguna' }}</h3>
                <p class="text-xs font-bold text-[#10b981] uppercase tracking-widest mb-8">
                    {{ ucfirst($user->role ?? 'Role') }}
                </p>

                <!-- Informasi Detail -->
                <div class="w-full space-y-4">
                    <div class="flex items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
                        <i class="fas fa-id-badge text-[#10b981] w-10 text-center text-xl"></i>
                        <div class="ml-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">NIP / Username</p>
                            <p class="text-sm font-black text-gray-700">{{ $user->nip ?? ($user->username ?? '-') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
                        <i class="fas fa-venus-mars text-[#10b981] w-10 text-center text-xl"></i>
                        <div class="ml-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Gender</p>
                            <p class="text-sm font-black text-gray-700">{{ $user->jk ?? ($user->gender ?? '-') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-5 bg-gray-50 rounded-2xl border border-gray-100">
                        <i class="fas fa-phone-alt text-[#10b981] w-10 text-center text-xl"></i>
                        <div class="ml-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No. Telepon</p>
                            <p class="text-sm font-black text-gray-700">{{ $user->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="toggleEditProfile(true)"
                    class="mt-8 bg-[#10b981] text-white px-8 py-4 rounded-2xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-105 transition w-full">
                    <i class="fas fa-user-edit mr-2"></i> Edit Profil
                </button>
            </div>

            <!-- FORM EDIT PROFIL (Disembunyikan Default) -->
            <form id="profileForm" class="hidden flex-col transition-all duration-300"
                action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-4">
                    <h3 class="text-lg font-black text-gray-700 uppercase tracking-widest">Edit Profil</h3>
                    <button type="button" onclick="toggleEditProfile(false)"
                        class="text-gray-400 hover:text-red-500 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Unggah
                            Foto Profil Baru</label>
                        <input type="file" name="photo" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-50 file:text-[#10b981] hover:file:bg-green-100 transition border border-gray-100 rounded-xl bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Nama
                            Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name ?? '' }}" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                    </div>

                    <!-- Karena Username/NIP biasanya menjadi acuan login, kita buat disabled (tidak bisa diubah) -->
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">NIP /
                            Username</label>
                        <input type="text" value="{{ $user->nip ?? ($user->username ?? '') }}" disabled
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-bold text-gray-400 cursor-not-allowed">
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Gender</label>
                        <select name="gender"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                            <option value="">Pilih Gender</option>
                            <option value="Laki-laki"
                                {{ ($user->jk ?? ($user->gender ?? '')) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="Perempuan"
                                {{ ($user->jk ?? ($user->gender ?? '')) === 'Perempuan' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">No.
                            Telepon</label>
                        <input type="tel" name="phone" value="{{ $user->phone ?? '' }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-green-100 transition">
                    </div>

                    <!-- Opsi Ganti Password -->
                    <div class="pt-4 mt-2 border-t border-gray-100">
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Ganti
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
    </main>

    <script>
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
    </script>

</body>

</html>
