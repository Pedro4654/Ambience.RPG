<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Criar Ticket - Ambience RPG</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #0a0f14;
            --card: #1f2a33;
            --muted: #8b9ba8;
            --accent: #22c55e;
            --accent-light: #16a34a;
            --accent-dark: #15803d;
            --hero-green: #052e16;
            --text-on-primary: #e6eef6;
            --transition-speed: 600ms;
            --header-bg: rgba(10, 15, 20, 0.75);
                --gradient-start: #022c22;  
    --gradient-mid:   #034935ff;  
    --gradient-end:   #1a422fff; 
            --btn-gradient-start: #22c55e;
            --btn-gradient-end: #16a34a;
            --accent-border: rgba(34, 197, 94, 0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Inter, system-ui, -apple-system, sans-serif;
           background: linear-gradient(145deg, #0a0f14f4, #141c23f2);
            color: var(--text-on-primary);
            -webkit-font-smoothing: antialiased;
            line-height: 1.5;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

         .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--muted);
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 24px;
        transition: all 0.2s;
    }

    .back-link:hover {
        color: var(--accent);
        transform: translateX(-5px);
    }

        .header {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            margin-bottom: 30px;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .header h1 {
            font-family: Montserrat, sans-serif;
            font-size: 36px;
            color: #fff;
            margin-bottom: 12px;
            font-weight: 900;
        }

        .header p {
            color: var(--muted);
            font-size: 16px;
            line-height: 1.6;
        }

        .alert {
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            animation: slideIn 0.3s ease-out;
            background: linear-gradient(145deg, rgba(31, 42, 51, 0.6), rgba(20, 28, 35, 0.4));
            border-left: 4px solid;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-error {
            border-left-color: #ef4444;
            background: linear-gradient(145deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.05));
        }

        .alert-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
            color: #ef4444;
        }

        .form-card {
            background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(12px);
            padding: 40px;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 2px solid rgba(34, 197, 94, 0.1);
        }

        .form-section:last-of-type {
            border-bottom: none;
            padding-bottom: 0;
        }

        .form-section h2 {
            font-family: Montserrat, sans-serif;
            font-size: 20px;
            color: var(--accent);
            margin-bottom: 20px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #fff;
            margin-bottom: 10px;
            font-size: 15px;
        }

        .required {
            color: var(--accent);
            margin-left: 4px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid rgba(34, 197, 94, 0.2);
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
            background: #1f2a3380;
            color: var(--text-on-primary);
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
            background: rgba(31, 42, 51, 0.7);
        }

        .form-select option {
            background: var(--card);
            color: var(--text-on-primary);
}

        .form-textarea {
            resize: vertical;
            min-height: 150px;
            line-height: 1.6;
        }

        .form-hint {
            color: var(--muted);
            font-size: 13px;
            margin-top: 8px;
            display: block;
        }

        .hidden {
            display: none;
        }

        .autocomplete-container {
            position: relative;
        }

        .autocomplete-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.98));
            border: 2px solid rgba(34, 197, 94, 0.3);
            border-top: none;
            border-radius: 0 0 10px 10px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        .autocomplete-item {
            padding: 12px 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(34, 197, 94, 0.1);
            transition: background 0.2s ease;
        }

        .autocomplete-item:hover {
            background: rgba(34, 197, 94, 0.1);
        }

        .autocomplete-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .autocomplete-item-info {
            flex: 1;
        }

        .autocomplete-item-username {
            font-weight: 600;
            color: #fff;
        }

        .autocomplete-item-nickname {
            font-size: 13px;
            color: var(--muted);
        }

        .autocomplete-empty {
            padding: 20px;
            text-align: center;
            color: var(--muted);
        }

        .selected-user-card {
            display: none;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15), rgba(22, 163, 74, 0.1));
            border: 2px solid var(--accent);
            border-radius: 10px;
            padding: 16px;
            margin-top: 12px;
            align-items: center;
            gap: 12px;
        }

        .selected-user-card.active {
            display: flex;
        }

        .selected-user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--accent);
        }

        .selected-user-info {
            flex: 1;
        }

        .selected-user-username {
            font-weight: 700;
            color: #fff;
            font-size: 16px;
        }

        .selected-user-nickname {
            font-size: 13px;
            color: var(--muted);
        }

        .remove-user-btn {
            background: #ef4444;
            color: white;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.2s;
        }

        .remove-user-btn:hover {
            background: #dc2626;
            transform: rotate(90deg);
        }

        .file-preview-container {
            margin-top: 16px;
            display: none;
        }

        .file-preview-container.active {
            display: block;
        }

        .file-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 12px;
        }

        .file-preview-item {
            background: rgba(31, 42, 51, 0.6);
            border: 2px solid rgba(34, 197, 94, 0.2);
            border-radius: 10px;
            padding: 12px;
            position: relative;
            transition: all 0.2s;
        }

        .file-preview-item:hover {
            border-color: var(--accent);
            transform: translateY(-2px);
        }

        .file-preview-image {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .file-preview-video {
            width: 100%;
            height: 100px;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .file-preview-icon {
            width: 100%;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            border-radius: 6px;
            color: white;
            margin-bottom: 8px;
        }

        .file-preview-name {
            font-size: 12px;
            font-weight: 600;
            color: #fff;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .file-preview-size {
            font-size: 11px;
            color: var(--muted);
        }

        .file-preview-remove {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #ef4444;
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all 0.2s;
        }

        .file-preview-remove:hover {
            background: #dc2626;
            transform: rotate(90deg);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid rgba(34, 197, 94, 0.1);
        }

        .btn {
            padding: 14px 28px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: Inter, sans-serif;
        }

        .btn-primary {
            background: linear-gradient(to right, var(--btn-gradient-start), var(--btn-gradient-end));
            color: var(--hero-green);
            box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: transparent;
            border: 1px solid var(--accent-border);
            color: var(--accent);
        }

        .btn-secondary:hover {
            background: rgba(34, 197, 94, 0.1);
            border-color: var(--accent);
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 20px;
            border: 2px dashed rgba(34, 197, 94, 0.3);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(31, 42, 51, 0.5);
        }

        .file-input-label:hover {
            border-color: var(--accent);
            background: rgba(34, 197, 94, 0.1);
        }

        .file-input-label svg {
            width: 24px;
            height: 24px;
            color: var(--accent);
        }

        input[type="file"] {
            position: absolute;
            left: -9999px;
        }

        .nsfw-warning {
            display: none;
            background: linear-gradient(145deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.05));
            border-left: 4px solid #ef4444;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            color: #fee;
            font-size: 14px;
        }

        .nsfw-warning.show {
            display: block;
            animation: slideIn 0.3s ease-out;
        }

        .input-warn {
            border: 2px solid #e0556b !important;
            background: rgba(224, 85, 107, 0.1) !important;
        }

        .moderation-warning {
            display: none;
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 4px;
            font-weight: 600;
        }

        .moderation-warning.show {
            display: block;
            animation: slideIn 0.3s ease-out;
        }

        .form-input.input-warn,
        .form-textarea.input-warn {
            animation: shake 0.3s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }

            .header,
            .form-card {
                padding: 25px 20px;
            }

            .header h1 {
                font-size: 28px;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn {
                width: 100%;
                text-align: center;
                justify-content: center;
            }

            .file-preview-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Back Link -->
        <a href="{{ route('suporte.index') }}" class="back-link">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Voltar
        </a>

        <!-- Header -->
        <div class="header">
            <h1> Criar Ticket de Suporte</h1>
            <p>Descreva seu problema ou dúvida com o máximo de detalhes possível para que possamos ajudá-lo melhor.</p>
        </div>

        <!-- Mensagens de Erro -->
        @if($errors->any())
        <div class="alert alert-error">
            <svg class="alert-icon" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
            </svg>
            <div>
                <strong>Erros encontrados:</strong>
                <ul style="margin-top: 10px; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            <svg class="alert-icon" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
            </svg>
            <div>{{ session('error') }}</div>
        </div>
        @endif

        <!-- Formulário -->
        <div class="form-card">
            <form id="ticketForm" action="{{ route('suporte.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Seção: Informações Básicas -->
                <div class="form-section">
                    <h2><img src="/images/ICONS/prancheta.png" alt="Informações" style="width:24px; height:24px; margin-right:6px;"> Informações Básicas</h2>

                    <!-- Categoria -->
                   <div class="form-group">
    <label for="categoria" class="form-label">
        Categoria do Ticket <span class="required">*</span>
    </label>
    <select name="categoria" id="categoria" required class="form-select">
        <option value="">Selecione uma categoria</option>
        <option value="duvida" {{ old('categoria') == 'duvida' ? 'selected' : '' }}>Dúvida</option>
        <option value="problema_tecnico" {{ old('categoria') == 'problema_tecnico' ? 'selected' : '' }}>Problema Técnico</option>
        <option value="denuncia" {{ old('categoria') == 'denuncia' ? 'selected' : '' }}>Denúncia</option>
        <option value="sugestao" {{ old('categoria') == 'sugestao' ? 'selected' : '' }}>Sugestão</option>
        <option value="outro" {{ old('categoria') == 'outro' ? 'selected' : '' }}>Outro</option>
    </select>
</div>


                    <!-- Campo de Denúncia (oculto por padrão) -->
                    <div id="campo-denuncia" class="form-group hidden">
                        <label for="usuario_denunciado_username" class="form-label">
                             Usuário Denunciado <span class="required">*</span>
                        </label>
                        <div class="autocomplete-container">
                            <input type="text"
                                name="usuario_denunciado_username"
                                id="usuario_denunciado_username"
                                value="{{ old('usuario_denunciado_username') }}"
                                placeholder="Digite o username do usuário"
                                class="form-input"
                                autocomplete="off">

                            <div id="sugestoes-usuarios" class="autocomplete-results hidden"></div>
                        </div>
                        <span class="form-hint">Digite para buscar o usuário que deseja denunciar</span>

                        <!-- Card do usuário selecionado -->
                        <div id="selected-user-card" class="selected-user-card">
                            <img id="selected-user-avatar" class="selected-user-avatar" src="" alt="">
                            <div class="selected-user-info">
                                <div class="selected-user-username" id="selected-user-username"></div>
                                <div class="selected-user-nickname" id="selected-user-nickname"></div>
                            </div>
                            <button type="button" class="remove-user-btn" id="remove-user-btn">×</button>
                        </div>
                    </div>
                </div>

                <!-- Seção: Detalhes do Problema -->
                <div class="form-section">
                    <h2><img src="/images/ICONS/comentarios.png" alt="Informações" style="width:24px; height:24px; margin-right:6px;"> Detalhes do Problema</h2>

                    <!-- Assunto -->
                    <div class="form-group">
                        <label for="assunto" class="form-label">
                            Assunto <span class="required">*</span>
                        </label>
                        <input type="text"
                            name="assunto"
                            id="assunto"
                            value="{{ old('assunto') }}"
                            required
                            maxlength="255"
                            placeholder="Ex: Não consigo acessar minha conta"
                            class="form-input">
                        <span class="form-hint">Descreva brevemente o problema (máximo 255 caracteres)</span>
                    </div>

                    <!-- Descrição -->
                    <div class="form-group">
                        <label for="descricao" class="form-label">
                            Descrição Detalhada <span class="required">*</span>
                        </label>
                        <textarea name="descricao"
                            id="descricao"
                            required
                            minlength="20"
                            placeholder="Descreva o problema com o máximo de detalhes possível. Inclua informações como quando aconteceu, o que você estava fazendo, mensagens de erro, etc."
                            class="form-textarea">{{ old('descricao') }}</textarea>
                        <span class="form-hint">Mínimo 20 caracteres - Quanto mais detalhes, melhor poderemos ajudá-lo</span>
                    </div>
                </div>

                <!-- Seção: Anexos -->
                <div class="form-section">
                    <h2><img src="/images/ICONS/anexos.png" alt="Informações" style="width:34px; height:34px; margin-right:6px;"> Anexos (Opcional)</h2>

                    <div class="form-group">
                        <div class="file-input-wrapper">
                            <label for="anexos" class="file-input-label">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span id="fileLabel">Clique para selecionar arquivos ou arraste aqui</span>
                            </label>
                            <input type="file"
                                name="anexos[]"
                                id="anexos"
                                multiple
                                accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt,.zip,.glb,.mp4,.webm,.mov,.avi,.xls,.xlsx,.ppt,.pptx,.csv">
                        </div>
                        <span class="form-hint">
                            Máximo {{ $maxAnexos ?? 5 }} arquivos de até 100MB cada.
                            Formatos permitidos: imagens (jpg, png, gif), vídeos (mp4, webm, mov), documentos (pdf, doc, docx, txt, xls, xlsx, ppt, pptx), zip, <strong>glb (modelos 3D)</strong>
                        </span>

                        <!-- Preview de arquivos -->
                        <div id="file-preview-container" class="file-preview-container">
                            <h4 style="font-size: 14px; font-weight: 600; color: #fff; margin-bottom: 12px;"><img src="/images/ICONS/pasta.png" alt="Informações" style="width:20px; height:20px; margin-right:6px;"> Arquivos Selecionados:</h4>
                            <div id="file-preview-grid" class="file-preview-grid"></div>
                        </div>

                        <!-- Alerta NSFW (oculto por padrão) -->
                        <div id="nsfwWarning" class="nsfw-warning">
                            ⚠️ Uma ou mais imagens foram identificadas como inapropriadas. Por favor, remova-as antes de continuar.
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="form-actions">
                    <a href="{{ route('suporte.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                         Criar Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts NSFW e Moderação -->
    <script src="https://unpkg.com/nsfwjs@2.4.2/dist/nsfwjs.min.js"></script>
    <script src="{{ asset('js/nsfw-detector.js') }}"></script>
    <script src="{{ asset('js/moderation.js') }}" defer></script>

    <script>
        // ===== MOSTRAR/OCULTAR CAMPO DE DENÚNCIA =====
        const categoriaSelect = document.getElementById('categoria');
        const campoDenuncia = document.getElementById('campo-denuncia');
        const inputUsuario = document.getElementById('usuario_denunciado_username');
        const sugestoesDiv = document.getElementById('sugestoes-usuarios');
        const selectedUserCard = document.getElementById('selected-user-card');
        const removeUserBtn = document.getElementById('remove-user-btn');

        let usuarioSelecionado = null;

        categoriaSelect.addEventListener('change', function() {
            if (this.value === 'denuncia') {
                campoDenuncia.classList.remove('hidden');
            } else {
                campoDenuncia.classList.add('hidden');
                inputUsuario.value = '';
                usuarioSelecionado = null;
                selectedUserCard.classList.remove('active');
            }
        });

        // Verificar se já estava selecionado (ao voltar com erro)
        if (categoriaSelect.value === 'denuncia') {
            campoDenuncia.classList.remove('hidden');
        }

        // ===== BUSCA DE USUÁRIOS EM TEMPO REAL (AJAX) =====
        let timeoutId;

        if (inputUsuario) {
            inputUsuario.addEventListener('input', function() {
                const termo = this.value.trim();

                clearTimeout(timeoutId);

                // Se tem usuário selecionado e apagou o input, limpar seleção
                if (!termo && usuarioSelecionado) {
                    usuarioSelecionado = null;
                    selectedUserCard.classList.remove('active');
                }

                if (termo.length < 2) {
                    sugestoesDiv.classList.add('hidden');
                    sugestoesDiv.innerHTML = '';
                    return;
                }

                timeoutId = setTimeout(() => {
                    fetch(`{{ route('suporte.buscar-usuarios') }}?termo=${encodeURIComponent(termo)}`)
                        .then(response => response.json())
                        .then(usuarios => {
                            if (usuarios.length === 0) {
                                sugestoesDiv.innerHTML = '<div class="autocomplete-empty">Nenhum usuário encontrado</div>';
                                sugestoesDiv.classList.remove('hidden');
                                return;
                            }

                            let html = '';
                            usuarios.forEach(usuario => {
                                const avatar = usuario.avatar_url || '/images/default-avatar.png';
                                const nickname = usuario.nickname || '';
                                html += `
                                    <div class="autocomplete-item" 
                                         data-username="${usuario.username}"
                                         data-nickname="${nickname}"
                                         data-avatar="${avatar}">
                                        <img src="${avatar}" alt="${usuario.username}">
                                        <div class="autocomplete-item-info">
                                            <div class="autocomplete-item-username">@${usuario.username}</div>
                                            ${nickname ? `<div class="autocomplete-item-nickname">${nickname}</div>` : ''}
                                        </div>
                                    </div>
                                `;
                            });

                            sugestoesDiv.innerHTML = html;
                            sugestoesDiv.classList.remove('hidden');

                            // Adicionar eventos de clique
                            document.querySelectorAll('.autocomplete-item').forEach(el => {
                                el.addEventListener('click', function() {
                                    const username = this.dataset.username;
                                    const nickname = this.dataset.nickname;
                                    const avatar = this.dataset.avatar;

                                    // Salvar usuário selecionado
                                    usuarioSelecionado = { username, nickname, avatar };

                                    // Preencher input
                                    inputUsuario.value = username;

                                    // Mostrar card do usuário selecionado
                                    document.getElementById('selected-user-avatar').src = avatar;
                                    document.getElementById('selected-user-username').textContent = `@${username}`;
                                    document.getElementById('selected-user-nickname').textContent = nickname || 'Sem apelido';
                                    selectedUserCard.classList.add('active');

                                    // Esconder sugestões
                                    sugestoesDiv.classList.add('hidden');
                                });
                            });
                        })
                        .catch(error => {
                            console.error('Erro ao buscar usuários:', error);
                            sugestoesDiv.innerHTML = '<div class="autocomplete-empty">Erro ao buscar usuários</div>';
                            sugestoesDiv.classList.remove('hidden');
                        });
                }, 300);
            });

            // Remover usuário selecionado
            removeUserBtn.addEventListener('click', function() {
                usuarioSelecionado = null;
                inputUsuario.value = '';
                selectedUserCard.classList.remove('active');
            });

            // Fechar sugestões ao clicar fora
            document.addEventListener('click', function(e) {
                if (!inputUsuario.contains(e.target) && !sugestoesDiv.contains(e.target)) {
                    sugestoesDiv.classList.add('hidden');
                }
            });
        }

        // ===== PREVIEW DE ARQUIVOS SELECIONADOS =====
        const fileInput = document.getElementById('anexos');
        const fileLabel = document.getElementById('fileLabel');
        const submitBtn = document.getElementById('submitBtn');
        const nsfwWarning = document.getElementById('nsfwWarning');
        const ticketForm = document.getElementById('ticketForm');
        const filePreviewContainer = document.getElementById('file-preview-container');
        const filePreviewGrid = document.getElementById('file-preview-grid');

        let hasInappropriateImages = false;
        let selectedFiles = [];

        if (fileInput) {
            fileInput.addEventListener('change', async function(e) {
                const newFiles = Array.from(this.files);
                const maxAnexos = {{ $maxAnexos ?? 5 }};
                
                // Adicionar novos arquivos aos já selecionados
                newFiles.forEach(file => {
                    // Verificar se não excede o limite
                    if (selectedFiles.length < maxAnexos) {
                        // Verificar se arquivo já não foi adicionado (mesmo nome e tamanho)
                        const jaExiste = selectedFiles.some(f => 
                            f.name === file.name && f.size === file.size
                        );
                        
                        if (!jaExiste) {
                            selectedFiles.push(file);
                        }
                    }
                });
                
                // Verificar se excedeu o limite
                if (newFiles.length + selectedFiles.length > maxAnexos && selectedFiles.length === maxAnexos) {
                    alert(`⚠️ Você pode adicionar no máximo ${maxAnexos} arquivos.`);
                }
                
                if (selectedFiles.length > 0) {
                    fileLabel.textContent = `${selectedFiles.length} arquivo(s) selecionado(s) (máx: ${maxAnexos})`;
                    filePreviewContainer.classList.add('active');
                    renderFilePreviews();
                } else {
                    fileLabel.textContent = 'Clique para selecionar arquivos ou arraste aqui';
                    filePreviewContainer.classList.remove('active');
                }
                
                // Atualizar o input com todos os arquivos selecionados
                updateFileInput();

                // Reset do estado NSFW
                hasInappropriateImages = false;
                nsfwWarning.classList.remove('show');
                submitBtn.disabled = false;

                // Analisar todas as imagens
                analyzeAllImages();
            });
        }

        // ===== RENDERIZAR PREVIEWS DOS ARQUIVOS =====
        function renderFilePreviews() {
            filePreviewGrid.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const previewItem = document.createElement('div');
                previewItem.className = 'file-preview-item';
                previewItem.dataset.index = index;

                // Botão de remover
                const removeBtn = document.createElement('button');
                removeBtn.className = 'file-preview-remove';
                removeBtn.innerHTML = '×';
                removeBtn.type = 'button';
                removeBtn.onclick = function() {
                    removeFile(index);
                };
                previewItem.appendChild(removeBtn);

                // Preview baseado no tipo
                const extension = file.name.split('.').pop().toLowerCase();
                
                if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(extension)) {
                    // Preview de imagem
                    const img = document.createElement('img');
                    img.className = 'file-preview-image';
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    previewItem.appendChild(img);
                } else if (['mp4', 'webm', 'mov', 'avi'].includes(extension)) {
                    // Preview de vídeo
                    const video = document.createElement('video');
                    video.className = 'file-preview-video';
                    video.controls = true;
                    video.preload = 'metadata';
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        video.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    previewItem.appendChild(video);
                } else {
                    // Ícone genérico
                    const iconDiv = document.createElement('div');
                    iconDiv.className = 'file-preview-icon';
                    iconDiv.textContent = getFileIcon(extension);
                    previewItem.appendChild(iconDiv);
                }

                // Nome e tamanho
                const nameDiv = document.createElement('div');
                nameDiv.className = 'file-preview-name';
                nameDiv.textContent = file.name;
                nameDiv.title = file.name;
                previewItem.appendChild(nameDiv);

                const sizeDiv = document.createElement('div');
                sizeDiv.className = 'file-preview-size';
                sizeDiv.textContent = formatFileSize(file.size);
                previewItem.appendChild(sizeDiv);

                filePreviewGrid.appendChild(previewItem);
            });
        }

        // ===== REMOVER ARQUIVO DA LISTA =====
        function removeFile(index) {
            selectedFiles.splice(index, 1);
            
            // Atualizar o input file
            updateFileInput();

            // Atualizar UI
            const maxAnexos = {{ $maxAnexos ?? 5 }};
            if (selectedFiles.length > 0) {
                fileLabel.textContent = `${selectedFiles.length} arquivo(s) selecionado(s) (máx: ${maxAnexos})`;
                renderFilePreviews();
                
                // Re-analisar imagens após remover arquivo
                reAnalyzeImages();
            } else {
                fileLabel.textContent = 'Clique para selecionar arquivos ou arraste aqui';
                filePreviewContainer.classList.remove('active');
                nsfwWarning.classList.remove('show');
                submitBtn.disabled = false;
                hasInappropriateImages = false;
            }
        }

        // ===== ATUALIZAR INPUT FILE =====
        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            fileInput.files = dataTransfer.files;
        }

        // ===== RE-ANALISAR IMAGENS APÓS REMOÇÃO =====
        async function reAnalyzeImages() {
            // Filtrar apenas imagens
            const imageFiles = selectedFiles.filter(file => {
                return file.type.startsWith('image/') &&
                    /\.(jpg|jpeg|png|gif|webp|bmp)$/i.test(file.name);
            });

            if (imageFiles.length === 0) {
                // Não há mais imagens para analisar
                hasInappropriateImages = false;
                nsfwWarning.classList.remove('show');
                submitBtn.disabled = false;
                return;
            }

            // Reset do estado
            hasInappropriateImages = false;
            nsfwWarning.classList.remove('show');

            console.log(`🔍 Re-analisando ${imageFiles.length} imagem(ns)...`);

            try {
                submitBtn.disabled = true;
                submitBtn.textContent = '⏳ Analisando imagens...';

                for (const file of imageFiles) {
                    const result = await window.NSFWDetector.analyze(file);

                    if (result && result.isBlocked) {
                        hasInappropriateImages = true;
                        console.warn('⚠️ Imagem inapropriada detectada:', file.name, result);
                        break;
                    }
                }

                // Atualizar UI
                if (hasInappropriateImages) {
                    nsfwWarning.classList.add('show');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Criar Ticket';
                    console.log('🚫 Submit bloqueado devido a conteúdo inapropriado');
                } else {
                    nsfwWarning.classList.remove('show');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Criar Ticket';
                    console.log('✅ Todas as imagens aprovadas');
                }

            } catch (error) {
                console.error('❌ Erro ao re-analisar imagens:', error);
                nsfwWarning.classList.remove('show');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Criar Ticket';
            }
        }

        // ===== OBTER ÍCONE DO ARQUIVO =====
        function getFileIcon(extension) {
            const icons = {
                'pdf': '📄',
                'doc': '📃',
                'docx': '📃',
                'xls': '📊',
                'xlsx': '📊',
                'ppt': '📽️',
                'pptx': '📽️',
                'txt': '📝',
                'zip': '🗜️',
                'rar': '🗜️',
                'glb': '🎲',
                'csv': '📊',
                'mp4': '🎥',
                'webm': '🎥',
                'mov': '🎥',
                'avi': '🎥'
            };
            return icons[extension] || '📎';
        }

        // ===== FORMATAR TAMANHO DO ARQUIVO =====
        function formatFileSize(bytes) {
            if (bytes >= 1073741824) {
                return (bytes / 1073741824).toFixed(2) + ' GB';
            } else if (bytes >= 1048576) {
                return (bytes / 1048576).toFixed(2) + ' MB';
            } else if (bytes >= 1024) {
                return (bytes / 1024).toFixed(2) + ' KB';
            } else {
                return bytes + ' bytes';
            }
        }

        // ===== VALIDAÇÃO ADICIONAL NO SUBMIT =====
        ticketForm.addEventListener('submit', function(e) {
            if (hasInappropriateImages) {
                e.preventDefault();
                e.stopPropagation();
                alert('⚠️ Por favor, remova as imagens inapropriadas antes de criar o ticket.');
                return false;
            }

            // Validação adicional para denúncias
            if (categoriaSelect.value === 'denuncia') {
                if (!inputUsuario.value.trim()) {
                    e.preventDefault();
                    alert('⚠️ Para denúncias, é obrigatório informar o usuário.');
                    inputUsuario.focus();
                    return false;
                }
            }

            // Desabilitar botão para evitar duplo envio
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Criando ticket...';
        });

        // ===== DRAG AND DROP SUPPORT =====
        const fileInputLabel = document.querySelector('.file-input-label');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileInputLabel.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileInputLabel.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileInputLabel.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            fileInputLabel.style.borderColor = '#667eea';
            fileInputLabel.style.background = '#edf2f7';
        }

        function unhighlight(e) {
            fileInputLabel.style.borderColor = '#cbd5e0';
            fileInputLabel.style.background = '#f7fafc';
        }

        fileInputLabel.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const newFiles = Array.from(dt.files);
            const maxAnexos = {{ $maxAnexos ?? 5 }};
            
            // Adicionar novos arquivos aos já selecionados
            newFiles.forEach(file => {
                if (selectedFiles.length < maxAnexos) {
                    const jaExiste = selectedFiles.some(f => 
                        f.name === file.name && f.size === file.size
                    );
                    
                    if (!jaExiste) {
                        selectedFiles.push(file);
                    }
                }
            });
            
            if (newFiles.length + selectedFiles.length > maxAnexos && selectedFiles.length === maxAnexos) {
                alert(`⚠️ Você pode adicionar no máximo ${maxAnexos} arquivos.`);
            }
            
            // Atualizar input e UI
            updateFileInput();
            
            if (selectedFiles.length > 0) {
                fileLabel.textContent = `${selectedFiles.length} arquivo(s) selecionado(s) (máx: ${maxAnexos})`;
                filePreviewContainer.classList.add('active');
                renderFilePreviews();
                
                // Trigger análise NSFW
                analyzeAllImages();
            }
        }

        // ===== ANALISAR TODAS AS IMAGENS (INICIAL) =====
        async function analyzeAllImages() {
            // Filtrar apenas imagens
            const imageFiles = selectedFiles.filter(file => {
                return file.type.startsWith('image/') &&
                    /\.(jpg|jpeg|png|gif|webp|bmp)$/i.test(file.name);
            });

            if (imageFiles.length === 0) {
                return;
            }

            console.log(`🔍 Analisando ${imageFiles.length} imagem(ns)...`);

            // Reset do estado
            hasInappropriateImages = false;
            nsfwWarning.classList.remove('show');

            try {
                submitBtn.disabled = true;
                submitBtn.textContent = '⏳ Analisando imagens...';

                for (const file of imageFiles) {
                    const result = await window.NSFWDetector.analyze(file);

                    if (result && result.isBlocked) {
                        hasInappropriateImages = true;
                        console.warn('⚠️ Imagem inapropriada detectada:', file.name, result);
                        break;
                    }
                }

                if (hasInappropriateImages) {
                    nsfwWarning.classList.add('show');
                    submitBtn.disabled = true;
                    submitBtn.textContent = '🚀 Criar Ticket';
                    console.log('🚫 Submit bloqueado devido a conteúdo inapropriado');
                } else {
                    nsfwWarning.classList.remove('show');
                    submitBtn.disabled = false;
                    submitBtn.textContent = '🚀 Criar Ticket';
                    console.log('✅ Todas as imagens aprovadas');
                }

            } catch (error) {
                console.error('❌ Erro ao analisar imagens:', error);
                nsfwWarning.classList.remove('show');
                submitBtn.disabled = false;
                submitBtn.textContent = '🚀 Criar Ticket';
            }
        }

        // ===== LOGS DE DEBUG =====
        console.log('✅ Sistema de tickets inicializado');
        console.log('📧 Sistema de emails ativo para:');
        console.log('   - Criação de ticket');
        console.log('   - Resposta de staff');
        console.log('   - Alteração de status');
        console.log('   - Alteração de prioridade');
        console.log('   - Atribuição de ticket');
        console.log('   - Fechamento de ticket');
        console.log('   - Reabertura de ticket');
        console.log('🛡️ Sistema de moderação NSFW ativo');
        console.log('🔍 Busca de usuários em tempo real ativa');
        console.log('📎 Preview de arquivos ativo');
        console.log('🎯 Suporte a múltiplos formatos: imagens, vídeos, documentos, 3D (GLB)');

        // ===== SISTEMA DE MODERAÇÃO DE TEXTO =====
        document.addEventListener('DOMContentLoaded', async function() {
            const form = document.getElementById('ticketForm');
            const assuntoInput = document.getElementById('assunto');
            const descricaoInput = document.getElementById('descricao');
            const submitBtn = document.getElementById('submitBtn');

            // Aguardar o carregamento do sistema de moderação
            if (!window.Moderation) {
                console.error('❌ Sistema de moderação não disponível');
                return;
            }

            try {
                // Inicializar o sistema de moderação
                const state = await window.Moderation.init({
                    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    endpoint: '/moderate',
                    debounceMs: 120
                });

                console.log('🛡️ Sistema de moderação de tickets inicializado:', state);

                // Função auxiliar para aplicar avisos visuais
                function applyWarning(selector, res) {
                    const el = document.querySelector(selector);
                    if (!el) return;

                    const warnId = selector.replace('#', '') + '-warning';
                    let warn = document.getElementById(warnId);
                    
                    // Criar elemento de aviso se não existir
                    if (!warn) {
                        warn = document.createElement('small');
                        warn.id = warnId;
                        warn.className = 'moderation-warning';
                        warn.textContent = 'Conteúdo inapropriado detectado';
                        el.parentNode.insertBefore(warn, el.nextSibling);
                    }

                    if (res && res.inappropriate) {
                        el.classList.add('input-warn');
                        warn.classList.add('show');
                    } else {
                        el.classList.remove('input-warn');
                        warn.classList.remove('show');
                    }
                }

                // Conectar campo assunto
                window.Moderation.attachInput('#assunto', 'assunto', {
                    onLocal: (res) => {
                        applyWarning('#assunto', res);
                        if (res.inappropriate) {
                            console.warn('⚠️ Assunto com conteúdo inapropriado:', res.matches);
                        }
                    },
                    onServer: (srv) => {
                        if (srv && srv.data && srv.data.inappropriate) {
                            applyWarning('#assunto', { inappropriate: true });
                            console.warn('⚠️ Servidor detectou conteúdo inapropriado no assunto');
                        }
                    }
                });

                // Conectar campo descrição
                window.Moderation.attachInput('#descricao', 'descricao', {
                    onLocal: (res) => {
                        applyWarning('#descricao', res);
                        if (res.inappropriate) {
                            console.warn('⚠️ Descrição com conteúdo inapropriado:', res.matches);
                        }
                    },
                    onServer: (srv) => {
                        if (srv && srv.data && srv.data.inappropriate) {
                            applyWarning('#descricao', { inappropriate: true });
                            console.warn('⚠️ Servidor detectou conteúdo inapropriado na descrição');
                        }
                    }
                });

                // Interceptar submit do formulário
                const formHook = window.Moderation.attachFormSubmit('#ticketForm', [
                    { selector: '#assunto', fieldName: 'assunto' },
                    { selector: '#descricao', fieldName: 'descricao' }
                ]);

                // Listener para bloqueio de formulário
                form.addEventListener('moderation:blocked', (e) => {
                    console.error('🚫 Formulário bloqueado por conteúdo inapropriado:', e.detail);
                    
                    // Reabilitar botão
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '🚀 Criar Ticket';
                    
                    // Mostrar alerta ao usuário
                    alert('⚠️ Seu ticket contém conteúdo inapropriado. Por favor, revise o texto antes de enviar.');
                });

                // Listener para formulário aprovado
                form.addEventListener('moderation:approved', (e) => {
                    console.log('✅ Formulário aprovado pela moderação:', e.detail);
                });

                console.log('✅ Sistema de moderação de texto configurado com sucesso');
                console.log('📝 Campos monitorados: assunto, descrição');

            } catch (error) {
                console.error('❌ Erro ao inicializar moderação de texto:', error);
                
                // Fallback: sistema básico de validação
                form.addEventListener('submit', function(e) {
                    const assunto = assuntoInput.value.trim();
                    const descricao = descricaoInput.value.trim();
                    
                    if (!assunto || assunto.length < 5) {
                        e.preventDefault();
                        alert('⚠️ O assunto deve ter no mínimo 5 caracteres.');
                        return false;
                    }
                    
                    if (!descricao || descricao.length < 20) {
                        e.preventDefault();
                        alert('⚠️ A descrição deve ter no mínimo 20 caracteres.');
                        return false;
                    }
                });
            }
        });

        

        // Pre-carregamento do modelo NSFW (opcional)
        document.addEventListener('DOMContentLoaded', () => {
            try {
                // Proteção: não pre-carrega se a conexão for péssima ou o usuário pediu economia de dados
                const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
                if (conn && (conn.saveData || /2g/.test(conn.effectiveType || ''))) {
                    console.log('ℹ️ Pulando pre-load do modelo NSFW (conexão lenta / save-data).');
                    return;
                }
                
                // Pré-carregar modelo NSFW se disponível
                if (window.NSFWDetector) {
                    window.NSFWDetector.loadModel()
                        .then(() => {
                            console.log('✅ Modelo NSFW pré-carregado para criar ticket.');
                        })
                        .catch(err => {
                            console.warn('⚠️ Falha ao pré-carregar modelo NSFW:', err);
                        });
                }
            } catch (e) { 
                console.warn('⚠️ Erro no preloader NSFW:', e); 
            }
        });
    </script>
</body>

</html>