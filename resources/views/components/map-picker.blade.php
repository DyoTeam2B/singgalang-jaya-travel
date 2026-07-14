@props([
    'latJemputBind' => 'latitude_jemput',
    'lngJemputBind' => 'longitude_jemput'
])

<div x-data="{
    latJemput: @entangle($latJemputBind),
    lngJemput: @entangle($lngJemputBind),
    markerJemput: null,
    map: null,
    
    geocodeAddress(address) {
        if (!address || address.trim().length < 3) {
            alert('Silakan masukkan alamat penjemputan yang lebih lengkap.');
            return;
        }
        
        const btn = document.getElementById('btn-cari-lokasi');
        const btnText = document.getElementById('text-cari-lokasi');
        if (btn && btnText) {
            btn.disabled = true;
            btnText.textContent = 'Mencari Lokasi...';
            btn.classList.add('opacity-70', 'cursor-wait');
        }

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
                    
                    const blueIcon = new L.Icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    });

                    if (!this.markerJemput) {
                        this.markerJemput = L.marker([lat, lon], { icon: blueIcon, draggable: true }).addTo(this.map);
                        this.markerJemput.bindPopup('<b>Titik Jemput (Biru)</b>');
                        this.markerJemput.on('dragend', () => {
                            const pos = this.markerJemput.getLatLng();
                            this.latJemput = pos.lat;
                            this.lngJemput = pos.lng;
                        });
                    } else {
                        this.markerJemput.setLatLng([lat, lon]);
                    }
                    
                    this.markerJemput.openPopup();
                    this.map.setView([lat, lon], 16);
                } else {
                    alert('Alamat tidak ditemukan di peta. Silakan cari dengan nama jalan/kota yang lebih spesifik.');
                }
            })
            .catch(err => {
                console.error('Geocoding error:', err);
                alert('Gagal menghubungi layanan peta. Silakan coba lagi.');
            })
            .finally(() => {
                if (btn && btnText) {
                    btn.disabled = false;
                    btnText.textContent = 'Cari Lokasi';
                    btn.classList.remove('opacity-70', 'cursor-wait');
                }
            });
    },

    initMap() {
        // Default coordinates for Padang Panjang (Singgalang Jaya Travel area)
        let initialLatJ = this.latJemput || -0.4669;
        let initialLngJ = this.lngJemput || 100.3986;

        const map = L.map('leaflet-map-picker').setView([initialLatJ, initialLngJ], 14);
        this.map = map;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const blueIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Initialize marker if it already exists (e.g. validation error re-render)
        if (this.latJemput && this.lngJemput) {
            this.markerJemput = L.marker([this.latJemput, this.lngJemput], { icon: blueIcon, draggable: true }).addTo(map);
            this.markerJemput.bindPopup('<b>Titik Jemput (Biru)</b>');
            this.markerJemput.on('dragend', () => {
                const pos = this.markerJemput.getLatLng();
                this.latJemput = pos.lat;
                this.lngJemput = pos.lng;
            });
        }

        // Click on map to place marker
        map.on('click', (e) => {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            this.latJemput = lat;
            this.lngJemput = lng;

            if (!this.markerJemput) {
                this.markerJemput = L.marker([lat, lng], { icon: blueIcon, draggable: true }).addTo(map);
                this.markerJemput.bindPopup('<b>Titik Jemput (Biru)</b>');
                this.markerJemput.on('dragend', () => {
                    const pos = this.markerJemput.getLatLng();
                    this.latJemput = pos.lat;
                    this.lngJemput = pos.lng;
                });
            } else {
                this.markerJemput.setLatLng(e.latlng);
            }
            this.markerJemput.openPopup();
        });
        
        // Listen to livewire coordinate changes
        this.$watch('latJemput', value => {
            if (value && this.markerJemput && value !== this.markerJemput.getLatLng().lat) {
                this.markerJemput.setLatLng([value, this.lngJemput]);
                map.setView([value, this.lngJemput], map.getZoom());
            }
        });
        this.$watch('lngJemput', value => {
            if (value && this.markerJemput && value !== this.markerJemput.getLatLng().lng) {
                this.markerJemput.setLatLng([this.latJemput, value]);
                map.setView([this.latJemput, value], map.getZoom());
            }
        });
    }
}" 
@set-active-marker.window="
    if (markerJemput) {
        markerJemput.openPopup();
    }
"
@search-and-set-marker.window="
    geocodeAddress($event.detail.address);
"
x-init="initMap()" class="w-full">
    <!-- Info banner shown when marker is not placed -->
    <div x-show="!latJemput" class="mb-2 flex flex-col md:flex-row gap-2 text-xs font-semibold justify-between bg-amber-50 p-3 rounded-xl border border-amber-200 text-amber-800">
        <span class="flex items-center gap-1">⚠️ Silakan isi alamat jemput kemudian klik tombol Cari Lokasi.</span>
    </div>
    
    <!-- Info banner shown when marker is placed -->
    <div x-show="latJemput" class="mb-2 flex flex-col md:flex-row gap-2 text-xs font-semibold justify-between bg-slate-100 p-3 rounded-xl text-slate-600" style="display: none;">
        <span class="flex items-center gap-1">🔵 Geser Pin Biru atau Klik di Peta untuk Lokasi Jemput</span>
    </div>
    
    <div id="leaflet-map-picker" wire:ignore class="h-80 w-full rounded-2xl border border-slate-200 shadow-sm z-10"></div>
</div>
