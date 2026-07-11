<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Models\Rute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCustomerFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected User $customerUser;
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
     * 1. Edit lokasi jemput berhasil pada status yang diizinkan.
     */
    public function test_customer_can_edit_pickup_location_on_allowed_statuses(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-TEST-100',
            'alamat_jemput' => 'Alamat Jemput A',
            'alamat_tujuan' => 'Alamat Tujuan A',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
        ]);

        $response = $this->actingAs($this->customerUser)
            ->put(route('booking.update', ['kode' => $booking->kode_booking]), [
                'alamat_jemput' => 'Alamat Jemput Baru',
                'latitude_jemput' => -0.46,
                'longitude_jemput' => 100.40,
            ]);

        $response->assertRedirect(route('booking.show', ['kode' => $booking->kode_booking]));
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'alamat_jemput' => 'Alamat Jemput Baru',
        ]);
    }

    /**
     * 2. Edit jumlah penumpang berhasil pada status menunggu_dp (booking_dibuat) / menunggu_verifikasi.
     */
    public function test_customer_can_edit_passengers_on_pending_statuses(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-TEST-101',
            'alamat_jemput' => 'Alamat Jemput A',
            'alamat_tujuan' => 'Alamat Tujuan A',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
        ]);

        // Change passenger count to 4 (quota is 10, so 4 is allowed)
        $response = $this->actingAs($this->customerUser)
            ->put(route('booking.update', ['kode' => $booking->kode_booking]), [
                'alamat_jemput' => 'Alamat Jemput A',
                'jumlah_penumpang' => 4,
            ]);

        $response->assertRedirect(route('booking.show', ['kode' => $booking->kode_booking]));
        
        $booking->refresh();
        $this->assertEquals(4, $booking->jumlah_penumpang);
        $this->assertEquals(600000, $booking->total_harga); // 150000 * 4 = 600000
    }

    /**
     * 3. Edit jumlah penumpang ditolak pada status terverifikasi (dikonfirmasi)/berjalan/selesai.
     */
    public function test_customer_cannot_edit_passengers_on_confirmed_or_further_statuses(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-TEST-102',
            'alamat_jemput' => 'Alamat Jemput A',
            'alamat_tujuan' => 'Alamat Tujuan A',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_DIKONFIRMASI,
        ]);

        $response = $this->actingAs($this->customerUser)
            ->put(route('booking.update', ['kode' => $booking->kode_booking]), [
                'alamat_jemput' => 'Alamat Jemput A',
                'jumlah_penumpang' => 4,
            ]);

        $response->assertRedirect(route('booking.show', ['kode' => $booking->kode_booking]));
        $response->assertSessionHas('error', 'Jumlah penumpang tidak dapat diubah pada status saat ini.');
        
        $booking->refresh();
        $this->assertEquals(2, $booking->jumlah_penumpang); // remains unchanged
    }

    /**
     * 4. Booking selesai masuk ke Riwayat Booking.
     * 5. Booking aktif tetap tampil di Booking Aktif.
     */
    public function test_bookings_are_split_between_active_and_history(): void
    {
        // Active Booking
        $activeBooking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-ACTIVE-001',
            'alamat_jemput' => 'Alamat Jemput Active',
            'alamat_tujuan' => 'Alamat Tujuan Active',
            'jumlah_penumpang' => 1,
            'total_harga' => 150000,
            'status_booking' => Booking::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        // History Booking
        $historyBooking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-HISTORY-001',
            'alamat_jemput' => 'Alamat Jemput History',
            'alamat_tujuan' => 'Alamat Tujuan History',
            'jumlah_penumpang' => 1,
            'total_harga' => 150000,
            'status_booking' => Booking::STATUS_COMPLETED,
        ]);

        $response = $this->actingAs($this->customerUser)
            ->get(route('booking.index'));

        $response->assertOk();
        
        $activeData = $response->viewData('activeBookings');
        $historyData = $response->viewData('historyBookings');

        $this->assertTrue($activeData->contains($activeBooking));
        $this->assertFalse($activeData->contains($historyBooking));

        $this->assertTrue($historyData->contains($historyBooking));
        $this->assertFalse($historyData->contains($activeBooking));
    }

    /**
     * TC-01 (Function review): Kode booking fiktif/salah -> Redirect kembali dengan error
     */
    public function test_customer_review_with_invalid_booking_code(): void
    {
        $response = $this->actingAs($this->customerUser)
            ->get(route('booking.review', ['kode' => 'SJT-INVALID-CODE']));

        $response->assertRedirect(route('booking.index'));
        $response->assertSessionHas('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');
    }

    /**
     * TC-02 (Function review): Buka booking milik orang lain -> HTTP 403
     */
    public function test_customer_cannot_review_another_customers_booking(): void
    {
        // Buat customer & booking lain
        $otherUser = User::create([
            'name' => 'Other Customer',
            'email' => 'other@cust.com',
            'password' => bcrypt('password123'),
            'role' => 'pelanggan',
        ]);
        $otherPelanggan = Pelanggan::create([
            'user_id' => $otherUser->id,
            'nama' => 'Other Customer',
            'no_hp' => '081200003333',
        ]);
        $otherBooking = Booking::create([
            'pelanggan_id' => $otherPelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-OTHER-123',
            'alamat_jemput' => 'Alamat Jemput O',
            'alamat_tujuan' => 'Alamat Tujuan O',
            'jumlah_penumpang' => 1,
            'total_harga' => 150000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
        ]);

        $response = $this->actingAs($this->customerUser)
            ->get(route('booking.review', ['kode' => $otherBooking->kode_booking]));

        $response->assertStatus(403);
    }

    /**
     * TC-03 (Function review): Booking sudah dikonfirmasi -> Redirect ke halaman pembayaran
     */
    public function test_customer_review_on_confirmed_booking_redirects_to_payment(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-CONFIRMED-123',
            'alamat_jemput' => 'Alamat Jemput C',
            'alamat_tujuan' => 'Alamat Tujuan C',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_DIKONFIRMASI,
        ]);

        $response = $this->actingAs($this->customerUser)
            ->get(route('booking.review', ['kode' => $booking->kode_booking]));

        $response->assertRedirect(route('booking.pembayaran', ['kode' => $booking->kode_booking]));
    }

    /**
     * TC-04 (Function review): Buka booking yang baru saja dibuat -> Render view review
     */
    public function test_customer_can_access_review_page_on_newly_created_booking(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-NEW-123',
            'alamat_jemput' => 'Alamat Jemput N',
            'alamat_tujuan' => 'Alamat Tujuan N',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
        ]);

        $response = $this->actingAs($this->customerUser)
            ->get(route('booking.review', ['kode' => $booking->kode_booking]));

        $response->assertOk();
        $response->assertViewIs('public.booking.review');
        $response->assertSee('Tinjau Pemesanan');
    }

    /**
     * TC-01 (Function update): Kode booking salah -> Redirect error
     */
    public function test_customer_cannot_update_invalid_booking(): void
    {
        $response = $this->actingAs($this->customerUser)
            ->put(route('booking.update', ['kode' => 'SJT-INVALID-CODE']), [
                'alamat_jemput' => 'Alamat Jemput Baru',
            ]);

        $response->assertRedirect(route('booking.index'));
        $response->assertSessionHas('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');
    }

    /**
     * TC-02 (Function update): Booking milik orang lain -> HTTP 403
     */
    public function test_customer_cannot_update_another_customers_booking(): void
    {
        // Buat customer & booking lain
        $otherUser = User::create([
            'name' => 'Other Customer',
            'email' => 'other_update@cust.com',
            'password' => bcrypt('password123'),
            'role' => 'pelanggan',
        ]);
        $otherPelanggan = Pelanggan::create([
            'user_id' => $otherUser->id,
            'nama' => 'Other Customer',
            'no_hp' => '081200004444',
        ]);
        $otherBooking = Booking::create([
            'pelanggan_id' => $otherPelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-OTHER-UPDATE-123',
            'alamat_jemput' => 'Alamat Jemput O',
            'alamat_tujuan' => 'Alamat Tujuan O',
            'jumlah_penumpang' => 1,
            'total_harga' => 150000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
        ]);

        $response = $this->actingAs($this->customerUser)
            ->put(route('booking.update', ['kode' => $otherBooking->kode_booking]), [
                'alamat_jemput' => 'Alamat Jemput Baru',
            ]);

        $response->assertStatus(403);
    }

    /**
     * TC-04 (Function update): Jumlah penumpang > kuota sisa -> Ditolak, error kuota
     */
    public function test_customer_cannot_update_passengers_exceeding_quota(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-QUOTA-TEST',
            'alamat_jemput' => 'Alamat Jemput N',
            'alamat_tujuan' => 'Alamat Tujuan N',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
        ]);

        // Kuota total adalah 10. Jika diubah menjadi 12 (melebihi kuota), harusnya ditolak.
        $response = $this->actingAs($this->customerUser)
            ->from(route('booking.show', ['kode' => $booking->kode_booking]))
            ->put(route('booking.update', ['kode' => $booking->kode_booking]), [
                'alamat_jemput' => 'Alamat Jemput N',
                'jumlah_penumpang' => 12,
            ]);

        $response->assertRedirect(route('booking.show', ['kode' => $booking->kode_booking]));
        $response->assertSessionHas('error', 'Jumlah penumpang melebihi kuota kursi yang tersedia (10 kursi tersisa).');
    }
}
