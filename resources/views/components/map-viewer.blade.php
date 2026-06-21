@props([
    'points' => [],
    'mapId' => null,
    'height' => 'h-96',
    'title' => null,
    'subtitle' => null,
])

@php
    $mapId = $mapId ?: 'map-viewer-'.uniqid();
    $normalizedPoints = collect($points)
        ->filter(fn ($point) => filled($point['lat'] ?? null) && filled($point['lng'] ?? null))
        ->map(fn ($point) => [
            'lat' => (float) $point['lat'],
            'lng' => (float) $point['lng'],
            'label' => $point['label'] ?? 'Titik perjalanan',
            'type' => $point['type'] ?? 'jemput',
            'address' => $point['address'] ?? '-',
            'description' => $point['description'] ?? null,
        ])
        ->values();
@endphp

@once
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endonce

<div {{ $attributes->merge(['class' => 'bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden']) }}>
    @if($title || $subtitle)
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                @if($title)
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-[0.18em]">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-blue-700 border border-blue-100">
                {{ $normalizedPoints->count() }} Titik
            </span>
        </div>
    @endif

    <div class="relative">
        <div id="{{ $mapId }}" class="{{ $height }} w-full z-10"></div>

        @if($normalizedPoints->isEmpty())
            <div class="absolute inset-0 z-[400] flex items-center justify-center bg-slate-50/95 text-center px-6">
                <div>
                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Belum ada koordinat</p>
                    <p class="text-[11px] font-medium text-slate-400 mt-1">Titik peta muncul setelah booking memiliki latitude dan longitude.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mapElement = document.getElementById(@json($mapId));
        const points = @json($normalizedPoints);

        if (!mapElement || typeof L === 'undefined') {
            return;
        }

        const defaultCenter = [-0.4669, 100.3986];
        const map = L.map(mapElement).setView(defaultCenter, 12);
        const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        }[char]));

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const bounds = [];

        points.forEach((point) => {
            const isDropoff = point.type === 'antar';
            const popupTitle = isDropoff ? 'Titik Antar' : 'Titik Jemput';
            const label = escapeHtml(point.label);
            const address = escapeHtml(point.address);
            const description = escapeHtml(point.description);

            L.marker([point.lat, point.lng])
                .addTo(map)
                .bindPopup(`
                    <div class="font-poppins min-w-[180px]">
                        <p class="text-[10px] font-black uppercase tracking-widest ${isDropoff ? 'text-emerald-600' : 'text-blue-600'}">${popupTitle}</p>
                        <p class="text-xs font-black text-slate-900 mt-1">${label}</p>
                        <p class="text-[10px] font-semibold text-slate-500 leading-tight mt-1">${address}</p>
                        ${description ? `<p class="text-[10px] font-medium text-slate-400 mt-2">${description}</p>` : ''}
                    </div>
                `);

            bounds.push([point.lat, point.lng]);
        });

        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [42, 42], maxZoom: 15 });
        }

        window.sjtMapViewers = window.sjtMapViewers || {};
        window.sjtMapViewers[@json($mapId)] = map;
        window.recenterMapViewer = function (id, lat, lng) {
            const targetMap = window.sjtMapViewers?.[id];
            if (targetMap && lat && lng) {
                targetMap.setView([lat, lng], 15);
            }
        };
    });
</script>
