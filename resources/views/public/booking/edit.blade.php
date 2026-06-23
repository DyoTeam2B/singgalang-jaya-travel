@extends('layouts.public')

@section('title', 'Ubah Detail Pemesanan - Singgalang Jaya Travel')

@section('content')
@php
    $bookedSeats = $booking->jadwal->bookings()
        ->whereNotIn('status_booking', [
            \App\Models\Booking::STATUS_CANCELLED,
            \App\Models\Booking::STATUS_EXPIRED
        ])
        ->where('id', '!=', $booking->id)
        ->sum('jumlah_penumpang');

    $availableSeatsForUser = $booking->jadwal->kuota - $bookedSeats;
    $canEditPassengers = in_array($booking->status_booking, [
        \App\Models\Booking::STATUS_BOOKING_DIBUAT,
        \App\Models\Booking::STATUS_MENUNGGU_VERIFIKASI
    ]);
@endphp

<div class="py-12 md:py-20 bg-slate-50 flex-1 flex items-center justify-center">
    <div class="max-w-3xl w-full mx-auto px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="p-6 border-b border-slate-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800 tracking-tight">Ubah Detail Pemesanan</h2>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mt-0.5">Sesuaikan titik penjemputan dan jumlah penumpang Anda</p>
                </div>
            </div>

            <div class="p-6 space-y-6">
                {{-- Session Error Notification --}}
                @if(session('error'))
                    <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Booking Summary Banner --}}
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-sm">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Kode Booking</span>
                        <span class="font-bold text-slate-800">{{ $booking->kode_booking }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Rute Perjalanan</span>
                        <span class="font-semibold text-slate-700">{{ $booking->jadwal->rute->asal }} → {{ $booking->jadwal->rute->tujuan }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Jadwal</span>
                        <span class="font-semibold text-slate-700">{{ $booking->jadwal->tanggal_keberangkatan->format('d M Y') }} ({{ $booking->jadwal->jam_berangkat->format('H:i') }})</span>
                    </div>
                </div>

                {{-- Form --}}
                <form action="{{ route('booking.update', ['kode' => $booking->kode_booking]) }}" method="POST" class="space-y-6"
                    x-data="{
                        latJemput: {{ $booking->latitude_jemput ?? -0.4669 }},
                        lngJemput: {{ $booking->longitude_jemput ?? 100.3986 }},
                        marker: null,
                        map: null,
                        jumlahPenumpang: {{ old('jumlah_penumpang', $booking->jumlah_penumpang) }},
                        tarifPerOrang: {{ $booking->jadwal->rute->tarif }},
                        totalHarga() {
                            return this.jumlahPenumpang * this.tarifPerOrang;
                        },
                        bayarKeDriver() {
                            return Math.max(0, this.totalHarga() - 50000);
                        },
                        geocodeAddress(address) {
                            if (!address || address.trim().length < 3) return;
                            
                            let query = address;
                            if (!address.toLowerCase().includes('indonesia')) {
                                query += ', Indonesia';
                            }

                            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
                                .then(res => res.json())
                                .then(data => {
                                    if (data && data.length > 0) {
                                        const lat = parseFloat(data[0].lat);
                                        const lon = parseFloat(data[0].lon);
                                        
                                        this.latJemput = lat;
                                        this.lngJemput = lon;
                                        if (this.marker) {
                                            this.marker.setLatLng([lat, lon]).bindPopup('<b>Lokasi Jemput Baru</b>').openPopup();
                                        }
                                        if (this.map) {
                                            this.map.setView([lat, lon], 14);
                                        }
                                    } else {
                                        alert('Alamat tidak ditemukan di peta. Silakan cari dengan nama jalan/kota yang lebih spesifik atau geser pin secara manual.');
                                    }
                                })
                                .catch(err => {
                                    console.error('Geocoding error:', err);
                                    alert('Gagal menghubungi layanan peta. Silakan geser pin secara manual.');
                                });
                        },
                        initMap() {
                            const map = L.map('edit-map-picker').setView([this.latJemput, this.lngJemput], 14);
                            this.map = map;

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; OpenStreetMap'
                            }).addTo(map);

                            const blueIcon = new L.Icon({
                                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                                shadowSize: [41, 41]
                            });

                            let marker = L.marker([this.latJemput, this.lngJemput], { icon: blueIcon, draggable: true }).addTo(map);
                            marker.bindPopup('<b>Lokasi Jemput Sekarang</b>').openPopup();
                            this.marker = marker;

                            marker.on('dragend', (e) => {
                                const pos = e.target.getLatLng();
                                this.latJemput = pos.lat;
                                this.lngJemput = pos.lng;
                            });

                            map.on('click', (e) => {
                                marker.setLatLng(e.latlng);
                                this.latJemput = e.latlng.lat;
                                this.lngJemput = e.latlng.lng;
                            });
                        }
                    }" x-init="initMap()">

                    @csrf
                    @method('PUT')

                    <input type="hidden" name="latitude_jemput" x-model="latJemput">
                    <input type="hidden" name="longitude_jemput" x-model="lngJemput">

                    {{-- Alamat Jemput --}}
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Alamat Penjemputan (Door to Door)</label>
                        </div>
                        <div class="relative">
                            <svg class="absolute left-4 top-4 w-5 h-5 text-blue-600 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <textarea id="alamat_jemput" name="alamat_jemput" rows="3" class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 resize-none">{{ old('alamat_jemput', $booking->alamat_jemput) }}</textarea>
                        </div>
                        <div class="flex flex-wrap gap-2 justify-end mt-1">
                            <button type="button" @click="document.getElementById('edit-map-picker').scrollIntoView({ behavior: 'smooth' });" class="inline-flex items-center gap-1 text-xs font-bold text-slate-600 hover:text-blue-800 transition-all">
                                📍 Tentukan Pin Jemput di Peta
                            </button>
                            <button type="button" @click="
                                const addr = document.getElementById('alamat_jemput').value;
                                if(addr.trim().length < 3) { alert('Silakan isi alamat jemput terlebih dahulu.'); return; }
                                geocodeAddress(addr);
                                document.getElementById('edit-map-picker').scrollIntoView({ behavior: 'smooth' });
                            " class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-800 hover:text-blue-900 transition-all">
                                🔍 Cari & Set Pin Jemput
                            </button>
                        </div>
                        @error('alamat_jemput')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Map --}}
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-slate-700">Tentukan Titik Penjemputan Baru di Peta</label>
                        <div class="bg-blue-50 rounded-xl p-3 border border-blue-100 flex items-start gap-2">
                            <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-xs font-medium text-blue-700">Geser pin biru atau klik di peta untuk menandai titik jemput baru.</p>
                        </div>
                        <div id="edit-map-picker" class="h-80 w-full rounded-2xl border border-slate-200 shadow-sm z-10"></div>
                    </div>

                    {{-- Section: Jumlah Penumpang --}}
                    @if($canEditPassengers)
                        <div class="space-y-3 border-t border-slate-100 pt-6">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                                <label for="jumlah_penumpang" class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jumlah Penumpang</label>
                            </div>
                            <div class="relative">
                                <select id="jumlah_penumpang" name="jumlah_penumpang" x-model.number="jumlahPenumpang"
                                        class="w-full pl-4 pr-10 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all appearance-none cursor-pointer">
                                    @for($i = 1; $i <= min(10, $availableSeatsForUser); $i++)
                                        <option value="{{ $i }}">{{ $i }} Penumpang</option>
                                    @endfor
                                </select>
                            </div>
                            @error('jumlah_penumpang')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            {{-- Dynamic pricing summary --}}
                            <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 space-y-2 mt-4">
                                <div class="flex justify-between items-center text-xs font-semibold text-blue-700">
                                    <span>Tarif per Orang:</span>
                                    <span>Rp {{ number_format($booking->jadwal->rute->tarif, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs font-semibold text-blue-700">
                                    <span>Subtotal Baru (<span x-text="jumlahPenumpang"></span>x):</span>
                                    <span>Rp <span x-text="new Intl.NumberFormat('id-ID').format(totalHarga())"></span></span>
                                </div>
                                <div class="flex justify-between items-center text-xs font-semibold text-blue-700">
                                    <span>Down Payment (DP Flat):</span>
                                    <span>Rp 50.000</span>
                                </div>
                                <div class="border-t border-blue-200/50 my-1"></div>
                                <div class="flex justify-between items-center text-sm font-bold text-blue-800">
                                    <span>Bayar ke Driver (Saat Keberangkatan):</span>
                                    <span>Rp <span x-text="new Intl.NumberFormat('id-ID').format(bayarKeDriver())"></span></span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-4 justify-between pt-4 border-t border-slate-100">
                        <a href="{{ route('booking.show', ['kode' => $booking->kode_booking]) }}" class="inline-flex justify-center items-center bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium px-6 py-3 rounded-xl transition-colors text-sm gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            Batal
                        </a>
                        <button type="submit" class="inline-flex justify-center items-center bg-blue-800 hover:bg-blue-900 text-white font-semibold px-8 py-3 rounded-xl transition-colors text-sm shadow-sm gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
