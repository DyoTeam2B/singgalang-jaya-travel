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
                    if (this.markerJemput) {
                        this.markerJemput.setLatLng([lat, lon]).bindPopup('<b>Titik Jemput (Biru)</b>').openPopup();
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
        // Default coordinates for Padang Panjang
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

        let markerJemput = L.marker([initialLatJ, initialLngJ], { icon: blueIcon, draggable: true }).addTo(map);
        markerJemput.bindPopup('<b>Titik Jemput (Biru)</b>');
        this.markerJemput = markerJemput;

        // Set initial values in Alpine/Livewire if not set
        if (!this.latJemput) {
            this.latJemput = initialLatJ;
            this.lngJemput = initialLngJ;
        }

        // Update function
        const updateCoords = () => {
            const posJ = markerJemput.getLatLng();
            this.latJemput = posJ.lat;
            this.lngJemput = posJ.lng;
        };

        markerJemput.on('dragend', updateCoords);

        // Click to place
        map.on('click', (e) => {
            markerJemput.setLatLng(e.latlng);
            markerJemput.bindPopup('<b>Titik Jemput (Biru)</b>').openPopup();
            updateCoords();
        });
        
        // Listen to livewire changes (e.g. if pre-loaded or set by code)
        this.$watch('latJemput', value => {
            if (value && value !== markerJemput.getLatLng().lat) {
                markerJemput.setLatLng([value, this.lngJemput]);
                map.setView([value, this.lngJemput], map.getZoom());
            }
        });
        this.$watch('lngJemput', value => {
            if (value && value !== markerJemput.getLatLng().lng) {
                markerJemput.setLatLng([this.latJemput, value]);
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
    <div class="mb-2 flex flex-col md:flex-row gap-2 text-xs font-semibold justify-between bg-slate-100 p-3 rounded-xl text-slate-600">
        <span class="flex items-center gap-1">🔵 Geser Pin Biru atau Klik di Peta untuk Lokasi Jemput</span>
    </div>
    <div id="leaflet-map-picker" wire:ignore class="h-80 w-full rounded-2xl border border-slate-200 shadow-sm z-10"></div>
</div>
