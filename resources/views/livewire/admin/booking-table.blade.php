<div class="space-y-6">
    <!-- Flash Alerts -->
    <x-alert />

    <!-- Toolbar -->
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between bg-white p-4 rounded-[2rem] border border-slate-200/60 shadow-sm">
        <div class="relative w-full md:w-80">
            <!-- Search Icon -->
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search"
                placeholder="Cari Kode, Nama, atau Email..." 
                class="w-full pl-11 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-slate-900/5 outline-none transition-all placeholder:text-slate-400"
            />
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto">
            <div class="relative flex-1 md:w-56">
                <!-- Filter Icon -->
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <select 
                    wire:model.live="statusFilter"
                    class="w-full pl-11 pr-8 py-3 bg-slate-50 border-none rounded-2xl text-xs font-bold focus:ring-2 focus:ring-slate-900/5 outline-none appearance-none cursor-pointer"
                >
                    <option value="Semua Status">Semua Status</option>
                    <option value="booking_dibuat">Booking Dibuat</option>
                    <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                    <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                    <option value="dikonfirmasi">Dikonfirmasi</option>
                    <option value="assigned_to_trip">Assigned To Trip</option>
                    <option value="on_trip">On Trip</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Booking Table -->
    <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Pelanggan</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Rute & Jadwal</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Penumpang</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Pembayaran</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Booking</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/60">
                    @forelse ($bookings as $b)
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <!-- Pelanggan Info -->
                            <td class="px-8 py-6">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-xs font-black text-slate-900">{{ $b->pelanggan->nama ?? 'N/A' }}</p>
                                        <span class="text-[9px] font-black text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded-md border border-blue-100/40">{{ $b->kode_booking }}</span>
                                    </div>
                                    <div class="space-y-0.5">
                                        <p class="text-[10px] font-bold text-slate-400 flex items-center gap-1.5">
                                            <!-- Phone icon -->
                                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $b->pelanggan->no_hp ?? 'N/A' }}
                                        </p>
                                        <p class="text-[10px] font-bold text-slate-400 flex items-center gap-1.5">
                                            <!-- Mail icon -->
                                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $b->pelanggan->user->email ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <!-- Rute & Jadwal -->
                            <td class="px-8 py-6">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-900">
                                        {{ $b->jadwal->rute->asal ?? 'N/A' }} → {{ $b->jadwal->rute->tujuan ?? 'N/A' }}
                                    </p>
                                    <div class="flex items-center gap-2">
                                        <!-- Calendar icon -->
                                        <svg class="w-3 h-3 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-[10px] font-bold text-slate-500">
                                            {{ $b->jadwal ? \Carbon\Carbon::parse($b->jadwal->tanggal_keberangkatan)->translatedFormat('d M Y') : 'N/A' }} 
                                            • {{ $b->jadwal ? ucfirst($b->jadwal->shift) : 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <!-- Penumpang -->
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-black text-slate-900">{{ $b->jumlah_penumpang }} Orang</span>
                                </div>
                            </td>
                            <!-- Pembayaran -->
                            <td class="px-8 py-6">
                                @php
                                    $pembayaranTerakhir = $b->pembayaran->last();
                                @endphp
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full {{ 
                                        $pembayaranTerakhir && $pembayaranTerakhir->status_pembayaran === 'terverifikasi' ? 'bg-green-500' : 
                                        ($pembayaranTerakhir && $pembayaranTerakhir->status_pembayaran === 'menunggu' ? 'bg-yellow-500' : 
                                        ($pembayaranTerakhir && $pembayaranTerakhir->status_pembayaran === 'ditolak' ? 'bg-red-500' : 'bg-slate-300'))
                                    }}"></div>
                                    <span class="text-[10px] font-black text-slate-900 uppercase">
                                        @if ($pembayaranTerakhir)
                                            {{ $pembayaranTerakhir->status_pembayaran }} ({{ ucfirst($pembayaranTerakhir->jenis_pembayaran) }})
                                        @else
                                            Belum Bayar
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <!-- Status Booking -->
                            <td class="px-8 py-6">
                                <div class="space-y-2">
                                    <x-status-badge :status="$b->status_booking" class="block w-fit text-[9px] uppercase tracking-widest py-1.5" />
                                    
                                    @php
                                        $hasConfirmSent = $b->whatsappNotifications->isNotEmpty();
                                    @endphp
                                    @if ($hasConfirmSent)
                                        <span class="text-[9px] font-bold text-blue-600 block">WA: Confirmation Sent</span>
                                    @else
                                        <span class="text-[9px] font-bold text-slate-400 block">WA: Confirmation Not Sent</span>
                                    @endif
                                </div>
                            </td>
                            <!-- Aksi -->
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- WA Confirm button -->
                                    @if (in_array($b->status_booking, ['dikonfirmasi', 'assigned_to_trip']) && !$hasConfirmSent)
                                        <button 
                                            wire:click="sendWAConfirm({{ $b->id }})"
                                            wire:loading.attr="disabled"
                                            class="flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all shadow-lg shadow-emerald-500/20 active:scale-95 disabled:opacity-50"
                                        >
                                            <!-- WA Icon -->
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            WA Confirm
                                        </button>
                                    @endif

                                    <!-- View Detail Link -->
                                    <a 
                                        href="{{ route('admin.bookings.show', $b->id) }}"
                                        title="Lihat Detail" 
                                        class="w-9 h-9 flex items-center justify-center bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-all active:scale-95 shrink-0"
                                    >
                                        <!-- Eye Icon -->
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <!-- Verifikasi Pembayaran Link -->
                                    @if ($b->status_booking === 'menunggu_verifikasi' && $pembayaranTerakhir && $pembayaranTerakhir->status_pembayaran === 'menunggu')
                                        <a 
                                            href="{{ route('admin.pembayaran.show', $pembayaranTerakhir->id) }}"
                                            class="flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all shadow-lg shadow-amber-500/20 active:scale-95"
                                        >
                                            <!-- Card Icon -->
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Verifikasi
                                        </a>
                                    @endif

                                    <!-- Assign Trip Link -->
                                    @if ($b->status_booking === 'dikonfirmasi')
                                        <a 
                                            href="{{ route('admin.trips.show', $b->jadwal_id) }}"
                                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-600/20 active:scale-95"
                                        >
                                            <!-- Clipboard Icon -->
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                            </svg>
                                            Assign Trip
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-12 text-center">
                                <div class="max-w-xs mx-auto">
                                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-sm font-bold text-slate-400">Tidak ada booking yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
