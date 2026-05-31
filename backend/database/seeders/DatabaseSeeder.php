<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call all seeders
        $this->call([
            UserSeeder::class,
            GuruSeeder::class,
            SiswaSeeder::class,
            PelanggaranSeeder::class,
        ]);
    }
}
