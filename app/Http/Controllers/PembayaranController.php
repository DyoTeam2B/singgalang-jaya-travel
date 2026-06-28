<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePembayaranRequest;
use App\Models\Booking;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    /**
     * Show the payment form/details.
     */
    public function show($kode)
    {
        $booking = Booking::with(['pelanggan', 'jadwal.rute', 'pembayaran' => fn ($query) => $query->latest()])
            ->where('kode_booking', $kode)
            ->first();

        if (!$booking) {
            return redirect()
                ->route('booking.index')
                ->with('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');
        }

        if ($booking->pelanggan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->expired_at && $booking->expired_at->isPast()) {
            $bookingService = app(\App\Services\BookingService::class);
            $bookingService->expireBooking($booking);

            return redirect()
                ->route('booking.index')
                ->with('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');
        }

        return view('public.pembayaran.show', [
            'booking' => $booking,
            'isExpired' => false,
            'isAlreadyProcessed' => $booking->status_booking !== Booking::STATUS_BOOKING_DIBUAT,
        ]);
    }

    /**
     * Store DP proof of payment.
     */
    public function store(StorePembayaranRequest $request, $kode)
    {
        $booking = Booking::with(['pelanggan', 'jadwal.rute'])
            ->where('kode_booking', $kode)
            ->first();

        if (!$booking) {
            return redirect()
                ->route('booking.index')
                ->with('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');
        }

        if ($booking->pelanggan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->expired_at && $booking->expired_at->isPast()) {
            $bookingService = app(\App\Services\BookingService::class);
            $bookingService->expireBooking($booking);

            return redirect()
                ->route('booking.index')
                ->with('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');
        }

        if ($booking->status_booking !== Booking::STATUS_BOOKING_DIBUAT) {
            return redirect()
                ->route('booking.show', ['kode' => $kode])
                ->with('info', 'Pembayaran booking ini sedang diproses atau sudah diverifikasi.');
        }

        $file = $request->file('bukti_pembayaran');
        $fileName = "{$booking->kode_booking}_" . time() . ".{$file->getClientOriginalExtension()}";
        $filePath = $file->storeAs('bukti-pembayaran', $fileName, 'public');

        Pembayaran::create([
            'booking_id' => $booking->id,
            'jenis_pembayaran' => Pembayaran::JENIS_DP,
            'jumlah_bayar' => 50000,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran' => $filePath,
            'status_pembayaran' => Pembayaran::STATUS_MENUNGGU,
            'catatan' => $request->catatan,
        ]);

        $booking->update([
            'status_booking' => Booking::STATUS_MENUNGGU_VERIFIKASI,
        ]);

        return redirect()
            ->route('booking.show', ['kode' => $booking->kode_booking])
            ->with('success', 'Bukti pembayaran DP berhasil diunggah. Menunggu verifikasi admin.');
    }
}