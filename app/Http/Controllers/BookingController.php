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

        return view('public.booking.create', compact('preselectedJadwalId'));
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
            ->first();

        if (!$booking) {
            return redirect()
                ->route('booking.index')
                ->with('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');
        }

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
        $pelangganId = $pelanggan?->id ?? 0;

        $activeStatuses = [
            Booking::STATUS_BOOKING_DIBUAT,
            Booking::STATUS_MENUNGGU_VERIFIKASI,
            Booking::STATUS_DIKONFIRMASI,
            Booking::STATUS_ASSIGNED_TO_TRIP,
            Booking::STATUS_ON_TRIP,
        ];

        $historyStatuses = [
            Booking::STATUS_COMPLETED,
            Booking::STATUS_CANCELLED,
            Booking::STATUS_EXPIRED
        ];

        $activeBookings = Booking::with([
                'jadwal.rute',
                'pembayaran' => fn ($query) => $query->latest(),
                'detailTrips.trip.driver',
                'detailTrips.trip.armada',
            ])
            ->where('pelanggan_id', $pelangganId)
            ->whereIn('status_booking', $activeStatuses)
            ->latest()
            ->get();

        $historyBookings = Booking::with([
                'jadwal.rute',
                'pembayaran' => fn ($query) => $query->latest(),
                'detailTrips.trip.driver',
                'detailTrips.trip.armada',
            ])
            ->where('pelanggan_id', $pelangganId)
            ->whereIn('status_booking', $historyStatuses)
            ->latest()
            ->paginate(10, ['*'], 'history_page');

        return view('public.booking.index', compact('activeBookings', 'historyBookings'));
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
            ->first();

        if (!$booking) {
            return redirect()
                ->route('booking.index')
                ->with('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');
        }

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
            ->first();

        if (!$booking) {
            return redirect()
                ->route('booking.index')
                ->with('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');
        }

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
        $booking = Booking::with(['pelanggan', 'jadwal.rute'])
            ->where('kode_booking', $kode)
            ->first();

        if (!$booking) {
            return redirect()
                ->route('booking.index')
                ->with('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');
        }

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
                ->with('error', 'Pemesanan tidak dapat diubah pada status saat ini.');
        }

        $rules = [
            'alamat_jemput' => ['required', 'string', 'max:500'],
            'latitude_jemput' => ['nullable', 'numeric'],
            'longitude_jemput' => ['nullable', 'numeric'],
        ];

        $messages = [
            'alamat_jemput.required' => 'Alamat penjemputan wajib diisi.',
            'alamat_jemput.max' => 'Alamat penjemputan maksimal 500 karakter.',
        ];

        $canEditPassengers = in_array($booking->status_booking, [
            Booking::STATUS_BOOKING_DIBUAT,
            Booking::STATUS_MENUNGGU_VERIFIKASI
        ]);

        if ($request->has('jumlah_penumpang')) {
            if (!$canEditPassengers) {
                return redirect()
                    ->route('booking.show', ['kode' => $kode])
                    ->with('error', 'Jumlah penumpang tidak dapat diubah pada status saat ini.');
            }

            $rules['jumlah_penumpang'] = ['required', 'integer', 'min:1'];
            $messages['jumlah_penumpang.required'] = 'Jumlah penumpang wajib diisi.';
            $messages['jumlah_penumpang.integer'] = 'Jumlah penumpang harus berupa angka.';
            $messages['jumlah_penumpang.min'] = 'Jumlah penumpang minimal 1 orang.';
        }

        $validated = $request->validate($rules, $messages);

        if ($request->has('jumlah_penumpang')) {
            $newJumlah = (int)$validated['jumlah_penumpang'];

            // Recalculate available seats excluding current booking
            $bookedSeats = $booking->jadwal->bookings()
                ->whereNotIn('status_booking', [
                    Booking::STATUS_CANCELLED,
                    Booking::STATUS_EXPIRED
                ])
                ->where('id', '!=', $booking->id)
                ->sum('jumlah_penumpang');

            $available = $booking->jadwal->kuota - $bookedSeats;

            if ($newJumlah > $available) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', "Jumlah penumpang melebihi kuota kursi yang tersedia ({$available} kursi tersisa).");
            }

            // Recalculate total price
            $tarif = $booking->jadwal->rute->tarif;
            $validated['total_harga'] = $tarif * $newJumlah;
        }

        $booking->update($validated);

        return redirect()
            ->route('booking.show', ['kode' => $kode])
            ->with('success', 'Detail pemesanan berhasil diperbarui.');
    }

    /**
     * Cancel the booking.
     */
    public function cancel(Request $request, $kode)
    {
        $booking = Booking::with(['pelanggan', 'jadwal.rute'])
            ->where('kode_booking', $kode)
            ->first();

        if (!$booking) {
            return redirect()
                ->route('booking.index')
                ->with('error', 'Booking tidak ditemukan atau telah dibatalkan secara otomatis karena melewati batas waktu pembayaran DP.');
        }

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
