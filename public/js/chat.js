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

        // Elementos DOM
        this.messagesContainer = document.getElementById('chatMessages');
        this.messageInput = document.getElementById('chatMessageInput');
        this.fileInput = document.getElementById('chatFileInput');
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
        this.fileInput.addEventListener('change', async (e) => await this.handleFileSelect(e));
        this.messageInput.addEventListener('input', (e) => this.handleInputModeration(e));

        const toggleBtn = document.getElementById('toggleChatBtn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.toggleChat());
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
        
        console.log('[Chat] ✅ WebSocket configurado');
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
            this.scrollToBottom();

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
    let mensagemExibida = msg.mensagem; // Já vem censurada do backend (*****) 
    
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
        
        // ✅ MENOR DE 15: SEMPRE CENSURAR (mostrar ***** com fundo vermelho)
        if (userAge < 15) {
            if (temProfanity || temConteudoSexual) {
                mensagemExibida = msg.mensagem; // Backend já enviou *****
                deveCensurar = true; // ← ATIVAR FUNDO VERMELHO
                console.log('✅ CENSURANDO PARA MENOR DE 15');
            }
        } 
        // ✅ 15-17: Mostra palavrões, censura sexual
        else if (userAge >= 15 && userAge < 18) {
            if (temConteudoSexual) {
                deveCensurar = true;
                mensagemExibida = '[Mensagem oculta – violou as regras de conteúdo]';
            } else if (temProfanity) {
                // Mostra original (sem ***)
                mensagemExibida = msg.mensagem_original || msg.mensagem;
                deveCensurar = false; // SEM fundo vermelho
            }
        } 
        // ✅ 18+: Mostra palavrões, censura sexual
        else if (userAge >= 18) {
            if (temConteudoSexual) {
                deveCensurar = true;
                podeVerCensurado = true;
                mensagemExibida = '[Mensagem oculta – violou as regras de conteúdo]';
            } else if (temProfanity) {
                // Mostra original (sem ***)
                mensagemExibida = msg.mensagem_original || msg.mensagem;
                deveCensurar = false; // SEM fundo vermelho
            }
        }
        
        console.log('[Chat] 📤 RESULTADO FINAL', {
            deveCensurar,
            mensagemExibida,
            vai_ter_fundo_vermelho: deveCensurar
        });
    }

    return `
        <div class="chat-message ${deveCensurar ? 'censored' : ''} ${msg.editada ? 'edited' : ''}" 
             data-message-id="${msg.id}">
            <div class="chat-message-header">
                <img src="${msg.usuario.avatar_url || '/images/default-avatar.png'}" 
                     alt="${msg.usuario.username}" 
                     class="chat-message-avatar">
                <span class="chat-message-author">${this.escapeHtml(msg.usuario.username)}</span>
                <span class="chat-message-timestamp">
                    ${msg.timestamp_formatado}
                    ${msg.editada ? '<span class="badge bg-secondary badge-sm ms-1">editada</span>' : ''}
                </span>
                
                ${isOwn ? '<span class="badge bg-primary badge-sm ms-2">Você</span>' : ''}
            </div>

            <div class="chat-message-content ${deveCensurar ? 'chat-message-censored' : ''}">
                <div class="chat-message-text" data-message-text="${msg.id}">
                    ${this.escapeHtml(mensagemExibida)}
                </div>
                
                ${deveCensurar && podeVerCensurado ? `
                    <div class="mt-2">
                        <button class="btn btn-sm btn-link text-warning p-0 view-censored-btn" 
                                data-message-id="${msg.id}">
                            <i class="fas fa-eye"></i> Ver conteúdo original
                        </button>
                    </div>
                ` : ''}

                ${msg.anexos && msg.anexos.length > 0 ? `
                    <div class="chat-message-attachments mt-2">
                        ${msg.anexos.map(a => this.renderAttachment(a)).join('')}
                    </div>
                ` : ''}

                <div class="chat-message-actions">
                    <button class="chat-message-menu" data-message-id="${msg.id}" data-user-id="${msg.usuario.id}">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

    renderAttachment(attachment) {
        if (attachment.eh_imagem) {
            return `
                <div class="chat-message-attachment-wrapper">
                    <img src="${attachment.url}" 
                         alt="${attachment.nome}" 
                         class="chat-message-attachment"
                         loading="lazy"
                         onclick="window.open('${attachment.url}', '_blank')">
                    ${attachment.nsfw_detectado ? '<span class="badge bg-danger nsfw-badge">NSFW</span>' : ''}
                </div>
            `;
        }

        return `
            <a href="${attachment.url}" target="_blank" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-file"></i> ${attachment.nome} (${attachment.tamanho})
            </a>
        `;
    }

    attachMessageMenuListeners() {
        document.querySelectorAll('.chat-message-menu').forEach(btn => {
            btn.addEventListener('click', (e) => this.showMessageMenu(e));
        });

        document.querySelectorAll('.view-censored-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.viewCensoredContent(e));
        });
    }

    showMessageMenu(e) {
        e.stopPropagation();
        const btn = e.currentTarget;
        const messageId = btn.dataset.messageId;
        const userId = btn.dataset.userId;
        const isOwn = parseInt(userId) === parseInt(this.userId);

        document.querySelectorAll('.chat-message-dropdown').forEach(m => m.remove());

        const menu = document.createElement('div');
        menu.className = 'chat-message-dropdown';
        menu.innerHTML = `
            <button class="dropdown-item" data-action="profile" data-user-id="${userId}">
                <i class="fas fa-user me-2"></i> Ver perfil
            </button>
            ${isOwn ? `
                <button class="dropdown-item" data-action="edit" data-message-id="${messageId}">
                    <i class="fas fa-edit me-2"></i> Editar
                </button>
            ` : ''}
            ${!isOwn ? `
                <button class="dropdown-item text-danger" data-action="report" data-message-id="${messageId}" data-user-id="${userId}">
                    <i class="fas fa-flag me-2"></i> Denunciar
                </button>
            ` : ''}
            ${isOwn ? `
                <button class="dropdown-item text-danger" data-action="delete" data-message-id="${messageId}">
                    <i class="fas fa-trash me-2"></i> Deletar
                </button>
            ` : ''}
            <button class="dropdown-item" data-action="select" data-message-id="${messageId}">
                <i class="fas fa-check-square me-2"></i> Selecionar para denúncia
            </button>
        `;

        const rect = btn.getBoundingClientRect();
        menu.style.position = 'fixed';
        menu.style.top = `${rect.bottom + 5}px`;
        menu.style.right = `${window.innerWidth - rect.right}px`;

        document.body.appendChild(menu);

        menu.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', (e) => this.handleMenuAction(e));
        });

        setTimeout(() => {
            document.addEventListener('click', () => menu.remove(), { once: true });
        }, 100);
    }

    async handleMenuAction(e) {
        const action = e.currentTarget.dataset.action;
        const messageId = e.currentTarget.dataset.messageId;
        const userId = e.currentTarget.dataset.userId;

        switch (action) {
            case 'profile':
                window.location.href = `/perfil/${userId}`;
                break;
            
            case 'edit':
                this.startEditMessage(messageId);
                break;
            
            case 'report':
                if (messageId) {
        this.selectedMessages.add(parseInt(messageId));
        const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
        if (messageEl) {
            messageEl.classList.add('selected');
        }
    }
    this.openReportModal(userId, messageId);
    break;
            
            case 'delete':
                await this.deleteMessage(messageId);
                break;
            
            case 'select':
                this.toggleMessageSelection(messageId);
                break;
        }

        document.querySelectorAll('.chat-message-dropdown').forEach(m => m.remove());
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
        if (!input) return;

        const newText = input.value.trim();
        if (!newText) {
            alert('A mensagem não pode estar vazia');
            return;
        }

        const msg = this.messages.find(m => m.id === parseInt(messageId));
        const originalText = msg.mensagem_original || msg.mensagem;

        if (newText === originalText) {
            this.cancelEditMessage(messageId);
            return;
        }

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

            if (!response.ok) throw new Error('Erro ao editar');

            const data = await response.json();
            
            // Atualizar localmente
            this.updateMessageInArray(messageId, data.mensagem);
            this.renderMessages();
            
            this.showSuccess('Mensagem editada com sucesso!');

        } catch (error) {
            console.error('[Chat] Erro ao editar mensagem:', error);
            this.showError('Erro ao editar mensagem');
            this.cancelEditMessage(messageId);
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
            this.messageInput.value = '';
            this.attachments = [];
            this.attachmentsPreview.innerHTML = '';
            this.attachmentsPreview.classList.add('d-none');
            this.hideModerationAlert();

            console.log('[Chat] ✅ Mensagem enviada com sucesso');

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
    
    for (const file of files) {
        if (this.attachments.length >= 5) {
            alert('Máximo de 5 anexos por mensagem');
            break;
        }

        if (file.size > 10 * 1024 * 1024) {
            alert(`Arquivo ${file.name} muito grande (máx 10MB)`);
            continue;
        }

        // Verificar NSFW para imagens
        if (file.type.startsWith('image/')) {
            try {
                if (window.NSFWDetector) {
                    this.showModerationAlert(['Analisando imagem...']);
                    
                    const result = await window.NSFWDetector.analyze(file);
                    
                    if (result && result.isBlocked) {
                        this.hideModerationAlert();
                        alert(`A imagem "${file.name}" foi identificada como inapropriada e não pode ser enviada.`);
                        console.warn('[Chat] Imagem bloqueada por NSFW:', file.name, result);
                        continue;
                    }
                    
                    this.hideModerationAlert();
                }
            } catch (error) {
                console.error('[Chat] Erro na análise NSFW:', error);
                this.hideModerationAlert();
            }
        }

        this.attachments.push(file);
    }

    this.renderAttachmentsPreview();
    e.target.value = '';
}

    renderAttachmentsPreview() {
        if (this.attachments.length === 0) {
            this.attachmentsPreview.classList.add('d-none');
            return;
        }

        this.attachmentsPreview.classList.remove('d-none');
        this.attachmentsPreview.innerHTML = this.attachments.map((file, index) => {
            const url = URL.createObjectURL(file);
            return `
                <div class="attachment-preview-item">
                    <img src="${url}" alt="${file.name}">
                    <button type="button" class="attachment-preview-remove" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        }).join('');

        this.attachmentsPreview.querySelectorAll('.attachment-preview-remove').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const index = parseInt(e.currentTarget.dataset.index);
                this.attachments.splice(index, 1);
                this.renderAttachmentsPreview();
            });
        });
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
        if (this.selectedMessages.has(messageId)) {
            this.selectedMessages.delete(messageId);
        } else {
            this.selectedMessages.add(messageId);
        }

        const messageEl = document.querySelector(`[data-message-id="${messageId}"]`);
        if (messageEl) {
            messageEl.classList.toggle('selected', this.selectedMessages.has(messageId));
        }

        console.log('[Chat] Mensagens selecionadas:', Array.from(this.selectedMessages));
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