<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sessão {{ $sessao->nome_sessao }}</title>
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Chat Floating CSS --}}
    <link rel="stylesheet" href="{{ asset('css/chat-floating.css') }}">
    
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        #owlbear-frame {
            border: 0;
            width: 100%;
            height: 100vh;
        }
    </style>
</head>
<body>
    {{-- ==================== IFRAME DO JOGO ==================== --}}
    <iframe 
        id="owlbear-frame"
        src="/owlbear/index.html?laravelSession={{ $sessao->id }}&laravelUser={{ auth()->id() }}&laravelRole={{ auth()->user()->id === $sessao->mestre_id ? 'mestre' : 'jogador' }}&laravelName={{ urlencode(auth()->user()->username) }}"
        allow="clipboard-write; clipboard-read">
    </iframe>

    {{-- ==================== CHAT FLUTUANTE ==================== --}}
    @php
        // Buscar permissões do usuário
        $permissoes = \App\Models\PermissaoSala::where('sala_id', $sessao->sala_id)
            ->where('usuario_id', auth()->id())
            ->first();
    @endphp

    @include('components.chat-container', [
        'sala' => $sessao->sala,
        'minhas_permissoes' => $permissoes
    ])

    {{-- ==================== MODAL DE DENÚNCIA ==================== --}}
    @include('partials.chat-denuncia-modal', [
        'sala' => $sessao->sala
    ])

    {{-- ==================== SCRIPTS ==================== --}}
    
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Pusher --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    {{-- Laravel Echo --}}
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
    
    {{-- NSFW Detector --}}
    <script src="{{ asset('js/nsfw-detector.js') }}"></script>
    
    {{-- Moderação --}}
    <script src="{{ asset('js/moderation.js') }}"></script>
    
    {{-- Sistema de Chat --}}
    <script src="{{ asset('js/chat.js') }}"></script>

    {{-- ==================== CONFIGURAÇÃO ECHO ==================== --}}
    <script>
        // Configurar Echo
        window.Pusher = Pusher;
        
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env("PUSHER_APP_KEY") }}',
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            wsHost: '{{ env("REVERB_HOST", "localhost") }}',
            wsPort: {{ env("REVERB_PORT", 6001) }},
            wssPort: {{ env("REVERB_PORT", 6001) }},
            forceTLS: {{ env("REVERB_SCHEME", "http") === "https" ? "true" : "false" }},
            encrypted: {{ env("REVERB_SCHEME", "http") === "https" ? "true" : "false" }},
            disableStats: true,
            enabledTransports: ['ws', 'wss'],
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }
        });

        console.log('✅ Echo configurado para chat');

        // Configurar iframe do Owlbear
        const iframe = document.getElementById('owlbear-frame');
        
        iframe.addEventListener('load', function() {
            try {
                iframe.contentWindow.OWLBEAR_CONFIG = {
                    apiBase: "{{ url('/api') }}",
                    sessionId: "{{ $sessao->id }}",
                    userId: "{{ auth()->id() }}",
                    userName: "{{ auth()->user()->username }}",
                    role: "{{ auth()->user()->id === $sessao->mestre_id ? 'mestre' : 'jogador' }}"
                };
                console.log('✅ OWLBEAR_CONFIG injetado:', iframe.contentWindow.OWLBEAR_CONFIG);
            } catch (e) {
                console.warn('⚠️ Não foi possível injetar config (CORS):', e);
            }
        });

        // Prevenir fechamento acidental
        window.addEventListener('beforeunload', function(e) {
            if (window.chatSystem) {
                window.chatSystem.clearTypingState();
            }
        });
    </script>
</body>
</html>