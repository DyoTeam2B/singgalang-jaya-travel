<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Booking;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the landing page with active schedules.
     */
    public function index()
    {
        $schedules = Jadwal::with('rute')
            ->withSum(['bookings as booked_seats' => function ($query) {
                $query->where('status_booking', '!=', Booking::STATUS_DIBATALKAN);
            }], 'jumlah_penumpang')
            ->aktif()
            ->orderBy('tanggal_keberangkatan', 'asc')
            ->orderBy('jam_berangkat', 'asc')
            ->get();

        return view('public.home', compact('schedules'));
    }
}
