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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-8 flex flex-col md:flex-row justify-between gap-6">
                        <div class="space-y-3">
                            <h2 class="text-2xl font-bold text-slate-800">{{ $booking->jadwal->rute->asal ?? '-' }} -> {{ $booking->jadwal->rute->tujuan ?? '-' }}</h2>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500">
                                <span>{{ $booking->jadwal?->tanggal_keberangkatan?->format('d M Y') ?? '-' }}</span>
                                @if($booking->jadwal)
                                    <span>Shift {{ ucfirst($booking->jadwal->shift) }} - {{ $booking->jadwal->jam_berangkat->format('H:i') }} WIB</span>
                                @endif
                                <span>{{ $booking->jumlah_penumpang }} penumpang</span>
                            </div>
                        </div>
                        <div class="md:text-right">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Tarif</p>
                            <p class="text-3xl font-bold text-slate-800">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 p-8 grid md:grid-cols-2 gap-6">
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

                    @if($trip && $driver)
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