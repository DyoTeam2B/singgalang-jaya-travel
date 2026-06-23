@extends('layouts.admin')

@section('content')
    <div x-data="{ assignBookingId: null, assignBookingCode: '', tripsForAssign: [] }" class="space-y-8 font-poppins">

        <!-- Header Section -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-2">Operasional Trip</p>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Manajemen Trip Aktif</h1>
                <p class="text-sm font-bold text-slate-400 mt-1">Atur alokasi armada, penugasan driver, dan manifes penumpang.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.trips.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-600/10 flex items-center gap-2 transition-all active:scale-[0.98]">
                    <!-- Plus Icon -->
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                    Trip Baru
                </a>
            </div>
        </div>

        <!-- Session Flash Notification -->
        <x-alert />

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-2 bg-slate-100/60 p-1.5 rounded-2xl w-fit border border-slate-200/60 overflow-x-auto no-scrollbar shadow-inner max-w-full">
            <a href="{{ route('admin.trips.index', ['status' => 'new', 'search' => $search]) }}"
               class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $status === 'new' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/50' : 'text-slate-400 hover:text-slate-600 hover:bg-white/50' }}">
                Baru Dibuat
                <span class="ml-3 px-2 py-0.5 rounded-lg text-[9px] {{ $status === 'new' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                    {{ $counts['new'] }}
                </span>
            </a>
            <a href="{{ route('admin.trips.index', ['status' => 'ready', 'search' => $search]) }}"
               class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $status === 'ready' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/50' : 'text-slate-400 hover:text-slate-600 hover:bg-white/50' }}">
                Siap Keberangkatan
                <span class="ml-3 px-2 py-0.5 rounded-lg text-[9px] {{ $status === 'ready' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                    {{ $counts['ready'] }}
                </span>
            </a>
            <a href="{{ route('admin.trips.index', ['status' => 'on_trip', 'search' => $search]) }}"
               class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $status === 'on_trip' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/50' : 'text-slate-400 hover:text-slate-600 hover:bg-white/50' }}">
                Dalam Perjalanan
                <span class="ml-3 px-2 py-0.5 rounded-lg text-[9px] {{ $status === 'on_trip' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                    {{ $counts['on_trip'] }}
                </span>
            </a>
            <a href="{{ route('admin.trips.index', ['status' => 'completed', 'search' => $search]) }}"
               class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $status === 'completed' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/50' : 'text-slate-400 hover:text-slate-600 hover:bg-white/50' }}">
                Selesai
                <span class="ml-3 px-2 py-0.5 rounded-lg text-[9px] {{ $status === 'completed' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                    {{ $counts['completed'] }}
                </span>
            </a>
            <a href="{{ route('admin.trips.index', ['status' => 'cancelled', 'search' => $search]) }}"
               class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $status === 'cancelled' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/50' : 'text-slate-400 hover:text-slate-600 hover:bg-white/50' }}">
                Dibatalkan
                <span class="ml-3 px-2 py-0.5 rounded-lg text-[9px] {{ $status === 'cancelled' ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500' }}">
                    {{ $counts['cancelled'] }}
                </span>
            </a>
        </div>

        <!-- Layout Grid: Left (Booking Queue), Right (Trip Cards) -->
        <div class="flex flex-col xl:flex-row gap-8 items-start w-full min-w-0">

            <!-- Left Panel: Verified Booking Queue -->
            <div class="w-full xl:w-[380px] shrink-0 flex flex-col">
                <div class="flex items-center justify-between mb-4 px-5 py-4 bg-white rounded-2xl border border-slate-200/80 shadow-sm">
                    <h2 class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-2">
                        <!-- Shield Verified Icon -->
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.285z"></path>
                        </svg>
                        Antrean Booking
                    </h2>
                    <span class="text-[10px] font-black text-slate-400 uppercase">{{ count($bookings) }} Penumpang</span>
                </div>

                <div class="space-y-4 max-h-[600px] overflow-y-auto pr-1">
                    @forelse($bookings as $booking)
                        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover:border-blue-500/30 hover:shadow-md transition-all space-y-4 relative group">
                            <!-- Booking Header -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $booking->kode_booking }}</span>
                                    <span class="w-1.5 h-1.5 bg-slate-200 rounded-full"></span>
                                    <span class="text-[9px] font-black text-green-600 bg-green-50 px-2 py-0.5 rounded-md uppercase border border-green-100">DP Terverifikasi</span>
                                </div>
                                <div class="px-2 py-0.5 bg-blue-800 text-white rounded-lg text-[9px] font-black uppercase tracking-widest">
                                    {{ $booking->jumlah_penumpang }} PAX
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <div>
                                <h4 class="text-sm font-black text-slate-900">{{ $booking->pelanggan->nama }}</h4>
                                <p class="text-[10px] font-semibold text-slate-400 flex items-center gap-1 mt-0.5">
                                    <!-- Phone Icon -->
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.387a20.373 20.373 0 01-9.357-9.357c-.155-.44-.01-1.29.387-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"></path>
                                    </svg>
                                    {{ $booking->pelanggan->no_hp }}
                                </p>
                            </div>

                            <!-- Schedule -->
                            <div class="grid grid-cols-2 gap-2 text-[10px] font-bold">
                                <div class="p-3 bg-blue-50 border border-blue-100 rounded-xl text-blue-800">
                                    <p class="text-[8px] uppercase tracking-widest text-blue-500 mb-1">Tanggal Berangkat</p>
                                    <p>{{ $booking->jadwal->tanggal_keberangkatan->format('d M Y') }}</p>
                                </div>
                                <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl text-slate-700">
                                    <p class="text-[8px] uppercase tracking-widest text-slate-400 mb-1">Shift / Jam</p>
                                    <p>{{ ucfirst($booking->jadwal->shift) }} - {{ $booking->jadwal->jam_berangkat->format('H:i') }} WIB</p>
                                </div>
                            </div>

                            <!-- Addresses -->
                            <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl space-y-2 text-[10px] font-bold text-slate-600">
                                <div class="flex items-start gap-2">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 mt-1 shrink-0"></div>
                                    <p class="truncate" title="{{ $booking->alamat_jemput }}">{{ $booking->alamat_jemput }}</p>
                                </div>
                                <div class="flex items-start gap-2">
                                    <div class="w-2 h-2 rounded-full bg-green-500 mt-1 shrink-0"></div>
                                    <p class="truncate" title="{{ $booking->alamat_tujuan }}">{{ $booking->alamat_tujuan }}</p>
                                </div>
                            </div>

                            <!-- Action -->
                            @php
                                $tripsForThisBooking = collect($booking->assignable_trips ?? []);
                            @endphp

                            @if($tripsForThisBooking->count() > 0)
                                <button @click="
                                            assignBookingId = {{ $booking->id }};
                                            assignBookingCode = '{{ $booking->kode_booking }}';
                                            tripsForAssign = {{ json_encode($tripsForThisBooking) }};
                                        "
                                        class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5 transition-all shadow-md shadow-blue-600/10 active:scale-[0.98]">
                                    Assign ke Trip
                                </button>
                            @else
                                <a href="{{ route('admin.trips.create', ['jadwal_id' => $booking->jadwal_id]) }}"
                                   class="w-full py-3 bg-slate-50 border border-slate-200/60 hover:bg-slate-100 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5 transition-all active:scale-[0.98] text-center">
                                    Buat Trip Baru
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="py-12 px-6 flex flex-col items-center justify-center text-center opacity-40 bg-slate-50/50 rounded-2xl border-2 border-dashed border-slate-200">
                            <!-- Clipboard List Icon -->
                            <svg class="w-10 h-10 mb-3 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 0A48.536 48.536 0 0112 3m0 0c2.917 0 5.747.294 8.5.862m-21 1.402L3 9m0 0l3 3m-3-3h15.75M3 12m0 0l3-3m-3 3h15.75M3 15m0 0l3 3m-3-3h15.75"></path>
                            </svg>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Tidak ada antrean booking</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Panel: Trip Cards & Search -->
            <div class="flex-1 w-full min-w-0 flex flex-col space-y-6">

                <!-- Search/Filter Bar -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
                    <form method="GET" action="{{ route('admin.trips.index') }}" class="flex flex-wrap gap-4 items-center">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <div class="relative flex-1 min-w-[240px]">
                            <!-- Search Icon -->
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari driver, plat nomor, kota asal/tujuan..."
                                   class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200/60 rounded-xl text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-600/10 focus:border-blue-500/30 transition-all shadow-sm">
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black uppercase tracking-widest px-6 py-3.5 rounded-xl transition-all shadow-md active:scale-95">
                            Filter
                        </button>
                        @if($search)
                            <a href="{{ route('admin.trips.index', ['status' => $status]) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-650 text-[10px] font-black uppercase tracking-widest px-6 py-3.5 rounded-xl transition-all border border-slate-200/40 text-center">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Trip List -->
                <div class="space-y-6">
                    @forelse($trips as $trip)
                        @php
                            $totalPax = $trip->detailTrips->sum(function($dt) {
                                return $dt->booking ? $dt->booking->jumlah_penumpang : 0;
                            });
                            $capacity = $trip->armada ? $trip->armada->kapasitas : 5;
                            $isFull = $totalPax >= $capacity;
                            $percentage = min(100, ($totalPax / $capacity) * 100);
                        @endphp
                        <div class="bg-white rounded-[2rem] border border-slate-200/80 shadow-sm overflow-hidden hover:shadow-md transition-all">
                            <!-- Card Header Info -->
                            <div class="p-6 bg-slate-50/50 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-blue-600/15">
                                        <!-- Car Icon -->
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25c0-.447-.266-.852-.676-1.03l-2.222-.962V5.25a2.25 2.25 0 00-2.25-2.25h-5.25a2.25 2.25 0 00-2.25 2.25v2.607L6.216 9.19a1.125 1.125 0 00-.676 1.03v4.5c0 .621.504 1.125 1.125 1.125h1.125m9.75 0v-4.5M6.75 14.25h12m-.75-3.75h-10.5M12 3v3.75M9.75 6.75H12"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="text-lg font-black text-slate-900">TRP-{{ str_pad($trip->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase border bg-blue-50 text-blue-600 border-blue-100">
                                                {{ $trip->jadwal->tanggal_keberangkatan->format('d M Y') }}
                                            </span>
                                            <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase border {{ strtolower($trip->jadwal->shift) === 'pagi' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-indigo-50 text-indigo-600 border-indigo-100' }}">
                                                Shift {{ ucfirst($trip->jadwal->shift) }} - {{ $trip->jadwal->jam_berangkat instanceof \DateTime ? $trip->jadwal->jam_berangkat->format('H:i') : \Carbon\Carbon::parse($trip->jadwal->jam_berangkat)->format('H:i') }} WIB
                                            </span>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs font-bold text-slate-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                                                </svg>
                                                {{ $trip->driver->nama_driver ?? 'Belum Ditugaskan' }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25c0-.447-.266-.852-.676-1.03l-2.222-.962V5.25a2.25 2.25 0 00-2.25-2.25h-5.25a2.25 2.25 0 00-2.25 2.25v2.607L6.216 9.19a1.125 1.125 0 00-.676 1.03v4.5c0 .621.504 1.125 1.125 1.125h1.125m9.75 0v-4.5M6.75 14.25h12m-.75-3.75h-10.5M12 3v3.75M9.75 6.75H12"></path>
                                                </svg>
                                                {{ $trip->armada->nomor_plat ?? '-' }} ({{ $trip->armada->nama_mobil ?? '-' }})
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Okupansi Kursi</p>
                                    <div class="flex items-center gap-3">
                                        <div class="w-24 h-2 bg-slate-100 rounded-full overflow-hidden shadow-inner border border-slate-200/40">
                                            <div class="h-full {{ $isFull ? 'bg-rose-500' : 'bg-blue-600 shadow-[0_0_8px_rgba(37,99,235,0.3)]' }} transition-all"
                                                 style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-sm font-black {{ $isFull ? 'text-rose-600' : 'text-slate-800' }}">
                                            {{ $totalPax }}/{{ $capacity }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Trip Manifest Preview -->
                            <div class="p-6 bg-white space-y-4">
                                <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    <span>Manifes Penumpang</span>
                                    <span class="text-blue-600">Rute: {{ $trip->jadwal->rute->asal }} → {{ $trip->jadwal->rute->tujuan }}</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @forelse($trip->detailTrips as $detail)
                                        <div class="flex items-center gap-3 bg-slate-50/50 p-4 rounded-xl border border-slate-100/80 hover:border-blue-500/20 transition-all">
                                            <div class="w-8 h-8 bg-white border border-slate-200/60 rounded-lg flex items-center justify-center text-xs font-black text-slate-900 shrink-0 shadow-sm">
                                                {{ $detail->booking->jumlah_penumpang }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-black text-slate-900 truncate">{{ $detail->booking->pelanggan->nama }}</p>
                                                <p class="text-[9px] font-bold text-blue-600 truncate mt-0.5">Berangkat: {{ $trip->jadwal->tanggal_keberangkatan->format('d M Y') }} - {{ ucfirst($trip->jadwal->shift) }} {{ $trip->jadwal->jam_berangkat->format('H:i') }} WIB</p>
                                                <p class="text-[9px] font-bold text-slate-400 truncate mt-0.5">Jemput: {{ $detail->booking->alamat_jemput }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-full py-8 flex flex-col items-center justify-center border border-dashed border-slate-200 rounded-xl bg-slate-50/20 text-slate-400">
                                            <svg class="w-8 h-8 mb-2 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7-7m7 7V3"></path>
                                            </svg>
                                            <p class="text-[10px] font-black uppercase tracking-widest text-center">Belum ada penumpang. Alokasikan booking dari panel kiri (Sprint 2).</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Card Footer Actions -->
                            <div class="p-6 bg-slate-50/50 border-t border-slate-200/80 flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zM14.25 15h.008v.008H14.25V15zm0 2.25h.008v.008H14.25v-.008zm2.25-2.25h.008v.008H16.5V15zm0 2.25h.008v.008H16.5v-.008z"></path>
                                        </svg>
                                        {{ $trip->jadwal->tanggal_keberangkatan->format('d M Y') }}
                                    </span>
                                    <span>·</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $trip->jadwal->jam_berangkat instanceof \DateTime ? $trip->jadwal->jam_berangkat->format('H:i') : \Carbon\Carbon::parse($trip->jadwal->jam_berangkat)->format('H:i') }} WIB
                                    </span>
                                    <span>·</span>
                                    <span>{{ $trip->status_trip }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.trips.show', $trip->id) }}"
                                       class="px-5 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-650 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm active:scale-95">
                                        Detail Trip
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-24 flex flex-col items-center justify-center text-center opacity-40 bg-slate-50/50 rounded-2xl border-2 border-dashed border-slate-200 p-12">
                            <!-- Car Icon Large -->
                            <svg class="w-16 h-16 mb-4 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25c0-.447-.266-.852-.676-1.03l-2.222-.962V5.25a2.25 2.25 0 00-2.25-2.25h-5.25a2.25 2.25 0 00-2.25 2.25v2.607L6.216 9.19a1.125 1.125 0 00-.676 1.03v4.5c0 .621.504 1.125 1.125 1.125h1.125m9.75 0v-4.5M6.75 14.25h12m-.75-3.75h-10.5M12 3v3.75M9.75 6.75H12"></path>
                            </svg>
                            <p class="text-sm font-black text-slate-500 uppercase tracking-widest">Tidak ada data trip {{ $status }}</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($trips->hasPages())
                    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                        {{ $trips->links() }}
                    </div>
                @endif

            </div>

        </div>

        <!-- Modal: Assign Booking from Index -->
        <div x-show="assignBookingId !== null" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="assignBookingId = null"></div>

            <!-- Modal Content Wrapper -->
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-white rounded-[2rem] shadow-2xl p-6 border border-slate-100"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                    
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-black text-slate-950 uppercase tracking-widest">Pilih Trip</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Tugaskan <span x-text="assignBookingCode"></span> ke salah satu trip</p>
                        </div>
                        <button @click="assignBookingId = null" class="p-2 bg-slate-50 hover:bg-slate-100 text-slate-400 hover:text-slate-800 rounded-xl transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-2 max-h-[300px] overflow-y-auto">
                        <template x-for="trip in tripsForAssign" :key="trip.id">
                            <div class="p-4 rounded-2xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/10 transition-all flex items-center justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-black text-slate-900" x-text="'TRP-' + String(trip.id).padStart(3, '0')"></p>
                                    <p class="text-[10px] font-bold text-slate-400 truncate mt-0.5" x-text="trip.driver_name"></p>
                                    <p class="text-[9px] font-bold text-slate-500 truncate" x-text="trip.plate"></p>
                                    <p class="text-[9px] font-black text-blue-700 truncate mt-1" x-text="trip.departure_date + ' - Shift ' + trip.shift + ' - ' + trip.time + ' WIB'"></p>
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[8px] font-black uppercase bg-blue-50 text-blue-600 border border-blue-100 mt-1.5" x-text="trip.pax + ' / ' + trip.capacity + ' PAX'"></span>
                                </div>
                                <form :action="'{{ url('admin/trips') }}/' + trip.id + '/assign'" method="POST" class="shrink-0">
                                    @csrf
                                    <input type="hidden" name="booking_id" :value="assignBookingId">
                                    <button type="submit" 
                                            class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all active:scale-95 shadow-md shadow-blue-600/10">
                                        Pilih
                                    </button>
                                </form>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
