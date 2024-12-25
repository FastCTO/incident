// resources/js/emergency_chat.js

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Make Pusher globally available if needed
window.Pusher = Pusher;

// Setup Echo with your environment variables from .env (via Vite)
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,       // e.g. a0b08d09a23d3a1b28fc
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER, // e.g. us3
    forceTLS: true, // or false if you want ws
    // If you have a unique host/port, define them here. Otherwise it defaults to Pusher.com
    // wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    // wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    // wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    enabledTransports: ['ws', 'wss'],
});

// Subscribe to the public channel 'emergency-chat'
const channel = window.Echo.channel('emergency-chat');

// Listen for the 'chat-message' event
channel.subscribed(() => {
    console.log('Subscribed to channel: emergency-chat');
}).listen('.chat-message', (data) => {
    console.log('Received chat-message:', data);

    const chatBox = document.getElementById('chat-box');
    if (!chatBox) return;

    const username = data.username ?? 'User';
    const message = data.message ?? '';

    // Append message
    chatBox.innerHTML += `<div><strong>${username}:</strong> ${message}</div>`;
    chatBox.scrollTop = chatBox.scrollHeight;
});

// Optional: log an init message
console.log('emergency_chat.js loaded!');

