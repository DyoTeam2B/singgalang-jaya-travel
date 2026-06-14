@extends('layouts.public')

@section('title', 'Tinjau Pemesanan - Singgalang Jaya Travel')

@section('content')
<div class="py-12 md:py-20 bg-slate-50 flex-1">
    <div class="max-w-4xl w-full mx-auto px-6 lg:px-8">

        {{-- Step Indicator --}}
        <div class="flex items-center justify-center mb-10 overflow-x-auto pb-4">
            <div class="flex items-center gap-2 sm:gap-4 text-xs font-semibold uppercase tracking-wider min-w-max">
                <div class="flex items-center gap-2 text-blue-800">
                    <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 text-xs font-bold border border-blue-200">1</span>
                    <span>Pemesanan</span>
                </div>
                <div class="w-10 h-0.5 bg-blue-300"></div>
                <div class="flex items-center gap-2 text-blue-800">
                    <span class="w-8 h-8 rounded-full bg-blue-800 flex items-center justify-center text-white text-xs font-bold shadow-sm">2</span>
                    <span>Review</span>
                </div>
                <div class="w-10 h-0.5 bg-slate-200"></div>
                <div class="flex items-center gap-2 text-slate-400">
                    <span class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 text-xs">3</span>
                    <span>Pembayaran</span>
                </div>
            </div>
        </div>

        {{-- Review Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Ticket Header --}}
            <div class="p-8">
                <div class="flex flex-col md:flex-row justify-between gap-6">
                    <div class="space-y-3">
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">{{ $booking->kode_booking }}</p>
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">{{ $booking->jadwal->rute->asal }} → {{ $booking->jadwal->rute->tujuan }}</h1>
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
                    <div class="text-right flex flex-col items-end justify-center shrink-0">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Tarif</p>
                        <p class="text-3xl font-bold text-slate-800 tracking-tight">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
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
            <div class="p-8 grid md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 ring-2 ring-blue-600/20"></span>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Titik Penjemputan</p>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-blue-600 shrink-0 shadow-sm border border-slate-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800 leading-relaxed">{{ $booking->alamat_jemput }}</p>
                            @if($booking->latitude_jemput && $booking->longitude_jemput)
                                <p class="text-xs text-slate-400 mt-1">{{ number_format($booking->latitude_jemput, 5) }}, {{ number_format($booking->longitude_jemput, 5) }}</p>
                            @endif
                        </div>
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
                        <div>
                            <p class="text-sm font-semibold text-slate-800 leading-relaxed">{{ $booking->alamat_tujuan }}</p>
                            @if($booking->latitude_tujuan && $booking->longitude_tujuan)
                                <p class="text-xs text-slate-400 mt-1">{{ number_format($booking->latitude_tujuan, 5) }}, {{ number_format($booking->longitude_tujuan, 5) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kontak Penumpang --}}
            <div class="px-8 pb-2">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col md:flex-row justify-between gap-4">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Nama Penumpang Utama</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $booking->pelanggan->nama }}</span>
                    </div>
                    <div class="md:text-right">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">No. HP / WhatsApp</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $booking->pelanggan->no_hp }}</span>
                    </div>
                </div>
            </div>

            {{-- Price Breakdown --}}
            <div class="p-8 border-t border-slate-100">
                @php
                    $dpAmount = \App\Models\Pembayaran::NOMINAL_DP;
                    $discountAmount = \App\Models\Pembayaran::hitungDiskonLunas($booking->total_harga);
                    $fullPaymentAmount = \App\Models\Pembayaran::hitungNominalLunas($booking->total_harga);
                @endphp
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span class="text-slate-500 uppercase tracking-wider">Tiket Perjalanan ({{ $booking->jumlah_penumpang }}x)</span>
                        <span class="text-slate-800 font-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span class="text-slate-500 uppercase tracking-wider">Uang Muka (DP) Wajib</span>
                        <span class="text-blue-600 font-bold">- Rp {{ number_format($dpAmount, 0, ',', '.') }}</span>
                    </div>

                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4 flex flex-col md:flex-row md:items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Voucher LUNAS10</p>
                            <p class="text-xs font-medium text-emerald-600 mt-1">Bayar lunas sekarang dan hemat 10% sebesar Rp {{ number_format($discountAmount, 0, ',', '.') }}.</p>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Total Transfer Lunas</p>
                            <p class="text-lg font-bold text-emerald-700">Rp {{ number_format($fullPaymentAmount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="relative py-3">
                        <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 border-t-2 border-dashed border-slate-100"></div>
                        <div class="relative z-10 flex justify-center">
                            <span class="px-4 bg-white text-xs font-semibold text-slate-300 uppercase tracking-widest">Pelunasan</span>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-2xl p-6 border border-green-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-green-600 shadow-sm border border-green-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-green-700 uppercase tracking-wider mb-0.5">Bayar di Driver</p>
                                <p class="text-xs font-medium text-green-600">Silakan lunasi sisa pembayaran saat penjemputan.</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-green-700 tracking-tight">Rp {{ number_format(max(0, $booking->total_harga - $dpAmount), 0, ',', '.') }}</p>
                            <p class="text-xs font-semibold text-green-600/60 uppercase tracking-wider mt-1">Cash / Transfer Driver</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-8 pb-8">
                <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('home') }}" class="flex-1 inline-flex justify-center items-center bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium px-6 py-3.5 rounded-xl transition-colors text-sm gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Kembali Ke Beranda
                    </a>
                    <a href="{{ route('booking.pembayaran', ['kode' => $booking->kode_booking]) }}" class="flex-1 inline-flex justify-center items-center bg-blue-800 hover:bg-blue-900 text-white font-semibold px-8 py-3.5 rounded-xl transition-colors text-sm shadow-sm gap-2">
                        Konfirmasi & Lanjutkan Pembayaran
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
