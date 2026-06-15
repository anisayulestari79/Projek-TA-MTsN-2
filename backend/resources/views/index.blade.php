<!DOCTYPE html>
<html lang="id" class="scroll-smooth" style="scroll-padding-top: 80px;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sistem Pelanggaran Poin Siswa - MTsN 2 Kota Banjarmasin</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        kemenag: {
                            500: '#10b981', // Emerald 500
                            600: '#059669', // Emerald 600
                            700: '#047857', // Emerald 700
                            900: '#064e3b', // Emerald 900
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-20px)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom Background Pattern & Blobs */
        .bg-pattern {
            background-image: radial-gradient(rgba(16, 185, 129, 0.15) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        .blob-1 {
            position: absolute;
            top: -10%;
            right: -10%;
            width: 50vw;
            height: 50vw;
            max-width: 500px;
            max-height: 500px;
            background: #10b981;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.25;
            z-index: -1;
        }

        .blob-2 {
            position: absolute;
            bottom: -10%;
            left: -10%;
            width: 40vw;
            height: 40vw;
            max-width: 400px;
            max-height: 400px;
            background: #3b82f6;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            z-index: -1;
        }

        /* Hilangkan highlight biru saat tombol ditekan di HP */
        * {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-800 bg-[#f8fafc] overflow-x-hidden relative flex flex-col min-h-screen">

    <!-- Latar Belakang Dekoratif (Absolute) -->
    <div class="fixed inset-0 w-full h-full pointer-events-none z-[-1] overflow-hidden">
        <div class="absolute inset-0 bg-pattern"></div>
        <div class="blob-1"></div>
        <div class="blob-2"></div>
    </div>

    <!-- NAVBAR (Glassmorphism) -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/80 backdrop-blur-lg border-b border-gray-100 shadow-sm"
        id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- Logo & Title -->
                <a href="#" class="flex items-center gap-2 md:gap-3 group">
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <img src="https://ppdb.mtsn2bjm.sch.id/img/logo.png" alt="Kemenag"
                            class="h-8 md:h-10 w-auto group-hover:scale-105 transition-transform">
                        <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                            alt="MTsN 2" class="h-8 md:h-10 w-auto group-hover:scale-105 transition-transform">
                    </div>
                    <div class="flex flex-col">
                        <h1
                            class="font-black text-xs md:text-base tracking-tight leading-none text-kemenag-900 uppercase">
                            Sistem Pelanggaran Poin Siswa</h1>
                        <p class="text-[8px] md:text-[10px] font-bold text-gray-500 uppercase tracking-widest mt-0.5">
                            MTsN 2 Banjarmasin</p>
                    </div>
                </a>

                <!-- Sapaan Waktu (Greeting) -->
                <div class="hidden md:flex items-center justify-center flex-1 mx-4">
                    <div
                        class="flex items-center gap-2 bg-[#005c45] px-4 py-1.5 rounded-full text-[10px] text-yellow-300 font-bold border border-[#008f6b] shadow-inner">
                        <i class="far fa-clock"></i>
                        <span id="greetingText">Selamat Datang</span>
                    </div>
                </div>

                <!-- Menu Desktop -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="#beranda"
                        class="text-sm font-bold text-gray-600 hover:text-kemenag-600 transition">Beranda</a>
                    <a href="#fitur"
                        class="text-sm font-bold text-gray-600 hover:text-kemenag-600 transition">Fitur</a>
                    <a href="#portal-login"
                        class="text-sm font-bold text-gray-600 hover:text-kemenag-600 transition">Portal Login</a>

                    <a href="#portal-login"
                        class="bg-kemenag-500 text-white px-7 py-3 rounded-full text-xs font-bold uppercase tracking-wider shadow-lg shadow-green-200 hover:bg-kemenag-600 hover:-translate-y-1 transition-all">
                        Masuk Sistem
                    </a>
                </div>

                <!-- Tombol Menu Mobile (Hamburger) -->
                <div class="lg:hidden flex items-center">
                    <button id="mobile-menu-btn"
                        class="text-gray-600 hover:text-kemenag-600 focus:outline-none p-2 bg-gray-50 rounded-xl border border-gray-100 active:scale-95 transition-transform">
                        <i class="fas fa-bars text-xl" id="menu-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Mobile (Overlay Slide Down) -->
        <div id="mobile-menu"
            class="lg:hidden fixed inset-x-0 top-20 bg-white/95 backdrop-blur-xl border-b border-gray-100 shadow-2xl transition-all duration-300 transform -translate-y-full opacity-0 pointer-events-none">
            <div class="px-6 py-8 space-y-4 flex flex-col items-center text-center">
                <!-- Mobile Greeting Pill -->
                <div
                    class="flex items-center gap-2 bg-[#005c45] px-4 py-2 rounded-full text-xs text-yellow-300 font-bold border border-[#008f6b] shadow-inner mb-4">
                    <i class="far fa-clock"></i>
                    <span id="mobileGreetingText">Selamat Datang</span>
                </div>

                <a href="#beranda"
                    class="mobile-link block w-full py-3 rounded-2xl text-base font-black text-gray-800 hover:bg-green-50 hover:text-kemenag-600 transition">Beranda</a>
                <a href="#fitur"
                    class="mobile-link block w-full py-3 rounded-2xl text-base font-black text-gray-800 hover:bg-green-50 hover:text-kemenag-600 transition">Fitur
                    Unggulan</a>
                <a href="#portal-login"
                    class="mobile-link block w-full py-3 rounded-2xl text-base font-black text-gray-800 hover:bg-green-50 hover:text-kemenag-600 transition">Portal
                    Login</a>

                <div class="w-full h-px bg-gray-100 my-2"></div>

                <a href="#portal-login"
                    class="mobile-link w-full bg-kemenag-500 text-white px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-wider shadow-lg shadow-green-200 hover:bg-kemenag-600 transition-all">
                    Masuk ke Sistem <i class="fas fa-sign-in-alt ml-2"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section id="beranda"
        class="relative pt-32 pb-16 md:pt-40 md:pb-24 overflow-hidden flex flex-col justify-center min-h-[90vh] md:min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-8 items-center">

                <!-- Teks Kiri (Responsive Centered on Mobile) -->
                <div class="text-center lg:text-left flex flex-col items-center lg:items-start">
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-50 border border-green-100 text-kemenag-600 text-[10px] md:text-xs font-black uppercase tracking-widest mb-6 lg:mb-8 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-kemenag-500 animate-pulse"></span>
                        Sistem Informasi Digital
                    </div>

                    <h1
                        class="text-4xl sm:text-5xl lg:text-6xl font-black text-gray-900 tracking-tight leading-[1.15] md:leading-[1.1] mb-6">
                        Pantau <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-kemenag-500 to-blue-600 relative inline-block">
                            Kedisiplinan
                            <!-- Garis hiasan di bawah teks -->
                            <svg class="absolute w-full h-3 -bottom-1 left-0 text-kemenag-300 opacity-50 hidden md:block"
                                viewBox="0 0 100 10" preserveAspectRatio="none">
                                <path d="M0 5 Q 50 15 100 5" stroke="currentColor" stroke-width="4" fill="transparent"
                                    stroke-linecap="round" />
                            </svg>
                        </span> <br class="hidden sm:block"> Generasi Masa Depan
                    </h1>

                    <p
                        class="text-sm sm:text-base md:text-lg text-gray-500 mb-8 lg:mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-medium px-2 sm:px-0">
                        Platform terpadu untuk memantau perilaku, mencatat pelanggaran, dan membangun komunikasi
                        sinergis antara pihak madrasah dan orang tua.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto px-4 sm:px-0">
                        <a href="#portal-login"
                            class="bg-kemenag-500 text-white px-8 py-4 rounded-2xl text-sm font-bold uppercase tracking-wider shadow-xl shadow-green-200 hover:bg-kemenag-600 hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                            Mulai Gunakan <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="#fitur"
                            class="bg-white text-gray-600 border border-gray-200 px-8 py-4 rounded-2xl text-sm font-bold uppercase tracking-wider hover:bg-gray-50 transition-all flex items-center justify-center gap-2 shadow-sm">
                            Pelajari Fitur
                        </a>
                    </div>
                </div>

                <!-- Gambar/Ilustrasi Kanan (Dibuat sejajar, rapi, dan teratur) -->
                <div class="relative hidden lg:block animate-float">
                    <div
                        class="absolute inset-0 bg-gradient-to-tr from-kemenag-100 to-blue-50 rounded-[40px] transform rotate-3 scale-95 -z-10 shadow-inner">
                    </div>

                    <!-- Kartu-kartu diubah menjadi list vertikal yang rapi dan seragam lebarnya -->
                    <div class="relative flex flex-col gap-5 p-4 max-w-md mx-auto">

                        <!-- Kartu Dekoratif 1 -->
                        <div
                            class="bg-white/90 backdrop-blur-sm p-5 rounded-2xl shadow-lg border border-white hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-4 w-full">
                            <div
                                class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm border border-blue-100">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-gray-800 text-sm">Transparan & Aman</h3>
                                <p class="text-[10px] text-gray-500 mt-1 leading-relaxed">Data poin kedisiplinan
                                    tercatat secara real-time.</p>
                            </div>
                        </div>

                        <!-- Kartu Dekoratif 2 -->
                        <div
                            class="bg-white/90 backdrop-blur-sm p-5 rounded-2xl shadow-lg border border-white hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-4 w-full">
                            <div
                                class="w-12 h-12 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm border border-orange-100">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-gray-800 text-sm">Sangat Komunikatif</h3>
                                <p class="text-[10px] text-gray-500 mt-1 leading-relaxed">Fasilitas konsultasi langsung
                                    dengan Guru BK.</p>
                            </div>
                        </div>

                        <!-- Kartu Dekoratif 3 -->
                        <div
                            class="bg-white/90 backdrop-blur-sm p-5 rounded-2xl shadow-lg border border-white hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-4 w-full">
                            <div
                                class="w-12 h-12 bg-emerald-50 text-kemenag-500 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm border border-green-100">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-gray-800 text-sm">Pantau Perkembangan Anak</h3>
                                <p class="text-[10px] text-gray-500 mt-1 leading-relaxed">Wali murid dapat melihat
                                    riwayat dan grafik kondisi kedisiplinan anak dari rumah tanpa perlu datang ke
                                    sekolah.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FITUR UTAMA SECTION -->
    <section id="fitur" class="py-20 md:py-32 bg-transparent relative z-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-20">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-gray-900 mb-4 px-4">Mengapa Sistem Ini
                    Penting?</h2>
                <p class="text-sm md:text-base text-gray-500 max-w-2xl mx-auto font-medium px-4">Platform ini didesain
                    khusus untuk mendigitalkan dan memudahkan alur pendisiplinan madrasah dari hulu ke hilir.</p>
                <div class="w-16 h-1.5 bg-gradient-to-r from-kemenag-500 to-blue-500 mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                <!-- Fitur 1 -->
                <div
                    class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-green-100/50 transition-all duration-300 group text-center sm:text-left flex flex-col items-center sm:items-start">
                    <div
                        class="w-16 h-16 bg-gray-50 shadow-inner border border-gray-100 rounded-[20px] flex items-center justify-center text-2xl text-kemenag-500 mb-6 group-hover:scale-110 group-hover:bg-kemenag-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800 mb-3">Akses Wali Murid</h3>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed font-medium">Orang tua dapat memantau
                        akumulasi poin dan jenis pelanggaran anak langsung dari genggaman *smartphone*.</p>
                </div>

                <!-- Fitur 2 -->
                <div
                    class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300 group text-center sm:text-left flex flex-col items-center sm:items-start">
                    <div
                        class="w-16 h-16 bg-gray-50 shadow-inner border border-gray-100 rounded-[20px] flex items-center justify-center text-2xl text-blue-500 mb-6 group-hover:scale-110 group-hover:bg-blue-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800 mb-3">Input Poin Cepat</h3>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed font-medium">Guru dapat memberikan poin
                        pelanggaran seketika saat kejadian berlangsung di lingkungan madrasah.</p>
                </div>

                <!-- Fitur 3 -->
                <div
                    class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-orange-100/50 transition-all duration-300 group text-center sm:text-left flex flex-col items-center sm:items-start">
                    <div
                        class="w-16 h-16 bg-gray-50 shadow-inner border border-gray-100 rounded-[20px] flex items-center justify-center text-2xl text-orange-500 mb-6 group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800 mb-3">Konsultasi BK</h3>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed font-medium">Tersedia fitur kotak masuk
                        untuk menjembatani komunikasi antara pihak sekolah (BK) dan orang tua siswa.</p>
                </div>

                <!-- Fitur 4 -->
                <div
                    class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-purple-100/50 transition-all duration-300 group text-center sm:text-left flex flex-col items-center sm:items-start">
                    <div
                        class="w-16 h-16 bg-gray-50 shadow-inner border border-gray-100 rounded-[20px] flex items-center justify-center text-2xl text-purple-500 mb-6 group-hover:scale-110 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300">
                        <i class="fas fa-print"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800 mb-3">Laporan Otomatis</h3>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed font-medium">Sistem merekapitulasi data
                        pelanggaran secara otomatis menjadi dokumen PDF yang siap cetak untuk pimpinan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PORTAL LOGIN SECTION (Warna Background Disamakan) -->
    <section id="portal-login" class="py-20 md:py-32 bg-transparent relative z-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">

            <div class="text-center mb-12 md:mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 mt-2 mb-4">Pilih Akses Login</h2>
                <p class="text-sm text-gray-500 max-w-xl mx-auto font-medium">Silakan pilih peran Anda untuk masuk ke
                    Sistem Pelanggaran Poin Siswa</p>
                <div class="w-16 h-1 bg-[#007a5c] mx-auto mt-6 rounded-full"></div>
            </div>

            <!-- Grid 4 Kartu Login (Putih, Minimalis) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- KARTU 1: PIMPINAN (KAMAD) -->
                <div
                    class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-100 flex flex-col items-center justify-between transition-all duration-300 transform hover:-translate-y-2 relative overflow-hidden text-center hover:shadow-lg">
                    <div class="relative z-10 w-full flex-grow flex flex-col justify-center items-center">
                        <div
                            class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-[20px] flex items-center justify-center text-3xl mb-5 border border-emerald-100">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h3 class="font-black text-gray-800 text-xl mb-2">Pimpinan</h3>
                        <p class="text-xs text-gray-500 leading-relaxed mb-6 font-medium px-2">
                            Pantau ringkasan poin dan laporan kedisiplinan madrasah.
                        </p>
                    </div>
                    <a href="{{ route('kamad.login') }}"
                        class="relative z-10 bg-[#007a5c] text-white font-bold text-xs tracking-widest uppercase py-3.5 px-6 rounded-full transition-all w-full hover:bg-emerald-700 shadow-md">
                        LOGIN KAMAD
                    </a>
                </div>

                <!-- KARTU 2: GURU -->
                <div
                    class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-100 flex flex-col items-center justify-between transition-all duration-300 transform hover:-translate-y-2 relative overflow-hidden text-center hover:shadow-lg">
                    <div class="relative z-10 w-full flex-grow flex flex-col justify-center items-center">
                        <div
                            class="w-16 h-16 bg-blue-50 text-blue-600 rounded-[20px] flex items-center justify-center text-3xl mb-5 border border-blue-100">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h3 class="font-black text-gray-800 text-xl mb-2">Guru</h3>
                        <p class="text-xs text-gray-500 leading-relaxed mb-6 font-medium px-2">
                            Kelola data pelanggaran kelas dan rekap poin harian.
                        </p>
                    </div>
                    <a href="{{ route('guru.login') }}"
                        class="relative z-10 bg-blue-600 text-white font-bold text-xs tracking-widest uppercase py-3.5 px-6 rounded-full transition-all w-full hover:bg-blue-700 shadow-md">
                        LOGIN GURU
                    </a>
                </div>

                <!-- KARTU 3: ADMIN -->
                <div
                    class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-100 flex flex-col items-center justify-between transition-all duration-300 transform hover:-translate-y-2 relative overflow-hidden text-center hover:shadow-lg">
                    <div class="relative z-10 w-full flex-grow flex flex-col justify-center items-center">
                        <div
                            class="w-16 h-16 bg-slate-100 text-slate-700 rounded-[20px] flex items-center justify-center text-3xl mb-5 border border-gray-200">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h3 class="font-black text-gray-800 text-xl mb-2">Admin</h3>
                        <p class="text-xs text-gray-500 leading-relaxed mb-6 font-medium px-2">
                            Manajemen data master guru, siswa, dan sistem.
                        </p>
                    </div>
                    <a href="{{ route('admin.login') }}"
                        class="relative z-10 bg-[#1e293b] text-white font-bold text-xs tracking-widest uppercase py-3.5 px-6 rounded-full transition-all w-full hover:bg-black shadow-md">
                        LOGIN ADMIN
                    </a>
                </div>

                <!-- KARTU 4: ORANG TUA -->
                <div
                    class="bg-white p-8 rounded-[30px] shadow-sm border border-gray-100 flex flex-col items-center justify-between transition-all duration-300 transform hover:-translate-y-2 relative overflow-hidden text-center hover:shadow-lg">
                    <div class="relative z-10 w-full flex-grow flex flex-col justify-center items-center">
                        <div
                            class="w-16 h-16 bg-green-50 text-[#10b981] rounded-[20px] flex items-center justify-center text-3xl mb-5 border border-green-100">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h3 class="font-black text-gray-800 text-xl mb-2">Orang Tua</h3>
                        <p class="text-xs text-gray-500 leading-relaxed mb-4 font-medium px-2">
                            Pantau riwayat poin dan perilaku anak secara real-time.
                        </p>
                    </div>
                    <div class="w-full relative z-10 mt-auto">
                        <a href="{{ route('ortu.login') }}"
                            class="bg-[#10b981] text-white font-bold text-xs tracking-widest uppercase py-3.5 px-6 rounded-full transition-all w-full block hover:bg-green-600 shadow-md">
                            LOGIN ORANG TUA
                        </a>
                        <p class="text-[10px] text-gray-400 mt-4 font-medium">
                            Belum memiliki akun? <a href="{{ route('ortu.register') }}"
                                class="text-[#10b981] font-bold hover:underline decoration-2 underline-offset-2 transition ml-1">Daftar
                                di sini</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-100 pt-16 pb-8 mt-auto relative z-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 md:gap-8 mb-12 text-center md:text-left">
                <!-- Identitas, Visi & Misi -->
                <div class="flex flex-col items-center md:items-start">
                    <div class="flex items-center gap-2.5 mb-5">
                        <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                            alt="Logo" class="h-12 w-auto drop-shadow-sm transition hover:scale-105">
                        <div class="text-left">
                            <span
                                class="font-black text-gray-800 text-base md:text-lg uppercase tracking-tighter block leading-tight">MTsN
                                2 BJM</span>
                            <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Kementerian
                                Agama RI</span>
                        </div>
                    </div>

                    <div
                        class="text-[11px] text-gray-500 leading-relaxed font-medium text-left max-w-sm bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <p class="mb-2"><strong
                                class="text-kemenag-600 uppercase tracking-wider text-[10px]">Visi:</strong><br>
                            "Terwujudnya Madrasah yang Unggul dan Berbudaya Lingkungan Islami".</p>

                        <p class="mb-1 mt-3"><strong
                                class="text-blue-600 uppercase tracking-wider text-[10px]">Misi:</strong></p>
                        <ol class="list-decimal pl-4 space-y-1">
                            <li>Terciptanya lingkungan madrasah yang agamis, menyenangkan, kondusif, serta dapat
                                dipertanggung jawabkan.</li>
                            <li>Optimalisasi kegiatan akademik melalui pemberdayaan profesionalisme pendidik dan tenaga
                                kependidikan.</li>
                            <li>Berkembangnya minat dan bakat siswa diberbagai bidang sesuai minat dan kemampuan.</li>
                        </ol>
                    </div>
                </div>

                <!-- Kontak -->
                <div class="md:mx-auto flex flex-col items-center md:items-start">
                    <h4
                        class="font-black text-gray-800 mb-5 uppercase tracking-widest text-xs border-b-2 border-kemenag-500 pb-2 inline-block">
                        Hubungi Kami</h4>
                    <ul class="space-y-4 text-xs text-gray-500 font-medium">
                        <li class="flex items-start gap-3 text-left max-w-[250px]">
                            <i class="fas fa-map-marker-alt text-kemenag-500 mt-1 text-base w-4 text-center"></i>
                            <div>
                                <span class="block text-gray-800 font-bold mb-0.5"><i
                                        class="fas fa-building mr-1 opacity-50"></i> Kampus 1:</span>
                                <span class="block mb-3">Jl. Batu Benawa Raya No.32, Banjarmasin.</span>

                                <span class="block text-gray-800 font-bold mb-0.5"><i
                                        class="fas fa-building mr-1 opacity-50"></i> Kampus 2:</span>
                                <span class="block">Jl. Kaca Piring 7 Kel. Mawar, Banjarmasin.</span>
                            </div>
                        </li>
                        <li class="flex items-center gap-3 text-left">
                            <i class="fas fa-phone text-kemenag-500 text-base w-4 text-center"></i>
                            <span>(0511) 1234567</span>
                        </li>
                        <li class="flex items-center gap-3 text-left">
                            <i class="fas fa-envelope text-kemenag-500 text-base w-4 text-center"></i>
                            <span>mtsn2bjm@kemenag.go.id</span>
                        </li>
                    </ul>
                </div>

                <!-- Tautan Tambahan -->
                <div class="md:ml-auto flex flex-col items-center md:items-start">
                    <h4
                        class="font-black text-gray-800 mb-5 uppercase tracking-widest text-xs border-b-2 border-kemenag-500 pb-2 inline-block">
                        Tautan Cepat</h4>
                    <ul class="space-y-3 text-xs font-bold text-gray-500">
                        <!-- LINK WEBSITE RESMI DIUBAH KE APPMADRASAH -->
                        <li><a href="https://ppdb.mtsn2bjm.sch.id" target="_blank"
                                class="hover:text-kemenag-700 hover:translate-x-1 transition-all inline-block flex items-center gap-2"><i
                                    class="fas fa-globe text-[10px] opacity-50"></i> Website Resmi Sekolah</a></li>
                        <li><a href="{{ asset('dokumen/buku_tata_tertib.pdf') }}" target="_blank"
                                class="hover:text-kemenag-700 hover:translate-x-1 transition-all inline-block flex items-center gap-2"><i
                                    class="fas fa-book text-[10px] opacity-50"></i> Buku Tata Tertib (PDF)</a></li>
                        <li><a href="{{ route('ortu.register') }}"
                                class="text-kemenag-600 hover:text-kemenag-800 hover:translate-x-1 transition-all inline-block flex items-center gap-2"><i
                                    class="fas fa-user-plus text-[10px] opacity-50"></i> Daftar Akun Wali Murid</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 md:gap-0">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest text-center md:text-left">
                    &copy; 2026 MTsN 2 Kota Banjarmasin. All rights reserved.
                </p>

                <!-- IKON MEDIA SOSIAL -->
                <div class="flex items-center gap-3">
                    <a href="https://www.instagram.com/mtsn2_kotabanjarmasin?igsh=OHBwdHRoOGx6dDJu" target="_blank"
                        title="Instagram"
                        class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gradient-to-tr hover:from-[#fd5949] hover:to-[#d6249f] hover:text-white transition-all"><i
                            class="fab fa-instagram"></i></a>
                    <a href="{{ 'https://youtube.com/@mtsn2kotabanjarmasin?si=wiKhEYt1ohkJBCAO' }}" target="_blank"
                        title="YouTube"
                        class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-[#FF0000] hover:text-white transition-all"><i
                            class="fab fa-youtube"></i></a>
                    <a href="{{ 'https://www.tiktok.com/@mtsn2.banjarmasin?is_from_webapp=1&sender_device=pc' }}"
                        target="_blank" title="TikTok"
                        class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-black hover:text-white transition-all"><i
                            class="fab fa-tiktok"></i></a>
                </div>

                <p
                    class="text-[10px] text-gray-400 font-bold bg-gray-50 px-4 py-2 rounded-full border border-gray-100 mt-2 md:mt-0">
                    Developed by <span class="text-gray-700">Politeknik Negeri Banjarmasin</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Script Sederhana untuk Mobile Menu & Scroll -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle dengan Efek Slide & Fade
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            const links = document.querySelectorAll('.mobile-link');

            function toggleMenu() {
                const isOpen = !menu.classList.contains('opacity-0');

                if (isOpen) {
                    // Tutup
                    menu.classList.add('opacity-0', '-translate-y-full', 'pointer-events-none');
                    icon.classList.remove('fa-times', 'text-kemenag-500');
                    icon.classList.add('fa-bars');
                    btn.classList.remove('border-kemenag-200', 'bg-green-50');
                } else {
                    // Buka
                    menu.classList.remove('opacity-0', '-translate-y-full', 'pointer-events-none');
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times', 'text-kemenag-500');
                    btn.classList.add('border-kemenag-200', 'bg-green-50');
                }
            }

            btn.addEventListener('click', toggleMenu);

            // Tutup menu saat link diklik (Mobile)
            links.forEach(link => {
                link.addEventListener('click', () => {
                    if (!menu.classList.contains('opacity-0')) {
                        toggleMenu();
                    }
                });
            });

            // Smooth Scroll (Navigasi Anchor)
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    e.preventDefault();
                    const targetEl = document.querySelector(targetId);

                    if (targetEl) {
                        // Memperhitungkan tinggi navbar (80px)
                        const headerOffset = 80;
                        const elementPosition = targetEl.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: "smooth"
                        });
                    }
                });
            });

            // Efek Navbar saat Scroll (Glassmorphism pekat)
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 10) {
                    navbar.classList.add('shadow-md', 'bg-white/95');
                    navbar.classList.remove('bg-white/80');
                } else {
                    navbar.classList.remove('shadow-md', 'bg-white/95');
                    navbar.classList.add('bg-white/80');
                }
            });

            // LOGIKA GREETING TIME
            function updateGreeting() {
                const hours = new Date().getHours();
                const greetingElementDesktop = document.getElementById("greetingText");
                const greetingElementMobile = document.getElementById("mobileGreetingText");

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

                if (greetingElementDesktop) greetingElementDesktop.innerText = greeting;
                if (greetingElementMobile) greetingElementMobile.innerText = greeting;
            }

            // Jalankan saat load
            updateGreeting();
        });
    </script>
</body>

</html>
