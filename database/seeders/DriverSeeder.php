<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Driver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Hubungkan user driver default dari UserSeeder jika ada
        $defaultDriverUser = User::where('email', 'driver@gmail.com')->first();
        if ($defaultDriverUser) {
            Driver::create([
                'user_id' => $defaultDriverUser->id,
                'nama_driver' => 'Driver Singgalang',
                'no_hp' => '081234567890',
                'nama_mobil' => 'Toyota Avanza',
                'nomor_plat' => 'BA 1234 XY',
                'kapasitas_mobil' => 5,
                'status_driver' => 'aktif',
            ]);
        }

        // 2. Tambah sample driver: Hendra Gunawan
        $userHendra = User::create([
            'name' => 'Hendra Gunawan',
            'email' => 'hendra@singgalang.com',
            'password' => Hash::make('driver12345'),
            'role' => 'driver',
        ]);
        Driver::create([
            'user_id' => $userHendra->id,
            'nama_driver' => 'Hendra Gunawan',
            'no_hp' => '081234567891',
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 5678 ZZ',
            'kapasitas_mobil' => 5,
            'status_driver' => 'aktif',
        ]);

        // 3. Tambah sample driver: Budi Santoso
        $userBudi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@singgalang.com',
            'password' => Hash::make('driver12345'),
            'role' => 'driver',
        ]);
        Driver::create([
            'user_id' => $userBudi->id,
            'nama_driver' => 'Budi Santoso',
            'no_hp' => '081356789012',
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 9999 AA',
            'kapasitas_mobil' => 5,
            'status_driver' => 'aktif',
        ]);
    }
}
