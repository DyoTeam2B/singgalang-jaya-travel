@extends('layouts.admin')

@section('content')
    <div class="space-y-8 font-poppins">
        <!-- Header Section -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mb-2">Manajemen Operasional</p>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Jadwal Keberangkatan</h1>
                <p class="text-sm font-semibold text-slate-500 mt-1">Atur rute, shift, dan kapasitas keberangkatan harian.</p>
            </div>
            <a href="{{ route('admin.jadwal.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-lg shadow-blue-600/10 flex items-center gap-2 transition-all active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Jadwal Baru
            </a>
        </div>

        <!-- Session Flash Notification -->
        <x-alert />

        <!-- Tabs & Search Grid -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Tabs -->
            <div class="bg-white p-1.5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-1 shrink-0">
                <a href="{{ route('admin.jadwal.index', ['tab' => 'active', 'search' => $search]) }}"
                   class="flex items-center gap-2 px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $tab === 'active' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-400 hover:text-slate-650 hover:bg-slate-50' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zM14.25 15h.008v.008H14.25V15zm0 2.25h.008v.008H14.25v-.008zm2.25-2.25h.008v.008H16.5V15zm0 2.25h.008v.008H16.5v-.008z"></path>
                    </svg>
                    Jadwal Aktif
                </a>
                <a href="{{ route('admin.jadwal.index', ['tab' => 'history', 'search' => $search]) }}"
                   class="flex items-center gap-2 px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ $tab === 'history' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-400 hover:text-slate-650 hover:bg-slate-50' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Riwayat
                </a>
            </div>

            <!-- Search -->
            <div class="flex-1 bg-white rounded-2xl border border-slate-200/80 p-2 shadow-sm flex items-center gap-3">
                <form method="GET" action="{{ route('admin.jadwal.index') }}" class="w-full flex gap-3 items-center">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <div class="relative flex-1">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari rute, tanggal (YYYY-MM-DD), atau shift..."
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200/60 rounded-xl text-xs font-bold text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 focus:ring-blue-600/10 focus:border-blue-500/30 transition-all">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black uppercase tracking-widest px-6 py-3.5 rounded-xl transition-all shadow-md active:scale-95">
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route('admin.jadwal.index', ['tab' => $tab]) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-650 text-[10px] font-black uppercase tracking-widest px-6 py-3.5 rounded-xl transition-all border border-slate-200/40 text-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Schedule Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($jadwal as $item)
                @php
                    $bookedSum = $item->bookings_sum_jumlah_penumpang ?? 0;
                    $displayStatus = $item->status_jadwal;
                    
                    // Check if there is a running or completed trip
                    $activeTrip = $item->trips()->whereIn('status_trip', ['on_trip', 'completed'])->first();
                    
                    if ($activeTrip) {
                        if ($activeTrip->status_trip === 'on_trip') {
                            $displayStatus = 'on_trip';
                        } elseif ($activeTrip->status_trip === 'completed') {
                            $displayStatus = 'completed';
                        }
                    } elseif ($item->is_expired) {
                        $displayStatus = 'nonaktif';
                    } elseif ($item->status_jadwal === 'aktif' && $bookedSum >= $item->kuota) {
                        $displayStatus = 'penuh';
                    }
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-all group">
                    <!-- Card Header -->
                    <div class="p-6 border-b border-slate-100 flex items-start justify-between gap-3">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">JDW-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</p>
                            <h3 class="text-base font-bold text-slate-900 tracking-tight leading-tight">
                                {{ $item->rute->asal }} → {{ $item->rute->tujuan }}
                            </h3>
                        </div>
                        <x-status-badge status="{{ $displayStatus }}" />
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 space-y-4 flex-1 bg-slate-50/20">
                        <!-- Tanggal -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-slate-400">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zM14.25 15h.008v.008H14.25V15zm0 2.25h.008v.008H14.25v-.008zm2.25-2.25h.008v.008H16.5V15zm0 2.25h.008v.008H16.5v-.008z"></path>
                                </svg>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Tanggal</span>
                            </div>
                            <span class="text-sm font-bold text-slate-800">{{ $item->tanggal_keberangkatan->format('d M Y') }}</span>
                        </div>

                        <!-- Shift -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-slate-400">
                                @if(strtolower($item->shift) === 'pagi')
                                    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21M5.136 5.136l1.591 1.591m10.546 10.546l1.591 1.591M3 12h2.25m13.5 0H21M5.136 18.864l1.591-1.591m10.546-10.546l1.591-1.591M12 9a3 3 0 100 6 3 3 0 000-6z"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-slate-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"></path>
                                    </svg>
                                @endif
                                <span class="text-[10px] font-bold uppercase tracking-widest">Shift</span>
                            </div>
                            <span class="text-sm font-bold text-slate-800 capitalize">{{ $item->shift }}</span>
                        </div>

                        <!-- Jam Berangkat -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-slate-400">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Berangkat</span>
                            </div>
                            <span class="text-sm font-bold text-slate-800">{{ $item->jam_berangkat instanceof \DateTime ? $item->jam_berangkat->format('H:i') : \Carbon\Carbon::parse($item->jam_berangkat)->format('H:i') }} WIB</span>
                        </div>

                        <!-- Kapasitas -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-slate-400">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.97 5.97 0 00-.75-2.906m-11.25 0a9.094 9.094 0 013.741-.479 3 3 0 01-4.682 2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12-1.125c0-2.485-3.358-4.5-7.5-4.5s-7.5 2.015-7.5 4.5m15 0c0-1.61-1.425-2.986-3.75-3.604M5.25 17.625C2.925 17.007 1.5 15.63 1.5 14.021m15-9.458a3 3 0 11-6 0 3 3 0 016 0zM8.25 4.5a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Kapasitas</span>
                            </div>
                            <span class="text-sm font-bold {{ $bookedSum >= $item->kuota ? 'text-amber-600' : 'text-slate-800' }}">
                                {{ $bookedSum }}/{{ $item->kuota }} kursi
                            </span>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="p-4 bg-white border-t border-slate-100 flex items-center gap-2">
                        @if($tab === 'active')
                            <a href="{{ route('admin.jadwal.edit', $item->id) }}"
                               class="flex-1 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-1.5 transition-colors shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path>
                                </svg>
                                Edit
                            </a>

                            <form action="{{ route('admin.jadwal.toggle', $item->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PUT')
                                @if($item->status_jadwal === 'nonaktif')
                                    <button type="submit"
                                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-1.5 transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.07 0a5 5 0 010 7.07M12 9v6m3-3H9"></path>
                                        </svg>
                                        Aktifkan
                                    </button>
                                @else
                                    <button type="submit"
                                            class="w-full bg-rose-50 hover:bg-rose-100 text-rose-600 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-1.5 transition-all border border-rose-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"></path>
                                        </svg>
                                        Nonaktifkan
                                    </button>
                                @endif
                            </form>

                            <form action="{{ route('admin.jadwal.destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');"
                                  class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-2.5 bg-white border border-slate-200 hover:bg-rose-50 hover:text-rose-600 rounded-xl transition-colors shadow-sm"
                                        title="Hapus Jadwal">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                    </svg>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.jadwal.destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat jadwal ini?');"
                                  class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full bg-slate-50 hover:bg-rose-50 text-slate-400 hover:text-rose-600 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 transition-all border border-slate-100"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                    </svg>
                                    Hapus dari Riwayat
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-dashed border-slate-200 py-24 text-center">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                    </svg>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Tidak ada jadwal {{ $tab === 'active' ? 'aktif' : 'di riwayat' }}.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($jadwal->hasPages())
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                {{ $jadwal->links() }}
            </div>
        @endif
    </div>
@endsection
