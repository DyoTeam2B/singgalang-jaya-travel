<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Models\Rating;
use App\Models\Rute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $customerUser;
    protected User $otherCustomerUser;
    protected User $adminUser;
    protected Pelanggan $pelanggan;
    protected Pelanggan $otherPelanggan;
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

        $this->otherCustomerUser = User::create([
            'name' => 'Andi Pelanggan',
            'email' => 'andi@test.com',
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

        $this->otherPelanggan = Pelanggan::create([
            'user_id' => $this->otherCustomerUser->id,
            'nama' => 'Andi Pelanggan',
            'no_hp' => '081211112222',
        ]);

        $this->rute = Rute::create([
            'asal' => 'Padang Panjang',
            'tujuan' => 'Pekanbaru',
            'tarif' => 150000,
        ]);

        $this->jadwal = Jadwal::create([
            'rute_id' => $this->rute->id,
            'tanggal_keberangkatan' => now()->subDays(2)->toDateString(),
            'shift' => 'pagi',
            'jam_berangkat' => '08:00',
            'kuota' => 10,
            'status_jadwal' => 'aktif',
        ]);
    }

    /**
     * Test customer can submit rating for their completed booking.
     */
    public function test_customer_can_submit_rating_for_completed_booking(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-COMP-001',
            'alamat_jemput' => 'Alamat Jemput',
            'alamat_tujuan' => 'Alamat Tujuan',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_COMPLETED,
        ]);

        $response = $this->actingAs($this->customerUser)
            ->post(route('booking.rating.store', ['kode' => $booking->kode_booking]), [
                'rating' => 5,
                'ulasan' => 'Layanan sangat memuaskan, sopir ramah dan armada bersih!',
            ]);

        $response->assertRedirect(route('booking.show', ['kode' => $booking->kode_booking]));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ratings', [
            'booking_id' => $booking->id,
            'pelanggan_id' => $this->pelanggan->id,
            'rating' => 5,
            'ulasan' => 'Layanan sangat memuaskan, sopir ramah dan armada bersih!',
            'status' => 'menunggu',
        ]);
    }

    /**
     * Test customer cannot submit rating for booking that is not completed.
     */
    public function test_customer_cannot_submit_rating_for_non_completed_booking(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-PEND-001',
            'alamat_jemput' => 'Alamat Jemput',
            'alamat_tujuan' => 'Alamat Tujuan',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
        ]);

        $response = $this->actingAs($this->customerUser)
            ->post(route('booking.rating.store', ['kode' => $booking->kode_booking]), [
                'rating' => 5,
                'ulasan' => 'Mencoba memberi ulasan',
            ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('ratings', [
            'booking_id' => $booking->id,
        ]);
    }

    /**
     * Test customer cannot submit duplicate rating.
     */
    public function test_customer_cannot_submit_duplicate_rating(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-COMP-002',
            'alamat_jemput' => 'Alamat Jemput',
            'alamat_tujuan' => 'Alamat Tujuan',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_COMPLETED,
        ]);

        Rating::create([
            'booking_id' => $booking->id,
            'pelanggan_id' => $this->pelanggan->id,
            'rating' => 4,
            'ulasan' => 'Rating pertama',
            'status' => 'menunggu',
        ]);

        $response = $this->actingAs($this->customerUser)
            ->post(route('booking.rating.store', ['kode' => $booking->kode_booking]), [
                'rating' => 5,
                'ulasan' => 'Rating kedua ganda',
            ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, Rating::where('booking_id', $booking->id)->count());
    }

    /**
     * Test customer cannot rate other customer's booking.
     */
    public function test_customer_cannot_rate_other_customer_booking(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->otherPelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-COMP-003',
            'alamat_jemput' => 'Alamat Jemput',
            'alamat_tujuan' => 'Alamat Tujuan',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_COMPLETED,
        ]);

        $response = $this->actingAs($this->customerUser)
            ->post(route('booking.rating.store', ['kode' => $booking->kode_booking]), [
                'rating' => 5,
                'ulasan' => 'Mencoba membobol rating orang lain',
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('ratings', [
            'booking_id' => $booking->id,
        ]);
    }

    /**
     * Test rating visibility on Landing Page based on status.
     */
    public function test_rating_visibility_on_landing_page(): void
    {
        $booking1 = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-COMP-004',
            'alamat_jemput' => 'Alamat Jemput',
            'alamat_tujuan' => 'Alamat Tujuan',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_COMPLETED,
        ]);

        $booking2 = Booking::create([
            'pelanggan_id' => $this->otherPelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-COMP-005',
            'alamat_jemput' => 'Alamat Jemput',
            'alamat_tujuan' => 'Alamat Tujuan',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_COMPLETED,
        ]);

        Rating::create([
            'booking_id' => $booking1->id,
            'pelanggan_id' => $this->pelanggan->id,
            'rating' => 5,
            'ulasan' => 'Ulasan Yang Dipublikasikan',
            'status' => 'published',
        ]);

        Rating::create([
            'booking_id' => $booking2->id,
            'pelanggan_id' => $this->otherPelanggan->id,
            'rating' => 4,
            'ulasan' => 'Ulasan Yang Disembunyikan',
            'status' => 'hidden',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Ulasan Yang Dipublikasikan');
        $response->assertDontSee('Ulasan Yang Disembunyikan');
    }

    /**
     * Test admin can manage rating status.
     */
    public function test_admin_can_manage_rating_status(): void
    {
        $booking = Booking::create([
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-COMP-006',
            'alamat_jemput' => 'Alamat Jemput',
            'alamat_tujuan' => 'Alamat Tujuan',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_COMPLETED,
        ]);

        $rating = Rating::create([
            'booking_id' => $booking->id,
            'pelanggan_id' => $this->pelanggan->id,
            'rating' => 5,
            'ulasan' => 'Ulasan Sangat Keren',
            'status' => 'menunggu',
        ]);

        // 1. Publish Rating
        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.rating.publish', $rating->id));

        $response->assertRedirect();
        $this->assertEquals('published', $rating->fresh()->status);

        // 2. Hide Rating
        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.rating.hide', $rating->id));

        $response->assertRedirect();
        $this->assertEquals('hidden', $rating->fresh()->status);

        // 3. Delete Rating
        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.rating.destroy', $rating->id));

        $response->assertRedirect(route('admin.rating.index'));
        $this->assertDatabaseMissing('ratings', [
            'id' => $rating->id,
        ]);
    }
}
