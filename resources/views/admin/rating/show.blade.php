@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 font-poppins">

    <!-- Header Section -->
    <div class="flex items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.rating.index') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-slate-600 font-bold text-xs uppercase tracking-wider mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"></path></svg>
                Kembali ke Daftar
            </a>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Detail Ulasan</h1>
        </div>
        <form action="{{ route('admin.rating.destroy', $rating->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini secara permanen?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 font-bold px-5 py-3 rounded-xl text-xs uppercase tracking-wider transition-all active:scale-[0.98]">
                Hapus Ulasan
            </button>
        </form>
    </div>

    <!-- Rating Information Card -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 space-y-6">
            
            <!-- Customer Profile Summary -->
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-700 font-bold text-lg uppercase shrink-0">
                    {{ substr($rating->pelanggan->nama, 0, 2) }}
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-slate-800">{{ $rating->pelanggan->nama }}</h2>
                    <p class="text-sm font-semibold text-slate-400 mt-0.5">{{ $rating->pelanggan->no_hp }}</p>
                </div>
            </div>

            <!-- Stars and Status Badge -->
            <div class="flex flex-wrap justify-between items-center gap-4 pt-6 border-t border-slate-100">
                <div>
                    <p class="text-[10px] font-black text-slate-450 uppercase tracking-widest mb-1.5">Rating Bintang</p>
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-6 h-6 {{ $i <= $rating->rating ? 'text-amber-400 fill-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @endfor
                    </div>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-455 uppercase tracking-widest mb-1.5">Status Publikasi</p>
                    @php
                        $badgeClasses = match($rating->status) {
                            'published' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                            'hidden' => 'bg-rose-50 text-rose-700 border-rose-100',
                            default => 'bg-amber-50 text-amber-700 border-amber-100',
                        };
                        $statusLabel = match($rating->status) {
                            'published' => 'Published',
                            'hidden' => 'Hidden',
                            default => 'Waiting',
                        };
                    @endphp
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider border {{ $badgeClasses }}">
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>

            <!-- Review Text -->
            <div class="pt-6 border-t border-slate-100">
                <p class="text-[10px] font-black text-slate-460 uppercase tracking-widest mb-2">Isi Ulasan</p>
                <div class="p-5 bg-slate-50 border border-slate-200/80 rounded-2xl">
                    <p class="text-slate-700 text-base leading-relaxed italic">"{{ $rating->ulasan }}"</p>
                </div>
            </div>

            <!-- Booking & Trip Information -->
            <div class="pt-6 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-[10px] font-black text-slate-465 uppercase tracking-widest mb-2">Kode Booking</p>
                    <a href="{{ route('admin.bookings.show', $rating->booking->id) }}" class="font-mono text-sm font-extrabold text-blue-600 hover:underline">
                        {{ $rating->booking->kode_booking }}
                    </a>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-470 uppercase tracking-widest mb-2">Rute Perjalanan</p>
                    <p class="text-sm font-bold text-slate-800">
                        {{ $rating->booking->jadwal->rute->asal }} &rarr; {{ $rating->booking->jadwal->rute->tujuan }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-475 uppercase tracking-widest mb-2">Tanggal Perjalanan</p>
                    <p class="text-sm font-bold text-slate-800">
                        {{ $rating->booking->jadwal->tanggal_keberangkatan->format('d M Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-480 uppercase tracking-widest mb-2">Ulasan Dibuat</p>
                    <p class="text-sm font-bold text-slate-800">
                        {{ $rating->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
            </div>

            <!-- Action Controls -->
            <div class="pt-8 border-t border-slate-100 flex flex-wrap gap-3">
                @if($rating->status !== 'published')
                    <form action="{{ route('admin.rating.publish', $rating->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3.5 rounded-xl text-xs uppercase tracking-wider transition-all active:scale-[0.98] shadow-lg shadow-emerald-600/10">
                            Publikasikan Ulasan
                        </button>
                    </form>
                @endif
                
                @if($rating->status !== 'hidden')
                    <form action="{{ route('admin.rating.hide', $rating->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="bg-white hover:bg-slate-50 border border-slate-350 text-slate-700 font-bold px-6 py-3.5 rounded-xl text-xs uppercase tracking-wider transition-all active:scale-[0.98]">
                            Sembunyikan Ulasan
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection
