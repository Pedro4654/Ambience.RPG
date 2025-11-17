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

/* Container */
.forgot-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 2rem 1rem;
}

.forgot-card {
    background: rgba(17, 24, 39, 0.95);
    backdrop-filter: blur(10px);
    padding: 2.5rem;
    border-radius: 1rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
    width: 100%;
    max-width: 450px;
}

.forgot-title {
    text-align: center;
    font-size: 1.75rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #f9fafb;
}

.forgot-subtitle {
    text-align: center;
    font-size: 0.875rem;
    color: #9ca3af;
    margin-bottom: 2rem;
    line-height: 1.5;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #d1d5db;
    margin-bottom: 0.5rem;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    background: #1f2937;
    border: 1px solid #374151;
    border-radius: 0.5rem;
    font-size: 1rem;
    color: #f9fafb;
    transition: all 0.2s;
}

.form-input::placeholder {
    color: #9ca3af;
}

.form-input:focus {
    outline: none;
    box-shadow: 0 0 0 2px #10b981;
    border-color: #10b981;
    background: #111827;
}

.submit-button {
    width: 100%;
    padding: 0.875rem 1rem;
    background: linear-gradient(to right, #22c55e, #16a34a);
    color: white;
    font-weight: 600;
    font-size: 1rem;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.2s;
}

.submit-button:hover {
    background: linear-gradient(to right, #16a34a, #15803d);
    transform: scale(1.02);
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.875rem;
    color: #9ca3af;
}

.back-link a {
    color: #10b981;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}

.back-link a:hover {
    color: #22c55e;
    text-decoration: underline;
}

/* Alertas */
.alert {
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid #10b981;
    color: #10b981;
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid #ef4444;
    color: #ef4444;
}

/* Info box */
.info-box {
    background: #1f2937;
    border: 1px solid #374151;
    border-left: 4px solid #10b981;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.info-box p {
    margin: 0;
    font-size: 0.875rem;
    color: #d1d5db;
    line-height: 1.6;
}

.info-box strong {
    color: #10b981;
}

small.error {
    display: block;
    margin-top: 0.375rem;
    font-size: 0.875rem;
    color: #ef4444;
}
</style>

<div class="forgot-container">
    <div class="forgot-card">
        <h1 class="forgot-title">🔒 Esqueci Minha Senha</h1>
        <p class="forgot-subtitle">Digite seu email para receber um código de 6 dígitos<br>para recuperar sua senha.</p>

        @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            ✗ {{ session('error') }}
        </div>
        @endif

        <div class="info-box">
            <p><strong>💡 Como funciona:</strong></p>
            <p>1. Digite seu email cadastrado</p>
            <p>2. Receba um código de 6 dígitos por email</p>
            <p>3. Use o código para redefinir sua senha</p>
        </div>

        <form method="POST" action="{{ route('usuarios.forgot.send') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    class="form-input"
                    placeholder="seuemail@exemplo.com"
                >
                @error('email')
                <small class="error">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="submit-button">Enviar Código</button>
        </form>

        <p class="back-link">
            <a href="{{ route('usuarios.login') }}">Voltar para o Login</a>
        </p>
    </div>
</div>

@endsection
    