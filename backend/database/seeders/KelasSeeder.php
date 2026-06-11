<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            ['tingkat' => 'VII', 'nama_kelas' => 'VII A'],
            ['tingkat' => 'VII', 'nama_kelas' => 'VII B'],
            ['tingkat' => 'VII', 'nama_kelas' => 'VII C'],
            ['tingkat' => 'VII', 'nama_kelas' => 'VII D'],
            ['tingkat' => 'VII', 'nama_kelas' => 'VII E'],
            ['tingkat' => 'VII', 'nama_kelas' => 'VII F'],
            ['tingkat' => 'VII', 'nama_kelas' => 'VII G'],
            ['tingkat' => 'VII', 'nama_kelas' => 'VII H'],
            ['tingkat' => 'VII', 'nama_kelas' => 'VII I'],
            ['tingkat' => 'VII', 'nama_kelas' => 'VII J'],
            ['tingkat' => 'VII', 'nama_kelas' => 'VII K'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'VIII A'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'VIII B'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'VIII C'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'VIII D'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'VIII E'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'VIII F'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'VIII G'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'VIII H'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'VIII I'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'VIII J'],
            ['tingkat' => 'VIII', 'nama_kelas' => 'VIII K'],
            ['tingkat' => 'IX', 'nama_kelas' => 'IX A'],
            ['tingkat' => 'IX', 'nama_kelas' => 'IX B'],
            ['tingkat' => 'IX', 'nama_kelas' => 'IX C'],
            ['tingkat' => 'IX', 'nama_kelas' => 'IX D'],
            ['tingkat' => 'IX', 'nama_kelas' => 'IX E'],
            ['tingkat' => 'IX', 'nama_kelas' => 'IX F'],
            ['tingkat' => 'IX', 'nama_kelas' => 'IX G'],
            ['tingkat' => 'IX', 'nama_kelas' => 'IX H'],
            ['tingkat' => 'IX', 'nama_kelas' => 'IX I'],
            ['tingkat' => 'IX', 'nama_kelas' => 'IX J'],
            ['tingkat' => 'IX', 'nama_kelas' => 'IX K'],
        ];

        foreach ($kelas as $k) {
            Kelas::create($k);
        }
    }
}
