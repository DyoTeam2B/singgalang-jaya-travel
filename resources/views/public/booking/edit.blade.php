@extends('layouts.public')

@section('title', 'Ubah Lokasi Jemput - Singgalang Jaya Travel')

@section('content')
<div class="py-12 md:py-20 bg-slate-50 flex-1 flex items-center justify-center">
    <div class="max-w-3xl w-full mx-auto px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="p-6 border-b border-slate-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800 tracking-tight">Ubah Lokasi Penjemputan</h2>
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mt-0.5">Sesuaikan titik jemput Anda di peta</p>
                </div>
            </div>

            <div class="p-6 space-y-6">
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
                        initMap() {
                            const map = L.map('edit-map-picker').setView([this.latJemput, this.lngJemput], 14);
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
                            <textarea name="alamat_jemput" rows="3" class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all placeholder:text-slate-400 resize-none">{{ old('alamat_jemput', $booking->alamat_jemput) }}</textarea>
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

                        <div class="flex gap-6 text-xs font-medium text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-100">
                            <div>
                                <span class="text-slate-400">Latitude Baru:</span>
                                <span class="font-semibold" x-text="latJemput.toFixed(6)"></span>
                            </div>
                            <div>
                                <span class="text-slate-400">Longitude Baru:</span>
                                <span class="font-semibold" x-text="lngJemput.toFixed(6)"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-4 justify-between pt-4 border-t border-slate-100">
                        <a href="{{ route('cek-booking.index', ['kode_booking' => $booking->kode_booking]) }}" class="inline-flex justify-center items-center bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium px-6 py-3 rounded-xl transition-colors text-sm gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            Batal
                        </a>
                        <button type="submit" class="inline-flex justify-center items-center bg-blue-800 hover:bg-blue-900 text-white font-semibold px-8 py-3 rounded-xl transition-colors text-sm shadow-sm gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Simpan Lokasi Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
