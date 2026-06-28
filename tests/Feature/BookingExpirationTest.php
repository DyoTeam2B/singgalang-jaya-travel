<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Rute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class BookingExpirationTest extends TestCase
{
    use RefreshDatabase;

    protected User $customerUser;
    protected User $adminUser;
    protected Pelanggan $pelanggan;
    protected Rute $rute;
    protected Jadwal $jadwal;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customerUser = User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi@test.com',
            'password' => bcrypt('password123'),
            'role' => 'pelanggan',
        ]);

        $this->adminUser = User::create([
            'name' => 'Admin Super',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $this->pelanggan = Pelanggan::create([
            'user_id' => $this->customerUser->id,
            'nama' => 'Budi Pelanggan',
            'no_hp' => '081299998888',
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
    }

    /**
     * Test booking creation sets expired_at to 30 minutes in the future.
     */
    public function test_booking_creation_sets_expired_at_automatically(): void
    {
        $response = $this->actingAs($this->customerUser)
            ->post(route('booking.store'), [
                'jadwal_id' => $this->jadwal->id,
                'nama' => 'Budi Pelanggan',
                'no_hp' => '081299998888',
                'alamat_jemput' => 'Alamat Jemput',
                'alamat_tujuan' => 'Alamat Tujuan',
                'jumlah_penumpang' => 2,
            ]);

        $booking = Booking::first();
        $this->assertNotNull($booking->expired_at);
        $this->assertTrue(now()->addMinutes(29)->lessThan($booking->expired_at));
        $this->assertTrue(now()->addMinutes(31)->greaterThan($booking->expired_at));
    }

    /**
     * Test booking:expire command cancels expired bookings and returns quota to schedule.
     */
    public function test_expire_bookings_command_cancels_expired_booking_and_recalculates_quota(): void
    {
        // 1. Create a booking that is already expired
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-EXP-001',
            'alamat_jemput' => 'Alamat Jemput A',
            'alamat_tujuan' => 'Alamat Tujuan A',
            'jumlah_penumpang' => 10, // takes all kuota
            'total_harga' => 1500000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
            'expired_at' => now()->subMinutes(1),
        ]);

        $this->jadwal->checkAndUpdateStatus();
        $this->jadwal->refresh();
        $this->assertEquals(Jadwal::STATUS_PENUH, $this->jadwal->status_jadwal);

        // 2. Run the expire command
        Artisan::call('booking:expire');

        // 3. Assert booking record is deleted
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);

        // 4. Assert seats are returned (schedule status becomes aktif again)
        $this->jadwal->refresh();
        $this->assertEquals(Jadwal::STATUS_AKTIF, $this->jadwal->status_jadwal);
    }

    /**
     * Test uploading proof of payment is blocked for expired bookings.
     */
    public function test_expired_booking_cannot_upload_proof_of_payment(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-EXP-002',
            'alamat_jemput' => 'Alamat Jemput A',
            'alamat_tujuan' => 'Alamat Tujuan A',
            'jumlah_penumpang' => 1,
            'total_harga' => 150000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
            'expired_at' => now()->subMinutes(1),
        ]);

        // Access pembayaran show page
        $response = $this->actingAs($this->customerUser)
            ->get(route('booking.pembayaran', ['kode' => $booking->kode_booking]));

        $response->assertRedirect(route('booking.index'));
        $response->assertSessionHas('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');

        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    /**
     * Test admin cannot verify payment of expired bookings.
     */
    public function test_admin_cannot_verify_payment_of_expired_booking(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-EXP-003',
            'alamat_jemput' => 'Alamat Jemput A',
            'alamat_tujuan' => 'Alamat Tujuan A',
            'jumlah_penumpang' => 1,
            'total_harga' => 150000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
            'expired_at' => now()->subMinutes(1),
        ]);

        $pembayaran = Pembayaran::create([
            'booking_id' => $booking->id,
            'jenis_pembayaran' => Pembayaran::JENIS_DP,
            'jumlah_bayar' => 50000,
            'bukti_pembayaran' => 'bukti.jpg',
            'status_pembayaran' => Pembayaran::STATUS_MENUNGGU,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.pembayaran.verify', ['pembayaran' => $pembayaran->id]));

        $response->assertRedirect(route('admin.pembayaran.index'));
        $response->assertSessionHas('error', 'Tidak dapat memproses pembayaran. Booking terkait sudah kedaluwarsa dan dibatalkan secara otomatis.');

        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
        $this->assertDatabaseMissing('pembayaran', ['id' => $pembayaran->id]);
    }
}
