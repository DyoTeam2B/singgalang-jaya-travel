<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Models\Rating;
use App\Models\Rute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingControllerTest extends TestCase
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
            'kode_booking' => 'SJT-RATING-123',
            'alamat_jemput' => 'A',
            'alamat_tujuan' => 'B',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
        ]);
    }

    /**
     * TC-01: Kode booking salah -> Redirect index dengan error
     */
    public function test_rating_store_with_invalid_booking_code(): void
    {
        $response = $this->actingAs($this->customerUser)
            ->post(route('booking.rating.store', ['kode' => 'SJT-INVALID-CODE']), [
                'rating' => 5,
                'ulasan' => 'Sangat memuaskan!',
            ]);

        $response->assertRedirect(route('booking.index'));
        $response->assertSessionHas('error', 'Booking tidak ditemukan.');
    }

    /**
     * TC-02: Rating pada booking yang masih aktif -> Dicegat, harus selesai dulu
     */
    public function test_rating_store_on_active_booking_is_blocked(): void
    {
        $response = $this->actingAs($this->customerUser)
            ->from(route('booking.show', ['kode' => $this->booking->kode_booking]))
            ->post(route('booking.rating.store', ['kode' => $this->booking->kode_booking]), [
                'rating' => 5,
                'ulasan' => 'Sangat memuaskan!',
            ]);

        $response->assertRedirect(route('booking.show', ['kode' => $this->booking->kode_booking]));
        $response->assertSessionHas('error', 'Rating hanya dapat diberikan setelah perjalanan selesai.');
    }

    /**
     * TC-03: Rating kedua kali pada trip yang sama -> Dicegat, ulasan hanya bisa sekali
     */
    public function test_rating_store_cannot_be_submitted_twice(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-COMPLETED-123',
            'alamat_jemput' => 'A',
            'alamat_tujuan' => 'B',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_COMPLETED,
        ]);

        Rating::create([
            'booking_id' => $booking->id,
            'pelanggan_id' => $this->pelanggan->id,
            'rating' => 5,
            'ulasan' => 'Ulasan pertama',
            'status' => 'published',
        ]);

        $response = $this->actingAs($this->customerUser)
            ->from(route('booking.show', ['kode' => $booking->kode_booking]))
            ->post(route('booking.rating.store', ['kode' => $booking->kode_booking]), [
                'rating' => 4,
                'ulasan' => 'Ulasan kedua coba-coba',
            ]);

        $response->assertRedirect(route('booking.show', ['kode' => $booking->kode_booking]));
        $response->assertSessionHas('error', 'Anda sudah memberikan rating untuk perjalanan ini.');
    }

    /**
     * TC-04: Rating pertama kali setelah trip selesai -> Ulasan tersimpan, status menunggu moderasi
     */
    public function test_rating_store_succeeds_on_completed_trip(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-COMPLETED-456',
            'alamat_jemput' => 'A',
            'alamat_tujuan' => 'B',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_COMPLETED,
        ]);

        $response = $this->actingAs($this->customerUser)
            ->post(route('booking.rating.store', ['kode' => $booking->kode_booking]), [
                'rating' => 5,
                'ulasan' => 'Sangat menyenangkan perjalanan dengan driver ramah!',
            ]);

        $response->assertRedirect(route('booking.show', ['kode' => $booking->kode_booking]));
        $response->assertSessionHas('success', 'Terima kasih atas ulasan Anda! Ulasan Anda sedang menunggu persetujuan admin.');

        $this->assertDatabaseHas('ratings', [
            'booking_id' => $booking->id,
            'pelanggan_id' => $this->pelanggan->id,
            'rating' => 5,
            'ulasan' => 'Sangat menyenangkan perjalanan dengan driver ramah!',
            'status' => 'menunggu',
        ]);
    }
}
