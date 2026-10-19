<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PortalDataSeeder::class,   // 10 guru + 360 siswa
        ]);
    }
}