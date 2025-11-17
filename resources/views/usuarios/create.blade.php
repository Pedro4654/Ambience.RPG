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
.register-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 90vh;
    padding: 2rem 1rem;
}

.register-card {
    background: rgba(17, 24, 39, 0.95);
    backdrop-filter: blur(10px);
    padding: 2.5rem;
    border-radius: 1rem;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
    width: 100%;
    max-width: 800px;
}

.register-title {
    text-align: center;
    font-size: 1.75rem;
    font-weight: bold;
    margin-bottom: 2rem;
    color: #f9fafb;
}

/* Grid de campos */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}

.form-grid-full {
    grid-column: 1 / -1;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* Inputs */
.form-group {
    margin-bottom: 0;
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

.input-warn {
    border: 2px solid #ef4444 !important;
    background: #1f2937;
}

textarea.form-input {
    min-height: 100px;
    resize: vertical;
}

.error-message, small[style*="color: red"], small[style*="color: #ef4444"] {
    display: block;
    margin-top: 0.375rem;
    font-size: 0.875rem;
    color: #ef4444;
}

.field-info {
    display: block;
    margin-top: 0.375rem;
    font-size: 0.75rem;
    color: #9ca3af;
}

/* Date Grid estilo Google */
.date-grid {
    display: grid;
    grid-template-columns: 1fr 2fr 1.5fr;
    gap: 0.75rem;
}

.date-select {
    padding: 0.75rem 1rem !important;
}

.date-select::placeholder {
    color: #9ca3af;
    font-weight: 400;
}

input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}

select.date-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: none !important;
    padding-right: 1rem !important;
    cursor: pointer;
    color: #f9fafb;
}

select.date-select option[value=""] {
    display: none;
}

select.date-select:invalid,
select.date-select[value=""] {
    color: #9ca3af;
}

select.date-select:valid {
    color: #f9fafb;
}

select.date-select option {
    background: #1f2937;
    color: #f9fafb;
    font-weight: 500;
    padding: 0.5rem;
}

select.date-select option:hover {
    background: #374151;
}

select.date-select option:checked {
    background: #10b981;
    color: white;
    font-weight: 600;
}

select.date-select option[value=""] {
    color: #9ca3af;
    font-weight: 400;
}

@media (max-width: 640px) {
    .date-grid {
        grid-template-columns: 1fr;
    }
}

/* Upload de arquivo */
.file-upload-wrapper {
    position: relative;
    width: 100%;
}

.file-input-hidden {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
    pointer-events: none;
}

.file-upload-button {
    display: inline-flex !important;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 0.75rem !important;
    padding: 0.75rem 1rem !important;
    background: #1f2937 !important;
    border: 1px solid #374151 !important;
    border-radius: 0.5rem !important;
    cursor: pointer !important;
    transition: all 0.2s !important;
    font-size: 0.875rem !important;
    color: #9ca3af !important;
    font-weight: 500 !important;
    width: 100% !important;
    box-sizing: border-box !important;
}

.file-upload-button:hover {
    background: #374151 !important;
    border-color: #10b981 !important;
}

.upload-icon {
    width: 1.5rem !important;
    height: 1.5rem !important;
    color: #10b981 !important;
    flex-shrink: 0 !important;
    display: inline-block !important;
}

#file-name {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    flex: 1;
    display: inline-block !important;
}

/* Checkbox termos */
.terms-box {
    background: #1f2937;
    border: 1px solid #374151;
    border-radius: 0.5rem;
    padding: 1rem;
    margin: 1.5rem 0;
}

.terms-box label {
    display: flex;
    align-items: flex-start;
    cursor: pointer;
    gap: 0.75rem;
    color: #d1d5db;
}

.terms-box input[type="checkbox"] {
    margin-top: 0.25rem;
    width: 1.125rem;
    height: 1.125rem;
    cursor: pointer;
    accent-color: #10b981;
}

.terms-box a {
    color: #10b981;
    text-decoration: underline;
    font-weight: 500;
}

.terms-box a:hover {
    color: #22c55e;
}

#terms-status {
    display: block;
    margin-top: 0.75rem;
    font-size: 0.75rem;
    color: #9ca3af;
}

/* Botão */
.register-button {
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

.register-button:hover:not(:disabled) {
    background: linear-gradient(to right, #16a34a, #15803d);
    transform: scale(1.02);
}

.register-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Link de login */
.login-text {
    text-align: center;
    margin-top: 1.5rem;
    font-size: 0.875rem;
    color: #9ca3af;
}

.login-text a {
    color: #10b981;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}

.login-text a:hover {
    color: #22c55e;
    text-decoration: underline;
}

/* Modais */
.terms-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
}

.terms-modal-content {
    background-color: #1f2937;
    margin: 3% auto;
    width: 90%;
    max-width: 800px;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
    display: flex;
    flex-direction: column;
    max-height: 85vh;
}

.terms-modal-header {
    padding: 20px 30px;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.terms-modal-header h2 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
}

.terms-close {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    font-size: 32px;
    font-weight: bold;
    cursor: pointer;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s;
}

.terms-close:hover {
    background: rgba(255,255,255,0.3);
}

.progress-container {
    background: #374151;
    height: 6px;
    margin: 0;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #10b981 0%, #059669 100%);
    width: 0%;
    transition: width 0.1s ease;
}

.progress-text {
    display: block;
    padding: 8px 30px;
    color: #9ca3af;
    font-size: 13px;
    background: #111827;
    border-bottom: 1px solid #374151;
}

.terms-modal-body {
    padding: 30px;
    overflow-y: auto;
    flex: 1;
    line-height: 1.8;
    color: #d1d5db;
    background: #1f2937;
}

.terms-modal-body h3 {
    color: #10b981;
    margin-top: 25px;
    margin-bottom: 15px;
    font-size: 20px;
    font-weight: 600;
}

.terms-modal-body p {
    margin-bottom: 15px;
    color: #d1d5db;
}

.terms-modal-body ul, .terms-modal-body ol {
    margin-left: 20px;
    margin-bottom: 15px;
}

.terms-modal-body strong {
    color: #f9fafb;
}

.terms-modal-footer {
    padding: 20px 30px;
    background: #111827;
    border-radius: 0 0 12px 12px;
    display: flex;
    justify-content: flex-end;
    border-top: 1px solid #374151;
}

.terms-btn-confirm {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.terms-btn-confirm:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
}

.terms-btn-confirm:disabled {
    background: #4b5563;
    cursor: not-allowed;
    opacity: 0.6;
}
</style>

<div class="register-container">
    <div class="register-card">
        <h1 class="register-title">Criar Conta</h1>

        <form method="POST" action="{{ route('usuarios.store') }}" enctype="multipart/form-data" id="form-cadastro">
            @csrf

            <div class="form-grid">
                <!-- Username -->
                <div class="form-group">
                    <label for="username">Nome de Usuário *</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required class="form-input">
                    <small id="username-warning" style="display:none;color:#ef4444;font-size:0.875rem;">Conteúdo inapropriado detectado</small>
                    @error('username')
                    <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-input">
                    @error('email')
                    <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Senha (SEMPRE VISÍVEL - type="text") -->
                <div class="form-group">
                    <label for="password">Senha *</label>
                    <input 
                        type="text" 
                        id="password" 
                        name="password" 
                        required 
                        class="form-input"
                        autocomplete="new-password"
                    >
                    <small id="password-strength" style="display:block;margin-top:0.375rem;font-size:0.75rem;color:#9ca3af;">Mínimo 8 caracteres com pelo menos 1 letra</small>
                    @error('password')
                    <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Confirmar Senha (SEMPRE VISÍVEL - type="text") -->
                <div class="form-group">
                    <label for="password_confirmation">Confirmar Senha *</label>
                    <input 
                        type="text" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required 
                        class="form-input"
                        autocomplete="new-password"
                    >
                    <small id="password-match" style="display:none;margin-top:0.375rem;font-size:0.75rem;"></small>
                    @error('password_confirmation')
                    <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Data de Nascimento -->
                <div class="form-group">
                    <label>Data de Nascimento *</label>
                    <div class="date-grid">
                        <input 
                            type="number" 
                            id="birth_day" 
                            name="birth_day" 
                            placeholder="Dia" 
                            min="1" 
                            max="31"
                            class="form-input date-select"
                            required
                        >
                        <select 
                            id="birth_month" 
                            name="birth_month" 
                            class="form-input date-select"
                            required
                        >
                            <option value="">Mês</option>
                            <option value="1">Janeiro</option>
                            <option value="2">Fevereiro</option>
                            <option value="3">Março</option>
                            <option value="4">Abril</option>
                            <option value="5">Maio</option>
                            <option value="6">Junho</option>
                            <option value="7">Julho</option>
                            <option value="8">Agosto</option>
                            <option value="9">Setembro</option>
                            <option value="10">Outubro</option>
                            <option value="11">Novembro</option>
                            <option value="12">Dezembro</option>
                        </select>
                        <input 
                            type="number" 
                            id="birth_year" 
                            name="birth_year" 
                            placeholder="Ano" 
                            min="1900" 
                            max="2025"
                            class="form-input date-select"
                            required
                        >
                    </div>
                    <input type="hidden" id="data_de_nascimento" name="data_de_nascimento" value="">
                    @error('data_de_nascimento')
                    <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Avatar -->
                <div class="form-group">
                    <label for="avatar">Foto de Perfil</label>
                    <div class="file-upload-wrapper">
                        <input 
                            type="file" 
                            id="avatar" 
                            name="avatar" 
                            accept="image/*" 
                            class="file-input-hidden"
                        >
                        <label for="avatar" class="file-upload-button">
                            <svg class="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <span id="file-name">Escolher arquivo</span>
                        </label>
                    </div>
                    <small class="field-info">JPG, PNG, GIF (máx. 2MB)</small>
                    @error('avatar')
                    <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Bio -->
                <div class="form-group form-grid-full">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" class="form-input">{{ old('bio') }}</textarea>
                    <small id="bio-warning" style="display:none;color:#ef4444;font-size:0.875rem;">Conteúdo inapropriado detectado</small>
                    @error('bio')
                    <small style="color: #ef4444;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Termos -->
            <div class="terms-box">
                <label>
                    <input type="checkbox" id="accept-terms" name="accept_terms" required disabled>
                    <span style="font-size: 0.875rem;">
                        Eu li e concordo com os 
                        <a href="#" id="open-terms">Termos de Serviço</a>
                        e a 
                        <a href="#" id="open-privacy">Política de Privacidade</a>
                    </span>
                </label>
                <small id="terms-status">⚠️ Você precisa ler os documentos antes de continuar</small>
                @error('accept_terms')
                <small style="color: #ef4444; display: block; margin-top: 0.5rem;">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" id="submit-btn" disabled class="register-button">CRIAR CONTA</button>
        </form>
        
        <p class="login-text">
            Já tem uma conta? <a href="{{ route('usuarios.login') }}">Entrar</a>
        </p>
    </div>
</div>

<!-- Modal de Termos de Serviço -->
<div id="terms-modal" class="terms-modal">
    <div class="terms-modal-content">
        <div class="terms-modal-header">
            <h2>📜 Termos de Serviço</h2>
            <button class="terms-close" data-modal="terms-modal">&times;</button>
        </div>
        <div class="progress-container">
            <div id="terms-progress" class="progress-bar"></div>
        </div>
        <span id="terms-progress-text" class="progress-text">0% concluído</span>
        <div id="terms-body" class="terms-modal-body">
            <h3>1. Aceitação dos Termos</h3>
            <p>Ao acessar e usar o Ambience RPG, você concorda em cumprir estes Termos de Serviço. Se você não concordar com alguma parte destes termos, não deverá usar nosso serviço.</p>

            <h3>2. Descrição do Serviço</h3>
            <p>O Ambience RPG é uma plataforma online dedicada a jogos de RPG por texto, onde os usuários podem criar personagens, participar de campanhas e interagir com uma comunidade de jogadores.</p>

            <h3>3. Conta de Usuário</h3>
            <p><strong>Responsabilidade:</strong> Você é responsável por manter a confidencialidade de sua conta e senha.</p>
            <p><strong>Informações Precisas:</strong> Você concorda em fornecer informações precisas e atualizadas durante o registro.</p>

            <h3>4. Conduta do Usuário</h3>
            <p>Ao usar o Ambience RPG, você concorda em <strong>NÃO</strong>:</p>
            <ul>
                <li>Usar linguagem ofensiva, discriminatória ou assediadora</li>
                <li>Compartilhar conteúdo ilegal, explícito ou inadequado</li>
                <li>Fazer spam, trolling ou qualquer comportamento disruptivo</li>
                <li>Tentar hackear, explorar bugs ou comprometer a segurança da plataforma</li>
                <li>Falsificar identidade ou se passar por outros usuários</li>
                <li>Usar bots, scripts automatizados ou ferramentas de terceiros não autorizadas</li>
            </ul>

            <h3>5. Conteúdo do Usuário</h3>
            <p><strong>Propriedade:</strong> Você mantém os direitos sobre o conteúdo que cria (personagens, histórias, etc.).</p>
            <p><strong>Licença:</strong> Ao postar conteúdo, você nos concede uma licença não exclusiva para exibir, armazenar e distribuir esse conteúdo na plataforma.</p>
            <p><strong>Moderação:</strong> Reservamos o direito de remover conteúdo que viole estes termos ou nossas diretrizes da comunidade.</p>

            <h3>6. Suspensão e Banimento</h3>
            <p>Podemos suspender ou encerrar sua conta se você violar estes Termos de Serviço, sem aviso prévio.</p>

            <h3>7. Limitação de Responsabilidade</h3>
            <p>O Ambience RPG é fornecido "como está". Não garantimos que o serviço estará sempre disponível, livre de erros ou seguro contra ataques.</p>

            <h3>8. Modificações nos Termos</h3>
            <p>Podemos atualizar estes Termos de Serviço a qualquer momento. Você será notificado sobre mudanças significativas.</p>

            <h3>9. Lei Aplicável</h3>
            <p>Estes termos são regidos pelas leis do Brasil.</p>

            <h3>10. Contato</h3>
            <p>Se você tiver dúvidas sobre estes Termos de Serviço, entre em contato conosco através do email: <strong>suporte@ambiencerpg.com</strong></p>
        </div>
        <div class="terms-modal-footer">
            <button id="terms-confirm-btn" class="terms-btn-confirm" disabled>Li e Aceito</button>
        </div>
    </div>
</div>

<!-- Modal de Política de Privacidade -->
<div id="privacy-modal" class="terms-modal">
    <div class="terms-modal-content">
        <div class="terms-modal-header">
            <h2>🔒 Política de Privacidade</h2>
            <button class="terms-close" data-modal="privacy-modal">&times;</button>
        </div>
        <div class="progress-container">
            <div id="privacy-progress" class="progress-bar"></div>
        </div>
        <span id="privacy-progress-text" class="progress-text">0% concluído</span>
        <div id="privacy-body" class="terms-modal-body">
            <h3>1. Informações que Coletamos</h3>
            <p><strong>Informações de Registro:</strong></p>
            <ul>
                <li>Nome de usuário</li>
                <li>Endereço de email</li>
                <li>Data de nascimento</li>
                <li>Avatar/foto de perfil (opcional)</li>
            </ul>
            <p><strong>Informações de Uso:</strong></p>
            <ul>
                <li>Endereço IP</li>
                <li>Tipo de navegador</li>
                <li>Páginas visitadas</li>
                <li>Tempo de uso da plataforma</li>
            </ul>

            <h3>2. Como Usamos Suas Informações</h3>
            <p>Usamos suas informações para:</p>
            <ul>
                <li>Fornecer e melhorar nossos serviços</li>
                <li>Personalizar sua experiência</li>
                <li>Comunicar atualizações e notificações importantes</li>
                <li>Garantir a segurança da plataforma</li>
                <li>Cumprir obrigações legais</li>
            </ul>

            <h3>3. Compartilhamento de Informações</h3>
            <p><strong>NÃO vendemos</strong> suas informações pessoais a terceiros.</p>
            <p>Podemos compartilhar informações com:</p>
            <ul>
                <li>Provedores de serviços (hospedagem, email)</li>
                <li>Autoridades legais, quando exigido por lei</li>
            </ul>

            <h3>4. Cookies</h3>
            <p>Usamos cookies para melhorar sua experiência, manter sua sessão ativa e analisar o uso da plataforma.</p>

            <h3>5. Segurança</h3>
            <p>Implementamos medidas de segurança para proteger suas informações, incluindo criptografia de senhas e proteção contra acesso não autorizado.</p>

            <h3>6. Seus Direitos</h3>
            <p>Você tem o direito de:</p>
            <ul>
                <li><strong>Acessar</strong> suas informações pessoais</li>
                <li><strong>Corrigir</strong> dados incorretos</li>
                <li><strong>Excluir</strong> sua conta e dados associados</li>
                <li><strong>Exportar</strong> seus dados</li>
            </ul>

            <h3>7. Alterações nesta Política</h3>
            <p>Podemos atualizar esta Política de Privacidade periodicamente. Notificaremos você sobre mudanças significativas.</p>

            <h3>8. Contato</h3>
            <p>Para questões sobre esta Política de Privacidade ou para exercer seus direitos, entre em contato: <strong>privacidade@ambiencerpg.com</strong></p>

            <p><strong>Última atualização:</strong> Novembro de 2025</p>
        </div>
        <div class="terms-modal-footer">
            <button id="privacy-confirm-btn" class="terms-btn-confirm" disabled>Li e Aceito</button>
        </div>
    </div>
</div>

<script src="{{ asset('js/moderation.js') }}" defer></script>
<script>
const termsState = {
    termsRead: false,
    privacyRead: false
};

window.addEventListener('DOMContentLoaded', async () => {
    // ==================== INICIALIZAÇÃO DA MODERAÇÃO ====================
    const state = await window.Moderation.init({
        csrfToken: '{{ csrf_token() }}',
        endpoint: '/moderate',
        debounceMs: 120,
    });

    function applyWarning(elSelector, res) {
        const el = document.querySelector(elSelector);
        const warn = document.querySelector(elSelector + '-warning') || document.querySelector('#' + elSelector.replace('#', '') + '-warning');
        if (!el) return;
        if (res && res.inappropriate) {
            el.classList.add('input-warn');
            if (warn) warn.style.display = 'block';
        } else {
            el.classList.remove('input-warn');
            if (warn) warn.style.display = 'none';
        }
    }

    window.Moderation.attachInput('#username', 'username', {
        onLocal: (res) => applyWarning('#username', res),
        onServer: (srv) => {
            if (srv && srv.data && srv.data.inappropriate) applyWarning('#username', { inappropriate: true });
        }
    });

    window.Moderation.attachInput('#bio', 'bio', {
        onLocal: (res) => applyWarning('#bio', res),
        onServer: (srv) => {}
    });

    const formHook = window.Moderation.attachFormSubmit('#form-cadastro', [
        { selector: '#username', fieldName: 'username' },
        { selector: '#bio', fieldName: 'bio' }
    ]);
});

// ==================== VALIDAÇÃO DE DATA DE NASCIMENTO ====================
const birthDay = document.getElementById('birth_day');
const birthMonth = document.getElementById('birth_month');
const birthYear = document.getElementById('birth_year');

let dateError = document.querySelector('#date-error');
if (!dateError) {
    dateError = document.createElement('small');
    dateError.id = 'date-error';
    dateError.style.display = 'none';
    dateError.style.color = '#ef4444';
    dateError.style.fontSize = '0.875rem';
    dateError.style.marginTop = '0.5rem';
    birthYear.parentElement.parentElement.appendChild(dateError);
}

function validateDate() {
    const day = parseInt(birthDay.value);
    const month = parseInt(birthMonth.value);
    const year = parseInt(birthYear.value);
    
    if (!day || !month || !year) {
        dateError.style.display = 'none';
        return { valid: false, message: 'Preencha a data completa' };
    }
    
    if (day < 1 || day > 31) {
        dateError.textContent = '✗ Dia inválido (deve ser entre 1 e 31)';
        dateError.style.display = 'block';
        return { valid: false, message: 'Dia inválido' };
    }
    
    if (year < 1900 || year > 2025) {
        dateError.textContent = '✗ Ano inválido (deve ser entre 1900 e 2025)';
        dateError.style.display = 'block';
        return { valid: false, message: 'Ano inválido' };
    }
    
    const daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    const isLeapYear = (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);
    if (isLeapYear) {
        daysInMonth[1] = 29;
    }
    
    if (day > daysInMonth[month - 1]) {
        dateError.textContent = `✗ Dia inválido para ${getMonthName(month)} (máximo ${daysInMonth[month - 1]} dias)`;
        dateError.style.display = 'block';
        return { valid: false, message: 'Dia inválido para o mês selecionado' };
    }
    
    const today = new Date();
    const birthDate = new Date(year, month - 1, day);
    
    if (birthDate > today) {
        dateError.textContent = '✗ Data de nascimento não pode ser no futuro';
        dateError.style.display = 'block';
        return { valid: false, message: 'Data no futuro' };
    }
    
    dateError.textContent = '✓ Data de nascimento válida';
    dateError.style.color = '#10b981';
    dateError.style.display = 'block';
    return { valid: true, message: 'Data válida' };
}

function getMonthName(month) {
    const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 
                    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    return months[month - 1];
}

birthDay.addEventListener('input', validateDate);
birthDay.addEventListener('blur', function() {
    if (this.value.length === 1 && parseInt(this.value) > 0) {
        this.value = '0' + this.value;
        validateDate();
    }
});
birthMonth.addEventListener('change', validateDate);
birthYear.addEventListener('input', validateDate);
birthYear.addEventListener('blur', validateDate);

// ==================== VALIDAÇÃO DE SENHAS (SEM TOGGLE) ====================
const passwordInput = document.getElementById('password');
const passwordConfirmation = document.getElementById('password_confirmation');
const passwordMatch = document.getElementById('password-match');
const passwordStrength = document.getElementById('password-strength');

function checkPasswordStrength() {
    const password = passwordInput.value;
    
    if (password.length === 0) {
        passwordStrength.textContent = 'Mínimo 8 caracteres com pelo menos 1 letra';
        passwordStrength.style.color = '#9ca3af';
        return false;
    }
    
    if (password.length < 8) {
        passwordStrength.textContent = '✗ Senha muito curta (mínimo 8 caracteres)';
        passwordStrength.style.color = '#ef4444';
        return false;
    }
    
    const hasLetter = /[a-zA-Z]/.test(password);
    if (!hasLetter) {
        passwordStrength.textContent = '✗ A senha deve conter pelo menos 1 letra';
        passwordStrength.style.color = '#ef4444';
        return false;
    }
    
    passwordStrength.textContent = '✓ Senha válida';
    passwordStrength.style.color = '#10b981';
    return true;
}

function checkPasswordMatch() {
    const password = passwordInput.value;
    const confirmation = passwordConfirmation.value;
    
    if (confirmation.length === 0) {
        passwordMatch.style.display = 'none';
        return false;
    }
    
    passwordMatch.style.display = 'block';
    
    const passwordValid = password.length >= 8 && /[a-zA-Z]/.test(password);
    
    if (password === confirmation && passwordValid) {
        passwordMatch.textContent = '✓ As senhas coincidem';
        passwordMatch.style.color = '#10b981';
        passwordConfirmation.style.borderColor = '#10b981';
        return true;
    } else if (password !== confirmation) {
        passwordMatch.textContent = '✗ As senhas não coincidem';
        passwordMatch.style.color = '#ef4444';
        passwordConfirmation.style.borderColor = '#ef4444';
        return false;
    } else if (!passwordValid) {
        passwordMatch.textContent = '✗ A senha não atende aos requisitos';
        passwordMatch.style.color = '#ef4444';
        passwordConfirmation.style.borderColor = '#ef4444';
        return false;
    }
}

passwordInput.addEventListener('input', checkPasswordStrength);
passwordInput.addEventListener('input', checkPasswordMatch);
passwordConfirmation.addEventListener('input', checkPasswordMatch);

// ==================== GERENCIAMENTO DOS MODAIS ====================
document.addEventListener('DOMContentLoaded', () => {
    const termsModal = document.getElementById('terms-modal');
    const privacyModal = document.getElementById('privacy-modal');
    const openTermsBtn = document.getElementById('open-terms');
    const openPrivacyBtn = document.getElementById('open-privacy');
    const closeTermsBtn = document.querySelector('[data-modal="terms-modal"]');
    const closePrivacyBtn = document.querySelector('[data-modal="privacy-modal"]');
    const confirmTermsBtn = document.getElementById('terms-confirm-btn');
    const confirmPrivacyBtn = document.getElementById('privacy-confirm-btn');
    const acceptCheckbox = document.getElementById('accept-terms');
    const submitBtn = document.getElementById('submit-btn');
    const termsStatus = document.getElementById('terms-status');

    const termsContent = document.getElementById('terms-body');
    const termsProgress = document.getElementById('terms-progress');
    const termsProgressText = document.getElementById('terms-progress-text');

    termsContent.addEventListener('scroll', () => {
        const scrollTop = termsContent.scrollTop;
        const scrollHeight = termsContent.scrollHeight - termsContent.clientHeight;
        const scrollPercent = (scrollTop / scrollHeight) * 100;
        
        termsProgress.style.width = scrollPercent + '%';
        termsProgressText.textContent = `Role até o final para continuar (${Math.round(scrollPercent)}%)`;
        
        if (scrollPercent >= 95) {
            confirmTermsBtn.disabled = false;
            termsProgressText.textContent = '✓ Você chegou ao final! Clique em "Li e Aceito"';
            termsProgressText.style.color = '#10b981';
        }
    });

    const privacyContent = document.getElementById('privacy-body');
    const privacyProgress = document.getElementById('privacy-progress');
    const privacyProgressText = document.getElementById('privacy-progress-text');

    privacyContent.addEventListener('scroll', () => {
        const scrollTop = privacyContent.scrollTop;
        const scrollHeight = privacyContent.scrollHeight - privacyContent.clientHeight;
        const scrollPercent = (scrollTop / scrollHeight) * 100;
        
        privacyProgress.style.width = scrollPercent + '%';
        privacyProgressText.textContent = `Role até o final para continuar (${Math.round(scrollPercent)}%)`;
        
        if (scrollPercent >= 95) {
            confirmPrivacyBtn.disabled = false;
            privacyProgressText.textContent = '✓ Você chegou ao final! Clique em "Li e Aceito"';
            privacyProgressText.style.color = '#10b981';
        }
    });

    openTermsBtn.addEventListener('click', (e) => {
        e.preventDefault();
        termsModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });

    openPrivacyBtn.addEventListener('click', (e) => {
        e.preventDefault();
        privacyModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });

    closeTermsBtn.addEventListener('click', () => {
        termsModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });

    closePrivacyBtn.addEventListener('click', () => {
        privacyModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });

    window.addEventListener('click', (e) => {
        if (e.target === termsModal) {
            termsModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        if (e.target === privacyModal) {
            privacyModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    confirmTermsBtn.addEventListener('click', () => {
        termsState.termsRead = true;
        termsModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        updateTermsStatus();
    });

    confirmPrivacyBtn.addEventListener('click', () => {
        termsState.privacyRead = true;
        privacyModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        updateTermsStatus();
    });

    function updateTermsStatus() {
        if (termsState.termsRead && termsState.privacyRead) {
            acceptCheckbox.disabled = false;
            termsStatus.textContent = '✓ Você leu todos os documentos. Marque a caixa para continuar.';
            termsStatus.style.color = '#10b981';
        } else if (termsState.termsRead && !termsState.privacyRead) {
            termsStatus.textContent = '⚠️ Você ainda precisa ler a Política de Privacidade';
            termsStatus.style.color = '#f59e0b';
        } else if (!termsState.termsRead && termsState.privacyRead) {
            termsStatus.textContent = '⚠️ Você ainda precisa ler os Termos de Serviço';
            termsStatus.style.color = '#f59e0b';
        }
    }

    acceptCheckbox.addEventListener('change', () => {
        if (acceptCheckbox.checked) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    });

    // ==================== GERENCIAMENTO DO FORMULÁRIO ====================
    const form = document.querySelector('#form-cadastro');

    function hasInappropriateFlag() {
        return !!form.querySelector('.input-warn, .moderation-blocked, .field-blocked');
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const dateValidation = validateDate();
        if (!dateValidation.valid) {
            alert('❌ ' + dateValidation.message);
            birthDay.focus();
            return;
        }

        const password = passwordInput.value;
        const confirmation = passwordConfirmation.value;
        
        if (password !== confirmation) {
            alert('❌ As senhas não coincidem. Por favor, verifique.');
            passwordConfirmation.focus();
            return;
        }
        
        if (password.length < 8) {
            alert('❌ A senha deve ter no mínimo 8 caracteres.');
            passwordInput.focus();
            return;
        }
        
        const hasLetter = /[a-zA-Z]/.test(password);
        if (!hasLetter) {
            alert('❌ A senha deve conter pelo menos 1 letra (a-z ou A-Z).');
            passwordInput.focus();
            return;
        }

        if (!acceptCheckbox.checked) {
            alert('❌ Você precisa aceitar os Termos de Serviço e a Política de Privacidade.');
            return;
        }

        if (hasInappropriateFlag()) {
            alert('❌ Conteúdo impróprio detectado – corrija os campos marcados antes de continuar.');
            return;
        }

        submitBtn.disabled = true;
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Enviando...';

        try {
            const day = birthDay.value;
            const month = birthMonth.value;
            const year = birthYear.value;
            
            if (day && month && year) {
                const formattedDate = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
                document.getElementById('data_de_nascimento').value = formattedDate;
            }

            const fd = new FormData(form);

            const resp = await fetch(form.action, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: fd,
                redirect: 'follow'
            });

            if (resp.redirected) {
                window.location.replace(resp.url);
                return;
            }

            const ct = resp.headers.get('Content-Type') || '';
            if (ct.includes('application/json')) {
                const json = await resp.json();

                if (resp.ok && json && json.success) {
                    window.location.replace(json.redirect || '{{ route('usuarios.index') }}');
                    return;
                }

                if (json && json.errors) {
                    const first = Object.values(json.errors)[0];
                    alert('❌ ' + (first && first[0] ? first[0] : 'Erro de validação.'));
                    return;
                }

                alert(json && json.message ? json.message : 'Resposta inesperada do servidor (JSON).');
                console.error('Resposta JSON inesperada', json);
                return;
            }

            const text = await resp.text();
            console.error('Resposta não-JSON do servidor:', text);
            alert('❌ Erro: resposta inesperada do servidor. Verifique o console para mais detalhes.');

        } catch (err) {
            console.error(err);
            alert('❌ Erro de rede. Tente novamente.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });

    const oldDate = '{{ old('data_de_nascimento') }}';
    if (oldDate) {
        const [year, month, day] = oldDate.split('-');
        birthDay.value = parseInt(day);
        birthMonth.value = parseInt(month);
        birthYear.value = year;
    }

    document.getElementById('avatar').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || 'Escolher arquivo';
        document.getElementById('file-name').textContent = fileName;
        
        const uploadButton = document.querySelector('.file-upload-button');
        if (e.target.files[0]) {
            uploadButton.style.borderColor = '#10b981';
            uploadButton.style.background = '#374151';
        } else {
            uploadButton.style.borderColor = '#374151';
            uploadButton.style.background = '#1f2937';
        }
    });
});
</script>

@endsection
