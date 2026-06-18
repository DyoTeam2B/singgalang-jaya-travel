<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Models\Rute;
use App\Models\User;
use App\Models\WhatsappNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCancellationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cancel_booking_sends_formatted_whatsapp_to_admin(): void
    {
        config([
            'services.fonnte.token' => null,
            'services.fonnte.admin_number' => '081111222333',
        ]);

        $customerUser = User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi-cancel@test.com',
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
            'kode_booking' => 'SJT-CANCEL-12345',
            'alamat_jemput' => 'Padang Panjang City Center',
            'alamat_tujuan' => 'Pekanbaru Airport',
            'jumlah_penumpang' => 2,
            'total_harga' => 300000,
            'status_booking' => Booking::STATUS_DIKONFIRMASI,
        ]);

        $response = $this->actingAs($customerUser)
            ->put(route('booking.cancel', $booking->kode_booking), [
                'alasan_pembatalan' => 'Jadwal pelanggan berubah.',
            ]);

        $response->assertRedirect(route('booking.show', ['kode' => $booking->kode_booking]));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status_booking' => Booking::STATUS_CANCELLED,
        ]);

        $notification = WhatsappNotification::where('booking_id', $booking->id)
            ->where('target', '6281111222333')
            ->where('type', WhatsappNotification::TYPE_PEMBATALAN_BOOKING)
            ->firstOrFail();

        $this->assertSame(WhatsappNotification::STATUS_SENT, $notification->status);
        $this->assertStringContainsString('*BOOKING DIBATALKAN*', $notification->message);
        $this->assertStringContainsString('*Detail Booking*', $notification->message);
        $this->assertStringContainsString('*Alasan Pembatalan*', $notification->message);
        $this->assertStringContainsString('Jadwal pelanggan berubah.', $notification->message);
    }
}
