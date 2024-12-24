<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Emergency Chat</title>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Initialize Pusher
        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });

        // Subscribe to the emergency-chat channel
        var channel = pusher.subscribe('emergency-chat');
        channel.bind('chat-message', function(data) {
            const chatBox = document.getElementById('chat-box');
            chatBox.innerHTML += `<div><strong>${data.user.name}</strong>: ${data.message}</div>`;
            chatBox.scrollTop = chatBox.scrollHeight;
        });

        async function sendMessage() {
            const message = document.getElementById('chat-input').value;
            if (!message) return;

            await fetch('{{ route('emergency.chat.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ message }),
            });

            document.getElementById('chat-input').value = '';
        }
    </script>
</head>
<body>
    <h1>Emergency Chat</h1>
    <div id="chat-box" style="height: 300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;"></div>
    <input type="text" id="chat-input" placeholder="Type a message..." />
    <button onclick="sendMessage()">Send</button>
</body>
</html>

