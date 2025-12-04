{{-- Overlay customizado --}}
<div class="ambience-modal-overlay" id="reportModalOverlay"></div>

{{-- Modal modernizado --}}
<div class="ambience-modal" id="reportModal" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="ambience-modal-container">
        <div class="ambience-modal-content">
            <form id="reportForm" enctype="multipart/form-data">
                @csrf
                
                {{-- Header --}}
                <div class="ambience-modal-header">
                    <div class="ambience-modal-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="ambience-modal-title" id="reportModalLabel">Denunciar Usuário</h2>
                        <p class="ambience-modal-subtitle">Reporte comportamentos que violam nossas regras</p>
                    </div>
                    <button type="button" class="ambience-modal-close" id="closeReportModal" aria-label="Fechar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="ambience-modal-body">
                    {{-- Alerta de informação --}}
                    <div class="ambience-alert ambience-alert-warning">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
    <line x1="12" y1="9" x2="12" y2="13"/>
    <line x1="12" y1="17" x2="12.01" y2="17"/>
</svg>
                        <div>
                            <strong>Atenção!</strong> Denúncias falsas podem resultar em punições à sua conta.
                            Certifique-se de que está denunciando um comportamento que realmente viola nossas regras.
                        </div>
                    </div>

                    {{-- Campos hidden --}}
                    <input type="hidden" id="report_sala_id" name="sala_id" value="{{ $sala->id ?? '' }}">
                    <input type="hidden" id="report_usuario_denunciado_id" name="usuario_denunciado_id">

                    {{-- Tipo de denúncia --}}
                    <div class="ambience-form-group">
                        <label for="report_tipo" class="ambience-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="8" y1="6" x2="21" y2="6"/>
                                <line x1="8" y1="12" x2="21" y2="12"/>
                                <line x1="8" y1="18" x2="21" y2="18"/>
                                <line x1="3" y1="6" x2="3.01" y2="6"/>
                                <line x1="3" y1="12" x2="3.01" y2="12"/>
                                <line x1="3" y1="18" x2="3.01" y2="18"/>
                            </svg>
                            Tipo de Denúncia <span class="ambience-required">*</span>
                        </label>
                        <select class="ambience-select" id="report_tipo" name="tipo_denuncia" required>
                            <option value="">Selecione...</option>
                            <option value="spam">🔴 Spam / Flood</option>
                            <option value="abuso">⚠️ Abuso Verbal / Ofensas</option>
                            <option value="assedio">🚫 Assédio</option>
                            <option value="sexual">🔞 Conteúdo Sexual Inapropriado</option>
                            <option value="outro">❓ Outro</option>
                        </select>
                        <small class="ambience-form-hint">
                            Escolha a categoria que melhor descreve o problema
                        </small>
                    </div>

                    {{-- Descrição --}}
                    <div class="ambience-form-group">
                        <label for="report_descricao" class="ambience-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            Descrição Detalhada <span class="ambience-required">*</span>
                        </label>
                        <textarea class="ambience-textarea" 
                                  id="report_descricao" 
                                  name="descricao" 
                                  rows="5" 
                                  minlength="20" 
                                  maxlength="2000" 
                                  required 
                                  placeholder="Descreva detalhadamente o que aconteceu..."></textarea>
                        <div class="ambience-form-footer">
                            <small class="ambience-form-hint">
                                Mínimo 20 caracteres. Seja específico e objetivo.
                            </small>
                            <span id="report_char_count" class="ambience-char-count">0 / 2000</span>
                        </div>
                    </div>

                    {{-- Mensagens selecionadas --}}
                    <div class="ambience-form-group">
                        <label class="ambience-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 11 12 14 22 4"/>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                            </svg>
                            Mensagens Selecionadas
                        </label>
                        <div id="selectedMessagesContainer" class="ambience-selected-messages">
                            <div class="ambience-empty-state-small">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                    <line x1="9" y1="10" x2="15" y2="10"/>
                                    <line x1="9" y1="14" x2="13" y2="14"/>
                                </svg>
                                <p>Nenhuma mensagem selecionada</p>
                            </div>
                        </div>
                        <small class="ambience-form-hint">
                            Você pode selecionar mensagens usando o menu de três pontos em cada mensagem do chat
                        </small>
                    </div>

                    {{-- Anexos (evidências) --}}
                    <div class="ambience-form-group">
                        <label for="report_anexos" class="ambience-label">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                            </svg>
                            Anexar Evidências (Opcional)
                        </label>
                        <div class="ambience-file-input-wrapper">
                            <input type="file" 
                                   class="ambience-file-input" 
                                   id="report_anexos" 
                                   name="anexos[]" 
                                   multiple 
                                   accept="image/*,.pdf">
                            <label for="report_anexos" class="ambience-file-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                <span>Clique para selecionar arquivos</span>
                            </label>
                        </div>
                        <small class="ambience-form-hint">
                            Máximo 5 arquivos. Formatos: JPG, PNG, GIF, PDF. Máximo 10MB por arquivo.
                        </small>
                        
                        {{-- Preview de anexos --}}
                        <div id="reportAttachmentsPreview" class="ambience-attachments-preview-report"></div>
                    </div>

                    {{-- Aviso final --}}
                    <div class="ambience-alert ambience-alert-info">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        <div>
                            <strong>Como funciona:</strong>
                            <ul>
                                <li>Sua denúncia será analisada pela equipe de moderação</li>
                                <li>Você receberá atualizações por email e notificações no sistema</li>
                                <li>Mensagens e anexos enviados ficam registrados no ticket</li>
                                <li>O processo pode levar até 48 horas úteis</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="ambience-modal-footer">
                    <button type="button" class="ambience-btn ambience-btn-secondary" id="cancelReportBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        Cancelar
                    </button>
                    <button type="submit" class="ambience-btn ambience-btn-danger" id="reportSubmitBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                        Enviar Denúncia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================== 
     ESTILOS MODERNOS DO MODAL
     ======================================== --}}
<style>
/* ========== OVERLAY ========== */
.ambience-modal-overlay {
    position: fixed !important;
    inset: 0 !important;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 9998 !important;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    pointer-events: none !important;
}

.ambience-modal-overlay.active {
    pointer-events: auto !important; /* ✅ Ativa interação quando ativo */
    opacity: 1 !important;
    visibility: visible !important;
}

/* ========== MODAL CONTAINER ========== */
.ambience-modal {
    position: fixed !important;
    inset: 0 !important;
    z-index: 9999 !important;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    overflow-y: auto;
    pointer-events: none !important;
}

.ambience-modal.active {
    pointer-events: auto !important; /* ✅ Ativa interação */
    opacity: 1 !important;
    visibility: visible !important;
    display: flex !important;
}

.ambience-modal-container {
    pointer-events: auto !important; /* ✅ Garante que o conteúdo seja clicável */
}

.ambience-modal-container {
    width: 100%;
    max-width: 680px;
    margin: auto;
    transform: scale(0.9) translateY(20px);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.ambience-modal.active .ambience-modal-container {
    transform: scale(1) translateY(0);
}

.ambience-modal-content {
    background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.95));
    border: 1px solid rgba(34, 197, 94, 0.2);
    border-radius: 20px;
    box-shadow: 0 24px 64px rgba(0, 0, 0, 0.8);
    overflow: hidden;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
}

/* ========== HEADER ========== */
.ambience-modal-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 28px 32px;
    border-bottom: 1px solid rgba(34, 197, 94, 0.15);
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.08), transparent);
}

.ambience-modal-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.1));
    border: 2px solid rgba(239, 68, 68, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.ambience-modal-icon svg {
    width: 24px;
    height: 24px;
    stroke: #ef4444;
}

.ambience-modal-title {
    font-size: 22px;
    font-weight: 700;
    color: #fff;
    margin: 0 0 4px 0;
    line-height: 1.2;
}

.ambience-modal-subtitle {
    font-size: 14px;
    color: #9ca3af;
    margin: 0;
    line-height: 1.4;
}

.ambience-modal-close {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: rgba(55, 65, 81, 0.5);
    border: 1px solid rgba(55, 65, 81, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    margin-left: auto;
    flex-shrink: 0;
}

.ambience-modal-close:hover {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.5);
}

.ambience-modal-close svg {
    width: 18px;
    height: 18px;
    stroke: #9ca3af;
    transition: stroke 0.2s;
}

.ambience-modal-close:hover svg {
    stroke: #ef4444;
}

/* ========== BODY ========== */
.ambience-modal-body {
    padding: 28px 32px;
    max-height: calc(100vh - 300px);
    overflow-y: auto;
}

.ambience-modal-body::-webkit-scrollbar {
    width: 6px;
}

.ambience-modal-body::-webkit-scrollbar-track {
    background: rgba(17, 24, 39, 0.5);
    border-radius: 3px;
}

.ambience-modal-body::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.3);
    border-radius: 3px;
}

.ambience-modal-body::-webkit-scrollbar-thumb:hover {
    background: rgba(34, 197, 94, 0.5);
}

/* ========== ALERTAS ========== */
.ambience-alert {
    display: flex;
    gap: 12px;
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 24px;
    font-size: 14px;
    line-height: 1.6;
}

.ambience-alert svg {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
    margin-top: 2px;
}

.ambience-alert strong {
    display: block;
    margin-bottom: 4px;
    font-weight: 600;
}

.ambience-alert ul {
    margin: 8px 0 0 0;
    padding-left: 20px;
}

.ambience-alert li {
    margin: 4px 0;
}

.ambience-alert-warning {
    background: rgba(251, 191, 36, 0.12);
    border: 1px solid rgba(251, 191, 36, 0.25);
    color: #fbbf24;
}

.ambience-alert-warning svg {
    stroke: #fbbf24;
}

.ambience-alert-info {
    background: rgba(59, 130, 246, 0.12);
    border: 1px solid rgba(59, 130, 246, 0.25);
    color: #60a5fa;
}

.ambience-alert-info svg {
    stroke: #60a5fa;
}

/* ========== FORM GROUPS ========== */
.ambience-form-group {
    margin-bottom: 24px;
}

.ambience-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #e5e7eb;
    margin-bottom: 10px;
}

.ambience-label svg {
    width: 16px;
    height: 16px;
    stroke: #22c55e;
}

.ambience-required {
    color: #ef4444;
    font-weight: 700;
}

.ambience-select,
.ambience-textarea {
    width: 100%;
    background: rgba(17, 24, 39, 0.8);
    border: 2px solid rgba(55, 65, 81, 0.8);
    border-radius: 12px;
    padding: 12px 16px;
    color: #f9fafb;
    font-size: 14px;
    font-weight: 500;
    font-family: Inter, sans-serif;
    transition: all 0.3s;
}

.ambience-select:focus,
.ambience-textarea:focus {
    outline: none;
    border-color: rgba(34, 197, 94, 0.5);
    background: rgba(17, 24, 39, 0.95);
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

.ambience-select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 40px;
}

.ambience-textarea {
    resize: vertical;
    min-height: 120px;
}

.ambience-textarea::placeholder {
    color: #6b7280;
}

.ambience-form-hint {
    display: block;
    font-size: 12px;
    color: #9ca3af;
    margin-top: 6px;
}

.ambience-form-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
}

.ambience-char-count {
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
    background: rgba(55, 65, 81, 0.5);
    color: #9ca3af;
    transition: all 0.2s;
}

.ambience-char-count.warning {
    background: rgba(251, 191, 36, 0.15);
    color: #fbbf24;
}

.ambience-char-count.success {
    background: rgba(34, 197, 94, 0.15);
    color: #22c55e;
}

.ambience-char-count.danger {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}

/* ========== MENSAGENS SELECIONADAS ========== */
.ambience-selected-messages {
    background: rgba(17, 24, 39, 0.5);
    border: 1px solid rgba(55, 65, 81, 0.5);
    border-radius: 12px;
    padding: 16px;
    min-height: 80px;
}

.ambience-empty-state-small {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    text-align: center;
    color: #9ca3af !important;
}

.ambience-empty-state-small svg {
    width: 40px;
    height: 40px;
    stroke: #6b7280 !important; /* ✅ COR MAIS VISÍVEL PARA O ÍCONE */
    margin-bottom: 12px;
    opacity: 0.7; /* ✅ REDUZIDO DE 0.5 PARA 0.7 */
}

.ambience-empty-state-small p {
    font-size: 14px;
    margin: 0;
    color: #9ca3af !important; /* ✅ COR EXPLÍCITA MAIS CLARA */
    font-weight: 500; /* ✅ TEXTO MAIS FORTE */
}

.selected-message-preview {
    padding: 12px;
    background: rgba(255, 255, 255, 0.03);
    border-left: 3px solid #22c55e;
    margin-bottom: 8px;
    border-radius: 8px;
    font-size: 13px;
    color: #d1d5db;
}

.selected-message-preview:last-child {
    margin-bottom: 0;
}

/* ========== FILE INPUT ========== */
.ambience-file-input-wrapper {
    position: relative;
    margin-bottom: 12px;
}

.ambience-file-input {
    position: absolute;
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    z-index: -1;
}

.ambience-file-label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px 24px;
    background: rgba(17, 24, 39, 0.6);
    border: 2px dashed rgba(55, 65, 81, 0.8);
    border-radius: 12px;
    color: #9ca3af;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.ambience-file-label:hover {
    background: rgba(34, 197, 94, 0.05);
    border-color: rgba(34, 197, 94, 0.4);
    color: #22c55e;
}

.ambience-file-label svg {
    width: 20px;
    height: 20px;
    stroke: currentColor;
}

/* ========== ATTACHMENTS PREVIEW ========== */
.ambience-attachments-preview-report {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 12px;
}

.ambience-attachment-preview-item {
    position: relative;
    width: 90px;
    height: 90px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid rgba(34, 197, 94, 0.3);
    background: rgba(17, 24, 39, 0.6);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 8px;
    transition: all 0.2s;
}

.ambience-attachment-preview-item:hover {
    border-color: #22c55e;
    transform: scale(1.05);
}

.ambience-attachment-preview-item svg {
    width: 32px;
    height: 32px;
    stroke: #9ca3af;
    margin-bottom: 6px;
}

.ambience-attachment-name {
    font-size: 10px;
    color: #d1d5db;
    text-align: center;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 100%;
}

.ambience-attachment-size {
    font-size: 9px;
    color: #6b7280;
    margin-top: 2px;
}

.ambience-attachment-remove-btn {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: rgba(239, 68, 68, 0.9);
    border: 2px solid #1f2937;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.ambience-attachment-remove-btn:hover {
    background: #ef4444;
    transform: scale(1.1);
}

.ambience-attachment-remove-btn svg {
    width: 12px;
    height: 12px;
    stroke: white;
    stroke-width: 3;
    margin: 0;
}

/* ========== FOOTER ========== */
.ambience-modal-footer {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding: 20px 32px;
    border-top: 1px solid rgba(34, 197, 94, 0.15);
    background: rgba(17, 24, 39, 0.3);
}

.ambience-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    font-family: Inter, sans-serif;
    border: none;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.ambience-btn svg {
    width: 16px;
    height: 16px;
    stroke: currentColor;
}

.ambience-btn-secondary {
    background: transparent;
    border: 1px solid rgba(55, 65, 81, 0.8);
    color: #d1d5db;
}

.ambience-btn-secondary:hover {
    background: rgba(55, 65, 81, 0.3);
    border-color: rgba(55, 65, 81, 1);
    transform: translateY(-1px);
}

.ambience-btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
}

.ambience-btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
}

.ambience-btn-danger:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.ambience-btn-danger:disabled:hover {
    transform: none;
    box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
}

/* ========== LOADING STATE ========== */
.ambience-btn .spinner-border {
    width: 14px;
    height: 14px;
    border-width: 2px;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
    .ambience-modal {
        padding: 16px;
    }

    .ambience-modal-header {
        padding: 20px 20px;
        flex-wrap: wrap;
    }

    .ambience-modal-icon {
        width: 40px;
        height: 40px;
    }

    .ambience-modal-icon svg {
        width: 20px;
        height: 20px;
    }

    .ambience-modal-title {
        font-size: 18px;
    }

    .ambience-modal-subtitle {
        font-size: 13px;
    }

    .ambience-modal-body {
        padding: 20px;
        max-height: calc(100vh - 250px);
    }

    .ambience-modal-footer {
        padding: 16px 20px;
        flex-direction: column;
    }

    .ambience-btn {
        width: 100%;
        justify-content: center;
    }

    .ambience-alert {
        font-size: 13px;
    }
}

@media (max-width: 480px) {
    .ambience-modal-container {
        max-width: 100%;
    }

    .ambience-modal-header {
        gap: 12px;
    }

    .ambience-attachment-preview-item {
        width: 70px;
        height: 70px;
    }
}
</style>

{{-- ======================================== 
     SCRIPT DO MODAL DE DENÚNCIA
     ======================================== --}}
<script>
/**
 * ========================================
 * MODAL DE DENÚNCIA - INTEGRADO COM CHAT.JS
 * ========================================
 * ✅ Utiliza métodos do chatSystem
 * ✅ Sem duplicação de código
 */

class ReportModal {
    constructor() {
        this.modal = document.getElementById('reportModal');
        this.overlay = document.getElementById('reportModalOverlay');
        this.form = document.getElementById('reportForm');
        this.descricao = document.getElementById('report_descricao');
        this.charCount = document.getElementById('report_char_count');
        this.anexosInput = document.getElementById('report_anexos');
        this.attachmentsPreview = document.getElementById('reportAttachmentsPreview');
        this.submitBtn = document.getElementById('reportSubmitBtn');
        
        this.attachments = [];
        
        this.init();
    }

    init() {
        if (!this.modal) {
            console.warn('[ReportModal] Modal não encontrado');
            return;
        }

        this.setupEventListeners();
        console.log('[ReportModal] ✅ Inicializado');
    }

    setupEventListeners() {
        // Botões de fechar
        document.getElementById('closeReportModal')?.addEventListener('click', () => this.close());
        document.getElementById('cancelReportBtn')?.addEventListener('click', () => this.close());
        
        // Fechar ao clicar no overlay
        this.overlay?.addEventListener('click', () => this.close());
        
        // Fechar com ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen()) {
                this.close();
            }
        });

        // Contador de caracteres
        this.descricao?.addEventListener('input', () => this.updateCharCount());

        // Anexos
        this.anexosInput?.addEventListener('change', (e) => this.handleFileSelect(e));

        // Submit
        this.form?.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    open(userId) {
    if (!this.modal || !this.overlay) {
        console.error('[ReportModal] ❌ Modal ou overlay não encontrado');
        return;
    }

    console.log('[ReportModal] 🚀 Abrindo modal para usuário:', userId);

    document.getElementById('report_usuario_denunciado_id').value = userId;
    
    // ✅ Atualizar display de mensagens selecionadas via chatSystem
    if (window.chatSystem) {
        window.chatSystem.updateSelectedMessagesDisplay();
    }

    // ✅ FORÇAR display primeiro
    this.modal.style.display = 'flex';
    this.overlay.style.display = 'block';
    
    // ✅ Adicionar classes
    this.modal.classList.add('active');
    this.overlay.classList.add('active');
    
    // ✅ FORÇAR visibilidade e opacidade
    setTimeout(() => {
        this.modal.style.opacity = '1';
        this.modal.style.visibility = 'visible';
        this.overlay.style.opacity = '1';
        this.overlay.style.visibility = 'visible';
    }, 10);
    
    document.body.style.overflow = 'hidden';
    
    console.log('[ReportModal] ✅ Modal aberto');
}

    close() {
    if (!this.modal || !this.overlay) {
        console.warn('[ReportModal] ⚠️ Modal ou overlay não encontrado');
        return;
    }

    console.log('[ReportModal] 🚪 Fechando modal...');

    // ✅ Remover classes
    this.modal.classList.remove('active');
    this.overlay.classList.remove('active');
    
    // ✅ Esconder elementos
    setTimeout(() => {
        this.modal.style.display = 'none';
        this.overlay.style.display = 'none';
        this.modal.style.opacity = '0';
        this.overlay.style.opacity = '0';
        this.modal.style.visibility = 'hidden';
        this.overlay.style.visibility = 'hidden';
    }, 300); // Aguardar animação
    
    document.body.style.overflow = '';
    
    // Limpar formulário
    this.reset();
    
    console.log('[ReportModal] ✅ Fechado');
}

    isOpen() {
        return this.modal?.classList.contains('active');
    }

    updateCharCount() {
        const count = this.descricao.value.length;
        this.charCount.textContent = `${count} / 2000`;
        
        this.charCount.classList.remove('danger', 'warning', 'success');
        
        if (count < 20) {
            this.charCount.classList.add('danger');
        } else if (count < 100) {
            this.charCount.classList.add('warning');
        } else {
            this.charCount.classList.add('success');
        }
    }

    async handleFileSelect(e) {
        const files = Array.from(e.target.files);
        
        for (const file of files) {
            if (this.attachments.length >= 5) {
                alert('Máximo de 5 anexos');
                break;
            }

            if (file.size > 10 * 1024 * 1024) {
                alert(`Arquivo ${file.name} muito grande (máx 10MB)`);
                continue;
            }

            this.attachments.push(file);
        }

        this.renderAttachmentsPreview();
    }

    renderAttachmentsPreview() {
        if (this.attachments.length === 0) {
            this.attachmentsPreview.innerHTML = '';
            return;
        }

        this.attachmentsPreview.innerHTML = this.attachments.map((file, index) => `
            <div class="ambience-attachment-preview-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                    <polyline points="13 2 13 9 20 9"/>
                </svg>
                <div class="ambience-attachment-name">${this.escapeHtml(file.name)}</div>
                <div class="ambience-attachment-size">${this.formatFileSize(file.size)}</div>
                <button type="button" 
                        class="ambience-attachment-remove-btn" 
                        data-index="${index}"
                        title="Remover">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
        `).join('');

        // Adicionar listeners aos botões de remoção
        this.attachmentsPreview.querySelectorAll('.ambience-attachment-remove-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const index = parseInt(e.currentTarget.dataset.index);
                this.attachments.splice(index, 1);
                this.renderAttachmentsPreview();
            });
        });
    }

    async handleSubmit(e) {
        e.preventDefault();

        // Validações
        const tipo = document.getElementById('report_tipo').value;
        const descricao = this.descricao.value.trim();
        const usuarioDenunciadoId = document.getElementById('report_usuario_denunciado_id').value;

        if (!tipo || !descricao || !usuarioDenunciadoId) {
            alert('Preencha todos os campos obrigatórios');
            return;
        }

        if (descricao.length < 20) {
            alert('A descrição deve ter no mínimo 20 caracteres');
            return;
        }

        // Preparar dados
        const formData = new FormData();
        formData.append('sala_id', document.getElementById('report_sala_id').value);
        formData.append('usuario_denunciado_id', usuarioDenunciadoId);
        formData.append('tipo_denuncia', tipo);
        formData.append('descricao', descricao);

        // ✅ Adicionar mensagens selecionadas via chatSystem
        if (window.chatSystem && window.chatSystem.selectedMessages.size > 0) {
            const selectedArray = Array.from(window.chatSystem.selectedMessages);
            selectedArray.forEach((msgId, index) => {
                formData.append(`mensagens_selecionadas[${index}]`, msgId);
            });
        }

        // Adicionar anexos
        this.attachments.forEach((file, index) => {
            formData.append(`anexos[${index}]`, file);
        });

        // Loading state
        this.submitBtn.disabled = true;
        this.submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm"></span>
            Enviando...
        `;

        try {
            const response = await fetch('/chat/denunciar-usuario', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                alert(`Denúncia enviada com sucesso!\n\nNúmero do ticket: ${data.numero_ticket}\n\nVocê pode acompanhar o status em Sistema de Suporte.`);
                
                this.close();
                
                // ✅ Limpar mensagens selecionadas via chatSystem
                if (window.chatSystem) {
                    window.chatSystem.clearSelectedMessages();
                }

                // Opcional: redirecionar para o ticket
                if (confirm('Deseja visualizar o ticket criado?')) {
                    window.location.href = `/suporte/${data.ticket_id}`;
                }
            } else {
                throw new Error(data.message || 'Erro ao enviar denúncia');
            }

        } catch (error) {
            console.error('[ReportModal] Erro:', error);
            alert('Erro ao enviar denúncia: ' + error.message);
        } finally {
            this.submitBtn.disabled = false;
            this.submitBtn.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="22" y1="2" x2="11" y2="13"/>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                </svg>
                Enviar Denúncia
            `;
        }
    }

    reset() {
        this.form?.reset();
        this.attachments = [];
        this.renderAttachmentsPreview();
        this.charCount.textContent = '0 / 2000';
        this.charCount.className = 'ambience-char-count';
    }

    // ========== UTILITIES ==========

    formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
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

// ========== INICIALIZAÇÃO ==========
let reportModal;

document.addEventListener('DOMContentLoaded', function() {
    reportModal = new ReportModal();
    window.reportModal = reportModal;
    
    // ✅ Expor função global para compatibilidade com chatSystem
    window.openReportModal = function(userId, messageId = null) {
        if (reportModal) {
            reportModal.open(userId);
        }
    };
});
</script>

<script>
// ========================================
// 🔧 SISTEMA DE DEBUG E CORREÇÃO AUTOMÁTICA
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔧 [Modal Fix] Iniciando diagnóstico...');
    
    const modal = document.getElementById('reportModal');
    const overlay = document.getElementById('reportModalOverlay');
    
    if (!modal || !overlay) {
        console.error('❌ Modal ou overlay não encontrado!');
        return;
    }
    
    // ✅ 1. MOVER PARA O BODY
    if (modal.parentElement !== document.body) {
        console.warn('⚠️ Movendo modal para body...');
        document.body.appendChild(modal);
    }
    
    if (overlay.parentElement !== document.body) {
        console.warn('⚠️ Movendo overlay para body...');
        document.body.appendChild(overlay);
    }
    
    // ✅ 2. FORÇAR ESTILOS INLINE (última chance)
    modal.style.position = 'fixed';
    modal.style.inset = '0';
    modal.style.zIndex = '99999';
    modal.style.display = 'none'; // Oculto por padrão
    
    overlay.style.position = 'fixed';
    overlay.style.inset = '0';
    overlay.style.zIndex = '99998';
    overlay.style.display = 'none'; // Oculto por padrão
    
    console.log('✅ Modal e overlay reposicionados');
    
    // ✅ 3. OVERRIDE DA FUNÇÃO OPEN DO ReportModal
    setTimeout(() => {
        if (window.reportModal) {
            const originalOpen = window.reportModal.open.bind(window.reportModal);
            
            window.reportModal.open = function(userId) {
                console.log('🚀 [Override] Abrindo modal com correções...');
                
                // Chamar função original
                originalOpen(userId);
                
                // ✅ GARANTIR que as classes e estilos sejam aplicados
                setTimeout(() => {
                    // Forçar display
                    modal.style.display = 'flex';
                    overlay.style.display = 'block';
                    
                    // Forçar classes
                    modal.classList.add('active');
                    overlay.classList.add('active');
                    
                    // Forçar opacity
                    modal.style.opacity = '1';
                    overlay.style.opacity = '1';
                    
                    // Forçar visibility
                    modal.style.visibility = 'visible';
                    overlay.style.visibility = 'visible';
                    
                    console.log('✅ Estilos forçados aplicados');
                }, 10);
            };
            
            console.log('✅ Função open() sobrescrita com sucesso');
        }
    }, 500);
});

// ✅ 4. FUNÇÃO GLOBAL DE TESTE
window.testReportModal = function() {
    console.log('🧪 ===== TESTE COMPLETO DO MODAL =====');
    
    const modal = document.getElementById('reportModal');
    const overlay = document.getElementById('reportModalOverlay');
    
    if (!modal || !overlay) {
        console.error('❌ Elementos não encontrados');
        return;
    }
    
    console.log('📊 Estado atual:');
    console.table({
        'Modal Parent': modal.parentElement?.tagName,
        'Modal Display': window.getComputedStyle(modal).display,
        'Modal Z-Index': window.getComputedStyle(modal).zIndex,
        'Modal Opacity': window.getComputedStyle(modal).opacity,
        'Overlay Display': window.getComputedStyle(overlay).display,
        'Overlay Z-Index': window.getComputedStyle(overlay).zIndex,
        'Overlay Opacity': window.getComputedStyle(overlay).opacity
    });
    
    console.log('🚀 Tentando abrir...');
    
    // Forçar abertura manual
    modal.style.display = 'flex';
    modal.style.opacity = '1';
    modal.style.visibility = 'visible';
    modal.classList.add('active');
    
    overlay.style.display = 'block';
    overlay.style.opacity = '1';
    overlay.style.visibility = 'visible';
    overlay.classList.add('active');
    
    console.log('✅ Modal aberto à força');
    console.log('=====================================');
};

// ✅ 5. EXPOR NO CONSOLE
console.log('💡 Use window.testReportModal() para testar o modal');
</script>