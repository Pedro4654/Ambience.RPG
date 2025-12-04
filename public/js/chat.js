/**
 * ========================================
 * SISTEMA DE CHAT - AMBIENCE RPG
 * ========================================
 * Chat em tempo real com moderação, censura por idade e denúncias
 * ✅ CORRIGIDO: Real-time para todos os usuários
 * ✅ NOVO: Deletar mensagens em tempo real
 * ✅ NOVO: Editar mensagens em tempo real
 */

class ChatSystem {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            console.error('[Chat] Container não encontrado:', containerId);
            return;
        }

        this.salaId = this.container.dataset.salaId;
        this.userId = this.container.dataset.userId;
        this.userAge = parseInt(this.container.dataset.userAge) || 18;

        this.podeModerarChat = this.container.dataset.podeModerarChat === 'true';
this.ehCriadorSala = this.container.dataset.ehCriadorSala === 'true';

console.log('[Chat] 🛡️ Permissões carregadas', {
    podeModerarChat: this.podeModerarChat,
    ehCriadorSala: this.ehCriadorSala
});

this.isInGrid = window.location.pathname.includes('/sessoes/');
    if (this.isInGrid) {
        console.log('[Chat] 🎮 Chat iniciado na GRID');
        this.setupFloatingBehavior();
    }

        // Elementos DOM
        this.messagesContainer = document.getElementById('chatMessages');
        this.messageInput = document.getElementById('chatMessageInput');
        this.fileInput = document.getElementById('chatFileInput');
        this.typingIndicator = document.getElementById('chatTypingIndicator');
this.typingText = document.getElementById('chatTypingText');
        this.sendBtn = document.getElementById('chatSendBtn');
        this.chatForm = document.getElementById('chatForm');
        this.attachmentsPreview = document.getElementById('chatAttachmentsPreview');
        this.moderationAlert = document.getElementById('chatModerationAlert');

        // Estado
        this.messages = [];
        this.attachments = [];
        this.loading = false;
        this.selectedMessages = new Set();
        this.channel = null; // Armazenar referência do canal
         this.usersTyping = new Map(); // Map<userId, {username, timeout}>
    this.typingTimeout = null;
    this.isTyping = false;
    this.typingDebounceMs = 2000;
    this.resizeState = null;
    this.dimensionsBadge = null;

     this.currentResizeHandlers = null;
    this.currentDragHandlers = null;

        this.init();
    }

    async init() {
        console.log('[Chat] 🚀 Inicializando...', {
            salaId: this.salaId,
            userId: this.userId,
            userAge: this.userAge,
            isInGrid: this.isInGrid
        });

        await this.initModeration();
        await this.loadMessages();
        this.setupEventListeners();
        this.setupWebSocket();

        if (this.isInGrid) {
        setTimeout(() => {
            this.setupFloatingBehavior();
        }, 100);
    }
    
        setTimeout(() => {
    this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
    console.log('[Chat] 📍 Scroll posicionado no final:', this.messagesContainer.scrollHeight);
}, 300);

        console.log('[Chat] ✅ Inicializado com sucesso');
    }

    async initModeration() {
        try {
            const result = await window.Moderation.init({
                csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                endpoint: '/moderate',
                debounceMs: 120
            });
            console.log('[Chat] Moderação inicializada:', result);
        } catch (error) {
            console.error('[Chat] Erro ao inicializar moderação:', error);
        }
    }

    setupEventListeners() {
        // Form submit
        this.chatForm.addEventListener('submit', (e) => this.handleSendMessage(e));
        
        // Input de moderação
        this.messageInput.addEventListener('input', (e) => {
        this.handleInputModeration(e);
        this.handleTypingInput(e); // ✅ NOVO
    });

        // Toggle chat
        const toggleBtn = document.getElementById('toggleChatBtn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggleChat());
        }
        
        // ✅ BOTÃO DE ANEXO - apenas um listener
        const attachBtn = document.getElementById('chatAttachBtn');
        if (attachBtn && this.fileInput) {
            console.log('[Chat] ✅ Configurando botão de anexo');
            
            // Remover listeners antigos se existirem
            attachBtn.replaceWith(attachBtn.cloneNode(true));
            const newAttachBtn = document.getElementById('chatAttachBtn');
            
            newAttachBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                console.log('[Chat] 🖱️ Botão de anexo clicado');
                this.fileInput.click();
            });
        }
        
        // ✅ FILE INPUT - apenas um listener
        if (this.fileInput) {
            console.log('[Chat] ✅ Configurando input de arquivo');
            
            // Remover listeners antigos
            this.fileInput.replaceWith(this.fileInput.cloneNode(true));
            this.fileInput = document.getElementById('chatFileInput');
            
            this.fileInput.addEventListener('change', async (e) => {
                console.log('[Chat] 📎 Arquivos selecionados');
                await this.handleFileSelect(e);
            });
        }
    }

    setupWebSocket() {
        if (!window.Echo) {
            console.error('[Chat] ❌ Echo não disponível');
            return;
        }

        const channelName = `sala.${this.salaId}`;
        console.log('[Chat] 🔌 Conectando ao canal:', channelName);

        this.channel = window.Echo.join(channelName);

        // ==================== LISTENER: NOVA MENSAGEM ====================
        this.channel.listen('.nova.mensagem', (data) => {
            console.group('[Chat] 📨 EVENTO: Nova Mensagem');
            console.log('Raw data:', data);
            console.log('Tipo:', typeof data);
            console.log('Keys:', Object.keys(data));
            
            // Extrair mensagem do payload
            const mensagem = data.id ? data : (data.mensagem || data.message || data);
            
            console.log('Mensagem extraída:', mensagem);
            console.log('ID da mensagem:', mensagem?.id);
            console.log('Usuário:', mensagem?.usuario?.username);
            
            if (!mensagem || !mensagem.id) {
                console.error('❌ Payload inválido');
                console.groupEnd();
                return;
            }
            
            // ✅ VERIFICAR SE JÁ EXISTE (evitar duplicatas)
            const exists = this.messages.find(m => m.id === mensagem.id);
            if (exists) {
                console.warn('⚠️ Mensagem já existe no array, ignorando');
                console.groupEnd();
                return;
            }
            
            console.log('✅ Mensagem válida, adicionando ao DOM');
            console.groupEnd();
            
            this.handleNewMessage(mensagem);
        });

        // ==================== LISTENER: MENSAGEM DELETADA ====================
        this.channel.listen('.mensagem.deletada', (data) => {
            console.group('[Chat] 🗑️ EVENTO: Mensagem Deletada');
            console.log('Data:', data);
            console.log('ID da mensagem:', data.mensagem_id);
            
            if (!data.mensagem_id) {
                console.error('❌ ID da mensagem não encontrado');
                console.groupEnd();
                return;
            }
            
            console.log('✅ Removendo mensagem do DOM');
            console.groupEnd();
            
            this.handleDeletedMessage(data.mensagem_id);
        });

        // ==================== LISTENER: MENSAGEM EDITADA ====================
        this.channel.listen('.mensagem.editada', (data) => {
            console.group('[Chat] ✏️ EVENTO: Mensagem Editada');
            console.log('Data:', data);
            console.log('ID da mensagem:', data.mensagem_id);
            
            if (!data.mensagem_id || !data.nova_mensagem) {
                console.error('❌ Dados incompletos');
                console.groupEnd();
                return;
            }
            
            console.log('✅ Atualizando mensagem no DOM');
            console.groupEnd();
            
            this.handleEditedMessage(data);
        });

        // Logs de presença
        this.channel.here((users) => {
            console.log('[Chat] 👥 Usuários online:', users.length, users);
        });
        
        this.channel.joining((user) => {
            console.log('[Chat] ✅ Usuário entrou:', user.username || user.name);
        });
        
        this.channel.leaving((user) => {
            console.log('[Chat] ❌ Usuário saiu:', user.username || user.name);
        });

        this.channel.error((error) => {
            console.error('[Chat] ❌ Erro no canal:', error);
        });

        this.channel.listen('.usuario.digitando', (data) => {
    console.log('[Chat] ⌨️ Evento de digitação:', data);
    this.handleTypingEvent(data);
});
        
        // Verificar subscrição
        setTimeout(() => {
            const subscription = window.Echo.connector.channels[`presence-${channelName}`];
            if (subscription) {
                console.log('[Chat] ✅ Canal subscrito:', channelName);
                console.log('[Chat] Subscription state:', subscription.subscription_state);
            } else {
                console.error('[Chat] ❌ Falha ao subscrever no canal');
            }
        }, 1000);
        

        this.initResize();

        console.log('[Chat] ✅ WebSocket configurado');
    }

    // ==================== TYPING INDICATOR ====================

/**
 * Manipular input de digitação
 */
handleTypingInput(e) {
    const text = e.target.value.trim();
    
    // Se campo vazio, notificar que parou de digitar
    if (!text) {
        if (this.isTyping) {
            this.notifyTyping(false);
        }
        return;
    }
    
    // Se ainda não estava digitando, notificar que começou
    if (!this.isTyping) {
        this.notifyTyping(true);
    }
    
    // Resetar timeout - usuário ainda está digitando
    clearTimeout(this.typingTimeout);
    
    // Após X ms sem digitar, considerar que parou
    this.typingTimeout = setTimeout(() => {
        if (this.isTyping) {
            this.notifyTyping(false);
        }
    }, this.typingDebounceMs);
}

/**
 * Notificar backend sobre status de digitação
 */
async notifyTyping(isTyping) {
    this.isTyping = isTyping;
    
    try {
        await fetch(`/salas/${this.salaId}/chat/typing`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.getCsrfToken()
            },
            body: JSON.stringify({ is_typing: isTyping })
        });
        
        console.log('[Chat] 📤 Typing notification sent:', isTyping);
    } catch (error) {
        console.error('[Chat] Erro ao notificar digitação:', error);
    }
}

/**
 * Processar evento de digitação recebido
 */
handleTypingEvent(data) {
    const userId = parseInt(data.user_id);
    const username = data.username;
    const isTyping = data.is_typing;
    
    // Ignorar evento do próprio usuário
    if (userId === parseInt(this.userId)) {
        return;
    }
    
    if (isTyping) {
        // Adicionar usuário à lista de quem está digitando
        this.usersTyping.set(userId, {
            username: username,
            timeout: setTimeout(() => {
                // Auto-remover após 3 segundos (caso evento de "parou" não chegue)
                this.usersTyping.delete(userId);
                this.updateTypingIndicator();
            }, 5000)
        });
    } else {
        // Remover usuário da lista
        const userData = this.usersTyping.get(userId);
        if (userData && userData.timeout) {
            clearTimeout(userData.timeout);
        }
        this.usersTyping.delete(userId);
    }
    
    this.updateTypingIndicator();
}

/**
 * Atualizar indicador visual de digitação
 */
updateTypingIndicator() {
    const typingCount = this.usersTyping.size;
    
    if (typingCount === 0) {
        // Ninguém digitando - esconder indicador
        this.typingIndicator.style.display = 'none';
        return;
    }
    
    // Alguém digitando - mostrar indicador
    this.typingIndicator.style.display = 'block';
    
    let text = '';
    
    if (typingCount === 1) {
        // 1 pessoa: "Ana digitando…"
        const [userData] = this.usersTyping.values();
        text = `${userData.username} digitando…`;
    } else if (typingCount <= 3) {
        // 2-3 pessoas: "Ana, João e Pedro digitando…"
        const names = Array.from(this.usersTyping.values()).map(u => u.username);
        const lastUser = names.pop();
        text = `${names.join(', ')} e ${lastUser} digitando…`;
    } else {
        // 3+ pessoas: "Várias pessoas estão digitando…"
        text = 'Várias pessoas estão digitando…';
    }
    
    this.typingText.textContent = text;
}

/**
 * Limpar estado de digitação ao enviar mensagem
 */
clearTypingState() {
    if (this.isTyping) {
        this.notifyTyping(false);
    }
    clearTimeout(this.typingTimeout);
}

    async loadMessages() {
        this.setLoading(true);
        
        try {
            const response = await fetch(`/salas/${this.salaId}/chat/mensagens`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            if (!response.ok) throw new Error('Erro ao carregar mensagens');

            const data = await response.json();
            this.messages = data.mensagens.data || [];
            
            this.renderMessages();
            requestAnimationFrame(() => {
    this.scrollToBottom(false); // false = sem animação smooth
});

        } catch (error) {
            console.error('[Chat] Erro ao carregar mensagens:', error);
            this.showError('Erro ao carregar mensagens do chat');
        } finally {
            this.setLoading(false);
        }
    }

    renderMessages() {
        if (this.messages.length === 0) {
            this.messagesContainer.innerHTML = `
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-comments fa-3x mb-3 opacity-50"></i>
                    <p>Nenhuma mensagem ainda. Seja o primeiro a enviar!</p>
                </div>
            `;
            return;
        }

        const getMessageTimestamp = (m) => {
            const candidates = [
                m.created_at, m.createdAt, m.timestamp, m.time, 
                m.criado_em, m.created_at_iso, m.data_hora
            ];
            
            for (const c of candidates) {
                if (!c) continue;
                const d = Date.parse(c);
                if (!isNaN(d)) return d;
            }
            
            return Number(m.id) || 0;
        };

        // Ordenar mensagens antigas primeiro (ASC)
        const sortedMessages = [...this.messages].sort((a, b) => {
            return getMessageTimestamp(a) - getMessageTimestamp(b);
        });

        this.messagesContainer.innerHTML = sortedMessages
            .map(msg => this.renderMessage(msg))
            .join('');

        this.attachMessageMenuListeners();
        this.scrollToBottom(false);
    }

    renderMessage(msg) {
    const isOwn = parseInt(msg.usuario.id) === parseInt(this.userId);
    const isCensored = msg.censurada;
    const flags = msg.flags_detectadas || msg.flags || [];
    
    const userAge = this.userAge;
    let deveCensurar = false;
    let podeVerCensurado = false;
    let mensagemExibida = msg.mensagem;
    
    console.log('[Chat] 🔍 RENDERIZANDO', {
        id: msg.id,
        isCensored,
        flags,
        userAge,
        mensagem_backend: msg.mensagem,
        mensagem_original: msg.mensagem_original
    });
    
    if (isCensored && flags.length > 0) {
        const temProfanity = flags.includes('profanity');
        const temConteudoSexual = flags.includes('sexual') || flags.includes('porn');
        
        console.log('[Chat] 🚨 MENSAGEM CENSURADA DETECTADA', {
            temProfanity,
            temConteudoSexual,
            userAge
        });
        
        // ✅ MENOR DE 15: SEMPRE CENSURAR (nunca pode ver)
        if (userAge < 15) {
            if (temProfanity || temConteudoSexual) {
                mensagemExibida = msg.mensagem;
                deveCensurar = true;
                podeVerCensurado = false; // ← NUNCA pode ver
                console.log('✅ CENSURANDO PARA MENOR DE 15');
            }
        } 
        // ✅ 15-17: Mostra palavrões, censura sexual (nunca pode ver sexual)
        else if (userAge >= 15 && userAge < 18) {
            if (temConteudoSexual) {
                deveCensurar = true;
                podeVerCensurado = false; // ← NUNCA pode ver
                mensagemExibida = '[Mensagem oculta – violou as regras de conteúdo]';
            } else if (temProfanity) {
                mensagemExibida = msg.mensagem_original || msg.mensagem;
                deveCensurar = false;
                podeVerCensurado = false;
            }
        } 
        // ✅ 18+: Mostra palavrões, censura sexual (MAS pode ver depois)
        else if (userAge >= 18) {
            if (temConteudoSexual) {
                deveCensurar = true;
                podeVerCensurado = true; // ← SÓ AQUI pode ver
                mensagemExibida = '[Mensagem oculta – violou as regras de conteúdo]';
            } else if (temProfanity) {
                mensagemExibida = msg.mensagem_original || msg.mensagem;
                deveCensurar = false;
                podeVerCensurado = false;
            }
        }
        
        console.log('[Chat] 📤 RESULTADO FINAL', {
            deveCensurar,
            podeVerCensurado,
            mensagemExibida,
            vai_ter_fundo_vermelho: deveCensurar
        });
    }

    // ✅ AVATAR HTML
   const avatarHtml = msg.usuario.avatar_url 
        ? `<img src="${msg.usuario.avatar_url}" 
                alt="${msg.usuario.username}" 
                class="ambience-avatar"
                style="width:38px!important;height:38px!important;object-fit:cover!important;border-radius:50%!important;">`
        : `<div class="ambience-avatar-fallback" 
                style="width:38px!important;height:38px!important;min-width:38px!important;">
                ${msg.usuario.username.charAt(0).toUpperCase()}
           </div>`;
    
    // ✅ ANEXOS
    const attachmentsHtml = msg.anexos && msg.anexos.length > 0
        ? msg.anexos.map(anexo => {
            if (anexo.eh_imagem) {
                return `
                    <img src="${anexo.url}" 
                         alt="${anexo.nome}" 
                         class="ambience-message-attachment"
                         onclick="window.open('${anexo.url}', '_blank')">
                `;
            } else {
                return `
                    <a href="${anexo.url}" 
                       target="_blank" 
                       class="ambience-attachment-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                            <polyline points="13 2 13 9 20 9"/>
                        </svg>
                        ${anexo.nome}
                    </a>
                `;
            }
          }).join('')
        : '';
    
    // ✅ RENDERIZAÇÃO FINAL (NOVO DESIGN)
    return `
        <div class="ambience-message ${msg.editada ? 'edited' : ''}" 
             data-message-id="${msg.id}"
            style="display:flex!important;gap:12px!important;align-items:flex-start!important;">
            ${avatarHtml}
            <div class="ambience-message-content" 
                 style="flex:1!important;min-width:0!important;max-width:75%!important;">
                <span class="ambience-username">
    <span style="color:#22c55e!important;font-weight:700!important;font-size:13px!important;">
        ${this.escapeHtml(msg.usuario.username)}
    </span>
    
    <!-- ✅ NOVO: Ícone de três pontos VERTICAIS ao lado do nome -->
    <button class="ambience-action-menu-btn-inline" 
            data-message-id="${msg.id}" 
            data-user-id="${msg.usuario.id}"
            title="Mais opções">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="1"/>
            <circle cx="12" cy="5" r="1"/>
            <circle cx="12" cy="19" r="1"/>
        </svg>
    </button>
    
    ${isOwn ? '<span class="ambience-user-badge" style="display:inline-flex!important;padding:3px 8px!important;margin-left:6px!important;background:rgba(59,130,246,0.2)!important;border:1px solid rgba(59,130,246,0.4)!important;border-radius:8px!important;color:#60a5fa!important;font-size:10px!important;font-weight:600!important;text-transform:uppercase!important;vertical-align:middle!important;">você</span>' : ''}
</span>  
                <div class="ambience-bubble ${deveCensurar ? 'ambience-bubble-censored' : ''}" 
                     data-message-text="${msg.id}"
                     style="padding:10px 14px!important;border-radius:12px!important;background:#374151!important;color:#e5eef1!important;font-size:14px!important;line-height:1.5!important;word-wrap:break-word!important;display:block!important;width:fit-content!important;">
                    ${this.escapeHtml(mensagemExibida)}
                    ${attachmentsHtml}
                </div>
                
                <div class="ambience-message-footer">
                    <span class="ambience-timestamp">
                        ${msg.timestamp_formatado}
                        ${msg.editada ? '<span class="ambience-edited-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:10px;height:10px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> editada</span>' : ''}
                    </span>
                    ${deveCensurar && podeVerCensurado ? `
                        <button class="ambience-reveal-btn" 
                                data-message-id="${msg.id}" 
                                data-revealed="false"
                                title="Exibir mensagem original">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <span>Exibir</span>
                        </button>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
}

    attachMessageMenuListeners() {
    // ✅ NOVA CLASSE: ambience-action-menu-btn-inline (botão ao lado do nome)
    document.querySelectorAll('.ambience-action-menu-btn-inline').forEach(btn => {
        btn.addEventListener('click', (e) => this.showMessageMenu(e));
    });

    // ✅ CLASSE: ambience-view-censored-btn
    document.querySelectorAll('.ambience-view-censored-btn').forEach(btn => {
        btn.addEventListener('click', (e) => this.viewCensoredContent(e));
    });

    document.querySelectorAll('.ambience-reveal-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.toggleRevealCensored(e));
        });
}

async toggleRevealCensored(e) {
    e.stopPropagation();
    const btn = e.currentTarget;
    const messageId = btn.dataset.messageId;
    const isRevealed = btn.dataset.revealed === 'true';
    
    const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
    if (!messageEl) return;
    
    const bubbleEl = messageEl.querySelector('.ambience-bubble');
    if (!bubbleEl) return;
    
    // Salvar posição atual do scroll ANTES de qualquer alteração
    const scrollContainer = this.messagesContainer;
    const currentScrollTop = scrollContainer.scrollTop;
    
    if (isRevealed) {
        // Ocultar novamente
        const msg = this.messages.find(m => m.id === parseInt(messageId));
        if (msg) {
            bubbleEl.textContent = '[Mensagem oculta — violou as regras de conteúdo]';
            bubbleEl.classList.add('ambience-bubble-censored');
            
            btn.dataset.revealed = 'false';
            btn.querySelector('span').textContent = 'Exibir';
            btn.title = 'Exibir mensagem original';
        }
    } else {
        // Revelar conteúdo
        try {
            const response = await fetch(`/chat/mensagens/${messageId}/ver-censurado`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            if (!response.ok) throw new Error('Não autorizado');

            const data = await response.json();
            
            bubbleEl.textContent = data.mensagem_original;
            bubbleEl.classList.remove('ambience-bubble-censored');
            
            btn.dataset.revealed = 'true';
            btn.querySelector('span').textContent = 'Ocultar';
            btn.title = 'Ocultar mensagem';

        } catch (error) {
            console.error('[Chat] Erro ao revelar conteúdo:', error);
            this.showError('Você não tem permissão para ver este conteúdo');
        }
    }
    
    // Restaurar posição exata do scroll após a alteração
    requestAnimationFrame(() => {
        scrollContainer.scrollTop = currentScrollTop;
    });
}

    showMessageMenu(e) {
    e.stopPropagation();
    const btn = e.currentTarget;
    const messageId = btn.dataset.messageId;
    const userId = btn.dataset.userId;
    const isOwn = parseInt(userId) === parseInt(this.userId);
    
    // ✅ VERIFICAR SE A MENSAGEM JÁ ESTÁ SELECIONADA
    const isSelected = this.selectedMessages.has(parseInt(messageId));

    document.querySelectorAll('.ambience-action-dropdown').forEach(m => m.remove());

    // ✅ OBTER USERNAME DA MENSAGEM
    const msg = this.messages.find(m => m.id === parseInt(messageId));
    const username = msg?.usuario?.username || '';

    const menu = document.createElement('div');
    menu.className = 'ambience-action-dropdown active';
    menu.innerHTML = `
        <button class="ambience-action-item" data-action="profile" data-username="${username}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            Ver perfil
        </button>
        ${(isOwn || this.podeModerarChat || this.ehCriadorSala) ? `
    <button class="ambience-action-item" data-action="edit" data-message-id="${messageId}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
        ${isOwn ? 'Editar' : 'Editar (Moderador)'}
    </button>
` : ''}
        ${!isOwn ? `
            <button class="ambience-action-item danger" data-action="report" data-message-id="${messageId}" data-user-id="${userId}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                    <line x1="4" y1="22" x2="4" y2="15"/>
                </svg>
                Denunciar
            </button>
        ` : ''}
       ${(isOwn || this.podeModerarChat || this.ehCriadorSala) ? `
    <button class="ambience-action-item danger" data-action="delete" data-message-id="${messageId}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="3 6 5 6 21 6"/>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
        </svg>
        ${isOwn ? 'Deletar' : 'Deletar (Moderador)'}
    </button>
` : ''}
        ${!isOwn ? `
            <button class="ambience-action-item ${isSelected ? 'selected' : ''}" data-action="select" data-message-id="${messageId}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    ${isSelected 
                        ? '<rect x="3" y="3" width="18" height="18" rx="2"/><polyline points="9 11 12 14 22 4"/>' 
                        : '<rect x="3" y="3" width="18" height="18" rx="2"/>'}
                </svg>
                ${isSelected ? 'Remover seleção' : 'Selecionar para denúncia'}
            </button>
        ` : ''}
    `;

        const rect = btn.getBoundingClientRect();
const menuWidth = 180;

// Adicionar ao body primeiro para calcular altura real
document.body.appendChild(menu);
const menuHeight = menu.offsetHeight;

// Calcular posição: sempre logo abaixo do botão
let top = rect.bottom + 4; // 4px de espaço
let left = rect.left;

// Ajustar horizontalmente se ultrapassar a borda direita
if (left + menuWidth > window.innerWidth - 8) {
    left = rect.right - menuWidth;
}

// Ajustar horizontalmente se ultrapassar a borda esquerda
if (left < 8) {
    left = 8;
}

// Ajustar verticalmente se ultrapassar a borda inferior
if (top + menuHeight > window.innerHeight - 8) {
    // Se não couber embaixo, colocar em cima
    top = rect.top - menuHeight - 4;
    
    // Se ainda assim não couber, manter embaixo mas ajustar
    if (top < 8) {
        top = window.innerHeight - menuHeight - 8;
    }
}

menu.style.top = `${top}px`;
menu.style.left = `${left}px`;

        menu.querySelectorAll('.ambience-action-item').forEach(item => {
            item.addEventListener('click', (e) => this.handleMenuAction(e));
        });

        setTimeout(() => {
    document.addEventListener('click', () => {
        menu.style.opacity = '0';
        menu.style.transform = 'translateY(-8px) scale(0.95)';
        menu.style.transition = 'all 0.2s ease';
        setTimeout(() => menu.remove(), 200);
    }, { once: true });
}, 100);
    }

    async handleMenuAction(e) {
    const action = e.currentTarget.dataset.action;
    const messageId = e.currentTarget.dataset.messageId;
    const userId = e.currentTarget.dataset.userId;
    const username = e.currentTarget.dataset.username;

    console.log('[Chat] 🎯 Ação do menu:', { action, messageId, userId, username });

    switch (action) {
        case 'profile':
            if (username) {
                window.location.href = `/perfil/${username}`;
            } else {
                console.error('[Chat] Username não encontrado');
            }
            break;
        
        case 'edit':
            this.startEditMessage(messageId);
            break;
        
        case 'report':
            console.log('[Chat] 🚨 Abrindo modal de denúncia...');
            
            if (messageId) {
                this.selectedMessages.add(parseInt(messageId));
                const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
                if (messageEl) {
                    messageEl.classList.add('ambience-message-selected');
                }
            }
            
            // ✅ GARANTIR que o modal existe antes de abrir
            if (window.reportModal) {
                console.log('[Chat] ✅ ReportModal encontrado, abrindo...');
                window.reportModal.open(userId);
            } else if (window.openReportModal) {
                console.log('[Chat] ✅ Usando função global openReportModal...');
                window.openReportModal(userId, messageId);
            } else {
                console.error('[Chat] ❌ ReportModal não disponível!');
                alert('Erro: Sistema de denúncia não carregado. Recarregue a página.');
            }
            break;
        
        case 'delete':
            await this.deleteMessage(messageId);
            break;
        
        case 'select':
            this.toggleMessageSelection(messageId);
            break;
    }

    document.querySelectorAll('.ambience-action-dropdown').forEach(m => m.remove());
}

    // ==================== EDITAR MENSAGEM ====================
    startEditMessage(messageId) {
        const msg = this.messages.find(m => m.id === parseInt(messageId));
        if (!msg) return;

        const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
        if (!messageEl) return;

        const textEl = messageEl.querySelector(`[data-message-text="${messageId}"]`);
        if (!textEl) return;

        const originalText = msg.mensagem_original || msg.mensagem;

        // Criar input de edição
        textEl.innerHTML = `
            <div class="edit-message-container">
                <input type="text" 
                       class="form-control form-control-sm" 
                       value="${this.escapeHtml(originalText)}" 
                       maxlength="2000"
                       id="edit-input-${messageId}">
                <div class="mt-2">
                    <button class="btn btn-sm btn-primary" onclick="chatSystem.saveEditMessage(${messageId})">
                        <i class="fas fa-check"></i> Salvar
                    </button>
                    <button class="btn btn-sm btn-secondary" onclick="chatSystem.cancelEditMessage(${messageId})">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </div>
        `;

        document.getElementById(`edit-input-${messageId}`).focus();
    }

    async saveEditMessage(messageId) {
    const input = document.getElementById(`edit-input-${messageId}`);
    if (!input) {
        console.error('[Chat] Input de edição não encontrado');
        return;
    }

    const newText = input.value.trim();
    if (!newText) {
        alert('A mensagem não pode estar vazia');
        return;
    }

    const msg = this.messages.find(m => m.id === parseInt(messageId));
    if (!msg) {
        console.error('[Chat] Mensagem não encontrada no array');
        this.cancelEditMessage(messageId);
        return;
    }

    const originalText = msg.mensagem_original || msg.mensagem;

    if (newText === originalText) {
        console.log('[Chat] Mensagem não foi alterada');
        this.cancelEditMessage(messageId);
        return;
    }

    // ✅ SALVAR POSIÇÃO DO SCROLL ANTES DE EDITAR
    const scrollContainer = this.messagesContainer;
    const currentScrollTop = scrollContainer.scrollTop;

    try {
        console.log('[Chat] 📤 Enviando edição', {
            messageId,
            newText: newText.substring(0, 50) + '...'
        });

        const response = await fetch(`/chat/mensagens/${messageId}/editar`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.getCsrfToken()
            },
            body: JSON.stringify({ mensagem: newText })
        });

        console.log('[Chat] 📥 Resposta recebida', {
            status: response.status,
            ok: response.ok
        });

        // ✅ LER RESPOSTA COMO TEXTO PRIMEIRO (PARA DEBUG)
        const responseText = await response.text();
        console.log('[Chat] 📄 Resposta (texto):', responseText.substring(0, 500));

        let data;
        try {
            data = JSON.parse(responseText);
        } catch (parseError) {
            console.error('[Chat] ❌ Erro ao parsear JSON:', parseError);
            console.error('[Chat] Resposta raw:', responseText);
            throw new Error('Resposta inválida do servidor');
        }

        if (!response.ok) {
            console.error('[Chat] ❌ Erro HTTP', {
                status: response.status,
                data
            });
            throw new Error(data.message || `Erro HTTP ${response.status}`);
        }

        if (!data.success) {
            throw new Error(data.message || 'Falha ao editar mensagem');
        }

        console.log('[Chat] ✅ Mensagem editada com sucesso');

        // ✅ ATUALIZAR LOCALMENTE
        this.updateMessageInArray(messageId, data.mensagem);
        this.renderMessages();

        // ✅ RESTAURAR SCROLL
        requestAnimationFrame(() => {
            scrollContainer.scrollTop = currentScrollTop;
        });

        this.showSuccess('Mensagem editada com sucesso!');

    } catch (error) {
        console.error('[Chat] Erro ao editar mensagem:', error);
        this.showError(error.message || 'Erro ao editar mensagem');
        this.cancelEditMessage(messageId);
        
        // ✅ RESTAURAR SCROLL MESMO EM CASO DE ERRO
        requestAnimationFrame(() => {
            scrollContainer.scrollTop = currentScrollTop;
        });
    }
}

    cancelEditMessage(messageId) {
        this.renderMessages();
    }

    // ==================== DELETAR MENSAGEM ====================
    async deleteMessage(messageId) {
        if (!confirm('Deseja realmente deletar esta mensagem?')) return;

        try {
            const response = await fetch(`/chat/mensagens/${messageId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            if (!response.ok) throw new Error('Erro ao deletar');

            const data = await response.json();
            
            // Remover localmente (o broadcast fará isso para os outros)
            this.handleDeletedMessage(messageId);
            
            this.showSuccess(data.message);

        } catch (error) {
            console.error('[Chat] Erro ao deletar mensagem:', error);
            this.showError('Erro ao deletar mensagem');
        }
    }

    // ==================== HANDLERS DE EVENTOS WEBSOCKET ====================
    
    handleNewMessage(message) {
        console.group('[Chat] 🆕 PROCESSAR NOVA MENSAGEM');
        console.log('1. Mensagem recebida:', message);
        console.log('2. ID:', message.id);
        console.log('3. Total antes:', this.messages.length);
        
        const exists = this.messages.find(m => m.id === message.id);
        console.log('4. Já existe?', !!exists);
        
        if (exists) {
            console.warn('⚠️ Duplicata ignorada');
            console.groupEnd();
            return;
        }

        this.messages.push(message);
        console.log('5. Total depois:', this.messages.length);
        
        const messageHtml = this.renderMessage(message);
        this.messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
        console.log('6. ✅ Inserido no DOM');

        this.attachMessageMenuListeners();
        this.scrollToBottom(true);
        
        console.log('7. ✅ Processamento completo');
        console.groupEnd();
    }

    handleDeletedMessage(messageId) {
        console.log('[Chat] 🗑️ Deletando mensagem:', messageId);
        
        // Remover do array
        this.messages = this.messages.filter(m => m.id !== messageId);
        
        // Remover do DOM com animação
        const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
        if (messageEl) {
            messageEl.style.opacity = '0';
            messageEl.style.transform = 'translateX(-20px)';
            setTimeout(() => messageEl.remove(), 300);
        }
        
        console.log('[Chat] ✅ Mensagem deletada');
    }

    handleEditedMessage(data) {
        console.log('[Chat] ✏️ Editando mensagem:', data.mensagem_id);
        
        // Atualizar no array
        this.updateMessageInArray(data.mensagem_id, data.nova_mensagem);
        
        // Re-renderizar apenas essa mensagem
        const messageEl = document.querySelector(`[data-message-id="${data.mensagem_id}"]`);
        if (messageEl) {
            const msg = this.messages.find(m => m.id === data.mensagem_id);
            if (msg) {
                messageEl.outerHTML = this.renderMessage(msg);
                this.attachMessageMenuListeners();
            }
        }
        
        console.log('[Chat] ✅ Mensagem editada');
    }

    updateMessageInArray(messageId, newText) {
        const msg = this.messages.find(m => m.id === parseInt(messageId));
        if (msg) {
            msg.mensagem = newText;
            msg.editada = true;
        }
    }

    // ==================== ENVIAR MENSAGEM ====================
    
    async handleSendMessage(e) {
        e.preventDefault();

        const messageText = this.messageInput.value.trim();
        if (!messageText && this.attachments.length === 0) return;

        if (this.attachments.length > 0 && window.NSFWDetector) {
        const imageFiles = this.attachments.filter(f => f.type.startsWith('image/'));
        
        for (const file of imageFiles) {
            try {
                const result = await window.NSFWDetector.analyze(file);
                if (result && result.isBlocked) {
                    alert(`A imagem "${file.name}" foi identificada como inapropriada. Remova-a para continuar.`);
                    return;
                }
            } catch (error) {
                console.error('[Chat] Erro na verificação NSFW:', error);
            }
        }
    }

        let flags = [];
        try {
            if (window.Moderation && typeof window.Moderation.checkLocal === 'function') {
                const localCheck = window.Moderation.checkLocal(messageText);
                flags = localCheck.matches || [];
            }
        } catch (modErr) {
            console.warn('[Chat] Moderação local falhou:', modErr);
        }

        const formData = new FormData();
        formData.append('mensagem', messageText);
        
        if (flags.length > 0) {
            flags.forEach((flag, index) => {
                formData.append(`flags[${index}]`, flag);
            });
        }

        this.attachments.forEach((file, index) => {
            formData.append(`anexos[${index}]`, file);
        });

        this.setLoading(true);
        this.sendBtn.disabled = true;

        try {
            const response = await fetch(`/salas/${this.salaId}/chat/enviar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: formData
            });

            const responseText = await response.text();
            console.log('[Chat] Response status:', response.status);

            let data;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                console.error('[Chat] Erro ao parsear JSON:', parseError);
                console.error('[Chat] Resposta raw:', responseText.substring(0, 1000));
                
                if (responseText.includes('<br') || responseText.includes('<!DOCTYPE') || responseText.includes('Exception')) {
                    throw new Error('Erro interno do servidor. Verifique os logs do Laravel.');
                }
                throw new Error('Resposta inválida do servidor');
            }

            if (!response.ok) {
                const errorMsg = data.message || data.error || `Erro HTTP ${response.status}`;
                console.error('[Chat] Erro do servidor:', data);
                
                if (data.errors) {
                    console.error('[Chat] Erros de validação:', data.errors);
                    const firstError = Object.values(data.errors)[0];
                    throw new Error(Array.isArray(firstError) ? firstError[0] : firstError);
                }
                throw new Error(errorMsg);
            }

            if (!data.success) {
                throw new Error(data.message || 'Falha ao enviar mensagem');
            }

            // Limpar form
            if (!data.success) {
                throw new Error(data.message || 'Falha ao enviar mensagem');
            }

            console.log('[Chat] ✅ Mensagem enviada com sucesso');

            // Limpar texto
            this.messageInput.value = '';
            
            // Limpar anexos (usando método dedicado)
            this.clearAttachments();
            
            // Esconder alerta
            this.hideModerationAlert();

            this.clearTypingState();
            
            // Focar no input
            this.messageInput.focus();

            console.log('[Chat] 🧹 Formulário limpo');
        } catch (error) {
            console.error('[Chat] Erro ao enviar mensagem:', error);
            this.showError(error.message || 'Erro ao enviar mensagem');
        } finally {
            this.setLoading(false);
            this.sendBtn.disabled = false;
        }
    }

    async handleInputModeration(e) {
        const text = e.target.value;
        if (!text || text.trim().length === 0) {
            this.hideModerationAlert();
            return;
        }

        const localCheck = window.Moderation.checkLocal(text);

        if (localCheck.inappropriate) {
            const flags = localCheck.matches || [];
            const shouldCensor = this.shouldCensorForAge(flags);

            if (shouldCensor) {
                this.showModerationAlert(flags);
            } else {
                this.hideModerationAlert();
            }
        } else {
            this.hideModerationAlert();
        }
    }

    shouldCensorForAge(flags) {
        if (this.userAge < 15) {
            return flags.some(f => ['swear', 'profanity'].includes(f));
        } else if (this.userAge >= 15) {
            return flags.some(f => ['sexual', 'porn'].includes(f));
        }
        return false;
    }

    showModerationAlert(flags) {
        this.moderationAlert.classList.remove('d-none');
        document.getElementById('chatModerationMessage').textContent = 
            `Conteúdo inapropriado detectado. Sua mensagem pode ser censurada ou resultar em ação de moderação.`;
    }

    hideModerationAlert() {
        this.moderationAlert.classList.add('d-none');
    }

     async handleFileSelect(e) {
        const files = Array.from(e.target.files);
        
        if (files.length === 0) {
            console.log('[Chat] ⚠️ Nenhum arquivo selecionado');
            return;
        }
        
        console.log('[Chat] 📎 Processando', files.length, 'arquivo(s)');
        
        for (const file of files) {
            console.log('[Chat] 📄 Arquivo:', {
                nome: file.name,
                tipo: file.type,
                tamanho: `${(file.size / 1024).toFixed(2)} KB`
            });
            
            // Validar quantidade
            if (this.attachments.length >= 5) {
                alert('Máximo de 5 anexos por mensagem');
                break;
            }

            // Validar tamanho (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert(`Arquivo ${file.name} muito grande (máx 10MB)`);
                continue;
            }

            // Validar se é imagem
            const isImage = file.type.startsWith('image/');
            if (!isImage) {
                alert(`Arquivo ${file.name} não é uma imagem válida.\nApenas imagens são permitidas (JPEG, PNG, GIF, WebP).`);
                continue;
            }

            // Verificar NSFW
            if (window.NSFWDetector) {
                try {
                    this.showModerationAlert(['Analisando imagem...']);
                    
                    const result = await window.NSFWDetector.analyze(file);
                    
                    if (result && result.isBlocked) {
                        this.hideModerationAlert();
                        alert(`A imagem "${file.name}" foi identificada como inapropriada e não pode ser enviada.`);
                        console.warn('[Chat] ❌ Imagem bloqueada por NSFW:', file.name);
                        continue;
                    }
                    
                    this.hideModerationAlert();
                } catch (error) {
                    console.error('[Chat] Erro na análise NSFW:', error);
                    this.hideModerationAlert();
                    // Continuar mesmo com erro
                }
            }

            this.attachments.push(file);
            console.log('[Chat] ✅ Arquivo adicionado:', file.name);
        }

        console.log('[Chat] 📊 Total de anexos:', this.attachments.length);
        
        if (this.attachments.length > 0) {
            this.renderAttachmentsPreview();
        }
    }

    renderAttachmentsPreview() {
        if (this.attachments.length === 0) {
            this.attachmentsPreview.style.display = 'none';
            this.attachmentsPreview.innerHTML = '';
            return;
        }

        // ✅ Revogar URLs antigas antes de criar novas
        const oldImages = this.attachmentsPreview.querySelectorAll('img');
        oldImages.forEach(img => {
            if (img.src.startsWith('blob:')) {
                URL.revokeObjectURL(img.src);
            }
        });

        this.attachmentsPreview.style.display = 'flex';
        this.attachmentsPreview.innerHTML = this.attachments.map((file, index) => {
            const url = URL.createObjectURL(file);
            return `
                <div class="ambience-attachment-item">
                    <img src="${url}" alt="${this.escapeHtml(file.name)}">
                    <button type="button" 
                            class="ambience-attachment-remove" 
                            data-index="${index}"
                            title="Remover ${this.escapeHtml(file.name)}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
            `;
        }).join('');

        // ✅ Adicionar listeners aos botões de remoção
        this.attachmentsPreview.querySelectorAll('.ambience-attachment-remove').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const index = parseInt(e.currentTarget.dataset.index);
                
                // Revogar URL do blob
                const img = btn.previousElementSibling;
                if (img && img.src.startsWith('blob:')) {
                    URL.revokeObjectURL(img.src);
                }
                
                // Remover do array
                this.attachments.splice(index, 1);
                
                console.log('[Chat] 🗑️ Anexo removido. Total:', this.attachments.length);
                
                // Re-renderizar
                this.renderAttachmentsPreview();
            });
        });
    }

     clearAttachments() {
        console.log('[Chat] 🧹 Limpando anexos...');
        
        // 1. Revogar todos os URLs de blob
        const images = this.attachmentsPreview.querySelectorAll('img');
        images.forEach(img => {
            if (img.src.startsWith('blob:')) {
                URL.revokeObjectURL(img.src);
            }
        });
        
        // 2. Limpar array
        this.attachments = [];
        
        // 3. Limpar preview
        this.attachmentsPreview.style.display = 'none';
        this.attachmentsPreview.innerHTML = '';
        
        // 4. Resetar input
        if (this.fileInput) {
            this.fileInput.value = '';
        }
        
        console.log('[Chat] ✅ Anexos limpos');
    }

    async viewCensoredContent(e) {
        const messageId = e.currentTarget.dataset.messageId;

        if (!confirm('Você tem 18+ anos e deseja ver o conteúdo censurado?')) return;

        try {
            const response = await fetch(`/chat/mensagens/${messageId}/ver-censurado`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            if (!response.ok) throw new Error('Não autorizado');

            const data = await response.json();
            alert(`Conteúdo original:\n\n${data.mensagem_original}`);

        } catch (error) {
            console.error('[Chat] Erro ao ver conteúdo:', error);
            this.showError('Você não tem permissão para ver este conteúdo');
        }
    }

    toggleMessageSelection(messageId) {
    const msg = this.messages.find(m => m.id === parseInt(messageId));
    if (!msg) return;
    
    // Impedir seleção de próprias mensagens
    if (parseInt(msg.usuario.id) === parseInt(this.userId)) {
        alert('Você não pode selecionar suas próprias mensagens para denúncia.');
        return;
    }
    
    const messageIdInt = parseInt(messageId);
    
    if (this.selectedMessages.has(messageIdInt)) {
        this.selectedMessages.delete(messageIdInt);
    } else {
        this.selectedMessages.add(messageIdInt);
    }

    const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
    if (messageEl) {
        messageEl.classList.toggle('ambience-message-selected', this.selectedMessages.has(messageIdInt));
    }

    console.log('[Chat] Mensagens selecionadas:', Array.from(this.selectedMessages));
    
    // ✅ FECHAR MENU ATUAL PARA FORÇAR ATUALIZAÇÃO
    document.querySelectorAll('.ambience-action-dropdown').forEach(m => m.remove());
}

    openReportModal(userId, messageId = null) {
        const modal = document.getElementById('reportModal');
        if (!modal) {
            console.error('[Chat] Modal de denúncia não encontrado');
            return;
        }

        document.getElementById('report_usuario_denunciado_id').value = userId;

        this.updateSelectedMessagesDisplay();

        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }

    updateSelectedMessagesDisplay() {
        const container = document.getElementById('selectedMessagesContainer');
        if (!container) return;

        if (this.selectedMessages.size === 0) {
            container.innerHTML = '<p class="text-muted">Nenhuma mensagem selecionada</p>';
            return;
        }

        const selectedMsgs = this.messages.filter(m => this.selectedMessages.has(m.id));
        
        container.innerHTML = `
            <div class="alert alert-info">
                <strong>${selectedMsgs.length} mensagem(ns) selecionada(s)</strong>
                <button class="btn btn-sm btn-link float-end" onclick="chatSystem.clearSelectedMessages()">
                    Limpar seleção
                </button>
            </div>
            ${selectedMsgs.map(m => `
                <div class="selected-message-preview">
                    <small><strong>${m.usuario.username}:</strong> ${this.escapeHtml(m.mensagem).substring(0, 50)}...</small>
                </div>
            `).join('')}
        `;
    }

    clearSelectedMessages() {
        this.selectedMessages.clear();
        document.querySelectorAll('.chat-message.selected').forEach(el => {
            el.classList.remove('selected');
        });
        this.updateSelectedMessagesDisplay();
    }

/**
 * Configurar comportamento flutuante do chat na grid
 */
setupFloatingBehavior() {
    console.log('[Chat] 🎮 Configurando chat flutuante para grid');
    
    // Adicionar classe para identificação
    this.container.classList.add('chat-in-grid');
    
    // ✅ Inicializar variáveis de controle
    this.resizeState = null;
    this.dragState = null;
    this.dimensionsBadge = null;
    this.currentResizeHandlers = null;
    this.currentDragHandlers = null;
    
    // Restaurar posição e tamanho salvos
    this.restoreChatState();
    
    // Adicionar handles de resize
    this.addResizeHandles();
    
    // Tornar arrastável
    this.makeDraggable();
    
    // Adicionar badge de dimensões
    this.addDimensionsBadge();
    
    // Salvar estado ao redimensionar/mover
    this.setupAutoSave();
}

/**
 * Adicionar handles de resize
 */
/**
 * Adicionar handles de resize
 */
addResizeHandles() {
    // Remover handles existentes primeiro
    this.container.querySelectorAll('.chat-resize-handle').forEach(h => h.remove());
    
    const handles = `
        <!-- Bordas -->
        <div class="chat-resize-handle chat-resize-handle-top" data-direction="n"></div>
        <div class="chat-resize-handle chat-resize-handle-bottom" data-direction="s"></div>
        <div class="chat-resize-handle chat-resize-handle-left" data-direction="w"></div>
        <div class="chat-resize-handle chat-resize-handle-right" data-direction="e"></div>
        
        <!-- Cantos -->
        <div class="chat-resize-handle chat-resize-handle-corner chat-resize-handle-nw" data-direction="nw"></div>
        <div class="chat-resize-handle chat-resize-handle-corner chat-resize-handle-ne" data-direction="ne"></div>
        <div class="chat-resize-handle chat-resize-handle-corner chat-resize-handle-sw" data-direction="sw"></div>
        <div class="chat-resize-handle chat-resize-handle-corner chat-resize-handle-se" data-direction="se"></div>
    `;
    
    this.container.insertAdjacentHTML('beforeend', handles);
    
    // Configurar eventos após DOM atualizar
    requestAnimationFrame(() => {
        this.setupResizeEvents();
    });
    
    console.log('[Chat] ✅ Handles de resize adicionados');
}


/**
 * Configurar eventos de resize
 */
setupResizeEvents() {
    const handles = this.container.querySelectorAll('.chat-resize-handle');
    
    console.log('[Chat] 🔧 Configurando', handles.length, 'handles de resize');
    
    handles.forEach((handle, index) => {
        handle.addEventListener('mousedown', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.startResize(e);
        });
        
        console.log(`[Chat] ✅ Handle ${index + 1}:`, handle.dataset.direction);
    });
}

startResize(e) {
    const target = e.target.closest('.chat-resize-handle');
    if (!target) return;
    
    const direction = target.dataset.direction;
    if (!direction) return;
    
    console.log('[Chat] 🔲 Preparando resize:', direction);
    
    this.resizeState = {
        isResizing: false,
        isWaitingForMovement: true,
        direction: direction,
        startX: e.clientX,
        startY: e.clientY,
        startWidth: this.container.offsetWidth,
        startHeight: this.container.offsetHeight,
        startLeft: this.container.offsetLeft,
        startTop: this.container.offsetTop
    };
    
    const cursorMap = {
        'n': 'ns-resize', 's': 'ns-resize', 'e': 'ew-resize', 'w': 'ew-resize',
        'ne': 'nesw-resize', 'nw': 'nwse-resize', 'se': 'nwse-resize', 'sw': 'nesw-resize'
    };
    document.body.style.cursor = cursorMap[direction] || 'default';
    
    // ✅ CRÍTICO: Criar funções bound para poder remover depois
    const moveHandler = (e) => this.doResize(e);
    const stopHandler = () => this.stopResize();
    
    this.currentResizeHandlers = { move: moveHandler, stop: stopHandler };
    
    // ✅ CRÍTICO: Listeners no DOCUMENT, não no container
    document.addEventListener('mousemove', moveHandler, true);
    document.addEventListener('mouseup', stopHandler, true);
    
    console.log('[Chat] ✅ Listeners adicionados');
}

doResize(e) {
    if (!this.resizeState) return;
    
    const { direction, startX, startY, startWidth, startHeight, startLeft, startTop, isWaitingForMovement } = this.resizeState;
    
    const deltaX = e.clientX - startX;
    const deltaY = e.clientY - startY;
    
    // ✅ SE AINDA ESTÁ AGUARDANDO MOVIMENTO, VERIFICAR SE PASSOU DO THRESHOLD
    if (isWaitingForMovement) {
        const MOVEMENT_THRESHOLD = 5; // pixels mínimos de movimento
        const totalMovement = Math.sqrt(deltaX * deltaX + deltaY * deltaY);
        
        if (totalMovement < MOVEMENT_THRESHOLD) {
            // Ainda não moveu o suficiente, não fazer nada
            return;
        }
        
        // ✅ PASSOU DO THRESHOLD - ATIVAR RESIZE AGORA!
        console.log('[Chat] 🔲 Resize ativado! Movimento:', totalMovement.toFixed(2), 'px');
        this.resizeState.isResizing = true;
        this.resizeState.isWaitingForMovement = false;
        
        // Ativar feedback visual AGORA
        this.container.classList.add('is-resizing');
        document.body.style.userSelect = 'none';
    }
    
    // ✅ SÓ EXECUTA SE JÁ PASSOU DO THRESHOLD
    if (!this.resizeState.isResizing) return;
    
    let newWidth = startWidth;
    let newHeight = startHeight;
    let newLeft = startLeft;
    let newTop = startTop;
    
    const minWidth = 320;
    const minHeight = 400;
    const maxWidth = window.innerWidth - 40;
    const maxHeight = window.innerHeight - 40;
    
    // ✅ HORIZONTAL (Leste/Oeste)
    if (direction.includes('e')) {
        // Arrastar para DIREITA = aumenta largura
        newWidth = startWidth + deltaX;
        newWidth = Math.max(minWidth, Math.min(maxWidth, newWidth));
    }
    
    if (direction.includes('w')) {
        // Arrastar para ESQUERDA = diminui largura E move container
        const proposedWidth = startWidth - deltaX;
        if (proposedWidth >= minWidth && proposedWidth <= maxWidth) {
            newWidth = proposedWidth;
            newLeft = startLeft + deltaX;
            
            // Não ultrapassar borda esquerda
            if (newLeft < 0) {
                newWidth = startWidth + startLeft;
                newLeft = 0;
            }
        }
    }
    
    // ✅ VERTICAL (Norte/Sul)
    if (direction.includes('s')) {
        // Arrastar para BAIXO = aumenta altura
        newHeight = startHeight + deltaY;
        newHeight = Math.max(minHeight, Math.min(maxHeight, newHeight));
    }
    
    if (direction.includes('n')) {
        // Arrastar para CIMA = diminui altura E move container
        const proposedHeight = startHeight - deltaY;
        if (proposedHeight >= minHeight && proposedHeight <= maxHeight) {
            newHeight = proposedHeight;
            newTop = startTop + deltaY;
            
            // Não ultrapassar borda superior
            if (newTop < 0) {
                newHeight = startHeight + startTop;
                newTop = 0;
            }
        }
    }
    
    // ✅ Não sair da tela (lado direito)
    if (newLeft + newWidth > window.innerWidth) {
        newWidth = window.innerWidth - newLeft;
    }
    
    // ✅ Não sair da tela (lado inferior)
    if (newTop + newHeight > window.innerHeight) {
        newHeight = window.innerHeight - newTop;
    }
    
    // ✅ Aplicar estilos em tempo real
    this.container.style.setProperty('width', `${newWidth}px`, 'important');
    this.container.style.setProperty('height', `${newHeight}px`, 'important');
    this.container.style.setProperty('left', `${newLeft}px`, 'important');
    this.container.style.setProperty('top', `${newTop}px`, 'important');
    this.container.style.setProperty('right', 'auto', 'important');
    this.container.style.setProperty('bottom', 'auto', 'important');
    
    // Atualizar badge
    this.updateDimensionsBadge(newWidth, newHeight);
}

/**
 * Parar resize (AO SOLTAR O MOUSE)
 */
stopResize() {
    console.log('[Chat] 🛑 stopResize() DISPARADO');
    
    if (!this.resizeState) {
        console.log('[Chat] ⚠️ Já estava limpo');
        return;
    }
    
    // ✅ REMOVER LISTENERS COM CAPTURE=TRUE
    if (this.currentResizeHandlers) {
        document.removeEventListener('mousemove', this.currentResizeHandlers.move, true);
        document.removeEventListener('mouseup', this.currentResizeHandlers.stop, true);
        this.currentResizeHandlers = null;
        console.log('[Chat] 🧹 Listeners REMOVIDOS');
    }
    
    // ✅ Limpar visual
    this.container.classList.remove('is-resizing');
    document.body.style.userSelect = '';
    document.body.style.cursor = '';
    
    // ✅ Salvar se redimensionou
    if (this.resizeState.isResizing) {
        this.saveChatState();
    }
    
    // ✅ Resetar estado
    this.resizeState = null;
    
    console.log('[Chat] ✅✅✅ RESIZE COMPLETAMENTE LIMPO');
}


/**
 * Tornar chat arrastável (COM DRAG)
 */
makeDraggable() {
    const header = this.container.querySelector('.ambience-chat-top');
    if (!header) {
        console.warn('[Chat] Header não encontrado');
        return;
    }
    
    header.style.cursor = 'grab';
    header.title = 'Arrastar para mover o chat';
    
    const onMouseDown = (e) => {
        // Não arrastar se clicar em botões ou handles
        if (e.target.closest('.ambience-toggle-btn') || 
            e.target.closest('.chat-resize-handle')) {
            return;
        }
        
        // Só arrastar se clicar no header
        if (!e.target.closest('.ambience-chat-top')) {
            return;
        }
        
        e.preventDefault();
        e.stopPropagation();
        
        console.log('[Chat] 🖐️ Iniciando drag');
        
        // ✅ Guardar estado inicial
        this.dragState = {
            isDragging: true,
            startX: e.clientX,
            startY: e.clientY,
            startLeft: this.container.offsetLeft,
            startTop: this.container.offsetTop
        };
        
        // ✅ Feedback visual
        this.container.classList.add('is-dragging');
        this.container.style.transition = 'none';
        header.style.cursor = 'grabbing';
        document.body.style.userSelect = 'none';
        
        // ✅ Criar handlers removíveis
        this.currentDragHandlers = {
            move: (e) => this.doDrag(e),
            stop: () => this.stopDrag()
        };
        
        // Adicionar listeners
        document.addEventListener('mousemove', this.currentDragHandlers.move);
        document.addEventListener('mouseup', this.currentDragHandlers.stop);
        document.addEventListener('mouseleave', this.currentDragHandlers.stop);
    };
    
    header.addEventListener('mousedown', onMouseDown);
    
    console.log('[Chat] ✅ Drag configurado');
}

/**
 * Executar drag (ENQUANTO ARRASTA)
 */
doDrag(e) {
    if (!this.dragState?.isDragging) return;
    
    e.preventDefault();
    e.stopPropagation();
    
    const deltaX = e.clientX - this.dragState.startX;
    const deltaY = e.clientY - this.dragState.startY;
    
    let newLeft = this.dragState.startLeft + deltaX;
    let newTop = this.dragState.startTop + deltaY;
    
    // Limites da tela
    const maxX = window.innerWidth - this.container.offsetWidth;
    const maxY = window.innerHeight - this.container.offsetHeight;
    
    newLeft = Math.max(0, Math.min(newLeft, maxX));
    newTop = Math.max(0, Math.min(newTop, maxY));
    
    this.container.style.left = `${newLeft}px`;
    this.container.style.top = `${newTop}px`;
    this.container.style.right = 'auto';
    this.container.style.bottom = 'auto';
}

/**
 * Parar drag (AO SOLTAR O MOUSE)
 */
stopDrag() {
    if (!this.dragState?.isDragging) return;
    
    console.log('[Chat] ✅ Drag finalizado');
    
    const header = this.container.querySelector('.ambience-chat-top');
    
    // ✅ Remover listeners
    if (this.currentDragHandlers) {
        document.removeEventListener('mousemove', this.currentDragHandlers.move);
        document.removeEventListener('mouseup', this.currentDragHandlers.stop);
        document.removeEventListener('mouseleave', this.currentDragHandlers.stop);
        this.currentDragHandlers = null;
    }
    
    // ✅ Resetar visual
    this.container.classList.remove('is-dragging');
    this.container.style.transition = '';
    if (header) header.style.cursor = 'grab';
    document.body.style.userSelect = '';
    
    // ✅ Resetar estado
    this.dragState = null;
    
    // Salvar
    this.saveChatState();
}

/**
 * Adicionar badge de dimensões
 */
addDimensionsBadge() {
    const badge = document.createElement('div');
    badge.className = 'chat-dimensions-badge';
    badge.textContent = '0 × 0';
    this.container.appendChild(badge);
    this.dimensionsBadge = badge;
}

/**
 * Atualizar badge de dimensões
 */
updateDimensionsBadge(width, height) {
    if (this.dimensionsBadge) {
        this.dimensionsBadge.textContent = `${Math.round(width)} × ${Math.round(height)}`;
    }
}

/**
 * Salvar estado do chat
 */
saveChatState() {
    const state = {
        width: this.container.offsetWidth,
        height: this.container.offsetHeight,
        left: this.container.offsetLeft,
        top: this.container.offsetTop
    };
    
    try {
        localStorage.setItem('ambience_chat_state', JSON.stringify(state));
        console.log('[Chat] 💾 Estado salvo:', state);
    } catch (error) {
        console.warn('[Chat] Erro ao salvar:', error);
    }
}

/**
 * Restaurar estado do chat
 */
restoreChatState() {
    try {
        const savedState = localStorage.getItem('ambience_chat_state');
        if (!savedState) return;
        
        const state = JSON.parse(savedState);
        
        const minWidth = 320;
        const minHeight = 400;
        const maxWidth = window.innerWidth - 40;
        const maxHeight = window.innerHeight - 40;
        
        const width = Math.max(minWidth, Math.min(state.width, maxWidth));
        const height = Math.max(minHeight, Math.min(state.height, maxHeight));
        const left = Math.max(0, Math.min(state.left, window.innerWidth - width));
        const top = Math.max(0, Math.min(state.top, window.innerHeight - height));
        
        this.container.style.width = `${width}px`;
        this.container.style.height = `${height}px`;
        this.container.style.left = `${left}px`;
        this.container.style.top = `${top}px`;
        this.container.style.right = 'auto';
        this.container.style.bottom = 'auto';
        
        console.log('[Chat] ✅ Estado restaurado:', { width, height, left, top });
    } catch (error) {
        console.warn('[Chat] Erro ao restaurar:', error);
    }
}

/**
 * Auto-save ao redimensionar janela
 */
setupAutoSave() {
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const maxWidth = window.innerWidth - 40;
            const maxHeight = window.innerHeight - 40;
            
            if (this.container.offsetWidth > maxWidth) {
                this.container.style.width = `${maxWidth}px`;
            }
            if (this.container.offsetHeight > maxHeight) {
                this.container.style.height = `${maxHeight}px`;
            }
            
            const maxLeft = window.innerWidth - this.container.offsetWidth;
            const maxTop = window.innerHeight - this.container.offsetHeight;
            
            if (this.container.offsetLeft > maxLeft) {
                this.container.style.left = `${Math.max(0, maxLeft)}px`;
            }
            if (this.container.offsetTop > maxTop) {
                this.container.style.top = `${Math.max(0, maxTop)}px`;
            }
            
            this.saveChatState();
        }, 250);
    });
}

/**
 * Limpar todos os listeners
 */
cleanup() {
    console.log('[Chat] 🧹 Limpando listeners...');
    
    // Limpar resize
    if (this.currentResizeHandlers) {
        document.removeEventListener('mousemove', this.currentResizeHandlers.move);
        document.removeEventListener('mouseup', this.currentResizeHandlers.stop);
        document.removeEventListener('mouseleave', this.currentResizeHandlers.stop);
        this.currentResizeHandlers = null;
    }
    
    // Limpar drag
    if (this.currentDragHandlers) {
        document.removeEventListener('mousemove', this.currentDragHandlers.move);
        document.removeEventListener('mouseup', this.currentDragHandlers.stop);
        document.removeEventListener('mouseleave', this.currentDragHandlers.stop);
        this.currentDragHandlers = null;
    }
    
    // Limpar typing
    this.clearTypingState();
    
    console.log('[Chat] ✅ Cleanup completo');
}

    scrollToBottom(smooth = false) {
        const scrollOptions = {
            top: this.messagesContainer.scrollHeight,
            behavior: smooth ? 'smooth' : 'auto'
        };
        
        this.messagesContainer.scrollTo(scrollOptions);
        
        setTimeout(() => {
            this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
        }, smooth ? 100 : 0);
    }

    toggleChat() {
        this.messagesContainer.classList.toggle('collapsed');
        const icon = document.querySelector('#toggleChatBtn i');
        if (icon) {
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        }
    }

    setLoading(loading) {
        this.loading = loading;
        if (loading) {
            this.messagesContainer.style.opacity = '0.6';
        } else {
            this.messagesContainer.style.opacity = '1';
        }
    }

    showError(message) {
        alert(message);
    }

    showSuccess(message) {
        console.log('[Chat] Success:', message);
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

document.addEventListener('mouseup', () => {
    console.log('[DEBUG] 🖱️ MouseUp global detectado');
    if (window.chatSystem && window.chatSystem.resizeState) {
        console.log('[DEBUG] ⚠️ ResizeState ainda ativo:', window.chatSystem.resizeState);
    }
});

// Inicializar
let chatSystem;
document.addEventListener('DOMContentLoaded', () => {
    const chatContainer = document.getElementById('chatContainer');
    if (chatContainer) {
        chatSystem = new ChatSystem('chatContainer');
        window.chatSystem = chatSystem;
    }
});

window.addEventListener('beforeunload', () => {
    if (chatSystem && typeof chatSystem.cleanup === 'function') {
        chatSystem.cleanup();
    }
});

// ✅ Limpar ao ocultar página
document.addEventListener('visibilitychange', () => {
    if (document.hidden && chatSystem) {
        if (chatSystem.resizeState?.isResizing) {
            chatSystem.stopResize();
        }
        if (chatSystem.dragState?.isDragging) {
            chatSystem.stopDrag();
        }
    }
});