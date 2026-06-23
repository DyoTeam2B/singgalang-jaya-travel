<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * Generate a unique booking code.
     * Format: SJT-{YYYYMMDD}-{RANDOM5}
     */
    public function generateBookingCode(): string
    {
        $date = now()->format('Ymd');
        
        do {
            $random = strtoupper(Str::random(5));
            $code = "SJT-{$date}-{$random}";
        } while (Booking::where('kode_booking', $code)->exists());

        return $code;
    }

    /**
     * Create a new booking transaction.
     *
     * @param array $data Validated input data
     * @param User $user The authenticated user creating the booking
     * @return Booking
     */
    public function createBooking(array $data, User $user): Booking
    {
        return DB::transaction(function () use ($data, $user) {
            // 1. Get or create Pelanggan profile
            $pelanggan = Pelanggan::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => $data['nama'] ?? $user->name,
                    'no_hp' => $data['no_hp'],
                ]
            );

            // Check for duplicate active booking
            $hasActiveBooking = Booking::where('pelanggan_id', $pelanggan->id)
                ->where('jadwal_id', $data['jadwal_id'])
                ->whereNotIn('status_booking', [
                    Booking::STATUS_CANCELLED,
                    Booking::STATUS_COMPLETED,
                    Booking::STATUS_EXPIRED
                ])
                ->whereDoesntHave('pembayaran', function ($q) {
                    $q->where('status_pembayaran', \App\Models\Pembayaran::STATUS_DITOLAK);
                })
                ->exists();

            if ($hasActiveBooking) {
                throw new \Exception('Anda sudah memiliki booking aktif pada jadwal ini.');
            }

            // 2. Retrieve the schedule and its rute's tarif
            $jadwal = Jadwal::with('rute')->findOrFail($data['jadwal_id']);
            $tarif = $jadwal->rute->tarif;
            
            // Calculate total price
            $totalHarga = $tarif * $data['jumlah_penumpang'];

            // 3. Create the booking record
            $booking = Booking::create([
                'pelanggan_id' => $pelanggan->id,
                'jadwal_id' => $jadwal->id,
                'kode_booking' => $this->generateBookingCode(),
                'alamat_jemput' => $data['alamat_jemput'],
                'latitude_jemput' => $data['latitude_jemput'] ?? null,
                'longitude_jemput' => $data['longitude_jemput'] ?? null,
                'alamat_tujuan' => $data['alamat_tujuan'],
                'latitude_tujuan' => $data['latitude_tujuan'] ?? null,
                'longitude_tujuan' => $data['longitude_tujuan'] ?? null,
                'jumlah_penumpang' => $data['jumlah_penumpang'],
                'total_harga' => $totalHarga,
                'status_booking' => Booking::STATUS_BOOKING_DIBUAT,
            ]);

            return $booking;
        });
    }

    /**
     * Calculate total price for a schedule and passenger count.
     */
    public function calculateTotal(int $jadwalId, int $jumlahPenumpang): int
    {
        $jadwal = Jadwal::with('rute')->findOrFail($jadwalId);
        return $jadwal->rute->tarif * $jumlahPenumpang;
    }
}
