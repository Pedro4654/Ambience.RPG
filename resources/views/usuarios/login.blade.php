@extends('layout.app')

@section('content')

<style>
/* Body com gradiente verde + imagem */
body {
    background: 
        linear-gradient(to bottom, rgba(5, 46, 22, 0.90), rgba(6, 78, 59, 0.85)),
        url('{{ asset("images/1.jpg") }}');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    min-height: 100vh;
}

/* Container centralizado */
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 90vh;
    padding: 2rem 1rem;
}

.login-card {
    background: rgba(17, 24, 39, 0.95); /* DARK MODE: Preto/cinza escuro */
    backdrop-filter: blur(10px);
    padding: 3rem;
    border-radius: 1rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
    width: 100%;
    max-width: 500px;
}

.login-title {
    text-align: center;
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 2.5rem;
    color: #f9fafb; /* DARK MODE: Branco */
}

/* Inputs */
.form-group {
    position: relative;
    margin-bottom: 1.5rem;
}

.form-input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    background: #1f2937; /* DARK MODE: Cinza escuro */
    border: 1px solid #374151; /* DARK MODE: Borda cinza */
    border-radius: 0.5rem;
    font-size: 1rem;
    color: #f9fafb; /* DARK MODE: Texto branco */
    transition: all 0.2s;
}

.form-input::placeholder {
    color: #9ca3af; /* DARK MODE: Placeholder cinza claro */
}

.form-input:focus {
    outline: none;
    box-shadow: 0 0 0 2px #10b981;
    border-color: #10b981;
    background: #111827; /* DARK MODE: Fundo mais escuro no focus */
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1.25rem;
    height: 1.25rem;
    color: #9ca3af; /* DARK MODE: Ícone cinza claro */
    pointer-events: none;
}

/* Botão de visualizar senha */
.password-toggle-btn {
    position: absolute;
    right: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    border-radius: 0.25rem;
    z-index: 10;
}

.password-toggle-btn:hover {
    background: rgba(16, 185, 129, 0.1);
}

.eye-icon {
    width: 1.25rem;
    height: 1.25rem;
    color: #9ca3af; /* DARK MODE: Ícone cinza claro */
    transition: color 0.2s;
}

.password-toggle-btn:hover .eye-icon {
    color: #10b981;
}

/* Link "Esqueci minha senha" */
.forgot-link {
    display: block;
    text-align: right;
    font-size: 0.875rem;
    color: #059669; /* DARK MODE: Cinza claro */
    text-decoration: none;
    margin-top: 0.5rem;
    transition: color 0.2s;
}

.forgot-link:hover {
    color: #10b981;
}

/* Botão de login */
.login-button {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(to right, #22c55e, #16a34a);
    color: white;
    font-weight: 600;
    font-size: 1.125rem;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.2s;
    margin-top: 1.5rem;
}

.login-button:hover {
    background: linear-gradient(to right, #16a34a, #15803d);
    transform: scale(1.02);
}

/* Link de cadastro embaixo do login */
.register-text {
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.875rem;
    color: #9ca3af; /* DARK MODE: Cinza claro */
}

.register-text a {
    color: #10b981;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}

.register-text a:hover {
    color: #22c55e;
    text-decoration: underline;
}
</style>
 <div class="login-container">
    <div class="login-card">
        <h1 class="login-title">Entrar</h1>

       <form method="POST" action="{{ route('usuarios.login') }}">
            @csrf
            
            <!-- Email -->
            <div class="form-group">
                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                </svg>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    required
                    class="form-input"
                    placeholder="Email"
                >
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Senha -->
            <div class="form-group">
                <div style="position: relative;">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="form-input"
                        placeholder="Password"
                        style="padding-right: 3rem;"
                    >
                    <button 
                        type="button" 
                        id="toggle-password-login" 
                        class="password-toggle-btn"
                        aria-label="Mostrar senha"
                    >
                        <svg id="eye-icon-login" class="eye-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eye-off-icon-login" class="eye-icon" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                    <a href="{{ route('usuarios.forgot.form') }}" class="forgot-link">
        Esqueci minha senha
            </a>

                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="login-button">ENTRAR</button>
        </form>
        
        <p class="register-text">
            Não tem conta? <a href="{{ route('usuarios.create') }}">Cadastre-se</a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const togglePasswordBtn = document.getElementById('toggle-password-login');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon-login');
    const eyeOffIcon = document.getElementById('eye-off-icon-login');

    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
                togglePasswordBtn.setAttribute('aria-label', 'Ocultar senha');
            } else {
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
                togglePasswordBtn.setAttribute('aria-label', 'Mostrar senha');
            }
        });
    }
});
</script>

@endsection
