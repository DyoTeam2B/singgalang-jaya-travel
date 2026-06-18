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
            '*SINGGALANG JAYA TRAVEL*',
            '*DP DIVERIFIKASI*',
            '',
            "Halo {$booking->pelanggan->nama},",
            '',
            "Pembayaran DP untuk booking *{$booking->kode_booking}* sudah diverifikasi oleh admin.",
            '',
            '*Detail Booking*',
            "Kode   : {$booking->kode_booking}",
            'Status : Dikonfirmasi',
            "Rute   : {$this->routeText($booking)}",
            "Jadwal : {$this->scheduleText($booking)}",
            '',
            'Booking Anda akan dimasukkan ke trip oleh admin. Informasi driver dan armada akan dikirim setelah trip ditentukan.',
            '',
            'Terima kasih,',
            'Singgalang Jaya Travel',
        ]);
    }

    private function buildCustomerTripAssignedMessage(Booking $booking, Trip $trip): string
    {
        return implode("\n", [
            '*SINGGALANG JAYA TRAVEL*',
            '*TRIP SUDAH DITENTUKAN*',
            '',
            "Halo {$booking->pelanggan->nama},",
            '',
            "Booking *{$booking->kode_booking}* sudah masuk ke trip.",
            '',
            '*Detail Trip*',
            "Kode      : {$booking->kode_booking}",
            "Rute      : {$this->routeText($booking)}",
            "Jadwal    : {$this->scheduleText($booking)}",
            "Driver    : {$this->driverText($trip)}",
            "Armada    : {$this->armadaText($trip)}",
            "Penumpang : {$booking->jumlah_penumpang} orang",
            '',
            '*Penjemputan*',
            "Alamat : {$booking->alamat_jemput}",
            "Tujuan : {$booking->alamat_tujuan}",
            '',
            'Mohon bersiap di lokasi penjemputan sesuai jadwal.',
            '',
            'Terima kasih,',
            'Singgalang Jaya Travel',
        ]);
    }

    private function buildDriverTripAssignedMessage(Booking $booking, Trip $trip): string
    {
        return implode("\n", [
            '*SINGGALANG JAYA TRAVEL*',
            '*BOOKING BARU DI TRIP*',
            '',
            "Halo {$trip->driver->nama_driver},",
            '',
            'Ada booking baru yang masuk ke trip Anda.',
            '',
            '*Detail Trip*',
            "Kode      : {$booking->kode_booking}",
            "Rute      : {$this->routeText($booking)}",
            "Jadwal    : {$this->scheduleText($booking)}",
            "Armada    : {$this->armadaText($trip)}",
            '',
            '*Data Pelanggan*',
            "Nama      : {$booking->pelanggan->nama}",
            "No. HP    : {$booking->pelanggan->no_hp}",
            "Penumpang : {$booking->jumlah_penumpang} orang",
            "Jemput    : {$booking->alamat_jemput}",
            "Tujuan    : {$booking->alamat_tujuan}",
            '',
            'Silakan cek manifest trip di dashboard driver.',
            '',
            'Terima kasih,',
            'Singgalang Jaya Travel',
        ]);
    }

    private function routeText(Booking $booking): string
    {
        return ($booking->jadwal->rute->asal ?? '-') . ' -> ' . ($booking->jadwal->rute->tujuan ?? '-');
    }

    private function scheduleText(Booking $booking): string
    {
        $date = $booking->jadwal->tanggal_keberangkatan?->format('d/m/Y') ?? '-';
        $shift = ucfirst($booking->jadwal->shift ?? '-');
        $time = $booking->jadwal->jam_berangkat?->format('H:i') ?? '-';

        return "{$date} - {$shift} {$time} WIB";
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

        return "{$trip->armada->nama_mobil} ({$trip->armada->nomor_plat})";
    }
}
