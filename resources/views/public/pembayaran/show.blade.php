@extends('layouts.public')

@section('title', 'Pembayaran - Singgalang Jaya Travel')

@section('content')
<div class="py-12 md:py-20 bg-slate-50 flex-1">
    <div class="max-w-5xl w-full mx-auto px-6 lg:px-8">

        {{-- Step Indicator --}}
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

        @if(session('error'))
            <div class="mb-6">
                <div class="flex items-center gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($isExpired)
            {{-- Expired State --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-10 text-center space-y-6">
                    <div class="w-20 h-20 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 mx-auto">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">{{ $booking->kode_booking }}</p>
                        <h2 class="text-2xl font-bold text-slate-800">Batas Waktu Pembayaran Habis</h2>
                        <p class="text-sm text-slate-500 max-w-md mx-auto">
                            Maaf, batas waktu pembayaran DP (30 menit) untuk booking ini telah berakhir. Kursi telah dirilis kembali. Silakan lakukan pemesanan ulang.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <a href="{{ route('booking.create') }}" class="inline-flex items-center gap-2 bg-blue-800 hover:bg-blue-900 text-white font-semibold px-8 py-3.5 rounded-xl transition-colors text-sm shadow-sm">
                            Buat Pemesanan Baru
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @elseif($isAlreadyProcessed)
            {{-- Already Processed State --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-10 text-center space-y-6">
                    <div class="w-20 h-20 bg-green-50 rounded-2xl flex items-center justify-center text-green-500 mx-auto">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">{{ $booking->kode_booking }}</p>
                        <h2 class="text-2xl font-bold text-slate-800">Pembayaran Sudah Diterima / Diproses</h2>
                        <p class="text-sm text-slate-500 max-w-md mx-auto">
                            Status booking saat ini adalah <span class="font-semibold text-blue-800 uppercase">{{ $booking->status_booking }}</span>.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <a href="{{ route('cek-booking.index', ['kode_booking' => $booking->kode_booking]) }}" class="inline-flex items-center gap-2 bg-blue-800 hover:bg-blue-900 text-white font-semibold px-8 py-3.5 rounded-xl transition-colors text-sm shadow-sm">
                            Cek Status Booking
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @else
            {{-- Normal Upload Payment State — 2 Column Layout --}}
            <div class="grid lg:grid-cols-12 gap-8 items-start"
                x-data="{
                    secondsRemaining: {{ $secondsRemaining ?? 0 }},
                    isExpired: false,
                    timerText: '30:00',
                    previewUrl: null,
                    fileName: null,
                    paymentType: @js(old('jenis_pembayaran', \App\Models\Pembayaran::JENIS_DP)),
                    dpAmount: {{ $paymentSummary['dp_amount'] }},
                    fullPaymentAmount: {{ $paymentSummary['full_payment_amount'] }},
                    discountAmount: {{ $paymentSummary['discount_amount'] }},
                    formatRupiah(value) {
                        return new Intl.NumberFormat('id-ID').format(value);
                    },
                    selectedAmount() {
                        return this.paymentType === 'pelunasan' ? this.fullPaymentAmount : this.dpAmount;
                    },
                    init() {
                        this.updateText();
                        let interval = setInterval(() => {
                            if (this.secondsRemaining <= 0) {
                                this.isExpired = true;
                                clearInterval(interval);
                                window.location.reload();
                            } else {
                                this.secondsRemaining--;
                                this.updateText();
                            }
                        }, 1000);
                    },
                    updateText() {
                        let minutes = Math.floor(this.secondsRemaining / 60);
                        let seconds = this.secondsRemaining % 60;
                        this.timerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    },
                    handleFile(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.fileName = file.name;
                            if (file.type.startsWith('image/')) {
                                this.previewUrl = URL.createObjectURL(file);
                            } else {
                                this.previewUrl = null;
                            }
                        }
                    },
                    clearFile() {
                        this.previewUrl = null;
                        this.fileName = null;
                        document.getElementById('bukti-upload').value = '';
                    }
                }">

                {{-- Left Column: Payment Instructions --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-bold text-slate-800 tracking-tight">Pembayaran Booking</h2>
                                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">
                                        <span x-text="paymentType === 'pelunasan' ? 'Bayar lunas dengan voucher 10%' : 'Selesaikan pembayaran DP'"></span>
                                    </p>
                                </div>
                            </div>

                            {{-- Timer Badge --}}
                            <div class="px-4 py-3 rounded-xl border flex items-center justify-between transition-colors"
                                :class="isExpired ? 'bg-red-50 border-red-200 text-red-600' : 'bg-amber-50 border-amber-200 text-amber-600'">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-xs font-bold uppercase tracking-wider" x-text="isExpired ? 'Batas Waktu Habis' : 'Batas Waktu Pembayaran'"></span>
                                </div>
                                <span class="text-lg font-bold tracking-tight font-mono" x-text="timerText">30:00</span>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            {{-- Bank Account Card --}}
                            <div class="space-y-3">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Transfer Ke Rekening</h3>

                                <div class="bg-slate-800 rounded-2xl p-5 text-white relative overflow-hidden">
                                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-600/10 rounded-full blur-2xl"></div>
                                    <div class="relative z-10">
                                        <div class="flex justify-between items-start mb-4">
                                            <span class="text-base font-bold tracking-wider uppercase">BANK BCA</span>
                                            <div class="w-10 h-6 bg-white/10 rounded-md"></div>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nomor Rekening</p>
                                            <div class="flex items-center gap-2">
                                                <p class="text-xl font-bold tracking-wider">123 456 7890</p>
                                                <button type="button" onclick="navigator.clipboard.writeText('1234567890').then(() => alert('Nomor rekening disalin!'))" class="p-1.5 bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex justify-between items-end">
                                            <p class="text-sm font-bold uppercase">Singgalang Jaya Travel</p>
                                            <span class="text-xs font-bold uppercase text-blue-400">Verifikasi Cepat</span>
                                        </div>
                                        <div class="mt-4 pt-4 border-t border-white/10 flex justify-between items-center">
                                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nominal Transfer</span>
                                            <span class="text-lg font-bold text-white">Rp <span x-text="formatRupiah(selectedAmount())">{{ number_format($paymentSummary['dp_amount'], 0, ',', '.') }}</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment Instructions --}}
                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                                <div class="flex gap-3">
                                    <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shrink-0 shadow-sm border border-blue-100">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-xs font-bold text-blue-900 uppercase tracking-wider">Instruksi Pembayaran</p>
                                        <ol class="text-xs font-medium text-blue-700 space-y-1.5 list-decimal ml-4">
                                            <li>
                                                Lakukan transfer sebesar
                                                <span class="text-blue-900 font-bold">Rp <span x-text="formatRupiah(selectedAmount())">{{ number_format($paymentSummary['dp_amount'], 0, ',', '.') }}</span></span>
                                                <span x-text="paymentType === 'pelunasan' ? '(lunas setelah voucher)' : '(DP)'"></span>.
                                            </li>
                                            <li>Pastikan nama pengirim atau berita transfer sesuai.</li>
                                            <li>Ambil screenshot atau foto bukti transfer.</li>
                                            <li>Unggah bukti tersebut pada form di sebelah kanan/bawah.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>

                            {{-- Booking Summary --}}
                            <div class="p-4 bg-slate-50 rounded-xl space-y-2 text-sm text-slate-600 border border-slate-100">
                                <div class="flex justify-between">
                                    <span>Kode Booking:</span>
                                    <span class="font-mono font-bold text-slate-800">{{ $booking->kode_booking }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Rute Travel:</span>
                                    <span class="font-semibold text-slate-800">{{ $booking->jadwal->rute->asal }} → {{ $booking->jadwal->rute->tujuan }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Jumlah Penumpang:</span>
                                    <span class="font-semibold text-slate-800">{{ $booking->jumlah_penumpang }} Orang</span>
                                </div>
                                <div class="flex justify-between border-t border-slate-200/50 pt-2 font-semibold">
                                    <span class="text-slate-800">Total Tarif:</span>
                                    <span class="text-slate-800">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Upload Form --}}
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <form action="{{ route('booking.pembayaran.store', ['kode' => $booking->kode_booking]) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                            @csrf

                            <div>
                                <h3 class="text-lg font-bold text-slate-800 tracking-tight mb-1">Unggah Bukti</h3>
                                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Format: JPG, PNG, atau PDF</p>
                            </div>

                            {{-- Payment Type --}}
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-slate-700">Pilih Jenis Pembayaran</label>
                                <div class="grid gap-3">
                                    <label class="block cursor-pointer rounded-2xl border p-4 transition-colors"
                                        :class="paymentType === 'dp' ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500/10' : 'border-slate-200 bg-white hover:bg-slate-50'">
                                        <input type="radio" name="jenis_pembayaran" value="dp" x-model="paymentType" class="sr-only">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-bold text-slate-800">Bayar DP</p>
                                                <p class="text-xs font-medium text-slate-500 mt-1">Kursi diamankan setelah bukti DP diverifikasi admin.</p>
                                            </div>
                                            <span class="text-sm font-bold text-blue-700 whitespace-nowrap">Rp {{ number_format($paymentSummary['dp_amount'], 0, ',', '.') }}</span>
                                        </div>
                                    </label>

                                    <label class="block cursor-pointer rounded-2xl border p-4 transition-colors"
                                        :class="paymentType === 'pelunasan' ? 'border-emerald-500 bg-emerald-50 ring-2 ring-emerald-500/10' : 'border-slate-200 bg-white hover:bg-slate-50'">
                                        <input type="radio" name="jenis_pembayaran" value="pelunasan" x-model="paymentType" class="sr-only">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <p class="text-sm font-bold text-slate-800">Bayar Lunas</p>
                                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700">{{ $paymentSummary['voucher_code'] }}</span>
                                                </div>
                                                <p class="text-xs font-medium text-slate-500 mt-1">Voucher diskon {{ $paymentSummary['discount_percent'] }}% langsung diterapkan.</p>
                                            </div>
                                            <span class="text-sm font-bold text-emerald-700 whitespace-nowrap">Rp {{ number_format($paymentSummary['full_payment_amount'], 0, ',', '.') }}</span>
                                        </div>
                                    </label>
                                </div>
                                @error('jenis_pembayaran')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Dropzone Upload --}}
                            <div class="space-y-3">
                                <div class="relative group">
                                    <input
                                        type="file"
                                        id="bukti-upload"
                                        name="bukti_pembayaran"
                                        accept="image/*,.pdf"
                                        @change="handleFile($event)"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                    >
                                    <div class="w-full h-56 rounded-2xl border-2 border-dashed transition-all flex flex-col items-center justify-center p-6 text-center"
                                        :class="previewUrl || fileName ? 'border-green-400 bg-green-50/30' : 'border-slate-200 bg-slate-50 group-hover:bg-slate-100 group-hover:border-blue-300'">

                                        <template x-if="previewUrl">
                                            <div class="relative w-full h-full">
                                                <img :src="previewUrl" alt="Bukti Transfer" class="w-full h-full object-contain rounded-xl">
                                                <div class="absolute top-2 right-2 bg-white rounded-full p-1.5 shadow-sm text-green-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                            </div>
                                        </template>

                                        <template x-if="!previewUrl && fileName">
                                            <div class="text-center">
                                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm border border-slate-100 mx-auto mb-3">
                                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </div>
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
                                    <button type="button" @click="clearFile()" class="w-full text-xs font-semibold text-red-500 hover:text-red-600 uppercase tracking-wider transition-colors">
                                        Hapus & Ganti Gambar
                                    </button>
                                </template>

                                @error('bukti_pembayaran')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Metode Pembayaran --}}
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

                            {{-- Catatan --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                                <textarea name="catatan" rows="2" class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none transition-colors" placeholder="Masukkan catatan tambahan jika diperlukan...">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Price Summary --}}
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-2.5">
                                <div class="flex justify-between items-center text-xs font-medium">
                                    <span class="text-slate-500 uppercase tracking-wider">Total Tiket</span>
                                    <span class="text-slate-800 font-bold">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                                </div>
                                <div x-show="paymentType === 'dp'" class="space-y-2.5">
                                    <div class="flex justify-between items-center text-xs font-medium">
                                        <span class="text-slate-500 uppercase tracking-wider">Down Payment (DP)</span>
                                        <span class="text-blue-600 font-bold">Rp {{ number_format($paymentSummary['dp_amount'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs font-medium">
                                        <span class="text-slate-500 uppercase tracking-wider">Sisa Bayar ke Driver</span>
                                        <span class="text-slate-800 font-bold">Rp {{ number_format($paymentSummary['dp_remaining'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div x-show="paymentType === 'pelunasan'" class="space-y-2.5">
                                    <div class="flex justify-between items-center text-xs font-medium">
                                        <span class="text-slate-500 uppercase tracking-wider">Voucher {{ $paymentSummary['voucher_code'] }} ({{ $paymentSummary['discount_percent'] }}%)</span>
                                        <span class="text-emerald-600 font-bold">- Rp {{ number_format($paymentSummary['discount_amount'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs font-medium">
                                        <span class="text-slate-500 uppercase tracking-wider">Sisa Bayar ke Driver</span>
                                        <span class="text-emerald-700 font-bold">Rp 0</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center border-t border-slate-200 pt-2 text-sm font-bold">
                                    <span class="text-slate-800 uppercase tracking-wider">Total Transfer</span>
                                    <span class="text-blue-800">Rp <span x-text="formatRupiah(selectedAmount())">{{ number_format($paymentSummary['dp_amount'], 0, ',', '.') }}</span></span>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <button
                                type="submit"
                                :disabled="isExpired"
                                class="w-full bg-blue-800 hover:bg-blue-900 disabled:bg-slate-300 disabled:cursor-not-allowed text-white py-3.5 rounded-xl font-semibold uppercase tracking-wider text-xs transition-colors flex items-center justify-center gap-2 shadow-sm"
                            >
                                <template x-if="isExpired">
                                    <span>Pembayaran Kadaluarsa</span>
                                </template>
                                <template x-if="!isExpired">
                                    <span class="flex items-center gap-2">
                                        <span x-text="paymentType === 'pelunasan' ? 'Kirim Bukti Lunas' : 'Kirim Bukti DP'"></span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </span>
                                </template>
                            </button>

                            <a href="{{ url()->previous() }}" class="w-full flex items-center justify-center gap-2 text-slate-400 hover:text-slate-800 text-xs font-semibold uppercase tracking-wider transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                Kembali ke Review
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
