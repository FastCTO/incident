@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Chatbot Page</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Chatbot</div>
                <div class="card-body">
                    <div id="chat-box" style="border: 1px solid #ccc; padding: 10px; height: 400px; overflow-y: auto;"></div>
                    <div class="mt-3">
                        <input type="text" id="message" class="form-control" placeholder="Type a message...">
                        <button class="btn btn-primary mt-2" onclick="sendMessage()">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function sendMessage() {
        const message = document.getElementById('message').value;
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
        document.getElementById('message').value = '';
    }
</script>
@endpush
@endsection

