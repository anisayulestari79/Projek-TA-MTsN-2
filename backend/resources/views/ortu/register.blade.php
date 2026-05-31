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
            margin: 0;
            padding: 0;
        }

        /* Custom Scrollbar for better look if it gets too long */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden">

    <!-- Elemen Background Dekoratif -->
    <div
        class="absolute top-0 left-0 w-full h-[40vh] bg-[#007a5c] rounded-b-[50px] sm:rounded-b-[100px] z-0 shadow-lg fixed">
    </div>
    <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-2xl z-0 fixed"></div>

    <!-- Container Form Register -->
    <div
        class="bg-white w-full max-w-lg rounded-[30px] shadow-2xl p-8 sm:p-10 relative z-10 border border-gray-100 my-10">

        <!-- Tombol Kembali -->
        <a href="{{ route('index') }}"
            class="absolute top-6 left-6 text-gray-400 hover:text-[#10b981] transition-colors flex items-center justify-center w-8 h-8 bg-gray-50 rounded-full">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- Header / Logos -->
        <div class="flex justify-center gap-3 mb-4 mt-4">
            <img src="https://ppdb.mtsn2bjm.sch.id/img/logo.png " alt="Logo Kemenag" class="h-10 w-auto object-contain">
            <img src="https://i.ibb.co.com/Psm2GxFN/5ef6453c-3a7b-47dc-a402-dacf0adb575d-removebg-preview-1.png"
                alt="Logo MTsN 2" class="h-10 w-auto object-contain">
        </div>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-black text-gray-800 tracking-tight leading-none mb-1">Daftar Akun Baru</h2>
            <p class="text-[11px] text-gray-400 font-medium">Khusus Orang Tua / Wali Murid</p>
        </div>

        <!-- Menampilkan Error Validasi -->
        @if ($errors->any())
            <div class="mb-5 p-3.5 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-bold">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Register -->
        <form action="{{ route('ortu.register.submit') }}" method="POST" class="space-y-6">
            @csrf

            <!-- BAGIAN 1: DATA ORANG TUA -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 border-b border-gray-100 pb-2 mb-2">
                    <i class="fas fa-user-shield text-[#10b981] text-sm"></i>
                    <h3 class="font-black text-gray-700 text-xs uppercase tracking-widest">Informasi Akun Wali</h3>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Nama
                        Lengkap Wali</label>
                    <div class="relative">
                        <i class="fas fa-id-card absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                        <input type="text" name="name" value="{{ old('name') }}"
                            placeholder="Contoh: Budi Santoso" required
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#10b981] focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label
                        class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Alamat
                        Email Aktif</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Untuk akses login"
                            required
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#10b981] focus:bg-white transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Kata
                            Sandi</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                            <input type="password" name="password" id="regPassword" placeholder="Min. 6 Karakter"
                                required minlength="6"
                                class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#10b981] focus:bg-white transition-all">
                            <button type="button" onclick="toggleRegPassword('regPassword', this)"
                                class="absolute right-4 top-3.5 text-gray-400 hover:text-[#10b981] focus:outline-none"><i
                                    class="fas fa-eye"></i></button>
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">Ulangi
                            Sandi</label>
                        <div class="relative">
                            <i class="fas fa-check-double absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                            <input type="password" name="password_confirmation" id="regPasswordConf"
                                placeholder="Ketik Ulang" required
                                class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#10b981] focus:bg-white transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 2: HUBUNGKAN DATA ANAK -->
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2 border-b border-gray-100 pb-2 mb-2">
                    <i class="fas fa-child text-[#10b981] text-sm"></i>
                    <h3 class="font-black text-gray-700 text-xs uppercase tracking-widest">Hubungkan Data Anak</h3>
                </div>
                <p class="text-[10px] text-gray-400 leading-relaxed mb-3">Masukkan NISN anak Anda untuk menghubungkan
                    akun pemantauan. Pastikan NISN yang dimasukkan valid dan terdaftar di MTsN 2.</p>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">NISN
                        Anak Pertama <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fas fa-id-badge absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                        <input type="text" name="nisn_anak_1" value="{{ old('nisn_anak_1') }}"
                            placeholder="Masukkan 10 digit NISN" required pattern="[0-9]{10}"
                            title="NISN harus berisi 10 digit angka"
                            class="w-full pl-10 pr-4 py-3 bg-emerald-50/30 border border-emerald-100 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#10b981] focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 ml-1">NISN
                        Anak Kedua <span class="text-gray-400 font-normal lowercase">(opsional jika
                            kembar/kakak-adik)</span></label>
                    <div class="relative">
                        <i class="fas fa-id-badge absolute left-4 top-3.5 text-gray-300 text-sm"></i>
                        <input type="text" name="nisn_anak_2" value="{{ old('nisn_anak_2') }}"
                            placeholder="Masukkan 10 digit NISN (Opsional)" pattern="[0-9]{10}"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#10b981] focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <!-- Tombol Submit -->
            <button type="submit"
                class="w-full bg-[#10b981] hover:bg-[#059669] text-white font-black text-xs tracking-widest uppercase py-4 rounded-xl shadow-lg shadow-green-200 transition-all transform hover:-translate-y-1 mt-8 flex justify-center items-center gap-2">
                Daftar & Hubungkan Anak <i class="fas fa-link"></i>
            </button>
        </form>

        <!-- Footer Link Login -->
        <div class="mt-6 text-center border-t border-gray-100 pt-6">
            <p class="text-[10px] text-gray-500 font-medium tracking-wide">
                Sudah punya akun? <a href="{{ route('ortu.login') }}"
                    class="text-[#10b981] font-black hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>

    <!-- SCRIPT UNTUK TOGGLE PASSWORD -->
    <script>
        function toggleRegPassword(inputId, btn) {
            const passInput = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
