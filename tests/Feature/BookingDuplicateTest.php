<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pembayaran;
use App\Models\Pelanggan;
use App\Models\Rute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingDuplicateTest extends TestCase
{
    use RefreshDatabase;

    protected User $customerUser;
    protected Pelanggan $pelanggan;
    protected Rute $rute;
    protected Jadwal $jadwalA;
    protected Jadwal $jadwalB;

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

        $this->jadwalA = Jadwal::create([
            'rute_id' => $this->rute->id,
            'tanggal_keberangkatan' => now()->addDays(2)->toDateString(),
            'shift' => 'pagi',
            'jam_berangkat' => '08:00',
            'kuota' => 10,
            'status_jadwal' => 'aktif',
        ]);

        $this->jadwalB = Jadwal::create([
            'rute_id' => $this->rute->id,
            'tanggal_keberangkatan' => now()->addDays(3)->toDateString(),
            'shift' => 'pagi',
            'jam_berangkat' => '08:00',
            'kuota' => 10,
            'status_jadwal' => 'aktif',
        ]);
    }

    /**
     * Test the 4 conditions:
     * 1. Booking pertama pada jadwal A berhasil.
     * 2. Booking kedua pada jadwal A ditolak.
     * 3. Booking pada jadwal B berhasil.
     * 4. Booking ulang jadwal A setelah status sebelumnya dibatalkan/ditolak/selesai berhasil.
     */
    public function test_booking_duplicate_validation_rules(): void
    {
        // 1. Booking pertama pada jadwal A berhasil.
        $response1 = $this->actingAs($this->customerUser)
            ->post(route('booking.store'), [
                'jadwal_id' => $this->jadwalA->id,
                'nama' => 'Budi Pelanggan',
                'no_hp' => '081299998888',
                'alamat_jemput' => 'Alamat Jemput A',
                'alamat_tujuan' => 'Alamat Tujuan A',
                'jumlah_penumpang' => 1,
            ]);

        $response1->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwalA->id,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
        ]);

        // Fetch first booking
        $firstBooking = Booking::where('pelanggan_id', $this->pelanggan->id)
            ->where('jadwal_id', $this->jadwalA->id)
            ->firstOrFail();

        // 2. Booking kedua pada jadwal A ditolak.
        $response2 = $this->actingAs($this->customerUser)
            ->post(route('booking.store'), [
                'jadwal_id' => $this->jadwalA->id,
                'nama' => 'Budi Pelanggan',
                'no_hp' => '081299998888',
                'alamat_jemput' => 'Alamat Jemput A',
                'alamat_tujuan' => 'Alamat Tujuan A',
                'jumlah_penumpang' => 1,
            ]);

        $response2->assertSessionHasErrors(['jadwal_id' => 'Anda sudah memiliki booking aktif pada jadwal ini.']);

        // 3. Booking pada jadwal B berhasil.
        $response3 = $this->actingAs($this->customerUser)
            ->post(route('booking.store'), [
                'jadwal_id' => $this->jadwalB->id,
                'nama' => 'Budi Pelanggan',
                'no_hp' => '081299998888',
                'alamat_jemput' => 'Alamat Jemput B',
                'alamat_tujuan' => 'Alamat Tujuan B',
                'jumlah_penumpang' => 1,
            ]);

        $response3->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'pelanggan_id' => $this->pelanggan->id,
            'jadwal_id' => $this->jadwalB->id,
        ]);

        // 4a. Booking ulang jadwal A setelah status sebelumnya dibatalkan berhasil.
        $firstBooking->update(['status_booking' => Booking::STATUS_CANCELLED]);

        $response4a = $this->actingAs($this->customerUser)
            ->post(route('booking.store'), [
                'jadwal_id' => $this->jadwalA->id,
                'nama' => 'Budi Pelanggan',
                'no_hp' => '081299998888',
                'alamat_jemput' => 'Alamat Jemput A2',
                'alamat_tujuan' => 'Alamat Tujuan A2',
                'jumlah_penumpang' => 1,
            ]);

        $response4a->assertRedirect();
        $this->assertEquals(2, Booking::where('pelanggan_id', $this->pelanggan->id)->where('jadwal_id', $this->jadwalA->id)->count());

        // Fetch second booking on Schedule A
        $secondBooking = Booking::where('pelanggan_id', $this->pelanggan->id)
            ->where('jadwal_id', $this->jadwalA->id)
            ->where('status_booking', Booking::STATUS_BOOKING_DIBUAT)
            ->firstOrFail();

        // 4b. Booking kedua pada jadwal A sekarang aktif, jadi booking ketiga harus ditolak.
        $response4b = $this->actingAs($this->customerUser)
            ->post(route('booking.store'), [
                'jadwal_id' => $this->jadwalA->id,
                'nama' => 'Budi Pelanggan',
                'no_hp' => '081299998888',
                'alamat_jemput' => 'Alamat Jemput A3',
                'alamat_tujuan' => 'Alamat Tujuan A3',
                'jumlah_penumpang' => 1,
            ]);

        $response4b->assertSessionHasErrors(['jadwal_id' => 'Anda sudah memiliki booking aktif pada jadwal ini.']);

        // 4c. Booking ulang jadwal A setelah status sebelumnya ditolak (payment ditolak) berhasil.
        // Simulate payment reject on the second booking
        Pembayaran::create([
            'booking_id' => $secondBooking->id,
            'jenis_pembayaran' => Pembayaran::JENIS_DP,
            'jumlah_bayar' => 50000,
            'metode_pembayaran' => 'transfer',
            'status_pembayaran' => Pembayaran::STATUS_DITOLAK,
            'catatan' => 'Bukti bayar tidak jelas',
        ]);

        $response4c = $this->actingAs($this->customerUser)
            ->post(route('booking.store'), [
                'jadwal_id' => $this->jadwalA->id,
                'nama' => 'Budi Pelanggan',
                'no_hp' => '081299998888',
                'alamat_jemput' => 'Alamat Jemput A4',
                'alamat_tujuan' => 'Alamat Tujuan A4',
                'jumlah_penumpang' => 1,
            ]);

        $response4c->assertRedirect();
        $this->assertEquals(3, Booking::where('pelanggan_id', $this->pelanggan->id)->where('jadwal_id', $this->jadwalA->id)->count());
    }
}
