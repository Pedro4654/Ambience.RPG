@extends('layout.app')

@section('content')

<style>
/* Fundo premium com gradiente e imagem */
body {
    background: 
        linear-gradient(to bottom, rgba(5, 46, 22, 0.92), rgba(6, 78, 59, 0.88)),
        url('{{ asset("images/1.jpg") }}');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    min-height: 100vh;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Animação de entrada */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulseSuccess {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
    }
    50% {
        box-shadow: 0 0 0 10px rgba(34, 197, 94, 0);
    }
}

/* Container principal */
.reset-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 2rem 1rem;
    animation: fadeInUp 0.6s ease-out;
}

.reset-card {
    background: rgba(17, 24, 39, 0.96);
    backdrop-filter: blur(20px);
    padding: 3rem 2.5rem;
    border-radius: 1.25rem;
    box-shadow: 
        0 20px 60px rgba(0, 0, 0, 0.6),
        0 0 0 1px rgba(255, 255, 255, 0.1);
    width: 100%;
    max-width: 480px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    position: relative;
    overflow: hidden;
}

/* Decoração do cartão */
.reset-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #22c55e, #16a34a, #10b981);
}

/* Cabeçalho */
.reset-header {
    text-align: center;
    margin-bottom: 2rem;
    position: relative;
}

.verification-badge {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    animation: pulseSuccess 2s infinite;
    box-shadow: 0 10px 30px rgba(34, 197, 94, 0.3);
}

.verification-badge svg {
    width: 28px;
    height: 28px;
    color: white;
}

.reset-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #f9fafb;
    margin-bottom: 0.75rem;
    letter-spacing: -0.025em;
}

.reset-subtitle {
    font-size: 0.9375rem;
    color: #9ca3af;
    line-height: 1.6;
    margin-bottom: 0.5rem;
}

.verified-email {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(34, 197, 94, 0.15);
    border: 1px solid rgba(34, 197, 94, 0.3);
    border-radius: 0.75rem;
    padding: 0.875rem 1.25rem;
    margin: 1rem 0;
    color: #22c55e;
    font-weight: 600;
}

.verified-email svg {
    width: 18px;
    height: 18px;
}

/* Progress steps */
.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 2.5rem 0;
    position: relative;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 16px;
    left: 30px;
    right: 30px;
    height: 2px;
    background: rgba(255, 255, 255, 0.1);
    z-index: 1;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
}

.step-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #1f2937;
    border: 2px solid rgba(255, 255, 255, 0.1);
    color: #9ca3af;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}

.step-label {
    font-size: 0.75rem;
    color: #9ca3af;
    font-weight: 500;
}

.progress-step.completed .step-icon {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    border-color: #22c55e;
    color: white;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.progress-step.active .step-icon {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border-color: #3b82f6;
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

/* Formulário */
.form-group {
    margin-bottom: 1.75rem;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #d1d5db;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-input-wrapper {
    position: relative;
}

.form-input {
    width: 100%;
    padding: 1rem 1.25rem;
    background: rgba(31, 41, 55, 0.8);
    border: 2px solid #374151;
    border-radius: 0.75rem;
    font-size: 1rem;
    color: #f9fafb;
    transition: all 0.2s;
    padding-right: 3rem;
}

.form-input::placeholder {
    color: #6b7280;
}

.form-input:focus {
    outline: none;
    border-color: #22c55e;
    background: rgba(17, 24, 39, 0.8);
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

.form-input-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    cursor: pointer;
    transition: color 0.2s;
}

.form-input-icon:hover {
    color: #22c55e;
}

/* Strength meter */
.strength-meter {
    margin-top: 0.75rem;
}

.strength-labels {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.375rem;
}

.strength-labels span {
    font-size: 0.75rem;
    font-weight: 500;
}

.strength-bar {
    height: 6px;
    background: #374151;
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.strength-fill {
    height: 100%;
    width: 0%;
    border-radius: 3px;
    transition: all 0.3s ease;
}

.strength-hint {
    font-size: 0.75rem;
    color: #9ca3af;
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

/* Botão de envio */
.submit-button {
    width: 100%;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: white;
    font-weight: 600;
    font-size: 1rem;
    border: none;
    border-radius: 0.75rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    margin: 2rem 0 1.5rem;
    box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
}

.submit-button:hover {
    background: linear-gradient(135deg, #16a34a, #15803d);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(34, 197, 94, 0.4);
}

.submit-button:active {
    transform: translateY(0);
}

.submit-button:disabled {
    background: #4b5563;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Security tips */
.security-tips {
    background: rgba(31, 41, 55, 0.6);
    border: 1px solid rgba(55, 65, 81, 0.5);
    border-radius: 0.75rem;
    padding: 1.5rem;
    margin-top: 2rem;
}

.security-tips h6 {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #22c55e;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 0.9375rem;
}

.security-tips ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.security-tips li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #d1d5db;
    font-size: 0.875rem;
    margin-bottom: 0.75rem;
}

.security-tips li svg {
    width: 12px;
    height: 12px;
    color: #22c55e;
}

/* Back link */
.back-link {
    text-align: center;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.back-link a {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #9ca3af;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.back-link a:hover {
    color: #22c55e;
}

/* Alertas */
.alert {
    padding: 1rem 1.25rem;
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    border: 1px solid;
}

.alert svg {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.alert-success {
    background: rgba(34, 197, 94, 0.1);
    border-color: rgba(34, 197, 94, 0.3);
    color: #22c55e;
}

.alert-danger {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

/* Tooltip */
.tooltip {
    position: relative;
    display: inline-block;
    cursor: help;
}

.tooltip .tooltip-text {
    visibility: hidden;
    width: 200px;
    background: rgba(17, 24, 39, 0.95);
    color: #f9fafb;
    text-align: center;
    padding: 0.75rem;
    border-radius: 0.5rem;
    position: absolute;
    z-index: 100;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 0.75rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}

/* Responsividade */
@media (max-width: 640px) {
    .reset-card {
        padding: 2rem 1.5rem;
        margin: 1rem;
    }
    
    .progress-steps::before {
        left: 20px;
        right: 20px;
    }
}
</style>

<div class="reset-container">
    <div class="reset-card">
        <div class="reset-header">
            <div class="verification-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h1 class="reset-title">Definir Nova Senha</h1>
            <p class="reset-subtitle">Sua identidade foi verificada com sucesso</p>
            
            <div class="verified-email">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="4"></circle>
                    <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"></path>
                </svg>
                <span>{{ session('verified_email') }}</span>
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="progress-step completed">
                <div class="step-icon">1</div>
                <span class="step-label">Email</span>
            </div>
            <div class="progress-step completed">
                <div class="step-icon">2</div>
                <span class="step-label">Código</span>
            </div>
            <div class="progress-step active">
                <div class="step-icon">3</div>
                <span class="step-label">Nova Senha</span>
            </div>
        </div>

        <!-- Alertas -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <div>
                    <strong>Corrija os seguintes erros:</strong>
                    <ul class="mb-0 mt-1 ps-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Formulário -->
        <form method="POST" action="{{ route('usuarios.reset.password') }}" id="resetForm">
            @csrf
            
            <input type="hidden" name="email" value="{{ session('verified_email') }}">
            <input type="hidden" name="token" value="{{ session('verified_token') }}">
            
            <!-- Nova Senha -->
            <div class="form-group">
                <label for="password">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Nova Senha
                    <span class="tooltip">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        <span class="tooltip-text">Use pelo menos 8 caracteres incluindo letras, números e símbolos para maior segurança</span>
                    </span>
                </label>
                <div class="form-input-wrapper">
                    <input type="password" 
                           class="form-input @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="Digite sua nova senha"
                           minlength="6"
                           required
                           autocomplete="new-password"
                           autofocus>
                    <svg class="form-input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="togglePassword">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
                
                <!-- Strength Meter -->
                <div class="strength-meter">
                    <div class="strength-labels">
                        <span id="strengthText">Força da senha</span>
                        <span id="strengthPercent">0%</span>
                    </div>
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                    <div class="strength-hint">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <span id="strengthHint">Comece a digitar sua senha</span>
                    </div>
                </div>
            </div>

            <!-- Confirmar Senha -->
            <div class="form-group">
                <label for="password_confirmation">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    Confirmar Nova Senha
                </label>
                <div class="form-input-wrapper">
                    <input type="password" 
                           class="form-input" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           placeholder="Digite novamente a senha"
                           minlength="6"
                           required
                           autocomplete="new-password">
                    <svg class="form-input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="togglePasswordConfirmation">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
                <div id="passwordMatch" class="mt-2"></div>
            </div>

            <!-- Botão de Envio -->
            <button type="submit" class="submit-button" id="submitBtn" disabled>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                </svg>
                <span id="buttonText">Atualizar Senha</span>
            </button>
        </form>

        <!-- Dicas de Segurança -->
        <div class="security-tips">
            <h6>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                Melhores práticas de segurança
            </h6>
            <ul>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Use uma senha única para esta conta
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Evite informações pessoais ou sequências
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Considere usar um gerenciador de senhas
                </li>
                <li>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Ative a autenticação de dois fatores
                </li>
            </ul>
        </div>

        <!-- Link de Voltar -->
        <div class="back-link">
            <a href="{{ route('usuarios.login') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Voltar para o Login
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const togglePasswordConfirmationBtn = document.getElementById('togglePasswordConfirmation');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    const strengthPercent = document.getElementById('strengthPercent');
    const strengthHint = document.getElementById('strengthHint');
    const passwordMatch = document.getElementById('passwordMatch');
    const submitBtn = document.getElementById('submitBtn');
    const buttonText = document.getElementById('buttonText');
    const form = document.getElementById('resetForm');
    
    let passwordStrength = 0;
    let passwordsMatch = false;

    // Toggle para mostrar/ocultar senha
    [togglePasswordBtn, togglePasswordConfirmationBtn].forEach((btn, index) => {
        btn.addEventListener('click', function() {
            const input = index === 0 ? passwordInput : passwordConfirmInput;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            
            // Trocar o ícone do olho
            if (type === 'text') {
                this.innerHTML = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>`;
            } else {
                this.innerHTML = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>`;
            }
        });
    });

    // Analisar força da senha
    function analyzePasswordStrength(password) {
        let score = 0;
        
        // Comprimento
        if (password.length >= 8) score += 20;
        if (password.length >= 12) score += 10;
        
        // Complexidade
        if (/[a-z]/.test(password)) score += 10;
        if (/[A-Z]/.test(password)) score += 15;
        if (/[0-9]/.test(password)) score += 15;
        if (/[^a-zA-Z0-9]/.test(password)) score += 20;
        
        // Padrões comuns (penalizações)
        if (/password|123456|qwerty/i.test(password)) score = Math.max(score - 30, 0);
        if (/(.)\1{2,}/.test(password)) score = Math.max(score - 15, 0);
        
        return Math.min(score, 100);
    }

    // Atualizar indicador de força
    function updateStrengthIndicator(password) {
        passwordStrength = analyzePasswordStrength(password);
        
        // Atualizar barra de progresso
        strengthFill.style.width = `${passwordStrength}%`;
        
        // Atualizar cores e texto
        let color, text, hint;
        
        if (passwordStrength === 0) {
            color = '#374151';
            text = 'Força da senha';
            hint = 'Comece a digitar sua senha';
        } else if (passwordStrength < 30) {
            color = '#ef4444';
            text = 'Muito fraca';
            hint = 'Adicione mais caracteres e tipos diferentes';
        } else if (passwordStrength < 60) {
            color = '#f59e0b';
            text = 'Fraca';
            hint = 'Adicione letras maiúsculas e símbolos';
        } else if (passwordStrength < 80) {
            color = '#3b82f6';
            text = 'Boa';
            hint = 'Quase lá! Pode melhorar um pouco mais';
        } else {
            color = '#22c55e';
            text = 'Excelente';
            hint = 'Senha forte e segura!';
        }
        
        strengthFill.style.background = color;
        strengthText.textContent = text;
        strengthText.style.color = color;
        strengthPercent.textContent = `${passwordStrength}%`;
        strengthPercent.style.color = color;
        strengthHint.textContent = hint;
        
        // Validar formulário
        validateForm();
    }

    // Verificar se senhas coincidem
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmation = passwordConfirmInput.value;
        
        if (!confirmation) {
            passwordMatch.innerHTML = '';
            passwordsMatch = false;
            return;
        }
        
        if (password === confirmation) {
            passwordMatch.innerHTML = `
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #22c55e; font-size: 0.875rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Senhas coincidem</span>
                </div>
            `;
            passwordsMatch = true;
        } else {
            passwordMatch.innerHTML = `
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #ef4444; font-size: 0.875rem;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    <span>Senhas não coincidem</span>
                </div>
            `;
            passwordsMatch = false;
        }
        
        // Validar formulário
        validateForm();
    }

    // Validar formulário completo
    function validateForm() {
        const password = passwordInput.value;
        const confirmation = passwordConfirmInput.value;
        
        // Verificar requisitos mínimos
        const meetsLength = password.length >= 6;
        const meetsStrength = passwordStrength >= 30;
        const isConfirmed = confirmation && passwordsMatch;
        
        if (meetsLength && meetsStrength && isConfirmed) {
            submitBtn.disabled = false;
            submitBtn.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
            buttonText.textContent = 'Atualizar Senha';
        } else {
            submitBtn.disabled = true;
            submitBtn.style.background = '#4b5563';
            
            if (!meetsLength) {
                buttonText.textContent = 'Senha muito curta';
            } else if (!meetsStrength) {
                buttonText.textContent = 'Senha muito fraca';
            } else if (!isConfirmed) {
                buttonText.textContent = 'Senhas não coincidem';
            }
        }
    }

    // Event listeners
    passwordInput.addEventListener('input', function() {
        updateStrengthIndicator(this.value);
        checkPasswordMatch();
    });
    
    passwordConfirmInput.addEventListener('input', checkPasswordMatch);
    
    // Feedback visual ao enviar
    form.addEventListener('submit', function(e) {
        if (!submitBtn.disabled) {
            submitBtn.innerHTML = `
                <svg class="spinner" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="2" x2="12" y2="6"></line>
                    <line x1="12" y1="18" x2="12" y2="22"></line>
                    <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                    <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                    <line x1="2" y1="12" x2="6" y2="12"></line>
                    <line x1="18" y1="12" x2="22" y2="12"></line>
                    <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                    <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                </svg>
                <span>Processando...</span>
            `;
            submitBtn.disabled = true;
        }
    });
    
    // Inicializar validação
    updateStrengthIndicator('');
    checkPasswordMatch();
});
</script>

<style>
.spinner {
    animation: spin 1s linear infinite;
    margin-right: 0.5rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection