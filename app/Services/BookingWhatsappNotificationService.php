<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\WhatsappNotification;

class BookingWhatsappNotificationService
{
    public function __construct(private FonnteService $fonnteService)
    {
    }

    public function sendDpVerifiedToCustomer(Booking $booking): bool
    {
        $booking->loadMissing(['pelanggan', 'jadwal.rute']);

        if (! $booking->pelanggan?->no_hp) {
            return false;
        }

        return $this->fonnteService->send(
            $booking->pelanggan->no_hp,
            $this->buildDpVerifiedMessage($booking),
            WhatsappNotification::TYPE_CUSTOM,
            $booking->id
        );
    }

    public function sendTripAssignedToCustomer(Booking $booking, Trip $trip): bool
    {
        $booking->loadMissing(['pelanggan', 'jadwal.rute']);
        $trip->loadMissing(['driver', 'armada', 'jadwal.rute']);

        if (! $booking->pelanggan?->no_hp) {
            return false;
        }

        return $this->fonnteService->send(
            $booking->pelanggan->no_hp,
            $this->buildCustomerTripAssignedMessage($booking, $trip),
            WhatsappNotification::TYPE_CUSTOM,
            $booking->id
        );
    }

    public function sendTripAssignedToDriver(Booking $booking, Trip $trip): bool
    {
        $booking->loadMissing(['pelanggan', 'jadwal.rute']);
        $trip->loadMissing(['driver', 'armada', 'jadwal.rute']);

        if (! $trip->driver?->no_hp) {
            return false;
        }

        return $this->fonnteService->send(
            $trip->driver->no_hp,
            $this->buildDriverTripAssignedMessage($booking, $trip),
            WhatsappNotification::TYPE_CUSTOM,
            $booking->id
        );
    }

    private function buildDpVerifiedMessage(Booking $booking): string
    {
        return implode("\n", [
            '🚗 *SINGGALANG JAYA TRAVEL* 🚗',
            '━━━━━━━━━━━━━━━━━━━━━━━━',
            '*PEMBAYARAN DP DIVERIFIKASI*',
            '',
            "Halo *{$booking->pelanggan->nama}*,",
            'Pembayaran uang muka (DP) Anda untuk pemesanan berikut telah berhasil diverifikasi oleh Admin.',
            '',
            '*📋 DETAIL BOOKING:*',
            "• *Kode Booking* : `{$booking->kode_booking}`",
            "• *Rute*         : {$this->routeText($booking)}",
            "• *Jadwal*       : {$this->scheduleText($booking)}",
            '• *Status*       : *Dikonfirmasi* (DP Lunas)',
            '━━━━━━━━━━━━━━━━━━━━━━━━',
            'Pemesanan Anda akan segera dimasukkan ke dalam manifest perjalanan (Trip). Detail driver dan nomor plat armada akan dikirimkan kepada Anda setelah ditentukan oleh Admin.',
            '',
            'Terima kasih telah memilih kami,',
            '*Singgalang Jaya Travel*',
        ]);
    }

    private function buildCustomerTripAssignedMessage(Booking $booking, Trip $trip): string
    {
        return implode("\n", [
            '🚗 *SINGGALANG JAYA TRAVEL* 🚗',
            '━━━━━━━━━━━━━━━━━━━━━━━━',
            '*JADWAL PERJALANAN (TRIP) DIKONFIRMASI*',
            '',
            "Halo *{$booking->pelanggan->nama}*,",
            'Detail perjalanan Anda telah ditentukan. Driver kami akan melakukan penjemputan sesuai jadwal berikut:',
            '',
            '*📋 DETAIL PERJALANAN:*',
            "• *Kode Booking* : `{$booking->kode_booking}`",
            "• *Rute*         : {$this->routeText($booking)}",
            "• *Jadwal*       : {$this->scheduleText($booking)}",
            "• *Penumpang*    : {$booking->jumlah_penumpang} orang",
            '',
            '*👤 DETAIL DRIVER & ARMADA:*',
            "• *Nama Driver*  : *{$trip->driver->nama_driver}*",
            "• *No. HP/WA*    : {$trip->driver->no_hp}",
            "• *Armada*       : {$this->armadaText($trip)}",
            '',
            '*📍 ALAMAT LOKASI:*',
            "• *Penjemputan*  : {$booking->alamat_jemput}",
            "• *Tujuan*        : {$booking->alamat_tujuan}",
            '━━━━━━━━━━━━━━━━━━━━━━━━',
            'Mohon bersiap di lokasi penjemputan sesuai jadwal dan pastikan nomor HP Anda selalu aktif agar driver mudah berkoordinasi.',
            '',
            'Selamat menikmati perjalanan Anda,',
            '*Singgalang Jaya Travel*',
        ]);
    }

    private function buildDriverTripAssignedMessage(Booking $booking, Trip $trip): string
    {
        return implode("\n", [
            '🔔 *SINGGALANG JAYA TRAVEL* 🔔',
            '━━━━━━━━━━━━━━━━━━━━━━━━',
            '*PENUGASAN BOOKING BARU*',
            '',
            "Halo *{$trip->driver->nama_driver}*,",
            'Ada booking pelanggan baru yang telah dimasukkan ke dalam manifest perjalanan Anda.',
            '',
            '*📋 DETAIL TRIP:*',
            "• *Kode Booking* : `{$booking->kode_booking}`",
            "• *Rute*         : {$this->routeText($booking)}",
            "• *Jadwal*       : {$this->scheduleText($booking)}",
            "• *Armada*       : {$this->armadaText($trip)}",
            '',
            '*👤 DATA PELANGGAN:*',
            "• *Nama*         : *{$booking->pelanggan->nama}*",
            "• *No. HP/WA*     : {$booking->pelanggan->no_hp}",
            "• *Penumpang*    : {$booking->jumlah_penumpang} orang",
            '',
            '*📍 ALAMAT LOKASI:*',
            "• *Titik Jemput*  : {$booking->alamat_jemput}",
            "• *Titik Tujuan*  : {$booking->alamat_tujuan}",
            '━━━━━━━━━━━━━━━━━━━━━━━━',
            'Silakan hubungi pelanggan untuk konfirmasi penjemputan, dan cek manifest trip lengkap di Dashboard Aplikasi Driver.',
            '',
            'Utamakan keselamatan berkendara!',
            '*Singgalang Jaya Travel*',
        ]);
    }

    private function routeText(Booking $booking): string
    {
        return ($booking->jadwal->rute->asal ?? '-') . ' ↔ ' . ($booking->jadwal->rute->tujuan ?? '-');
    }

    private function scheduleText(Booking $booking): string
    {
        $date = $booking->jadwal->tanggal_keberangkatan?->format('d/m/Y') ?? '-';
        $shift = ucfirst($booking->jadwal->shift ?? '-');
        $time = $booking->jadwal->jam_berangkat?->format('H:i') ?? '-';

        return "{$date} - {$shift} ({$time} WIB)";
    }

    private function driverText(Trip $trip): string
    {
        $name = $trip->driver?->nama_driver ?? '-';
        $phone = $trip->driver?->no_hp ?? '-';

        return "{$name} ({$phone})";
    }

    private function armadaText(Trip $trip): string
    {
        if (! $trip->armada) {
            return '-';
        }

        return "{$trip->armada->nama_mobil} (`{$trip->armada->nomor_plat}`)";
    }
}
