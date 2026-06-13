@extends('layouts.admin')

@section('content')
    <div class="space-y-8 font-poppins">

        <!-- Header Section -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-2">Operasional Trip</p>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Manajemen Trip Aktif</h1>
                <p class="text-sm font-bold text-slate-400 mt-1">Atur alokasi armada, penugasan driver, dan manifes penumpang.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.trips.create') }}"
                   class="bg-blue-800 hover:bg-blue-900 text-white px-6 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl shadow-blue-800/20 flex items-center gap-2 transition-all active:scale-95">
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
        <div class="flex items-center gap-2 bg-slate-100/80 p-1.5 rounded-2xl w-fit border border-slate-200 overflow-x-auto no-scrollbar shadow-inner">
            <a href="{{ route('admin.trips.index', ['status' => 'ready', 'search' => $search]) }}"
               class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $status === 'ready' ? 'bg-white text-slate-900 shadow-sm border border-slate-200' : 'text-slate-400 hover:text-slate-600 hover:bg-white/50' }}">
                Siap Keberangkatan
                <span class="ml-3 px-2 py-0.5 rounded-lg text-[9px] {{ $status === 'ready' ? 'bg-slate-900 text-white' : 'bg-slate-200 text-slate-500' }}">
                    {{ $counts['ready'] }}
                </span>
            </a>
            <a href="{{ route('admin.trips.index', ['status' => 'on_trip', 'search' => $search]) }}"
               class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $status === 'on_trip' ? 'bg-white text-slate-900 shadow-sm border border-slate-200' : 'text-slate-400 hover:text-slate-600 hover:bg-white/50' }}">
                Dalam Perjalanan
                <span class="ml-3 px-2 py-0.5 rounded-lg text-[9px] {{ $status === 'on_trip' ? 'bg-slate-900 text-white' : 'bg-slate-200 text-slate-500' }}">
                    {{ $counts['on_trip'] }}
                </span>
            </a>
            <a href="{{ route('admin.trips.index', ['status' => 'completed', 'search' => $search]) }}"
               class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $status === 'completed' ? 'bg-white text-slate-900 shadow-sm border border-slate-200' : 'text-slate-400 hover:text-slate-600 hover:bg-white/50' }}">
                Selesai
                <span class="ml-3 px-2 py-0.5 rounded-lg text-[9px] {{ $status === 'completed' ? 'bg-slate-900 text-white' : 'bg-slate-200 text-slate-500' }}">
                    {{ $counts['completed'] }}
                </span>
            </a>
        </div>

        <!-- Layout Grid: Left (Booking Queue), Right (Trip Cards) -->
        <div class="flex flex-col xl:flex-row gap-8 items-start">

            <!-- Left Panel: Verified Booking Queue -->
            <div class="w-full xl:w-[380px] shrink-0 flex flex-col">
                <div class="flex items-center justify-between mb-4 px-5 py-3 bg-white rounded-2xl border border-slate-200 shadow-sm">
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
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:border-blue-300 transition-all space-y-4 relative group">
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
                            <button disabled
                                    class="w-full py-2.5 bg-slate-100 text-slate-400 rounded-xl cursor-not-allowed text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-1.5 border border-slate-200">
                                Assign ke Trip (Sprint 2)
                            </button>
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
            <div class="flex-1 w-full flex flex-col space-y-6">

                <!-- Search/Filter Bar -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
                    <form method="GET" action="{{ route('admin.trips.index') }}" class="flex flex-wrap gap-4 items-center">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <div class="relative flex-1 min-w-[240px]">
                            <!-- Search Icon -->
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Cari driver, plat nomor, kota asal/tujuan..."
                                   class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-black uppercase tracking-widest px-6 py-3 rounded-xl transition-all">
                            Filter
                        </button>
                        @if($search)
                            <a href="{{ route('admin.trips.index', ['status' => $status]) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-black uppercase tracking-widest px-6 py-3 rounded-xl transition-all">
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
                            $capacity = $trip->driver ? $trip->driver->kapasitas_mobil : 5;
                            $isFull = $totalPax >= $capacity;
                            $percentage = min(100, ($totalPax / $capacity) * 100);
                        @endphp
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-all">
                            <!-- Card Header Info -->
                            <div class="p-6 bg-slate-50/50 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-blue-800 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-md shadow-blue-800/10">
                                        <!-- Car Icon -->
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.129-1.125V11.25c0-.447-.266-.852-.676-1.03l-2.222-.962V5.25a2.25 2.25 0 00-2.25-2.25h-5.25a2.25 2.25 0 00-2.25 2.25v2.607L6.216 9.19a1.125 1.125 0 00-.676 1.03v4.5c0 .621.504 1.125 1.125 1.125h1.125m9.75 0v-4.5M6.75 14.25h12m-.75-3.75h-10.5M12 3v3.75M9.75 6.75H12"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-lg font-black text-slate-900">TRP-{{ str_pad($trip->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase border {{ strtolower($trip->jadwal->shift) === 'pagi' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-indigo-50 text-indigo-600 border-indigo-100' }}">
                                                {{ $trip->jadwal->shift }} ({{ $trip->jadwal->jam_berangkat instanceof \DateTime ? $trip->jadwal->jam_berangkat->format('H:i') : \Carbon\Carbon::parse($trip->jadwal->jam_berangkat)->format('H:i') }})
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
                                                {{ $trip->driver->nomor_plat ?? '-' }} ({{ $trip->driver->nama_mobil ?? '-' }})
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Okupansi Kursi</p>
                                    <div class="flex items-center gap-3">
                                        <div class="w-24 h-2.5 bg-slate-200 rounded-full overflow-hidden shadow-inner">
                                            <div class="h-full {{ $isFull ? 'bg-red-600' : 'bg-blue-600 shadow-[0_0_8px_rgba(30,58,138,0.3)]' }} transition-all"
                                                 style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-sm font-black {{ $isFull ? 'text-red-600' : 'text-slate-800' }}">
                                            {{ $totalPax }}/{{ $capacity }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Trip Manifest Preview -->
                            <div class="p-6 bg-white space-y-4">
                                <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    <span>Manifes Penumpang</span>
                                    <span class="text-blue-800">Rute: {{ $trip->jadwal->rute->asal }} → {{ $trip->jadwal->rute->tujuan }}</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @forelse($trip->detailTrips as $detail)
                                        <div class="flex items-center gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100 hover:border-blue-100 transition-colors">
                                            <div class="w-8 h-8 bg-white border border-slate-200 rounded-lg flex items-center justify-center text-xs font-black text-slate-900 shrink-0 shadow-sm">
                                                {{ $detail->booking->jumlah_penumpang }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-black text-slate-900 truncate">{{ $detail->booking->pelanggan->nama }}</p>
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
                            <div class="p-6 bg-slate-50/30 border-t border-slate-200 flex flex-wrap items-center justify-between gap-4">
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
                                        {{ $trip->status_trip }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.trips.show', $trip->id) }}"
                                       class="px-5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm">
                                        Detail Trip (Sprint 2)
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

    </div>
@endsection
