@extends('layouts.admin')

@section('content')
<div class="space-y-8 font-poppins">

    <!-- Header Section -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-2">Manajemen Konten</p>
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Manajemen Ulasan & Rating</h1>
            <p class="text-sm font-bold text-slate-400 mt-1">Kelola dan moderasi ulasan pelanggan sebelum dipublikasikan ke Landing Page.</p>
        </div>
    </div>

    <!-- Session Flash Notification -->
    <x-alert />

    <!-- Statistics Overview -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Stat 1: Total -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Rating</p>
                <p class="text-2xl font-black text-slate-800 mt-1">{{ $totalCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>

        <!-- Stat 2: Pending -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menunggu</p>
                <p class="text-2xl font-black text-amber-650 mt-1">{{ $pendingCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Stat 3: Published -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dipublikasikan</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ $publishedCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                <svg class="w-5 h-5 fill-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            </div>
        </div>

        <!-- Stat 4: Hidden -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Disembunyikan</p>
                <p class="text-2xl font-black text-rose-650 mt-1">{{ $hiddenCount }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
            </div>
        </div>

        <!-- Stat 5: Average -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rata-rata</p>
                <p class="text-2xl font-black text-slate-800 mt-1">{{ number_format($averageRating, 1) }} / 5</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 shrink-0">
                <svg class="w-5 h-5 fill-amber-400 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6">
        <form action="{{ route('admin.rating.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <!-- Filter Tabs -->
            <div class="flex items-center gap-2 bg-slate-100/60 p-1.5 rounded-xl border border-slate-200/60 w-full md:w-auto overflow-x-auto no-scrollbar">
                <a href="{{ route('admin.rating.index', ['status' => '', 'search' => $search]) }}"
                   class="px-5 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all whitespace-nowrap {{ !$status ? 'bg-white text-slate-800 shadow-sm border border-slate-200/50' : 'text-slate-400 hover:text-slate-600' }}">
                    Semua
                </a>
                <a href="{{ route('admin.rating.index', ['status' => 'menunggu', 'search' => $search]) }}"
                   class="px-5 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $status === 'menunggu' ? 'bg-white text-slate-800 shadow-sm border border-slate-200/50' : 'text-slate-400 hover:text-slate-600' }}">
                    Menunggu Persetujuan
                </a>
                <a href="{{ route('admin.rating.index', ['status' => 'published', 'search' => $search]) }}"
                   class="px-5 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $status === 'published' ? 'bg-white text-slate-800 shadow-sm border border-slate-200/50' : 'text-slate-400 hover:text-slate-600' }}">
                    Dipublikasikan
                </a>
                <a href="{{ route('admin.rating.index', ['status' => 'hidden', 'search' => $search]) }}"
                   class="px-5 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all whitespace-nowrap {{ $status === 'hidden' ? 'bg-white text-slate-800 shadow-sm border border-slate-200/50' : 'text-slate-400 hover:text-slate-600' }}">
                    Disembunyikan
                </a>
            </div>

            <!-- Search Field -->
            <div class="flex items-center gap-2.5 px-4 py-2.5 bg-slate-50 border border-slate-200/60 rounded-xl w-full md:w-80 focus-within:ring-4 focus-within:ring-blue-600/5 focus-within:border-blue-500/30 transition-all">
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama, booking, ulasan..."
                       class="bg-transparent border-none outline-none text-xs font-semibold w-full placeholder:text-slate-400 focus:ring-0 focus:border-transparent p-0" />
            </div>

            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
        </form>
    </div>

    <!-- Rating Table -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-black uppercase tracking-wider text-slate-400">
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Booking / Rute</th>
                        <th class="px-6 py-4">Rating</th>
                        <th class="px-6 py-4">Ulasan</th>
                        <th class="px-6 py-4">Tanggal Perjalanan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-600">
                    @forelse($ratings as $ratingItem)
                        <tr class="hover:bg-slate-50/55 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-extrabold text-slate-800 block">{{ $ratingItem->pelanggan->nama }}</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">{{ $ratingItem->pelanggan->no_hp }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono font-bold text-blue-600 block">{{ $ratingItem->booking->kode_booking }}</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">
                                    {{ $ratingItem->booking->jadwal->rute->asal }} &rarr; {{ $ratingItem->booking->jadwal->rute->tujuan }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $ratingItem->rating ? 'text-amber-400 fill-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4 max-w-xs truncate">
                                <span class="text-slate-700 italic">"{{ $ratingItem->ulasan }}"</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-semibold text-slate-700">{{ $ratingItem->booking->jadwal->tanggal_keberangkatan->format('d M Y') }}</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Shift {{ ucfirst($ratingItem->booking->jadwal->shift) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $badgeClasses = match($ratingItem->status) {
                                        'published' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'hidden' => 'bg-rose-50 text-rose-700 border-rose-100',
                                        default => 'bg-amber-50 text-amber-700 border-amber-100',
                                    };
                                    $statusLabel = match($ratingItem->status) {
                                        'published' => 'Published',
                                        'hidden' => 'Hidden',
                                        default => 'Waiting',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border {{ $badgeClasses }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($ratingItem->status !== 'published')
                                        <form action="{{ route('admin.rating.publish', $ratingItem->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 border border-emerald-250 text-emerald-700 font-bold px-2.5 py-1.5 rounded-lg text-[9px] uppercase tracking-wider transition-colors">Publish</button>
                                        </form>
                                    @endif
                                    @if($ratingItem->status !== 'hidden')
                                        <form action="{{ route('admin.rating.hide', $ratingItem->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 border border-rose-250 text-rose-700 font-bold px-2.5 py-1.5 rounded-lg text-[9px] uppercase tracking-wider transition-colors">Hide</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.rating.show', $ratingItem->id) }}" class="bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-bold px-2.5 py-1.5 rounded-lg text-[9px] uppercase tracking-wider transition-colors">Detail</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data ulasan rating ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ratings->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $ratings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
