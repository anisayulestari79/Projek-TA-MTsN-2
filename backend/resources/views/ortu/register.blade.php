<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Orang Tua - MTsN 2 Kota Banjarmasin</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Elemen Background Dekoratif -->
    <div class="absolute top-0 left-0 w-full h-[40vh] bg-[#10b981] rounded-b-[50px] sm:rounded-b-[100px] z-0 shadow-lg">
    </div>
    <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-2xl z-0"></div>

    <!-- Container Form Register -->
    <div
        class="bg-white w-full max-w-lg rounded-[30px] shadow-2xl p-8 sm:p-10 relative z-10 border border-gray-100 my-10">

        <!-- Tombol Kembali ke Login -->
        <a href="{{ route('ortu.login') }}"
            class="absolute top-6 left-6 text-gray-400 hover:text-[#10b981] transition-colors flex items-center justify-center w-8 h-8 bg-gray-50 rounded-full">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- Header -->
        <div class="text-center mb-8">
            <div
                class="w-14 h-14 bg-green-50 text-[#10b981] rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 shadow-inner border border-green-100/50">
                <i class="fas fa-user-friends"></i>
            </div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight leading-none mb-1">Daftar Akun Wali</h2>
            <p class="text-xs text-gray-400 font-medium">Monitoring Kedisiplinan Siswa</p>
        </div>

        <!-- Pesan Error -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <p class="text-xs font-bold text-red-700">Pendaftaran gagal, periksa kembali:</p>
                </div>
                <ul class="list-disc list-inside text-[10px] font-medium text-red-600 ml-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Pendaftaran -->
        <form action="{{ route('ortu.register.submit') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Kolom Nama -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Nama
                    Lengkap Bapak/Ibu</label>
                <div class="relative">
                    <i class="fas fa-id-card absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Contoh: Budi Susanto"
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#10b981] focus:bg-white transition-all">
                </div>
            </div>

            <!-- Kolom Email -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Alamat
                    Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="Contoh: budi@gmail.com"
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#10b981] focus:bg-white transition-all">
                </div>
            </div>

            <!-- Kolom NISN ANAK (KUNCI OTOMATISASI) -->
            <div>
                <label class="block text-[10px] font-bold text-[#10b981] uppercase tracking-widest mb-1.5 ml-1">NISN
                    Anak (Wajib 10 Angka)</label>
                <div class="relative">
                    <i class="fas fa-graduation-cap absolute left-4 top-3.5 text-[#10b981] text-sm"></i>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" required
                        placeholder="Masukkan 10 digit NISN Anak Anda"
                        class="w-full pl-10 pr-4 py-3 bg-green-50/50 border border-green-200 rounded-xl text-sm font-bold text-gray-800 outline-none focus:ring-2 focus:ring-[#10b981] transition-all">
                </div>
                <p class="text-[9px] text-gray-400 mt-1.5 ml-1 italic">*Data Anda akan otomatis terhubung dengan anak
                    menggunakan NISN ini.</p>
            </div>

            <!-- Kolom Sandi -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Kata
                        Sandi</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                        <input type="password" name="password" required placeholder="Min 6 karakter"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#10b981] focus:bg-white transition-all">
                    </div>
                </div>
                <div>
                    <label
                        class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Ulangi
                        Sandi</label>
                    <div class="relative">
                        <i class="fas fa-check-circle absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                        <input type="password" name="password_confirmation" required placeholder="Ketik ulang"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#10b981] focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <button type="submit"
                class="w-full bg-[#10b981] hover:bg-green-600 text-white font-black text-xs tracking-widest uppercase py-3.5 rounded-xl shadow-lg shadow-green-200 transition-all transform hover:-translate-y-1 mt-6 flex justify-center items-center gap-2">
                Daftar & Hubungkan <i class="fas fa-link"></i>
            </button>

            <p class="text-center text-xs text-gray-500 mt-5 font-medium">
                Sudah memiliki akun? <a href="{{ route('ortu.login') }}"
                    class="text-[#10b981] font-bold hover:underline">Masuk di sini</a>
            </p>
        </form>
    </div>

</body>

</html>
