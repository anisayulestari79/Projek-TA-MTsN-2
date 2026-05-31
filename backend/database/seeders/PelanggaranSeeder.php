<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggaran;

class PelanggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataTatib = [
            ['jenis' => 'Terlambat masuk madrasah kurang atau sama dengan 10 menit', 'sanksi' => 'Teguran lisan dan penindakan secara langsung oleh guru piket dan membaca Al Quran', 'skor_poin' => 1],
            ['jenis' => 'Terlambat lebih dari 10 menit', 'sanksi' => 'Teguran lisan, penindakan secara langsung, dan dicatat secara administrasi oleh guru piket dan membaca Al Quran', 'skor_poin' => 2],
            ['jenis' => 'Terlambat yang kedua kalinya', 'sanksi' => 'Peringatan tertulis dari guru bimbingan konseling dan diberi tugas yang mendidik dan membaca Al Quran', 'skor_poin' => 3],
            ['jenis' => 'Terlambat yang ketiga kalinya dan seterusnya', 'sanksi' => 'Pemanggilan orang tua oleh wali kelas', 'skor_poin' => 4],
            ['jenis' => 'Terlambat masuk karena izin keluar', 'sanksi' => 'Teguran lisan oleh guru yang sedang mengajar', 'skor_poin' => 1],
            ['jenis' => 'Terlambat masuk karena diberi tugas oleh guru atau madrasah', 'sanksi' => 'Teguran lisan oleh guru yang sedang mengajar', 'skor_poin' => 0],
            ['jenis' => 'Izin/tidak izin keluar saat PBM berlangsung dan tidak kembali (membolos)', 'sanksi' => 'Teguran lisan dan diberi tugas yang mendidik oleh guru piket', 'skor_poin' => 5],
            ['jenis' => 'Terlambat masuk setelah jam istirahat I dan II lebih dari 10 menit tanpa izin piket sebelumnya', 'sanksi' => 'Teguran lisan oleh guru yang sedang mengajar', 'skor_poin' => 1],
            ['jenis' => 'Terlambat masuk setelah jam istirahat I dan II lebih dari 10 menit tanpa izin piket sebelumnya untuk yang kedua kalinya', 'sanksi' => 'Peringatan tertulis dan diberi tugas yang mendidik yaitu menulis surah Al Insyiqaq', 'skor_poin' => 6],
            ['jenis' => 'Terlambat masuk setelah jam istirahat I dan II lebih dari 10 menit tanpa izin piket sebelumnya untuk yang ketiga kalinya dan seterusnya', 'sanksi' => 'Pemanggilan orang tua dan diberi tugas yang mendidik yaitu menulis surah Al Insyiqaq atau An Naziat', 'skor_poin' => 8],
            ['jenis' => 'Sakit tanpa disertai keterangan surat secara tertulis (lebih dari 3 hari harus ada surat keterangan dokter)', 'sanksi' => 'Teguran lisan oleh wali kelas dan guru yang sedang mengajar', 'skor_poin' => 2],
            ['jenis' => 'Tanpa keterangan (Alpa) yang pertama kali', 'sanksi' => 'Teguran lisan oleh wali kelas dan guru yang sedang mengajar', 'skor_poin' => 2],
            ['jenis' => 'Tanpa keterangan (Alpa) yang kedua kali', 'sanksi' => 'Teguran tertulis dan diberi tugas yang mendidik oleh wali kelas yaitu menulis surah An Naziat', 'skor_poin' => 5],
            ['jenis' => 'Tanpa keterangan (Alpa) yang ketiga kali', 'sanksi' => 'Pemanggilan orang tua dan diberi tugas yang mendidik oleh wali kelas yaitu menulis surah An Naziat', 'skor_poin' => 8],
            ['jenis' => 'Izin lebih dari 3 hari (jika lebih 3 hari disertai surat permohonan dari orang tua yang telah disetujui oleh sebagian guru yang ditinggalkan)', 'sanksi' => 'Pemanggilan orang tua dan diberi tugas yang mendidik oleh wali kelas yaitu menulis surah An Naziat', 'skor_poin' => 4],
            ['jenis' => 'Siswa tidak masuk dengan membuat surat izin palsu', 'sanksi' => 'Pemanggilan orang tua dan diberi tugas yang mendidik oleh wali kelas menulis surah An Naziat', 'skor_poin' => 8],
            ['jenis' => 'Siswa kedapatan membuat surat keterangan tidak hadir untuk kawannya', 'sanksi' => 'Pemanggilan orang tua dan diberi tugas yang mendidik oleh wali kelas yaitu menulis surah An Naziat', 'skor_poin' => 8],
            ['jenis' => 'Siswa memberikan keterangan tidak benar tentang alasan ketidakhadiran temannya', 'sanksi' => 'Pemanggilan orang tua dan diberi tugas yang mendidik yaitu menulis surah An Naziat', 'skor_poin' => 8],
            ['jenis' => 'Siswa putri memakai perhiasan yang berlebihan dan berupa emas (seperti kalung, gelang, cincin)', 'sanksi' => 'Teguran lisan oleh wali kelas dan guru yang sedang mengajar', 'skor_poin' => 2],
            ['jenis' => 'Siswa putri memakai perhiasan yang berlebihan dan berupa emas (seperti kalung, gelang, cincin) yang kedua kali', 'sanksi' => 'Penyitaan perhiasan dan pemanggilan orang tua oleh PTK yang menemukannya', 'skor_poin' => 5],
            ['jenis' => 'Siswa putri berhias/ menggunakan make up (lipstick, lipgloss, liptint, lipcream, blush on, eye liner, eye shadow, dsb)', 'sanksi' => 'Teguran lisan oleh wali kelas dan guru yang sedang mengajar', 'skor_poin' => 2],
            ['jenis' => 'Siswa putri berhias/ menggunakan dan make up (lipstick, lipgloss, liptint, lipcream, blush on, eye liner, eye shadow, dsb) yang kedua kali', 'sanksi' => 'Pemanggilan orang tua dan diberi tugas yang mendidik yaitu menulis surah An Naziat oleh wali kelas', 'skor_poin' => 5],
            ['jenis' => 'Siswa membawa peralatan make up (lipstick, lipgloss, liptint, lipcream, blush on, eye liner, eye shadow, dsb)', 'sanksi' => 'Penyitaan make up dan pemanggilan orang tua oleh wali kelas', 'skor_poin' => 4],
            ['jenis' => 'Siswa puteri tidak menggunakan jilbab di dalam atau di luar lingkungan madrsah', 'sanksi' => 'Teguran lisan dan diberi tugas yang mendidik yaitu menulis Istighfar 100x oleh wali kelas', 'skor_poin' => 3],
            ['jenis' => 'Siswa putra memakai gelang, kalung, anting-anting', 'sanksi' => 'Penyitaan barang dan pemanggilan orang tua oleh wali kelas', 'skor_poin' => 5],
            ['jenis' => 'Siswa putra rambut menutup telinga/ kerah baju/ di bawah alis', 'sanksi' => 'Diarahkan untuk memotong langsung oleh guru yang berwenang/ oleh wali kelas', 'skor_poin' => 2],
            ['jenis' => 'Siswa memelihara kuku Panjang', 'sanksi' => 'Diarahkan untuk memotong langsung oleh guru yang berwenang oleh wali kelas', 'skor_poin' => 1],
            ['jenis' => 'Siswa mencat kuku dengan kotek', 'sanksi' => 'Langsung diminta untuk membersihkan oleh guru yang menemukannya', 'skor_poin' => 1],
            ['jenis' => 'Siswa puteri memakai pakaian yang tidak sepantasnya (pendek, tipis, ketat, pinggang rok terlalu ke bawah, rok di belah, dll)', 'sanksi' => 'Teguran lisan dan dibimbing oleh oleh wali kelas dan guru yang sedang mengajar/ melihatnya.', 'skor_poin' => 3],
            ['jenis' => 'Siswa mencat rambut warna selain hitam', 'sanksi' => 'Teguran tertulis dan pemanggilan orang tua oleh wali kelas', 'skor_poin' => 6],
            ['jenis' => 'Tidak mengikuti shalat zuhur dan dhuha berjamaah tanpa uzur/ alasan', 'sanksi' => 'Diberi tugas yang mendidik yaitu menulis surah Yasin oleh wali kelas', 'skor_poin' => 4],
            ['jenis' => 'Tidak melaksanakan kebersihan sesuai jadwal kebersihan kelas', 'sanksi' => 'Penindakan oleh wali kelas dan guru yang mengajar', 'skor_poin' => 2],
            ['jenis' => 'Membuang sampah sembarangan', 'sanksi' => 'Langsung diminta membersihkan /mengambil Kembali dan meletakkan sebagaimana mestinya oleh guru yang menemukannya', 'skor_poin' => 1],
            ['jenis' => 'Membiarkan sampah berserakan dan tidak menghiraukannya', 'sanksi' => 'Ditunjukkan yang melihat', 'skor_poin' => 1],
            ['jenis' => 'Mengotori (mencoret - coret) benda milik sekolah, guru, karyawan, teman, atau lingkungan', 'sanksi' => 'Diminta membersihkan oleh wali kelas', 'skor_poin' => 2],
            ['jenis' => 'Merusak/ menghilangkan barang milik sekolah/ guru/ teman', 'sanksi' => 'Mengganti rugi oleh wali kelas', 'skor_poin' => 6],
            ['jenis' => 'Membawa benda yang tidak ada kaitannya dengan pelajaran tanpa seijin pihak madrsah', 'sanksi' => 'Disita dan pemanggilan orang tua oleh wali kelas', 'skor_poin' => 5],
            ['jenis' => 'Membuat keributan di kelas atau pada saat jam belajar', 'sanksi' => 'Teguran lisan oleh wali kelas dan guru yang sedang mengajar', 'skor_poin' => 3],
            ['jenis' => 'Sering keluar masuk pada saat jam belajar berlangsung', 'sanksi' => 'Teguran lisan dan pembinaan oleh guru BK', 'skor_poin' => 3],
            ['jenis' => 'Berlaku tidak sopan/ menyinggung perasaan guru', 'sanksi' => 'Teguran lisan oleh wali kelas dan guru yang sedang mengajar', 'skor_poin' => 4],
            ['jenis' => 'Keluar kelas pada saat belajar/ pergantian jam pelajaran tanpa seizin guru di kelas', 'sanksi' => 'Teguran lisan dan dan diberi tugas yang mendidik yaitu menulis surah Al Mulk oleh guru yang ditinggalkan', 'skor_poin' => 4],
            ['jenis' => 'Memparkir sepeda di luar tempat parkir yang sudah ditentukan madrasah', 'sanksi' => 'Penindakan oleh guru piket', 'skor_poin' => 1],
            ['jenis' => 'Tidak mengikuti salah satu kegiatan ekstrakurikuler sekolah (Pramuka, PMR, Olahraga, Kesenian yang telah ditentukan/ diumumkan madrasah)', 'sanksi' => 'Dibina oleh pembimbing/Pembina/pelatih dan wali kelas', 'skor_poin' => 2],
            ['jenis' => 'Tidak tertib mengikuti kegiatan madrasah (Upacara hari senin, dan upacara lainnya)', 'sanksi' => 'Teguran lisan dan diberi tugas yang mendidik yaitu menulis surah Al Waqiah', 'skor_poin' => 3],
            ['jenis' => 'Siswa memasuki ruang Kepala Madrasah/Guru/TU/ Laboratorium/ Perpustakaan tanpa izin', 'sanksi' => 'Teguran lisan oleh guru piket dan PTK yang melihatnya', 'skor_poin' => 2],
            ['jenis' => 'Siswa tidur dikelas saat jam belajar', 'sanksi' => 'Teguran lisan dan diberi tugas yang mendidik yaitu menulis surah Ad Dhuha oleh guru yang mengajar', 'skor_poin' => 2],
            ['jenis' => 'Siswa jajan di luar kantin madrasah saat jam pelajaran', 'sanksi' => 'Teguran lisan oleh wali kelas dan guru piket', 'skor_poin' => 2],
            ['jenis' => 'Siswa pura-pura sakit di ruang UKS', 'sanksi' => 'Teguran lisan dan diberi tugas yang mendidik yaitu menulis surah Al A\'la oleh wali kelas', 'skor_poin' => 3],
            ['jenis' => 'Siswa menaiki sepeda/ sepeda motor di halaman madrasah', 'sanksi' => 'Teguran lisan oleh guru piket dan guru yang melihatnya', 'skor_poin' => 2],
            ['jenis' => 'Siswa main sepak bola dan lain-lain pada waktu istirahat atau bukan jam istirahat kecuali jam pelajaran olahraga', 'sanksi' => 'Teguran lisan dan penyitaan bola atau barang mainan lainnya oleh guru piket dan wali kelas', 'skor_poin' => 3],
            ['jenis' => 'Membawa rokok ke madrasah', 'sanksi' => 'Disita, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah Yasin oleh wali kelas', 'skor_poin' => 8],
            ['jenis' => 'Menghisap rokok di lingkungan madrasah', 'sanksi' => 'Disita, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah Yasin oleh wali kelas', 'skor_poin' => 10],
            ['jenis' => 'Merokok berpakaian seragam atau baju bebas di luar lingkungan madrasah', 'sanksi' => 'Dibimbing, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah Yasin oleh wali kelas', 'skor_poin' => 20],
            ['jenis' => 'Membawa komik/ novel/ majalah/ buku yang tidak berhubungan dengan pelajaran', 'sanksi' => 'Disita, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah An Naziaat oleh wali kelas', 'skor_poin' => 4],
            ['jenis' => 'Membawa HP di lingkungan madrasah atau menggunakan HP tanpa izin tertulis oleh guru', 'sanksi' => 'Disita, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah An Naziaat oleh wali kelas', 'skor_poin' => 6],
            ['jenis' => 'Menggunakan Headset di madrasah tanpa seizin guru yang mengajar', 'sanksi' => 'Disita, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah An Naziaat oleh guru yang sedang mengajar', 'skor_poin' => 5],
            ['jenis' => 'Mengupload atau memposting konten tidak sopan di media sosial', 'sanksi' => 'Dibimbing, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah An Naziaat oleh wali kelas', 'skor_poin' => 8],
            ['jenis' => 'Membawa kartu uno, domino, remi, rubrik, dan lain-lain ke madrasah', 'sanksi' => 'Disita, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah An Naziaat oleh guru piket dan/atau wali kelas', 'skor_poin' => 4],
            ['jenis' => 'Bermain kartu di madrasah saat jam belajar atau istirahat', 'sanksi' => 'Disita, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah An Naziaat oleh wali kelas', 'skor_poin' => 5],
            ['jenis' => 'Menggunakan kendaraan di lingkungan madrasah', 'sanksi' => 'Teguran lisan dan pemanggilan orang tua oleh wali kelas', 'skor_poin' => 4],
            ['jenis' => 'Melakukan pemalakan', 'sanksi' => 'Pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah An Naba oleh wali kelas', 'skor_poin' => 10],
            ['jenis' => 'Membawa senjata tajam di madrasah', 'sanksi' => 'Disita, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah An Naba oleh wali kelas', 'skor_poin' => 12],
            ['jenis' => 'Menggunakan senjata tajam untuk mengancam/ membajak/ berkelahi', 'sanksi' => 'Disita, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah An Naba oleh wali kelas', 'skor_poin' => 25],
            ['jenis' => 'Mengambil barang orang lain tanpa izin ( barang berharga)', 'sanksi' => 'Pemanggilan orang tua, mengembalikan barang yang diambil, dan diberi tugas yang mendidik yaitu menulis surah An Naba oleh wali kelas', 'skor_poin' => 20],
            ['jenis' => 'Membawa obat/minuman terlarang ke madrasah dan lingkungannya', 'sanksi' => 'Disita, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah Al Mulk oleh guru BK', 'skor_poin' => 25],
            ['jenis' => 'Menggunakan obat/minuman terlarang di madrasah dan lingkungannya', 'sanksi' => 'Disita, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah Al Mulk oleh guru BK dan/atau wali kelas', 'skor_poin' => 25],
            ['jenis' => 'Berjudi di dalam kelas/madrasah', 'sanksi' => 'Penyitaan barang bukti, pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah Yasin oleh yang menemukannya atau wali kelas', 'skor_poin' => 25],
            ['jenis' => 'Berjudi di luar lingkungan madrasah', 'sanksi' => 'Pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah Yasin oleh wali kelas', 'skor_poin' => 12],
            ['jenis' => 'Siswa berbohong kepada guru atau teman ', 'sanksi' => 'Pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah Yasin oleh guru BK dan/atau wali kelas', 'skor_poin' => 5],
            ['jenis' => 'Perkelahian dengan sesama kawan (pertama) tidak menggunakan senjata tajam', 'sanksi' => 'Pemanggilan orang tua, dan diberi tugas yang mendidik yaitu menulis surah Yasin oleh wali kelas dan/atau guru BK', 'skor_poin' => 25],
            ['jenis' => 'Perkelahian kedua', 'sanksi' => 'Pemanggilan orang tua, teguran tertulis, dan diberi tugas yang mendidik yaitu menulis surah Yasin', 'skor_poin' => 15],
            ['jenis' => 'Perkelahian ketiga', 'sanksi' => 'Pemanggilan orang tua, diberi tugas mendidik, dan diberhentikan dari madrasah', 'skor_poin' => 100],
            ['jenis' => 'Berkelahi dengan siswa madrasah lain (pertama)', 'sanksi' => 'Pemanggilan orang tua dan diberi tugas yang mendidik yaitu menulis surah Al Waqiah', 'skor_poin' => 25],
            ['jenis' => 'Berkelahi dengan siswa madrasah lain (kedua)', 'sanksi' => 'Pemanggilan orang tua, peringatan tertulis, dan diberi tugas yang mendidik yaitu menulis surah Al Waqiah', 'skor_poin' => 50],
            ['jenis' => 'Berkelahi dengan siswa madrasah lain (ketiga)', 'sanksi' => 'Pemanggilan orang tua, diberi tugas mendidik, dan diberhentikan dari madrasah', 'skor_poin' => 100],
            ['jenis' => 'Perkelahian menggunakan senjata tajam (pertama)', 'sanksi' => 'Penyitaan, pemanggilan orang tua, peringatan tertulis, dan tugas menulis surah Al Waqiah', 'skor_poin' => 50],
            ['jenis' => 'Perkelahian dengan senjata tajam (kedua)', 'sanksi' => 'Penyitaan, pemanggilan orang tua, tugas mendidik, dan diberhentikan dari madrasah', 'skor_poin' => 100],
            ['jenis' => 'Siswa/i berduaan bergandengan tangan dengan lawan jenis', 'sanksi' => 'Teguran tertulis dan diberi tugas yang mendidik yaitu menulis surah At Takwir', 'skor_poin' => 8],
            ['jenis' => 'Siswa/i berpelukan/berciuman/berzina', 'sanksi' => 'Pemanggilan orang tua dan diberi tugas yang mendidik yaitu menulis surah At Takwir dan sholat taubat', 'skor_poin' => 100],
            ['jenis' => 'Siswa/i menikah', 'sanksi' => 'Pemanggilan orang tua, diberi tugas mendidik, dan diberhentikan dari madrasah', 'skor_poin' => 100],
            ['jenis' => 'Siswi hamil', 'sanksi' => 'Pemanggilan orang tua, dan diberhentikan dari madrasah', 'skor_poin' => 100],
            ['jenis' => 'Memakai seragam tidak rapi', 'sanksi' => 'Teguran lisan dan langsung diminta merapikan', 'skor_poin' => 1],
            ['jenis' => 'Salah memakai seragam sesuai jadwal', 'sanksi' => 'Teguran lisan dan tugas menulis surah Al Infitar', 'skor_poin' => 2],
            ['jenis' => 'Seragam tidak dilengkapi atribut', 'sanksi' => 'Teguran lisan dan tugas menulis surah Al Infitar', 'skor_poin' => 2],
            ['jenis' => 'Tidak memakai ikat pinggang dan peci', 'sanksi' => 'Teguran lisan dan tugas menulis surah Al Infitar', 'skor_poin' => 2],
            ['jenis' => 'Tidak memakai sepatu hitam polos', 'sanksi' => 'Penyitaan sepatu dan tugas menulis surah Al Muthaffifin', 'skor_poin' => 3],
            ['jenis' => 'Tidak memakai kaos kaki putih (Senin–Jumat)', 'sanksi' => 'Penyitaan kaos kaki dan tugas menulis surah Al Muthaffifin', 'skor_poin' => 2],
            ['jenis' => 'Tidak memakai kaos kaki hitam (kamis)', 'sanksi' => 'Penyitaan kaos kaki dan tugas menulis surah Al Muthaffifin', 'skor_poin' => 2],
            ['jenis' => 'Tidak memakai seragam madrasah pada hari yang ditentukan', 'sanksi' => 'Teguran lisan dan diberi tugas yang mendidik yaitu menulis surah Al Muthaffifin oleh guru piket', 'skor_poin' => 3],
            ['jenis' => 'Tidak melaporkan kegiatan yang mengatasnamakan madrasah', 'sanksi' => 'Dipanggil orang tuanya dan diberi peringatan', 'skor_poin' => 4],
        ];

        foreach ($dataTatib as $data) {
            Pelanggaran::create($data);
        }
    }
}

