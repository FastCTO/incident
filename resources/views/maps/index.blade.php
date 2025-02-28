@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header Information -->
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>New Hope Academy</h1>
            <p>1820 Downs Boulevard<br>Franklin, Tennessee 37064</p>
            <p>Phone: 615-595-0324</p>
            <p>Longitude: 35.918235935881874<br>Latitude: -86.89927768510286</p>
        </div>
        <div>
            <a href="{{ route('maps.indoor') }}" class="btn btn-primary">View Indoor Map</a>
        </div>
    </div>

    <!-- Outdoor Map Container -->
    <div id="map" style="height: 600px; width: 100%; margin-top: 20px;"></div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('map').setView([35.918235935881874, -86.89927768510286], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var rooms = @json($rooms);
        rooms.forEach(function(room) {
            if (room.lat && room.lng) {
                L.marker([room.lat, room.lng]).addTo(map)
                    .bindPopup("Room " + room.room_number)
                    .on('click', function() {
                        window.location.href = '/rooms/' + room.id;
                    });
            }
        });

        // Add camera marker
        var cameraIcon = L.icon({
            iconUrl: "{{ asset('images/outside-click.png') }}",
            iconSize: [40, 40],
            iconAnchor: [20, 20],
            popupAnchor: [0, -20]
        });

        L.marker([35.9184, -86.8992], { icon: cameraIcon }) // Adjust coords if needed
            .addTo(map)
            .bindPopup('<b>Live Camera</b><br><a href="/live-stream">View Live Feed</a>')
            .on('click', function() {
                window.location.href = '/live-stream';
            });
    });
</script>
@endsection

