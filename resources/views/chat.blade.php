@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            Chat with the Bot
        </div>
        <div class="card-body">
            <div id="chat-box" style="height: 400px; overflow-y: auto; border: 1px solid #ccc; margin-bottom: 10px;"></div>
            <input type="text" id="chat-input" class="form-control" placeholder="Type a message...">
            <button class="btn btn-primary mt-2 w-100" onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>

<script>
    async function sendMessage() {
        const message = document.getElementById('chat-input').value;
        if (!message) return;

        const chatBox = document.getElementById('chat-box');
        chatBox.innerHTML += `<div class="text-end"><strong>You:</strong> ${message}</div>`;

        const response = await fetch('{{ route('chat.send') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ message }),
        });

        const data = await response.json();
        chatBox.innerHTML += `<div class="text-start"><strong>Bot:</strong> ${data.response}</div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
        document.getElementById('chat-input').value = '';
    }
</script>
@endsection

