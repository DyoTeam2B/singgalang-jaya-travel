@extends('layouts.public')

@section('title', 'Pembayaran DP - Singgalang Jaya Travel')

@section('content')
<div class="py-12 md:py-20 bg-slate-50 flex-1">
    <div class="max-w-5xl w-full mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-center mb-10 overflow-x-auto pb-4">
            <div class="flex items-center gap-2 sm:gap-4 text-xs font-semibold uppercase tracking-wider min-w-max">
                <div class="flex items-center gap-2 text-blue-800">
                    <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 text-xs font-bold border border-blue-200">1</span>
                    <span>Pemesanan</span>
                </div>
                <div class="w-10 h-0.5 bg-blue-300"></div>
                <div class="flex items-center gap-2 text-blue-800">
                    <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 text-xs font-bold border border-blue-200">2</span>
                    <span>Review</span>
                </div>
                <div class="w-10 h-0.5 bg-blue-300"></div>
                <div class="flex items-center gap-2 text-blue-800">
                    <span class="w-8 h-8 rounded-full bg-blue-800 flex items-center justify-center text-white text-xs font-bold shadow-sm">3</span>
                    <span>Pembayaran</span>
                </div>
            </div>
        </div>

        <x-alert />

        @if($isAlreadyProcessed)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-10 text-center space-y-6">
                    <div class="w-20 h-20 bg-green-50 rounded-2xl flex items-center justify-center text-green-500 mx-auto">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">{{ $booking->kode_booking }}</p>
                        <h2 class="text-2xl font-bold text-slate-800">Pembayaran Sudah Diproses</h2>
                        <p class="text-sm text-slate-500 max-w-md mx-auto">
                            Status booking saat ini adalah <span class="font-semibold text-blue-800 uppercase">{{ str_replace('_', ' ', $booking->status_booking) }}</span>.
                        </p>
                    </div>
                    <a href="{{ route('booking.show', ['kode' => $booking->kode_booking]) }}" class="inline-flex items-center gap-2 bg-blue-800 hover:bg-blue-900 text-white font-semibold px-8 py-3.5 rounded-xl transition-colors text-sm shadow-sm">
                        Lihat Detail Booking
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        @else
            <div class="grid lg:grid-cols-12 gap-8 items-start"
                x-data="{
                    previewUrl: null,
                    fileName: null,
                    handleFile(event) {
                        const file = event.target.files[0];
                        if (!file) return;
                        this.fileName = file.name;
                        this.previewUrl = file.type.startsWith('image/') ? URL.createObjectURL(file) : null;
                    },
                    clearFile() {
                        this.previewUrl = null;
                        this.fileName = null;
                        document.getElementById('bukti-upload').value = '';
                    }
                }">
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-slate-800 tracking-tight">Pembayaran DP</h2>
                                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Upload bukti DP flat Rp 50.000</p>
                                </div>
                            </div>
                            <div class="px-4 py-3 rounded-xl border flex items-start gap-3 bg-blue-50 border-blue-100 text-blue-800">
                                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-xs font-semibold leading-relaxed">Booking Anda akan diproses setelah bukti pembayaran DP diverifikasi oleh admin.</p>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            <div class="space-y-3">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Transfer Ke Rekening</h3>
                                <div class="bg-slate-800 rounded-2xl p-5 text-white relative overflow-hidden">
                                    <div class="relative z-10">
                                        <div class="flex justify-between items-start mb-4">
                                            <span class="text-base font-bold tracking-wider uppercase">BANK BCA</span>
                                            <div class="w-10 h-6 bg-white/10 rounded-md"></div>
                                        </div>
                                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nomor Rekening</p>
                                        <p class="text-xl font-bold tracking-wider mt-1">123 456 7890</p>
                                        <div class="mt-4 flex justify-between items-end">
                                            <p class="text-sm font-bold uppercase">Singgalang Jaya Travel</p>
                                            <span class="text-xs font-bold uppercase text-blue-400">DP Rp50.000</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                                <ol class="text-xs font-medium text-blue-700 space-y-1.5 list-decimal ml-4">
                                    <li>Transfer DP sebesar <span class="text-blue-900 font-bold">Rp 50.000</span>.</li>
                                    <li>Pastikan nama pengirim atau berita transfer mudah dikenali.</li>
                                    <li>Upload screenshot/foto bukti transfer melalui form.</li>
                                    <li>Admin akan memverifikasi bukti pembayaran.</li>
                                    <li class="font-bold text-amber-700">DP tidak dapat dikembalikan jika pembatalan dilakukan oleh pelanggan.</li>
                                </ol>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-xl space-y-2 text-sm text-slate-600 border border-slate-100">
                                <div class="flex justify-between gap-4">
                                    <span>Kode Booking:</span>
                                    <span class="font-mono font-bold text-slate-800">{{ $booking->kode_booking }}</span>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <span>Rute Travel:</span>
                                    <span class="font-semibold text-slate-800 text-right">{{ $booking->jadwal->rute->asal }} -> {{ $booking->jadwal->rute->tujuan }}</span>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <span>Jumlah Penumpang:</span>
                                    <span class="font-semibold text-slate-800">{{ $booking->jumlah_penumpang }} Orang</span>
                                </div>
                                <div class="flex justify-between gap-4 border-t border-slate-200/50 pt-2 font-semibold">
                                    <span class="text-slate-800">Total Tarif:</span>
                                    <span class="text-slate-800">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <form action="{{ route('booking.pembayaran.store', ['kode' => $booking->kode_booking]) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                            @csrf

                            <div>
                                <h3 class="text-lg font-bold text-slate-800 tracking-tight mb-1">Unggah Bukti</h3>
                                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Format: JPG, PNG, atau PDF. Maksimal 2MB.</p>
                            </div>

                            <div class="space-y-3">
                                <div class="relative group">
                                    <input type="file" id="bukti-upload" name="bukti_pembayaran" accept="image/*,.pdf" @change="handleFile($event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                    <div class="w-full h-56 rounded-2xl border-2 border-dashed transition-all flex flex-col items-center justify-center p-6 text-center" :class="previewUrl || fileName ? 'border-green-400 bg-green-50/30' : 'border-slate-200 bg-slate-50 group-hover:bg-slate-100 group-hover:border-blue-300'">
                                        <template x-if="previewUrl">
                                            <img :src="previewUrl" alt="Bukti Transfer" class="w-full h-full object-contain rounded-xl">
                                        </template>
                                        <template x-if="!previewUrl && fileName">
                                            <div class="text-center">
                                                <p class="text-sm font-semibold text-slate-800 mb-0.5" x-text="fileName"></p>
                                                <p class="text-xs font-medium text-slate-400">File siap diunggah</p>
                                            </div>
                                        </template>
                                        <template x-if="!previewUrl && !fileName">
                                            <div class="text-center">
                                                <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center shadow-sm border border-slate-100 mb-3 mx-auto group-hover:scale-105 transition-transform">
                                                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                </div>
                                                <p class="text-sm font-semibold text-slate-800 mb-0.5">Pilih Bukti Transfer</p>
                                                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Klik atau seret file ke sini</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <template x-if="previewUrl || fileName">
                                    <button type="button" @click="clearFile()" class="w-full text-xs font-semibold text-red-500 hover:text-red-600 uppercase tracking-wider transition-colors">Hapus & Ganti File</button>
                                </template>

                                @error('bukti_pembayaran')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Metode Transfer Bank</label>
                                <select name="metode_pembayaran" class="w-full border border-slate-300 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">-- Pilih Rekening Tujuan --</option>
                                    <option value="Transfer Bank BCA" {{ old('metode_pembayaran') == 'Transfer Bank BCA' ? 'selected' : '' }}>Transfer Bank BCA (123 456 7890)</option>
                                </select>
                                @error('metode_pembayaran')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-2.5">
                                <div class="flex justify-between items-center text-xs font-medium">
                                    <span class="text-slate-500 uppercase tracking-wider">Down Payment (DP)</span>
                                    <span class="text-blue-600 font-bold">Rp {{ number_format(50000, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs font-medium">
                                    <span class="text-slate-500 uppercase tracking-wider">Total Tiket</span>
                                    <span class="text-slate-800 font-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs font-medium">
                                    <span class="text-slate-500 uppercase tracking-wider">Pelunasan ke Driver</span>
                                    <span class="text-slate-800 font-bold">Rp {{ number_format(max(0, $booking->total_harga - 50000), 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white py-3.5 rounded-xl font-semibold uppercase tracking-wider text-xs transition-colors flex items-center justify-center gap-2 shadow-sm">
                                Kirim Bukti Pembayaran
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>

                            <a href="{{ route('booking.show', ['kode' => $booking->kode_booking]) }}" class="w-full flex items-center justify-center gap-2 text-slate-400 hover:text-slate-800 text-xs font-semibold uppercase tracking-wider transition-colors">
                                Kembali ke Detail Booking
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection