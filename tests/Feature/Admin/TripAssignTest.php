<?php

namespace Tests\Feature\Admin;

use App\Models\Armada;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Models\Rute;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripAssignTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected Rute $rute;
    protected Jadwal $jadwal;
    protected Driver $driver;
    protected Armada $armada;
    protected Pelanggan $pelanggan;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin User
        $this->adminUser = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        // Create Rute
        $this->rute = Rute::create([
            'asal' => 'Padang Panjang',
            'tujuan' => 'Pekanbaru',
            'tarif' => 150000,
        ]);

        // Create Jadwal
        $this->jadwal = Jadwal::create([
            'rute_id' => $this->rute->id,
            'tanggal_keberangkatan' => now()->addDays(2)->toDateString(),
            'shift' => 'pagi',
            'jam_berangkat' => '08:00',
            'kuota' => 10,
            'status_jadwal' => 'aktif',
        ]);

        // Create Armada
        $this->armada = Armada::create([
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 1234 XY',
            'kapasitas' => 5,
            'status_armada' => 'aktif',
        ]);

        // Create Driver User & Driver
        $driverUser = User::create([
            'name' => 'Joni Driver',
            'email' => 'joni@test.com',
            'password' => bcrypt('password123'),
            'role' => 'driver',
        ]);

        $this->driver = Driver::create([
            'user_id' => $driverUser->id,
            'nama_driver' => 'Joni Driver',
            'no_hp' => '081234567890',
            'armada_id' => $this->armada->id,
            'status_driver' => 'aktif',
        ]);

        // Create Pelanggan User & Pelanggan
        $pelangganUser = User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi@test.com',
            'password' => bcrypt('password123'),
            'role' => 'pelanggan',
        ]);

        $this->pelanggan = Pelanggan::create([
            'user_id' => $pelangganUser->id,
            'nama' => 'Budi Pelanggan',
            'no_hp' => '081299998888',
        ]);
    }

    /**
     * Test admin can assign booking to trip.
     */
    public function test_admin_can_assign_booking_to_trip(): void
    {
        // Create a confirmed booking for the schedule
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-TEST-12345',
            'alamat_jemput' => 'Padang Panjang City Center',
            'alamat_tujuan' => 'Pekanbaru Airport',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_DIKONFIRMASI,
        ]);

        // Create a trip for the schedule
        $trip = Trip::create([
            'jadwal_id' => $this->jadwal->id,
            'driver_id' => $this->driver->id,
            'armada_id' => $this->armada->id,
            'status_trip' => 'ready',
        ]);

        // Assign booking to trip
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.trips.assign', $trip->id), [
                'booking_id' => $booking->id,
            ]);

        $response->assertRedirect(route('admin.trips.show', $trip->id));
        $response->assertSessionHas('success');

        // Check database status
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status_booking' => Booking::STATUS_ASSIGNED_TO_TRIP,
        ]);

        $this->assertDatabaseHas('detail_trip', [
            'trip_id' => $trip->id,
            'booking_id' => $booking->id,
            'status_jemput' => 'belum',
            'status_antar' => 'belum',
        ]);
    }
}
