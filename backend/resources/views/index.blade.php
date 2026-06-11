<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Akses Login - MTsN 2 Kota Banjarmasin</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Mengimpor Font Poppins agar sama persis seperti di gambar */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="min-h-screen flex flex-col justify-between bg-[#f8fafc]">

        <!-- HEADER / NAVBAR -->
        <header class="bg-[#007a5c] text-white py-4 px-6 md:px-12 flex justify-between items-center shadow-md">
            <div class="flex items-center gap-4">
                <!-- Dua Logo Berdampingan (Kemenag & MTsN 2) -->
                <div class="flex items-center gap-2">
                    <img src="https://ppdb.mtsn2bjm.sch.id/img/logo.png" alt="Logo Kemenag"
                        class="h-10 md:h-12 w-auto object-contain">
                    <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                        alt="Logo MTsN 2" class="h-10 md:h-12 w-auto object-contain">
                </div>
                <div>
                    <h1 class="font-bold text-sm md:text-lg tracking-wide uppercase leading-tight">Sistem Pelanggaran
                        Poin Siswa</h1>
                    <p class="text-[10px] md:text-xs opacity-90 font-medium">MTsN 2 Kota Banjarmasin</p>
                </div>
            </div>

            <!-- Pill Waktu Dinamis -->
            <div
                class="flex items-center gap-2 bg-[#005c45] px-4 py-1.5 rounded-full text-xs text-yellow-300 font-bold border border-[#008f6b] shadow-inner hidden sm:flex">
                <i class="far fa-clock text-xs"></i>
                <span id="greetingText">Selamat Siang</span>
            </div>
        </header>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-grow flex flex-col justify-center py-12 px-4">

            <!-- Judul Pilihan Akses -->
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-extrabold text-[#1e293b] tracking-tight">Pilih Akses Login</h2>
                <p class="text-xs md:text-sm text-gray-400 mt-2 max-w-xl mx-auto">
                    Silakan pilih peran Anda untuk masuk ke sistem monitoring kedisiplinan madrasah
                </p>
                <div class="w-16 h-1 bg-[#007a5c] mx-auto mt-4 rounded-full"></div>
            </div>

            <!-- Grid 4 Kartu Login -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-7xl mx-auto w-full px-4 md:px-8">

                <!-- KARTU 1: PIMPINAN (KAMAD) -->
                <div
                    class="bg-white p-8 rounded-[24px] shadow-sm hover:shadow-md border border-gray-100 flex flex-col items-center justify-between transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mb-5 shadow-inner">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h3 class="font-black text-slate-800 text-lg mb-2">Pimpinan</h3>
                        <p class="text-xs text-gray-400 leading-relaxed mb-6">
                            Pantau ringkasan poin dan laporan kedisiplinan madrasah.
                        </p>
                    </div>
                    <a href="{{ route('kamad.login') }}"
                        class="bg-[#007a5c] text-white hover:bg-[#005c45] font-extrabold text-xs tracking-wider uppercase py-3.5 px-8 rounded-full shadow-md shadow-emerald-100 transition-all w-full text-center hover:scale-[1.02]">
                        LOGIN KAMAD
                    </a>
                </div>


                <!-- KARTU 2: GURU -->
                <div
                    class="bg-white p-8 rounded-[24px] shadow-sm hover:shadow-md border border-gray-100 flex flex-col items-center justify-between transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-5 shadow-inner">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="font-black text-slate-800 text-lg mb-2">Guru</h3>
                        <p class="text-xs text-gray-400 leading-relaxed mb-6">
                            Kelola data pelanggaran kelas dan rekap poin harian.
                        </p>
                    </div>
                    <a href="{{ route('guru.login') }}"
                        class="bg-[#2563eb] text-white hover:bg-[#1d4ed8] font-extrabold text-xs tracking-wider uppercase py-3.5 px-8 rounded-full shadow-md shadow-blue-100 transition-all w-full text-center hover:scale-[1.02]">
                        LOGIN GURU
                    </a>
                </div>

                <!-- KARTU 3: ADMIN -->
                <div
                    class="bg-white p-8 rounded-[24px] shadow-sm hover:shadow-md border border-gray-100 flex flex-col items-center justify-between transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 bg-slate-100 text-slate-700 rounded-2xl flex items-center justify-center text-2xl mb-5 shadow-inner">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3 class="font-black text-slate-800 text-lg mb-2">Admin</h3>
                        <p class="text-xs text-gray-400 leading-relaxed mb-6">
                            Manajemen data master guru, siswa, dan sistem.
                        </p>
                    </div>
                    <a href="{{ route('admin.login') }}"
                        class="bg-[#1e293b] text-white hover:bg-[#0f172a] font-extrabold text-xs tracking-wider uppercase py-3.5 px-8 rounded-full shadow-md shadow-slate-200 transition-all w-full text-center hover:scale-[1.02]">
                        LOGIN ADMIN
                    </a>
                </div>

                <!-- KARTU 4: ORANG TUA -->
                <div
                    class="bg-white p-8 rounded-[24px] shadow-sm hover:shadow-md border border-gray-100 flex flex-col items-center justify-between transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex flex-col items-center text-center">
                        <div
                            class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-2xl mb-5 shadow-inner">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h3 class="font-black text-slate-800 text-lg mb-2">Orang Tua</h3>
                        <p class="text-xs text-gray-400 leading-relaxed mb-4">
                            Pantau riwayat poin dan perilaku anak secara real-time.
                        </p>
                    </div>
                    <div class="w-full text-center">
                        <!-- Sambungan ke Login Ortu -->
                        <a href="{{ route('ortu.login') }}"
                            class="bg-[#10b981] text-white hover:bg-[#059669] font-extrabold text-xs tracking-wider uppercase py-3.5 px-8 rounded-full shadow-md shadow-green-100 transition-all w-full block text-center hover:scale-[1.02]">
                            LOGIN ORANG TUA
                        </a>
                        <p class="text-[10px] text-gray-400 mt-4">
                            <!-- Sambungan ke Register Ortu -->
                            Belum memiliki akun? <a href="{{ route('ortu.register') }}"
                                class="text-[#10b981] font-bold hover:underline">Daftar di sini</a>
                        </p>
                    </div>
                </div>

            </div>
        </main>

        <!-- FOOTER -->
        <footer class="text-center py-6 text-[10px] md:text-xs text-gray-400 font-medium">
            © 2026 MTsN 2 Kota Banjarmasin. Developed by Politeknik Negeri Banjarmasin.
        </footer>

    </div>

    <!-- SCRIPT UNTUK MENYESUAIKAN SALUTATION DENGAN WAKTU SEBENARNYA -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const hours = new Date().getHours();
            const greetingElement = document.getElementById("greetingText");

            let greeting = "Selamat Siang";
            if (hours >= 5 && hours < 11) {
                greeting = "Selamat Pagi";
            } else if (hours >= 11 && hours < 15) {
                greeting = "Selamat Siang";
            } else if (hours >= 15 && hours < 18) {
                greeting = "Selamat Sore";
            } else {
                greeting = "Selamat Malam";
            }

            if (greetingElement) greetingElement.innerText = greeting;
        });
    </script>
</body>

</html>
