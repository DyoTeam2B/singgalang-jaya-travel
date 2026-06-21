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
        $period = $request->get('period', '7days');
        $shift = $request->get('shift', 'semua');

        $dateRange = $this->getDateRange($period, $request);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        $bookingQuery = $this->baseBookingQuery($startDate, $endDate, $shift);
        $tripQuery = $this->baseTripQuery($startDate, $endDate, $shift);

        $totalBookings = (clone $bookingQuery)->count();
        $totalPassengers = (clone $bookingQuery)->sum('jumlah_penumpang');
        $totalRevenue = (clone $bookingQuery)
            ->where('status_booking', Booking::STATUS_COMPLETED)
            ->sum('total_harga');
        $totalTrips = (clone $tripQuery)->count();
        $avgOccupancy = $this->calculateAverageOccupancy($tripQuery);

        $chartData = $this->baseBookingQuery($startDate, $endDate, $shift)
            ->where('status_booking', Booking::STATUS_COMPLETED)
            ->select(
                DB::raw('DATE(bookings.created_at) as date'),
                DB::raw('SUM(total_harga) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = $chartData->pluck('date')->map(fn($d) => Carbon::parse($d)->translatedFormat('d M'))->toArray();
        $chartValues = $chartData->pluck('revenue')->toArray();
        $dailyReports = $this->buildDailyReports($startDate, $endDate, $shift);
        $tripSummary = $this->buildTripSummary($startDate, $endDate, $shift);
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
     * Export report as CSV compatible with spreadsheet apps.
     */
    public function export(Request $request)
    {
        $period = $request->get('period', $request->get('export_period', '7days'));
        $shift = $request->get('shift', 'semua');

        $dateRange = $this->getDateRange($period, $request);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        $bookingQuery = $this->baseBookingQuery($startDate, $endDate, $shift);
        $tripQuery = $this->baseTripQuery($startDate, $endDate, $shift);
        $dailyReports = $this->buildDailyReports($startDate, $endDate, $shift);
        $tripSummary = $this->buildTripSummary($startDate, $endDate, $shift);
        $periodLabel = $this->getPeriodLabel($period, $request);

        $summary = [
            'total_bookings' => (clone $bookingQuery)->count(),
            'total_passengers' => (clone $bookingQuery)->sum('jumlah_penumpang'),
            'total_revenue' => (clone $bookingQuery)->where('status_booking', Booking::STATUS_COMPLETED)->sum('total_harga'),
            'total_trips' => (clone $tripQuery)->count(),
            'completed_trips' => (clone $tripQuery)->where('status_trip', Trip::STATUS_COMPLETED)->count(),
            'avg_occupancy' => $this->calculateAverageOccupancy($tripQuery),
        ];

        $filename = 'laporan-keuangan-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($periodLabel, $shift, $startDate, $endDate, $summary, $dailyReports, $tripSummary) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Laporan Keuangan Singgalang Jaya Travel']);
            fputcsv($handle, ['Periode', $periodLabel]);
            fputcsv($handle, ['Tanggal Mulai', $startDate->format('Y-m-d')]);
            fputcsv($handle, ['Tanggal Akhir', $endDate->format('Y-m-d')]);
            fputcsv($handle, ['Shift', $shift === 'semua' ? 'Semua Shift' : ucfirst($shift)]);
            fputcsv($handle, ['Diexport Pada', now()->format('Y-m-d H:i:s')]);
            fwrite($handle, "\n");

            fputcsv($handle, ['Ringkasan']);
            fputcsv($handle, ['Total Booking', $summary['total_bookings']]);
            fputcsv($handle, ['Total Penumpang', $summary['total_passengers']]);
            fputcsv($handle, ['Total Pendapatan Completed', $summary['total_revenue']]);
            fputcsv($handle, ['Total Trip', $summary['total_trips']]);
            fputcsv($handle, ['Trip Selesai', $summary['completed_trips']]);
            fputcsv($handle, ['Okupansi Rata-rata (%)', $summary['avg_occupancy']]);
            fwrite($handle, "\n");

            fputcsv($handle, ['Rincian Harian']);
            fputcsv($handle, ['Tanggal', 'Booking', 'Penumpang', 'Trip', 'Pendapatan', 'DP Terverifikasi', 'Pelunasan Terverifikasi', 'Booking Batal']);

            foreach ($dailyReports as $report) {
                fputcsv($handle, [
                    $report->report_date,
                    $report->total_booking,
                    $report->total_passengers ?? 0,
                    $report->total_trip,
                    $report->revenue,
                    $report->dp_revenue,
                    $report->pelunasan_revenue,
                    $report->cancelled,
                ]);
            }

            fwrite($handle, "\n");
            fputcsv($handle, ['Rangkuman Trip']);
            fputcsv($handle, ['Trip', 'Tanggal', 'Jam', 'Rute', 'Shift', 'Driver', 'Armada', 'Pax Manifest', 'Status']);

            foreach ($tripSummary as $trip) {
                $jadwal = $trip->jadwal;
                $rute = $jadwal?->rute;
                $armada = $trip->armada;

                fputcsv($handle, [
                    'TRP-'.str_pad((string) $trip->id, 3, '0', STR_PAD_LEFT),
                    $jadwal?->tanggal_keberangkatan?->format('Y-m-d') ?? '-',
                    $jadwal?->jam_berangkat?->format('H:i') ?? '-',
                    $rute ? "{$rute->asal} -> {$rute->tujuan}" : '-',
                    $jadwal?->shift ? ucfirst($jadwal->shift) : '-',
                    $trip->driver?->nama_driver ?? '-',
                    $armada ? "{$armada->nama_mobil} ({$armada->nomor_plat})" : '-',
                    $trip->detailTrips->count(),
                    $trip->status_trip,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function baseBookingQuery(Carbon $startDate, Carbon $endDate, string $shift)
    {
        return Booking::query()
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->when($shift !== 'semua', function ($query) use ($shift) {
                $query->whereHas('jadwal', fn($jadwalQuery) => $jadwalQuery->where('shift', $shift));
            });
    }

    private function baseTripQuery(Carbon $startDate, Carbon $endDate, string $shift)
    {
        return Trip::query()
            ->whereHas('jadwal', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_keberangkatan', [$startDate->toDateString(), $endDate->toDateString()]);
            })
            ->when($shift !== 'semua', function ($query) use ($shift) {
                $query->whereHas('jadwal', fn($jadwalQuery) => $jadwalQuery->where('shift', $shift));
            });
    }

    private function calculateAverageOccupancy($tripQuery): int
    {
        $completedTripIds = (clone $tripQuery)
            ->where('status_trip', Trip::STATUS_COMPLETED)
            ->pluck('trips.id');

        if ($completedTripIds->isEmpty()) {
            return 0;
        }

        $totalCapacity = Trip::whereIn('trips.id', $completedTripIds)
            ->join('armada', 'trips.armada_id', '=', 'armada.id')
            ->sum('armada.kapasitas');

        $totalPassengersInTrips = \App\Models\DetailTrip::whereIn('trip_id', $completedTripIds)->count();

        if ($totalCapacity <= 0) {
            return 0;
        }

        return (int) round(($totalPassengersInTrips / $totalCapacity) * 100);
    }

    private function buildDailyReports(Carbon $startDate, Carbon $endDate, string $shift)
    {
        $dailyReports = $this->baseBookingQuery($startDate, $endDate, $shift)
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

        foreach ($dailyReports as $report) {
            $reportDate = $report->report_date;

            $report->total_trip = Trip::whereHas('jadwal', function ($query) use ($reportDate) {
                $query->where('tanggal_keberangkatan', $reportDate);
            })->when($shift !== 'semua', function ($query) use ($shift) {
                $query->whereHas('jadwal', fn($jadwalQuery) => $jadwalQuery->where('shift', $shift));
            })->count();

            $report->dp_revenue = $this->paymentTotalForReportDate($reportDate, Pembayaran::JENIS_DP, $shift);
            $report->pelunasan_revenue = $this->paymentTotalForReportDate($reportDate, Pembayaran::JENIS_PELUNASAN, $shift);
        }

        return $dailyReports;
    }

    private function paymentTotalForReportDate(string $reportDate, string $jenisPembayaran, string $shift): int
    {
        return (int) Pembayaran::where('jenis_pembayaran', $jenisPembayaran)
            ->where('status_pembayaran', Pembayaran::STATUS_TERVERIFIKASI)
            ->whereHas('booking', function ($query) use ($reportDate, $shift) {
                $query->whereDate('created_at', $reportDate)
                    ->when($shift !== 'semua', function ($bookingQuery) use ($shift) {
                        $bookingQuery->whereHas('jadwal', fn($jadwalQuery) => $jadwalQuery->where('shift', $shift));
                    });
            })
            ->sum('jumlah_bayar');
    }

    private function buildTripSummary(Carbon $startDate, Carbon $endDate, string $shift)
    {
        return Trip::with(['jadwal.rute', 'driver', 'armada', 'detailTrips'])
            ->whereHas('jadwal', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_keberangkatan', [$startDate->toDateString(), $endDate->toDateString()]);
            })
            ->when($shift !== 'semua', function ($query) use ($shift) {
                $query->whereHas('jadwal', fn($jadwalQuery) => $jadwalQuery->where('shift', $shift));
            })
            ->orderByDesc('created_at')
            ->get();
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
            default => [
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