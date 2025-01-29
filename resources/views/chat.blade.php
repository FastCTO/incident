@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span>Emergency Assistant - AI Safety Help</span>
            <div>
                <a href="{{ route('emergency.report') }}" class="btn btn-danger btn-sm">Report Emergency</a>
                <a href="{{ route('critical-care') }}" class="btn btn-warning btn-sm">Critical Care</a>
                <a href="{{ route('emergency.chat') }}" class="btn btn-info btn-sm">Emergency Chat</a>
            </div>
        </div>

        <div class="card-body">
            <div id="chat-box" style="height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;"></div>
            <input type="text" id="chat-input" class="form-control" placeholder="Type a message..." onkeypress="handleKeyPress(event)">
            <button class="btn btn-primary mt-2 w-100" onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>

<script>
    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent form submission if applicable
            sendMessage();
        }
    }

    async function sendMessage() {
        const messageInput = document.getElementById('chat-input');
        const message = messageInput.value.trim();
        if (!message) return;

        const chatBox = document.getElementById('chat-box');
        chatBox.innerHTML += `<div class="text-end"><strong>You:</strong> ${message}</div>`;

        messageInput.value = ''; // Clear input field

        try {
            const response = await fetch('{{ route('chat.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ message }),
            });

            const data = await response.json();
            let botResponse = `<div class="text-start"><strong>AI Safety Help:</strong> ${data.response}</div>`;

            chatBox.innerHTML += botResponse;
            chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to latest message
        } catch (error) {
            console.error('Error sending message:', error);
            chatBox.innerHTML += `<div class="text-start text-danger"><strong>AI Safety Help:</strong> Error processing request. Please try again.</div>`;
        }
    }
</script>
@endsection

