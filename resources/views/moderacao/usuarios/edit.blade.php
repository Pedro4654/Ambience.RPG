<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar {{ $usuario->username }} - Moderação</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 24px;
            transition: opacity 0.2s;
        }

        .back-link:hover {
            opacity: 0.8;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px;
        }

        .card-header {
            margin-bottom: 32px;
        }

        .card-header h1 {
            font-size: 32px;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .card-header p {
            color: #6b7280;
            font-size: 16px;
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

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-hint {
            font-size: 13px;
            color: #6b7280;
            margin-top: 6px;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-error {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }

        .alert-warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            color: #92400e;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        .btn {
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 15px;
            }

            .card {
                padding: 24px 20px;
            }

            .card-header h1 {
                font-size: 24px;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('moderacao.usuarios.show', $usuario->id) }}" class="back-link">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Voltar
        </a>

        <div class="card">
            <div class="card-header">
                <h1>✏️ Editar Usuário</h1>
                <p>Editando: <strong>{{ $usuario->username }}</strong></p>
            </div>

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

            @if(!auth()->user()->isAdmin() && $usuario->isStaff())
                <div class="alert alert-warning">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                    </svg>
                    <div>Você não pode alterar o nível de usuário de membros da staff.</div>
                </div>
            @endif

            <form action="{{ route('moderacao.usuarios.update', $usuario->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="username" class="form-label">Username *</label>
                    <input type="text" 
                        name="username" 
                        id="username" 
                        value="{{ old('username', $usuario->username) }}"
                        required
                        maxlength="50"
                        class="form-input">
                    <div class="form-hint">Nome de usuário único para login</div>
                </div>

                <div class="form-group">
                    <label for="nickname" class="form-label">Nickname</label>
                    <input type="text" 
                        name="nickname" 
                        id="nickname" 
                        value="{{ old('nickname', $usuario->nickname) }}"
                        maxlength="50"
                        class="form-input">
                    <div class="form-hint">Nome de exibição (opcional)</div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email', $usuario->email) }}"
                        required
                        maxlength="100"
                        class="form-input">
                </div>

                <div class="form-group">
                    <label for="bio" class="form-label">Biografia</label>
                    <textarea name="bio" 
                        id="bio" 
                        maxlength="500"
                        class="form-textarea">{{ old('bio', $usuario->bio) }}</textarea>
                    <div class="form-hint">Máximo 500 caracteres</div>
                </div>

                @if(auth()->user()->isAdmin())
                    <div class="form-group">
                        <label for="nivel_usuario" class="form-label">Nível de Usuário *</label>
                        <select name="nivel_usuario" id="nivel_usuario" class="form-select">
                            <option value="usuario" {{ $usuario->nivel_usuario === 'usuario' ? 'selected' : '' }}>Usuário</option>
                            <option value="moderador" {{ $usuario->nivel_usuario === 'moderador' ? 'selected' : '' }}>Moderador</option>
                            <option value="admin" {{ $usuario->nivel_usuario === 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                        <div class="form-hint">⚠️ Apenas administradores podem alterar este campo</div>
                    </div>
                @endif

                <div class="form-group">
                    <label for="status" class="form-label">Status da Conta *</label>
                    <select name="status" id="status" class="form-select">
                        <option value="ativo" {{ $usuario->status === 'ativo' ? 'selected' : '' }}>Ativo</option>
                        <option value="inativo" {{ $usuario->status === 'inativo' ? 'selected' : '' }}>Inativo</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        💾 Salvar Alterações
                    </button>
                    <a href="{{ route('moderacao.usuarios.show', $usuario->id) }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>