@extends('layouts.driver')

@section('content')
    <div class="max-w-3xl mx-auto space-y-8 font-poppins pb-24">
        
        <!-- Sticky Sub-Header Nav -->
        <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('driver.trips.index') }}" class="p-2 hover:bg-slate-100 rounded-xl transition-all">
                    <!-- Left Arrow SVG -->
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-xs font-black text-slate-900 uppercase tracking-widest">Detail Manifest Perjalanan</h1>
            </div>
            <div class="bg-blue-50 px-3.5 py-1 rounded-full border border-blue-100">
                <p class="text-[10px] font-black text-blue-700 uppercase">TRP-{{ str_pad($trip->id, 3, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <!-- Trip Summary Card -->
        <div class="bg-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-2xl shadow-slate-900/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-10 -mt-10 blur-2xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-4">
                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-tight bg-orange-500/20 text-orange-400 border border-orange-500/20">
                        Shift {{ ucfirst($trip->jadwal->shift) }}
                    </span>
                    <span class="px-2.5 py-1 bg-white/10 text-white/60 rounded-lg text-[9px] font-black uppercase tracking-tight border border-white/5">
                        {{ $trip->jadwal->tanggal_keberangkatan->format('d M Y') }}
                    </span>
                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-tight bg-blue-500/20 text-blue-400 border border-blue-500/20">
                        {{ $trip->status_trip }}
                    </span>
                </div>

                <h2 class="text-lg sm:text-xl font-black mb-6 leading-tight flex items-center gap-3 uppercase">
                    {{ $trip->jadwal->rute->asal }} &rarr; {{ $trip->jadwal->rute->tujuan }}
                </h2>

                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 bg-white/5 p-3 rounded-2xl border border-white/5">
                        <div class="w-8 h-8 rounded-xl bg-blue-500/20 flex items-center justify-center shrink-0">
                            <!-- Car Icon -->
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-white/40 uppercase tracking-tighter">Armada</p>
                            <p class="text-[10px] font-black text-white uppercase">{{ $trip->armada->nomor_plat ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-white/5 p-3 rounded-2xl border border-white/5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 flex items-center justify-center shrink-0">
                            <!-- Users Icon -->
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-white/40 uppercase tracking-tighter">Okupansi</p>
                            <p class="text-[10px] font-black text-white uppercase">{{ $trip->detailTrips->count() }} Penumpang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Label -->
        <div class="flex items-center justify-between px-2">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center gap-2">
                <!-- Users SVG -->
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Daftar Penumpang
            </h3>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                {{ $trip->detailTrips->count() }} Total
            </span>
        </div>

        <!-- Passenger List Cards -->
        <div class="space-y-6">
            @forelse($trip->detailTrips as $index => $dt)
                @php
                    $b = $dt->booking;
                    $p = $b->pelanggan;
                    $isPickedUp = $dt->status_jemput === 'sudah_dijemput';
                    $isDroppedOff = $dt->status_antar === 'sudah_diantar';
                    $hasPelunasan = $b->pembayaran()->where('jenis_pembayaran', 'pelunasan')->where('status_pembayaran', 'terverifikasi')->exists();
                    $remainingFare = max(0, $b->total_harga - 50000);
                @endphp
                <div class="bg-white rounded-3xl border transition-all duration-300 relative {{ $isDroppedOff ? 'border-emerald-100 bg-slate-50/50' : 'border-slate-200 shadow-sm' }}">
                    
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xs font-black transition-all {{ $isPickedUp ? 'bg-emerald-100 text-emerald-700 shadow-inner' : 'bg-blue-900 text-white shadow-md' }}">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-slate-900 leading-none mb-1.5">{{ $p->nama ?? 'No Name' }}</h4>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[9px] font-black text-blue-600 uppercase bg-blue-50 px-2 py-0.5 rounded-md">{{ $b->jumlah_penumpang }} PAX</span>
                                        <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-tight border {{ ($isDroppedOff || $hasPelunasan) ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                            {{ ($isDroppedOff || $hasPelunasan) ? 'Lunas' : 'DP Lunas' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vertical timeline locations -->
                        <div class="space-y-6 relative mb-6 pl-4 border-l border-slate-100 ml-5">
                            
                            <!-- Pickup -->
                            <div class="relative">
                                <div class="absolute -left-[22px] top-0.5 w-2.5 h-2.5 rounded-full border-2 border-blue-500 bg-white"></div>
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider">Titik Penjemputan</p>
                                    <button type="button"
                                            @click="window.open('https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent('{{ $b->alamat_jemput }}'), '_blank')"
                                            class="text-[9px] font-black text-blue-600 uppercase flex items-center gap-1 hover:underline">
                                        Peta
                                    </button>
                                </div>
                                <p class="text-xs font-bold text-slate-600 leading-normal italic">
                                    "{{ $b->alamat_jemput }}"
                                </p>
                            </div>

                            <!-- Destination -->
                            <div class="relative">
                                <div class="absolute -left-[22px] top-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider">Titik Tujuan</p>
                                    <button type="button"
                                            @click="window.open('https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent('{{ $b->alamat_tujuan }}'), '_blank')"
                                            class="text-[9px] font-black text-blue-600 uppercase flex items-center gap-1 hover:underline">
                                        Peta
                                    </button>
                                </div>
                                <p class="text-xs font-bold text-slate-600 leading-normal italic">
                                    "{{ $b->alamat_tujuan }}"
                                </p>
                            </div>
                        </div>

                        <!-- Price and payment summaries -->
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 mb-6 space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-400 font-bold uppercase text-[9px]">Sisa Tagihan Cash</span>
                                <span class="{{ ($isDroppedOff || $hasPelunasan) ? 'text-slate-400 line-through font-semibold' : 'text-rose-600 font-black' }}">
                                    Rp {{ number_format($remainingFare, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions block -->
                        <div class="grid grid-cols-2 gap-4">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->no_hp) }}"
                               target="_blank"
                               class="flex items-center justify-center gap-2 py-3 bg-slate-50 hover:bg-slate-100 text-slate-900 rounded-xl transition-all text-[10px] font-black border border-slate-200">
                                <!-- Phone/Chat SVG -->
                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.665.989 3.3 1.503 4.94 1.505 5.548 0 10.064-4.512 10.068-10.066.002-2.69-1.047-5.216-2.951-7.121-1.905-1.904-4.43-2.951-7.125-2.952-5.55 0-10.066 4.512-10.07 10.068-.001 1.884.5 3.73 1.453 5.392L1.085 21.03l6.562-1.876zm7.915-12.28c-.19-.424-.393-.43-.574-.438-.149-.007-.32-.007-.492-.007-.172 0-.453.064-.69.322-.237.258-.905.884-.905 2.152 0 1.268.922 2.497 1.05 2.667.129.17 1.814 2.769 4.394 3.882.613.265 1.092.423 1.465.54.618.196 1.18.168 1.625.102.496-.074 1.52-.62 1.734-1.22.215-.6.215-1.115.15-1.22-.064-.105-.237-.17-.502-.303-.264-.132-1.562-.771-1.802-.857-.24-.086-.414-.13-.59.13-.176.258-.68.857-.834 1.032-.154.172-.308.194-.573.062-.265-.13-1.118-.412-2.13-1.31-.786-.701-1.317-1.567-1.472-1.832-.154-.264-.016-.407.117-.539.12-.118.264-.308.396-.462.132-.155.176-.264.264-.44.088-.177.044-.33-.022-.462-.066-.132-.574-1.385-.788-1.898z"/>
                                </svg>
                                HUBUNGI WA
                           </a>

                            <div class="flex items-center justify-center py-3 rounded-xl border font-black text-[10px] text-center uppercase">
                                @if($isDroppedOff)
                                    <span class="text-emerald-600">Sudah Diantar</span>
                                @elseif($isPickedUp)
                                    <span class="text-blue-600">Dalam Armada</span>
                                @else
                                    <span class="text-amber-600">Menunggu Naik</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Bottom strip bar -->
                    @if($isDroppedOff)
                        <div class="bg-emerald-50 py-2.5 px-6 flex items-center justify-center gap-1.5 border-t border-emerald-100 rounded-b-3xl">
                            <!-- Shield Check SVG -->
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <p class="text-[9px] font-black text-emerald-700 uppercase tracking-widest italic">Penumpang telah tiba di lokasi tujuan</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-3xl border border-slate-200 p-8 text-center text-slate-400 font-bold italic">
                    Belum ada manifest penumpang pada trip ini.
                </div>
            @endforelse
        </div>
    </div>
@endsection
