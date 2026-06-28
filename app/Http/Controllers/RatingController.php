<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Store a newly created rating in storage.
     */
    public function store(Request $request, $kode)
    {
        $booking = Booking::with('pelanggan')
            ->where('kode_booking', $kode)
            ->first();

        if (!$booking) {
            return redirect()
                ->route('booking.index')
                ->with('error', 'Booking tidak ditemukan.');
        }

        if ($booking->pelanggan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($booking->status_booking !== Booking::STATUS_COMPLETED) {
            return redirect()
                ->back()
                ->with('error', 'Rating hanya dapat diberikan setelah perjalanan selesai.');
        }

        if ($booking->rating()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Anda sudah memberikan rating untuk perjalanan ini.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'ulasan' => ['required', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Rating bintang wajib diisi.',
            'rating.integer' => 'Rating bintang harus berupa angka.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'ulasan.required' => 'Ulasan wajib diisi.',
            'ulasan.max' => 'Ulasan maksimal 1000 karakter.',
        ]);

        Rating::create([
            'booking_id' => $booking->id,
            'pelanggan_id' => $booking->pelanggan_id,
            'rating' => $validated['rating'],
            'ulasan' => $validated['ulasan'],
            'status' => Rating::STATUS_MENUNGGU,
        ]);

        return redirect()
            ->route('booking.show', ['kode' => $kode])
            ->with('success', 'Terima kasih atas ulasan Anda! Ulasan Anda sedang menunggu persetujuan admin.');
    }
}
