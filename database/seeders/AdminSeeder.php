<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nis' => 'ADMIN001',
            'name' => 'Administrator',
            'email' => 'admin@sekolah.test',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'bio' => 'Akun administrator sistem absensi.',
        ]);

        $this->command->info('✅ Admin dibuat: NIS=ADMIN001, Password=admin123');
    }
}