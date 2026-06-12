<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Booking;
use App\Http\Requests\SearchJadwalRequest;

class JadwalPublicController extends Controller
{
    /**
     * Display public schedules with optional filtering.
     */
    public function index(SearchJadwalRequest $request)
    {
        $validated = $request->validated();

        $query = Jadwal::with('rute')
            ->withSum(['bookings as booked_seats' => function ($q) {
                $q->whereNotIn('status_booking', [Booking::STATUS_CANCELLED, Booking::STATUS_EXPIRED]);
            }], 'jumlah_penumpang')
            ->aktif();

        if (!empty($validated['asal'])) {
            $query->whereHas('rute', function ($q) use ($validated) {
                $q->where('asal', 'like', '%' . $validated['asal'] . '%');
            });
        }

        if (!empty($validated['tujuan'])) {
            $query->whereHas('rute', function ($q) use ($validated) {
                $q->where('tujuan', 'like', '%' . $validated['tujuan'] . '%');
            });
        }

        if (!empty($validated['tanggal'])) {
            $query->whereDate('tanggal_keberangkatan', $validated['tanggal']);
        }

        $schedules = $query->orderBy('tanggal_keberangkatan', 'asc')
            ->orderBy('jam_berangkat', 'asc')
            ->get();

        return view('public.jadwal.index', compact('schedules'));
    }
}
