<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the landing page with active schedules.
     */
    public function index()
    {
        $schedules = Jadwal::with('rute')
            ->where('status_jadwal', 'aktif')
            ->orderBy('tanggal_keberangkatan', 'asc')
            ->orderBy('jam_berangkat', 'asc')
            ->get();

        return view('public.home', compact('schedules'));
    }
}
