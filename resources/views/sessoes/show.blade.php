<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessão {{ $sessao->nome_sessao }}</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        #owlbear-frame {
            border: 0;
            width: 100%;
            height: 100vh;
        }
    </style>
</head>
<body>
    <iframe 
        id="owlbear-frame"
        src="/owlbear/index.html?laravelSession={{ $sessao->id }}&laravelUser={{ auth()->id() }}&laravelRole={{ auth()->user()->id === $sessao->mestre_id ? 'mestre' : 'jogador' }}&laravelName={{ urlencode(auth()->user()->username) }}"
        allow="clipboard-write; clipboard-read">
    </iframe>

    <script>
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
    </script>
</body>
</html>