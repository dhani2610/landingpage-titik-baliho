<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat 1 akun admin khusus
        User::create([
            'name' => 'Admin Petani Besi',
            'email' => 'admin@petanibesi.com',
            'password' => Hash::make('admin123'), // Password login
        ]);
    }
}
