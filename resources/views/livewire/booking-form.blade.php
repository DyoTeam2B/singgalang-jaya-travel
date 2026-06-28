<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    {{-- LEFT SIDE — BOOKING FORM (8/12 on desktop) --}}
    <div class="lg:col-span-8 space-y-6">

        {{-- Section: Detail Perjalanan --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800 tracking-tight">Detail Perjalanan</h2>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mt-0.5">Lengkapi data rute & lokasi</p>
                </div>
            </div>

            <div class="p-6 space-y-6">
                {{-- Jadwal Picker — Pencarian Jadwal Terintegrasi --}}
                <div class="space-y-3">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Jadwal Perjalanan</label>
                    
                    @if($selectedJadwal)
                        <div class="p-5 rounded-2xl border border-blue-100 bg-blue-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4 transition-all">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-800 text-white">
                                        Shift {{ ucfirst($selectedJadwal->shift) }}
                                    </span>
                                    <span class="text-xs font-medium text-slate-500">
                                        {{ $selectedJadwal->tanggal_keberangkatan->format('d M Y') }}
                                    </span>
                                </div>
                                <h3 class="font-extrabold text-slate-800 text-base sm:text-lg">
                                    {{ $selectedJadwal->rute->asal }} &rarr; {{ $selectedJadwal->rute->tujuan }}
                                </h3>
                                <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs font-semibold text-slate-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $selectedJadwal->jam_berangkat->format('H:i') }} WIB
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        Sisa {{ $available_seats }} kursi
                                    </span>
                                    <span class="text-blue-800 font-bold">
                                        Rp {{ number_format($tarif_per_orang, 0, ',', '.') }} / orang
                                    </span>
                                </div>
                            </div>
                            
                            <button
                                type="button"
                                wire:click="cariJadwal"
                                class="px-4 py-2.5 rounded-xl border border-blue-300 hover:bg-blue-100/50 text-blue-800 text-xs font-bold uppercase tracking-wider transition-colors shrink-0 flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H18m0 0V5"></path></svg>
                                Ganti Jadwal
                            </button>
                        </div>
                    @else
                        <div class="p-8 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 text-center flex flex-col items-center justify-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-700 text-sm">Belum ada jadwal dipilih</h4>
                                <p class="text-xs text-slate-400 mt-1">Silakan cari dan pilih jadwal perjalanan Anda terlebih dahulu.</p>
                            </div>
                            <button
                                type="button"
                                wire:click="cariJadwal"
                                class="px-5 py-3 rounded-xl bg-blue-800 hover:bg-blue-900 text-white text-xs font-bold uppercase tracking-wider transition-colors flex items-center gap-2 shadow-sm"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Cari & Pilih Jadwal
                            </button>
                        </div>
                    @endif
                    
                    @error('selectedJadwalId')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jumlah Penumpang + Info DP --}}
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Jumlah Penumpang</label>
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <select
                                wire:model.live="jumlah_penumpang"
                                class="w-full pl-12 pr-6 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all appearance-none cursor-pointer"
                            >
                                @for($i = 1; $i <= min(10, max(1, $available_seats)); $i++)
                                    <option value="{{ $i }}">{{ $i }} Penumpang</option>
                                @endfor
                            </select>
                        </div>
                        @error('jumlah_penumpang')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($selectedJadwalId && $tarif_per_orang > 0)
                        <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 flex flex-col gap-2.5 text-blue-700">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-xs font-medium leading-relaxed">
                                    Harga tiket flat <span class="font-bold">Rp {{ number_format($tarif_per_orang, 0, ',', '.') }}</span> per orang. Down Payment (DP) hanya <span class="font-bold">Rp 50.000</span> per booking.
                                </p>
                            </div>
                            <div class="border-t border-blue-200/50 pt-2 text-xs font-semibold space-y-1.5 pl-8">
                                <p>• Sisa pembayaran akan dilunasi saat keberangkatan/ke driver.</p>
                                <p>• Booking Anda akan diproses setelah bukti pembayaran DP diverifikasi oleh admin.</p>
                                <p class="text-amber-700">• DP tidak dapat dikembalikan jika pembatalan dilakukan oleh pelanggan.</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Informasi Penumpang Utama --}}
                <div class="border-t border-slate-100 pt-6 space-y-4">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Informasi Penumpang Utama</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                            <input type="text" wire:model.defer="nama" class="w-full border border-slate-300 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Masukkan nama lengkap">
                            @error('nama')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">No. HP / WhatsApp</label>
                            <input type="text" wire:model.defer="no_hp" class="w-full border border-slate-300 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Contoh: 08123456789">
                            <p class="text-xs text-slate-500 mt-1">Digunakan untuk notifikasi WhatsApp keberangkatan</p>
                            @error('no_hp')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Alamat Jemput --}}
                <div class="border-t border-slate-100 pt-6 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Titik Jemput (Door-to-Door)</label>
                    </div>
                    <div class="relative">
                        <svg class="absolute left-4 top-4 w-5 h-5 text-blue-600 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <textarea id="alamat_jemput" wire:model.defer="alamat_jemput" rows="2" class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 resize-none" placeholder="Masukkan alamat lengkap penjemputan..."></textarea>
                    </div>
                    
                    @error('alamat_jemput')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Map Picker --}}
                <div class="border-t border-slate-100 pt-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Tentukan Titik Koordinat Peta</label>
                    <x-map-picker
                        latJemputBind="latitude_jemput"
                        lngJemputBind="longitude_jemput"
                    />
                </div>

                {{-- Alamat Tujuan --}}
                <div class="border-t border-slate-100 pt-6 space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Titik Antar (Tujuan)</label>
                    </div>
                    <div class="relative">
                        <svg class="absolute left-4 top-4 w-5 h-5 text-green-600 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <textarea id="alamat_tujuan" wire:model.defer="alamat_tujuan" rows="2" class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 resize-none" placeholder="Masukkan alamat lengkap tujuan..."></textarea>
                    </div>
                    @error('alamat_tujuan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT SIDE — SUMMARY CARD (4/12 on desktop) --}}
    <div class="lg:col-span-4 relative">
        <div class="lg:sticky lg:top-28 space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight mb-6">Ringkasan Tiket</h3>

                    <div class="space-y-5">
                        {{-- Pemesan Info --}}
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center border border-slate-200 shrink-0">
                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Pemesan</p>
                                <p class="font-bold text-slate-800 text-sm">{{ auth()->user()->name ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Detail Fields --}}
                        <div class="space-y-3 px-1">
                            @if($selectedJadwal && $tarif_per_orang > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Rute</span>
                                    <span class="text-sm font-bold text-slate-800">{{ $selectedJadwal->rute->asal }} → {{ $selectedJadwal->rute->tujuan }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Waktu</span>
                                    <span class="text-sm font-bold text-slate-800">{{ ucfirst($selectedJadwal->shift) }}, {{ $selectedJadwal->jam_berangkat->format('H:i') }} WIB</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Penumpang</span>
                                <span class="text-sm font-bold text-slate-800">{{ $jumlah_penumpang }} Kursi</span>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="border-t border-dashed border-slate-200"></div>

                        {{-- Pricing --}}
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between text-xs font-medium">
                                <span class="text-slate-500">Subtotal ({{ $jumlah_penumpang }}x)</span>
                                <span class="text-slate-800 font-bold">Rp {{ number_format($total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs font-medium">
                                <span class="text-slate-500">Uang Muka (DP)</span>
                                <span class="text-blue-600 font-bold">- Rp {{ number_format(50000, 0, ',', '.') }}</span>
                            </div>
                            <div class="p-3 bg-green-50 rounded-xl border border-green-100">
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="text-xs font-bold text-green-700 uppercase tracking-wider">Bayar di Driver</span>
                                    <span class="text-sm font-bold text-green-700">Rp {{ number_format(max(0, $total_harga - 50000), 0, ',', '.') }}</span>
                                </div>
                                <p class="text-xs font-medium text-green-600 uppercase tracking-wider">Pelunasan saat penjemputan</p>
                            </div>
                        </div>

                        {{-- Total DP --}}
                        <div class="flex items-end justify-between pt-3">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Bayar (DP)</p>
                            <p class="text-2xl font-bold text-blue-800 tracking-tight">Rp {{ number_format(50000, 0, ',', '.') }}</p>
                        </div>

                        {{-- CTA Button --}}
                        <button
                            wire:click="submit"
                            wire:loading.attr="disabled"
                            class="w-full bg-blue-800 hover:bg-blue-900 text-white py-3.5 rounded-xl font-semibold uppercase tracking-wider text-xs transition-colors flex items-center justify-center gap-2 shadow-sm"
                        >
                            <span wire:loading.remove>Konfirmasi Booking</span>
                            <span wire:loading class="inline-block w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                            <span wire:loading>Memproses...</span>
                            <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="button" wire:click="cariJadwal" class="w-full flex items-center justify-center gap-2 text-slate-400 hover:text-slate-800 text-xs font-semibold uppercase tracking-wider transition-colors">
                ← Ganti Jadwal
            </button>
        </div>
    </div>
</div>
