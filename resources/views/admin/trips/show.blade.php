@extends('layouts.admin')

@section('content')
@php
    $totalPax = $trip->detailTrips->sum(function($dt) {
        return $dt->booking ? $dt->booking->jumlah_penumpang : 0;
    });
    $capacity = $trip->armada ? $trip->armada->kapasitas : 5;
    $remainingSeats = $capacity - $totalPax;
    $estRevenue = $trip->detailTrips->sum(function($dt) {
        return $dt->booking ? $dt->booking->total_harga : 0;
    });
    $mapPoints = $trip->detailTrips
        ->filter(fn ($detail) => $detail->booking)
        ->flatMap(function ($detail) {
            $booking = $detail->booking;
            $customerName = $booking->pelanggan->nama ?? 'Penumpang';
            $points = [];

            if ($booking->latitude_jemput && $booking->longitude_jemput) {
                $points[] = [
                    'lat' => $booking->latitude_jemput,
                    'lng' => $booking->longitude_jemput,
                    'label' => $customerName,
                    'type' => 'jemput',
                    'address' => $booking->alamat_jemput,
                    'description' => $booking->kode_booking,
                ];
            }

            if ($booking->latitude_tujuan && $booking->longitude_tujuan) {
                $points[] = [
                    'lat' => $booking->latitude_tujuan,
                    'lng' => $booking->longitude_tujuan,
                    'label' => $customerName,
                    'type' => 'antar',
                    'address' => $booking->alamat_tujuan,
                    'description' => $booking->kode_booking,
                ];
            }

            return $points;
        })
        ->values();
@endphp

<div x-data="{ 
        isDriverModalOpen: false, 
        isBookingModalOpen: false, 
        driverSearch: '', 
        bookingSearch: '' 
    }" 
     class="space-y-8 font-poppins pb-12 relative">

    <!-- Breadcrumbs & Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
            <a href="{{ route('admin.trips.index') }}"
               class="flex items-center gap-2 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-blue-600 transition-colors mb-2 w-fit">
                <!-- Arrow Left SVG -->
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                </svg>
                Kembali ke Trip
            </a>
            <div class="flex flex-wrap items-center gap-4">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Detail Trip TRP-{{ str_pad($trip->id, 3, '0', STR_PAD_LEFT) }}</h1>
                <x-status-badge :status="$trip->status_trip" />
            </div>
        </div>

        <div class="flex items-center flex-wrap gap-3">
            @if($trip->status_trip !== 'cancelled' && $trip->status_trip !== 'completed')
                <!-- Form Batalkan Trip -->
                <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status_trip" value="cancelled">
                    <button type="submit" 
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan trip ini? Semua penumpang akan dikembalikan ke antrean.')"
                            class="flex items-center gap-2 px-6 py-3.5 bg-white border border-slate-200 text-red-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-red-50 hover:border-red-200 transition-all active:scale-95 shadow-sm">
                        <!-- X Circle Icon -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Batalkan Trip
                    </button>
                </form>
            @endif

            @if($trip->status_trip === 'on_trip')
                <!-- Form Selesaikan Trip -->
                <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status_trip" value="completed">
                    <button type="submit" 
                            onclick="return confirm('Apakah Anda yakin ingin menyelesaikan trip ini? Status seluruh booking akan diubah menjadi Selesai.')"
                            class="flex items-center gap-2 px-6 py-3.5 bg-blue-800 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-900 transition-all active:scale-95 shadow-xl shadow-blue-800/20">
                        <!-- Check Circle Icon -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Selesaikan Trip
                    </button>
                </form>
            @endif

            @if($trip->status_trip === 'new')
                <!-- Form Setujui Trip -->
                <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status_trip" value="ready">
                    <button type="submit" 
                            class="flex items-center gap-2 px-6 py-3.5 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all active:scale-95 shadow-xl shadow-emerald-800/20">
                        <!-- Check Icon -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                        </svg>
                        Setujui Trip
                    </button>
                </form>
            @endif

            @if($trip->status_trip === 'ready')
                <!-- Form Mulai Trip (Transisi ke On Trip oleh Admin jika diperlukan) -->
                <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status_trip" value="on_trip">
                    <button type="submit" 
                            class="flex items-center gap-2 px-6 py-3.5 bg-blue-800 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-900 transition-all active:scale-95 shadow-xl shadow-blue-800/20">
                        <!-- Play Icon -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"></path>
                        </svg>
                        Mulai Perjalanan (On Trip)
                    </button>
                </form>
            @endif

            <!-- Form Delete Trip -->
            <form action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('Apakah Anda yakin ingin menghapus trip ini? Semua data penumpang akan dikembalikan ke antrean booking.')"
                        class="flex items-center gap-2 px-6 py-3.5 bg-red-50 text-red-600 border border-red-100 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-red-100 hover:text-red-700 transition-all active:scale-95 shadow-sm">
                    <!-- Trash Icon -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                    </svg>
                    Hapus Trip
                </button>
            </form>
        </div>
    </div>

    <!-- Alert / Session Notification -->
    <x-alert />

    <!-- Top Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- Left Column: Main Trip Info Card -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 md:p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Route & Schedule Details -->
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Rute Perjalanan</p>
                        <h3 class="text-xl font-bold text-slate-800">{{ $trip->jadwal->rute->asal }} &rarr; {{ $trip->jadwal->rute->tujuan }}</h3>
                    </div>
                    <div class="flex items-center gap-8">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Berangkat</p>
                            <div class="flex items-center gap-2 text-slate-700">
                                <!-- Calendar Icon -->
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zM14.25 15h.008v.008H14.25V15zm0 2.25h.008v.008H14.25v-.008zm2.25-2.25h.008v.008H16.5V15zm0 2.25h.008v.008H16.5v-.008z"></path>
                                </svg>
                                <span class="text-sm font-semibold">{{ $trip->jadwal->tanggal_keberangkatan->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Shift / Jam</p>
                            <div class="flex items-center gap-2 text-slate-700">
                                <!-- Clock Icon -->
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm font-semibold capitalize">{{ $trip->jadwal->shift }} ({{ $trip->jadwal->jam_berangkat instanceof \DateTime ? $trip->jadwal->jam_berangkat->format('H:i') : \Carbon\Carbon::parse($trip->jadwal->jam_berangkat)->format('H:i') }} WIB)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Driver & vehicle Cards -->
                <div class="space-y-4">
                    <!-- Driver Card Info -->
                    <div class="flex items-start justify-between p-4 bg-slate-50 border border-slate-200 rounded-2xl group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 group-hover:text-blue-600 transition-colors shadow-sm shrink-0">
                                <!-- User Icon -->
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Driver</p>
                                <p class="text-sm font-black text-slate-800">{{ $trip->driver->nama_driver ?? 'Belum Ditugaskan' }}</p>
                                <p class="text-[10px] font-bold text-slate-500">{{ $trip->driver->no_hp ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Card Info -->
                    <div class="flex items-start justify-between p-4 bg-slate-50 border border-slate-200 rounded-2xl group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 group-hover:text-blue-600 transition-colors shadow-sm shrink-0">
                                <!-- Truck Icon -->
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25c0-.447-.266-.852-.676-1.03l-2.222-.962V5.25a2.25 2.25 0 00-2.25-2.25h-5.25a2.25 2.25 0 00-2.25 2.25v2.607L6.216 9.19a1.125 1.125 0 00-.676 1.03v4.5c0 .621.504 1.125 1.125 1.125h1.125m9.75 0v-4.5M6.75 14.25h12m-.75-3.75h-10.5M12 3v3.75M9.75 6.75H12"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Armada</p>
                                <p class="text-sm font-black text-slate-800">{{ $trip->armada->nomor_plat ?? 'Belum Ditentukan' }}</p>
                                <p class="text-[10px] font-bold text-slate-500">{{ $trip->armada->nama_mobil ?? '-' }} (Kapasitas: {{ $capacity }} Pax)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            @if($trip->status_trip !== 'completed' && $trip->status_trip !== 'cancelled')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-6 border-t border-slate-100">
                    <button @click="isDriverModalOpen = true"
                            class="flex items-center justify-center gap-3 py-3.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-800 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:border-slate-400 transition-all active:scale-[0.98] shadow-sm">
                        <!-- UserPlus Icon -->
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.3 20.737a12.318 12.318 0 01-6.3-1.502z"></path>
                        </svg>
                        Tugaskan / Ganti Driver
                    </button>
                    <button @click="isBookingModalOpen = true"
                            class="flex items-center justify-center gap-3 py-3.5 bg-blue-800 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-900 transition-all active:scale-[0.98] shadow-md shadow-blue-800/10">
                        <!-- Users Icon -->
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.3 20.737a12.318 12.318 0 01-6.3-1.502z"></path>
                        </svg>
                        Tugaskan Penumpang
                    </button>
                </div>
            @endif
        </div>

        <!-- Right Column: Trip Summary Card -->
        <div class="bg-slate-900 rounded-3xl p-6 md:p-8 text-white relative overflow-hidden shadow-xl shadow-slate-900/10 border border-slate-800 self-stretch flex flex-col justify-between">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/15 rounded-full -mr-16 -mt-16 blur-3xl"></div>
            
            <div class="relative z-10 space-y-8">
                <div>
                    <h3 class="text-xs font-black text-white/40 uppercase tracking-[0.2em] mb-6">Ringkasan Trip</h3>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <!-- Users Icon -->
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                                </svg>
                                <span class="text-xs font-bold text-white/60">Total Penumpang</span>
                            </div>
                            <span class="text-base font-black">{{ $totalPax }} / {{ $capacity }} PAX</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <!-- Info Icon -->
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.028M12 20.25a8.25 8.25 0 110-16.5 8.25 8.25 0 010 16.5zM12 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"></path>
                                </svg>
                                <span class="text-xs font-bold text-white/60">Kursi Tersedia</span>
                            </div>
                            <span class="text-base font-black {{ $remainingSeats <= 0 ? 'text-red-400' : 'text-amber-400' }}">
                                {{ $remainingSeats }} Kursi
                            </span>
                        </div>
                        <div class="w-full h-px bg-white/10"></div>
                        <div>
                            <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-1.5">Estimasi Pendapatan</p>
                            <h4 class="text-3xl font-black text-blue-400">Rp {{ number_format($estRevenue, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white/5 rounded-2xl p-4 border border-white/5 mt-8">
                <div class="flex items-center gap-3 mb-2">
                    <!-- Shield Icon -->
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.285z"></path>
                    </svg>
                    <span class="text-[10px] font-black uppercase tracking-widest">Catatan Operasional</span>
                </div>
                <p class="text-[11px] text-white/50 leading-relaxed font-medium">
                    Pilih driver yang tersedia untuk mengaktifkan trip. Pastikan manifes diisi sebelum driver memulai perjalanan.
                </p>
            </div>
        </div>
    </div>

    <x-map-viewer
        :points="$mapPoints"
        mapId="admin-trip-map-{{ $trip->id }}"
        height="h-[420px]"
        title="Peta Distribusi Penumpang"
        subtitle="Titik jemput dan antar pada trip ini"
    />
    <!-- Passenger Manifest Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between gap-4 flex-wrap">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em] flex items-center gap-3">
                <!-- Users Manifest Icon -->
                <svg class="w-5 h-5 text-blue-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.97 5.97 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                </svg>
                Manifes Penumpang
            </h3>
            
            <a href="#" class="text-[10px] font-black text-blue-600 hover:text-blue-800 uppercase tracking-widest hover:underline flex items-center gap-1.5 transition-colors">
                <!-- Download icon -->
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path>
                </svg>
                Cetak Manifest (PDF)
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest font-semibold">Penumpang</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest font-semibold">Kontak</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest font-semibold">Titik Jemput &amp; Tujuan</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest font-semibold">Pax</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest font-semibold">Total Biaya</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest font-semibold">Status Bayar</th>
                        @if($trip->status_trip !== 'completed' && $trip->status_trip !== 'cancelled')
                            <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest font-semibold">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($trip->detailTrips as $detail)
                        @if($detail->booking)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-5">
                                    <div>
                                        <p class="text-sm font-black text-slate-800 mb-0.5">{{ $detail->booking->pelanggan->nama }}</p>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $detail->booking->kode_booking }}</span>
                                        <p class="text-[10px] font-black text-blue-700 mt-1">Berangkat: {{ $trip->jadwal->tanggal_keberangkatan->format('d M Y') }} - {{ ucfirst($trip->jadwal->shift) }} {{ $trip->jadwal->jam_berangkat->format('H:i') }} WIB</p>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2 text-slate-600 text-xs font-semibold">
                                        <!-- Phone Icon -->
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.387a20.373 20.373 0 01-9.357-9.357c-.155-.44-.01-1.29.387-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"></path>
                                        </svg>
                                        {{ $detail->booking->pelanggan->no_hp }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 max-w-xs md:max-w-sm">
                                    <div class="space-y-1">
                                        <div class="flex items-start gap-1.5 text-slate-600 text-xs">
                                            <span class="w-2 h-2 rounded-full bg-blue-500 mt-1 shrink-0"></span>
                                            <span class="truncate" title="{{ $detail->booking->alamat_jemput }}">{{ $detail->booking->alamat_jemput }}</span>
                                        </div>
                                        <div class="flex items-start gap-1.5 text-slate-500 text-xs">
                                            <span class="w-2 h-2 rounded-full bg-green-500 mt-1 shrink-0"></span>
                                            <span class="truncate" title="{{ $detail->booking->alamat_tujuan }}">{{ $detail->booking->alamat_tujuan }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-xs font-black text-slate-800">{{ $detail->booking->jumlah_penumpang }} PAX</span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-xs font-black text-slate-800">Rp {{ number_format($detail->booking->total_harga, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase border bg-green-50 text-green-700 border-green-200">
                                        DP Lunas
                                    </span>
                                </td>
                                @if($trip->status_trip !== 'completed' && $trip->status_trip !== 'cancelled')
                                    <td class="px-6 py-5 text-right">
                                        <form action="{{ route('admin.trips.remove', [$trip->id, $detail->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Keluarkan penumpang ini dari trip?')"
                                                    class="text-red-500 hover:text-red-700 text-[10px] font-black uppercase tracking-widest hover:underline transition-all">
                                                Lepaskan
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 bg-slate-50/10">
                                <div class="max-w-md mx-auto space-y-2">
                                    <!-- Users Outline Icon -->
                                    <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5m15 6l-7.5 7.5-7.5-7.5"></path>
                                    </svg>
                                    <p class="text-[10px] font-black uppercase tracking-widest">Belum ada manifes penumpang</p>
                                    <p class="text-xs text-slate-400">Gunakan tombol "Tugaskan Penumpang" di atas untuk menambahkan penumpang dari antrean booking.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Driver Selection -->
    <div x-show="isDriverModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="isDriverModalOpen = false"></div>

        <!-- Modal Wrapper -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-xl bg-white rounded-[2rem] shadow-2xl overflow-hidden flex flex-col max-h-[85vh] border border-slate-100"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Modal Header -->
                <div class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-lg font-black text-slate-950">Tugaskan Driver</h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Pilih driver untuk mengoperasikan Trip ini</p>
                    </div>
                    <button @click="isDriverModalOpen = false" 
                            class="p-2.5 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-800 rounded-xl transition-all">
                        <!-- Close Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Search Input -->
                <div class="px-6 md:px-8 py-4 border-b border-slate-100 bg-slate-50/50 shrink-0">
                    <div class="relative">
                        <!-- Search Icon -->
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" 
                               x-model="driverSearch" 
                               placeholder="Cari driver, plat nomor, atau jenis mobil..."
                               class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <!-- Driver List -->
                <div class="flex-1 overflow-y-auto p-4 space-y-2">
                    @forelse($drivers as $driver)
                        @php
                            $dynamicStatus = $driver->dynamic_status;
                            $isCurrentDriver = ($trip->driver_id === $driver->id);
                        @endphp
                        <div x-show="driverSearch === '' || 
                                    '{{ addslashes(strtolower($driver->nama_driver)) }}'.includes(driverSearch.toLowerCase()) || 
                                    '{{ addslashes(strtolower($driver->armada->nomor_plat ?? "")) }}'.includes(driverSearch.toLowerCase()) || 
                                    '{{ addslashes(strtolower($driver->armada->nama_mobil ?? "")) }}'.includes(driverSearch.toLowerCase())"
                             class="p-4 rounded-2xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/10 transition-all flex items-center justify-between gap-4 group">
                            
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-10 h-10 bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-center text-slate-400 group-hover:text-blue-800 transition-colors shrink-0">
                                    <!-- User Icon -->
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-xs font-black text-slate-900 truncate">{{ $driver->nama_driver }}</h4>
                                    <div class="flex items-center gap-2 mt-0.5 text-[10px] font-bold text-slate-400 flex-wrap">
                                        <span>{{ $driver->armada->nomor_plat ?? '-' }}</span>
                                        <span>·</span>
                                        <span class="truncate">{{ $driver->armada->nama_mobil ?? '-' }}</span>
                                        <span>·</span>
                                        <span class="shrink-0 text-slate-500">Cap: {{ $driver->armada->kapasitas ?? '-' }} Pax</span>
                                    </div>
                                    <div class="mt-1 flex items-center gap-1.5">
                                        <x-status-badge :status="$dynamicStatus" class="!px-2 !py-0.5 !text-[8px]" />
                                        @if($isCurrentDriver)
                                            <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">Driver Aktif</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($isCurrentDriver)
                                <button disabled
                                        class="px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200">
                                    Ditugaskan
                                </button>
                            @elseif($dynamicStatus === 'tersedia')
                                <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="driver_id" value="{{ $driver->id }}">
                                    <button type="submit" 
                                            class="px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-blue-800 text-white hover:bg-blue-900 transition-all active:scale-95 shadow-sm shadow-blue-800/10">
                                        Pilih
                                    </button>
                                </form>
                            @else
                                <button disabled
                                        class="px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-slate-50 text-slate-300 border border-slate-100 cursor-not-allowed">
                                    Sibuk
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400">
                            <p class="text-sm">Tidak ada driver aktif.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Modal Footer -->
                <div class="p-6 bg-slate-50 border-t border-slate-100 text-center shrink-0">
                    <p class="text-[9px] font-bold text-slate-400">
                        * Hanya driver dengan status <span class="text-green-600 font-extrabold">Tersedia</span> yang dapat ditugaskan untuk trip ini.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Passenger / Booking Assignment -->
    <div x-show="isBookingModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="isBookingModalOpen = false"></div>

        <!-- Modal Wrapper -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative w-full max-w-xl bg-white rounded-[2rem] shadow-2xl overflow-hidden flex flex-col max-h-[85vh] border border-slate-100"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Modal Header -->
                <div class="p-6 md:p-8 border-b border-slate-100 flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-lg font-black text-slate-950">Tugaskan Penumpang</h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Alokasikan booking terverifikasi untuk Trip ini</p>
                    </div>
                    <button @click="isBookingModalOpen = false" 
                            class="p-2.5 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-800 rounded-xl transition-all">
                        <!-- Close Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Search Input -->
                <div class="px-6 md:px-8 py-4 border-b border-slate-100 bg-slate-50/50 shrink-0">
                    <div class="relative">
                        <!-- Search Icon -->
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" 
                               x-model="bookingSearch" 
                               placeholder="Cari kode booking atau nama pelanggan..."
                               class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <!-- Booking List -->
                <div class="flex-1 overflow-y-auto p-4 space-y-2">
                    @forelse($availableBookings as $booking)
                        @php
                            $isCapacityExceeded = ($booking->jumlah_penumpang > $remainingSeats);
                        @endphp
                        <div x-show="bookingSearch === '' || 
                                    '{{ addslashes(strtolower($booking->kode_booking)) }}'.includes(bookingSearch.toLowerCase()) || 
                                    '{{ addslashes(strtolower($booking->pelanggan->nama)) }}'.includes(bookingSearch.toLowerCase())"
                             class="p-4 rounded-2xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/10 transition-all flex items-center justify-between gap-4 group">
                            
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest shrink-0">{{ $booking->kode_booking }}</span>
                                    <span class="w-1.5 h-1.5 bg-slate-200 rounded-full"></span>
                                    <span class="text-[9px] font-black text-blue-800 bg-blue-50 px-2 py-0.5 rounded-md uppercase border border-blue-100 shrink-0">{{ $booking->jumlah_penumpang }} PAX</span>
                                </div>
                                <h4 class="text-xs font-black text-slate-900 truncate">{{ $booking->pelanggan->nama }}</h4>
                                <p class="text-[9px] font-black text-blue-700 mt-1">Berangkat: {{ $trip->jadwal->tanggal_keberangkatan->format('d M Y') }} - {{ ucfirst($trip->jadwal->shift) }} {{ $trip->jadwal->jam_berangkat->format('H:i') }} WIB</p>
                                <div class="mt-2 p-2 bg-slate-50 border border-slate-100 rounded-lg space-y-1 text-[9px] font-bold text-slate-500">
                                    <p class="truncate"><span class="text-[8px] uppercase tracking-wider text-blue-500 font-black">Jemput:</span> {{ $booking->alamat_jemput }}</p>
                                    <p class="truncate"><span class="text-[8px] uppercase tracking-wider text-green-500 font-black">Tujuan:</span> {{ $booking->alamat_tujuan }}</p>
                                </div>
                            </div>

                            @if($isCapacityExceeded)
                                <button disabled
                                        title="Kursi tersisa tidak mencukupi"
                                        class="px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-red-50 text-red-400 border border-red-100 cursor-not-allowed">
                                    Penuh
                                </button>
                            @else
                                <form action="{{ route('admin.trips.assign', $trip->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                    <button type="submit" 
                                            class="px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-blue-800 text-white hover:bg-blue-900 transition-all active:scale-95 shadow-sm shadow-blue-800/10">
                                        Tugaskan
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="py-16 text-center text-slate-400">
                            <!-- Shield Check Icon -->
                            <svg class="w-12 h-12 mx-auto text-slate-200 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"></path>
                            </svg>
                            <p class="text-xs font-black uppercase tracking-widest text-slate-400">Tidak ada booking terverifikasi</p>
                            <p class="text-[11px] text-slate-400/80 mt-1 max-w-xs mx-auto">Semua booking pada jadwal ini telah dialokasikan atau belum dikonfirmasi pembayarannya oleh admin.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Modal Footer -->
                <div class="p-6 bg-slate-50 border-t border-slate-100 shrink-0 text-center">
                    <p class="text-[9px] font-bold text-slate-400">
                        * Hanya menampilkan booking dengan status <span class="text-blue-800 font-extrabold">Dikonfirmasi</span> pada jadwal yang bersangkutan.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
