<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Orang Tua - MTsN 2 Kota Banjarmasin</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Elemen Background Dekoratif (Hijau Khas Sekolah) -->
    <div class="absolute top-0 left-0 w-full h-[40vh] bg-[#007a5c] rounded-b-[50px] sm:rounded-b-[100px] z-0 shadow-lg">
    </div>
    <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-2xl z-0"></div>
    <div class="absolute top-20 -left-20 w-48 h-48 bg-white/10 rounded-full blur-2xl z-0"></div>

    <!-- Container Form Login -->
    <div class="bg-white w-full max-w-md rounded-[30px] shadow-2xl p-8 sm:p-10 relative z-10 border border-gray-100">

        <!-- Tombol Kembali ke Index -->
        <a href="{{ route('index') }}"
            class="absolute top-6 left-6 text-gray-400 hover:text-[#10b981] transition-colors flex items-center justify-center w-8 h-8 bg-gray-50 rounded-full">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- Header / Logos -->
        <div class="flex justify-center gap-3 mb-6 mt-4">
            <img src="https://ppdb.mtsn2bjm.sch.id/img/logo.png" alt="Logo Kemenag" class="h-12 w-auto object-contain">
            <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                alt="Logo MTsN 2" class="h-12 w-auto object-contain">
        </div>

        <div class="text-center mb-8">
            <!-- Ikon Orang Tua dengan warna Hijau Zamrud -->
            <div
                class="w-14 h-14 bg-green-50 text-[#10b981] rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 shadow-inner border border-green-100">
                <i class="fas fa-user-friends"></i>
            </div>
            <h2 class="text-2xl font-black text-gray-800 tracking-tight leading-none mb-1">Login Orang Tua</h2>
            <p class="text-xs text-gray-400 font-medium">Sistem Pelanggaran Poin Siswa</p>
        </div>

        <!-- Menampilkan Error dari Controller jika ada -->
        @if (session('error'))
            <div
                class="mb-5 p-3.5 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-bold text-center flex items-center justify-center gap-2">
                <i class="fas fa-exclamation-circle text-sm"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Menampilkan Success dari Controller jika ada (seperti setelah register) -->
        @if (session('success'))
            <div
                class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-xs font-bold text-center flex items-center justify-center gap-2">
                <i class="fas fa-check-circle text-sm"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Form Login -->
        <!-- Pastikan Anda membuat route 'ortu.login.submit' di web.php -->
        <form id="ortuLoginForm" action="{{ route('ortu.login.submit') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Kolom Username / Email -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Email /
                    Username</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                    <input type="text" name="email" placeholder="Masukkan Email atau Username" required
                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#10b981] focus:bg-white transition-all">
                </div>
            </div>

            <!-- Kolom Password -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Kata
                    Sandi</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                    <input type="password" name="password" id="passwordInput" placeholder="Masukkan Kata Sandi" required
                        class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#10b981] focus:bg-white transition-all">

                    <button type="button" id="togglePassword"
                        class="absolute right-4 top-3.5 text-gray-400 hover:text-[#10b981] transition-colors focus:outline-none">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Ingat Saya -->
            <div class="flex items-center justify-between mt-2 ml-1">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <div class="relative flex items-center justify-center">
                        <input type="checkbox" name="remember"
                            class="peer w-4 h-4 text-[#10b981] bg-gray-100 border-gray-300 rounded focus:ring-[#10b981] focus:ring-2 cursor-pointer appearance-none transition-all">
                        <i
                            class="fas fa-check absolute text-white text-[10px] opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 group-hover:text-gray-700 transition-colors">Ingat
                        Saya</span>
                </label>
            </div>

            <!-- Tombol Submit (Warna Hijau Terang Khas Ortu) -->
            <button type="submit"
                class="w-full bg-[#10b981] hover:bg-[#059669] text-white font-black text-xs tracking-widest uppercase py-3.5 rounded-xl shadow-lg shadow-green-200 transition-all transform hover:-translate-y-1 mt-4 flex justify-center items-center gap-2">
                Masuk ke Sistem <i class="fas fa-sign-in-alt"></i>
            </button>
        </form>

        <!-- Footer Form & Link Pendaftaran -->
        <div class="mt-8 text-center border-t border-gray-100 pt-6">
            <p class="text-[10px] text-gray-500 font-medium tracking-wide mb-3">
                Belum memiliki akun? <a href="{{ route('ortu.register') }}"
                    class="text-[#10b981] font-bold hover:underline">Daftar di sini</a>
            </p>
            <p class="text-[10px] text-gray-400 font-medium tracking-wide">
                <i class="fas fa-info-circle mr-1 text-[#10b981]"></i> Akses pemantauan khusus Wali Murid
            </p>
        </div>
    </div>

    <!-- SCRIPT UNTUK TOGGLE PASSWORD -->
    <script>
        const toggleBtn = document.getElementById('togglePassword');
        const passInput = document.getElementById('passwordInput');

        toggleBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>

</html>
