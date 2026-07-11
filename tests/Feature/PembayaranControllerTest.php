<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Rute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PembayaranControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $customerUser;
    protected Pelanggan $pelanggan;
    protected Rute $rute;
    protected Jadwal $jadwal;
    protected Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customerUser = User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi@test.com',
            'password' => bcrypt('password123'),
            'role' => 'pelanggan',
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

        $this->booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-DP-123',
            'alamat_jemput' => 'A',
            'alamat_tujuan' => 'B',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
            'expired_at' => now()->addMinutes(30),
        ]);
    }

    /**
     * TC-01: Kode booking salah -> Redirect error
     */
    public function test_payment_store_with_invalid_booking_code(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->actingAs($this->customerUser)
            ->post(route('booking.pembayaran.store', ['kode' => 'SJT-INVALID']), [
                'bukti_pembayaran' => $file,
                'metode_pembayaran' => 'Transfer Bank BCA',
            ]);

        $response->assertRedirect(route('booking.index'));
        $response->assertSessionHas('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');
    }

    /**
     * TC-02: Booking milik orang lain -> HTTP 403
     */
    public function test_payment_store_for_another_customers_booking(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('bukti.jpg');

        $otherUser = User::create([
            'name' => 'Other Customer',
            'email' => 'other@cust.com',
            'password' => bcrypt('password123'),
            'role' => 'pelanggan',
        ]);
        $otherPelanggan = Pelanggan::create([
            'user_id' => $otherUser->id,
            'nama' => 'Other Customer',
            'no_hp' => '081200009999',
        ]);
        $otherBooking = Booking::create([
            'pelanggan_id' => $otherPelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-OTHER-DP',
            'alamat_jemput' => 'A',
            'alamat_tujuan' => 'B',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
        ]);

        $response = $this->actingAs($this->customerUser)
            ->post(route('booking.pembayaran.store', ['kode' => $otherBooking->kode_booking]), [
                'bukti_pembayaran' => $file,
                'metode_pembayaran' => 'Transfer Bank BCA',
            ]);

        $response->assertStatus(403);
    }

    /**
     * TC-03: Booking kedaluwarsa -> Expire otomatis, redirect error
     */
    public function test_payment_store_for_expired_booking(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('bukti.jpg');

        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-EXP-DP',
            'alamat_jemput' => 'A',
            'alamat_tujuan' => 'B',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
            'expired_at' => now()->subMinutes(1),
        ]);

        $response = $this->actingAs($this->customerUser)
            ->post(route('booking.pembayaran.store', ['kode' => $booking->kode_booking]), [
                'bukti_pembayaran' => $file,
                'metode_pembayaran' => 'Transfer Bank BCA',
            ]);

        $response->assertRedirect(route('booking.index'));
        $response->assertSessionHas('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');
    }

    /**
     * TC-04: Booking sudah diverifikasi -> Redirect info
     */
    public function test_payment_store_for_already_processed_booking(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('bukti.jpg');

        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-PROCESSED-DP',
            'alamat_jemput' => 'A',
            'alamat_tujuan' => 'B',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_DIKONFIRMASI,
        ]);

        $response = $this->actingAs($this->customerUser)
            ->post(route('booking.pembayaran.store', ['kode' => $booking->kode_booking]), [
                'bukti_pembayaran' => $file,
                'metode_pembayaran' => 'Transfer Bank BCA',
            ]);

        $response->assertRedirect(route('booking.show', ['kode' => $booking->kode_booking]));
        $response->assertSessionHas('info', 'Pembayaran booking ini sedang diproses atau sudah diverifikasi.');
    }

    /**
     * TC-05: Upload bukti valid -> Status menunggu verifikasi
     */
    public function test_payment_store_succeeds_with_valid_data(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->actingAs($this->customerUser)
            ->post(route('booking.pembayaran.store', ['kode' => $this->booking->kode_booking]), [
                'bukti_pembayaran' => $file,
                'metode_pembayaran' => 'Transfer Bank BCA',
                'catatan' => 'Catatan tes',
            ]);

        $response->assertRedirect(route('booking.show', ['kode' => $this->booking->kode_booking]));
        $response->assertSessionHas('success', 'Bukti pembayaran DP berhasil diunggah. Menunggu verifikasi admin.');

        $this->assertDatabaseHas('bookings', [
            'id' => $this->booking->id,
            'status_booking' => Booking::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        $this->assertDatabaseHas('pembayaran', [
            'booking_id' => $this->booking->id,
            'jenis_pembayaran' => 'dp',
            'jumlah_bayar' => 50000,
            'metode_pembayaran' => 'Transfer Bank BCA',
            'status_pembayaran' => 'menunggu',
            'catatan' => 'Catatan tes',
        ]);
    }
}
