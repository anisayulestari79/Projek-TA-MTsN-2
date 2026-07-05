<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KamadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mengecek agar tidak terjadi duplikasi jika seeder dijalankan 2x
        $existingKamad = User::where('role', 'kamad')->first();

        if (!$existingKamad) {
            User::create([
                'name'     => 'Drs. Riduansyah, M.Pd.',
                'email'    => 'kamad@mtsn2bjm.sch.id',
                'username' => 'kamad_mtsn2',
                'nip'      => '196702031994031008',
                'role'     => 'kamad',
                'gender'   => 'Laki-laki',
                'phone'    => '081234567890',
                'password' => Hash::make('kamad123'),
            ]);

            $this->command->info('Akun Kepala Madrasah berhasil dibuat!');
        } else {
            $this->command->info('Akun Kepala Madrasah sudah ada di database.');
        }
    }
}
