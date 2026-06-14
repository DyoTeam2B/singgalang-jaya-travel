<div class="flex flex-col h-full space-y-6">
    <!-- Header with statistics -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-1">Verifikasi Pembayaran</h1>
            <p class="text-sm font-medium text-slate-500">Konfirmasi bukti transfer DP dan Pelunasan dari pelanggan.</p>
        </div>
        <div class="flex items-center gap-3 bg-blue-50 px-4 py-2.5 rounded-2xl border border-blue-100/50 shrink-0">
            <!-- Wallet Icon -->
            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            <div>
                <p class="text-[10px] font-black text-blue-500 uppercase leading-none mb-1">Menunggu Verifikasi</p>
                <p class="text-sm font-black text-blue-900 leading-none">
                    {{ $pendingCount }} Pembayaran
                </p>
            </div>
        </div>
    </div>

    <!-- Flash Alerts -->
    <x-alert />

    <!-- Toolbar -->
    <div class="flex flex-col xl:flex-row xl:items-center gap-4">
        <div class="relative w-full xl:w-96 shrink-0">
            <!-- Search Icon -->
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search"
                placeholder="Cari ID Booking atau Nama..." 
                class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-bold focus:outline-none focus:ring-4 focus:ring-slate-900/5 transition-all shadow-sm placeholder:text-slate-400 focus:border-slate-300"
            />
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full">
            <div class="relative flex-1 min-w-[150px] max-w-[240px]">
                <!-- Filter Icon -->
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <select 
                    wire:model.live="statusFilter"
                    class="w-full pl-10 pr-8 py-3 bg-white border border-slate-200 shadow-sm rounded-2xl text-xs font-bold text-slate-700 appearance-none focus:outline-none focus:ring-4 focus:ring-slate-900/5 focus:border-slate-300 cursor-pointer"
                >
                    <option value="Semua Status">Semua Status</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="terverifikasi">Terverifikasi</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Main Grid (Table + Detail Panel) -->
    <div class="flex flex-col xl:flex-row gap-6 items-start">
        
        <!-- Table Section -->
        <div class="flex-1 w-full bg-white rounded-[2rem] border border-slate-200/60 shadow-sm flex flex-col overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">ID Booking</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nominal</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Bukti</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/60">
                        @forelse ($payments as $p)
                            @php
                                $isSelected = $selectedPaymentId === $p->id;
                            @endphp
                            <tr 
                                wire:click="selectPayment({{ $p->id }})"
                                class="transition-colors cursor-pointer group {{ $isSelected ? 'bg-blue-50/20' : 'hover:bg-slate-50/40' }}"
                            >
                                <td class="px-6 py-4 text-xs font-black text-slate-900 whitespace-nowrap relative">
                                    @if ($isSelected)
                                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-600 rounded-r-full"></div>
                                    @endif
                                    {{ $p->booking->kode_booking ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-xs font-bold text-slate-800">{{ $p->booking->pelanggan->nama ?? 'N/A' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-[10px] font-black {{ $p->isDp() ? 'text-blue-600' : 'text-emerald-600' }} uppercase">
                                        @if ($p->isDp())
                                            DP (Flat Rp50.000)
                                        @elseif ($p->voucher_kode)
                                            Pelunasan ({{ $p->voucher_kode }})
                                        @else
                                            Pelunasan
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-black text-slate-900">
                                    Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($p->bukti_pembayaran)
                                        <div class="flex items-center gap-1.5 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100/50 w-fit">
                                            <!-- Image Icon -->
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Sudah Unggah
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1.5 text-[10px] font-black text-slate-400 bg-slate-50 px-2 py-1 rounded-md border border-slate-200/40 w-fit">
                                            Belum Unggah
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$p->status_pembayaran" class="text-[9px] uppercase tracking-widest py-1" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="max-w-xs mx-auto">
                                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <p class="text-sm font-bold text-slate-400">Tidak ada data pembayaran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detail Panel -->
        <div class="w-full xl:w-[420px] shrink-0">
            @if ($selectedPayment)
                @php
                    $proofUrl = filter_var($selectedPayment->bukti_pembayaran, FILTER_VALIDATE_URL) 
                        ? $selectedPayment->bukti_pembayaran 
                        : Storage::url($selectedPayment->bukti_pembayaran);
                @endphp
                <div class="bg-white rounded-[2rem] border border-slate-200/60 shadow-sm flex flex-col overflow-hidden animate-in slide-in-from-right-8 fade-in duration-300">
                    <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between shrink-0">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Rincian Pembayaran</p>
                            <h3 class="text-xs font-black text-slate-900">{{ $selectedPayment->booking->kode_booking }}</h3>
                        </div>
                        <button 
                            wire:click="$set('selectedPaymentId', null)"
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 transition-colors"
                        >
                            <!-- Close Icon -->
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-5 space-y-6 overflow-y-auto max-h-[60vh] no-scrollbar">
                        <!-- Customer Info -->
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shrink-0 border border-blue-100/50">
                                <!-- User Icon -->
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-950">{{ $selectedPayment->booking->pelanggan->nama ?? 'N/A' }}</p>
                                <p class="text-[10px] font-bold text-slate-400 flex items-center gap-1 mt-0.5">
                                    <!-- Phone Icon -->
                                    <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ $selectedPayment->booking->pelanggan->no_hp ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <!-- Transaction Details -->
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Tipe Pembayaran</p>
                                    <p class="text-xs font-bold text-slate-900 uppercase">
                                        @if ($selectedPayment->isDp())
                                            DP (Flat Rp50.000)
                                        @elseif ($selectedPayment->voucher_kode)
                                            Pelunasan ({{ $selectedPayment->voucher_kode }})
                                        @else
                                            Pelunasan
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Metode Pembayaran</p>
                                    <p class="text-xs font-bold text-slate-900">{{ $selectedPayment->metode_pembayaran ?? 'Transfer Bank' }}</p>
                                </div>
                            </div>
                            <div class="pt-3 border-t border-slate-200/50">
                                <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Nominal Transfer</p>
                                <p class="text-lg font-black text-blue-900">Rp {{ number_format($selectedPayment->jumlah_bayar, 0, ',', '.') }}</p>
                            </div>
                            @if ($selectedPayment->nominal_diskon > 0)
                                <div class="pt-3 border-t border-slate-200/50 grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Voucher</p>
                                        <p class="text-xs font-black text-emerald-700">{{ $selectedPayment->voucher_kode }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Diskon</p>
                                        <p class="text-xs font-black text-emerald-700">{{ $selectedPayment->diskon_persen }}% (-Rp {{ number_format($selectedPayment->nominal_diskon, 0, ',', '.') }})</p>
                                    </div>
                                </div>
                            @endif
                            <div class="flex items-center gap-3 pt-1">
                                <div class="flex items-center gap-1 text-[9px] font-bold text-slate-500 bg-white px-2 py-1 rounded border border-slate-200/60 shadow-sm">
                                    <!-- Calendar Icon -->
                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $selectedPayment->created_at->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-[9px] font-bold text-slate-500 bg-white px-2 py-1 rounded border border-slate-200/60 shadow-sm">
                                    {{ $selectedPayment->created_at->format('H:i') }} WIB
                                </div>
                            </div>
                        </div>

                        <!-- Proof Image Preview -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Bukti Transfer</h4>
                                <button 
                                    wire:click="$set('isPreviewOpen', true)"
                                    class="text-[10px] font-black text-blue-600 hover:underline flex items-center gap-1"
                                >
                                    Perbesar 
                                    <!-- Expand Icon -->
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </button>
                            </div>
                            @if ($selectedPayment->bukti_pembayaran)
                                <div 
                                    wire:click="$set('isPreviewOpen', true)"
                                    class="relative group cursor-pointer aspect-[4/3] rounded-2xl overflow-hidden border border-slate-200 shadow-sm"
                                >
                                    <img 
                                        src="{{ $proofUrl }}" 
                                        alt="Bukti Transfer"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    />
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                        <!-- Eye Icon -->
                                        <svg class="text-white opacity-0 group-hover:opacity-100 transition-opacity w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </div>
                                </div>
                            @else
                                <div class="aspect-[4/3] bg-slate-50 border border-slate-200 border-dashed rounded-2xl flex flex-col items-center justify-center text-center p-4">
                                    <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-[10px] font-bold text-slate-400">Bukti pembayaran belum diunggah</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Verification Actions -->
                    <div class="p-4 border-t border-slate-100 bg-slate-50 shrink-0">
                        @if ($selectedPayment->status_pembayaran === 'menunggu')
                            <div class="grid grid-cols-2 gap-3">
                                <button
                                    wire:click="$set('isRejectModalOpen', true)"
                                    class="flex items-center justify-center gap-2 py-3 bg-white border border-red-200 text-red-600 rounded-2xl text-xs font-bold hover:bg-red-50 transition-all shadow-sm active:scale-95"
                                >
                                    <!-- X Circle -->
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Tolak Bukti
                                </button>
                                <button
                                    wire:click="$set('isVerifyModalOpen', true)"
                                    class="flex items-center justify-center gap-2 py-3 bg-blue-800 hover:bg-blue-900 text-white rounded-2xl text-xs font-bold transition-all shadow-md shadow-blue-800/10 active:scale-95"
                                >
                                    <!-- Check Circle -->
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Verifikasi
                                </button>
                            </div>
                        @else
                            <div class="flex items-center justify-center gap-2 py-3.5 rounded-2xl text-xs font-black uppercase tracking-wider border {{
                                $selectedPayment->status_pembayaran === 'terverifikasi' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200'
                            }}">
                                @if ($selectedPayment->status_pembayaran === 'terverifikasi')
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Sudah Diverifikasi
                                @else
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Bukti Transfer Ditolak
                                @endif
                            </div>
                            @if ($selectedPayment->catatan)
                                <div class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-100 text-[10px] text-slate-500 font-medium">
                                    <span class="font-bold text-slate-700 block mb-0.5">Catatan Penolakan:</span>
                                    {{ $selectedPayment->catatan }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @else
                <div class="w-full xl:w-[420px] bg-slate-50/50 rounded-[2rem] border-2 border-slate-200 border-dashed flex flex-col items-center justify-center text-center p-8 min-h-[300px]">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-slate-200 mb-4 shadow-sm">
                        <!-- Card Icon -->
                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-slate-400">Pilih pembayaran untuk memproses verifikasi bukti transfer</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Lightbox Preview Modal -->
    @if ($isPreviewOpen && $selectedPayment)
        <div class="fixed inset-0 z-[100] bg-slate-900/90 backdrop-blur-sm flex items-center justify-center p-4 sm:p-8 animate-in fade-in duration-300">
            <button 
                wire:click="$set('isPreviewOpen', false)"
                class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors"
            >
                <!-- Close SVG -->
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
            <div class="max-w-4xl w-full h-full flex flex-col items-center justify-center">
                <div class="w-full bg-white rounded-t-2xl p-4 flex items-center justify-between shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                            <!-- Image Icon -->
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-500 uppercase leading-none mb-1">Bukti Transfer - {{ $selectedPayment->booking->kode_booking }}</p>
                            <p class="text-xs font-black text-slate-900 leading-none">{{ $selectedPayment->booking->pelanggan->nama ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <a 
                        href="{{ $proofUrl }}" 
                        download="bukti_transfer_{{ $selectedPayment->booking->kode_booking }}"
                        class="flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-colors"
                    >
                        <!-- Download icon -->
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Simpan Gambar
                    </a>
                </div>
                <div class="w-full h-[65vh] bg-slate-50 rounded-b-2xl overflow-hidden flex items-center justify-center p-4 border border-t-0 border-slate-100 shadow-lg">
                    <img 
                        src="{{ $proofUrl }}" 
                        alt="Full Bukti Transfer"
                        class="max-w-full max-h-full object-contain"
                    />
                </div>
                <p class="mt-4 text-white/60 text-xs font-medium">Klik tombol X untuk menutup atau di luar gambar.</p>
            </div>

            <div class="absolute inset-0 -z-10" wire:click="$set('isPreviewOpen', false)"></div>
        </div>
    @endif

    <!-- Verify Confirmation Modal -->
    @if ($isVerifyModalOpen && $selectedPayment)
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-200">
            <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 animate-in zoom-in-95 duration-200">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mx-auto mb-5">
                    <!-- Check Circle Icon -->
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 text-center mb-2">Verifikasi Pembayaran?</h3>
                <p class="text-xs font-medium text-slate-500 text-center mb-6 leading-relaxed">
                    Pembayaran untuk booking <span class="font-black text-slate-900">{{ $selectedPayment->booking->kode_booking }}</span> sebesar
                    <span class="font-black text-slate-900"> Rp {{ number_format($selectedPayment->jumlah_bayar, 0, ',', '.') }}</span> akan ditandai sebagai <span class="font-black text-emerald-600">Verified</span> dan status booking diperbarui menjadi <span class="font-black text-blue-600">Dikonfirmasi</span>.
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <button
                        wire:click="$set('isVerifyModalOpen', false)"
                        class="py-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-200 transition-all"
                    >
                        Batal
                    </button>
                    <button
                        wire:click="verifyPayment"
                        class="py-3 bg-emerald-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20"
                    >
                        Ya, Verifikasi
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Reject Reason Modal -->
    @if ($isRejectModalOpen && $selectedPayment)
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-200">
            <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 animate-in zoom-in-95 duration-200">
                <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center text-red-600 mx-auto mb-5">
                    <!-- X Circle Icon -->
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 text-center mb-2">Tolak Bukti Pembayaran</h3>
                <p class="text-xs font-medium text-slate-500 text-center mb-4">
                    Tuliskan alasan penolakan bukti transfer ini agar dapat dibaca pelanggan.
                </p>
                <div class="mb-5">
                    <textarea
                        wire:model="rejectReason"
                        placeholder="Misal: Bukti pembayaran buram / nominal tidak sesuai."
                        rows="3"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all resize-none"
                    ></textarea>
                    @error('rejectReason')
                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <button
                        wire:click="$set('isRejectModalOpen', false)"
                        class="py-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-200 transition-all"
                    >
                        Batal
                    </button>
                    <button
                        wire:click="rejectPayment"
                        class="py-3 bg-red-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-red-700 transition-all shadow-lg shadow-red-600/20"
                    >
                        Kirim Penolakan
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
