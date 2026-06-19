<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tahun Ajaran - Sistem Pelanggaran Poin</title>
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

        .view-section {
            display: none;
        }

        .view-section.active {
            display: block;
        }
    </style>
</head>

<body class="bg-[#f4f7f6] font-sans flex text-gray-800 h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-72 bg-[#10b981] text-white flex flex-col shadow-xl z-50 shrink-0 h-full">
        <div class="p-8 relative border-b border-white/10">
            <div class="flex items-center gap-3 mb-2">
                <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                    class="w-10 drop-shadow-md" alt="Logo">
                <h1 class="font-bold text-lg leading-tight tracking-tight uppercase">Sistem<br>Pelanggaran<br>Poin Siswa
                </h1>
            </div>
            <p class="text-[9px] opacity-80 font-bold tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
        </div>

        <nav class="mt-4 flex-grow pl-6 overflow-y-auto pr-2 space-y-1 pb-10">
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-item flex items-center px-6 py-4 transition hover:bg-white/10 rounded-l-xl">
                <i class="fas fa-th-large mr-4 text-sm opacity-80"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.guru.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-chalkboard-teacher mr-4 text-sm opacity-80"></i> <span class="font-medium">Data
                    Guru</span>
            </a>
            <a href="{{ route('admin.siswa.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-users mr-4 text-sm opacity-80"></i> <span>Data Siswa</span>
            </a>
            <a href="{{ route('admin.konsultasi.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-comments mr-4 text-sm opacity-80"></i> <span class="font-medium">Konsultasi BK</span>
            </a>
            <a href="{{ route('admin.poin.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-star mr-4 text-sm opacity-80"></i> <span class="font-medium">Poin Siswa</span>
            </a>

            <!-- MENU TAHUN AJARAN (BARU) -->
            <a href="{{ route('admin.tahunajaran.index') }}"
                class="sidebar-item active flex items-center px-6 py-4 transition shadow-sm">
                <i class="fas fa-calendar-alt mr-4 text-sm"></i> <span class="font-bold tracking-wide">Tahun
                    Ajaran</span>
            </a>

            <a href="{{ route('admin.audit.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-history mr-4 text-sm opacity-80"></i> <span class="font-medium">Audit Log</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6 md:p-10 overflow-y-auto h-full relative w-full">

        <header class="flex justify-between items-start md:items-center mb-10">
            <div>
                <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                    Home / Data Master / Tahun Ajaran
                </nav>
                <h2
                    class="text-2xl md:text-3xl font-black text-gray-700 uppercase tracking-tighter italic leading-tight">
                    Manajemen Tahun Ajaran
                </h2>
                <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Pengaturan Masa Aktif Sistem Akademik</p>
            </div>

            <div
                class="flex items-center gap-3 bg-white px-4 md:px-6 py-2 rounded-full shadow-sm border border-gray-100">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-black text-[#10b981] uppercase leading-none">{{ $user['name'] ?? 'Admin' }}
                    </p>
                    <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">Status: Administrator</p>
                </div>
                <img src="{{ $user['photo'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user['name'] ?? 'Admin') . '&background=10b981&color=fff' }}"
                    class="w-10 h-10 rounded-full border-2 border-green-50 object-cover" alt="Profile">
            </div>
        </header>

        <!-- ALERTS -->
        @if (session('success'))
            <div
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-2xl relative mb-6 shadow-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl relative mb-6 shadow-sm flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-lg"></i>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl relative mb-6 shadow-sm flex items-center gap-3">
                <i class="fas fa-times-circle text-lg"></i>
                <span class="font-bold text-sm">Gagal memproses data. Pastikan format penulisan benar.</span>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">

            <!-- KARTU TAMBAH TAHUN AJARAN -->
            <div class="xl:col-span-1 bg-white p-8 rounded-[30px] shadow-sm border border-gray-50 h-fit sticky top-10">
                <h3
                    class="font-black text-gray-700 text-base uppercase tracking-widest mb-6 border-b pb-4 flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plus"></i>
                    </div>
                    Tahun Ajaran Baru
                </h3>

                <form action="{{ route('admin.tahunajaran.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Nama
                            Tahun Ajaran</label>
                        <div class="relative">
                            <i class="fas fa-calendar-alt absolute left-4 top-3.5 text-gray-300 text-xs"></i>
                            <input type="text" name="nama" required placeholder="Contoh: 2024/2025"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#10b981] outline-none transition">
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Semester</label>
                        <div class="relative">
                            <i class="fas fa-layer-group absolute left-4 top-3.5 text-gray-300 text-xs"></i>
                            <select name="semester" required
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#10b981] transition appearance-none cursor-pointer">
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 text-[10px] pointer-events-none"></i>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#10b981] text-white px-6 py-4 rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-100 hover:scale-[1.02] hover:bg-green-600 transition-all flex justify-center items-center gap-2 mt-4">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                </form>
            </div>

            <!-- KARTU DAFTAR TAHUN AJARAN -->
            <div class="xl:col-span-2 bg-white p-8 rounded-[30px] shadow-sm border border-gray-50">
                <h3
                    class="font-black text-gray-700 text-base uppercase tracking-widest mb-6 border-b pb-4 flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-50 text-purple-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    Daftar Periode Akademik
                </h3>

                <div class="overflow-x-auto border border-gray-50 rounded-2xl mt-4">
                    <table class="w-full text-left border-collapse min-w-[500px]">
                        <thead class="bg-[#005c4b] text-white text-[10px] font-black uppercase tracking-widest">
                            <tr>
                                <th class="p-4 rounded-tl-2xl w-16 text-center">No</th>
                                <th class="p-4">Tahun Ajaran</th>
                                <th class="p-4 text-center">Semester</th>
                                <th class="p-4 text-center">Status</th>
                                <th class="p-4 text-center rounded-tr-2xl w-40">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm bg-white">
                            @forelse($tahunAjarans ?? [] as $index => $ta)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-4 text-center text-gray-400 font-bold">{{ $index + 1 }}</td>
                                    <td class="p-4 font-black text-gray-800">{{ $ta->nama }}</td>
                                    <td class="p-4 text-center font-bold text-gray-600">{{ $ta->semester }}</td>
                                    <td class="p-4 text-center">
                                        @if ($ta->is_active)
                                            <span
                                                class="bg-green-100 text-green-700 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider border border-green-200">
                                                <i class="fas fa-check-circle mr-1"></i> Sedang Aktif
                                            </span>
                                        @else
                                            <span
                                                class="bg-gray-100 text-gray-500 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider border border-gray-200">
                                                Non-Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 flex items-center justify-center gap-2">
                                        <!-- Tombol Set Aktif (Hanya muncul jika belum aktif) -->
                                        @if (!$ta->is_active)
                                            <form action="{{ route('admin.tahunajaran.aktif', $ta->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white border border-blue-100 px-3 py-2 rounded-lg text-[10px] font-bold uppercase tracking-wider transition shadow-sm"
                                                    title="Jadikan Aktif">
                                                    Aktifkan
                                                </button>
                                            </form>

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('admin.tahunajaran.destroy', $ta->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus Tahun Ajaran ini? Data yang terkait mungkin akan terpengaruh.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-50 text-red-500 hover:bg-red-500 hover:text-white px-3 py-2 rounded-lg text-xs transition shadow-sm"
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <!-- Jika sedang aktif, dilarang menghapus -->
                                            <span
                                                class="text-[10px] text-gray-400 font-bold italic border border-dashed border-gray-200 px-3 py-1.5 rounded-lg">Default
                                                System</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-10 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-calendar-times text-4xl mb-3 opacity-50"></i>
                                            <p class="font-bold">Belum ada data Tahun Ajaran yang diinput.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 bg-blue-50/50 border border-blue-100 p-4 rounded-xl flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    <p class="text-[10px] text-gray-500 leading-relaxed font-medium">
                        <strong class="text-blue-600">Informasi Penting:</strong> Hanya boleh ada <span
                            class="font-black text-gray-700">satu</span> Tahun Ajaran yang berstatus Aktif. Tahun
                        Ajaran Aktif akan digunakan sebagai standar rekaman status Siswa dan riwayat Poin Kedisiplinan
                        secara *real-time*.
                    </p>
                </div>
            </div>
        </div>
    </main>

</body>

</html>
