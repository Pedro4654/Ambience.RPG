// resources/js/bootstrap.ts
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// expõe Pusher no window (laravel-echo espera window.Pusher)
(window as any).Pusher = Pusher;

// URL/config - pega de import.meta.env (Vite) ou usa defaults
const PUSHER_KEY = import.meta.env.VITE_PUSHER_KEY ?? '';
const PUSHER_CLUSTER = import.meta.env.VITE_PUSHER_CLUSTER ?? '';
const REVERB_HOST = import.meta.env.VITE_REVERB_HOST ?? window.location.hostname;
const REVERB_PORT = import.meta.env.VITE_REVERB_PORT ?? '6001';
const REVERB_SCHEME = import.meta.env.VITE_REVERB_SCHEME ?? (location.protocol.replace(':','') || 'http');

const forceTLS = REVERB_SCHEME === 'https';

// Se você estiver usando um servidor Reverb que emula o protocolo Pusher,
// em vez de conectar no pusher.com, configuramos wsHost/wsPort para o seu Reverb.
console.log('[bootstrap] Pusher key:', PUSHER_KEY, 'cluster:', PUSHER_CLUSTER, 'reverb host:', REVERB_HOST, REVERB_PORT);

(window as any).Echo = new Echo({
  broadcaster: 'pusher',
  key: PUSHER_KEY,              // Vite env: VITE_PUSHER_KEY
  cluster: PUSHER_CLUSTER,      // Vite env: VITE_PUSHER_CLUSTER (se aplica)
  forceTLS: forceTLS,
  // Se você está ligando a um servidor local (Reverb) que emula pusher,
  // descomente e configure wsHost/wsPort/wssPort conforme necessário:
  wsHost: REVERB_HOST,
  wsPort: Number(REVERB_PORT),
  wssPort: Number(REVERB_PORT),
  enabledTransports: ['ws', 'wss'], // evita polling
});
