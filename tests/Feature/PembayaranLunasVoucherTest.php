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

class PembayaranLunasVoucherTest extends TestCase
{
    use RefreshDatabase;

    public function test_pelanggan_dapat_bayar_lunas_dengan_voucher_diskon_10_persen(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'pelanggan',
        ]);

        $rute = Rute::create([
            'asal' => 'Padang',
            'tujuan' => 'Bukittinggi',
            'tarif' => 100000,
        ]);

        $jadwal = Jadwal::create([
            'rute_id' => $rute->id,
            'tanggal_keberangkatan' => now()->addDay()->toDateString(),
            'shift' => 'pagi',
            'jam_berangkat' => '08:00:00',
            'kuota' => 10,
            'status_jadwal' => Jadwal::STATUS_AKTIF,
        ]);

        $pelanggan = Pelanggan::create([
            'user_id' => $user->id,
            'nama' => 'Pelanggan Test',
            'no_hp' => '081234567890',
        ]);

        $booking = Booking::create([
            'pelanggan_id' => $pelanggan->id,
            'jadwal_id' => $jadwal->id,
            'kode_booking' => 'SJT-20260615-TEST1',
            'alamat_jemput' => 'Jl. Jemput No. 1',
            'alamat_tujuan' => 'Jl. Tujuan No. 2',
            'jumlah_penumpang' => 2,
            'total_harga' => 200000,
            'status_booking' => Booking::STATUS_MENUNGGU_PEMBAYARAN,
            'batas_bayar_at' => now()->addMinutes(30),
        ]);

        $response = $this->actingAs($user)->post(route('booking.pembayaran.store', $booking->kode_booking), [
            'jenis_pembayaran' => Pembayaran::JENIS_PELUNASAN,
            'metode_pembayaran' => 'Transfer Bank BCA',
            'bukti_pembayaran' => UploadedFile::fake()->image('bukti-lunas.jpg'),
        ]);

        $response->assertRedirect(route('cek-booking.index', ['kode_booking' => $booking->kode_booking]));

        $this->assertDatabaseHas('pembayaran', [
            'booking_id' => $booking->id,
            'jenis_pembayaran' => Pembayaran::JENIS_PELUNASAN,
            'jumlah_bayar' => 180000,
            'voucher_kode' => Pembayaran::VOUCHER_LUNAS_10,
            'diskon_persen' => Pembayaran::DISKON_LUNAS_PERSEN,
            'nominal_diskon' => 20000,
            'status_pembayaran' => Pembayaran::STATUS_MENUNGGU,
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status_booking' => Booking::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        $pembayaran = Pembayaran::firstOrFail();
        Storage::disk('public')->assertExists($pembayaran->bukti_pembayaran);
    }

    public function test_helper_menghitung_diskon_dan_nominal_lunas(): void
    {
        $this->assertSame(20000, Pembayaran::hitungDiskonLunas(200000));
        $this->assertSame(180000, Pembayaran::hitungNominalLunas(200000));
    }
}
