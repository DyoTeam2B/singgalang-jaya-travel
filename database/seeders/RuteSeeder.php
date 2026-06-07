<?php

namespace Database\Seeders;

use App\Models\Rute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rute::create([
            'asal' => 'Padang Panjang',
            'tujuan' => 'Pekanbaru',
            'tarif' => 150000,
        ]);

        Rute::create([
            'asal' => 'Pekanbaru',
            'tujuan' => 'Padang Panjang',
            'tarif' => 150000,
        ]);
    }
}
