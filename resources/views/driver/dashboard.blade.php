@extends('layouts.driver')

@section('content')
    @php
        $passengersJson = [];
        if ($activeTrip) {
            $passengersJson = $activeTrip->detailTrips->map(function($dt) {
                return [
                    'id' => $dt->id,
                    'name' => $dt->booking->pelanggan->nama ?? 'Penumpang',
                    'status_jemput' => $dt->status_jemput,
                    'status_antar' => $dt->status_antar,
                    'pickup_lat' => (float) $dt->booking->latitude_jemput,
                    'pickup_lng' => (float) $dt->booking->longitude_jemput,
                    'dest_lat' => (float) $dt->booking->latitude_tujuan,
                    'dest_lng' => (float) $dt->booking->longitude_tujuan,
                    'pickup_addr' => $dt->booking->alamat_jemput,
                    'dest_addr' => $dt->booking->alamat_tujuan,
                ];
            })->toArray();
        }
    @endphp

    <div x-data="{ 
        dropoffModalPassenger: null,
        activeTab: 'manifest'
    }" 
    x-init="$watch('activeTab', value => {
        if (value === 'map' && window.driverMap) {
            setTimeout(() => {
                window.driverMap.invalidateSize();
                const passengers = {{ json_encode($passengersJson) }};
                if (passengers && passengers.length > 0) {
                    const bounds = passengers.map(p => {
                        const isPickedUp = p.status_antar === 'sudah_diantar' || p.status_jemput === 'sudah_dijemput';
                        const lat = isPickedUp ? p.dest_lat : p.pickup_lat;
                        const lng = isPickedUp ? p.dest_lng : p.pickup_lng;
                        return [lat, lng];
                    }).filter(b => b[0] && b[1]);
                    if (bounds.length > 0) {
                        window.driverMap.fitBounds(bounds, { padding: [50, 50] });
                    }
                }
            }, 100);
        }
    })"
    class="relative w-full max-w-full min-w-0">

        @if (session('success'))
            <x-alert type="success" :message="session('success')" class="mb-6" />
        @endif

        @if (session('error'))
            <x-alert type="danger" :message="session('error')" class="mb-6" />
        @endif

        @if($activeTrip)
            <!-- ACTIVE TRIP BOARD (DASHBOARD OPERASIONAL) -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
            <style>
                .leaflet-container {
                    font-family: 'Poppins', sans-serif;
                }
            </style>

            <!-- Header Info -->
            <div class="bg-gradient-to-br from-[#0b1329] via-[#0d1b3e] to-[#070c1b] text-white p-6 sm:p-8 md:p-10 relative overflow-hidden rounded-[2rem] mb-8 shadow-xl border border-slate-800/80">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 rounded-full blur-[80px]"></div>
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div class="space-y-3 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-[10px] font-black bg-gradient-to-r from-blue-600 to-blue-500 text-white px-3 py-1 rounded-xl uppercase tracking-widest shadow-md shadow-blue-500/10">TRP-{{ str_pad($activeTrip->id, 3, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-[10px] font-black text-slate-300 bg-white/5 border border-white/10 px-3 py-1 rounded-xl uppercase tracking-widest">{{ $activeTrip->armada->nama_mobil ?? '-' }} • {{ $activeTrip->armada->nomor_plat ?? '-' }}</span>
                            </div>
                            <h1 class="text-xl md:text-3xl font-extrabold tracking-tight uppercase leading-tight bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-100 to-slate-200">
                                {{ $activeTrip->jadwal->rute->asal }} &rarr; {{ $activeTrip->jadwal->rute->tujuan }}
                            </h1>
                            <div class="flex flex-wrap items-center gap-4 sm:gap-6 pt-1 text-slate-400">
                                <div class="flex items-center gap-2">
                                    <!-- Calendar Icon -->
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-xs font-semibold text-slate-300">{{ $activeTrip->jadwal->tanggal_keberangkatan->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <!-- Clock Icon -->
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-xs font-semibold text-slate-300">Shift {{ ucfirst($activeTrip->jadwal->shift) }} • {{ $activeTrip->jadwal->jam_berangkat->format('H:i') }} WIB</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <!-- Users Icon -->
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="text-xs font-semibold text-slate-300">{{ $activeTrip->detailTrips->count() }} Penumpang</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 mt-4 lg:mt-0 shrink-0">
                            @if($activeTrip->status_trip === \App\Models\Trip::STATUS_READY)
                                <form action="{{ route('driver.trips.start', $activeTrip->id) }}" method="POST" class="w-full sm:w-auto">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-600 text-white px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-blue-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                                        <!-- Navigation Icon -->
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        Mulai Perjalanan
                                    </button>
                                </form>
                            @else
                                <div class="flex items-center gap-3 w-full sm:w-auto">
                                    <div class="px-5 py-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md text-left flex-1 sm:flex-initial">
                                        <p class="text-[8px] font-black text-blue-400 uppercase tracking-widest mb-0.5">Status Perjalanan</p>
                                        <p class="text-xs font-black text-white uppercase">{{ str_replace('_', ' ', $activeTrip->status_trip) }}</p>
                                    </div>
                                    @php
                                        $allAntarDropoff = $activeTrip->detailTrips->every(fn($dt) => $dt->status_antar === 'sudah_diantar');
                                    @endphp
                                    @if($allAntarDropoff && $activeTrip->status_trip === \App\Models\Trip::STATUS_ON_TRIP)
                                        <form action="{{ route('driver.trips.complete', $activeTrip->id) }}" method="POST" class="w-full sm:w-auto">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-600 text-white px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-emerald-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2 animate-pulse">
                                                <!-- Check Circle Icon -->
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Selesaikan Trip
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Switcher (Mobile Only) -->
            <div class="flex lg:hidden bg-slate-100 p-1.5 rounded-2xl mb-6 border border-slate-200 shadow-inner">
                <button type="button" 
                        @click="activeTab = 'manifest'"
                        :class="activeTab === 'manifest' ? 'bg-white text-blue-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                        class="flex-1 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Manifest
                </button>
                <button type="button" 
                        @click="activeTab = 'map'"
                        :class="activeTab === 'map' ? 'bg-white text-blue-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                        class="flex-1 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    Peta & Progres
                </button>
            </div>

            <!-- Dashboard Grid split view -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start w-full">
                
                <!-- Kiri: Manifest Penumpang -->
                <div :class="activeTab === 'manifest' ? 'block' : 'hidden lg:block'" class="lg:col-span-5 space-y-6 min-w-0 w-full">
                    <div class="flex items-center justify-between px-2">
                        <h2 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em] flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Manifest Penumpang
                        </h2>
                        <span class="text-[10px] font-black text-blue-600 bg-blue-50 border border-blue-100 px-3 py-1 rounded-full uppercase tracking-wider">{{ $activeTrip->detailTrips->count() }} Orang</span>
                    </div>

                    <div class="space-y-6 max-h-[calc(100vh-280px)] overflow-y-auto pr-1 no-scrollbar w-full">
                        @foreach ($activeTrip->detailTrips as $index => $dt)
                            @php
                                $b = $dt->booking;
                                $p = $b->pelanggan;
                                $isWaiting = $dt->status_jemput === 'belum';
                                $isPickedUp = $dt->status_jemput === 'sudah_dijemput' && $dt->status_antar === 'belum';
                                $isDroppedOff = $dt->status_antar === 'sudah_diantar';
                                $remainingFare = max(0, $b->total_harga - 50000);
                                $hasPelunasan = $b->pembayaran()->where('jenis_pembayaran', 'pelunasan')->where('status_pembayaran', 'terverifikasi')->exists();
                            @endphp

                            <div @click="if(window.recenterDriverMap) window.recenterDriverMap({{ $isPickedUp ? ($b->latitude_tujuan ?? 'null') : ($b->latitude_jemput ?? 'null') }}, {{ $isPickedUp ? ($b->longitude_tujuan ?? 'null') : ($b->longitude_jemput ?? 'null') }})"
                                 class="bg-white rounded-3xl border transition-all duration-300 cursor-pointer overflow-hidden relative group {{ ($b->latitude_jemput && $b->longitude_jemput) ? 'hover:border-blue-400 hover:shadow-md' : 'hover:border-slate-300' }} border-slate-200 shadow-sm w-full">
                                
                                <div class="p-6">
                                    <!-- Card Header -->
                                    <div class="flex justify-between items-start gap-4 mb-4">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-slate-900 to-slate-800 text-white flex items-center justify-center font-black text-sm shrink-0">
                                                {{ substr($p->nama ?? 'P', 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <h3 class="text-sm font-black text-slate-900 leading-tight truncate">{{ $p->nama ?? 'No Name' }}</h3>
                                                <p class="text-[9px] font-bold text-slate-400 mt-0.5 truncate">{{ $b->jumlah_penumpang }} PAX • {{ $b->kode_booking }}</p>
                                            </div>
                                        </div>

                                        <span class="shrink-0 px-3 py-1 rounded-xl text-[8px] font-black uppercase tracking-widest border {{ $isWaiting ? 'bg-amber-50 text-amber-600 border-amber-100' : ($isPickedUp ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100') }}">
                                            {{ $isWaiting ? 'Menunggu' : ($isPickedUp ? 'Dalam Armada' : 'Sudah Tiba') }}
                                        </span>
                                    </div>

                                    <!-- Locations details -->
                                    <div class="space-y-4 mb-4">
                                        <div class="flex gap-3 text-xs">
                                            <div class="w-6 h-6 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                                                <!-- Pin Icon -->
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Penjemputan</p>
                                                <p class="text-[10px] font-semibold text-slate-700 leading-tight italic truncate" title="{{ $b->alamat_jemput }}">"{{ $b->alamat_jemput }}"</p>
                                            </div>
                                        </div>

                                        <div class="flex gap-3 text-xs">
                                            <div class="w-6 h-6 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0">
                                                <!-- Flag Icon -->
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider mb-0.5">Tujuan</p>
                                                <p class="text-[10px] font-semibold text-slate-700 leading-tight italic truncate" title="{{ $b->alamat_tujuan }}">"{{ $b->alamat_tujuan }}"</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Fare info pane -->
                                    <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl mb-4 space-y-2">
                                        <div class="flex justify-between items-center text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                            <span>Tarif Total</span>
                                            <span>Rp {{ number_format($b->total_harga, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between items-center text-[9px] font-black text-blue-500 uppercase tracking-widest">
                                            <span>DP Lunas</span>
                                            <span>-Rp 50.000</span>
                                        </div>
                                        <div class="border-t border-slate-200 pt-2 flex justify-between items-center text-xs font-black">
                                            <span class="text-[9px] text-slate-900 uppercase tracking-widest">Sisa Tagihan Cash</span>
                                            <span class="{{ ($isDroppedOff || $hasPelunasan) ? 'text-slate-400 line-through' : 'text-rose-600 font-extrabold' }}">
                                                Rp {{ number_format($remainingFare, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        @if($isDroppedOff || $hasPelunasan)
                                            <div class="flex items-center justify-center gap-1 text-emerald-600 text-[9px] font-black uppercase tracking-wider pt-1 border-t border-emerald-100 mt-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                                                </svg>
                                                Lunas Terbayar
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Quick actions -->
                                    <div class="grid grid-cols-2 gap-3 mb-3">
                                        <button type="button"
                                                @click.stop="
                                                    const url = 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent('{{ $isPickedUp ? $b->alamat_tujuan : $b->alamat_jemput }}');
                                                    window.open(url, '_blank');
                                                "
                                                class="flex items-center justify-center gap-1.5 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all active:scale-[0.97]">
                                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                            </svg>
                                            Peta Rute
                                        </button>

                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->no_hp) }}"
                                           target="_blank"
                                           @click.stop
                                           class="flex items-center justify-center gap-1.5 py-3 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-slate-800 transition-all active:scale-[0.97] shadow-sm">
                                            <!-- WhatsApp SVG -->
                                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.665.989 3.3 1.503 4.94 1.505 5.548 0 10.064-4.512 10.068-10.066.002-2.69-1.047-5.216-2.951-7.121-1.905-1.904-4.43-2.951-7.125-2.952-5.55 0-10.066 4.512-10.07 10.068-.001 1.884.5 3.73 1.453 5.392L1.085 21.03l6.562-1.876zm7.915-12.28c-.19-.424-.393-.43-.574-.438-.149-.007-.32-.007-.492-.007-.172 0-.453.064-.69.322-.237.258-.905.884-.905 2.152 0 1.268.922 2.497 1.05 2.667.129.17 1.814 2.769 4.394 3.882.613.265 1.092.423 1.465.54.618.196 1.18.168 1.625.102.496-.074 1.52-.62 1.734-1.22.215-.6.215-1.115.15-1.22-.064-.105-.237-.17-.502-.303-.264-.132-1.562-.771-1.802-.857-.24-.086-.414-.13-.59.13-.176.258-.68.857-.834 1.032-.154.172-.308.194-.573.062-.265-.13-1.118-.412-2.13-1.31-.786-.701-1.317-1.567-1.472-1.832-.154-.264-.016-.407.117-.539.12-.118.264-.308.396-.462.132-.155.176-.264.264-.44.088-.177.044-.33-.022-.462-.066-.132-.574-1.385-.788-1.898z"/>
                                            </svg>
                                            Chat WA
                                        </a>
                                    </div>

                                    <!-- Action state flows -->
                                    @if($activeTrip->status_trip === \App\Models\Trip::STATUS_ON_TRIP)
                                        @if($isWaiting)
                                            <form action="{{ route('driver.trips.pickup', [$activeTrip->id, $dt->id]) }}" method="POST" @click.stop class="w-full">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-3.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-500/10 transition-all active:scale-[0.97]">
                                                    Naikkan Penumpang
                                                </button>
                                            </form>
                                        @elseif($isPickedUp)
                                            <button type="button"
                                                    @click.stop="dropoffModalPassenger = {
                                                        id: '{{ $dt->id }}',
                                                        name: '{{ $p->nama ?? '' }}',
                                                        pax: {{ $b->jumlah_penumpang }},
                                                        destination: '{{ $b->alamat_tujuan }}',
                                                        remaining: {{ $remainingFare }},
                                                        actionUrl: '{{ route('driver.trips.dropoff', [$activeTrip->id, $dt->id]) }}',
                                                        hasPelunasan: {{ $hasPelunasan ? 'true' : 'false' }}
                                                    }"
                                                    class="w-full flex items-center justify-center gap-1.5 py-3.5 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/10 transition-all active:scale-[0.97]">
                                                Turunkan & Selesai
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Kanan: Map & Stepper Progress -->
                <div :class="activeTab === 'map' ? 'block' : 'hidden lg:block'" class="lg:col-span-7 space-y-6 min-w-0 w-full">
                    <div class="flex items-center justify-between px-2">
                        <h2 class="text-xs font-black text-slate-800 uppercase tracking-[0.2em] flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            Peta Rute Perjalanan
                        </h2>
                        <div class="px-3.5 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded-full flex items-center gap-1.5 text-[9px] font-black uppercase tracking-wider">
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></div>
                            GPS Aktif
                        </div>
                    </div>

                    <!-- Map Container -->
                    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm h-[460px] relative w-full">
                        <div id="driver-map" style="height: 100%; width: 100%; z-index: 10;"></div>
                        
                        <!-- Floating Overlay -->
                        <div class="absolute bottom-4 left-4 right-4 z-[400] bg-slate-900/95 backdrop-blur-md p-4 rounded-2xl border border-white/10 text-white flex items-center justify-between shadow-2xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Operasi Sekarang</p>
                                    <p class="text-xs font-black tracking-tight leading-tight">
                                        @if($activeTrip->status_trip === \App\Models\Trip::STATUS_READY)
                                            Siap Menanti Perjalanan Dimulai
                                        @elseif($activeTrip->detailTrips->contains(fn($dt) => $dt->status_jemput === 'belum'))
                                            Mengambil Penumpang (Pickup Mode)
                                        @else
                                            Mengantar Penumpang (Delivery Mode)
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trip Stepper Progress -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm w-full">
                        <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 text-center">Progres Perjalanan</h3>
                        @php
                            $isReady = $activeTrip->status_trip === \App\Models\Trip::STATUS_READY;
                            $isOnTrip = $activeTrip->status_trip === \App\Models\Trip::STATUS_ON_TRIP;
                            $hasWaiting = $activeTrip->detailTrips->contains(fn($dt) => $dt->status_jemput === 'belum');
                            $hasDelivery = $activeTrip->detailTrips->contains(fn($dt) => $dt->status_jemput === 'sudah_dijemput' && $dt->status_antar === 'belum');
                            $isDone = $activeTrip->status_trip === \App\Models\Trip::STATUS_COMPLETED;
                        @endphp
                        <div class="flex items-center justify-between max-w-md mx-auto relative px-2">
                            <div class="absolute top-1/2 left-0 w-full h-0.5 bg-slate-100 -translate-y-1/2"></div>
                            <div class="absolute top-1/2 left-0 h-0.5 bg-gradient-to-r from-blue-600 to-blue-500 -translate-y-1/2 transition-all duration-500"
                                 style="width: {{ $isReady ? '0%' : ($isOnTrip && $hasWaiting ? '33%' : ($isOnTrip && !$hasWaiting ? '66%' : '100%')) }}"></div>
                            
                            <!-- Step 1: Ready -->
                            <div class="relative z-10 flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center border-4 border-white shadow-md text-xs font-black transition-all {{ !$isReady ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-400' }}">
                                    1
                                </div>
                                <span class="text-[8px] font-black uppercase mt-1.5 text-slate-400">Ready</span>
                            </div>

                            <!-- Step 2: Jemput -->
                            <div class="relative z-10 flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center border-4 border-white shadow-md text-xs font-black transition-all {{ ($isOnTrip && $hasWaiting) || ($isOnTrip && !$hasWaiting) || $isDone ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-400' }}">
                                    2
                                </div>
                                <span class="text-[8px] font-black uppercase mt-1.5 text-slate-400">Jemput</span>
                            </div>

                            <!-- Step 3: Antar -->
                            <div class="relative z-10 flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center border-4 border-white shadow-md text-xs font-black transition-all {{ ($isOnTrip && !$hasWaiting) || $isDone ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-400' }}">
                                    3
                                </div>
                                <span class="text-[8px] font-black uppercase mt-1.5 text-slate-400">Antar</span>
                            </div>

                            <!-- Step 4: Selesai -->
                            <div class="relative z-10 flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center border-4 border-white shadow-md text-xs font-black transition-all {{ $isDone ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-400' }}">
                                    4
                                </div>
                                <span class="text-[8px] font-black uppercase mt-1.5 text-slate-400">Selesai</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dropoff Modal Confirmation Alpine -->
            <template x-if="dropoffModalPassenger">
                <div class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                    <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden p-8 sm:p-10 text-center border border-slate-100"
                         @click.outside="dropoffModalPassenger = null">
                        <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-[1.5rem] flex items-center justify-center mx-auto mb-4 shadow-xl shadow-emerald-500/10">
                            <!-- Checkmark SVG -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-950 mb-1">Konfirmasi Selesai Antar</h3>
                        <p class="text-xs text-slate-400 mb-6 leading-relaxed px-4">Pastikan penumpang sudah sampai di titik tujuan dan sisa pelunasan cash telah diterima.</p>
                        
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 text-left mb-6 space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-400 uppercase font-black text-[9px]">Pelanggan</span>
                                <span class="text-slate-800 font-bold" x-text="dropoffModalPassenger.name + ' (' + dropoffModalPassenger.pax + ' Org)'"></span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-slate-400 uppercase font-black text-[9px] shrink-0">Alamat Antar</span>
                                <span class="text-slate-700 text-right leading-tight italic truncate" x-text="dropoffModalPassenger.destination"></span>
                            </div>
                            <div class="border-t border-slate-200 pt-3 flex justify-between items-center text-sm font-black mt-2">
                                <span class="text-slate-950 uppercase text-[9px]">Sisa Tagihan Cash</span>
                                <span class="text-emerald-600 text-base font-extrabold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(dropoffModalPassenger.remaining)"></span>
                            </div>
                        </div>

                        <form :action="dropoffModalPassenger.actionUrl" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-2 gap-4">
                                <button type="button" @click="dropoffModalPassenger = null"
                                        class="py-3.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20 transition-all active:scale-[0.98]">
                                    Konfirmasi & Lunas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        @else
            <!-- STATS / WELCOME SCREEN FOR DRIVERS WITH NO ACTIVE TRIP -->
            <div class="py-4">
                <div class="bg-white overflow-hidden shadow-sm rounded-3xl border border-slate-200">
                    <div class="p-6 sm:p-10 text-slate-900">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-inner shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black uppercase tracking-wider text-slate-800 leading-tight">Selamat Datang, {{ Auth::user()->name }}!</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Portal Driver Singgalang Jaya Travel</p>
                            </div>
                        </div>
                        
                        <p class="text-xs font-bold text-slate-500 mb-8 leading-relaxed max-w-2xl">
                            Anda telah berhasil masuk ke panel operasional driver. Di sini Anda dapat memantau trip yang ditugaskan kepada Anda, melihat manifest penumpang, dan melakukan navigasi titik jemput/antar penumpang secara real-time.
                        </p>

                        <!-- Overview stats cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                            <div class="bg-slate-50 border border-slate-200/60 p-6 rounded-2xl">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                                    <!-- Car SVG -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Trip Selesai</p>
                                <h4 class="text-lg font-black text-slate-900">{{ $stats['total_trips'] }} Perjalanan</h4>
                            </div>

                            <div class="bg-slate-50 border border-slate-200/60 p-6 rounded-2xl">
                                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4">
                                    <!-- Users SVG -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Penumpang</p>
                                <h4 class="text-lg font-black text-slate-900">{{ $stats['total_passengers'] }} Orang</h4>
                            </div>

                            <div class="bg-slate-50 border border-slate-200/60 p-6 rounded-2xl sm:col-span-2 lg:col-span-1">
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4">
                                    <!-- Wallet SVG -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                </div>
                                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Estimasi Pendapatan Cash</p>
                                <h4 class="text-lg font-black text-emerald-600">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h4>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-[#0B1329] text-white rounded-2xl p-6 relative overflow-hidden flex flex-col justify-between border border-slate-800 min-w-0">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/10 rounded-full blur-[40px]"></div>
                                <div class="relative z-10">
                                    <h4 class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-4">Tugas Saat Ini</h4>
                                    <div class="flex items-center gap-3 text-slate-400 italic text-xs font-semibold">
                                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <span class="truncate">Belum ada trip aktif yang ditugaskan kepada Anda hari ini.</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-6 min-w-0">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Informasi Kendaraan</h4>
                                @if($driver && $driver->armada)
                                    <div class="space-y-3 text-xs font-semibold text-slate-600">
                                        <div class="flex justify-between border-b border-slate-200 pb-2">
                                            <span class="text-slate-400 uppercase text-[9px] tracking-wider">Merk Mobil</span>
                                            <span class="text-slate-900 font-bold uppercase">{{ $driver->armada->nama_mobil }}</span>
                                        </div>
                                        <div class="flex justify-between border-b border-slate-200 pb-2">
                                            <span class="text-slate-400 uppercase text-[9px] tracking-wider">Nomor Plat</span>
                                            <span class="text-slate-900 font-bold uppercase">{{ $driver->armada->nomor_plat }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-400 uppercase text-[9px] tracking-wider">Kapasitas</span>
                                            <span class="text-slate-900 font-bold uppercase">{{ $driver->armada->kapasitas }} PAX</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-3 text-slate-400 italic text-xs font-semibold">
                                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <span>Profil driver Anda atau tautan kendaraan belum diatur oleh admin.</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    @if($activeTrip)
        <!-- LEAFLET MAP OPERATIONS SCRIPT -->
        <script>
            let driverMap = null;
            document.addEventListener('DOMContentLoaded', function () {
                const mapEl = document.getElementById('driver-map');
                if (!mapEl) return;

                // Center Map to the first passenger's location initially (or default)
                driverMap = L.map('driver-map').setView([-0.4650, 100.3980], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(driverMap);

                const passengers = @json($passengersJson);
                const markers = [];
                let bounds = [];

                passengers.forEach(p => {
                    const isPickedUp = p.status_antar === 'sudah_diantar' || p.status_jemput === 'sudah_dijemput';
                    const lat = isPickedUp ? p.dest_lat : p.pickup_lat;
                    const lng = isPickedUp ? p.dest_lng : p.pickup_lng;
                    const label = isPickedUp ? 'Titik Antar: ' + p.dest_addr : 'Titik Jemput: ' + p.pickup_addr;
                    const color = isPickedUp ? '#10b981' : '#2563eb'; // Green for dropoff, Blue for pickup

                    if (lat && lng) {
                        const iconHtml = `<div style="background-color: ${color}; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 10px;">${p.name.charAt(0)}</div>`;
                        const customIcon = L.divIcon({
                            className: 'custom-div-icon',
                            html: iconHtml,
                            iconSize: [24, 24],
                            iconAnchor: [12, 12]
                        });

                        const marker = L.marker([lat, lng], { icon: customIcon })
                            .addTo(driverMap)
                            .bindPopup(`
                                <div class="p-1 font-poppins min-w-[180px]">
                                    <p class="text-[10px] font-black uppercase tracking-widest ${p.status_jemput === 'sudah_dijemput' ? 'text-emerald-600' : 'text-blue-600'}">${p.status_jemput === 'sudah_dijemput' ? 'Tujuan' : 'Jemput'}</p>
                                    <p class="text-xs font-black text-slate-900 mt-1">${p.name}</p>
                                    <p class="text-[10px] font-semibold text-slate-500 leading-tight mt-1">${label}</p>
                                </div>
                            `);
                        
                        markers.push(marker);
                        bounds.push([lat, lng]);
                    }
                });

                // Zoom map to fit all bounds
                if (bounds.length > 0) {
                    driverMap.fitBounds(bounds, { padding: [50, 50] });
                }
            });

            window.recenterDriverMap = function(lat, lng) {
                if (lat && lng && driverMap) {
                    driverMap.setView([lat, lng], 15);
                }
            };
        </script>
    @endif
@endsection