@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header Information -->
    <h1>New Hope Academy</h1>
    <p>1820 Downs Boulevard<br>Franklin, Tennessee 37064</p>
    <p>Phone: 615-595-0324</p>
    <p>Longitude: 35.918235935881874<br>Latitude: -86.89927768510286</p>

    <!-- Map Container -->
    <div id="map" style="height: 600px; width: 100%; margin-top: 20px;"></div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<!-- jQuery (Optional, for easier DOM manipulation) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Leaflet map at specified coordinates
        var map = L.map('map').setView([35.918235935881874, -86.89927768510286], 17);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Fetch rooms data passed from the controller
        var rooms = @json($rooms);

        // Add markers for each room
        rooms.forEach(function(room) {
            if (room.lat && room.lng) {
                L.marker([room.lat, room.lng]).addTo(map)
                    .bindPopup("Room " + room.room_number)
                    .on('click', function() {
                        window.location.href = '/rooms/' + room.id;
                    });
            }
        });

        // Handle map click to switch to image map
        map.on('click', function(e) {
            // Replace the map div with the SVG image map
            $('#map').html(`
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" 
                     xmlns:xlink="http://www.w3.org/1999/xlink" 
                     viewBox="0 0 1200 1200" style="width: 100%; height: 600px;">
                    <image width="1200" height="1200" xlink:href="{{ asset('images/map.png') }}"></image> 
                    <a xlink:href="/rooms/1">
                        <rect x="136" y="204" fill="#fff" opacity="0" width="100" height="100"></rect>
                    </a>
                    <a xlink:href="/rooms/2">
                        <rect x="312" y="456" fill="#fff" opacity="0" width="100" height="100"></rect>
                    </a>
                    <a xlink:href="/rooms/20">
                        <rect x="523" y="70" fill="#fff" opacity="0" width="100" height="100"></rect>
                    </a>
                    <a xlink:href="/rooms/20">
                        <rect x="267" y="203" fill="#fff" opacity="0" width="100" height="100"></rect>
                    </a>
                    <a xlink:href="/rooms/24">
                        <rect x="385" y="207" fill="#fff" opacity="0" width="100" height="100"></rect>
                    </a>
                    <a xlink:href="/rooms/21">
                        <rect x="643" y="203" fill="#fff" opacity="0" width="100" height="100"></rect>
                    </a>
                    <a xlink:href="/rooms/21">
                        <rect x="735" y="210" fill="#fff" opacity="0" width="100" height="100"></rect>
                    </a>
                    <a xlink:href="#">
                        <rect x="835" y="214" fill="#fff" opacity="0" width="100" height="100"></rect>
                    </a>
                    <a xlink:href="/rooms/30">
                        <rect x="976" y="202" fill="#fff" opacity="0" width="100" height="100"></rect>
                    </a>
                </svg>
            `);
        });
    });
</script>
@endsection

