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
        $today = now()->toDateString();
        $currentTime = now()->toTimeString();

        $schedules = Jadwal::with('rute')
            ->withSum(['bookings as booked_seats' => function ($query) {
                $query->whereNotIn('status_booking', [Booking::STATUS_CANCELLED, Booking::STATUS_EXPIRED]);
            }], 'jumlah_penumpang')
            ->aktif()
            ->where(function ($q) use ($today, $currentTime) {
                $q->where('tanggal_keberangkatan', '>', $today)
                  ->orWhere(function ($sq) use ($today, $currentTime) {
                      $sq->where('tanggal_keberangkatan', $today)
                         ->where('jam_berangkat', '>=', $currentTime);
                  });
            })
            ->orderBy('tanggal_keberangkatan', 'asc')
            ->orderBy('jam_berangkat', 'asc')
            ->get();

        $drivers = \App\Models\Driver::where('status_driver', 'aktif')->take(4)->get();

        return view('public.home', compact('schedules', 'drivers'));
    }
}
