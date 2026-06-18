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
use App\Models\WhatsappNotification;
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

        config(['services.fonnte.token' => null]);

        $this->adminUser = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $this->rute = Rute::create([
            'asal' => 'Padang Panjang',
            'tujuan' => 'Pekanbaru',
            'tarif' => 150000,
        ]);

        $this->jadwal = Jadwal::create([
            'rute_id' => $this->rute->id,
            'tanggal_keberangkatan' => now()->addDays(2)->toDateString(),
            'shift' => 'pagi',
            'jam_berangkat' => '08:00',
            'kuota' => 10,
            'status_jadwal' => 'aktif',
        ]);

        $this->armada = Armada::create([
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 1234 XY',
            'kapasitas' => 5,
            'status_armada' => 'aktif',
        ]);

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

    public function test_admin_can_assign_booking_to_trip(): void
    {
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

        $trip = $this->createTrip($this->jadwal, $this->driver, $this->armada);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.trips.assign', $trip->id), [
                'booking_id' => $booking->id,
            ]);

        $response->assertRedirect(route('admin.trips.show', $trip->id));
        $response->assertSessionHas('success');

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

        $this->assertDatabaseHas('whatsapp_notifications', [
            'booking_id' => $booking->id,
            'target' => '6281299998888',
            'type' => WhatsappNotification::TYPE_CUSTOM,
            'status' => WhatsappNotification::STATUS_SENT,
        ]);

        $this->assertDatabaseHas('whatsapp_notifications', [
            'booking_id' => $booking->id,
            'target' => '6281234567890',
            'type' => WhatsappNotification::TYPE_CUSTOM,
            'status' => WhatsappNotification::STATUS_SENT,
        ]);

        $customerMessage = WhatsappNotification::where('booking_id', $booking->id)
            ->where('target', '6281299998888')
            ->firstOrFail()
            ->message;
        $driverMessage = WhatsappNotification::where('booking_id', $booking->id)
            ->where('target', '6281234567890')
            ->firstOrFail()
            ->message;

        $this->assertStringContainsString('*TRIP SUDAH DITENTUKAN*', $customerMessage);
        $this->assertStringContainsString('*Detail Trip*', $customerMessage);
        $this->assertStringContainsString('*Penjemputan*', $customerMessage);
        $this->assertStringContainsString('*BOOKING BARU DI TRIP*', $driverMessage);
        $this->assertStringContainsString('*Data Pelanggan*', $driverMessage);
    }

    public function test_trip_create_form_uses_driver_armada_without_armada_select(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.trips.create'));

        $response->assertOk();
        $response->assertSee($this->driver->nama_driver);
        $response->assertSee($this->armada->nomor_plat);
        $response->assertDontSee('name="armada_id"', false);
        $response->assertDontSee('Pilih Armada', false);
    }

    public function test_trip_detail_does_not_show_separate_armada_assignment(): void
    {
        $trip = $this->createTrip($this->jadwal, $this->driver, $this->armada);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.trips.show', $trip->id));

        $response->assertOk();
        $response->assertDontSee('Tugaskan / Ganti Armada', false);
        $response->assertDontSee('name="armada_id"', false);
    }

    public function test_admin_can_create_second_trip_for_same_schedule_with_different_driver(): void
    {
        $this->createTrip($this->jadwal, $this->driver, $this->armada);
        [$secondDriver, $secondArmada] = $this->createDriverWithArmada('Dedi Driver', 'dedi@test.com', 'BA 4321 CD');

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.trips.store'), [
                'jadwal_id' => $this->jadwal->id,
                'driver_id' => $secondDriver->id,
            ]);

        $response->assertRedirect(route('admin.trips.index'));
        $response->assertSessionHasNoErrors();

        $this->assertSame(2, Trip::where('jadwal_id', $this->jadwal->id)->count());
        $this->assertDatabaseHas('trips', [
            'jadwal_id' => $this->jadwal->id,
            'driver_id' => $secondDriver->id,
            'armada_id' => $secondArmada->id,
        ]);
    }

    public function test_admin_cannot_create_trip_with_inactive_driver(): void
    {
        [$inactiveDriver] = $this->createDriverWithArmada('Nonaktif Driver', 'nonaktif@test.com', 'BA 4040 ND');
        $inactiveDriver->update(['status_driver' => 'nonaktif']);

        $response = $this->actingAs($this->adminUser)
            ->from(route('admin.trips.create'))
            ->post(route('admin.trips.store'), [
                'jadwal_id' => $this->jadwal->id,
                'driver_id' => $inactiveDriver->id,
            ]);

        $response->assertRedirect(route('admin.trips.create'));
        $response->assertSessionHasErrors(['driver_id' => 'Driver ini tidak aktif.']);

        $this->assertDatabaseMissing('trips', [
            'jadwal_id' => $this->jadwal->id,
            'driver_id' => $inactiveDriver->id,
        ]);
    }

    public function test_admin_can_change_driver_when_driver_has_trip_on_different_shift(): void
    {
        $targetTrip = $this->createTrip($this->jadwal, $this->driver, $this->armada);
        [$otherDriver, $otherArmada] = $this->createDriverWithArmada('Riko Driver', 'riko@test.com', 'BA 7777 RK');
        $differentShift = $this->createSchedule('malam', '20:00');
        $this->createTrip($differentShift, $otherDriver, $otherArmada);

        $response = $this->actingAs($this->adminUser)
            ->from(route('admin.trips.show', $targetTrip->id))
            ->put(route('admin.trips.update', $targetTrip->id), [
                'driver_id' => $otherDriver->id,
            ]);

        $response->assertRedirect(route('admin.trips.show', $targetTrip->id));
        $response->assertSessionMissing('error');

        $this->assertDatabaseHas('trips', [
            'id' => $targetTrip->id,
            'driver_id' => $otherDriver->id,
            'armada_id' => $otherArmada->id,
        ]);
    }

    public function test_admin_cannot_change_driver_when_driver_has_trip_on_same_date_and_shift(): void
    {
        $targetTrip = $this->createTrip($this->jadwal, $this->driver, $this->armada);
        [$conflictDriver, $conflictArmada] = $this->createDriverWithArmada('Sari Driver', 'sari@test.com', 'BA 8888 SR');
        $this->createTrip($this->jadwal, $conflictDriver, $conflictArmada);

        $response = $this->actingAs($this->adminUser)
            ->from(route('admin.trips.show', $targetTrip->id))
            ->put(route('admin.trips.update', $targetTrip->id), [
                'driver_id' => $conflictDriver->id,
            ]);

        $response->assertRedirect(route('admin.trips.show', $targetTrip->id));
        $response->assertSessionHas('error', 'Driver sudah bertugas pada tanggal dan shift yang sama.');

        $this->assertDatabaseHas('trips', [
            'id' => $targetTrip->id,
            'driver_id' => $this->driver->id,
        ]);
    }

    public function test_admin_cannot_change_to_driver_whose_armada_has_trip_on_same_date_and_shift(): void
    {
        $targetTrip = $this->createTrip($this->jadwal, $this->driver, $this->armada);
        [$conflictDriver, $conflictArmada] = $this->createDriverWithArmada('Tono Driver', 'tono@test.com', 'BA 9999 TN');
        $this->createTrip($this->jadwal, $conflictDriver, $conflictArmada);

        $sharedArmadaUser = User::create([
            'name' => 'Satu Armada Driver',
            'email' => 'satu-armada@test.com',
            'password' => bcrypt('password123'),
            'role' => 'driver',
        ]);

        $sharedArmadaDriver = Driver::create([
            'user_id' => $sharedArmadaUser->id,
            'nama_driver' => 'Satu Armada Driver',
            'no_hp' => '081233344455',
            'armada_id' => $conflictArmada->id,
            'status_driver' => 'aktif',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->from(route('admin.trips.show', $targetTrip->id))
            ->put(route('admin.trips.update', $targetTrip->id), [
                'driver_id' => $sharedArmadaDriver->id,
            ]);

        $response->assertRedirect(route('admin.trips.show', $targetTrip->id));
        $response->assertSessionHas('error', 'Armada milik driver sudah digunakan pada tanggal dan shift yang sama.');

        $this->assertDatabaseHas('trips', [
            'id' => $targetTrip->id,
            'driver_id' => $this->driver->id,
            'armada_id' => $this->armada->id,
        ]);
    }

    private function createDriverWithArmada(string $name, string $email, string $plate): array
    {
        $armada = Armada::create([
            'nama_mobil' => 'Toyota Innova',
            'nomor_plat' => $plate,
            'kapasitas' => 6,
            'status_armada' => 'aktif',
        ]);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt('password123'),
            'role' => 'driver',
        ]);

        $driver = Driver::create([
            'user_id' => $user->id,
            'nama_driver' => $name,
            'no_hp' => '081200000000',
            'armada_id' => $armada->id,
            'status_driver' => 'aktif',
        ]);

        return [$driver, $armada];
    }

    private function createSchedule(string $shift, string $time): Jadwal
    {
        return Jadwal::create([
            'rute_id' => $this->rute->id,
            'tanggal_keberangkatan' => $this->jadwal->tanggal_keberangkatan->toDateString(),
            'shift' => $shift,
            'jam_berangkat' => $time,
            'kuota' => 10,
            'status_jadwal' => 'aktif',
        ]);
    }

    private function createTrip(Jadwal $jadwal, Driver $driver, Armada $armada): Trip
    {
        return Trip::create([
            'jadwal_id' => $jadwal->id,
            'driver_id' => $driver->id,
            'armada_id' => $armada->id,
            'status_trip' => Trip::STATUS_READY,
        ]);
    }
}
