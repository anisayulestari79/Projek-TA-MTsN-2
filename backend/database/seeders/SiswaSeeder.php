<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Siswa;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswaData = [
            ['nisn' => '001', 'nama' => 'Rafi Alif', 'kelas' => 'VII.A', 'kontak' => '081234567890', 'poin' => 0],
            ['nisn' => '002', 'nama' => 'Nabila Ayu', 'kelas' => 'VII.B', 'kontak' => '081324354657', 'poin' => 0],
            ['nisn' => '003', 'nama' => 'Dimas F.', 'kelas' => 'VIII.A', 'kontak' => '085798765432', 'poin' => 0],
            ['nisn' => '004', 'nama' => 'Siti Aisyah', 'kelas' => 'VIII.B', 'kontak' => '081122334455', 'poin' => 0],
            ['nisn' => '005', 'nama' => 'Andi Pratama', 'kelas' => 'IX.A', 'kontak' => '087855667788', 'poin' => 0],
            ['nisn' => '006', 'nama' => 'Dini Lestari', 'kelas' => 'IX.B', 'kontak' => '089988776655', 'poin' => 0],
            ['nisn' => '007', 'nama' => 'Budi Santoso', 'kelas' => 'VII.C', 'kontak' => '081211223344', 'poin' => 0],
            ['nisn' => '008', 'nama' => 'Citra Amelia', 'kelas' => 'VIII.C', 'kontak' => '081333445566', 'poin' => 0],
            ['nisn' => '009', 'nama' => 'Fajar Kurniawan', 'kelas' => 'IX.C', 'kontak' => '085777889900', 'poin' => 0],
            ['nisn' => '010', 'nama' => 'Gita Rahmawati', 'kelas' => 'VII.D', 'kontak' => '087899887766', 'poin' => 0],
            ['nisn' => '011', 'nama' => 'Heri Gunawan', 'kelas' => 'VIII.D', 'kontak' => '081133445566', 'poin' => 0],
            ['nisn' => '012', 'nama' => 'Indah Permata', 'kelas' => 'IX.D', 'kontak' => '089911223344', 'poin' => 0],
        ];

        foreach ($siswaData as $data) {
            Siswa::create([
                'nisn' => $data['nisn'],
                'nama' => $data['nama'],
                'kelas' => $data['kelas'],
                'kontak_ortu' => $data['kontak'],
                'poin' => $data['poin'],
            ]);
        }
    }
}
