@extends('layouts.admin')

@section('content')
<div class="space-y-8 max-w-5xl">
    <!-- Header / Navigation -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.bookings.index') }}" class="w-9 h-9 flex items-center justify-center bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors">
                    <!-- Arrow Left Icon -->
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Detail Booking</h1>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-0.5">Kode: {{ $booking->kode_booking }}</p>
                </div>
            </div>
        </div>
        <div>
            <x-status-badge :status="$booking->status_booking" class="text-xs uppercase tracking-widest py-1.5 px-4 rounded-xl" />
        </div>
    </div>

    <!-- Alert Messages -->
    <x-alert />

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Left 2 Columns: Details & Log -->
        <div class="md:col-span-2 space-y-6">
            <!-- Pelanggan & Perjalanan -->
            <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-sm p-6 space-y-6">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-3">Informasi Perjalanan</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Rute</p>
                        <p class="text-sm font-bold text-slate-900">{{ $booking->jadwal->rute->asal ?? 'N/A' }} → {{ $booking->jadwal->rute->tujuan ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Jadwal & Shift</p>
                        <p class="text-sm font-bold text-slate-900">
                            {{ $booking->jadwal ? \Carbon\Carbon::parse($booking->jadwal->tanggal_keberangkatan)->translatedFormat('d F Y') : 'N/A' }}
                            <span class="text-xs font-normal text-slate-500">({{ $booking->jadwal ? ucfirst($booking->jadwal->shift) : 'N/A' }})</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Jam Keberangkatan</p>
                        <p class="text-sm font-bold text-slate-900">{{ $booking->jadwal->jam_berangkat ?? 'N/A' }} WIB</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Jumlah Penumpang</p>
                        <p class="text-sm font-bold text-slate-900">{{ $booking->jumlah_penumpang }} Orang</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 space-y-4">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Alamat Penjemputan</p>
                        <p class="text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200/50 rounded-xl p-3.5">{{ $booking->alamat_jemput }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Alamat Tujuan</p>
                        <p class="text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200/50 rounded-xl p-3.5">{{ $booking->alamat_tujuan }}</p>
                    </div>
                </div>
            </div>

            <!-- WhatsApp Notification Logs -->
            <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-3">Log Notifikasi WhatsApp</h3>
                
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse ($booking->whatsappNotifications as $notification)
                            <li>
                                <div class="relative pb-8">
                                    @if (!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-xl flex items-center justify-center ring-8 ring-white {{
                                                $notification->status === 'sent' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100'
                                            }}">
                                                @if ($notification->status === 'sent')
                                                    <!-- Check icon -->
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @else
                                                    <!-- X icon -->
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-xs font-bold text-slate-800">
                                                    Dikirim ke <span class="font-black">{{ $notification->target }}</span> 
                                                    <span class="text-[9px] font-black uppercase tracking-wider text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100/30">
                                                        {{ str_replace('_', ' ', $notification->type) }}
                                                    </span>
                                                </p>
                                                <p class="text-[10px] font-medium text-slate-500 mt-1 italic">"{{ $notification->message }}"</p>
                                            </div>
                                            <div class="text-right text-[10px] font-bold text-slate-400 whitespace-nowrap">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <div class="text-center py-6 text-slate-400 text-xs font-bold">
                                Belum ada notifikasi WhatsApp yang dikirimkan untuk booking ini.
                            </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right 1 Column: Customer & Payment Summary -->
        <div class="space-y-6">
            <!-- Data Pelanggan -->
            <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-3">Pelanggan</h3>
                
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-slate-600 border border-slate-200/40">
                        <!-- User Circle -->
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-900">{{ $booking->pelanggan->nama ?? 'N/A' }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Akun Pelanggan</p>
                    </div>
                </div>

                <div class="space-y-2 pt-3 border-t border-slate-100">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Nomor HP</p>
                        <p class="text-xs font-bold text-slate-700">{{ $booking->pelanggan->no_hp ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">Email</p>
                        <p class="text-xs font-bold text-slate-700">{{ $booking->pelanggan->user->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Rincian Biaya -->
            <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-sm p-6 space-y-4">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-3">Rincian Biaya</h3>
                @php
                    $latestPayment = $booking->pembayaran->first();
                @endphp
                
                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-medium text-slate-500">
                        <span>Tarif per Orang:</span>
                        <span>Rp {{ number_format($booking->jadwal->rute->tarif ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-medium text-slate-500">
                        <span>Jumlah Penumpang:</span>
                        <span>x{{ $booking->jumlah_penumpang }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-black text-slate-800 pt-2 border-t border-slate-100">
                        <span>Total Biaya:</span>
                        <span class="text-blue-900">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                    @if ($latestPayment)
                        <div class="flex justify-between text-xs font-medium text-slate-500 pt-2 border-t border-slate-100">
                            <span>Pembayaran Terakhir:</span>
                            <span class="font-bold {{ $latestPayment->isPelunasan() ? 'text-emerald-700' : 'text-blue-700' }}">
                                {{ $latestPayment->isPelunasan() ? 'Pelunasan' : 'DP' }} - Rp {{ number_format($latestPayment->jumlah_bayar, 0, ',', '.') }}
                            </span>
                        </div>
                        @if ($latestPayment->nominal_diskon > 0)
                            <div class="flex justify-between text-xs font-medium text-emerald-700">
                                <span>Voucher {{ $latestPayment->voucher_kode }}:</span>
                                <span class="font-bold">- Rp {{ number_format($latestPayment->nominal_diskon, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Batalkan Booking Form -->
            @if (!in_array($booking->status_booking, ['completed', 'cancelled', 'expired']))
                <div x-data="{ open: false }" class="bg-white rounded-[2rem] border border-red-100 shadow-sm p-6 space-y-4">
                    <h3 class="text-sm font-black text-red-900 uppercase tracking-widest border-b border-red-50 pb-3">Batalkan Booking</h3>
                    
                    <p class="text-[10px] font-medium text-slate-500 leading-relaxed">
                        Anda dapat membatalkan pesanan ini jika terjadi kendala pada ketersediaan rute/jadwal atau atas permintaan pelanggan.
                    </p>

                    <button 
                        @click="open = !open" 
                        class="w-full py-3 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm active:scale-95"
                    >
                        Batalkan Pesanan
                    </button>

                    <form 
                        x-show="open" 
                        x-cloak 
                        action="{{ route('admin.bookings.cancel', $booking->id) }}" 
                        method="POST" 
                        class="space-y-3 pt-3 border-t border-red-50 animate-in slide-in-from-top-4 duration-200"
                    >
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase mb-1">Alasan Pembatalan</label>
                            <textarea 
                                name="alasan_pembatalan" 
                                rows="3" 
                                required 
                                placeholder="Misal: Penumpang meminta batal / Jadwal diundur."
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all resize-none"
                            ></textarea>
                            @error('alasan_pembatalan')
                                <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                        <button 
                            type="submit" 
                            class="w-full py-3 bg-red-600 hover:bg-red-700 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-red-600/20"
                        >
                            Konfirmasi Batal
                        </button>
                    </form>
                </div>
            @endif

            @if ($booking->status_booking === 'cancelled' && $booking->alasan_pembatalan)
                <div class="bg-red-50 rounded-[2rem] border border-red-100 p-6 space-y-2">
                    <h3 class="text-xs font-black text-red-900 uppercase tracking-widest">Detail Pembatalan</h3>
                    <p class="text-[10px] font-bold text-red-600 uppercase">Alasan:</p>
                    <p class="text-xs font-bold text-red-800 leading-relaxed italic bg-white/60 border border-red-100 rounded-xl p-3">
                        "{{ $booking->alasan_pembatalan }}"
                    </p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
