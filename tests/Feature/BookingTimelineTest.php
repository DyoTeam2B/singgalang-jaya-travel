<?php

namespace Tests\Feature;

use App\Models\Armada;
use App\Models\Booking;
use App\Models\DetailTrip;
use App\Models\Driver;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Models\Rute;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTimelineTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigned_booking_detail_shows_timeline_driver_and_whatsapp_link(): void
    {
        $customerUser = User::factory()->create(['role' => 'pelanggan']);
        $pelanggan = Pelanggan::create([
            'user_id' => $customerUser->id,
            'nama' => 'Pelanggan Test',
            'no_hp' => '081111111111',
        ]);

        $rute = Rute::create([
            'asal' => 'Padang Panjang',
            'tujuan' => 'Pekanbaru',
            'tarif' => 150000,
        ]);

        $jadwal = Jadwal::create([
            'rute_id' => $rute->id,
            'tanggal_keberangkatan' => '2026-06-17',
            'shift' => 'pagi',
            'jam_berangkat' => '08:00',
            'kuota' => 20,
            'status_jadwal' => 'aktif',
        ]);

        $armada = Armada::create([
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 1234 XY',
            'kapasitas' => 5,
            'status_armada' => 'aktif',
        ]);

        $driverUser = User::factory()->create(['role' => 'driver']);
        $driver = Driver::create([
            'user_id' => $driverUser->id,
            'armada_id' => $armada->id,
            'nama_driver' => 'Joni Driver',
            'no_hp' => '081234567890',
            'status_driver' => 'aktif',
        ]);

        $booking = Booking::create([
            'pelanggan_id' => $pelanggan->id,
            'jadwal_id' => $jadwal->id,
            'kode_booking' => 'SJT-TEST-001',
            'alamat_jemput' => 'Alamat Jemput',
            'alamat_tujuan' => 'Alamat Tujuan',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_ASSIGNED_TO_TRIP,
        ]);

        $trip = Trip::create([
            'jadwal_id' => $jadwal->id,
            'driver_id' => $driver->id,
            'armada_id' => $armada->id,
            'status_trip' => Trip::STATUS_READY,
        ]);

        DetailTrip::create([
            'trip_id' => $trip->id,
            'booking_id' => $booking->id,
            'status_jemput' => 'belum',
            'status_antar' => 'belum',
        ]);

        $response = $this->actingAs($customerUser)
            ->get(route('booking.show', ['kode' => $booking->kode_booking]));

        $response
            ->assertOk()
            ->assertSee('Timeline Status')
            ->assertSee('Masuk Trip')
            ->assertSee('Joni Driver')
            ->assertSee('Toyota Avanza')
            ->assertSee('17 Jun 2026')
            ->assertSee('Shift Pagi - 08:00 WIB')
            ->assertSee('https://wa.me/6281234567890');
    }
}