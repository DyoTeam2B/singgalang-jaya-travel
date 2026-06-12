<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Singgalang',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Driver Singgalang',
            'email' => 'driver@gmail.com',
            'password' => Hash::make('driver12345'),
            'role' => 'driver',
        ]);
        $pelangganUser = User::create([
            'name' => 'Pelanggan Singgalang',
            'email' => 'pelanggan@gmail.com',
            'password' => Hash::make('pelanggan12345'),
            'role' => 'pelanggan',
        ]);
        \App\Models\Pelanggan::create([
            'user_id' => $pelangganUser->id,
            'nama' => 'Pelanggan Singgalang',
            'no_hp' => '081234567890',
        ]);
    }
}
