<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar {{ $usuario->username }} - Moderação</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* =========================================================================
   1. VARIÁVEIS E RESET (Idêntico ao Dashboard)
   ========================================================================= */
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Inter:wght@300;400;600;700;800&display=swap');

:root {
    /* Paleta Core do Dashboard */
    --bg-dark: #0a0f14;
    --card-bg: #1f2a33; /* Fallback */
    --muted: #8b9ba8;
    --accent: #22c55e;
    --accent-light: #16a34a;
    --accent-dark: #15803d;
    --hero-green: #052e16;
    --text-on-primary: #e6eef6;
    
    /* Variáveis de UI */
    --transition-speed: 300ms;
    --btn-gradient-start: #22c55e;
    --btn-gradient-end: #16a34a;
    --card-border: rgba(34, 197, 94, 0.2);
    --input-bg: rgba(10, 15, 20, 0.6);
    --input-border: rgba(34, 197, 94, 0.15);
    
    /* Espaçamentos */
    --spacing-1: 8px;
    --spacing-2: 16px;
    --spacing-3: 24px;
    --spacing-4: 32px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    background: linear-gradient(145deg, #0a0f14f4, #141c23f2); /* Fundo exato do dashboard */
    min-height: 100vh;
    padding: 40px 20px;
    color: var(--text-on-primary);
    -webkit-font-smoothing: antialiased;
    line-height: 1.5;
}

.container {
    max-width: 800px; /* Levemente ajustado para leitura */
    margin: 0 auto;
}

/* =========================================================================
   2. LAYOUT E CARTÃO (Estilo "Glassy" Moderno)
   ========================================================================= */

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


.card {
    background: linear-gradient(145deg, #0a0f14bf, #141c23f2);
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    border: 1px solid var(--card-border);
    animation: fadeInUp 0.5s ease both;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.card-header {
    border-bottom: 1px solid rgba(34, 197, 94, 0.1);
    padding-bottom: var(--spacing-3);
    margin-bottom: var(--spacing-4);
}

.card-header h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 28px;
    color: #fff;
    margin-bottom: 8px;
    font-weight: 800;
    letter-spacing: -0.5px;
}

.card-header p {
    color: var(--muted);
    font-size: 15px;
}

/* =========================================================================
   3. FORMULÁRIO E INPUTS (Adaptados para o Tema Dark/Green)
   ========================================================================= */

.form-group {
    margin-bottom: var(--spacing-3);
}

.form-label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: var(--muted);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 14px 18px;
    background-color: var(--input-bg);
    color: #fff;
    border: 1px solid var(--input-border);
    border-radius: 12px;
    font-size: 15px;
    font-family: inherit;
    transition: all 0.3s ease;
    appearance: none;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: var(--accent);
    background-color: rgba(10, 15, 20, 0.8);
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
}

.form-input::placeholder, .form-textarea::placeholder {
    color: #4b5563;
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
}

.form-hint {
    font-size: 12px;
    color: #6b7280;
    margin-top: 8px;
}

/* Select Arrow Customization */
.select-wrapper {
    position: relative;
}

.form-group option {
            background: var(--card);
            color: var(--text-on-primary);
}


.select-wrapper::after {
    content: '▼';
    position: absolute;
    top: 50%;
    right: 18px;
    transform: translateY(-50%);
    color: var(--accent);
    font-size: 10px;
    pointer-events: none;
}
.form-select.has-arrow {
    padding-right: 40px;
}

/* =========================================================================
   4. ALERTAS E BOTÕES
   ========================================================================= */

.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: var(--spacing-3);
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 14px;
    border: 1px solid transparent;
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
    color: #fca5a5;
    border-left: 4px solid #ef4444;
}

.alert-warning {
    background: rgba(245, 158, 11, 0.1);
    border-color: rgba(245, 158, 11, 0.3);
    color: #fcd34d;
    border-left: 4px solid #f59e0b;
}

.alert-icon {
    flex-shrink: 0;
    width: 20px;
    height: 20px;
    fill: currentColor;
    margin-top: 1px;
}

.form-actions {
    display: flex;
    gap: 16px;
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid rgba(34, 197, 94, 0.1);
}

.btn {
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    border: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    text-decoration: none;
}

/* Botão Primário (Verde Gradiente do Dashboard) */
.btn-primary {
    background: linear-gradient(to right, var(--btn-gradient-start), var(--btn-gradient-end));
    color: var(--hero-green);
    box-shadow: 0 4px 14px rgba(34, 197, 94, 0.3);
    flex: 1;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
    filter: brightness(1.1);
}

/* Botão Secundário (Outline Style do Dashboard) */
.btn-secondary {
    background: rgba(34, 197, 94, 0.05);
    color: var(--accent);
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.btn-secondary:hover {
    background: rgba(34, 197, 94, 0.15);
    transform: translateY(-2px);
    border-color: var(--accent);
    color: #fff;
}

/* =========================================================================
   5. RESPONSIVIDADE
   ========================================================================= */

@media (max-width: 768px) {
    body { padding: 20px 16px; }
    .card { padding: 24px; }
    .card-header h1 { font-size: 24px; }
    .form-actions { flex-direction: column; }
    .btn { width: 100%; }
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
                <h1> Edição de Usuário</h1>
                <p>Ajustando configurações para: **{{ $usuario->username }}**</p>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    <svg class="alert-icon" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                    </svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <div style="margin-bottom: 4px;">{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!auth()->user()->isAdmin() && $usuario->isStaff())
                <div class="alert alert-warning">
                    <svg class="alert-icon" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                    </svg>
                    <div>Você não tem permissão para alterar o nível de usuário de membros da staff.</div>
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
                    <div class="form-hint">Nome de usuário único para login.</div>
                </div>

                <div class="form-group">
                    <label for="nickname" class="form-label">Nickname</label>
                    <input type="text" 
                        name="nickname" 
                        id="nickname" 
                        value="{{ old('nickname', $usuario->nickname) }}"
                        maxlength="50"
                        class="form-input">
                    <div class="form-hint">Nome de exibição público (opcional).</div>
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
                    <div class="form-hint">Máximo 500 caracteres.</div>
                </div>

                <div class="form-group">
                    <label for="genero" class="form-label">Gênero *</label>
                    <div class="select-wrapper">
                        <select name="genero" id="genero" class="form-select has-arrow" required>
                            <option value="">Selecione</option>
                            <option value="masculino" {{ old('genero', $usuario->genero) == 'masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="feminino" {{ old('genero', $usuario->genero) == 'feminino' ? 'selected' : '' }}>Feminino</option>
                        </select>
                    </div>
                    <div class="form-hint">⚠️ Alterar o gênero pode mudar o avatar padrão do usuário.</div>
                </div>

                <div class="form-group">
                    <label for="classe_personagem" class="form-label">Classe do Personagem *</label>
                    <div class="select-wrapper">
                        <select name="classe_personagem" id="classe_personagem" class="form-select has-arrow" required>
                            <option value="">Selecione</option>
                            <option value="ladino" {{ old('classe_personagem', $usuario->classe_personagem) == 'ladino' ? 'selected' : '' }}>🗡️ Ladino</option>
                            <option value="barbaro" {{ old('classe_personagem', $usuario->classe_personagem) == 'barbaro' ? 'selected' : '' }}>🪓 Bárbaro</option>
                            <option value="paladino" {{ old('classe_personagem', $usuario->classe_personagem) == 'paladino' ? 'selected' : '' }}>🛡️ Paladino</option>
                            <option value="arqueiro" {{ old('classe_personagem', $usuario->classe_personagem) == 'arqueiro' ? 'selected' : '' }}>🏹 Arqueiro</option>
                            <option value="bardo" {{ old('classe_personagem', $usuario->classe_personagem) == 'bardo' ? 'selected' : '' }}>🎵 Bardo</option>
                            <option value="mago" {{ old('classe_personagem', $usuario->classe_personagem) == 'mago' ? 'selected' : '' }}>🔮 Mago</option>
                        </select>
                    </div>
                    <div class="form-hint">A classe é usada para exibir emblemas e estatísticas no jogo.</div>
                </div>

                @if(auth()->user()->isAdmin())
                    <div class="form-group">
                        <label for="nivel_usuario" class="form-label">Nível de Usuário *</label>
                        <div class="select-wrapper">
                            <select name="nivel_usuario" id="nivel_usuario" class="form-select has-arrow">
                                <option value="usuario" {{ $usuario->nivel_usuario === 'usuario' ? 'selected' : '' }}>👤 Usuário Padrão</option>
                                <option value="moderador" {{ $usuario->nivel_usuario === 'moderador' ? 'selected' : '' }}>🛡️ Moderador</option>
                                <option value="admin" {{ $usuario->nivel_usuario === 'admin' ? 'selected' : '' }}>👑 Administrador</option>
                            </select>
                        </div>
                        <div class="form-hint">Nível de acesso na plataforma. **Apenas administradores podem alterar este campo.**</div>
                    </div>
                @endif

                <div class="form-group">
                    <label for="status" class="form-label">Status da Conta *</label>
                    <div class="select-wrapper">
                        <select name="status" id="status" class="form-select has-arrow">
                            <option value="ativo" {{ $usuario->status === 'ativo' ? 'selected' : '' }}>🟢 Ativo</option>
                            <option value="inativo" {{ $usuario->status === 'inativo' ? 'selected' : '' }}>🔴 Inativo/Bloqueado</option>
                        </select>
                    </div>
                    <div class="form-hint">Define se o usuário pode interagir na plataforma.</div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="margin-right: -4px;">
                            <path d="M7.707 9.293a1 1 0 010 1.414L5.414 12l2.293 2.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zM12.293 9.293a1 1 0 00-1.414 1.414L14.586 14l-2.293 2.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 00-1.414 0zM10 13a1 1 0 100-2 1 1 0 000 2z"/>
                        </svg>
                        Salvar Alterações
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