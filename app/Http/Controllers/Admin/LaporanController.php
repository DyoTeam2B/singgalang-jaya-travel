<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Trip;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Display the report page with statistics, chart data, and daily breakdown.
     */
    public function index(Request $request)
    {
        // Determine date range from filter
        $period = $request->get('period', '7days');
        $shift = $request->get('shift', 'semua');

        $dateRange = $this->getDateRange($period, $request);

        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // Base query scoping by jadwal shift if filtered
        $bookingQuery = Booking::query()
            ->whereBetween('bookings.created_at', [$startDate, $endDate]);

        $tripQuery = Trip::query()
            ->whereHas('jadwal', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_keberangkatan', [$startDate->toDateString(), $endDate->toDateString()]);
            });

        if ($shift !== 'semua') {
            $bookingQuery->whereHas('jadwal', fn($q) => $q->where('shift', $shift));
            $tripQuery->whereHas('jadwal', fn($q) => $q->where('shift', $shift));
        }

        // Summary cards
        $totalBookings = (clone $bookingQuery)->count();
        $totalPassengers = (clone $bookingQuery)->sum('jumlah_penumpang');
        $totalRevenue = (clone $bookingQuery)
            ->where('status_booking', Booking::STATUS_COMPLETED)
            ->sum('total_harga');
        $totalTrips = (clone $tripQuery)->count();
        $completedTrips = (clone $tripQuery)->where('status_trip', Trip::STATUS_COMPLETED)->count();

        // Calculate average occupancy
        $avgOccupancy = 0;
        if ($completedTrips > 0) {
            $completedTripIds = (clone $tripQuery)
                ->where('status_trip', Trip::STATUS_COMPLETED)
                ->pluck('trips.id');

            $totalCapacity = Trip::whereIn('trips.id', $completedTripIds)
                ->join('armada', 'trips.armada_id', '=', 'armada.id')
                ->sum('armada.kapasitas');

            $totalPassengersInTrips = \App\Models\DetailTrip::whereIn('trip_id', $completedTripIds)->count();

            if ($totalCapacity > 0) {
                $avgOccupancy = round(($totalPassengersInTrips / $totalCapacity) * 100);
            }
        }

        // Revenue chart data (daily)
        $chartData = Booking::where('status_booking', Booking::STATUS_COMPLETED)
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->when($shift !== 'semua', function ($q) use ($shift) {
                $q->whereHas('jadwal', fn($jq) => $jq->where('shift', $shift));
            })
            ->select(
                DB::raw('DATE(bookings.created_at) as date'),
                DB::raw('SUM(total_harga) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = $chartData->pluck('date')->map(fn($d) => Carbon::parse($d)->translatedFormat('d M'))->toArray();
        $chartValues = $chartData->pluck('revenue')->toArray();

        // Daily report breakdown
        $dailyReports = Booking::query()
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->when($shift !== 'semua', function ($q) use ($shift) {
                $q->whereHas('jadwal', fn($jq) => $jq->where('shift', $shift));
            })
            ->select(
                DB::raw('DATE(bookings.created_at) as report_date'),
                DB::raw('COUNT(*) as total_booking'),
                DB::raw('SUM(jumlah_penumpang) as total_passengers'),
                DB::raw("SUM(CASE WHEN status_booking = 'completed' THEN total_harga ELSE 0 END) as revenue"),
                DB::raw("SUM(CASE WHEN status_booking = 'cancelled' THEN 1 ELSE 0 END) as cancelled")
            )
            ->groupBy('report_date')
            ->orderByDesc('report_date')
            ->get();

        // Enrich daily reports with trip count and DP/pelunasan breakdown
        foreach ($dailyReports as $report) {
            $reportDate = $report->report_date;

            $report->total_trip = Trip::whereHas('jadwal', function ($q) use ($reportDate) {
                $q->where('tanggal_keberangkatan', $reportDate);
            })->when($shift !== 'semua', function ($q) use ($shift) {
                $q->whereHas('jadwal', fn($jq) => $jq->where('shift', $shift));
            })->count();

            // DP collected (pembayaran type 'dp' and verified)
            $report->dp_revenue = Pembayaran::where('jenis_pembayaran', 'dp')
                ->where('status_pembayaran', 'terverifikasi')
                ->whereHas('booking', function ($q) use ($reportDate) {
                    $q->whereDate('created_at', $reportDate);
                })
                ->sum('jumlah_bayar');

            // Pelunasan collected
            $report->pelunasan_revenue = Pembayaran::where('jenis_pembayaran', 'pelunasan')
                ->where('status_pembayaran', 'terverifikasi')
                ->whereHas('booking', function ($q) use ($reportDate) {
                    $q->whereDate('created_at', $reportDate);
                })
                ->sum('jumlah_bayar');
        }

        // Trip summary for detail modal (all trips in date range)
        $tripSummary = Trip::with(['jadwal.rute', 'driver', 'armada', 'detailTrips'])
            ->whereHas('jadwal', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_keberangkatan', [$startDate->toDateString(), $endDate->toDateString()]);
            })
            ->when($shift !== 'semua', function ($q) use ($shift) {
                $q->whereHas('jadwal', fn($jq) => $jq->where('shift', $shift));
            })
            ->orderByDesc('created_at')
            ->get();

        // Period label for display
        $periodLabel = $this->getPeriodLabel($period, $request);

        return view('admin.laporan.index', compact(
            'totalBookings',
            'totalPassengers',
            'totalRevenue',
            'totalTrips',
            'avgOccupancy',
            'chartLabels',
            'chartValues',
            'dailyReports',
            'tripSummary',
            'period',
            'shift',
            'periodLabel',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export report as downloadable file (placeholder for Sprint 4).
     */
    public function export(Request $request)
    {
        // TODO: Implement PDF/Excel export in Sprint 4
        return back()->with('info', 'Fitur export laporan akan tersedia pada update berikutnya.');
    }

    /**
     * Get date range based on period filter.
     */
    private function getDateRange(string $period, Request $request): array
    {
        return match ($period) {
            'today' => [
                'start' => Carbon::today()->startOfDay(),
                'end' => Carbon::today()->endOfDay(),
            ],
            '30days' => [
                'start' => Carbon::today()->subDays(29)->startOfDay(),
                'end' => Carbon::today()->endOfDay(),
            ],
            'custom' => [
                'start' => Carbon::parse($request->get('start_date', Carbon::today()->subDays(6)->toDateString()))->startOfDay(),
                'end' => Carbon::parse($request->get('end_date', Carbon::today()->toDateString()))->endOfDay(),
            ],
            default => [ // 7days
                'start' => Carbon::today()->subDays(6)->startOfDay(),
                'end' => Carbon::today()->endOfDay(),
            ],
        };
    }

    /**
     * Get human-readable period label.
     */
    private function getPeriodLabel(string $period, Request $request): string
    {
        return match ($period) {
            'today' => 'Hari Ini',
            '30days' => 'Bulan Ini',
            'custom' => Carbon::parse($request->get('start_date'))->format('d M Y') . ' - ' . Carbon::parse($request->get('end_date'))->format('d M Y'),
            default => '7 Hari Terakhir',
        };
    }
}
