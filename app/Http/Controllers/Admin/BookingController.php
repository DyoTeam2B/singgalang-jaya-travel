<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.bookings.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $booking->load([
            'pelanggan.user',
            'jadwal.rute',
            'pembayaran' => function ($q) {
                $q->latest();
            },
            'whatsappNotifications',
        ]);
        
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Cancel the specified booking.
     */
    public function cancel(Request $request, Booking $booking)
    {
        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500',
        ], [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $booking->update([
            'status_booking' => Booking::STATUS_CANCELLED,
            'alasan_pembatalan' => $request->alasan_pembatalan,
        ]);

        return redirect()
            ->route('admin.bookings.show', $booking->id)
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
