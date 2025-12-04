{{-- resources/views/comunidade/edit.blade.php --}}
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar Postagem - Ambience RPG</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ============================================
           VARIÁVEIS - Mesmas do Create
           ============================================ */
        :root {
            --bg-dark: #0a0f14;
            --bg-secondary: #151b23;
            --card-bg: rgba(26, 35, 50, 0.9);
            --card-light: rgba(31, 42, 51, 0.7);
            --border-color: rgba(34,197,94,0.2);
            --accent: #22c55e;
            --accent-light: #16a34a;
            --text-primary: #e6eef6;
            --text-secondary: #8b9ba8;
            --text-muted: #64748b;
        }
/* ============================================
   SCROLLBAR PERSONALIZADA - IDÊNTICA AO SHOW.BLADE.PHP
   ============================================ */

::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: var(--bg-dark);
}

::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.3);
    border-radius: 5px;
    transition: background 0.3s ease;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(34, 197, 94, 0.5);
}

/* Scrollbar para modais */
.modal-body::-webkit-scrollbar,
.rpg-modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track,
.rpg-modal-body::-webkit-scrollbar-track {
    background: rgba(17, 24, 39, 0.6);
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb,
.rpg-modal-body::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.3);
    border-radius: 4px;
    transition: background 0.3s ease;
}

.modal-body::-webkit-scrollbar-thumb:hover,
.rpg-modal-body::-webkit-scrollbar-thumb:hover {
    background: rgba(34, 197, 94, 0.5);
}

/* Scrollbar para listas */
.notification-list::-webkit-scrollbar {
    width: 6px;
}

.notification-list::-webkit-scrollbar-track {
    background: rgba(17, 24, 39, 0.4);
    border-radius: 3px;
}

.notification-list::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.3);
    border-radius: 3px;
    transition: background 0.3s ease;
}

.notification-list::-webkit-scrollbar-thumb:hover {
    background: rgba(34, 197, 94, 0.5);
}
        /* ============================================
           BACKGROUND
           ============================================ */
        body {
            font-family: 'Inter', sans-serif;
            background: #0a0f14;
            color: var(--text-primary);
            line-height: 1.5;
            margin: 0;
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset("images/rpg-background.gif") }}') center/cover;
            filter: blur(4px) brightness(0.5);
            z-index: -1;
        }

        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(10, 15, 20, 0.7);
            z-index: -1;
        }

        /* ============================================
           HEADER
           ============================================ */
        .header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(10, 15, 20, 0.75);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(34,197,94,0.12);
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
        }

        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 0;
            height: 70px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 19px;
            color: #fff;
            text-decoration: none;
        }

        .logo-img {
            height: 50px;
            width: auto;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .nav-links a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: color .2s;
        }

        .nav-links a:hover {
            color: var(--accent);
        }

        .btn {
            padding: 11px 22px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: all .25s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn.primary {
            background: linear-gradient(to right, #22c55e, #16a34a);
            color: #052e16;
            box-shadow: 0 4px 14px rgba(34,197,94,0.3);
        }

        .btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34,197,94,0.4);
        }

        /* ============================================
           CONTAINER PRINCIPAL
           ============================================ */
        .edit-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        /* ============================================
           HEADER DA PÁGINA
           ============================================ */
        .edit-header {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeInDown 0.6s ease;
        }

        .edit-header h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 42px;
            font-weight: 900;
            color: #fff;
            margin-bottom: 12px;
            letter-spacing: 1px;
            text-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
        }

        .edit-header p {
            color: var(--text-secondary);
            font-size: 16px;
        }

        /* ============================================
           FORM CARD
           ============================================ */
        .form-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            animation: fadeInUp 0.6s ease;
        }

        .form-section {
            padding: 32px;
            border-bottom: 1px solid var(--border-color);
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .form-section-title {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ============================================
           TIPO DE CONTEÚDO - CARDS
           ============================================ */
        .tipo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
            margin-bottom: 10px;
        }

        .tipo-card {
            position: relative;
            cursor: pointer;
            transition: all 0.3s;
        }

        .tipo-card input[type="radio"] {
            display: none;
        }

        .tipo-card-inner {
            background: rgba(10, 15, 20, 0.6);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
        }

        .tipo-card:hover .tipo-card-inner {
            transform: translateY(-4px);
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
        }

        .tipo-card input[type="radio"]:checked + .tipo-card-inner {
            background: linear-gradient(135deg, rgba(6, 78, 59, 0.4), rgba(5, 46, 22, 0.3));
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
        }

        .tipo-icon {
            font-size: 36px;
            margin-bottom: 8px;
            filter: grayscale(1);
            transition: filter 0.3s;
        }

        .tipo-card input[type="radio"]:checked + .tipo-card-inner .tipo-icon {
            filter: grayscale(0);
        }

        .tipo-label {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-muted);
            transition: color 0.3s;
        }

        .tipo-card input[type="radio"]:checked + .tipo-card-inner .tipo-label {
            color: var(--accent);
        }

        /* ============================================
           CAMPOS DE FORMULÁRIO
           ============================================ */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }

        .form-input,
        .form-textarea {
            width: 98%;
            padding: 14px 18px;
            background: rgba(10, 15, 20, 0.8);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            color: #fff;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--accent);
            background: rgba(10, 15, 20, 0.95);
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: var(--text-muted);
        }

        .form-textarea {
            resize: vertical;
            min-height: 200px;
        }

        .char-counter {
            text-align: right;
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 6px;
        }

        .error-message {
            display: none;
            color: #ef4444;
            font-size: 13px;
            margin-top: 8px;
        }

        .form-input.error,
        .form-textarea.error {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.05);
        }

        /* ============================================
           INFORMAÇÕES DA POSTAGEM ATUAL
           ============================================ */
        .post-info {
            background: rgba(5, 46, 22, 0.1);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .post-info-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .post-info-content {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.5;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        .info-item {
            background: rgba(10, 15, 20, 0.3);
            border-radius: 8px;
            padding: 12px;
        }

        .info-label {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            color: #fff;
            font-weight: 600;
        }

        /* ============================================
           FOOTER - BOTÕES
           ============================================ */
        .form-footer {
            padding: 24px 32px;
            background: rgba(5, 46, 22, 0.1);
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 12px;
        }

        .form-footer .btn {
            flex: 1;
            padding: 14px 24px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
            color: #052e16;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid var(--border-color);
            color: var(--text-secondary);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent);
            color: #fff;
        }

        /* ============================================
           ANIMAÇÕES
           ============================================ */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ============================================
           FOOTER DA PÁGINA
           ============================================ */
        .footer {
            border-top: 1px solid rgba(34,197,94,0.1);
            padding: 48px 0 24px;
            background: #0d1419;
            margin-top: 80px;
        }

        .footer-bottom {
            text-align: center;
            color: var(--text-muted);
            font-size: 13px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px 32px;
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .edit-header h1 {
                font-size: 32px;
            }
            
            .tipo-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-footer {
                flex-direction: column;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* ============================================
           SVG ICONS
           ============================================ */
        .svg-icon {
            width: 1.2em;
            height: 1.2em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
    </style>
</head>
<body>

<header class="header">
    <div class="container">
        <nav class="nav">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Ambience RPG" class="logo-img">
            </a>
            <div class="nav-links">
                <a href="{{ route('salas.index') }}">Salas</a>
                <a href="{{ route('comunidade.feed') }}">Comunidade</a>
                <a href="{{ route('suporte.index') }}">Suporte</a>
            </div>
            <button class="btn primary" onclick="window.location.href='{{ route('comunidade.post.show', $post->slug) }}'">
                <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-27.2 65.6 0 89.6l309.6 280z"/>
                </svg>
                Voltar para Post
            </button>
        </nav>
    </div>
</header>

<div class="edit-container">
    {{-- ========== HEADER ========== --}}
    <div class="edit-header">
        <h1>
            <svg class="svg-icon" viewBox="0 0 1024 1024">
                <path d="M878.08 688c26.88-46.72 41.92-100.48 41.92-160 0-185.6-150.4-336-336-336s-336 150.4-336 336 150.4 336 336 336c59.52 0 113.28-15.04 160-41.92l158.08 158.08c12.48 12.48 32.64 12.48 45.12 0s12.48-32.64 0-45.12L878.08 688z m-158.08 0c-123.52 0-224-100.48-224-224s100.48-224 224-224 224 100.48 224 224-100.48 224-224 224z"/>
                <path d="M224 512c0-17.6 14.4-32 32-32s32 14.4 32 32-14.4 32-32 32-32-14.4-32-32zM416 512c0-17.6 14.4-32 32-32s32 14.4 32 32-14.4 32-32 32-32-14.4-32-32zM608 512c0-17.6 14.4-32 32-32s32 14.4 32 32-14.4 32-32 32-32-14.4-32-32z"/>
            </svg>
            EDITAR POSTAGEM
        </h1>
        <p>Ajuste as informações básicas da sua postagem</p>
    </div>

    {{-- ========== INFORMAÇÕES ATUAIS ========== --}}
    <div class="form-card" style="margin-bottom: 24px;">
        <div class="form-section">
            <div class="form-section-title">
                <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                    <path d="M320 320h384v64H320zM320 448h384v64H320zM320 576h256v64H320z"/>
                </svg>
                Informações Atuais da Postagem
            </div>
            
            <div class="post-info">
                <div class="post-info-title">Tipo de Conteúdo Atual</div>
                <div class="post-info-content">
                    @if($post->tipo_conteudo == 'texto')
                        <svg class="svg-icon" viewBox="0 0 1024 1024">
                            <path d="M896 192H128c-17.6 0-32 14.4-32 32v576c0 17.6 14.4 32 32 32h768c17.6 0 32-14.4 32-32V224c0-17.6-14.4-32-32-32z m-32 576H160V256h704v512z"/>
                            <path d="M256 320h512v64H256zM256 448h384v64H256zM256 576h256v64H256z"/>
                        </svg>
                        Texto
                    @elseif($post->tipo_conteudo == 'imagem')
                        <svg class="svg-icon" viewBox="0 0 1024 1024">
                            <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                            <path d="M320 448c-52.8 0-96 43.2-96 96s43.2 96 96 96 96-43.2 96-96-43.2-96-96-96z m320 192l-160-160-160 160h320z"/>
                        </svg>
                        Imagem
                    @elseif($post->tipo_conteudo == 'video')
                        <svg class="svg-icon" viewBox="0 0 1024 1024">
                            <path d="M896 192H128c-70.4 0-128 57.6-128 128v384c0 70.4 57.6 128 128 128h768c70.4 0 128-57.6 128-128V320c0-70.4-57.6-128-128-128z m-64 512H192V320h640v384z"/>
                            <path d="M416 448l192 128-192 128z"/>
                        </svg>
                        Vídeo
                    @elseif($post->tipo_conteudo == 'ficha_rpg')
                        <svg class="svg-icon" viewBox="0 0 1024 1024">
                            <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                            <path d="M320 320h64v64h-64zM448 320h192v64H448zM320 448h64v64h-64zM448 448h192v64H448zM320 576h64v64h-64zM448 576h192v64H448z"/>
                        </svg>
                        Ficha RPG
                    @else
                        <svg class="svg-icon" viewBox="0 0 1024 1024">
                            <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                            <path d="M384 384h256v256H384z"/>
                        </svg>
                        Outros
                    @endif
                </div>
                
                @if($post->arquivos->count() > 0)
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Arquivos</div>
                            <div class="info-value">{{ $post->arquivos->count() }} arquivo(s)</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Data de Criação</div>
                            <div class="info-value">{{ $post->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Visualizações</div>
                            <div class="info-value">{{ $post->visualizacoes }}</div>
                        </div>
                    </div>
                @endif
                
                @if($post->tipo_conteudo == 'ficha_rpg' && $post->fichaRpg)
                    <div style="margin-top: 16px;">
                        <div class="post-info-title">Ficha RPG</div>
                        <div class="post-info-content">
                            <strong>{{ $post->fichaRpg->nome }}</strong> - Nível {{ $post->fichaRpg->nivel }} {{ $post->fichaRpg->raca }} {{ $post->fichaRpg->classe }}
                        </div>
                    </div>
                @endif
            </div>
            
            <div style="color: var(--text-muted); font-size: 14px; text-align: center; padding: 12px; border-top: 1px solid var(--border-color); margin-top: 16px;">
                <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                    <path d="M512 320c-17.6 0-32 14.4-32 32v192c0 17.6 14.4 32 32 32s32-14.4 32-32V352c0-17.6-14.4-32-32-32zM512 576c-17.6 0-32 14.4-32 32s14.4 32 32 32 32-14.4 32-32-14.4-32-32-32z"/>
                </svg>
                Apenas título, descrição e tipo de conteúdo podem ser editados. Arquivos e fichas RPG não podem ser modificados.
            </div>
        </div>
    </div>

    {{-- ========== FORMULÁRIO DE EDIÇÃO ========== --}}
    <form action="{{ route('comunidade.post.update', $post->id) }}" 
          method="POST" 
          id="form-editar-post"
          class="form-card">
        @csrf
        @method('PUT')

        {{-- ========== SEÇÃO 1: TIPO DE CONTEÚDO ========== --}}
        <div class="form-section">
            <div class="form-section-title">
                <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M512 960c-246.4 0-448-201.6-448-448s201.6-448 448-448 448 201.6 448 448-201.6 448-448 448z m0-832c-211.2 0-384 172.8-384 384s172.8 384 384 384 384-172.8 384-384-172.8-384-384-384z"/>
                    <path d="M512 512m-192 0a192 192 0 1 0 384 0 192 192 0 1 0-384 0Z"/>
                </svg>
                Tipo de Conteúdo
            </div>
            
            <div class="tipo-grid">
                {{-- TEXTO --}}
                <label class="tipo-card">
                    <input type="radio" name="tipo_conteudo" value="texto" 
                           {{ old('tipo_conteudo', $post->tipo_conteudo) == 'texto' ? 'checked' : '' }} required>
                    <div class="tipo-card-inner">
                        <div class="tipo-icon">
                            <svg class="svg-icon" viewBox="0 0 1024 1024">
                                <path d="M896 192H128c-17.6 0-32 14.4-32 32v576c0 17.6 14.4 32 32 32h768c17.6 0 32-14.4 32-32V224c0-17.6-14.4-32-32-32z m-32 576H160V256h704v512z"/>
                                <path d="M256 320h512v64H256zM256 448h384v64H256zM256 576h256v64H256z"/>
                            </svg>
                        </div>
                        <div class="tipo-label">Texto</div>
                    </div>
                </label>

                {{-- IMAGEM --}}
                <label class="tipo-card">
                    <input type="radio" name="tipo_conteudo" value="imagem"
                           {{ old('tipo_conteudo', $post->tipo_conteudo) == 'imagem' ? 'checked' : '' }}>
                    <div class="tipo-card-inner">
                        <div class="tipo-icon">
                            <svg class="svg-icon" viewBox="0 0 1024 1024">
                                <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                                <path d="M320 448c-52.8 0-96 43.2-96 96s43.2 96 96 96 96-43.2 96-96-43.2-96-96-96z m320 192l-160-160-160 160h320z"/>
                            </svg>
                        </div>
                        <div class="tipo-label">Imagem</div>
                    </div>
                </label>

                {{-- VÍDEO --}}
                <label class="tipo-card">
                    <input type="radio" name="tipo_conteudo" value="video"
                           {{ old('tipo_conteudo', $post->tipo_conteudo) == 'video' ? 'checked' : '' }}>
                    <div class="tipo-card-inner">
                        <div class="tipo-icon">
                            <svg class="svg-icon" viewBox="0 0 1024 1024">
                                <path d="M896 192H128c-70.4 0-128 57.6-128 128v384c0 70.4 57.6 128 128 128h768c70.4 0 128-57.6 128-128V320c0-70.4-57.6-128-128-128z m-64 512H192V320h640v384z"/>
                                <path d="M416 448l192 128-192 128z"/>
                            </svg>
                        </div>
                        <div class="tipo-label">Vídeo</div>
                    </div>
                </label>

                {{-- FICHA RPG --}}
                <label class="tipo-card">
                    <input type="radio" name="tipo_conteudo" value="ficha_rpg"
                           {{ old('tipo_conteudo', $post->tipo_conteudo) == 'ficha_rpg' ? 'checked' : '' }}>
                    <div class="tipo-card-inner">
                        <div class="tipo-icon">
                            <svg class="svg-icon" viewBox="0 0 1024 1024">
                                <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                                <path d="M320 320h64v64h-64zM448 320h192v64H448zM320 448h64v64h-64zM448 448h192v64H448zM320 576h64v64h-64zM448 576h192v64H448z"/>
                            </svg>
                        </div>
                        <div class="tipo-label">Ficha RPG</div>
                    </div>
                </label>

                {{-- OUTROS --}}
                <label class="tipo-card">
                    <input type="radio" name="tipo_conteudo" value="outros"
                           {{ old('tipo_conteudo', $post->tipo_conteudo) == 'outros' ? 'checked' : '' }}>
                    <div class="tipo-card-inner">
                        <div class="tipo-icon">
                            <svg class="svg-icon" viewBox="0 0 1024 1024">
                                <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                                <path d="M384 384h256v256H384z"/>
                            </svg>
                        </div>
                        <div class="tipo-label">Outros</div>
                    </div>
                </label>
            </div>

            @error('tipo_conteudo')
                <p class="error-message" style="display:block; margin-top: 12px;">{{ $message }}</p>
            @enderror
            
            <div style="color: var(--text-muted); font-size: 13px; margin-top: 12px;">
                <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                    <path d="M512 320c-17.6 0-32 14.4-32 32v192c0 17.6 14.4 32 32 32s32-14.4 32-32V352c0-17.6-14.4-32-32-32zM512 576c-17.6 0-32 14.4-32 32s14.4 32 32 32 32-14.4 32-32-14.4-32-32-32z"/>
                </svg>
                Alterar o tipo de conteúdo não afetará os arquivos ou ficha RPG existentes.
            </div>
        </div>

        {{-- ========== SEÇÃO 2: TÍTULO E DESCRIÇÃO ========== --}}
        <div class="form-section">
            <div class="form-section-title">
                <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                    <path d="M320 320h384v64H320zM320 448h256v64H320z"/>
                </svg>
                Conteúdo Editável
            </div>

            {{-- Título --}}
            <div class="form-group">
                <label for="titulo" class="form-label">Título *</label>
                <input 
                    type="text" 
                    id="titulo" 
                    name="titulo" 
                    required
                    maxlength="200"
                    placeholder="Digite o novo título..."
                    value="{{ old('titulo', $post->titulo) }}"
                    class="form-input @error('titulo') error @enderror"
                >
                <small id="titulo-warning" class="error-message">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                        <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                        <path d="M512 320c-17.6 0-32 14.4-32 32v192c0 17.6 14.4 32 32 32s32-14.4 32-32V352c0-17.6-14.4-32-32-32zM512 576c-17.6 0-32 14.4-32 32s14.4 32 32 32 32-14.4 32-32-14.4-32-32-32z"/>
                    </svg>
                    Conteúdo inapropriado detectado
                </small>
                @error('titulo')
                    <p class="error-message" style="display:block">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descrição --}}
            <div class="form-group">
                <label for="conteudo" class="form-label">Descrição *</label>
                <textarea 
                    id="conteudo" 
                    name="conteudo" 
                    required
                    maxlength="5000"
                    placeholder="Digite a nova descrição..."
                    class="form-textarea @error('conteudo') error @enderror"
                >{{ old('conteudo', $post->conteudo) }}</textarea>
                <div class="char-counter">
                    <span id="contador-chars">{{ strlen($post->conteudo) }}</span>/5000 caracteres
                </div>
                <small id="conteudo-warning" class="error-message">
                    <svg class="svg-icon" viewBox="0 0 1024 1024">
                        <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                        <path d="M512 320c-17.6 0-32 14.4-32 32v192c0 17.6 14.4 32 32 32s32-14.4 32-32V352c0-17.6-14.4-32-32-32zM512 576c-17.6 0-32 14.4-32 32s14.4 32 32 32 32-14.4 32-32-14.4-32-32-32z"/>
                    </svg>
                    Conteúdo inapropriado detectado
                </small>
                @error('conteudo')
                    <p class="error-message" style="display:block">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- ========== FOOTER COM BOTÕES ========== --}}
        <div class="form-footer">
            <button type="submit" id="submit-btn" class="btn btn-primary">
                <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
                    <path d="M384 448l128 128 224-224 64 64-288 288-192-192z"/>
                </svg>
                Salvar Alterações
            </button>
            <a href="{{ route('comunidade.post.show', $post->slug) }}" class="btn btn-secondary">
                <svg class="svg-icon" viewBox="0 0 1024 1024">
                    <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                    <path d="M672 352l-160 160-160-160-64 64 224 224 224-224z"/>
                </svg>
                Cancelar
            </a>
        </div>
    </form>
</div>

<footer class="footer">
    <div class="footer-bottom">© 2025 Ambience RPG. Todos os direitos reservados.</div>
</footer>

{{-- ========== SCRIPTS ========== --}}
<script src="{{ asset('js/moderation.js') }}" defer></script>

<script>
/* ============================================
   CONTADOR DE CARACTERES
   ============================================ */
const conteudoTextarea = document.getElementById('conteudo');
const contadorChars = document.getElementById('contador-chars');

if (conteudoTextarea && contadorChars) {
    conteudoTextarea.addEventListener('input', function() {
        contadorChars.textContent = this.value.length;
        
        if (this.value.length > 4500) {
            contadorChars.style.color = '#ef4444';
            contadorChars.style.fontWeight = '700';
        } else {
            contadorChars.style.color = 'var(--text-muted)';
            contadorChars.style.fontWeight = '400';
        }
    });
}

/* ============================================
   VALIDAÇÃO DO FORMULÁRIO
   ============================================ */
const form = document.getElementById('form-editar-post');
if (form) {
    form.addEventListener('submit', function(e) {
        let hasErrors = false;
        
        // Verificar todos os campos com classe 'error'
        const camposComErro = document.querySelectorAll('.form-input.error, .form-textarea.error');
        
        // Verificar campos de texto inapropriados
        if (camposComErro.length > 0) {
            hasErrors = true;
            const campos = Array.from(camposComErro).map(campo => {
                const label = campo.previousElementSibling?.textContent || campo.id;
                return `• ${label}`;
            }).join('\n');
            
            alert(`⚠️ Conteúdo inapropriado detectado nos seguintes campos:\n\n${campos}\n\nCorrija antes de continuar.`);
        }
        
        // Se houver erros, prevenir envio
        if (hasErrors) {
            e.preventDefault();
            return false;
        }
        
        // Tudo ok, pode enviar
        return true;
    });
}

/* ============================================
   MODERAÇÃO LOCAL
   ============================================ */
window.addEventListener('DOMContentLoaded', async () => {
    // Inicializar sistema de moderação de texto
    const state = await window.Moderation.init({
        csrfToken: '{{ csrf_token() }}',
        endpoint: '/moderate',
        debounceMs: 120,
    });

    // Função para aplicar warning em qualquer campo
    function applyWarning(seletor, res) {
        const el = document.querySelector(seletor);
        const warn = document.querySelector(seletor + '-warning');
        if (!el) return;
        
        if (res && res.inappropriate) {
            el.classList.add('error');
            if (warn) warn.style.display = 'block';
        } else {
            el.classList.remove('error');
            if (warn) warn.style.display = 'none';
        }
    }

    // Aplicar moderação aos campos de texto
    window.Moderation.attachInput('#titulo', 'titulo', {
        onLocal: (res) => applyWarning('#titulo', res),
        onServer: (srv) => {
            if (srv && srv.data && srv.data.inappropriate) {
                applyWarning('#titulo', { inappropriate: true });
            }
        }
    });

    window.Moderation.attachInput('#conteudo', 'conteudo', {
        onLocal: (res) => applyWarning('#conteudo', res),
        onServer: (srv) => {
            if (srv && srv.data && srv.data.inappropriate) {
                applyWarning('#conteudo', { inappropriate: true });
            }
        }
    });
});
</script>

</body>
</html>