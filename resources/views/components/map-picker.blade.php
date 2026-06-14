@props([
    'latJemputBind' => 'latitude_jemput',
    'lngJemputBind' => 'longitude_jemput',
    'latTujuanBind' => 'latitude_tujuan',
    'lngTujuanBind' => 'longitude_tujuan'
])

<div x-data="{
    latJemput: @entangle($latJemputBind),
    lngJemput: @entangle($lngJemputBind),
    latTujuan: @entangle($latTujuanBind),
    lngTujuan: @entangle($lngTujuanBind),
    initMap() {
        const map = L.map('leaflet-map-picker').setView([-0.4669, 100.3986], 13);
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

        const redIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Use values if already present, otherwise default
        let initialLatJ = this.latJemput || -0.4669;
        let initialLngJ = this.lngJemput || 100.3986;
        let initialLatT = this.latTujuan || -0.4600;
        let initialLngT = this.lngTujuan || 100.4100;

        let markerJemput = L.marker([initialLatJ, initialLngJ], { icon: blueIcon, draggable: true }).addTo(map);
        markerJemput.bindPopup('<b>Titik Jemput (Biru)</b>');

        let markerTujuan = L.marker([initialLatT, initialLngT], { icon: redIcon, draggable: true }).addTo(map);
        markerTujuan.bindPopup('<b>Titik Tujuan (Merah)</b>');

        // Set initial values in Alpine/Livewire if not set
        if (!this.latJemput) {
            this.latJemput = initialLatJ;
            this.lngJemput = initialLngJ;
        }
        if (!this.latTujuan) {
            this.latTujuan = initialLatT;
            this.lngTujuan = initialLngT;
        }

        // Fit bounds to show both markers if they are set
        try {
            const bounds = L.latLngBounds([markerJemput.getLatLng(), markerTujuan.getLatLng()]);
            map.fitBounds(bounds, { padding: [50, 50] });
        } catch (e) {
            console.log(e);
        }

        // Update function
        const updateCoords = () => {
            const posJ = markerJemput.getLatLng();
            this.latJemput = posJ.lat;
            this.lngJemput = posJ.lng;
            
            const posT = markerTujuan.getLatLng();
            this.latTujuan = posT.lat;
            this.lngTujuan = posT.lng;
        };

        markerJemput.on('dragend', updateCoords);
        markerTujuan.on('dragend', updateCoords);

        // Click to place
        let activeMarker = 'jemput';
        map.on('click', (e) => {
            if (activeMarker === 'jemput') {
                markerJemput.setLatLng(e.latlng);
                activeMarker = 'tujuan';
                markerJemput.bindPopup('<b>Titik Jemput (Biru)</b>').openPopup();
            } else {
                markerTujuan.setLatLng(e.latlng);
                activeMarker = 'jemput';
                markerTujuan.bindPopup('<b>Titik Tujuan (Merah)</b>').openPopup();
            }
            updateCoords();
        });
        
        // Listen to livewire changes (e.g. if pre-loaded or set by code)
        this.$watch('latJemput', value => {
            if (value && value !== markerJemput.getLatLng().lat) {
                markerJemput.setLatLng([value, this.lngJemput]);
            }
        });
        this.$watch('latTujuan', value => {
            if (value && value !== markerTujuan.getLatLng().lat) {
                markerTujuan.setLatLng([value, this.lngTujuan]);
            }
        });
    }
}" x-init="initMap()" class="w-full">
    <div class="mb-2 flex flex-col md:flex-row gap-2 text-xs font-semibold justify-between bg-slate-100 p-3 rounded-xl text-slate-600">
        <span class="flex items-center gap-1">🔵 Geser Pin Biru untuk Lokasi Jemput</span>
        <span class="flex items-center gap-1">🔴 Geser Pin Merah untuk Lokasi Tujuan</span>
    </div>
    <div id="leaflet-map-picker" wire:ignore class="h-80 w-full rounded-2xl border border-slate-200 shadow-sm z-10"></div>
</div>
