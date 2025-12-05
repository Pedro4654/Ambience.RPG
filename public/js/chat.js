/**
 * ========================================
 * SISTEMA DE CHAT - AMBIENCE RPG
 * ========================================
 * ✅ REFATORADO: Pointer Events para drag + resize robusto
 * ✅ NOVO: Sistema PiP-like de redimensionamento
 * ✅ CORRIGIDO: Frozen edges, setPointerCapture, RAF
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

<<<<<<< HEAD
=======
        this.isInGrid = window.location.pathname.includes('/sessoes/');
        if (this.isInGrid) {
            console.log('[Chat] 🎮 Chat iniciado na GRID');
        }

>>>>>>> 7dbc97f (Chat fix)
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
<<<<<<< HEAD
        this.channel = null; // Armazenar referência do canal
         this.usersTyping = new Map(); // Map<userId, {username, timeout}>
    this.typingTimeout = null;
    this.isTyping = false;
    this.typingDebounceMs = 2000;
=======
        this.channel = null;
        this.usersTyping = new Map();
        this.typingTimeout = null;
        this.isTyping = false;
        this.typingDebounceMs = 2000;
        
        // ✅ NOVO: Estado Pointer Events
        this.pointerResizeState = null;
        this.pointerDragState = null;
        this.raf = null; // requestAnimationFrame ID
        this.dimensionsBadge = null;
        
        // Edge detection constants
        this.EDGE_SIZE = 20;
        this.MIN_WIDTH = 320;
        this.MIN_HEIGHT = 400;
>>>>>>> 7dbc97f (Chat fix)

        this.init();
    }

    async init() {
        console.log('[Chat] 🚀 Inicializando...', {
            salaId: this.salaId,
            userId: this.userId,
            userAge: this.userAge
        });

        await this.initModeration();
        await this.loadMessages();
        this.setupEventListeners();
        this.setupWebSocket();

<<<<<<< HEAD
        setTimeout(() => {
    this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
    console.log('[Chat] 📍 Scroll posicionado no final:', this.messagesContainer.scrollHeight);
}, 300);
=======
        if (this.isInGrid) {
            setTimeout(() => {
                this.setupFloatingBehavior();
            }, 100);
        }
        
        setTimeout(() => {
            this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
            console.log('[Chat] 📍 Scroll posicionado no final:', this.messagesContainer.scrollHeight);
        }, 300);
>>>>>>> 7dbc97f (Chat fix)

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
        this.chatForm.addEventListener('submit', (e) => this.handleSendMessage(e));
        
        this.messageInput.addEventListener('input', (e) => {
            this.handleInputModeration(e);
            this.handleTypingInput(e);
        });

        const toggleBtn = document.getElementById('toggleChatBtn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggleChat());
        }
        
        const attachBtn = document.getElementById('chatAttachBtn');
        if (attachBtn && this.fileInput) {
            console.log('[Chat] ✅ Configurando botão de anexo');
            
            attachBtn.replaceWith(attachBtn.cloneNode(true));
            const newAttachBtn = document.getElementById('chatAttachBtn');
            
            newAttachBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                console.log('[Chat] 🖱️ Botão de anexo clicado');
                this.fileInput.click();
            });
        }
        
        if (this.fileInput) {
            console.log('[Chat] ✅ Configurando input de arquivo');
            
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

        this.channel.listen('.nova.mensagem', (data) => {
            console.group('[Chat] 📨 EVENTO: Nova Mensagem');
            console.log('Raw data:', data);
            
            const mensagem = data.id ? data : (data.mensagem || data.message || data);
            
            console.log('Mensagem extraída:', mensagem);
            
            if (!mensagem || !mensagem.id) {
                console.error('❌ Payload inválido');
                console.groupEnd();
                return;
            }
            
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

        this.channel.listen('.mensagem.deletada', (data) => {
            console.group('[Chat] 🗑️ EVENTO: Mensagem Deletada');
            console.log('Data:', data);
            
            if (!data.mensagem_id) {
                console.error('❌ ID da mensagem não encontrado');
                console.groupEnd();
                return;
            }
            
            console.log('✅ Removendo mensagem do DOM');
            console.groupEnd();
            
            this.handleDeletedMessage(data.mensagem_id);
        });

        this.channel.listen('.mensagem.editada', (data) => {
            console.group('[Chat] ✏️ EVENTO: Mensagem Editada');
            console.log('Data:', data);
            
            if (!data.mensagem_id || !data.nova_mensagem) {
                console.error('❌ Dados incompletos');
                console.groupEnd();
                return;
            }
            
            console.log('✅ Atualizando mensagem no DOM');
            console.groupEnd();
            
            this.handleEditedMessage(data);
        });

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
        
        setTimeout(() => {
            const subscription = window.Echo.connector.channels[`presence-${channelName}`];
            if (subscription) {
                console.log('[Chat] ✅ Canal subscrito:', channelName);
                console.log('[Chat] Subscription state:', subscription.subscription_state);
            } else {
                console.error('[Chat] ❌ Falha ao subscrever no canal');
            }
        }, 1000);
<<<<<<< HEAD
        
=======

>>>>>>> 7dbc97f (Chat fix)
        console.log('[Chat] ✅ WebSocket configurado');
    }

    handleTypingInput(e) {
        const text = e.target.value.trim();
        
        if (!text) {
            if (this.isTyping) {
                this.notifyTyping(false);
            }
            return;
        }
        
        if (!this.isTyping) {
            this.notifyTyping(true);
        }
        
        clearTimeout(this.typingTimeout);
        
        this.typingTimeout = setTimeout(() => {
            if (this.isTyping) {
                this.notifyTyping(false);
            }
        }, this.typingDebounceMs);
    }

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

    handleTypingEvent(data) {
        const userId = parseInt(data.user_id);
        const username = data.username;
        const isTyping = data.is_typing;
        
        if (userId === parseInt(this.userId)) {
            return;
        }
        
        if (isTyping) {
            this.usersTyping.set(userId, {
                username: username,
                timeout: setTimeout(() => {
                    this.usersTyping.delete(userId);
                    this.updateTypingIndicator();
                }, 5000)
            });
        } else {
            const userData = this.usersTyping.get(userId);
            if (userData && userData.timeout) {
                clearTimeout(userData.timeout);
            }
            this.usersTyping.delete(userId);
        }
        
        this.updateTypingIndicator();
    }

    updateTypingIndicator() {
        const typingCount = this.usersTyping.size;
        
        if (typingCount === 0) {
            this.typingIndicator.style.display = 'none';
            return;
        }
        
        this.typingIndicator.style.display = 'block';
        
        let text = '';
        
        if (typingCount === 1) {
            const [userData] = this.usersTyping.values();
            text = `${userData.username} digitando…`;
        } else if (typingCount <= 3) {
            const names = Array.from(this.usersTyping.values()).map(u => u.username);
            const lastUser = names.pop();
            text = `${names.join(', ')} e ${lastUser} digitando…`;
        } else {
            text = 'Várias pessoas estão digitando…';
        }
        
        this.typingText.textContent = text;
    }

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
                this.scrollToBottom(false);
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
        
        if (isCensored && flags.length > 0) {
            const temProfanity = flags.includes('profanity');
            const temConteudoSexual = flags.includes('sexual') || flags.includes('porn');
            
            if (userAge < 15) {
                if (temProfanity || temConteudoSexual) {
                    mensagemExibida = msg.mensagem;
                    deveCensurar = true;
                    podeVerCensurado = false;
                }
            } else if (userAge >= 15 && userAge < 18) {
                if (temConteudoSexual) {
                    deveCensurar = true;
                    podeVerCensurado = false;
                    mensagemExibida = '[Mensagem oculta – violou as regras de conteúdo]';
                } else if (temProfanity) {
                    mensagemExibida = msg.mensagem_original || msg.mensagem;
                    deveCensurar = false;
                    podeVerCensurado = false;
                }
            } else if (userAge >= 18) {
                if (temConteudoSexual) {
                    deveCensurar = true;
                    podeVerCensurado = true;
                    mensagemExibida = '[Mensagem oculta – violou as regras de conteúdo]';
                } else if (temProfanity) {
                    mensagemExibida = msg.mensagem_original || msg.mensagem;
                    deveCensurar = false;
                    podeVerCensurado = false;
                }
            }
        }

        const avatarHtml = msg.usuario.avatar_url 
            ? `<img src="${msg.usuario.avatar_url}" 
                    alt="${msg.usuario.username}" 
                    class="ambience-avatar"
                    style="width:38px!important;height:38px!important;object-fit:cover!important;border-radius:50%!important;">`
            : `<div class="ambience-avatar-fallback" 
                    style="width:38px!important;height:38px!important;min-width:38px!important;">
                    ${msg.usuario.username.charAt(0).toUpperCase()}
               </div>`;
        
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
        document.querySelectorAll('.ambience-action-menu-btn-inline').forEach(btn => {
            btn.addEventListener('click', (e) => this.showMessageMenu(e));
        });

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
        
        const scrollContainer = this.messagesContainer;
        const currentScrollTop = scrollContainer.scrollTop;
        
        if (isRevealed) {
            const msg = this.messages.find(m => m.id === parseInt(messageId));
            if (msg) {
                bubbleEl.textContent = '[Mensagem oculta — violou as regras de conteúdo]';
                bubbleEl.classList.add('ambience-bubble-censored');
                
                btn.dataset.revealed = 'false';
                btn.querySelector('span').textContent = 'Exibir';
                btn.title = 'Exibir mensagem original';
            }
        } else {
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
        
        const isSelected = this.selectedMessages.has(parseInt(messageId));

        document.querySelectorAll('.ambience-action-dropdown').forEach(m => m.remove());

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

        document.body.appendChild(menu);
        const menuHeight = menu.offsetHeight;

        let top = rect.bottom + 4;
        let left = rect.left;

        if (left + menuWidth > window.innerWidth - 8) {
            left = rect.right - menuWidth;
        }

        if (left < 8) {
            left = 8;
        }

        if (top + menuHeight > window.innerHeight - 8) {
            top = rect.top - menuHeight - 4;
            
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
                if (messageId) {
                    this.selectedMessages.add(parseInt(messageId));
                    const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
                    if (messageEl) {
                        messageEl.classList.add('ambience-message-selected');
                    }
                }
                
                if (window.reportModal) {
                    window.reportModal.open(userId);
                } else if (window.openReportModal) {
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

    startEditMessage(messageId) {
        const msg = this.messages.find(m => m.id === parseInt(messageId));
        if (!msg) return;

        const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
        if (!messageEl) return;

        const textEl = messageEl.querySelector(`[data-message-text="${messageId}"]`);
        if (!textEl) return;

        const originalText = msg.mensagem_original || msg.mensagem;

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
            this.cancelEditMessage(messageId);
            return;
        }

        const scrollContainer = this.messagesContainer;
        const currentScrollTop = scrollContainer.scrollTop;

        try {
            const response = await fetch(`/chat/mensagens/${messageId}/editar`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify({ mensagem: newText })
            });

            const responseText = await response.text();
            let data;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                console.error('[Chat] ❌ Erro ao parsear JSON:', parseError);
                throw new Error('Resposta inválida do servidor');
            }

            if (!response.ok) {
                throw new Error(data.message || `Erro HTTP ${response.status}`);
            }

            if (!data.success) {
                throw new Error(data.message || 'Falha ao editar mensagem');
            }

            this.updateMessageInArray(messageId, data.mensagem);
            this.renderMessages();

            requestAnimationFrame(() => {
                scrollContainer.scrollTop = currentScrollTop;
            });

            this.showSuccess('Mensagem editada com sucesso!');

        } catch (error) {
            console.error('[Chat] Erro ao editar mensagem:', error);
            this.showError(error.message || 'Erro ao editar mensagem');
            this.cancelEditMessage(messageId);
            
            requestAnimationFrame(() => {
                scrollContainer.scrollTop = currentScrollTop;
            });
        }
    }

    cancelEditMessage(messageId) {
        this.renderMessages();
    }

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
            
            this.handleDeletedMessage(messageId);
            
            this.showSuccess(data.message);

        } catch (error) {
            console.error('[Chat] Erro ao deletar mensagem:', error);
            this.showError('Erro ao deletar mensagem');
        }
    }

    handleNewMessage(message) {
        const exists = this.messages.find(m => m.id === message.id);
        
        if (exists) {
            return;
        }

        this.messages.push(message);
        
        const messageHtml = this.renderMessage(message);
        this.messagesContainer.insertAdjacentHTML('beforeend', messageHtml);

        this.attachMessageMenuListeners();
        this.scrollToBottom(true);
    }

    handleDeletedMessage(messageId) {
        this.messages = this.messages.filter(m => m.id !== messageId);
        
        const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
        if (messageEl) {
            messageEl.style.opacity = '0';
            messageEl.style.transform = 'translateX(-20px)';
            setTimeout(() => messageEl.remove(), 300);
        }
    }

    handleEditedMessage(data) {
        this.updateMessageInArray(data.mensagem_id, data.nova_mensagem);
        
        const messageEl = document.querySelector(`[data-message-id="${data.mensagem_id}"]`);
        if (messageEl) {
            const msg = this.messages.find(m => m.id === data.mensagem_id);
            if (msg) {
                messageEl.outerHTML = this.renderMessage(msg);
                this.attachMessageMenuListeners();
            }
        }
    }

    updateMessageInArray(messageId, newText) {
        const msg = this.messages.find(m => m.id === parseInt(messageId));
        if (msg) {
            msg.mensagem = newText;
            msg.editada = true;
        }
    }

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

            let data;
            try {
                data = JSON.parse(responseText);
            } catch (parseError) {
                console.error('[Chat] Erro ao parsear JSON:', parseError);
                
                if (responseText.includes('<br') || responseText.includes('<!DOCTYPE') || responseText.includes('Exception')) {
                    throw new Error('Erro interno do servidor. Verifique os logs do Laravel.');
                }
                throw new Error('Resposta inválida do servidor');
            }

            if (!response.ok) {
                const errorMsg = data.message || data.error || `Erro HTTP ${response.status}`;
                
                if (data.errors) {
                    const firstError = Object.values(data.errors)[0];
                    throw new Error(Array.isArray(firstError) ? firstError[0] : firstError);
                }
                throw new Error(errorMsg);
            }

            if (!data.success) {
                throw new Error(data.message || 'Falha ao enviar mensagem');
            }

            this.messageInput.value = '';
            this.clearAttachments();
            this.hideModerationAlert();
            this.clearTypingState();
            this.messageInput.focus();

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
            return;
        }
        
        for (const file of files) {
            if (this.attachments.length >= 5) {
                alert('Máximo de 5 anexos por mensagem');
                break;
            }

            if (file.size > 10 * 1024 * 1024) {
                alert(`Arquivo ${file.name} muito grande (máx 10MB)`);
                continue;
            }

            const isImage = file.type.startsWith('image/');
            if (!isImage) {
                alert(`Arquivo ${file.name} não é uma imagem válida.\nApenas imagens são permitidas (JPEG, PNG, GIF, WebP).`);
                continue;
            }

            if (window.NSFWDetector) {
                try {
                    this.showModerationAlert(['Analisando imagem...']);
                    
                    const result = await window.NSFWDetector.analyze(file);
                    
                    if (result && result.isBlocked) {
                        this.hideModerationAlert();
                        alert(`A imagem "${file.name}" foi identificada como inapropriada e não pode ser enviada.`);
                        continue;
                    }
                    
                    this.hideModerationAlert();
                } catch (error) {
                    console.error('[Chat] Erro na análise NSFW:', error);
                    this.hideModerationAlert();
                }
            }

            this.attachments.push(file);
        }

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

        this.attachmentsPreview.querySelectorAll('.ambience-attachment-remove').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const index = parseInt(e.currentTarget.dataset.index);
                
                const img = btn.previousElementSibling;
                if (img && img.src.startsWith('blob:')) {
                    URL.revokeObjectURL(img.src);
                }
                
                this.attachments.splice(index, 1);
                
                this.renderAttachmentsPreview();
            });
        });
    }

    clearAttachments() {
        const images = this.attachmentsPreview.querySelectorAll('img');
        images.forEach(img => {
            if (img.src.startsWith('blob:')) {
                URL.revokeObjectURL(img.src);
            }
        });
        
        this.attachments = [];
        this.attachmentsPreview.style.display = 'none';
        this.attachmentsPreview.innerHTML = '';
        
        if (this.fileInput) {
            this.fileInput.value = '';
        }
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

<<<<<<< HEAD
=======
    /**
     * ========================================
     * FLOATING BEHAVIOR - POINTER EVENTS
     * ========================================
     */
    setupFloatingBehavior() {
        console.log('[Chat] 🎮 Configurando chat flutuante (Pointer Events)');
        
        this.container.classList.add('chat-in-grid');
        
        this.restoreChatState();
        this.addResizeHandles();
        this.setupPointerEvents();
        this.addDimensionsBadge();
        this.setupAutoSave();
        
        console.log('[Chat] ✅ Pointer Events configurados');
    }

    /**
     * Setup Pointer Events for drag and resize
     */
    setupPointerEvents() {

         this.container.addEventListener('pointermove', (e) => {
        if (this.pointerResizeState || this.pointerDragState) return;
        
        const edges = this.detectEdges(e);
        
        if (edges.length > 0) {
            this.container.style.cursor = this.getCursorForEdges(edges);
            console.log('[Chat] 🎯 Edges detectados:', edges); // ✅ Debug
        } else {
            this.container.style.cursor = '';
        }
    });
        this.container.addEventListener('pointerdown', (e) => this.handlePointerDown(e));
        
        document.addEventListener('pointermove', (e) => this.handlePointerMove(e));
        document.addEventListener('pointerup', (e) => this.handlePointerUp(e));
        document.addEventListener('pointercancel', (e) => this.handlePointerCancel(e));
        document.addEventListener('lostpointercapture', (e) => this.handleLostPointerCapture(e));
        
        console.log('[Chat] ✅ Pointer listeners registrados');
    }

    /**
     * Handle pointerdown - Decide drag vs resize
     */
    handlePointerDown(e) {
    console.log('[Chat] 🖱️ PointerDown:', {
        target: e.target.className,
        clientX: e.clientX,
        clientY: e.clientY
    });
    
    // Ignorar cliques em elementos interativos
    if (e.target.closest('.ambience-toggle-btn') || 
        e.target.closest('.ambience-message-input') ||
        e.target.closest('.ambience-input-btn') ||
        e.target.closest('.ambience-message-actions') ||
        e.target.closest('.ambience-action-menu-btn-inline')) {
        return;
    }
    
    // ✅ CRÍTICO: VERIFICAR EDGES PRIMEIRO (prioridade máxima)
    const edges = this.detectEdges(e);
    console.log('[Chat] 🔍 Edges detectados:', edges);
    
    // ✅ Se detectou edges, SEMPRE fazer resize (não verificar header)
    if (edges.length > 0) {
        console.log('[Chat] 🔲 Iniciando RESIZE');
        this.startPointerResize(e, edges);
        return; // ✅ SAIR AQUI, não verificar header
    }
    
    // ✅ Só fazer drag se NÃO detectou edges E está no header
    const isHeader = e.target.closest('.ambience-chat-top');
    if (isHeader) {
        console.log('[Chat] 🖐️ Iniciando DRAG');
        this.startPointerDrag(e);
    }
}

    /**
     * Detect which edges are within EDGE_SIZE pixels
     */
   detectEdges(e) {
    const rect = this.container.getBoundingClientRect();
    const x = e.clientX;
    const y = e.clientY;
    
    const edges = [];
    
    // ✅ Detecção INTERNA (handles estão dentro agora)
    const edgeThreshold = 12; // Mesma largura dos handles
    
    // Dentro do container
    if (x >= rect.left && x <= rect.left + edgeThreshold) {
        edges.push('w');
    }
    if (x >= rect.right - edgeThreshold && x <= rect.right) {
        edges.push('e');
    }
    if (y >= rect.top && y <= rect.top + edgeThreshold) {
        edges.push('n');
    }
    if (y >= rect.bottom - edgeThreshold && y <= rect.bottom) {
        edges.push('s');
    }
    
    console.log('[Chat] 🎯 Detecção:', {
        x: x - rect.left,
        y: y - rect.top,
        edges: edges,
        rect: { left: rect.left, top: rect.top, right: rect.right, bottom: rect.bottom }
    });
    
    return edges;
}

    /**
     * Start resize interaction
     */
    startPointerResize(e, edges) {
        e.preventDefault();
        
        console.log('[Chat] 🔲 Resize iniciado:', edges);
        
        const rect = this.container.getBoundingClientRect();
        
        this.pointerResizeState = {
            pointerId: e.pointerId,
            edges: edges,
            startX: e.clientX,
            startY: e.clientY,
            startWidth: rect.width,
            startHeight: rect.height,
            startLeft: rect.left,
            startTop: rect.top
        };
        
        this.container.setPointerCapture(e.pointerId);
        
        this.container.classList.add('is-resizing');
        document.body.style.userSelect = 'none';
        document.body.style.cursor = this.getCursorForEdges(edges);
    }

    /**
     * Start drag interaction
     */
    startPointerDrag(e) {
        e.preventDefault();
        
        console.log('[Chat] 🖐️ Drag iniciado');
        
        const rect = this.container.getBoundingClientRect();
        
        this.pointerDragState = {
            pointerId: e.pointerId,
            startX: e.clientX,
            startY: e.clientY,
            startLeft: rect.left,
            startTop: rect.top
        };
        
        this.container.setPointerCapture(e.pointerId);
        
        this.container.classList.add('is-dragging');
        this.container.style.transition = 'none';
        document.body.style.userSelect = 'none';
        document.body.style.cursor = 'grabbing';
    }

    /**
     * Handle pointermove - Apply drag or resize
     */
    handlePointerMove(e) {
        if (this.pointerResizeState && e.pointerId === this.pointerResizeState.pointerId) {
            this.applyResize(e);
        } else if (this.pointerDragState && e.pointerId === this.pointerDragState.pointerId) {
            this.applyDrag(e);
        }
    }

    /**
     * Apply resize in requestAnimationFrame
     */
    applyResize(e) {
        if (this.raf) return;
        
        this.raf = requestAnimationFrame(() => {
            this.raf = null;
            
            if (!this.pointerResizeState) return;
            
            const state = this.pointerResizeState;
            const deltaX = e.clientX - state.startX;
            const deltaY = e.clientY - state.startY;
            
            let newWidth = state.startWidth;
            let newHeight = state.startHeight;
            let newLeft = state.startLeft;
            let newTop = state.startTop;
            
            if (state.edges.includes('e')) {
                newWidth = state.startWidth + deltaX;
            }
            if (state.edges.includes('w')) {
                newWidth = state.startWidth - deltaX;
                newLeft = state.startLeft + deltaX;
            }
            if (state.edges.includes('s')) {
                newHeight = state.startHeight + deltaY;
            }
            if (state.edges.includes('n')) {
                newHeight = state.startHeight - deltaY;
                newTop = state.startTop + deltaY;
            }
            
            const maxWidth = window.innerWidth - 40;
            const maxHeight = window.innerHeight - 40;
            
            newWidth = Math.max(this.MIN_WIDTH, Math.min(newWidth, maxWidth));
            newHeight = Math.max(this.MIN_HEIGHT, Math.min(newHeight, maxHeight));
            
            if (state.edges.includes('w') && newWidth === this.MIN_WIDTH) {
                newLeft = state.startLeft + state.startWidth - this.MIN_WIDTH;
            }
            if (state.edges.includes('n') && newHeight === this.MIN_HEIGHT) {
                newTop = state.startTop + state.startHeight - this.MIN_HEIGHT;
            }
            
            newLeft = Math.max(0, Math.min(newLeft, window.innerWidth - newWidth));
            newTop = Math.max(0, Math.min(newTop, window.innerHeight - newHeight));
            
            this.container.style.width = `${newWidth}px`;
            this.container.style.height = `${newHeight}px`;
            this.container.style.left = `${newLeft}px`;
            this.container.style.top = `${newTop}px`;
            this.container.style.right = 'auto';
            this.container.style.bottom = 'auto';
            
            this.updateDimensionsBadge(newWidth, newHeight);
        });
    }

    /**
     * Apply drag using transform (commit on pointerup)
     */
    applyDrag(e) {
        if (this.raf) return;
        
        this.raf = requestAnimationFrame(() => {
            this.raf = null;
            
            if (!this.pointerDragState) return;
            
            const state = this.pointerDragState;
            const deltaX = e.clientX - state.startX;
            const deltaY = e.clientY - state.startY;
            
            this.container.style.transform = `translate(${deltaX}px, ${deltaY}px)`;
        });
    }

    /**
     * Handle pointerup - Finalize and release
     */
    handlePointerUp(e) {
        if (this.pointerResizeState && e.pointerId === this.pointerResizeState.pointerId) {
            this.finishResize(e);
        } else if (this.pointerDragState && e.pointerId === this.pointerDragState.pointerId) {
            this.finishDrag(e);
        }
    }

    /**
     * Handle pointercancel - Same as pointerup
     */
    handlePointerCancel(e) {
        console.warn('[Chat] ⚠️ pointercancel detectado');
        this.handlePointerUp(e);
    }

    /**
     * Handle lostpointercapture
     */
    handleLostPointerCapture(e) {
        console.warn('[Chat] ⚠️ lostpointercapture detectado');
        
        if (this.pointerResizeState && e.pointerId === this.pointerResizeState.pointerId) {
            this.finishResize(e);
        } else if (this.pointerDragState && e.pointerId === this.pointerDragState.pointerId) {
            this.finishDrag(e);
        }
    }

    /**
     * Finish resize
     */
    finishResize(e) {
        console.log('[Chat] ✅ Resize finalizado');
        
        if (this.container.hasPointerCapture(e.pointerId)) {
            this.container.releasePointerCapture(e.pointerId);
        }
        
        this.container.classList.remove('is-resizing');
        document.body.style.userSelect = '';
        document.body.style.cursor = '';
        
        this.saveChatState();
        
        this.pointerResizeState = null;
        
        if (this.raf) {
            cancelAnimationFrame(this.raf);
            this.raf = null;
        }
    }

    /**
     * Finish drag - Commit transform to position
     */
    finishDrag(e) {
        console.log('[Chat] ✅ Drag finalizado');
        
        if (!this.pointerDragState) return;
        
        const state = this.pointerDragState;
        const deltaX = e.clientX - state.startX;
        const deltaY = e.clientY - state.startY;
        
        let newLeft = state.startLeft + deltaX;
        let newTop = state.startTop + deltaY;
        
        const maxX = window.innerWidth - this.container.offsetWidth;
        const maxY = window.innerHeight - this.container.offsetHeight;
        newLeft = Math.max(0, Math.min(newLeft, maxX));
        newTop = Math.max(0, Math.min(newTop, maxY));
        
        this.container.style.left = `${newLeft}px`;
        this.container.style.top = `${newTop}px`;
        this.container.style.transform = '';
        
        if (this.container.hasPointerCapture(e.pointerId)) {
            this.container.releasePointerCapture(e.pointerId);
        }
        
        this.container.classList.remove('is-dragging');
        this.container.style.transition = '';
        document.body.style.userSelect = '';
        document.body.style.cursor = '';
        
        this.saveChatState();
        
        this.pointerDragState = null;
        
        if (this.raf) {
            cancelAnimationFrame(this.raf);
            this.raf = null;
        }
    }

    /**
     * Get cursor for edges
     */
    getCursorForEdges(edges) {
        const key = edges.sort().join('');
        const cursors = {
            'n': 'ns-resize',
            's': 'ns-resize',
            'e': 'ew-resize',
            'w': 'ew-resize',
            'ne': 'nesw-resize',
            'nw': 'nwse-resize',
            'se': 'nwse-resize',
            'sw': 'nesw-resize',
            'en': 'nesw-resize',
            'es': 'nwse-resize'
        };
        return cursors[key] || 'default';
    }

    /**
     * Add resize handles (visual only)
     */
    addResizeHandles() {
        this.container.querySelectorAll('.chat-resize-handle').forEach(h => h.remove());
        
        const handles = `
            <div class="chat-resize-handle chat-resize-handle-top"></div>
            <div class="chat-resize-handle chat-resize-handle-bottom"></div>
            <div class="chat-resize-handle chat-resize-handle-left"></div>
            <div class="chat-resize-handle chat-resize-handle-right"></div>
            <div class="chat-resize-handle chat-resize-handle-corner chat-resize-handle-nw"></div>
            <div class="chat-resize-handle chat-resize-handle-corner chat-resize-handle-ne"></div>
            <div class="chat-resize-handle chat-resize-handle-corner chat-resize-handle-sw"></div>
            <div class="chat-resize-handle chat-resize-handle-corner chat-resize-handle-se"></div>
        `;
        
        this.container.insertAdjacentHTML('beforeend', handles);
        
        console.log('[Chat] ✅ Resize handles adicionados');
    }

    /**
     * Dimensions badge
     */
    addDimensionsBadge() {
        const badge = document.createElement('div');
        badge.className = 'chat-dimensions-badge';
        badge.textContent = '0 × 0';
        this.container.appendChild(badge);
        this.dimensionsBadge = badge;
    }

    updateDimensionsBadge(width, height) {
        if (this.dimensionsBadge) {
            this.dimensionsBadge.textContent = `${Math.round(width)} × ${Math.round(height)}`;
        }
    }

    /**
     * Save and restore state
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

    restoreChatState() {
        try {
            const savedState = localStorage.getItem('ambience_chat_state');
            if (!savedState) return;
            
            const state = JSON.parse(savedState);
            
            const width = Math.max(this.MIN_WIDTH, Math.min(state.width, window.innerWidth - 40));
            const height = Math.max(this.MIN_HEIGHT, Math.min(state.height, window.innerHeight - 40));
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
     * Cleanup all pointer listeners
     */
    cleanup() {
        console.log('[Chat] 🧹 Limpando listeners...');
        
        if (this.pointerResizeState) {
            if (this.container.hasPointerCapture(this.pointerResizeState.pointerId)) {
                this.container.releasePointerCapture(this.pointerResizeState.pointerId);
            }
            this.pointerResizeState = null;
        }
        
        if (this.pointerDragState) {
            if (this.container.hasPointerCapture(this.pointerDragState.pointerId)) {
                this.container.releasePointerCapture(this.pointerDragState.pointerId);
            }
            this.pointerDragState = null;
        }
        
        if (this.raf) {
            cancelAnimationFrame(this.raf);
            this.raf = null;
        }
        
        this.clearTypingState();
        
        console.log('[Chat] ✅ Cleanup completo');
    }

>>>>>>> 7dbc97f (Chat fix)
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
<<<<<<< HEAD
    if (chatSystem) {
        chatSystem.clearTypingState();
=======
    if (chatSystem && typeof chatSystem.cleanup === 'function') {
        chatSystem.cleanup();
    }
});

document.addEventListener('visibilitychange', () => {
    if (document.hidden && chatSystem) {
        if (chatSystem.pointerResizeState) {
            chatSystem.finishResize({ pointerId: chatSystem.pointerResizeState.pointerId });
        }
        if (chatSystem.pointerDragState) {
            chatSystem.finishDrag({ pointerId: chatSystem.pointerDragState.pointerId });
        }
>>>>>>> 7dbc97f (Chat fix)
    }
});