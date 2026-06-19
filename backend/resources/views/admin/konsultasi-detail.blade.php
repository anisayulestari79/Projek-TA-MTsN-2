<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Konsultasi BK - Sistem Pelanggaran Poin</title>
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
    </style>
</head>

<body class="bg-[#f4f7f6] font-sans flex text-gray-800 min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-72 min-h-screen bg-[#10b981] text-white flex flex-col fixed shadow-xl z-50">
        <div class="p-8 border-b border-white/10">
            <div class="flex items-center gap-3 mb-2">
                <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                    class="w-10 drop-shadow-md" alt="Logo Kemenag">
                <h1 class="font-bold text-xl leading-tight tracking-tight uppercase">Sistem <br> Pelanggaran Poin</h1>
            </div>
            <p class="text-[10px] opacity-80 font-bold tracking-widest uppercase ml-1">MTsN 2 Kota Banjarmasin</p>
        </div>

        <nav class="mt-6 flex-grow pl-6 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl duration-300">
                <i class="fas fa-th-large mr-4 text-lg opacity-80"></i> <span
                    class="font-medium tracking-wide">Dashboard</span>
            </a>
            <a href="{{ route('admin.guru.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl duration-300">
                <i class="fas fa-chalkboard-teacher mr-4 text-lg opacity-80"></i> <span
                    class="font-medium tracking-wide">Data Guru</span>
            </a>
            <a href="{{ route('admin.siswa.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl duration-300">
                <i class="fas fa-users mr-4 text-lg opacity-80"></i> <span class="font-medium tracking-wide">Data
                    Siswa</span>
            </a>

            <!-- MENU KONSULTASI AKTIF -->
            <a href="{{ route('admin.konsultasi.index') }}" id="nav-konsultasi"
                class="sidebar-item active flex items-center px-6 py-4 transition duration-300">
                <i class="fas fa-comments mr-4 text-lg"></i> <span class="font-bold tracking-wide">Konsultasi BK</span>
            </a>

            <a href="{{ route('admin.poin.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl duration-300">
                <i class="fas fa-star mr-4 text-lg opacity-80"></i> <span class="font-medium tracking-wide">Poin
                    Siswa</span>
            </a>
            <a href="{{ route('admin.tahunajaran.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl">
                <i class="fas fa-calendar-alt mr-4 text-sm"></i> <span class="font-medium">Tahun Ajaran</span>
            </a>
            <a href="{{ route('admin.audit.index') }}"
                class="sidebar-item flex items-center px-6 py-4 hover:bg-white/10 transition rounded-l-xl duration-300">
                <i class="fas fa-history mr-4 text-lg opacity-80"></i> <span class="font-medium tracking-wide">Audit
                    Log</span>
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 ml-72 p-10 relative">
        <!-- GLOBAL HEADER -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <nav class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                    <a href="{{ route('admin.konsultasi.index') }}" class="hover:text-blue-500">Konsultasi BK</a> /
                    <span class="text-gray-600">Detail</span>
                </nav>
                <h2 class="text-3xl font-black text-gray-700 uppercase tracking-tighter italic">
                    Detail Konsultasi
                </h2>
                <p class="text-xs text-gray-400 font-bold uppercase mt-1">Layanan Bimbingan Konseling Siswa</p>
            </div>

            <!-- User Profile & Dropdown -->
            <div class="relative">
                <div class="flex items-center gap-4 bg-white px-6 py-2 rounded-full shadow-sm border border-gray-100">
                    <div class="text-right">
                        <p class="text-xs font-black text-[#10b981] uppercase leading-none">
                            {{ $user['name'] ?? 'Admin User' }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Status: Administrator</p>
                    </div>
                    <img src="{{ $user['photo'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user['name'] ?? 'Admin') . '&background=10b981&color=fff' }}"
                        class="w-10 h-10 rounded-full border-2 border-green-50 shadow-sm object-cover" alt="Profile">
                </div>
            </div>
        </header>

        <!-- ALERTS -->
        @if (session('success'))
            <div
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-2xl relative mb-6 shadow-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i> <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- PERUBAHAN: Menghapus max-w-4xl mx-auto menjadi w-full agar melebar kesamping -->
        <div class="bg-white rounded-[30px] shadow-sm border border-gray-50 overflow-hidden w-full">

            <!-- HEADER KONTROL (Lebih Ringkas) -->
            <div
                class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                <a href="{{ route('admin.konsultasi.index') }}"
                    class="w-full sm:w-auto bg-white text-gray-600 border border-gray-200 px-5 py-2 rounded-xl text-xs font-bold uppercase hover:bg-gray-50 hover:text-gray-800 transition flex items-center justify-center gap-2 shadow-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

                @if (($consultation->status ?? 'menunggu') !== 'selesai' && ($consultation->status ?? 'menunggu') !== 'dibalas')
                    <div class="w-full sm:w-auto">
                        <form action="{{ route('admin.konsultasi.complete', $consultation->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="w-full bg-[#10b981] text-white px-6 py-2 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-emerald-600 transition flex items-center justify-center gap-2 shadow-sm">
                                <i class="fas fa-check-circle text-sm"></i> Tandai Selesai
                            </button>
                        </form>
                    </div>
                @else
                    <span
                        class="bg-green-50 text-green-700 px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-2 border border-green-100">
                        <i class="fas fa-check-double text-sm"></i> Selesai / Closed
                    </span>
                @endif
            </div>

            <!-- DETAIL KONTENT (Padding dan Spasi Diperkecil) -->
            <div class="p-6">

                <!-- Status & Waktu -->
                <div class="flex flex-wrap gap-3 justify-between items-center mb-4 border-b border-gray-50 pb-4">
                    <div class="flex items-center gap-3">
                        @if (($consultation->pengirim ?? 'ortu') == 'bk')
                            <span
                                class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest">Panggilan
                                Sekolah</span>
                        @else
                            @if (($consultation->status ?? 'menunggu') == 'menunggu')
                                <span
                                    class="bg-orange-100 text-orange-600 px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest">Menunggu
                                    Respon</span>
                            @else
                                <span
                                    class="bg-green-100 text-green-700 px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest">Sudah
                                    Dibalas</span>
                            @endif
                        @endif
                        <span class="text-xs text-gray-400 font-bold ml-1">
                            <i class="far fa-clock mr-1"></i> {{ $consultation->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                    <span
                        class="text-xs font-bold text-gray-600 bg-gray-50 px-4 py-1.5 rounded-xl border border-gray-100 shadow-sm flex items-center gap-2">
                        <i class="fas fa-child text-[#10b981]"></i>
                        {{ $consultation->student->nama ?? 'Siswa Terhapus' }}
                        ({{ $consultation->student->kelas ?? '-' }})
                    </span>
                </div>

                <!-- PERUBAHAN: Grid disesuaikan lg:col-span-2 dan lg:col-span-10 agar melebar kesamping proporsional -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-4 gap-x-4 items-start">

                    <!-- Pengirim -->
                    <div class="lg:col-span-2">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-3">Pengirim</p>
                    </div>
                    <div class="lg:col-span-10">
                        <div
                            class="flex items-center gap-3 bg-gray-50/50 px-3 py-2 rounded-xl border border-gray-100 w-fit">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-sm shadow-inner">
                                <i
                                    class="fas {{ ($consultation->pengirim ?? 'ortu') == 'bk' ? 'fa-chalkboard-teacher' : 'fa-user-tie' }}"></i>
                            </div>
                            <div>
                                @if (($consultation->pengirim ?? 'ortu') == 'bk')
                                    <p class="text-sm font-bold text-gray-800 leading-none">
                                        {{ $consultation->bk->name ?? 'Guru BK' }}</p>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mt-1">Pihak
                                        Sekolah</p>
                                @else
                                    <p class="text-sm font-bold text-gray-800 leading-none">
                                        {{ $consultation->parent->name ?? 'Wali Murid' }}</p>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mt-1">Orang
                                        Tua</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Topik -->
                    <div class="lg:col-span-2">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Topik</p>
                    </div>
                    <div class="lg:col-span-10 flex items-center gap-3">
                        <h4 class="text-sm font-black text-gray-800 uppercase tracking-tight">
                            {{ $consultation->topic }}</h4>
                        @if ($consultation->academic_period)
                            <span
                                class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-widest border border-gray-200">TA:
                                {{ $consultation->academic_period }}</span>
                        @endif
                    </div>

                    <!-- Pesan Utama -->
                    <div class="lg:col-span-2">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-3">Isi Pesan</p>
                    </div>
                    <div class="lg:col-span-10">
                        <div class="bg-blue-50/30 px-5 py-4 rounded-2xl border border-blue-100 relative">
                            <i class="fas fa-quote-left absolute top-3 left-3 text-lg text-blue-200/50"></i>
                            <p
                                class="text-xs leading-relaxed text-gray-700 font-medium relative z-10 whitespace-pre-line pl-6 pr-2">
                                {{ $consultation->message }}</p>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="lg:col-span-12 border-b border-gray-50 my-1"></div>

                    <!-- Formulir Tanggapan / Balasan -->
                    <div class="lg:col-span-2">
                        <p class="text-[10px] font-black text-[#10b981] uppercase tracking-widest mt-3">
                            @if (($consultation->pengirim ?? 'ortu') == 'bk')
                                Respon Ortu
                            @else
                                Respon Anda
                            @endif
                        </p>
                    </div>

                    <div class="lg:col-span-10">
                        @if (($consultation->status ?? 'menunggu') !== 'selesai' && ($consultation->status ?? 'menunggu') !== 'dibalas')
                            @if (($consultation->pengirim ?? 'ortu') == 'bk')
                                <div
                                    class="bg-orange-50 px-5 py-3 rounded-xl border border-orange-100 flex items-center gap-4 w-fit">
                                    <div
                                        class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-orange-400 text-sm shadow-sm shrink-0">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-700 leading-none">Menunggu Konfirmasi
                                            Wali Murid</p>
                                        <p class="text-[9px] text-gray-500 mt-1">Sistem menunggu wali murid membalas
                                            pesan ini.</p>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('admin.konsultasi.reply', $consultation->id) }}"
                                    method="POST" class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                                    @csrf
                                    @method('PUT')

                                    <!-- PERUBAHAN: Form lebih pendek (rows=3) -->
                                    <textarea name="reply" rows="3" required
                                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-xs font-medium outline-none focus:ring-2 focus:ring-[#10b981] transition resize-none mb-3"
                                        placeholder="Ketikkan balasan, arahan, atau tindak lanjut di sini..."></textarea>

                                    <div class="flex justify-end">
                                        <button type="submit"
                                            class="bg-[#10b981] text-white px-6 py-2 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-emerald-600 transition flex items-center gap-2 shadow-sm">
                                            <i class="fas fa-paper-plane"></i> Kirim Respon
                                        </button>
                                    </div>
                                </form>
                            @endif
                        @else
                            <!-- Tampilan Jika Sudah Dibalas / Selesai -->
                            <div class="bg-green-50/30 px-5 py-4 rounded-2xl border border-green-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <div
                                        class="w-5 h-5 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-[10px]">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <p class="text-[10px] font-black text-gray-700 uppercase tracking-widest">
                                        @if (($consultation->pengirim ?? 'ortu') == 'bk')
                                            Tanggapan Wali Murid
                                        @else
                                            Dibalas Oleh: {{ $consultation->bk->name ?? 'Admin / Sekolah' }}
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                                    <p class="text-xs leading-relaxed text-gray-700 font-medium whitespace-pre-line">
                                        {{ $consultation->reply ?? 'Tidak ada teks tanggapan yang tercatat. Tiket telah ditutup.' }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </main>

</body>

</html>
