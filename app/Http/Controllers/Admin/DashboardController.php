<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $totalBookings = \App\Models\Booking::count();
        $pendingVerification = \App\Models\Booking::where('status_booking', \App\Models\Booking::STATUS_MENUNGGU_VERIFIKASI)->count();
        $activeTrips = \App\Models\Trip::where('status_trip', 'berjalan')->count();
        $totalRevenue = \App\Models\Booking::where('status_booking', \App\Models\Booking::STATUS_SELESAI)->sum('total_harga');
        
        $recentBookings = \App\Models\Booking::with(['pelanggan', 'jadwal.rute'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBookings',
            'pendingVerification',
            'activeTrips',
            'totalRevenue',
            'recentBookings'
        ));
    }
}
