<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Admin Users
        User::create([
            'name' => 'Kepala Tata Usaha',
            'email' => 'admin@mtsn2bjm.sch.id',
            'username' => 'adminmt2',
            'nip' => '987654321',
            'role' => 'admin',
            'gender' => 'Laki-laki',
            'phone' => '08121212121',
            'password' => Hash::make('adminpass'),
        ]);

        User::create([
            'name' => 'Operator Sekolah',
            'email' => 'operator@mtsn2bjm.sch.id',
            'username' => 'operator',
            'role' => 'admin',
            'password' => Hash::make('op123'),
        ]);

        // Seed Guru Users
        User::create([
            'name' => 'Ibu Annisa',
            'email' => 'annisa@mtsn2bjm.sch.id',
            'nip' => '1234',
            'role' => 'guru',
            'gender' => 'Perempuan',
            'phone' => '08111222333',
            'password' => Hash::make('guru123'),
        ]);

        User::create([
            'name' => 'Bapak Rahmat',
            'email' => 'rahmat@mtsn2bjm.sch.id',
            'nip' => '5678',
            'role' => 'guru',
            'gender' => 'Laki-laki',
            'phone' => '08555666777',
            'password' => Hash::make('guru456'),
        ]);
    }
}
