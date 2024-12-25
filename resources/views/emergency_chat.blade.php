@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white">
            Emergency Chat
        </div>
        <div class="card-body">
            <div id="chat-box" style="height: 400px; overflow-y: auto; border: 1px solid #ccc; margin-bottom: 10px;"></div>
            <input type="text" id="chat-input" class="form-control" placeholder="Type a message...">
            <button class="btn btn-danger mt-2 w-100" onclick="sendEmergencyMessage()">Send</button>
        </div>
    </div>
</div>

<script>
    // Fetch new messages every 2 seconds
    setInterval(fetchMessages, 2000);

    async function sendEmergencyMessage() {
        const message = document.getElementById('chat-input').value;
        if (!message) return;

        const chatBox = document.getElementById('chat-box');
        chatBox.innerHTML += `<div class="text-end"><strong>You:</strong> ${message}</div>`;

        const response = await fetch('{{ route('emergency.chat.send') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ message }),
        });

        document.getElementById('chat-input').value = '';
        fetchMessages(); // Refresh the chat box
    }

    async function fetchMessages() {
        const response = await fetch('{{ route('emergency.chat.fetch') }}', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        });

        const messages = await response.json();
        const chatBox = document.getElementById('chat-box');
        chatBox.innerHTML = ''; // Clear current messages

        messages.forEach(msg => {
            chatBox.innerHTML += `<div><strong>${msg.user}:</strong> ${msg.message}</div>`;
        });

        chatBox.scrollTop = chatBox.scrollHeight;
    }
</script>
@endsection

