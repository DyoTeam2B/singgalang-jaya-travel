<?php

namespace App\Http\Controllers;

use App\Http\Requests\CekBookingRequest;
use App\Models\Booking;
use Illuminate\Http\Request;

class CekBookingController extends Controller
{
    /**
     * Display the search booking form or booking details if code is provided.
     */
    public function index(Request $request)
    {
        $kode = $request->query('kode_booking');

        if ($kode) {
            $booking = Booking::with([
                'pelanggan.user',
                'jadwal.rute',
                'pembayaran' => function ($q) {
                    $q->latest();
                },
                'detailTrips.trip.driver'
            ])
            ->where('kode_booking', $kode)
            ->first();

            if (!$booking) {
                return redirect()
                    ->route('cek-booking.index')
                    ->with('error', 'Kode booking tidak ditemukan.');
            }

            // Get payment status for display
            $latestPayment = $booking->pembayaran->first();

            return view('public.cek-booking.show', compact('booking', 'latestPayment'));
        }

        return view('public.cek-booking.index');
    }

    /**
     * Handle the booking search form submission.
     */
    public function show(CekBookingRequest $request)
    {
        return redirect()->route('cek-booking.index', [
            'kode_booking' => $request->validated()['kode_booking']
        ]);
    }
}
