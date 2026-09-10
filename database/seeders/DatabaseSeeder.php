<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::create([
            'username' => 'admin',
            'email' => 'admin@perpus.local',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        User::create([
            'name' => 'Peminjam Buku',
            'email' => 'user@gmail.com',
            'phone' => '089876543210',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
    }
}
