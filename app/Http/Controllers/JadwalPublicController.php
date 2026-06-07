<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalPublicController extends Controller
{
    /**
     * Display public schedules with optional filtering.
     */
    public function index(Request $request)
    {
        $query = Jadwal::with('rute')->where('status_jadwal', 'aktif');

        if ($request->filled('asal')) {
            $query->whereHas('rute', function ($q) use ($request) {
                $q->where('asal', 'like', '%' . $request->asal . '%');
            });
        }

        if ($request->filled('tujuan')) {
            $query->whereHas('rute', function ($q) use ($request) {
                $q->where('tujuan', 'like', '%' . $request->tujuan . '%');
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_keberangkatan', $request->tanggal);
        }

        $schedules = $query->orderBy('tanggal_keberangkatan', 'asc')
            ->orderBy('jam_berangkat', 'asc')
            ->get();

        return view('public.jadwal.index', compact('schedules'));
    }
}
