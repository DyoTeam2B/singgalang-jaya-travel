<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Models\Rute;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CekBookingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected Rute $rute;
    protected Jadwal $jadwal;
    protected Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();

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

        $this->booking = Booking::create([
            'pelanggan_id' => $pelanggan->id,
            'jadwal_id' => $this->jadwal->id,
            'kode_booking' => 'SJT-CHECK-123',
            'alamat_jemput' => 'A',
            'alamat_tujuan' => 'B',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
        ]);
    }

    /**
     * TC-01: Buka halaman cek pertama kali -> Form pencarian kosong
     */
    public function test_cek_booking_index_without_code_returns_form(): void
    {
        $response = $this->get(route('cek-booking.index'));

        $response->assertStatus(200);
        $response->assertViewIs('public.cek-booking.index');
    }

    /**
     * TC-02: Kode booking salah -> Kembali ke form dengan alert error
     */
    public function test_cek_booking_index_with_invalid_code_redirects_with_error(): void
    {
        $response = $this->get(route('cek-booking.index', ['kode_booking' => 'SJT-INVALID-CODE']));

        $response->assertRedirect(route('cek-booking.index'));
        $response->assertSessionHas('error', 'Kode booking tidak ditemukan.');
    }

    /**
     * TC-03: Kode booking benar -> Menampilkan informasi lengkap manifest & status pembayaran
     */
    public function test_cek_booking_index_with_valid_code_returns_show_view(): void
    {
        $response = $this->get(route('cek-booking.index', ['kode_booking' => $this->booking->kode_booking]));

        $response->assertStatus(200);
        $response->assertViewIs('public.cek-booking.show');
        $response->assertViewHas('booking');
    }
}
