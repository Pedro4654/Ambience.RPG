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

console.log('[bootstrap] Pusher key:', PUSHER_KEY, 'cluster:', PUSHER_CLUSTER, 'reverb host:', REVERB_HOST, REVERB_PORT);

// ==================== CONFIGURAÇÃO DO ECHO COM AUTENTICAÇÃO ====================
(window as any).Echo = new Echo({
  broadcaster: 'pusher',
  key: PUSHER_KEY,
  cluster: PUSHER_CLUSTER,
  forceTLS: forceTLS,
  wsHost: REVERB_HOST,
  wsPort: Number(REVERB_PORT),
  wssPort: Number(REVERB_PORT),
  enabledTransports: ['ws', 'wss'],
  disableStats: true,
  
  // ==================== AUTENTICAÇÃO (ESSENCIAL PARA CANAIS PRIVADOS) ====================
  auth: {
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
      'Accept': 'application/json',
    }
  },
  
  authEndpoint: '/broadcasting/auth',
  encrypted: forceTLS
});

console.log('[bootstrap] ✅ Echo configurado com autenticação');

// ==================== PRESENÇA NA SESSÃO (SÓ SE HOUVER SessionId) ====================
// Verificar se estamos em uma página de sessão
const sessionId = (window as any).SessionId;

if (sessionId) {
  console.log(`[bootstrap] Configurando presença para sessão: ${sessionId}`);
  
  // Inicia array global de participantes
  (window as any).ParticipantesSessao = (window as any).ParticipantesSessao || [];

  (window as any).Echo.join(`sessao.${sessionId}`)
    .here((users: Array<{ id: number }>) => {
      console.log('[Presença] Usuários online:', users);
      (window as any).ParticipantesSessao = users.map(u => u.id);
      atualizarIndicadores();
    })
    .joining((user: { id: number }) => {
      console.log('[Presença] Usuário entrou:', user);
      (window as any).ParticipantesSessao.push(user.id);
      atualizarIndicadores();
    })
    .leaving((user: { id: number }) => {
      console.log('[Presença] Usuário saiu:', user);
      (window as any).ParticipantesSessao =
        (window as any).ParticipantesSessao.filter((id: number) => id !== user.id);
      atualizarIndicadores();
    })
    .error((error: any) => {
      console.error('[Presença] Erro no canal de sessão:', error);
    });

  function atualizarIndicadores() {
    document.querySelectorAll<HTMLElement>('.participante-item').forEach(el => {
      const uid = parseInt(el.dataset.usuarioId || '0', 10);
      const indicator = el.querySelector<HTMLElement>('.online-indicator');
      if (!indicator) return;
      if ((window as any).ParticipantesSessao.includes(uid)) {
        indicator.classList.add('online');
        indicator.classList.remove('offline');
      } else {
        indicator.classList.add('offline');
        indicator.classList.remove('online');
      }
    });
  }
} else {
  console.log('[bootstrap] Nenhuma sessão ativa, presença não configurada');
}
