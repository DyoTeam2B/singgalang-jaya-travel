@extends('layouts.public')

@section('title', 'Detail Booking ' . $booking->kode_booking . ' - Singgalang Jaya Travel')

@section('content')
@php
    $latestPayment = $latestPayment ?? $booking->pembayaran->first();
    $tripDetail = $booking->detailTrips->first();
    $trip = $tripDetail?->trip;
    $driver = $trip?->driver;
    $armada = $trip?->armada;
    $canEdit = in_array($booking->status_booking, [
        \App\Models\Booking::STATUS_BOOKING_DIBUAT,
        \App\Models\Booking::STATUS_MENUNGGU_VERIFIKASI,
        \App\Models\Booking::STATUS_DIKONFIRMASI,
    ]);
    $canCancel = !in_array($booking->status_booking, [
        \App\Models\Booking::STATUS_ON_TRIP,
        \App\Models\Booking::STATUS_COMPLETED,
        \App\Models\Booking::STATUS_CANCELLED,
        \App\Models\Booking::STATUS_EXPIRED,
    ]);
    $timelineSteps = [
        ['status' => \App\Models\Booking::STATUS_BOOKING_DIBUAT, 'label' => 'Booking Dibuat', 'description' => 'Pesanan berhasil dibuat'],
        ['status' => \App\Models\Booking::STATUS_MENUNGGU_VERIFIKASI, 'label' => 'DP Diupload', 'description' => 'Menunggu verifikasi admin'],
        ['status' => \App\Models\Booking::STATUS_DIKONFIRMASI, 'label' => 'Dikonfirmasi', 'description' => 'Pembayaran DP diterima'],
        ['status' => \App\Models\Booking::STATUS_ASSIGNED_TO_TRIP, 'label' => 'Masuk Trip', 'description' => 'Driver dan armada ditentukan'],
        ['status' => \App\Models\Booking::STATUS_ON_TRIP, 'label' => 'Perjalanan', 'description' => 'Trip sedang berjalan'],
        ['status' => \App\Models\Booking::STATUS_COMPLETED, 'label' => 'Selesai', 'description' => 'Perjalanan selesai'],
    ];
    $currentStepIndex = collect($timelineSteps)->search(fn ($step) => $step['status'] === $booking->status_booking);
    $currentStepIndex = $currentStepIndex === false ? 0 : $currentStepIndex;
    $showDriverInfo = $driver && in_array($booking->status_booking, [
        \App\Models\Booking::STATUS_ASSIGNED_TO_TRIP,
        \App\Models\Booking::STATUS_ON_TRIP,
        \App\Models\Booking::STATUS_COMPLETED,
    ]);
    $driverWhatsapp = preg_replace('/\D+/', '', $driver->no_hp ?? '');
    if ($driverWhatsapp && str_starts_with($driverWhatsapp, '0')) {
        $driverWhatsapp = '62' . substr($driverWhatsapp, 1);
    } elseif ($driverWhatsapp && !str_starts_with($driverWhatsapp, '62')) {
        $driverWhatsapp = '62' . $driverWhatsapp;
    }
@endphp

<div class="py-12 md:py-20 bg-slate-50 flex-1">
    <div class="max-w-6xl mx-auto px-6 lg:px-8 space-y-8" x-data="{ cancelOpen: false }">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <a href="{{ route('booking.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-blue-600 uppercase tracking-wider transition-colors mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Booking Saya
                </a>
                <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-2">{{ $booking->kode_booking }}</p>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-800 tracking-tight">Detail Booking</h1>
            </div>
            <x-status-badge :status="$booking->status_booking" class="text-sm" />
        </div>

        <x-alert />

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 md:p-8">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Progress Booking</p>
                    <h2 class="text-xl font-bold text-slate-800 mt-1">Timeline Status</h2>
                </div>
                <span class="text-xs font-semibold text-slate-400">{{ $booking->updated_at->format('d M Y H:i') }} WIB</span>
            </div>

            {{-- Mobile Timeline (Vertical) --}}
            <div class="block md:hidden space-y-4">
                @foreach($timelineSteps as $index => $step)
                    @php
                        $isActive = $index <= $currentStepIndex;
                        $isLast = $loop->last;
                    @endphp
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center shrink-0">
                            <div class="w-8 h-8 rounded-full border-4 flex items-center justify-center text-[10px] font-black {{ $isActive ? 'bg-blue-600 border-blue-100 text-white shadow-md shadow-blue-600/20' : 'bg-white border-slate-200 text-slate-400' }}">
                                {{ $index + 1 }}
                            </div>
                            @if(!$isLast)
                                <div class="w-0.5 h-10 {{ $index < $currentStepIndex ? 'bg-blue-600' : 'bg-slate-200' }} my-1"></div>
                            @endif
                        </div>
                        <div class="pt-0.5">
                            <p class="text-sm font-bold {{ $isActive ? 'text-blue-800' : 'text-slate-400' }}">{{ $step['label'] }}</p>
                            <p class="text-xs font-medium {{ $isActive ? 'text-slate-500' : 'text-slate-300' }} mt-0.5">{{ $step['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Desktop Timeline (Horizontal) --}}
            <div class="hidden md:block overflow-x-auto pb-2">
                <div class="min-w-[760px] grid grid-cols-6">
                    @foreach($timelineSteps as $index => $step)
                        @php
                            $isActive = $index <= $currentStepIndex;
                            $lineActive = $index < $currentStepIndex;
                        @endphp
                        <div class="relative px-2">
                            @if(!$loop->last)
                                <div class="absolute top-4 left-1/2 w-full h-1 {{ $lineActive ? 'bg-blue-600' : 'bg-slate-200' }}"></div>
                            @endif
                            <div class="relative z-10 flex flex-col items-center text-center gap-2">
                                <div class="w-9 h-9 rounded-full border-4 flex items-center justify-center text-[10px] font-black {{ $isActive ? 'bg-blue-600 border-blue-100 text-white shadow-lg shadow-blue-600/20' : 'bg-white border-slate-200 text-slate-400' }}">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold {{ $isActive ? 'text-blue-800' : 'text-slate-400' }}">{{ $step['label'] }}</p>
                                    <p class="text-[10px] font-medium {{ $isActive ? 'text-slate-500' : 'text-slate-300' }} mt-0.5">{{ $step['description'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 sm:p-8 flex flex-col md:flex-row justify-between gap-6">
                        <div class="space-y-3">
                            <h2 class="text-2xl font-bold text-slate-800">{{ $booking->jadwal->rute->asal ?? '-' }} -> {{ $booking->jadwal->rute->tujuan ?? '-' }}</h2>
                            <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500">
                                <span class="inline-flex items-center gap-2 bg-slate-100 text-slate-700 px-3 py-1.5 rounded-xl font-semibold">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $booking->jadwal?->tanggal_keberangkatan?->format('d M Y') ?? '-' }}
                                </span>
                                @if($booking->jadwal)
                                    <span class="inline-flex items-center gap-2 bg-blue-50 text-blue-800 px-3 py-1.5 rounded-xl font-semibold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4m6 0a10 10 0 11-20 0 10 10 0 0120 0z"></path></svg>
                                        Shift {{ ucfirst($booking->jadwal->shift) }} - {{ $booking->jadwal->jam_berangkat->format('H:i') }} WIB
                                    </span>
                                @endif
                                <span class="inline-flex items-center bg-slate-100 text-slate-700 px-3 py-1.5 rounded-xl font-semibold">{{ $booking->jumlah_penumpang }} penumpang</span>
                            </div>
                        </div>
                        <div class="md:text-right">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Tarif</p>
                            <p class="text-3xl font-bold text-slate-800">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 p-6 sm:p-8 grid md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Alamat Jemput</p>
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-medium text-slate-700 leading-relaxed">{{ $booking->alamat_jemput }}</div>
                        </div>
                        <div class="space-y-3">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Alamat Tujuan</p>
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-medium text-slate-700 leading-relaxed">{{ $booking->alamat_tujuan }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Pembayaran DP</h2>
                            <p class="text-sm text-slate-500">DP flat Rp50.000. Pelunasan dibayar langsung ke driver.</p>
                        </div>
                        @if($latestPayment)
                            <x-status-badge :status="$latestPayment->status_pembayaran" />
                        @else
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">Belum Upload</span>
                        @endif
                    </div>

                    <div class="grid sm:grid-cols-3 gap-4">
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 uppercase tracking-wider">DP</p>
                            <p class="text-lg font-bold text-blue-800 mt-1">Rp {{ number_format(50000, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 uppercase tracking-wider">Total</p>
                            <p class="text-lg font-bold text-slate-800 mt-1">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 uppercase tracking-wider">Pelunasan</p>
                            <p class="text-lg font-bold text-green-700 mt-1">Rp {{ number_format(max(0, $booking->total_harga - 50000), 0, ',', '.') }}</p>
                        </div>
                    </div>

                    @if($latestPayment?->catatan)
                        <div class="p-4 bg-yellow-50 border border-yellow-100 rounded-xl text-sm text-yellow-800">
                            <span class="font-semibold">Catatan admin:</span> {{ $latestPayment->catatan }}
                        </div>
                    @endif

                    @if($booking->status_booking === \App\Models\Booking::STATUS_BOOKING_DIBUAT)
                        <a href="{{ route('booking.pembayaran', ['kode' => $booking->kode_booking]) }}" class="inline-flex items-center justify-center gap-2 bg-blue-800 hover:bg-blue-900 text-white font-semibold px-6 py-3 rounded-xl transition-colors text-sm shadow-sm">
                            {{ $latestPayment?->status_pembayaran === 'ditolak' ? 'Upload Ulang Bukti DP' : 'Upload Bukti DP' }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <h2 class="text-lg font-bold text-slate-800">Aksi Booking</h2>
                    <div class="grid gap-3">
                        @if($canEdit)
                            <a href="{{ route('booking.edit', ['kode' => $booking->kode_booking]) }}" class="inline-flex justify-center items-center bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold px-5 py-3 rounded-xl transition-colors text-sm">Edit Lokasi Jemput</a>
                        @endif
                        @if($canCancel)
                            <button type="button" @click="cancelOpen = !cancelOpen" class="inline-flex justify-center items-center bg-red-50 border border-red-200 hover:bg-red-100 text-red-700 font-semibold px-5 py-3 rounded-xl transition-colors text-sm">Batalkan Booking</button>
                        @endif
                    </div>

                    @if($canCancel)
                        <form x-show="cancelOpen" x-transition action="{{ route('booking.cancel', ['kode' => $booking->kode_booking]) }}" method="POST" class="space-y-3 pt-4 border-t border-slate-100" style="display: none;">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Alasan Pembatalan</label>
                                <textarea name="alasan_pembatalan" rows="3" required class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-3 rounded-xl transition-colors text-sm">Konfirmasi Batal</button>
                        </form>
                    @endif
                </div>

                <div class="bg-slate-900 rounded-2xl p-6 text-white shadow-sm space-y-5">
                    <div>
                        <p class="text-xs font-bold text-blue-300 uppercase tracking-wider mb-2">Driver & Armada</p>
                        <h2 class="text-xl font-bold">Informasi Trip</h2>
                    </div>

                    @if($showDriverInfo)
                        <div class="space-y-3">
                            <div class="p-4 bg-white/5 rounded-xl border border-white/10">
                                <p class="text-xs text-slate-400 uppercase tracking-wider">Driver</p>
                                <p class="text-sm font-bold mt-1">{{ $driver->nama_driver }}</p>
                                <p class="text-xs text-slate-300 mt-1">{{ $driver->no_hp }}</p>
                            </div>
                            <div class="p-4 bg-white/5 rounded-xl border border-white/10">
                                <p class="text-xs text-slate-400 uppercase tracking-wider">Armada</p>
                                <p class="text-sm font-bold mt-1">{{ $armada->nama_mobil ?? '-' }}</p>
                                <p class="text-xs text-slate-300 mt-1">{{ $armada->nomor_plat ?? '-' }}</p>
                            </div>
                            <div class="p-4 bg-white/5 rounded-xl border border-white/10">
                                <p class="text-xs text-slate-400 uppercase tracking-wider">Jadwal Trip</p>
                                <p class="text-sm font-bold mt-1">{{ $trip->jadwal->tanggal_keberangkatan->format('d M Y') }}</p>
                                <p class="text-xs text-slate-300 mt-1">Shift {{ ucfirst($trip->jadwal->shift) }} - {{ $trip->jadwal->jam_berangkat->format('H:i') }} WIB</p>
                            </div>
                            @if($driverWhatsapp)
                                <a href="https://wa.me/{{ $driverWhatsapp }}" target="_blank" rel="noopener" class="w-full inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold px-5 py-3 rounded-xl transition-colors text-sm">
                                    Hubungi Driver via WhatsApp
                                </a>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-slate-300 leading-relaxed">Driver dan armada akan muncul setelah admin memasukkan booking ini ke trip.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection