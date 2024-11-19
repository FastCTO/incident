
document.addEventListener('DOMContentLoaded', function () {
    // Check if the map element exists
    var mapContainer = document.getElementById('map');
    if (!mapContainer) {
        console.error("Map container not found!");
        return;
    }

    // Initialize the map
    var map = L.map('map').setView([37.7749, -122.4194], 13); // Example coordinates

    // Add a tile layer from OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Example marker (San Francisco)
    L.marker([37.7749, -122.4194]).addTo(map).bindPopup("Test Room");

    // Load your custom image map (if you have one)
    L.imageOverlay('images/map.png', [[lat1, lng1], [lat2, lng2]]).addTo(map); // Replace lat/lng with your image bounds
});

