@extends('layouts.public')

@section('title', 'Lacak Pemesanan #' . $booking->kode_booking . ' - Singgalang Jaya Travel')

@section('content')
<div class="py-12 md:py-20 bg-slate-50 flex-1">
    <div class="max-w-6xl mx-auto px-6 lg:px-8">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6">
                <div class="flex items-center gap-3 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6">
                <div class="flex items-center gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6">
                <div class="flex items-center gap-3 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-medium">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        {{-- Banner for Expired / Cancelled --}}
        @if($booking->status_booking === \App\Models\Booking::STATUS_EXPIRED)
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-2xl mb-6 flex items-center gap-3">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h4 class="font-bold text-sm">Pemesanan Kedaluwarsa</h4>
                    <p class="text-xs mt-0.5">Pemesanan ini tidak aktif. Silakan lakukan pemesanan ulang jika masih membutuhkan perjalanan.</p>
                </div>
            </div>
        @elseif($booking->status_booking === \App\Models\Booking::STATUS_CANCELLED)
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-2xl mb-6 flex items-center gap-3">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h4 class="font-bold text-sm">Pemesanan Dibatalkan</h4>
                    <p class="text-xs mt-0.5">
                        Pemesanan ini telah dibatalkan.
                        @if($booking->alasan_pembatalan)
                            <br><span class="font-semibold">Alasan:</span> {{ $booking->alasan_pembatalan }}
                        @endif
                    </p>
                </div>
            </div>
        @endif

        {{-- Top Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
            <a href="{{ route('cek-booking.index') }}" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 text-xs font-semibold uppercase tracking-wider transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            @if(auth()->check() && $booking->pelanggan->user_id === auth()->id())
                @php
                    $allowedEdit = in_array($booking->status_booking, [
                        \App\Models\Booking::STATUS_BOOKING_DIBUAT,
                        \App\Models\Booking::STATUS_MENUNGGU_VERIFIKASI,
                        \App\Models\Booking::STATUS_DIKONFIRMASI
                    ]);
                    $allowedCancel = !in_array($booking->status_booking, [
                        \App\Models\Booking::STATUS_ON_TRIP,
                        \App\Models\Booking::STATUS_COMPLETED,
                        \App\Models\Booking::STATUS_CANCELLED,
                        \App\Models\Booking::STATUS_EXPIRED
                    ]);
                @endphp
                <div class="flex items-center gap-3">
                    @if($allowedEdit)
                        <a href="{{ route('booking.edit', ['kode' => $booking->kode_booking]) }}" class="flex items-center gap-2 bg-white border border-slate-200 px-5 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wider hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Edit Lokasi
                        </a>
                    @endif
                    @if($allowedCancel)
                        <button onclick="document.getElementById('cancelModal').classList.remove('hidden')" class="flex items-center gap-2 bg-white border border-slate-200 px-5 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wider hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Batalkan Booking
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Main Content (8/12) --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- Ticket Header Card --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden relative">
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col md:flex-row justify-between gap-6">
                            <div class="space-y-3">
                                <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">{{ $booking->kode_booking }}</p>
                                <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight leading-tight">{{ $booking->jadwal->rute->asal }} → {{ $booking->jadwal->rute->tujuan }}</h1>
                                <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="font-medium">{{ $booking->jadwal->tanggal_keberangkatan->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span class="font-medium">Shift {{ ucfirst($booking->jadwal->shift) }} • {{ $booking->jadwal->jam_berangkat->format('H:i') }} WIB</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="font-medium">{{ $booking->jumlah_penumpang }} Penumpang</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center md:text-right flex flex-col items-center md:items-end justify-center shrink-0">
                                <x-status-badge :status="$booking->status_booking" />
                                <div class="mt-3">
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Tarif</p>
                                    <p class="text-3xl font-bold text-slate-800 tracking-tight">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ticket Divider --}}
                    <div class="relative -mx-2 my-0">
                        <div class="absolute left-0 top-1/2 -translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-slate-50 rounded-full border border-slate-200 z-20"></div>
                        <div class="absolute right-0 top-1/2 translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-slate-50 rounded-full border border-slate-200 z-20"></div>
                        <div class="border-t-2 border-dashed border-slate-100 w-full relative z-10 mx-6"></div>
                    </div>

                    {{-- Location Info --}}
                    <div class="p-6 sm:p-8 grid md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600 ring-2 ring-blue-600/20"></span>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Titik Penjemputan</p>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-blue-600 shrink-0 shadow-sm border border-slate-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-800 leading-relaxed">{{ $booking->alamat_jemput }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500 ring-2 ring-green-500/20"></span>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Titik Tujuan</p>
                            </div>
                            <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-green-600 shrink-0 shadow-sm border border-slate-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-800 leading-relaxed">{{ $booking->alamat_tujuan }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Billing Detail --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-800 tracking-tight">Rincian Pembayaran</h3>
                            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mt-0.5">Struktur biaya perjalanan</p>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center text-sm font-medium">
                            <span class="text-slate-500 uppercase tracking-wider">Tiket Perjalanan ({{ $booking->jumlah_penumpang }}x)</span>
                            <span class="text-slate-800 font-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                        </div>

                        {{-- DP Status --}}
                        @if($booking->pembayaran->isEmpty())
                            <div class="flex justify-between items-center text-sm font-medium">
                                <span class="text-slate-500 uppercase tracking-wider">Uang Muka (DP)</span>
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Belum Dibayar</span>
                            </div>
                            @if(auth()->check() && $booking->pelanggan->user_id === auth()->id() && $booking->status_booking === \App\Models\Booking::STATUS_BOOKING_DIBUAT)
                                <a href="{{ route('booking.pembayaran', ['kode' => $booking->kode_booking]) }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs px-4 py-2.5 rounded-xl transition-colors shadow-sm">
                                    Bayar Sekarang
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            @endif
                        @else
                            @php $pembayaran = $booking->pembayaran->first(); @endphp
                            <div class="flex justify-between items-center text-sm font-medium">
                                <span class="text-slate-500 uppercase tracking-wider">Uang Muka (DP)</span>
                                <span class="text-blue-600 font-bold">- Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm font-medium">
                                <span class="text-slate-500 uppercase tracking-wider">Status Verifikasi</span>
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    {{ $pembayaran->status_pembayaran === 'terverifikasi' ? 'bg-green-100 text-green-800' : ($pembayaran->status_pembayaran === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($pembayaran->status_pembayaran) }}
                                </span>
                            </div>
                            @if($pembayaran->catatan)
                                <div class="p-3 bg-yellow-50 border border-yellow-100 rounded-xl text-xs text-yellow-800">
                                    <span class="font-bold">Catatan Admin:</span> {{ $pembayaran->catatan }}
                                </div>
                            @endif
                            @if(auth()->check() && $booking->pelanggan->user_id === auth()->id() && $pembayaran->status_pembayaran === 'ditolak' && $booking->status_booking === \App\Models\Booking::STATUS_BOOKING_DIBUAT)
                                <a href="{{ route('booking.pembayaran', ['kode' => $booking->kode_booking]) }}" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs px-4 py-2.5 rounded-xl transition-colors shadow-sm">
                                    Upload Ulang Bukti Pembayaran
                                </a>
                            @endif
                        @endif

                        {{-- Pelunasan Box --}}
                        <div class="relative py-3">
                            <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 border-t-2 border-dashed border-slate-100"></div>
                            <div class="relative z-10 flex justify-center">
                                <span class="px-4 bg-white text-xs font-semibold text-slate-300 uppercase tracking-widest">Pelunasan</span>
                            </div>
                        </div>

                        <div class="bg-green-50 rounded-2xl p-5 border border-green-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 bg-white rounded-xl flex items-center justify-center text-green-600 shadow-sm border border-green-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-green-700 uppercase tracking-wider mb-0.5">Bayar di Driver</p>
                                    <p class="text-xs font-medium text-green-600">Silakan lunasi sisa pembayaran saat penjemputan.</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-green-700 tracking-tight">Rp {{ number_format(max(0, $booking->total_harga - 50000), 0, ',', '.') }}</p>
                                <p class="text-xs font-semibold text-green-600/60 uppercase tracking-wider mt-1">Cash / Transfer Driver</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Sidebar (4/12) --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- Timeline --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <svg class="w-5 h-5 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Status Perjalanan</h3>
                    </div>

                    @php
                        $status = $booking->status_booking;
                        $steps = [
                            ['label' => 'Pemesanan Dibuat', 'desc' => 'Data booking berhasil disimpan.', 'active' => true],
                            ['label' => 'Upload Bukti DP', 'desc' => 'Pelanggan upload bukti DP Rp50.000.', 'active' => in_array($status, ['booking_dibuat', 'menunggu_verifikasi', 'dikonfirmasi', 'assigned_to_trip', 'on_trip', 'completed'])],
                            ['label' => 'Verifikasi Pembayaran', 'desc' => 'Bukti transfer DP sedang ditinjau admin.', 'active' => in_array($status, ['menunggu_verifikasi', 'dikonfirmasi', 'assigned_to_trip', 'on_trip', 'completed'])],
                            ['label' => 'Booking Dikonfirmasi', 'desc' => 'DP terverifikasi. Kursi diamankan.', 'active' => in_array($status, ['dikonfirmasi', 'assigned_to_trip', 'on_trip', 'completed'])],
                            ['label' => 'Penugasan Trip & Driver', 'desc' => 'Booking dimasukkan ke trip perjalanan.', 'active' => in_array($status, ['assigned_to_trip', 'on_trip', 'completed'])],
                            ['label' => 'Dalam Perjalanan', 'desc' => 'Perjalanan rute sedang berlangsung.', 'active' => in_array($status, ['on_trip', 'completed'])],
                            ['label' => 'Perjalanan Selesai', 'desc' => 'Terima kasih telah bepergian bersama kami!', 'active' => ($status === 'completed')],
                        ];
                    @endphp

                    <div class="space-y-6 relative">
                        {{-- Timeline Line --}}
                        <div class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-slate-100"></div>

                        @foreach($steps as $step)
                            <div class="flex gap-4 relative z-10">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 border-2 border-white shadow-sm transition-all
                                    {{ $step['active'] ? 'bg-blue-800' : 'bg-slate-200' }}">
                                    @if($step['active'])
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                </div>
                                <div class="flex-1 pb-1">
                                    <p class="text-xs font-bold uppercase tracking-wider mb-0.5 transition-colors {{ $step['active'] ? 'text-slate-800' : 'text-slate-300' }}">
                                        {{ $step['label'] }}
                                    </p>
                                    @if($step['active'])
                                        <p class="text-xs font-medium text-slate-400">{{ $step['desc'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Vehicle/Driver Info Card --}}
                @php
                    $tripDetail = $booking->detailTrips->first();
                    $trip = $tripDetail?->trip;
                    $driver = $trip?->driver;
                @endphp
                <div class="bg-slate-800 rounded-2xl p-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/10 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center mb-5 border border-white/10">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                        </div>
                        <h4 class="text-base font-bold tracking-tight mb-2">Informasi Armada</h4>

                        @if($driver)
                            <p class="text-sm font-medium text-slate-400 mb-5 leading-relaxed">
                                Perjalanan Anda ditugaskan ke armada {{ $trip->armada->nama_mobil ?? 'Travel' }}. Driver akan segera menghubungi Anda.
                            </p>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10">
                                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Nama Driver</p>
                                        <p class="text-sm font-bold text-white">{{ $driver->nama_driver }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10">
                                    <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Kontak</p>
                                        <p class="text-sm font-bold text-white">{{ $driver->no_hp }}</p>
                                    </div>
                                </div>
                                @if($trip->armada?->nomor_plat)
                                    <div class="p-3 bg-white/5 rounded-xl border border-white/10 flex justify-between items-center">
                                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nomor Plat</span>
                                        <span class="bg-blue-800 text-xs px-3 py-1.5 rounded-lg font-mono font-bold text-white">{{ $trip->armada->nomor_plat }}</span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-sm font-medium text-slate-400 mb-5 leading-relaxed">
                                Driver dan armada akan ditugaskan oleh Admin setelah verifikasi pembayaran DP selesai.
                            </p>
                            <div class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Menunggu Penugasan</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Cancel Modal --}}
        @if(auth()->check() && $booking->pelanggan->user_id === auth()->id())
            <div id="cancelModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
                <div class="bg-white w-full max-w-sm rounded-2xl shadow-lg overflow-hidden p-8 text-center">
                    <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight mb-2">Batalkan Booking?</h3>
                    <p class="text-sm text-slate-500 mb-6 px-4 leading-relaxed">Tindakan ini akan membatalkan tiket perjalanan Anda. DP yang sudah dibayar tidak dapat dikembalikan.</p>

                    <form action="{{ route('booking.cancel', ['kode' => $booking->kode_booking]) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="text-left">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Alasan Pembatalan</label>
                            <textarea name="alasan_pembatalan" rows="3" class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 resize-none transition-colors" placeholder="Masukkan alasan Anda membatalkan pesanan..." required></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="document.getElementById('cancelModal').classList.add('hidden')" class="py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold uppercase tracking-wider hover:bg-slate-50 transition-colors">
                                Tutup
                            </button>
                            <button type="submit" class="py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-semibold uppercase tracking-wider transition-colors shadow-sm">
                                Ya, Batalkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
