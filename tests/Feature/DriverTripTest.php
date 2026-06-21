<?php

namespace Tests\Feature;

use App\Models\Armada;
use App\Models\Booking;
use App\Models\Driver;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Models\Rute;
use App\Models\Trip;
use App\Models\User;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverTripTest extends TestCase
{
    use RefreshDatabase;

    protected User $driverUser;
    protected Driver $driver;
    protected Armada $armada;
    protected Rute $rute;
    protected Jadwal $jadwal;
    protected Pelanggan $pelanggan;
    protected Booking $booking;
    protected Trip $trip;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Armada
        $this->armada = Armada::create([
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 9999 AA',
            'kapasitas' => 5,
            'status_armada' => 'aktif',
        ]);

        // 2. Create Driver User
        $this->driverUser = User::create([
            'name' => 'Kevin Driver',
            'email' => 'kevin@driver.com',
            'password' => bcrypt('password123'),
            'role' => 'driver',
        ]);

        // 3. Create Driver Profile
        $this->driver = Driver::create([
            'user_id' => $this->driverUser->id,
            'nama_driver' => 'Kevin Driver',
            'no_hp' => '081211112222',
            'armada_id' => $this->armada->id,
            'status_driver' => 'aktif',
        ]);

        // 4. Create Rute
        $this->rute = Rute::create([
            'asal' => 'Padang Panjang',
            'tujuan' => 'Pekanbaru',
            'tarif' => 150000,
        ]);

        // 5. Create Jadwal
        $this->jadwal = Jadwal::create([
            'rute_id' => $this->rute->id,
            'tanggal_keberangkatan' => now()->addDay()->toDateString(),
            'shift' => 'pagi',
            'jam_berangkat' => '08:00',
            'kuota' => 5,
            'status_jadwal' => 'aktif',
        ]);

        // 6. Create Pelanggan
        $pelangganUser = User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi@test.com',
            'password' => bcrypt('password123'),
            'role' => 'pelanggan',
        ]);
        $this->pelanggan = Pelanggan::create([
            'user_id' => $pelangganUser->id,
            'nama' => 'Budi Pelanggan',
            'no_hp' => '081233334444',
        ]);

        // 7. Create Booking (confirmed DP)
        $this->booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-20260601-ABCDE',
            'alamat_jemput' => 'Lokasi Penjemputan Joni',
            'alamat_tujuan' => 'Lokasi Pengantaran Joni',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000, // 2 x 150000
            'status_booking' => Booking::STATUS_DIKONFIRMASI,
        ]);

        // 8. Create Trip (assigned to our Driver)
        $this->trip = Trip::create([
            'jadwal_id' => $this->jadwal->id,
            'driver_id' => $this->driver->id,
            'armada_id' => $this->armada->id,
            'status_trip' => Trip::STATUS_READY,
        ]);

        // 9. Assign Booking to Trip
        $this->trip->detailTrips()->create([
            'booking_id' => $this->booking->id,
            'status_jemput' => 'belum',
            'status_antar' => 'belum',
        ]);
    }

    public function test_driver_can_access_dashboard_and_see_active_trip(): void
    {
        $response = $this->actingAs($this->driverUser)
            ->get(route('driver.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('TRP-');
        $response->assertSee('Toyota Avanza');
        $response->assertSee('Padang Panjang');
        $response->assertSee('Pekanbaru');
        $response->assertSee('Mulai Perjalanan');
    }

    public function test_driver_can_start_trip(): void
    {
        $response = $this->actingAs($this->driverUser)
            ->put(route('driver.trips.start', $this->trip->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('trips', [
            'id' => $this->trip->id,
            'status_trip' => Trip::STATUS_ON_TRIP,
        ]);

        $this->booking->refresh();
        $this->assertEquals(Booking::STATUS_ON_TRIP, $this->booking->status_booking);
    }

    public function test_driver_can_pickup_passenger(): void
    {
        $this->trip->update(['status_trip' => Trip::STATUS_ON_TRIP]);
        $detailTrip = $this->trip->detailTrips->first();

        $response = $this->actingAs($this->driverUser)
            ->put(route('driver.trips.pickup', [$this->trip->id, $detailTrip->id]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('detail_trip', [
            'id' => $detailTrip->id,
            'status_jemput' => 'sudah_dijemput',
        ]);
    }

    public function test_driver_can_dropoff_passenger_and_auto_confirm_pelunasan(): void
    {
        $this->trip->update(['status_trip' => Trip::STATUS_ON_TRIP]);
        $detailTrip = $this->trip->detailTrips->first();
        $detailTrip->update(['status_jemput' => 'sudah_dijemput']);

        $response = $this->actingAs($this->driverUser)
            ->put(route('driver.trips.dropoff', [$this->trip->id, $detailTrip->id]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('detail_trip', [
            'id' => $detailTrip->id,
            'status_antar' => 'sudah_diantar',
        ]);

        // Check if pelunasan payment record is created
        $this->assertDatabaseHas('pembayaran', [
            'booking_id' => $this->booking->id,
            'jenis_pembayaran' => Pembayaran::JENIS_PELUNASAN,
            'jumlah_bayar' => 250000, // 300000 total - 50000 DP
            'status_pembayaran' => Pembayaran::STATUS_TERVERIFIKASI,
            'metode_pembayaran' => 'cash',
        ]);
    }

    public function test_driver_can_confirm_payment_separately(): void
    {
        $detailTrip = $this->trip->detailTrips->first();

        $response = $this->actingAs($this->driverUser)
            ->put(route('driver.trips.confirmPayment', [$this->trip->id, $detailTrip->id]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pembayaran', [
            'booking_id' => $this->booking->id,
            'jenis_pembayaran' => Pembayaran::JENIS_PELUNASAN,
            'jumlah_bayar' => 250000,
            'status_pembayaran' => Pembayaran::STATUS_TERVERIFIKASI,
        ]);
    }

    public function test_driver_can_complete_trip(): void
    {
        $this->trip->update(['status_trip' => Trip::STATUS_ON_TRIP]);
        $detailTrip = $this->trip->detailTrips->first();
        $detailTrip->update([
            'status_jemput' => 'sudah_dijemput',
            'status_antar' => 'sudah_diantar',
        ]);

        $response = $this->actingAs($this->driverUser)
            ->put(route('driver.trips.complete', $this->trip->id));

        $response->assertRedirect(route('driver.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('trips', [
            'id' => $this->trip->id,
            'status_trip' => Trip::STATUS_COMPLETED,
        ]);

        $this->booking->refresh();
        $this->assertEquals(Booking::STATUS_COMPLETED, $this->booking->status_booking);
    }

    public function test_driver_can_access_trip_history(): void
    {
        $response = $this->actingAs($this->driverUser)
            ->get(route('driver.trips.index'));

        $response->assertStatus(200);
        $response->assertSee('Riwayat Perjalanan');
    }
}
