<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePembayaranRequest;
use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Show the payment form/details.
     */
    public function show($kode)
    {
        $booking = Booking::with(['pelanggan', 'jadwal.rute'])
            ->where('kode_booking', $kode)
            ->firstOrFail();

        if ($booking->pelanggan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Auto transition from booking_dibuat to menunggu_pembayaran
        if ($booking->status_booking === Booking::STATUS_BOOKING_DIBUAT) {
            $booking->update([
                'status_booking' => Booking::STATUS_MENUNGGU_PEMBAYARAN,
            ]);
        }

        // Check if expired
        if ($booking->status_booking === Booking::STATUS_MENUNGGU_PEMBAYARAN) {
            if (now()->greaterThan($booking->batas_bayar_at)) {
                $booking->update([
                    'status_booking' => Booking::STATUS_EXPIRED,
                ]);
            }
        }

        // If booking is already verified or confirmed, redirect to check status
        if ($booking->status_booking !== Booking::STATUS_MENUNGGU_PEMBAYARAN) {
            return view('public.pembayaran.show', [
                'booking' => $booking,
                'paymentSummary' => $this->paymentSummary($booking),
                'isExpired' => $booking->status_booking === Booking::STATUS_EXPIRED,
                'isAlreadyProcessed' => !in_array($booking->status_booking, [Booking::STATUS_MENUNGGU_PEMBAYARAN, Booking::STATUS_EXPIRED]),
            ]);
        }

        $isExpired = false;
        $secondsRemaining = (int) floor(now()->diffInSeconds($booking->batas_bayar_at, false));
        if ($secondsRemaining <= 0) {
            $isExpired = true;
            $secondsRemaining = 0;
            $booking->update([
                'status_booking' => Booking::STATUS_EXPIRED,
            ]);
        }

        return view('public.pembayaran.show', [
            'booking' => $booking,
            'paymentSummary' => $this->paymentSummary($booking),
            'secondsRemaining' => $secondsRemaining,
            'isExpired' => $isExpired,
            'isAlreadyProcessed' => false,
        ]);
    }

    /**
     * Store DP proof of payment.
     */
    public function store(StorePembayaranRequest $request, $kode)
    {
        $booking = Booking::with('pelanggan')
            ->where('kode_booking', $kode)
            ->firstOrFail();

        if ($booking->pelanggan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Verify if booking is still in payment waiting phase and not expired
        if ($booking->status_booking === Booking::STATUS_MENUNGGU_PEMBAYARAN) {
            if (now()->greaterThan($booking->batas_bayar_at)) {
                $booking->update([
                    'status_booking' => Booking::STATUS_EXPIRED,
                ]);
            }
        }

        if ($booking->status_booking === Booking::STATUS_EXPIRED) {
            return redirect()
                ->route('booking.pembayaran', ['kode' => $kode])
                ->with('error', 'Batas waktu pembayaran DP (30 menit) telah kedaluwarsa.');
        }

        if ($booking->status_booking !== Booking::STATUS_MENUNGGU_PEMBAYARAN) {
            return redirect()
                ->route('cek-booking.index', ['kode_booking' => $kode])
                ->with('info', 'Pembayaran booking ini sedang diproses atau sudah diverifikasi.');
        }

        $validated = $request->validated();

        // Upload file
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $timestamp = time();
            $extension = $file->getClientOriginalExtension();
            $fileName = "{$booking->kode_booking}_{$timestamp}.{$extension}";
            
            // Store file to storage/app/public/bukti-pembayaran
            $filePath = $file->storeAs('bukti-pembayaran', $fileName, 'public');

            $jenisPembayaran = $validated['jenis_pembayaran'];
            $isPelunasan = $jenisPembayaran === Pembayaran::JENIS_PELUNASAN;
            $nominalDiskon = $isPelunasan ? Pembayaran::hitungDiskonLunas($booking->total_harga) : 0;
            $jumlahBayar = $isPelunasan
                ? Pembayaran::hitungNominalLunas($booking->total_harga)
                : Pembayaran::NOMINAL_DP;

            // Save Payment record
            Pembayaran::create([
                'booking_id' => $booking->id,
                'jenis_pembayaran' => $jenisPembayaran,
                'jumlah_bayar' => $jumlahBayar,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'bukti_pembayaran' => $filePath,
                'status_pembayaran' => Pembayaran::STATUS_MENUNGGU,
                'voucher_kode' => $isPelunasan ? Pembayaran::VOUCHER_LUNAS_10 : null,
                'diskon_persen' => $isPelunasan ? Pembayaran::DISKON_LUNAS_PERSEN : 0,
                'nominal_diskon' => $nominalDiskon,
                'catatan' => $validated['catatan'] ?? null,
            ]);

            // Update Booking status
            $booking->update([
                'status_booking' => Booking::STATUS_MENUNGGU_VERIFIKASI,
            ]);

            return redirect()
                ->route('cek-booking.index', ['kode_booking' => $booking->kode_booking])
                ->with('success', $isPelunasan
                    ? 'Bukti pembayaran lunas berhasil diunggah. Voucher LUNAS10 10% diterapkan dan menunggu verifikasi admin.'
                    : 'Bukti pembayaran DP berhasil diunggah. Menunggu verifikasi admin.');
        }

        return redirect()
            ->back()
            ->with('error', 'Gagal mengunggah bukti pembayaran.');
    }

    /**
     * Build payment option numbers shown to customers.
     */
    private function paymentSummary(Booking $booking): array
    {
        $discountAmount = Pembayaran::hitungDiskonLunas($booking->total_harga);

        return [
            'dp_amount' => Pembayaran::NOMINAL_DP,
            'dp_remaining' => max(0, $booking->total_harga - Pembayaran::NOMINAL_DP),
            'voucher_code' => Pembayaran::VOUCHER_LUNAS_10,
            'discount_percent' => Pembayaran::DISKON_LUNAS_PERSEN,
            'discount_amount' => $discountAmount,
            'full_payment_amount' => max(0, $booking->total_harga - $discountAmount),
        ];
    }
}
