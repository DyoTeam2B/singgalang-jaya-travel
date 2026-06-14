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
        $armadas = \App\Models\Armada::all();

        // 1. Hubungkan user driver default dari UserSeeder jika ada
        $defaultDriverUser = User::where('email', 'driver@gmail.com')->first();
        if ($defaultDriverUser && $armadas->count() > 0) {
            Driver::create([
                'user_id' => $defaultDriverUser->id,
                'armada_id' => $armadas[0]->id,
                'nama_driver' => 'Driver Singgalang',
                'no_hp' => '081234567890',
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
        if ($armadas->count() > 1) {
            Driver::create([
                'user_id' => $userHendra->id,
                'armada_id' => $armadas[1]->id,
                'nama_driver' => 'Hendra Gunawan',
                'no_hp' => '081234567891',
                'status_driver' => 'aktif',
            ]);
        }

        // 3. Tambah sample driver: Budi Santoso
        $userBudi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@singgalang.com',
            'password' => Hash::make('driver12345'),
            'role' => 'driver',
        ]);
        if ($armadas->count() > 2) {
            Driver::create([
                'user_id' => $userBudi->id,
                'armada_id' => $armadas[2]->id,
                'nama_driver' => 'Budi Santoso',
                'no_hp' => '081356789012',
                'status_driver' => 'aktif',
            ]);
        }
    }
}
