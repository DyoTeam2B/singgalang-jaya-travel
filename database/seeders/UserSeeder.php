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
    }
}
