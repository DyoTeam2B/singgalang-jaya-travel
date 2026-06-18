<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Jadwal;
use App\Services\BookingService;
use App\Services\FonnteService;
use App\Models\WhatsappNotification;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    protected FonnteService $fonnteService;

    public function __construct(BookingService $bookingService, FonnteService $fonnteService)
    {
        $this->bookingService = $bookingService;
        $this->fonnteService = $fonnteService;
    }

    /**
     * Show the booking form.
     */
    public function create(Request $request)
    {
        $preselectedJadwalId = $request->query('jadwal_id');
        
        // Retrieve active schedules with remaining kuota
        $today = now()->toDateString();
        $currentTime = now()->toTimeString();

        $schedules = Jadwal::with('rute')
            ->aktif()
            ->withSum(['bookings as booked_seats' => function ($q) {
                $q->whereNotIn('status_booking', [Booking::STATUS_CANCELLED, Booking::STATUS_EXPIRED]);
            }], 'jumlah_penumpang')
            ->where(function ($q) use ($today, $currentTime) {
                $q->where('tanggal_keberangkatan', '>', $today)
                  ->orWhere(function ($sq) use ($today, $currentTime) {
                      $sq->where('tanggal_keberangkatan', $today)
                         ->where('jam_berangkat', '>=', $currentTime);
                  });
            })
            ->orderBy('tanggal_keberangkatan', 'asc')
            ->orderBy('jam_berangkat', 'asc')
            ->get()
            ->filter(function ($schedule) {
                return ($schedule->kuota - ($schedule->booked_seats ?? 0)) > 0;
            });

        return view('public.booking.create', compact('schedules', 'preselectedJadwalId'));
    }

    /**
     * Store a new booking.
     */
    public function store(StoreBookingRequest $request)
    {
        $booking = $this->bookingService->createBooking($request->validated(), auth()->user());

        return redirect()
            ->route('booking.review', ['kode' => $booking->kode_booking])
            ->with('success', 'Booking berhasil dibuat. Silakan tinjau pesanan Anda.');
    }

    /**
     * Show the booking review page.
     */
    public function review($kode)
    {
        $booking = Booking::with(['pelanggan', 'jadwal.rute'])
            ->where('kode_booking', $kode)
            ->firstOrFail();

        // Customer can only review if they own the booking
        if ($booking->pelanggan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow review for new/created bookings
        if ($booking->status_booking !== Booking::STATUS_BOOKING_DIBUAT) {
            return redirect()->route('booking.pembayaran', ['kode' => $kode]);
        }

        return view('public.booking.review', compact('booking'));
    }

    /**
     * Display authenticated customer bookings.
     */
    public function index()
    {
        $pelanggan = auth()->user()->pelanggan;

        $bookings = Booking::with([
                'jadwal.rute',
                'pembayaran' => fn ($query) => $query->latest(),
                'detailTrips.trip.driver',
                'detailTrips.trip.armada',
            ])
            ->where('pelanggan_id', $pelanggan?->id ?? 0)
            ->latest()
            ->paginate(10);

        return view('public.booking.index', compact('bookings'));
    }

    /**
     * Display one authenticated customer booking.
     */
    public function show($kode)
    {
        $booking = Booking::with([
                'pelanggan.user',
                'jadwal.rute',
                'pembayaran' => fn ($query) => $query->latest(),
                'detailTrips.trip.driver',
                'detailTrips.trip.armada',
            ])
            ->where('kode_booking', $kode)
            ->firstOrFail();

        if ($booking->pelanggan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $latestPayment = $booking->pembayaran->first();

        return view('public.booking.show', compact('booking', 'latestPayment'));
    }
    /**
     * Show the edit lokasi form.
     */
    public function edit($kode)
    {
        $booking = Booking::with('pelanggan')
            ->where('kode_booking', $kode)
            ->firstOrFail();

        if ($booking->pelanggan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check restriction: cannot edit if already assigned to trip or further
        $allowedStatuses = [
            Booking::STATUS_BOOKING_DIBUAT,
            Booking::STATUS_MENUNGGU_VERIFIKASI,
            Booking::STATUS_DIKONFIRMASI
        ];

        if (!in_array($booking->status_booking, $allowedStatuses)) {
            return redirect()
                ->route('booking.show', ['kode' => $kode])
                ->with('error', 'Lokasi penjemputan tidak dapat diubah karena pesanan sudah masuk ke tahap trip/perjalanan.');
        }

        return view('public.booking.edit', compact('booking'));
    }

    /**
     * Update the booking lokasi jemput.
     */
    public function update(Request $request, $kode)
    {
        $booking = Booking::with('pelanggan')
            ->where('kode_booking', $kode)
            ->firstOrFail();

        if ($booking->pelanggan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $allowedStatuses = [
            Booking::STATUS_BOOKING_DIBUAT,
            Booking::STATUS_MENUNGGU_VERIFIKASI,
            Booking::STATUS_DIKONFIRMASI
        ];

        if (!in_array($booking->status_booking, $allowedStatuses)) {
            return redirect()
                ->route('booking.show', ['kode' => $kode])
                ->with('error', 'Lokasi penjemputan tidak dapat diubah karena pesanan sudah masuk ke tahap trip/perjalanan.');
        }

        $validated = $request->validate([
            'alamat_jemput' => ['required', 'string', 'max:500'],
            'latitude_jemput' => ['nullable', 'numeric'],
            'longitude_jemput' => ['nullable', 'numeric'],
        ], [
            'alamat_jemput.required' => 'Alamat penjemputan wajib diisi.',
            'alamat_jemput.max' => 'Alamat penjemputan maksimal 500 karakter.',
        ]);

        $booking->update($validated);

        // Redirect back to customer booking detail.
        return redirect()
            ->route('booking.show', ['kode' => $kode])
            ->with('success', 'Lokasi penjemputan berhasil diperbarui.');
    }

    /**
     * Cancel the booking.
     */
    public function cancel(Request $request, $kode)
    {
        $booking = Booking::with(['pelanggan', 'jadwal.rute'])
            ->where('kode_booking', $kode)
            ->firstOrFail();

        if ($booking->pelanggan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Cannot cancel if trip has already started
        $disallowedStatuses = [
            Booking::STATUS_ON_TRIP,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_CANCELLED,
            Booking::STATUS_EXPIRED
        ];

        if (in_array($booking->status_booking, $disallowedStatuses)) {
            return redirect()
                ->route('booking.show', ['kode' => $kode])
                ->with('error', 'Pemesanan tidak dapat dibatalkan pada status saat ini.');
        }

        $request->validate([
            'alasan_pembatalan' => ['required', 'string', 'max:500'],
        ], [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.',
        ]);

        $booking->update([
            'status_booking' => Booking::STATUS_CANCELLED,
            'alasan_pembatalan' => $request->alasan_pembatalan,
        ]);

        // Send WhatsApp notifications via FonnteService
        $adminWA = config('services.fonnte.admin_number');
        $message = $this->buildCancellationMessage($booking, $request->alasan_pembatalan);

        // 1. Notify Admin
        if ($adminWA) {
            $this->fonnteService->send($adminWA, $message, WhatsappNotification::TYPE_PEMBATALAN_BOOKING, $booking->id);
        }

        // 2. Notify Driver (if already assigned to trip)
        $driver = $booking->detailTrips()->first()?->trip?->driver;
        if ($driver && $driver->no_hp) {
            $this->fonnteService->send($driver->no_hp, $message, WhatsappNotification::TYPE_PEMBATALAN_BOOKING, $booking->id);
        }

        return redirect()
            ->route('booking.show', ['kode' => $kode])
            ->with('success', 'Pemesanan berhasil dibatalkan.');
    }

    private function buildCancellationMessage(Booking $booking, string $reason): string
    {
        $date = $booking->jadwal->tanggal_keberangkatan?->format('d/m/Y') ?? '-';
        $shift = ucfirst($booking->jadwal->shift ?? '-');
        $time = $booking->jadwal->jam_berangkat?->format('H:i') ?? '-';

        return implode("\n", [
            '*SINGGALANG JAYA TRAVEL*',
            '*BOOKING DIBATALKAN*',
            '',
            'Pelanggan membatalkan booking berikut:',
            '',
            '*Detail Booking*',
            "Kode      : {$booking->kode_booking}",
            "Pelanggan : {$booking->pelanggan->nama}",
            "No. HP    : {$booking->pelanggan->no_hp}",
            "Rute      : {$booking->jadwal->rute->asal} -> {$booking->jadwal->rute->tujuan}",
            "Jadwal    : {$date} - {$shift} {$time} WIB",
            "Penumpang : {$booking->jumlah_penumpang} orang",
            '',
            '*Alasan Pembatalan*',
            $reason,
            '',
            'Silakan cek dashboard untuk tindak lanjut.',
        ]);
    }
}
