<?php

namespace Database\Seeders;

use App\Models\Armada;
use Illuminate\Database\Seeder;

class ArmadaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Armada::create([
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 1234 XY',
            'kapasitas' => 5,
            'status_armada' => 'aktif',
        ]);

        Armada::create([
            'nama_mobil' => 'Suzuki Ertiga',
            'nomor_plat' => 'BA 5678 ZZ',
            'kapasitas' => 5,
            'status_armada' => 'aktif',
        ]);

        Armada::create([
            'nama_mobil' => 'Toyota Innova',
            'nomor_plat' => 'BA 9999 AA',
            'kapasitas' => 7,
            'status_armada' => 'aktif',
        ]);

        Armada::create([
            'nama_mobil' => 'Daihatsu Xenia',
            'nomor_plat' => 'BA 4321 YX',
            'kapasitas' => 5,
            'status_armada' => 'aktif',
        ]);
    }
}
