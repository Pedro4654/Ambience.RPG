{{-- resources/views/components/chat-container.blade.php --}}
<div id="chatContainer" 
     class="ambience-chat-wrapper" 
     data-sala-id="{{ $sala->id }}" 
     data-user-id="{{ auth()->id() }}" 
     data-user-age="{{ auth()->user()->data_de_nascimento ? \Carbon\Carbon::parse(auth()->user()->data_de_nascimento)->age : 18 }}"
     data-pode-moderar-chat="{{ $minhas_permissoes && $minhas_permissoes->pode_moderar_chat ? 'true' : 'false' }}"
     data-eh-criador-sala="{{ $sala->criador_id === auth()->id() ? 'true' : 'false' }}">

    <div class="ambience-chat-card">
        <div class="ambience-chat-mockup">
            
            {{-- Header com Window Dots --}}
            <div class="ambience-chat-top">
                <div class="ambience-window-dots">
                    <div class="ambience-dot ambience-dot-red"></div>
                    <div class="ambience-dot ambience-dot-yellow"></div>
                    <div class="ambience-dot ambience-dot-green"></div>
                </div>
                <div class="ambience-chat-title">Chat da sala: {{ $sala->nome }}</div>
                <button id="toggleChatBtn" class="ambience-toggle-btn" title="Minimizar/Maximizar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="18 15 12 9 6 15"/>
                    </svg>
                </button>
            </div>

            {{-- Área de Mensagens --}}
            <div id="chatMessages" class="ambience-chat-messages">
                <div class="ambience-chat-loading">
                    <div class="ambience-spinner"></div>
                    <p class="ambience-loading-text">Carregando mensagens...</p>
                </div>
            </div>

            {{-- Indicador de Digitação --}}
            <div id="chatTypingIndicator" class="ambience-typing-indicator" style="display: none;">
    <div class="ambience-typing-content">
        <div class="ambience-typing-dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <span id="chatTypingText" class="ambience-typing-text"></span>
    </div>
</div>

            {{-- Área de Input --}}
            <div class="ambience-chat-input-area">
                <form id="chatForm" enctype="multipart/form-data" class="ambience-chat-form">
                    @csrf
                    
                    {{-- Preview de Anexos --}}
                    <div id="chatAttachmentsPreview" class="ambience-attachments-preview" style="display: none;">
                        {{-- Previews inseridos via JS --}}
                    </div>

                    {{-- Input Row --}}
                    <div class="ambience-input-row">
                        <button type="button" 
                                class="ambience-input-btn ambience-attach-btn" 
                                id="chatAttachBtn"
                                title="Anexar imagem">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                            </svg>
                        </button>
                        
                        <input type="file" 
       id="chatFileInput" 
       name="anexos[]" 
       class="ambience-file-input" 
       accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" 
       multiple
       style="display: none;">
                        
                        <input type="text" 
                               id="chatMessageInput" 
                               name="mensagem" 
                               class="ambience-message-input" 
                               placeholder="Digite sua mensagem..." 
                               maxlength="2000"
                               autocomplete="off"
                               required>
                        
                        <button type="submit" 
                                class="ambience-input-btn ambience-send-btn" 
                                id="chatSendBtn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13"/>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Alerta de Moderação --}}
                    <div id="chatModerationAlert" class="ambience-moderation-alert" style="display: none;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        <span id="chatModerationMessage"></span>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
/* ========================================
   ESTILOS ENCAPSULADOS DO CHAT MODERNIZADO
   ======================================== */

/* Container Principal */
.ambience-chat-wrapper {
    width: 100%;
    height: 100%;
    max-height: 100%; /* ✅ Adicionar */
    display: flex;
    flex-direction: column;
    overflow: hidden; /* ✅ Adicionar */
}

.ambience-chat-card {
    background: linear-gradient(145deg, rgba(31, 42, 51, 0.6), rgba(20, 28, 35, 0.4));
    padding: 24px;
    border-radius: 20px;
    border: 1px solid rgba(34, 197, 94, 0.1);
    display: flex;
    flex-direction: column;
    height: 100%;
    max-height: 100%; /* ✅ Adicionar */
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden; /* ✅ Adicionar */
}

.ambience-chat-card:hover {
    border-color: rgba(34, 197, 94, 0.2);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

.ambience-chat-mockup {
     background: linear-gradient(180deg, #1f2937, #111827);
     border-radius: 18px;
     padding: 20px;
     box-shadow: 0 16px 48px rgba(0, 0, 0, 0.5);
     display: flex;
     flex-direction: column;
    height: 600px; /* ✅ Altura fixa */
    max-height: calc(100vh - 200px) !important;
    min-height: 400px !important;
     overflow: hidden;
    contain: layout; /* ✅ CRÍTICO: Contenção CSS */
}
/* Header com Window Dots */
.ambience-chat-top {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(34, 197, 94, 0.15);
    flex-shrink: 0;
}

.ambience-window-dots {
    display: flex;
    gap: 7px;
}

.ambience-dot {
    width: 11px;
    height: 11px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.ambience-dot-red {
    background: #ef4444;
}

.ambience-dot-red:hover {
    background: #dc2626;
    box-shadow: 0 0 8px rgba(239, 68, 68, 0.6);
}

.ambience-dot-yellow {
    background: #eab308;
}

.ambience-dot-yellow:hover {
    background: #ca8a04;
    box-shadow: 0 0 8px rgba(234, 179, 8, 0.6);
}

.ambience-dot-green {
    background: #22c55e;
}

.ambience-dot-green:hover {
    background: #16a34a;
    box-shadow: 0 0 8px rgba(34, 197, 94, 0.6);
}

.ambience-chat-title {
    flex: 1;
    text-align: center;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.ambience-toggle-btn {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: rgba(55, 65, 81, 0.5);
    border: 1px solid rgba(55, 65, 81, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    padding: 0;
}

.ambience-toggle-btn:hover {
    background: rgba(34, 197, 94, 0.1);
    border-color: rgba(34, 197, 94, 0.5);
}

.ambience-toggle-btn svg {
    width: 16px;
    height: 16px;
    stroke: #9ca3af;
    transition: all 0.2s;
}

.ambience-toggle-btn:hover svg {
    stroke: #22c55e;
}

.ambience-toggle-btn.collapsed svg {
    transform: rotate(180deg);
}

/* Área de Mensagens */
.ambience-chat-messages {
    flex: 1 1 0; /* ✅ CRÍTICO: Força o flex a respeitar constraints */
    overflow-y: auto !important;
    overflow-x: hidden !important;
     display: flex;
     flex-direction: column;
     gap: 16px;
     padding: 4px 8px;
     margin: 0 -8px;
    min-height: 0; /* ✅ CRÍTICO: Permite encolher */
    max-height: none; /* ✅ Remove limite artificial */
    height: 100%; /* ✅ Força ocupar espaço disponível */
   contain: layout style paint; /* ✅ Performance e contenção */
}

.ambience-chat-messages::-webkit-scrollbar {
    width: 6px;
}

.ambience-chat-messages::-webkit-scrollbar-track {
    background: rgba(17, 24, 39, 0.5);
    border-radius: 3px;
}

.ambience-chat-messages::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.3);
    border-radius: 3px;
    transition: background 0.3s;
}

.ambience-chat-messages::-webkit-scrollbar-thumb:hover {
    background: rgba(34, 197, 94, 0.5);
}

/* Loading State */
.ambience-chat-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 16px;
    padding: 60px 20px;
}

.ambience-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid rgba(34, 197, 94, 0.2);
    border-top-color: #22c55e;
    border-radius: 50%;
    animation: ambience-spin 0.8s linear infinite;
}

@keyframes ambience-spin {
    to { transform: rotate(360deg); }
}

.ambience-loading-text {
    color: #9ca3af;
    font-size: 14px;
    font-weight: 500;
    margin: 0;
}

/* Mensagem Individual */
.ambience-message {
     display: flex;
     gap: 12px;
     align-items: flex-start;
     animation: ambience-messageSlideIn 0.3s ease-out;
     position: relative;
    width: 100% !important;
    max-width: 100% !important;
   box-sizing: border-box !important;
    padding: 0 !important;
    margin: 0 !important;
 }

@keyframes ambience-messageSlideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.ambience-avatar,
.ambience-avatar-fallback {
    width: 38px !important;
    height: 38px !important;
    min-width: 38px !important;
    min-height: 38px !important;
    max-width: 38px !important;
    max-height: 38px !important;
    border-radius: 50%;
    flex-shrink: 0;
    display: block;
    object-fit: cover !important;
    border: 2px solid rgba(34, 197, 94, 0.3);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
}

.ambience-message:hover .ambience-avatar {
    border-color: rgba(34, 197, 94, 0.6);
    transform: scale(1.08);
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
}

.ambience-avatar-fallback {
    background: linear-gradient(135deg, #064e3b, #052e16) !important;
     border: 2px solid rgba(34, 197, 94, 0.2);
    display: flex !important;
     align-items: center;
     justify-content: center;
     font-size: 16px;
     font-weight: 700;
     color: #22c55e;
    line-height: 1; /* ✅ Remove espaço extra */
 }

.ambience-message-content {
     display: flex;
     flex-direction: column;
    gap: 6px !important;
    max-width: 75% !important;
     flex: 1;
    min-width: 0; /* ✅ Permite encolher */
    overflow: hidden; /* ✅ Contenção */
 }

.ambience-username {
     color: #22c55e;
    font-size: 13px !important;
     font-weight: 700;
     letter-spacing: 0.3px;
    line-height: 1.2 !important;
     display: inline-block;
     margin-bottom: 2px;
     transition: color 0.2s;
    vertical-align: middle !important;
 }

.ambience-message:hover .ambience-username {
    color: #16a34a;
}

.ambience-user-badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 8px !important;
    margin-left: 6px !important;
    background: rgba(34, 197, 94, 0.12) !important;
    border: 1px solid rgba(34, 197, 94, 0.3) !important;
    border-radius: 8px !important;
    color: #16a34a !important;
    font-size: 10px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    vertical-align: middle !important;
    line-height: 1 !important;
    box-shadow: 0 2px 4px rgba(34, 197, 94, 0.15) !important;
}

.ambience-bubble {
    padding: 10px 14px !important;
    border-radius: 12px !important;
     font-size: 14px;
     line-height: 1.5;
    background: #374151 !important;
    color: #e5eef1 !important;
     word-wrap: break-word;
    word-break: break-word;
     transition: all 0.2s;
     position: relative;
    display: block !important;
    width: fit-content !important;
    max-width: 100% !important;
 }

.ambience-message:hover .ambience-bubble {
    background: #404a5a !important;
     box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
 }

.ambience-bubble-censored {
    background: #7f1d1d;
    border-left: 3px solid #ef4444;
    font-style: italic;
    color: #fca5a5;
}

.ambience-timestamp {
    color: #6b7280;
    font-size: 11px;
    margin-top: 2px;
    padding-left: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.ambience-message-footer {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 4px;
}

/* ========== BOTÃO DE REVELAR/OCULTAR ========== */
.ambience-reveal-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    border-radius: 6px;
    color: #22c55e;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
}

.ambience-reveal-btn:hover {
    background: rgba(34, 197, 94, 0.2);
    border-color: #22c55e;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.2);
}

.ambience-reveal-btn svg {
    width: 12px;
    height: 12px;
    stroke: currentColor;
    flex-shrink: 0;
}

.ambience-reveal-btn[data-revealed="true"] {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.ambience-reveal-btn[data-revealed="true"]:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: #ef4444;
}

.ambience-edited-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10px;
    color: #9ca3af;
    background: rgba(156, 163, 175, 0.1);
    padding: 2px 6px;
    border-radius: 4px;
}

/* ========== MENSAGEM SELECIONADA PARA DENÚNCIA ========== */
.ambience-message.ambience-message-selected {
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Borda lateral sutil */
.ambience-message.ambience-message-selected::before {
    content: '';
    position: absolute;
    left: -12px; /* Fora do container da mensagem */
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #22c55e, #16a34a);
    border-radius: 2px;
    box-shadow: 0 0 12px rgba(34, 197, 94, 0.4);
    animation: selectedPulseSubtle 2s ease-in-out infinite;
}

/* Animação sutil da borda */
@keyframes selectedPulseSubtle {
    0%, 100% {
        opacity: 1;
        box-shadow: 0 0 12px rgba(34, 197, 94, 0.4);
    }
    50% {
        opacity: 0.8;
        box-shadow: 0 0 20px rgba(34, 197, 94, 0.6);
    }
}

/* Efeito sutil no bubble */
.ambience-message.ambience-message-selected .ambience-bubble {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.08), rgba(34, 197, 94, 0.04)) !important;
    border: 1px solid rgba(34, 197, 94, 0.25);
    box-shadow: 0 0 0 1px rgba(34, 197, 94, 0.1);
}

/* Avatar com glow verde */
.ambience-message.ambience-message-selected .ambience-avatar,
.ambience-message.ambience-message-selected .ambience-avatar-fallback {
    border-color: #22c55e;
    box-shadow: 
        0 0 0 2px rgba(34, 197, 94, 0.2),
        0 0 16px rgba(34, 197, 94, 0.3);
}

/* Hover state mais sutil */
.ambience-message.ambience-message-selected:hover .ambience-bubble {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.12), rgba(34, 197, 94, 0.06)) !important;
    border-color: rgba(34, 197, 94, 0.35);
}

.ambience-action-item.selected {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
    border-left: 3px solid #22c55e;
    padding-left: 9px; /* Compensar a borda */
}

.ambience-action-item.selected svg {
    fill: rgba(34, 197, 94, 0.2);
}

/* Anexos */
.ambience-message-attachment {
    margin-top: 8px;
    max-width: 180px !important; /* ✅ Reduzido de 300px para 180px */
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.2s;
    border: 2px solid rgba(34, 197, 94, 0.2);
}

.ambience-message-attachment:hover {
    border-color: rgba(34, 197, 94, 0.4);
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
}

.ambience-message-attachment img {
    width: 100%;
    height: auto;
    max-height: 200px; /* ✅ Limitar altura também */
    object-fit: cover; /* ✅ Manter proporções */
    display: block;
}

/* Menu de Ações */
.ambience-message-actions {
    position: absolute;
    top: 8px;
    right: 8px;
    opacity: 0;
    transition: opacity 0.2s;
}

.ambience-message:hover .ambience-message-actions {
    opacity: 1;
}

.ambience-action-menu-btn {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: rgba(17, 24, 39, 0.9);
    border: 1px solid rgba(34, 197, 94, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    padding: 0;
}

.ambience-action-menu-btn:hover {
    background: rgba(34, 197, 94, 0.2);
    border-color: #22c55e;
}

.ambience-action-menu-btn svg {
    width: 16px;
    height: 16px;
    stroke: #9ca3af;
}

.ambience-action-menu-btn:hover svg {
    stroke: #22c55e;
}

/* Dropdown do Menu */
.ambience-action-dropdown {
    position: fixed !important; /* ✅ Mudou de absolute para fixed */
    /* ✅ Removido top, right - serão definidos via JS */
    margin: 0; /* ✅ Removido margin-top */
    background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.95));
    border: 1px solid rgba(34, 197, 94, 0.2);
    border-radius: 12px;
    padding: 6px;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(12px);
    z-index: 9999 !important; /* ✅ Aumentado de 100 para 9999 */
    min-width: 180px; /* ✅ Aumentado de 160px para 180px */
    display: none;
    animation: ambience-dropdownSlideIn 0.2s ease;
    transform-origin: top center; /* ✅ Adicionado */
}

.ambience-action-dropdown.active {
    display: block;
}

@keyframes ambience-dropdownSlideIn {
    from {
        opacity: 0;
        transform: translateY(-8px) scale(0.95); /* ✅ Adicionado scale */
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1); /* ✅ Adicionado scale */
    }
}

/* ✅ ADICIONAR NOVA ANIMAÇÃO */
@keyframes ambience-dropdownSlideOut {
    from {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    to {
        opacity: 0;
        transform: translateY(-8px) scale(0.95);
    }
}

.ambience-action-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 8px;
    color: #d1d5db;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
}

.ambience-action-item:hover {
    background: rgba(34, 197, 94, 0.1);
    color: #22c55e;
}

.ambience-action-item.danger {
    color: #ef4444;
}

.ambience-action-item.danger:hover {
    background: rgba(239, 68, 68, 0.1);
}

.ambience-action-item svg {
    width: 16px;
    height: 16px;
    stroke: currentColor;
}

/* Área de Input */
.ambience-chat-input-area {
    flex-shrink: 0;
    padding-top: 16px;
    border-top: 1px solid rgba(34, 197, 94, 0.15);
    margin-top: 16px;
}

.ambience-chat-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.ambience-input-row {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(17, 24, 39, 0.8);
    border: 2px solid rgba(55, 65, 81, 0.8);
    border-radius: 14px;
    padding: 8px;
    transition: all 0.3s;
}

.ambience-input-row:focus-within {
    border-color: rgba(34, 197, 94, 0.5);
    background: rgba(17, 24, 39, 0.95);
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

.ambience-input-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: none;
    background: rgba(55, 65, 81, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
    padding: 0;
}

.ambience-input-btn:hover {
    background: rgba(34, 197, 94, 0.2);
    transform: translateY(-2px);
}

.ambience-input-btn svg {
    width: 20px;
    height: 20px;
    stroke: #9ca3af;
    transition: stroke 0.2s;
}

.ambience-input-btn:hover svg {
    stroke: #22c55e;
}

.ambience-send-btn {
    background: linear-gradient(135deg, #22c55e, #16a34a);
}

.ambience-send-btn:hover {
    background: linear-gradient(135deg, #16a34a, #15803d);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
}

.ambience-send-btn svg {
    stroke: #052e16;
}

.ambience-send-btn:disabled {
    background: rgba(55, 65, 81, 0.5);
    cursor: not-allowed;
    transform: none;
}

.ambience-send-btn:disabled svg {
    stroke: #6b7280;
}

.ambience-message-input {
    flex: 1;
    background: transparent;
    border: none;
    outline: none;
    color: #f9fafb;
    font-size: 14px;
    font-weight: 500;
    padding: 8px 12px;
    min-width: 0;
}

.ambience-message-input::placeholder {
    color: #6b7280;
}

/* Preview de Anexos */
.ambience-attachments-preview {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    padding: 12px;
    background: rgba(17, 24, 39, 0.5);
    border-radius: 12px;
    border: 1px solid rgba(55, 65, 81, 0.5);
}

.ambience-attachment-item {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid rgba(34, 197, 94, 0.3);
    transition: all 0.2s;
}

.ambience-attachment-item:hover {
    border-color: #22c55e;
    transform: scale(1.05);
}

.ambience-attachment-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.ambience-attachment-remove {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: rgba(239, 68, 68, 0.9);
    border: 2px solid #111827;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.ambience-attachment-remove:hover {
    background: #ef4444;
    transform: scale(1.1);
}

.ambience-attachment-remove svg {
    width: 12px;
    height: 12px;
    stroke: white;
    stroke-width: 3;
}

/* Alerta de Moderação */
.ambience-moderation-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    background: rgba(251, 191, 36, 0.15);
    border: 1px solid rgba(251, 191, 36, 0.3);
    border-radius: 12px;
    color: #fbbf24;
    font-size: 13px;
    font-weight: 500;
    animation: ambience-alertSlideIn 0.3s ease;
}

@keyframes ambience-alertSlideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.ambience-moderation-alert svg {
    width: 18px;
    height: 18px;
    stroke: currentColor;
    flex-shrink: 0;
}

/* Estados Vazios */
.ambience-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    text-align: center;
    color: #6b7280;
}

.ambience-empty-state svg {
    width: 64px;
    height: 64px;
    stroke: #4b5563;
    margin-bottom: 16px;
    opacity: 0.5;
}

.ambience-empty-state h3 {
    font-size: 16px;
    font-weight: 700;
    color: #9ca3af;
    margin: 0 0 8px 0;
}

.ambience-empty-state p {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
    line-height: 1.6;
}

/* Responsivo */
@media (max-width: 768px) {
    .ambience-chat-card {
        padding: 16px;
    }

    .ambience-chat-mockup {
        padding: 16px;
        min-height: 400px;
    }

    .ambience-message-content {
        max-width: 85%;
    }

    .ambience-bubble {
        font-size: 13px;
        padding: 8px 12px;
    }

    .ambience-avatar,
    .ambience-avatar-fallback {
        width: 32px;
        height: 32px;
    }

    .ambience-username {
        font-size: 12px;
    }

    .ambience-timestamp {
        font-size: 10px;
    }

    .ambience-input-btn {
        width: 32px;
        height: 32px;
    }

    .ambience-input-btn svg {
        width: 18px;
        height: 18px;
    }

    .ambience-message-input {
        font-size: 13px;
        padding: 6px 10px;
    }

    .ambience-attachment-item {
        width: 60px;
        height: 60px;
    }

    .ambience-message-attachment {
        max-width: 140px !important;
    }
    
    .ambience-message-attachment img {
        max-height: 160px;
    }
}

/* Estado Colapsado */
/* 1. MINIMIZAR CHAT - Colapsar tudo exceto o header */
.ambience-chat-wrapper.collapsed .ambience-chat-messages,
.ambience-chat-wrapper.collapsed .ambience-chat-input-area {
    display: none !important;
    opacity: 0;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.ambience-chat-wrapper.collapsed .ambience-chat-mockup {
    min-height: 60px !important;
    height: 60px !important;
    max-height: 60px !important;
    padding: 16px 20px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ✅ CARD - Reduzir padding e altura quando colapsado */
.ambience-chat-wrapper.collapsed .ambience-chat-card {
    padding: 12px !important;
    height: 60px !important;
    min-height: 60px !important;
    max-height: 60px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ✅ WRAPPER - Reduzir altura total quando colapsado */
.ambience-chat-wrapper.collapsed {
    height: 60px !important;
    min-height: 60px !important;
    max-height: 60px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ✅ ESCONDER HANDLES DE RESIZE QUANDO COLAPSADO */
.ambience-chat-wrapper.collapsed .chat-resize-handle {
    display: none !important;
    opacity: 0 !important;
    pointer-events: none !important;
}

.ambience-chat-wrapper.collapsed .chat-dimensions-badge {
    display: none !important;
}

/* ✅ REMOVER SOMBRAS/BORDAS EXTRAS QUANDO COLAPSADO */
.ambience-chat-wrapper.collapsed .ambience-chat-card {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
    overflow: hidden !important;
}

/* ✅ GARANTIR QUE APENAS O HEADER FIQUE VISÍVEL */
.ambience-chat-wrapper.collapsed .ambience-chat-mockup {
    overflow: hidden !important;
    display: flex !important;
    flex-direction: column !important;
}

/* ✅ FORÇAR OCULTAÇÃO DE TUDO EXCETO HEADER */
.ambience-chat-wrapper.collapsed .ambience-chat-messages,
.ambience-chat-wrapper.collapsed .ambience-chat-input-area,
.ambience-chat-wrapper.collapsed .ambience-typing-indicator {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    height: 0 !important;
    max-height: 0 !important;
    overflow: hidden !important;
    margin: 0 !important;
    padding: 0 !important;
}

/* ✅ AJUSTAR ESPAÇAMENTO DO HEADER QUANDO COLAPSADO */
.ambience-chat-wrapper.collapsed .ambience-chat-top {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
    border-bottom: none !important;
}

/* ✅ Transições suaves para todos os containers */
.ambience-chat-mockup,
.ambience-chat-card,
.ambience-chat-wrapper {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Animação do ícone do botão de toggle */
.ambience-toggle-btn.collapsed svg {
    transform: rotate(180deg);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.ambience-toggle-btn svg {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Animações de Loading */
@keyframes ambience-shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

.ambience-skeleton {
    background: linear-gradient(90deg, rgba(55, 65, 81, 0.3) 25%, rgba(55, 65, 81, 0.5) 50%, rgba(55, 65, 81, 0.3) 75%);
    background-size: 1000px 100%;
    animation: ambience-shimmer 2s infinite;
    border-radius: 8px;
}

/* Transições Suaves */
.ambience-chat-card,
.ambience-message,
.ambience-bubble,
.ambience-input-btn,
.ambience-action-item {
    will-change: transform;
}

/* Performance */
.ambience-chat-messages {
    contain: layout style paint;
}

.ambience-message {
    contain: layout style;
}

.ambience-typing-indicator {
    padding: 8px 16px;
    background: rgba(17, 24, 39, 0.5);
    border-top: 1px solid rgba(34, 197, 94, 0.1);
    border-bottom: 1px solid rgba(34, 197, 94, 0.1);
    margin: 0 -8px;
    flex-shrink: 0;
    animation: ambience-typingSlideIn 0.2s ease-out;
}

@keyframes ambience-typingSlideIn {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.ambience-typing-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.ambience-typing-dots {
    display: flex;
    align-items: center;
    gap: 4px;
}

.ambience-typing-dots span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #22c55e;
    animation: ambience-typingDotsBounce 1.4s infinite ease-in-out both;
}

.ambience-typing-dots span:nth-child(1) {
    animation-delay: -0.32s;
}

.ambience-typing-dots span:nth-child(2) {
    animation-delay: -0.16s;
}

@keyframes ambience-typingDotsBounce {
    0%, 80%, 100% {
        opacity: 0.3;
        transform: scale(0.8);
    }
    40% {
        opacity: 1;
        transform: scale(1);
    }
}

.ambience-action-item.moderator-action {
    background: rgba(251, 191, 36, 0.1);
    border-left: 3px solid #fbbf24;
}

.ambience-action-item.moderator-action:hover {
    background: rgba(251, 191, 36, 0.2);
}

.ambience-action-item.moderator-action svg {
    stroke: #fbbf24;
}

.ambience-typing-text {
    color: #9ca3af;
    font-size: 13px;
    font-weight: 500;
    font-style: italic;
}

/* Responsivo */
@media (max-width: 768px) {
    .ambience-typing-indicator {
        padding: 6px 12px;
    }
    
    .ambience-typing-text {
        font-size: 12px;
    }
    
    .ambience-typing-dots span {
        width: 5px;
        height: 5px;
    }
}

.ambience-action-menu-btn-inline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px !important;
    height: 18px !important;
    margin-left: 0 !important; /* ✅ Sem margem inicial */
    margin-right: 0 !important;
    padding: 0;
    background: transparent !important;
    border: none !important;
    cursor: pointer;
    
    /* ✅ OCULTO POR PADRÃO */
    opacity: 0 !important;
    transform: scale(0.8);
    max-width: 0 !important; /* ✅ Colapsa o espaço */
    overflow: hidden;
    
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    vertical-align: middle !important;
    position: relative;
    top: -1px;
}

/* ✅ APARECE NO HOVER DA MENSAGEM */
.ambience-message:hover .ambience-action-menu-btn-inline {
    opacity: 0.5 !important;
    transform: scale(1);
    max-width: 18px !important; /* ✅ Expande o espaço */
    margin-left: 6px !important; /* ✅ Empurra para a direita */
}

/* ✅ HOVER NO BOTÃO (mais opaco) */
.ambience-action-menu-btn-inline:hover {
    opacity: 1 !important;
    transform: scale(1.15);
}

/* ✅ SVG dos três pontos verticais */
.ambience-action-menu-btn-inline svg {
    width: 14px !important;
    height: 14px !important;
    stroke: #9ca3af !important;
    transition: stroke 0.2s ease;
    flex-shrink: 0;
}

.ambience-action-menu-btn-inline:hover svg {
    stroke: #22c55e !important;
}

/* ✅ Badge "Você" se adapta suavemente */
.ambience-user-badge {
    transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

/* ✅ Quando o botão aparece, o badge se move */
.ambience-message:hover .ambience-user-badge {
    margin-left: 8px !important; /* ✅ Aumenta o espaço */
}

/* ✅ REMOVER estilos do botão antigo (se ainda existirem) */
.ambience-message-actions {
    display: none !important;
}

.ambience-action-menu-btn {
    display: none !important;
}
</style>

<script>
// ========== AJUSTES DE RENDERIZAÇÃO ==========
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎨 Chat modernizado carregado');
    
    // Garantir que o toggle funcione
    const toggleBtn = document.getElementById('toggleChatBtn');
    const chatWrapper = document.querySelector('.ambience-chat-wrapper');
    
    if (toggleBtn && chatWrapper) {
        toggleBtn.addEventListener('click', function() {
            chatWrapper.classList.toggle('collapsed');
            this.classList.toggle('collapsed');
        });
    }
    
    // Auto-scroll ao receber mensagem
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
        const observer = new MutationObserver(() => {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
        
        observer.observe(chatMessages, { childList: true, subtree: true });
    }
});
</script>