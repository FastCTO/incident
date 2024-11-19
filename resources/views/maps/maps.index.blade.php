@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Maps Overview</h1>
    <p>Total Occupancy: {{ $totalOccupancy }}</p>
    <p>Rooms Occupied: {{ $rooms->count() }}</p>

    <!-- Map Container -->
    <div id="map" style="height: 600px; width: 100%;"></div>
</div>

<!-- Include Leaflet.js CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // Initialize the map
    var map = L.map('map').setView([51.505, -0.09], 17); // Adjust the coordinates as necessary

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Room data from the backend
    var rooms = @json($rooms);

    // Add markers for each room
    rooms.forEach(function(room) {
        var marker = L.marker([room.lat, room.lng]).addTo(map)
            .bindPopup("Room " + room.room_number)
            .on('click', function() {
                window.location.href = '/rooms/' + room.id;
            });
    });
</script>
@endsection

