{{-- resources/views/components/chat-container.blade.php --}}
<div id="chatContainer" class="chat-container" data-sala-id="{{ $sala->id }}" data-user-id="{{ auth()->id() }}" data-user-age="{{ auth()->user()->data_de_nascimento ? \Carbon\Carbon::parse(auth()->user()->data_de_nascimento)->age : 18 }}">
    
    <!-- Header do Chat -->
    <div class="chat-header">
        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
            <div class="d-flex align-items-center">
                <i class="fas fa-comments me-2 text-primary"></i>
                <h5 class="mb-0">Chat da Sala</h5>
            </div>
            <button id="toggleChatBtn" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    </div>

    <!-- Área de Mensagens -->
    <div id="chatMessages" class="chat-messages p-3">
        <!-- Mensagens serão carregadas aqui via JavaScript -->
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="text-muted mt-2">Carregando mensagens...</p>
        </div>
    </div>

    <!-- Formulário de Envio -->
    <div class="chat-input border-top p-3">
        <form id="chatForm" enctype="multipart/form-data">
            @csrf
            <div class="mb-2">
                <div class="input-group">
                    <input type="text" 
                           id="chatMessageInput" 
                           name="mensagem" 
                           class="form-control" 
                           placeholder="Digite sua mensagem..." 
                           maxlength="2000"
                           required>
                    <label for="chatFileInput" class="btn btn-outline-secondary" title="Anexar imagem">
                        <i class="fas fa-paperclip"></i>
                    </label>
                    <input type="file" 
                           id="chatFileInput" 
                           name="anexos[]" 
                           class="d-none" 
                           accept="image/*" 
                           multiple>
                    <button type="submit" class="btn btn-primary" id="chatSendBtn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
            
            <!-- Preview de anexos -->
            <div id="chatAttachmentsPreview" class="attachments-preview d-none">
                <!-- Previews serão inseridos aqui -->
            </div>

            <!-- Alerta de moderação -->
            <div id="chatModerationAlert" class="alert alert-warning mt-2 d-none" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span id="chatModerationMessage"></span>
            </div>
        </form>
    </div>
</div>

<style>
.chat-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    height: 600px;
    max-height: 80vh;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    background: #f8f9fa;
}

.chat-message {
    margin-bottom: 16px;
    animation: slideInMessage 0.3s ease-out;
}

@keyframes slideInMessage {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chat-message-header {
    display: flex;
    align-items: center;
    margin-bottom: 4px;
}

.chat-message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 8px;
    object-fit: cover;
}

.chat-message-author {
    font-weight: 600;
    color: #2d3748;
    margin-right: 8px;
}

.chat-message-timestamp {
    font-size: 0.75rem;
    color: #718096;
}

.chat-message-content {
    background: white;
    padding: 12px;
    border-radius: 8px;
    margin-left: 40px;
    position: relative;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.chat-message-text {
    word-wrap: break-word;
    color: #2d3748;
}

.chat-message-censored {
    background: #fed7d7;
    border-left: 4px solid #f56565;
    font-style: italic;
    color: #742a2a;
}

.chat-message-flags {
    margin-top: 8px;
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}

.chat-message-flag {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.chat-message-actions {
    position: absolute;
    top: 8px;
    right: 8px;
}

.chat-message-menu {
    background: transparent;
    border: none;
    padding: 4px;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.2s;
}

.chat-message:hover .chat-message-menu {
    opacity: 1;
}

.chat-message-attachment {
    margin-top: 8px;
    max-width: 300px;
    border-radius: 8px;
    cursor: pointer;
}

.chat-input {
    background: white;
}

.attachments-preview {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    padding: 8px 0;
}

.attachment-preview-item {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e2e8f0;
}

.attachment-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.attachment-preview-remove {
    position: absolute;
    top: 4px;
    right: 4px;
    background: rgba(0,0,0,0.7);
    color: white;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
}

.chat-messages::-webkit-scrollbar {
    width: 8px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 4px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}
</style>

@include('partials.chat-denuncia-modal')