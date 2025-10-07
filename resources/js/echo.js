// resources/js/echo.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Debug connection status
window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('✅ WebSocket conectado com sucesso!');
});

window.Echo.connector.pusher.connection.bind('disconnected', function() {
    console.log('❌ WebSocket desconectado');
});

window.Echo.connector.pusher.connection.bind('error', function(error) {
    console.error('❌ Erro no WebSocket:', error);
});
