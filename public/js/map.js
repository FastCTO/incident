function showRoomDetails(roomNumber) {
    fetch(`/api/rooms/${roomNumber}`)
        .then(response => response.json())
        .then(data => {
            alert(`Room: ${data.room_number}\nOccupancy: ${data.occupancy}`);
        })
        .catch(error => console.error('Error fetching room data:', error));
}

setInterval(() => {
    fetch('/api/rooms')
        .then(response => response.json())
        .then(rooms => {
            rooms.forEach(room => {
                const roomElement = document.getElementById(`room${room.room_number}`);
                roomElement.setAttribute('fill', room.occupancy > 0 ? 'red' : 'green');
            });
        });
}, 5000);

