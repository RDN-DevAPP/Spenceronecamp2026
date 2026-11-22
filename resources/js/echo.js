import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const envHost = import.meta.env.VITE_REVERB_HOST;
const resolvedHost =
    envHost && envHost !== '127.0.0.1' && envHost !== 'localhost'
        ? envHost
        : window.location.hostname;

const echoInstance = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY || 'spencerone_key',
    wsHost: resolvedHost,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8081,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8081,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
});

export default echoInstance;
