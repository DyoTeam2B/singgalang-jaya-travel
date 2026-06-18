<?php

namespace Tests\Feature\Admin;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Rute;
use App\Models\User;
use App\Models\WhatsappNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PembayaranNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_verifies_dp_and_sends_whatsapp_to_customer(): void
    {
        config(['services.fonnte.token' => null]);

        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin-payment@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $customerUser = User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi-payment@test.com',
            'password' => bcrypt('password123'),
            'role' => 'pelanggan',
        ]);

        $pelanggan = Pelanggan::create([
            'user_id' => $customerUser->id,
            'nama' => 'Budi Pelanggan',
            'no_hp' => '081299998888',
        ]);

        $rute = Rute::create([
            'asal' => 'Padang Panjang',
            'tujuan' => 'Pekanbaru',
            'tarif' => 150000,
        ]);

        $jadwal = Jadwal::create([
            'rute_id' => $rute->id,
            'tanggal_keberangkatan' => now()->addDays(2)->toDateString(),
            'shift' => 'pagi',
            'jam_berangkat' => '08:00',
            'kuota' => 10,
            'status_jadwal' => 'aktif',
        ]);

        $booking = Booking::create([
            'pelanggan_id' => $pelanggan->id,
            'jadwal_id' => $jadwal->id,
            'kode_booking' => 'SJT-DP-12345',
            'alamat_jemput' => 'Padang Panjang City Center',
            'alamat_tujuan' => 'Pekanbaru Airport',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        $pembayaran = Pembayaran::create([
            'booking_id' => $booking->id,
            'jenis_pembayaran' => Pembayaran::JENIS_DP,
            'jumlah_bayar' => 50000,
            'metode_pembayaran' => 'transfer',
            'bukti_pembayaran' => 'bukti-pembayaran/test.jpg',
            'status_pembayaran' => Pembayaran::STATUS_MENUNGGU,
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.pembayaran.verify', $pembayaran->id));

        $response->assertRedirect(route('admin.pembayaran.index', ['payment_id' => $pembayaran->id]));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status_booking' => Booking::STATUS_DIKONFIRMASI,
        ]);

        $this->assertDatabaseHas('whatsapp_notifications', [
            'booking_id' => $booking->id,
            'target' => '6281299998888',
            'type' => WhatsappNotification::TYPE_CUSTOM,
            'status' => WhatsappNotification::STATUS_SENT,
        ]);

        $notification = WhatsappNotification::where('booking_id', $booking->id)
            ->where('target', '6281299998888')
            ->firstOrFail();

        $this->assertStringContainsString('*SINGGALANG JAYA TRAVEL*', $notification->message);
        $this->assertStringContainsString('*DP DIVERIFIKASI*', $notification->message);
        $this->assertStringContainsString('*Detail Booking*', $notification->message);
        $this->assertStringContainsString('Status : Dikonfirmasi', $notification->message);

        $this->actingAs($admin)
            ->put(route('admin.pembayaran.verify', $pembayaran->id))
            ->assertRedirect(route('admin.pembayaran.index', ['payment_id' => $pembayaran->id]));

        $this->assertSame(
            1,
            WhatsappNotification::where('booking_id', $booking->id)
                ->where('target', '6281299998888')
                ->count()
        );
    }
}
