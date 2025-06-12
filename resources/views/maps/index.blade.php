@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- 📍 Location Card --}}
    <div class="card shadow-sm mx-auto" style="max-width: 960px;">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                
                {{-- Left Section: School Info --}}
                <div class="mb-3 mb-md-0">
                    <h2 class="mb-2">New Hope Academy</h2>
                    <p class="mb-1">📍 1820 Downs Blvd, Franklin, TN 37064</p>
                    <p class="mb-1">📞 <a href="tel:6155950324">615-595-0324</a></p>
                    <p class="mb-0">🌐 Lat: <strong>35.9182</strong> | Lng: <strong>-86.8992</strong></p>
                </div>

                {{-- Right Section: Button --}}
                <div class="text-md-end text-center">
                    <a href="{{ route('maps.indoor') }}" class="btn btn-primary btn-lg">
                        🗺️ View Indoor Map
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 🗺️ Outdoor Map --}}
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <strong>🗺️ Outdoor Map - Click markers for room or camera</strong>
        </div>
        <div class="card-body p-0">
            <div id="map" style="height: 600px; width: 100%;"></div>
        </div>
    </div>

</div>

{{-- Leaflet Scripts --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('map').setView([35.9182359, -86.8992776], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const rooms = @json($rooms);
        rooms.forEach(room => {
            if (room.lat && room.lng) {
                L.marker([room.lat, room.lng])
                    .addTo(map)
                    .bindPopup(`Room ${room.room_number}`)
                    .on('click', () => window.location.href = '/rooms/' + room.id);
            }
        });

        const cameraIcon = L.icon({
            iconUrl: "{{ asset('images/outside-click.png') }}",
            iconSize: [126, 29],
            iconAnchor: [63, 15],
            popupAnchor: [0, -10]
        });

        L.marker([35.9184, -86.8992], { icon: cameraIcon })
            .addTo(map)
            .bindPopup('<b>Live Camera</b><br><a href="/live-stream">View Live Feed</a>')
            .on('click', () => window.location.href = '/live-stream');
    });
</script>
@endsection

