@extends('layout.app')

@section('content')
<h1>Cadastro</h1>

<form method="POST" action="{{ route('usuarios.store') }}" enctype="multipart/form-data" id="form-cadastro">
    @csrf

    <div>
        <label for="username">Nome de Usuário</label>
        <input type="text" id="username" name="username" value="{{ old('username') }}" required>
        <small id="username-warning" style="display:none;color:#e0556b;font-size:0.9rem;">Conteúdo inapropriado detectado</small>
        @error('username')
        <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <div>
        <label for="nickname">Apelido</label>
        <input type="text" id="nickname" name="nickname" value="{{ old('nickname') }}">
        <small id="nickname-warning" style="display:none;color:#e0556b;font-size:0.9rem;">Conteúdo inapropriado detectado</small>
        @error('nickname')
        <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
        <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <div>
        <label for="password">Senha</label>
        <input type="password" id="password" name="password" required>
        @error('password')
        <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <div>
        <label for="avatar">Foto de Perfil</label>
        <input type="file" id="avatar" name="avatar" accept="image/*">
        <small>Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 2MB</small>
        @error('avatar')
        <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <div>
        <label for="data_de_nascimento">Data de Nascimento</label>
        <input type="date" id="data_de_nascimento" name="data_de_nascimento" value="{{ old('data_de_nascimento') }}" required>
        @error('data_de_nascimento')
        <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <div>
        <label for="bio">Bio</label>
        <textarea id="bio" name="bio">{{ old('bio') }}</textarea>
        <small id="bio-warning" style="display:none;color:#e0556b;font-size:0.9rem;">Conteúdo inapropriado detectado</small>
        @error('bio')
        <small style="color: red;">{{ $message }}</small>
        @enderror
    </div>

    <!-- CHECKBOX DE TERMOS E POLÍTICA -->
    <div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">
        <label style="display: flex; align-items: flex-start; cursor: pointer;">
            <input type="checkbox" id="accept-terms" name="accept_terms" style="margin-right: 10px; margin-top: 3px;" required disabled>
            <span style="font-size: 14px;">
                Eu li e concordo com os 
                <a href="#" id="open-terms" style="color: #007bff; text-decoration: underline;">Termos de Serviço</a>
                e a 
                <a href="#" id="open-privacy" style="color: #007bff; text-decoration: underline;">Política de Privacidade</a>
            </span>
        </label>
        <small style="display: block; margin-top: 8px; color: #666; font-size: 12px;" id="terms-status">
            ⚠️ Você precisa ler os Termos de Serviço e a Política de Privacidade antes de continuar
        </small>
        @error('accept_terms')
        <small style="color: red; display: block; margin-top: 5px;">{{ $message }}</small>
        @enderror
    </div>

    <button type="submit" id="submit-btn" disabled style="opacity: 0.5; cursor: not-allowed;">Cadastrar</button>
</form>

<!-- MODAL DOS TERMOS DE SERVIÇO -->
<div id="terms-modal" class="terms-modal">
    <div class="terms-modal-content">
        <div class="terms-modal-header">
            <h2>Termos de Serviço do Ambience RPG</h2>
            <button class="terms-close" id="close-terms">&times;</button>
        </div>
        
        <!-- Barra de Progresso -->
        <div class="progress-container">
            <div class="progress-bar" id="terms-progress"></div>
        </div>
        <small class="progress-text" id="terms-progress-text">Role até o final para continuar (0%)</small>
        
        <div class="terms-modal-body" id="terms-content">
            <h3>1. Aceitação dos Termos</h3>
            <p>O uso do Ambience RPG implica a aceitação integral deste Termo de Serviço. Ao criar conta ou utilizar qualquer funcionalidade do site, o usuário concorda em cumprir todas as disposições aqui estabelecidas e a legislação brasileira aplicável (como o Marco Civil da Internet -- Lei nº 12.965/2014 e a LGPD -- Lei nº 13.709/2018). Caso o usuário não concorde com algum item deste Termo, não deverá usar o site.</p>

            <h3>2. Descrição do Serviço</h3>
            <p>O Ambience RPG é um site gratuito em português (Brasil) voltado ao RPG online. Entre seus recursos estão:</p>
            <ul>
                <li><strong>Criação de salas de RPG:</strong> o usuário pode criar salas com nome e descrição próprias.</li>
                <li><strong>Ferramentas de jogo:</strong> cada sala pode conter mapas (grids), imagens, dados virtuais e fichas de personagem compartilhadas.</li>
                <li><strong>Sistema de postagens:</strong> é possível publicar aventuras, crônicas, fichas de RPG e demais conteúdos relacionados.</li>
                <li><strong>Conquistas e seguidores:</strong> usuários podem ganhar selos ("conquistas") e seguir outros usuários.</li>
            </ul>

            <h3>3. Cadastro e Elegibilidade</h3>
            <p><strong>Idade mínima:</strong> Para criar uma conta, o usuário deve ter 13 anos ou mais. Menores de 18 anos precisam de autorização dos pais ou responsáveis legais. Menores de 13 anos podem usar o site somente sob supervisão e terão o chat moderado.</p>
            <p><strong>Informações verdadeiras:</strong> O usuário deve fornecer dados pessoais autênticos ao se cadastrar. Caso sejam detectadas informações falsas, a conta será imediatamente deletada.</p>
            <p><strong>Segurança da conta:</strong> O usuário é responsável por manter sua senha segura e por todas as ações realizadas em sua conta.</p>

            <h3>4. Propriedade Intelectual e Conteúdo do Site</h3>
            <p><strong>Direitos do site:</strong> Todo o conteúdo original do Ambience RPG é protegido pela Lei de Direitos Autorais (Lei nº 9.610/98). É proibido copiar, reproduzir, modificar, distribuir ou criar obras derivadas sem autorização expressa.</p>
            <p><strong>Direitos do usuário:</strong> O usuário mantém a titularidade dos direitos autorais sobre os conteúdos que criar, mas concede ao Ambience RPG uma licença não exclusiva para exibir e armazenar esse conteúdo.</p>
            <p><strong>Proibição de concorrência:</strong> É proibido baixar dados em massa do site para criar serviços concorrentes.</p>

            <h3>5. Privacidade e Proteção de Dados</h3>
            <p>O Ambience RPG cumpre a LGPD (Lei nº 13.709/2018), protegendo a privacidade dos usuários. Coletamos apenas os dados necessários e não os vendemos a terceiros. O usuário tem direito de solicitar a exclusão definitiva de seus dados ao término da relação com o serviço.</p>

            <h3>6. Conduta e Uso Aceitável</h3>
            <p>São expressamente proibidas as seguintes condutas:</p>
            <ul>
                <li><strong>Conteúdo inadequado:</strong> Material sexual explícito, pornográfico, ofensivo, racista ou discriminatório.</li>
                <li><strong>Violência e assédio:</strong> Ameaças, bullying, difamação, assédio sexual ou incitação à violência.</li>
                <li><strong>Direitos de terceiros:</strong> Violação de direitos autorais e propriedade intelectual.</li>
                <li><strong>Uso indevido:</strong> Bots, scripts, fraudes, ataques ou códigos maliciosos.</li>
            </ul>

            <h3>7. Política de Sanções</h3>
            <p>O Ambience RPG pode aplicar sanções disciplinares:</p>
            <ol>
                <li><strong>Aviso:</strong> Notificação de alerta para infrações leves.</li>
                <li><strong>Suspensão temporária:</strong> Perda de acesso por período determinado.</li>
                <li><strong>Banimento permanente:</strong> Exclusão definitiva da conta.</li>
                <li><strong>Banimento do dispositivo:</strong> Bloqueio de IP em casos de reincidência.</li>
                <li><strong>Exclusão de conta:</strong> Remoção permanente de dados e conteúdos.</li>
            </ol>

            <h3>8. Alterações no Termo de Serviço</h3>
            <p>Podemos alterar este Termo a qualquer momento. Alterações serão publicadas no site e os usuários serão notificados. O uso continuado implica aceitação das novas condições.</p>

            <h3>9. Legislação Aplicável e Foro</h3>
            <p>Este Termo é regido pela legislação brasileira, incluindo o Marco Civil da Internet (Lei nº 12.965/2014), o Estatuto da Criança e do Adolescente (Lei nº 8.069/1990) e a LGPD (Lei nº 13.709/2018). Controvérsias serão solucionadas no foro da comarca do domicílio do usuário.</p>
        </div>
        
        <div class="terms-modal-footer">
            <button id="confirm-terms" class="terms-btn-confirm" disabled>Confirmar Leitura</button>
        </div>
    </div>
</div>

<!-- MODAL DA POLÍTICA DE PRIVACIDADE -->
<div id="privacy-modal" class="terms-modal">
    <div class="terms-modal-content">
        <div class="terms-modal-header">
            <h2>Política de Privacidade</h2>
            <button class="terms-close" id="close-privacy">&times;</button>
        </div>
        
        <!-- Barra de Progresso -->
        <div class="progress-container">
            <div class="progress-bar" id="privacy-progress"></div>
        </div>
        <small class="progress-text" id="privacy-progress-text">Role até o final para continuar (0%)</small>
        
        <div class="terms-modal-body" id="privacy-content">
            <p>Nossa política de privacidade explica como coletamos, usamos e protegemos os dados pessoais dos usuários, em conformidade com a Lei Geral de Proteção de Dados (LGPD). Segundo a LGPD, as empresas devem fornecer transparência sobre o tratamento de dados pessoais.</p>

            <h3>Dados Pessoais Coletados</h3>
            <p>Coletamos apenas os dados necessários para oferecer nossos serviços:</p>
            <ul>
                <li><strong>Dados de cadastro:</strong> nome de usuário, apelido, email e senha (armazenada como hash).</li>
                <li><strong>Dados de perfil:</strong> nome completo, foto de perfil, data de nascimento.</li>
                <li><strong>Conteúdos e interações:</strong> arquivos enviados, publicações, comentários, curtidas, sessões participadas.</li>
                <li><strong>Dados de uso:</strong> endereços de IP, agente de usuário e registros de ações para segurança.</li>
            </ul>

            <h3>Compartilhamento de Dados</h3>
            <p>Não compartilhamos seus dados pessoais com terceiros para fins comerciais. Os dados são acessados apenas internamente e por serviços necessários à operação do sistema. Em caso de exigências legais (ordens judiciais), podemos compartilhar informações com órgãos competentes.</p>

            <h3>Retenção de Dados e Exclusão de Conta</h3>
            <p>Os dados são mantidos apenas pelo tempo necessário:</p>
            <ul>
                <li><strong>Retenção mínima:</strong> dados guardados apenas durante a prestação dos serviços.</li>
                <li><strong>Exclusão de conta:</strong> dados removidos em até 30 dias após solicitação.</li>
                <li><strong>Logs de sistema:</strong> registros de segurança mantidos por tempo determinado para rastreabilidade.</li>
            </ul>

            <h3>Direitos dos Titulares</h3>
            <p>Você possui direitos garantidos pela LGPD:</p>
            <ul>
                <li><strong>Acesso:</strong> solicitar confirmação dos dados mantidos.</li>
                <li><strong>Retificação:</strong> corrigir dados incorretos.</li>
                <li><strong>Eliminação:</strong> pedir exclusão definitiva (direito ao esquecimento).</li>
                <li><strong>Oposição:</strong> contestar o tratamento de dados.</li>
                <li><strong>Portabilidade:</strong> receber dados em formato estruturado.</li>
                <li><strong>Revogação:</strong> retirar consentimento dado.</li>
            </ul>

            <h3>Segurança das Informações</h3>
            <p>Implementamos medidas técnicas e organizacionais para proteger seus dados: criptografia de senhas, controle de acesso interno e monitoramento de atividades. Logs de eventos importantes são registrados de forma criptografada para identificar possíveis ameaças.</p>

            <h3>Atualizações desta Política</h3>
            <p>Podemos atualizar esta política periodicamente. Alterações serão comunicadas por meio do site ou por email. Recomendamos que você reveja esta página regularmente.</p>

            <p><strong>Conformidade Legal:</strong> Esta política baseia-se nos requisitos da LGPD (Lei nº 13.709/2018) e em práticas recomendadas de privacidade e segurança de dados.</p>
        </div>
        
        <div class="terms-modal-footer">
            <button id="confirm-privacy" class="terms-btn-confirm" disabled>Confirmar Leitura</button>
        </div>
    </div>
</div>

<style>
    .input-warn {
        border: 2px solid #e0556b !important;
        background: #fff6f7;
    }

    .field-info {
        color: #666;
        font-size: 0.85rem;
        margin-top: 4px;
    }

    /* Estilos do Modal */
    .terms-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.6);
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .terms-modal-content {
        background-color: #fff;
        margin: 3% auto;
        width: 90%;
        max-width: 800px;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        display: flex;
        flex-direction: column;
        max-height: 85vh;
        animation: slideDown 0.3s;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .terms-modal-header {
        padding: 20px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        background: #e0e0e0;
        height: 6px;
        margin: 0;
        position: relative;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        width: 0%;
        transition: width 0.1s ease;
    }

    .progress-text {
        display: block;
        padding: 8px 30px;
        color: #666;
        font-size: 13px;
        background: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
    }

    .terms-modal-body {
        padding: 30px;
        overflow-y: auto;
        flex: 1;
        line-height: 1.8;
        color: #333;
    }

    .terms-modal-body h3 {
        color: #667eea;
        margin-top: 25px;
        margin-bottom: 15px;
        font-size: 20px;
        font-weight: 600;
    }

    .terms-modal-body p {
        margin-bottom: 15px;
        text-align: justify;
    }

    .terms-modal-body ul, .terms-modal-body ol {
        margin-left: 20px;
        margin-bottom: 15px;
    }

    .terms-modal-body li {
        margin-bottom: 8px;
    }

    .terms-modal-footer {
        padding: 20px 30px;
        background: #f8f9fa;
        border-radius: 0 0 12px 12px;
        display: flex;
        justify-content: flex-end;
        border-top: 1px solid #e0e0e0;
    }

    .terms-btn-confirm {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .terms-btn-confirm:disabled {
        background: #ccc;
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Scrollbar personalizada */
    .terms-modal-body::-webkit-scrollbar {
        width: 10px;
    }

    .terms-modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .terms-modal-body::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }

    .terms-modal-body::-webkit-scrollbar-thumb:hover {
        background: #764ba2;
    }
</style>

<script src="{{ asset('js/moderation.js') }}" defer></script>
<script>
    // Estado global
    const termsState = {
        termsRead: false,
        privacyRead: false
    };

    window.addEventListener('DOMContentLoaded', async () => {
        // Inicializa moderação
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

        window.Moderation.attachInput('#nickname', 'nickname', {
            onLocal: (res) => applyWarning('#nickname', res)
        });

        window.Moderation.attachInput('#bio', 'bio', {
            onLocal: (res) => applyWarning('#bio', res),
            onServer: (srv) => {}
        });

        const formHook = window.Moderation.attachFormSubmit('#form-cadastro', [
            { selector: '#username', fieldName: 'username' },
            { selector: '#nickname', fieldName: 'nickname' },
            { selector: '#bio', fieldName: 'bio' }
        ]);
    });

    // Gerenciamento dos Modais
    document.addEventListener('DOMContentLoaded', () => {
        const termsModal = document.getElementById('terms-modal');
        const privacyModal = document.getElementById('privacy-modal');
        const openTermsBtn = document.getElementById('open-terms');
        const openPrivacyBtn = document.getElementById('open-privacy');
        const closeTermsBtn = document.getElementById('close-terms');
        const closePrivacyBtn = document.getElementById('close-privacy');
        const confirmTermsBtn = document.getElementById('confirm-terms');
        const confirmPrivacyBtn = document.getElementById('confirm-privacy');
        const acceptCheckbox = document.getElementById('accept-terms');
        const submitBtn = document.getElementById('submit-btn');
        const termsStatus = document.getElementById('terms-status');

        // Controle de scroll para Termos
        const termsContent = document.getElementById('terms-content');
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
                termsProgressText.textContent = '✓ Você chegou ao final! Clique em "Confirmar Leitura"';
                termsProgressText.style.color = '#28a745';
            }
        });

        // Controle de scroll para Política de Privacidade
        const privacyContent = document.getElementById('privacy-content');
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
                privacyProgressText.textContent = '✓ Você chegou ao final! Clique em "Confirmar Leitura"';
                privacyProgressText.style.color = '#28a745';
            }
        });

        // Abrir modais
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

        // Fechar modais
        closeTermsBtn.addEventListener('click', () => {
            termsModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        closePrivacyBtn.addEventListener('click', () => {
            privacyModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        // Fechar ao clicar fora
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

        // Confirmar leitura dos Termos
        confirmTermsBtn.addEventListener('click', () => {
            termsState.termsRead = true;
            termsModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            updateTermsStatus();
        });

        // Confirmar leitura da Política
        confirmPrivacyBtn.addEventListener('click', () => {
            termsState.privacyRead = true;
            privacyModal.style.display = 'none';
            document.body.style.overflow = 'auto';
            updateTermsStatus();
        });

        // Atualizar status e habilitar checkbox/botão
        function updateTermsStatus() {
            if (termsState.termsRead && termsState.privacyRead) {
                acceptCheckbox.disabled = false;
                termsStatus.textContent = '✓ Você leu todos os documentos. Marque a caixa para continuar.';
                termsStatus.style.color = '#28a745';
            } else if (termsState.termsRead && !termsState.privacyRead) {
                termsStatus.textContent = '⚠️ Você ainda precisa ler a Política de Privacidade';
                termsStatus.style.color = '#ffc107';
            } else if (!termsState.termsRead && termsState.privacyRead) {
                termsStatus.textContent = '⚠️ Você ainda precisa ler os Termos de Serviço';
                termsStatus.style.color = '#ffc107';
            }
        }

        // Habilitar botão de submit quando checkbox for marcado
        acceptCheckbox.addEventListener('change', () => {
            if (acceptCheckbox.checked) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            } else {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
            }
        });

        // Gerenciamento do formulário
        const form = document.querySelector('#form-cadastro');

        function hasInappropriateFlag() {
            return !!form.querySelector('.input-warn, .moderation-blocked, .field-blocked');
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!acceptCheckbox.checked) {
                alert('Você precisa aceitar os Termos de Serviço e a Política de Privacidade.');
                return;
            }

            if (hasInappropriateFlag()) {
                alert('Conteúdo impróprio detectado – corrija os campos marcados antes de continuar.');
                return;
            }

            submitBtn.disabled = true;
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Enviando...';

            try {
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
                        alert(first && first[0] ? first[0] : 'Erro de validação.');
                        return;
                    }

                    alert(json && json.message ? json.message : 'Resposta inesperada do servidor (JSON).');
                    console.error('Resposta JSON inesperada', json);
                    return;
                }

                const text = await resp.text();
                console.error('Resposta não-JSON do servidor:', text);
                alert('Erro: resposta inesperada do servidor. Verifique o console para mais detalhes.');

            } catch (err) {
                console.error(err);
                alert('Erro de rede. Tente novamente.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    });
</script>
@endsection