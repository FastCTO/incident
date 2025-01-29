@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <span>Emergency Chat</span>
            <div>
                <a href="{{ route('emergency.report') }}" class="btn btn-dark-red btn-sm">Report Emergency</a>
                <a href="{{ route('critical-care') }}" class="btn btn-warning btn-sm">Critical Care</a>
                <a href="{{ route('chat') }}" class="btn btn-success btn-sm">AI Assistant</a>
            </div>
        </div>

        <div class="card-body">
            <div id="chat-box" style="height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;"></div>
            <input type="text" id="chat-input" class="form-control" placeholder="Type a message..." onkeypress="handleKeyPress(event)">
            <button class="btn btn-danger mt-2 w-100" onclick="sendEmergencyMessage()">Send</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetchMessages();
        setInterval(fetchMessages, 2000);
    });

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            sendEmergencyMessage();
        }
    }

    async function sendEmergencyMessage() {
        const messageInput = document.getElementById('chat-input');
        const message = messageInput.value.trim();
        if (!message) return;

        const chatBox = document.getElementById('chat-box');
        chatBox.innerHTML += `<div class="text-end"><strong>You:</strong> ${message}</div>`;

        messageInput.value = ''; // Clear input field

        try {
            const response = await fetch('{{ route('emergency.chat.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ message }),
            });

            await response.json();
            fetchMessages(); // Ensure the new message stays in history
        } catch (error) {
            console.error('Error sending message:', error);
            chatBox.innerHTML += `<div class="text-start text-danger"><strong>Emergency Chat:</strong> Error processing request. Please try again.</div>`;
        }
    }

    async function fetchMessages() {
        try {
            const response = await fetch('{{ route('emergency.chat.fetch') }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            });

            const messages = await response.json();
            const chatBox = document.getElementById('chat-box');

            // Only append new messages instead of clearing the chat history
            chatBox.innerHTML = messages.map(msg => `<div><strong>${msg.user}:</strong> ${msg.message}</div>`).join("");

            chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to latest message
        } catch (error) {
            console.error('Error fetching messages:', error);
        }
    }
</script>

<style>
    .btn-dark-red {
        background-color: #8B0000 !important;
        color: white !important;
    }
</style>

@endsection

