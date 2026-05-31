<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Guru::create([
            'nuptk' => '2633762663200022',
            'nip' => '12345678',
            'nama' => 'Abdul Aziz, S.Pd',
            'jk' => 'Laki-laki',
            'pendidikan' => 'Sarjana (S1)',
            'tempat_lahir' => 'Banjarmasin',
            'tanggal_lahir' => '1985-04-01',
            'password' => 'mtsn02',
            'wali_kelas' => 'VII.A',
        ]);

        Guru::create([
            'nuptk' => '9135750653200003',
            'nip' => '',
            'nama' => 'Abdul Halim, S.Kom',
            'jk' => 'Laki-laki',
            'pendidikan' => 'Sarjana (S1)',
            'tempat_lahir' => 'Banjarmasin',
            'tanggal_lahir' => '1988-05-15',
            'password' => 'mtsn02',
            'wali_kelas' => 'VII.C',
        ]);

        Guru::create([
            'nuptk' => '4433759660200062',
            'nip' => '',
            'nama' => 'Abdul Rahim, S.Pd',
            'jk' => 'Laki-laki',
            'pendidikan' => 'Sarjana (S1)',
            'tempat_lahir' => 'Banjarmasin',
            'tanggal_lahir' => '1990-06-20',
            'password' => 'mtsn02',
            'wali_kelas' => 'VIII.B',
        ]);
    }
}
