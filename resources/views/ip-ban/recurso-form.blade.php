<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recurso de IP Ban - Ambience RPG</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 700px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .card-header svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .card-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .card-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .card-body {
            padding: 40px 30px;
        }

        .alert {
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .alert-error {
            background: #fee2e2;
            border-left: 4px solid #dc2626;
            color: #991b1b;
        }

        .info-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-left: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .info-box h3 {
            font-size: 16px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .info-box p {
            font-size: 14px;
            color: #1e40af;
            line-height: 1.6;
        }

        .user-info {
            background: #f9fafb;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid #dc2626;
        }

        .user-details h4 {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 4px;
        }

        .user-details p {
            font-size: 14px;
            color: #6b7280;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .required {
            color: #dc2626;
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 150px;
        }

        .form-hint {
            font-size: 13px;
            color: #6b7280;
            margin-top: 6px;
            display: block;
        }

        .file-input-wrapper {
            position: relative;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 20px;
            border: 2px dashed #cbd5e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .file-input-label:hover {
            border-color: #dc2626;
            background: #fee2e2;
        }

        input[type="file"] {
            position: absolute;
            left: -9999px;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }

            .card-header {
                padding: 30px 20px;
            }

            .card-header h1 {
                font-size: 24px;
            }

            .card-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <h1>🛡️ Recurso de IP Ban</h1>
                <p>Preencha o formulário abaixo para solicitar revisão do banimento</p>
            </div>

            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-error">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                        </svg>
                        <div>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="info-box">
                    <h3>ℹ️ Informações Importantes</h3>
                    <p>
                        • Seu recurso será analisado por nossa equipe de moderação<br>
                        • Forneça o máximo de detalhes possível sobre sua situação<br>
                        • Você receberá uma resposta por email em até 72 horas<br>
                        • Anexe provas se tiver (capturas de tela, documentos, etc.)
                    </p>
                </div>

                <div class="user-info">
                    <img src="{{ $usuarioBanido->avatar_url }}" alt="{{ $usuarioBanido->username }}" class="user-avatar">
                    <div class="user-details">
                        <h4>{{ $usuarioBanido->username }}</h4>
                        <p>Conta banida em: {{ $usuarioBanido->ip_ban_data ? $usuarioBanido->ip_ban_data->format('d/m/Y H:i') : 'N/A' }}</p>
                        <p><strong>Motivo:</strong> {{ $usuarioBanido->ip_ban_motivo ?? 'Não especificado' }}</p>
                    </div>
                </div>

                <form action="{{ route('ip-ban.recurso.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email para Contato <span class="required">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $usuarioBanido->email) }}"
                               required
                               class="form-input"
                               placeholder="seu@email.com">
                        <span class="form-hint">Usaremos este email para responder seu recurso</span>
                    </div>

                    <div class="form-group">
                        <label for="assunto" class="form-label">
                            Assunto <span class="required">*</span>
                        </label>
                        <input type="text" 
                               name="assunto" 
                               id="assunto" 
                               value="{{ old('assunto') }}"
                               required
                               minlength="10"
                               maxlength="255"
                               class="form-input"
                               placeholder="Ex: Solicito revisão do banimento de IP">
                        <span class="form-hint">Mínimo 10 caracteres</span>
                    </div>

                    <div class="form-group">
                        <label for="descricao" class="form-label">
                            Descrição Detalhada <span class="required">*</span>
                        </label>
                        <textarea name="descricao" 
                                  id="descricao" 
                                  required
                                  minlength="50"
                                  maxlength="5000"
                                  class="form-textarea"
                                  placeholder="Explique detalhadamente por que você acredita que o banimento foi injusto ou deve ser revisado. Seja honesto e forneça o máximo de informações possível.">{{ old('descricao') }}</textarea>
                        <span class="form-hint">Mínimo 50 caracteres - Seja detalhado e honesto</span>
                    </div>

                    <div class="form-group">
                        <label for="anexos" class="form-label">Anexos (Opcional)</label>
                        <div class="file-input-wrapper">
                            <label for="anexos" class="file-input-label">
                                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <span>Clique para adicionar arquivos</span>
                            </label>
                            <input type="file" 
                                   name="anexos[]" 
                                   id="anexos" 
                                   multiple
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">
                        </div>
                        <span class="form-hint">Formatos aceitos: JPG, PNG, PDF, DOC, TXT. Máximo 10MB por arquivo</span>
                    </div>

                    <button type="submit" class="btn-submit">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                        </svg>
                        Enviar Recurso
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Validação básica do formulário
        document.querySelector('form').addEventListener('submit', function(e) {
            const assunto = document.getElementById('assunto').value.trim();
            const descricao = document.getElementById('descricao').value.trim();
            const email = document.getElementById('email').value.trim();

            if (assunto.length < 10) {
                e.preventDefault();
                alert('O assunto deve ter no mínimo 10 caracteres.');
                return false;
            }

            if (descricao.length < 50) {
                e.preventDefault();
                alert('A descrição deve ter no mínimo 50 caracteres.');
                return false;
            }

            if (!email || !email.includes('@')) {
                e.preventDefault();
                alert('Digite um email válido.');
                return false;
            }

            // Desabilitar botão
            const btn = document.querySelector('.btn-submit');
            btn.disabled = true;
            btn.innerHTML = '<svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg> Enviando...';
        });

        // Preview de arquivos
        document.getElementById('anexos').addEventListener('change', function(e) {
            const label = document.querySelector('.file-input-label span');
            const files = this.files;
            
            if (files.length > 0) {
                label.textContent = `${files.length} arquivo(s) selecionado(s)`;
            } else {
                label.textContent = 'Clique para adicionar arquivos';
            }
        });
    </script>
</body>
</html>