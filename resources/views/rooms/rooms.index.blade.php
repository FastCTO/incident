@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Rooms Map</h1>
    <!-- Map Container -->
    <div id="map" style="height: 600px; width: 100%;"></div>
</div>

<!-- Include Leaflet.js CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // Initialize the map centered around a generic starting point
    var map = L.map('map').setView([51.505, -0.09], 17); // Adjust these coordinates for your specific location

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Room data from the backend
    var rooms = @json($rooms); // Ensure $rooms is passed from the controller

    // Add markers for each room
    rooms.forEach(function(room) {
        var marker = L.marker([room.lat, room.lng]).addTo(map) // Use room lat/lng
            .bindPopup("Room " + room.room_number)
            .on('click', function() {
                window.location.href = '/rooms/' + room.id; // Redirect to the room page
            });
    });
</script>
@endsection

