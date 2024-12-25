@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Emergency Chat</h1>
    <div class="row">
        <div class="col-md-8 offset-md-2">

            <div class="card">
                <div class="card-header">Live Emergency Chat</div>
                <div class="card-body" style="height: 400px; overflow-y: auto;" id="chat-box">
                    <!-- Incoming messages appear here -->
                </div>
            </div>

            <div class="input-group mt-3">
                <input type="text" id="chat-input" class="form-control" placeholder="Type a message..."/>
                <button class="btn btn-primary" onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- This loads the real-time logic from emergency_chat.js -->
    @vite('resources/js/emergency_chat.js')

    <script>
        async function sendMessage() {
            const chatInput = document.getElementById('chat-input');
            const message = chatInput.value.trim();
            if (!message) return;

            // POST to /emergency-chat/messages
            const response = await fetch('{{ route('emergency.chat.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ message }),
            });

            if (!response.ok) {
                alert('Error sending message. Check logs.');
            }

            chatInput.value = '';
        }
    </script>
@endpush

