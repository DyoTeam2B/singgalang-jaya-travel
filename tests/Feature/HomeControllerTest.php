<?php

namespace Tests\Feature;

use App\Models\Armada;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Models\Rating;
use App\Models\Rute;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Rute $rute;
    protected Jadwal $jadwal;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup basic rute and jadwal
        $this->rute = Rute::create([
            'asal' => 'Padang Panjang',
            'tujuan' => 'Pekanbaru',
            'tarif' => 150000,
        ]);

        $this->jadwal = Jadwal::create([
            'rute_id' => $this->rute->id,
            'tanggal_keberangkatan' => now()->toDateString(),
            'shift' => 'pagi',
            'jam_berangkat' => '08:00',
            'kuota' => 10,
            'status_jadwal' => 'aktif',
        ]);
    }

    /**
     * TC-01: DB kosong (belum ada ulasan/trip) -> Rating default 4.9, on-time 99%
     */
    public function test_home_page_shows_default_stats_when_db_is_empty(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('averageRating', 4.9);
        $response->assertViewHas('onTimePercentage', 99);
    }

    /**
     * TC-02: Ada ulasan dan trip tepat waktu -> Statistik dihitung aktual
     */
    public function test_home_page_shows_actual_stats_when_trips_and_ratings_exist(): void
    {
        // 1. Create a customer
        $user = User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi@test.com',
            'password' => bcrypt('password123'),
            'role' => 'pelanggan',
        ]);
        $pelanggan = Pelanggan::create([
            'user_id' => $user->id,
            'nama' => 'Budi Pelanggan',
            'no_hp' => '081299998888',
        ]);

        // 2. Create bookings
        $booking1 = Booking::create([
            'pelanggan_id' => $pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-B1',
            'alamat_jemput' => 'A',
            'alamat_tujuan' => 'B',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_COMPLETED,
        ]);
        $booking2 = Booking::create([
            'pelanggan_id' => $pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-B2',
            'alamat_jemput' => 'A',
            'alamat_tujuan' => 'B',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_COMPLETED,
        ]);

        // 3. Create rating (published)
        Rating::create([
            'booking_id' => $booking1->id,
            'pelanggan_id' => $pelanggan->id,
            'rating' => 5,
            'ulasan' => 'Sangat bagus!',
            'status' => 'published',
        ]);
        Rating::create([
            'booking_id' => $booking2->id,
            'pelanggan_id' => $pelanggan->id,
            'rating' => 4,
            'ulasan' => 'Bagus!',
            'status' => 'published',
        ]);

        // 4. Create completed trip that is on time (started within 3 hours of scheduled time)
        $armada = Armada::create([
            'nama_mobil' => 'Avanza',
            'nomor_plat' => 'BA 1111 AA',
            'kapasitas' => 5,
            'status_armada' => 'aktif',
        ]);
        $driverUser = User::create([
            'name' => 'Driver Budi',
            'email' => 'driver@test.com',
            'password' => bcrypt('password123'),
            'role' => 'driver',
        ]);
        $driver = Driver::create([
            'user_id' => $driverUser->id,
            'nama_driver' => 'Driver Budi',
            'no_hp' => '081299990000',
            'armada_id' => $armada->id,
            'status_driver' => 'aktif',
        ]);

        $trip = Trip::create([
            'jadwal_id' => $this->jadwal->id,
            'driver_id' => $driver->id,
            'armada_id' => $armada->id,
            'status_trip' => Trip::STATUS_COMPLETED,
            'started_at' => now()->startOfDay()->addHours(9), // scheduled 08:00, started 09:00 (< 3 hours late)
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('averageRating', 4.5);
        $response->assertViewHas('onTimePercentage', 100);
    }

    /**
     * TC-03: Trip completed terlambat lebih dari 3 jam -> Tidak dihitung sebagai on-time
     */
    public function test_home_page_excludes_delayed_trips_from_on_time_percentage(): void
    {
        $armada = Armada::create([
            'nama_mobil' => 'Avanza',
            'nomor_plat' => 'BA 1111 AA',
            'kapasitas' => 5,
            'status_armada' => 'aktif',
        ]);
        $driverUser = User::create([
            'name' => 'Driver Budi',
            'email' => 'driver@test.com',
            'password' => bcrypt('password123'),
            'role' => 'driver',
        ]);
        $driver = Driver::create([
            'user_id' => $driverUser->id,
            'nama_driver' => 'Driver Budi',
            'no_hp' => '081299990000',
            'armada_id' => $armada->id,
            'status_driver' => 'aktif',
        ]);

        // Scheduled 08:00, started 12:00 (4 hours late -> not on time)
        $trip = Trip::create([
            'jadwal_id' => $this->jadwal->id,
            'driver_id' => $driver->id,
            'armada_id' => $armada->id,
            'status_trip' => Trip::STATUS_COMPLETED,
            'started_at' => now()->startOfDay()->addHours(12),
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('onTimePercentage', 0);
    }
}
