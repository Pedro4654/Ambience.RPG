{{-- resources/views/partials/profile-photo-editor.blade.php --}}

<!-- Cropper (mesma versão usada no banner) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<style>
/* ========== PROFILE PHOTO EDITOR STYLES ========== */
.profile-editor-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 10000;
    animation: profileFadeIn 0.3s ease;
}

.profile-editor-modal.show {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.profile-editor-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(8px);
    cursor: pointer;
    z-index: 1;
}

.profile-editor-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 95%;
    max-width: 1000px;
    max-height: 90vh;
    z-index: 2;
}

.profile-editor-wrapper {
    width: 100%;
    height: auto;
    max-height: 90vh;
    background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.95));
    border-radius: 24px;
    border: 1px solid rgba(34, 197, 94, 0.2);
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6);
    overflow: hidden;
    animation: profileModalSlideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: flex;
    flex-direction: column;
}

@keyframes profileFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes profileModalSlideUp {
    from {
        opacity: 0;
        transform: translateY(50px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.profile-editor-body {
    padding: 2rem;
    overflow-y: auto;
    flex: 1 1 auto;
    min-height: 0;
}

/* Header */
.profile-editor-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 2rem 2rem 1.5rem;
    border-bottom: 1px solid rgba(34, 197, 94, 0.15);
    background: linear-gradient(135deg, rgba(5, 46, 22, 0.3), transparent);
    position: relative;
    overflow: hidden;
}

.profile-editor-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at top left, rgba(34, 197, 94, 0.1), transparent 60%);
    animation: glow 3s ease-in-out infinite;
}

@keyframes glow {
    0%, 100% { opacity: 0.5; }
    50% { opacity: 1; }
}

.profile-editor-title-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    z-index: 2;
}

.profile-editor-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.1));
    border: 1px solid rgba(34, 197, 94, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-editor-icon svg {
    width: 24px;
    height: 24px;
    stroke: var(--accent);
}

.profile-editor-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #fff;
    margin: 0;
    letter-spacing: 0.5px;
}

.profile-editor-subtitle {
    font-size: 0.875rem;
    color: var(--muted);
    margin: 0.25rem 0 0;
}

.profile-editor-close {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    z-index: 2;
}

.profile-editor-close:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: #ef4444;
    transform: rotate(90deg);
}

.profile-editor-close svg {
    width: 18px;
    height: 18px;
    stroke: var(--muted);
    transition: stroke 0.2s;
}

.profile-editor-close:hover svg {
    stroke: #ef4444;
}

/* Body */
.profile-editor-body::-webkit-scrollbar {
    width: 8px;
}

.profile-editor-body::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 4px;
}

.profile-editor-body::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.3);
    border-radius: 4px;
}

/* Alert Container */
.profile-alert-container {
    margin-bottom: 1.5rem;
}

.profile-info-card {
    background: rgba(59, 130, 246, 0.08);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 12px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    animation: slideDown 0.4s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.profile-info-card svg {
    width: 20px;
    height: 20px;
    stroke: #3b82f6;
    flex-shrink: 0;
}

.profile-info-card span {
    font-size: 0.875rem;
    color: #d1d5db;
    line-height: 1.6;
}

.profile-info-card strong {
    color: #3b82f6;
    font-weight: 700;
}

/* Layout Grid */
.profile-editor-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

/* Canvas Section */
.profile-canvas-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.profile-canvas-wrapper {
    width: 100%;
    min-height: 400px;
    background: rgba(17, 24, 39, 0.6);
    border: 2px dashed rgba(55, 65, 81, 0.5);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
    transition: all 0.3s;
}

.profile-canvas-wrapper:hover {
    border-color: rgba(34, 197, 94, 0.4);
}

.profile-canvas-wrapper.has-image {
    border-style: solid;
    border-color: rgba(34, 197, 94, 0.3);
    background: #000;
}

#profileEditorImage {
    max-width: 100%;
    max-height: 500px;
    display: none;
}

.profile-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    color: var(--muted);
    padding: 3rem;
}

.profile-empty-state svg {
    width: 64px;
    height: 64px;
    stroke: rgba(34, 197, 94, 0.3);
    opacity: 0.6;
}

.profile-empty-state h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #9ca3af;
    margin: 0;
}

.profile-empty-state p {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
    text-align: center;
}

/* Controls Section */
.profile-controls-section {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.profile-control-card {
    background: rgba(17, 24, 39, 0.6);
    border: 1px solid rgba(55, 65, 81, 0.5);
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s;
}

.profile-control-card:hover {
    border-color: rgba(34, 197, 94, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

.profile-control-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.profile-control-header svg {
    width: 20px;
    height: 20px;
    stroke: var(--accent);
}

.profile-control-header h3 {
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
}

/* File Upload */
.profile-file-input-wrapper {
    position: relative;
}

.profile-file-input {
    display: none;
}

.profile-file-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    padding: 1.5rem;
    background: rgba(34, 197, 94, 0.08);
    border: 2px dashed rgba(34, 197, 94, 0.3);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
}

.profile-file-label:hover {
    background: rgba(34, 197, 94, 0.15);
    border-color: var(--accent);
    transform: translateY(-2px);
}

.profile-file-label svg {
    width: 32px;
    height: 32px;
    stroke: var(--accent);
}

.profile-file-label-text {
    font-size: 0.875rem;
    font-weight: 600;
    color: #d1d5db;
}

.profile-file-label-hint {
    font-size: 0.75rem;
    color: var(--muted);
}

/* Preview */
.profile-preview-wrapper {
    width: 100%;
    height: 200px;
    background: rgba(17, 24, 39, 0.8);
    border: 1px solid rgba(55, 65, 81, 0.5);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

#profilePreviewImg {
    max-width: 100%;
    max-height: 100%;
    display: none;
}

.profile-preview-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    color: var(--muted);
}

.profile-preview-empty svg {
    width: 32px;
    height: 32px;
    stroke: rgba(34, 197, 94, 0.3);
}

.profile-preview-empty span {
    font-size: 0.75rem;
}

/* Buttons */
.profile-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 0.875rem 1.5rem;
    border-radius: 12px;
    font-size: 0.95rem;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    width: 100%;
    margin-bottom: 0.75rem;
}

.profile-btn svg {
    width: 18px;
    height: 18px;
    transition: transform 0.3s;
}

.profile-btn:hover svg {
    transform: scale(1.1);
}

.profile-btn-primary {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #052e16;
    box-shadow: 0 4px 14px rgba(34, 197, 94, 0.4);
}

.profile-btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(34, 197, 94, 0.5);
}

.profile-btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.profile-btn-danger {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.profile-btn-danger:hover {
    background: rgba(239, 68, 68, 0.25);
    border-color: #ef4444;
    transform: translateY(-2px);
}

.profile-btn-secondary {
    background: rgba(55, 65, 81, 0.6);
    color: #d1d5db;
    border: 1px solid rgba(55, 65, 81, 0.8);
}

.profile-btn-secondary:hover {
    background: rgba(55, 65, 81, 0.8);
    border-color: rgba(107, 114, 128, 0.8);
    transform: translateY(-2px);
}

/* Divider */
.profile-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(34, 197, 94, 0.2), transparent);
    margin: 1.5rem 0;
}

/* Remove Confirm */
.profile-remove-confirm {
    background: rgba(239, 68, 68, 0.08);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 12px;
    padding: 1rem;
    animation: slideDown 0.3s ease;
}

.profile-remove-confirm-text {
    font-size: 0.875rem;
    color: #f87171;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.profile-remove-confirm-text svg {
    width: 18px;
    height: 18px;
    stroke: #ef4444;
}

.profile-remove-actions {
    display: flex;
    gap: 0.5rem;
}

.profile-btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

/* Loading State */
.profile-btn.loading {
    pointer-events: none;
    opacity: 0.7;
    animation: pulseButton 1.5s ease-in-out infinite;
}

@keyframes pulseButton {
    0%, 100% { opacity: 0.7; }
    50% { opacity: 0.85; }
}

/* Cropper.js Custom Styles */
.cropper-container {
    border-radius: 12px;
    overflow: hidden;
}

.cropper-view-box {
    border-radius: 50%;
    box-shadow: 0 0 0 1px rgba(34, 197, 94, 0.5);
}

.cropper-face {
    background-color: rgba(34, 197, 94, 0.1) !important;
}

.cropper-line {
    background-color: var(--accent);
}

.cropper-point {
    background-color: var(--accent);
    border: 2px solid #052e16;
}

.cropper-point.point-se {
    width: 8px;
    height: 8px;
    opacity: 1;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-editor-grid {
        grid-template-columns: 1fr;
    }
    
    .profile-editor-container {
        width: 100%;
        max-width: 100%;
        height: 100%;
        max-height: 100%;
        border-radius: 0;
    }
    
    .profile-editor-wrapper {
        border-radius: 0;
        max-height: 100vh;
    }
}
</style>

<!-- Modal Structure -->
<div class="profile-editor-modal" id="modalProfileEditor">
    <div class="profile-editor-overlay" onclick="closeProfileEditor()"></div>
    
    <div class="profile-editor-container">
        <div class="profile-editor-wrapper">
            <!-- Header -->
            <div class="profile-editor-header">
                <div class="profile-editor-title-wrapper">
                    <div class="profile-editor-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="profile-editor-title">Editor de Foto de Perfil</h2>
                        <p class="profile-editor-subtitle">Personalize a identidade visual da sala</p>
                    </div>
                </div>
                <button class="profile-editor-close" onclick="closeProfileEditor()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="profile-editor-body">
                <!-- Alerts -->
                <div class="profile-alert-container" id="profileEditorMessageContainer"></div>
                <div id="profileNsfwAlert"></div>

                <!-- Info Card -->
                <div class="profile-info-card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 16v-4"/>
                        <path d="M12 8h.01"/>
                    </svg>
                    <span>
                        A imagem precisa ser <strong>quadrada (1:1)</strong>. 
                        Recomendado: <strong>500x500</strong> pixels. Máximo 5MB.
                    </span>
                </div>

                <!-- Grid Layout -->
                <div class="profile-editor-grid">
                    <!-- Canvas Section -->
                    <div class="profile-canvas-section">
                        <div class="profile-canvas-wrapper" id="profileCanvasWrapper">
                            <img id="profileEditorImage" alt="Foto de Perfil"/>
                            <div class="profile-empty-state" id="profileEditorEmpty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                <h4>Nenhuma Imagem Selecionada</h4>
                                <p>Faça upload de uma imagem para começar</p>
                            </div>
                        </div>
                    </div>

                    <!-- Controls Section -->
                    <div class="profile-controls-section">
                        <!-- Upload Card -->
                        <div class="profile-control-card">
                            <div class="profile-control-header">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                <h3>Upload de Imagem</h3>
                            </div>

                            <div class="profile-file-input-wrapper">
                                <input 
                                    type="file" 
                                    id="profileFileInput" 
                                    class="profile-file-input" 
                                    accept="image/png,image/jpeg"
                                />
                                <label for="profileFileInput" class="profile-file-label">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="17 8 12 3 7 8"/>
                                        <line x1="12" y1="3" x2="12" y2="15"/>
                                    </svg>
                                    <span class="profile-file-label-text">Escolher Imagem</span>
                                    <span class="profile-file-label-hint">PNG ou JPG • Máx 5MB</span>
                                </label>
                            </div>

                            <div class="profile-divider"></div>

                            <!-- Preview -->
                            <div>
                                <div class="profile-control-header" style="margin-bottom: 0.75rem;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <h3>Pré-visualização</h3>
                                </div>
                                <div class="profile-preview-wrapper">
                                    <img id="profilePreviewImg" alt="Preview"/>
                                    <div class="profile-preview-empty" id="profilePreviewEmpty">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                        <span>Aguardando imagem</span>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-divider"></div>

                            <!-- Actions -->
                            <button id="btnUploadProfile" class="profile-btn profile-btn-primary" disabled>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                    <polyline points="17 21 17 13 7 13 7 21"/>
                                    <polyline points="7 3 7 8 15 8"/>
                                </svg>
                                <span>Salvar Foto</span>
                            </button>

                            <button id="btnRemoveProfile" class="profile-btn profile-btn-danger">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                                <span>Remover Foto</span>
                            </button>

                            <!-- Remove Confirm -->
                            <div id="profileRemoveConfirm" class="profile-remove-confirm" style="display: none;">
                                <div class="profile-remove-confirm-text">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                        <line x1="12" y1="9" x2="12" y2="13"/>
                                        <circle cx="12" cy="17" r="0.5" fill="currentColor"/>
                                    </svg>
                                    Confirma remoção da foto desta sala?
                                </div>
                                <div class="profile-remove-actions">
                                    <button id="btnConfirmRemoveProfile" class="profile-btn profile-btn-danger profile-btn-sm">
                                        Confirmar
                                    </button>
                                    <button id="btnCancelRemoveProfile" class="profile-btn profile-btn-secondary profile-btn-sm">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                </div>

                <!-- Hidden Input -->
                <input type="hidden" id="profileSalaId" value="" />
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let cropper = null;
    let currentFile = null;
    let nsfwAnalysisResult = null;
    
    const modal = document.getElementById('modalProfileEditor');
    const img = document.getElementById('profileEditorImage');
    const emptyMsg = document.getElementById('profileEditorEmpty');
    const canvasWrapper = document.getElementById('profileCanvasWrapper');
    const fileInput = document.getElementById('profileFileInput');
    const previewImg = document.getElementById('profilePreviewImg');
    const previewEmpty = document.getElementById('profilePreviewEmpty');
    const btnUpload = document.getElementById('btnUploadProfile');
    const btnRemove = document.getElementById('btnRemoveProfile');
    const salaIdInput = document.getElementById('profileSalaId');
    const messageContainer = document.getElementById('profileEditorMessageContainer');
    const removeConfirm = document.getElementById('profileRemoveConfirm');
    const btnConfirmRemove = document.getElementById('btnConfirmRemoveProfile');
    const btnCancelRemove = document.getElementById('btnCancelRemoveProfile');

    // ========== FUNÇÕES DE MENSAGEM ==========
    function clearMessage() {
        messageContainer.innerHTML = '';
    }

    function showMessage(type, text, timeout = 6000) {
        clearMessage();
        
        const typeConfig = {
            'success': {
                bg: 'rgba(34, 197, 94, 0.08)',
                border: 'rgba(34, 197, 94, 0.3)',
                color: '#22c55e',
                icon: '<circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/>'
            },
            'danger': {
                bg: 'rgba(239, 68, 68, 0.08)',
                border: 'rgba(239, 68, 68, 0.3)',
                color: '#ef4444',
                icon: '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>'
            },
            'warning': {
                bg: 'rgba(251, 191, 36, 0.08)',
                border: 'rgba(251, 191, 36, 0.3)',
                color: '#fbbf24',
                icon: '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>'
            }
        };

        const config = typeConfig[type] || typeConfig['success'];

        const div = document.createElement('div');
        div.style.cssText = `
            background: ${config.bg};
            border: 1px solid ${config.border};
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideDown 0.4s ease;
        `;

        div.innerHTML = `
            <svg viewBox="0 0 24 24" fill="none" stroke="${config.color}" stroke-width="2" style="width: 20px; height: 20px; flex-shrink: 0;">
                ${config.icon}
            </svg>
            <span style="font-size: 0.875rem; color: #d1d5db; line-height: 1.6; flex: 1;">${text}</span>
        `;

        messageContainer.appendChild(div);
        
        if (timeout > 0) {
            setTimeout(() => {
                if (messageContainer.contains(div)) {
                    div.style.animation = 'fadeOut 0.3s ease';
                    setTimeout(() => {
                        if (messageContainer.contains(div)) {
                            messageContainer.removeChild(div);
                        }
                    }, 300);
                }
            }, timeout);
        }
    }

    // ========== FUNÇÕES DO CROPPER ==========
    function initCropper() {
        if (cropper) { 
            cropper.destroy(); 
            cropper = null; 
        }
        
        cropper = new Cropper(img, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
            movable: true,
            zoomable: true,
            scalable: true,
            rotatable: false,
            responsive: true,
            ready() { 
                updatePreview(); 
            },
            crop() { 
                updatePreview(); 
            }
        });
    }

    async function loadExistingPhotoIntoEditor(url) {
        try {
            const res = await fetch(url, { credentials: 'same-origin' });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const blob = await res.blob();
            const objUrl = URL.createObjectURL(blob);
            
            img.onload = () => { 
                initCropper(); 
                canvasWrapper.classList.add('has-image');
            };
            img.src = objUrl;
            img.style.display = 'block';
            emptyMsg.style.display = 'none';
            btnUpload.disabled = false;
            return;
        } catch (e) {
            img.crossOrigin = 'anonymous';
            img.onload = () => { 
                initCropper(); 
                canvasWrapper.classList.add('has-image');
            };
            img.onerror = () => {
                img.style.display = 'none';
                emptyMsg.style.display = 'block';
                canvasWrapper.classList.remove('has-image');
            };
            img.src = url;
            img.style.display = 'block';
            emptyMsg.style.display = 'none';
            btnUpload.disabled = false;
        }
    }

    function updatePreview() {
        if (!cropper) return;
        
        const canvas = cropper.getCroppedCanvas({
            width: 800,
            height: 800,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        if (canvas) {
            previewImg.src = canvas.toDataURL('image/jpeg', 0.85);
            previewImg.style.display = 'block';
            previewEmpty.style.display = 'none';
        }
    }

    // ========== ANÁLISE NSFW ==========
    async function analyzeImageNSFW(file) {
        try {
            if (!window.NSFWAlert || !window.NSFWDetector) {
                console.warn('NSFW detection não disponível');
                btnUpload.disabled = false;
                return null;
            }

            NSFWAlert.showLoading('profileNsfwAlert', 'Analisando imagem...');
            
            const result = await NSFWDetector.analyze(file);
            nsfwAnalysisResult = result;
            
            NSFWAlert.show('profileNsfwAlert', result, {
                showClose: false,
                showDetails: false
            });

            if (result.isBlocked) {
                btnUpload.disabled = true;
                showMessage('danger', 'A imagem foi identificada como inapropriada. Se você acha que isso é um erro, contate o suporte.');
            } else {
                btnUpload.disabled = false;
                if (result.riskLevel === 'warning') {
                    showMessage('warning', 'Imagem aprovada com ressalvas. Verifique se está apropriada.', 8000);
                }
            }

            return result;
        } catch (error) {
            console.error('Erro na análise NSFW:', error);
            if (window.NSFWAlert) {
                NSFWAlert.showError('profileNsfwAlert', 'Erro ao analisar imagem. A imagem será permitida.');
            }
            btnUpload.disabled = false;
            return null;
        }
    }

    // ========== FILE INPUT CHANGE ==========
    fileInput.addEventListener('change', async function(e) {
        const f = e.target.files && e.target.files[0];
        if (!f) return;

        // Validações básicas
        if (!/image\/(png|jpeg|jpg)/.test(f.type)) {
            showMessage('danger', 'Formato inválido. Use PNG ou JPG.');
            this.value = '';
            return;
        }
        if (f.size > 5 * 1024 * 1024) {
            showMessage('danger', 'Arquivo muito grande. Máx 5MB.');
            this.value = '';
            return;
        }

        currentFile = f;
        clearMessage();
        if (window.NSFWAlert) {
            NSFWAlert.clear('profileNsfwAlert');
        }

        // Mostrar imagem no editor
        const url = URL.createObjectURL(f);
        img.src = url;
        img.style.display = 'block';
        emptyMsg.style.display = 'none';
        canvasWrapper.classList.add('has-image');

        if (cropper) {
            cropper.destroy();
            cropper = null;
        }

        cropper = new Cropper(img, {
            aspectRatio: 1,
            viewMode: 1,
            autoCropArea: 1,
            movable: true,
            zoomable: true,
            scalable: true,
            responsive: true,
            ready() {
                updatePreview();
            },
            crop() {
                updatePreview();
            }
        });

        // Desabilitar botão até análise completar
        btnUpload.disabled = true;

        // ANALISAR NSFW
        await analyzeImageNSFW(f);
    });

    // ========== UPLOAD ==========
    btnUpload.addEventListener('click', async function() {
        if (!cropper) {
            showMessage('danger', 'Nenhuma imagem selecionada.');
            return;
        }

        // Verificar se análise NSFW bloqueou
        if (nsfwAnalysisResult && nsfwAnalysisResult.isBlocked) {
            showMessage('danger', 'A imagem foi identificada como inapropriada. Se você acha que isso é um erro, contate o suporte.');
            return;
        }

        const salaId = salaIdInput.value;
        const canvas = cropper.getCroppedCanvas({
            width: 800,
            height: 800,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });
        
        if (!canvas) {
            showMessage('danger', 'Erro ao processar a imagem.');
            return;
        }

        const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.85));
        if (!blob) {
            showMessage('danger', 'Erro ao processar a imagem.');
            return;
        }
        
        const fd = new FormData();
        fd.append('_token', '{{ csrf_token() }}');
        fd.append('profile_photo', blob, 'profile.jpg');

        // Loading state
        btnUpload.classList.add('loading');
        btnUpload.disabled = true;
        const originalText = btnUpload.querySelector('span').textContent;
        btnUpload.querySelector('span').textContent = 'Salvando...';

        try {
            const res = await fetch(`/salas/${salaId}/profile-photo`, {
                method: 'POST',
                credentials: 'same-origin',
                body: fd,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (handleCommonHttpStatuses(res.status)) {
                btnUpload.classList.remove('loading');
                btnUpload.disabled = false;
                btnUpload.querySelector('span').textContent = originalText;
                return;
            }

            const parsed = await parseJsonSafe(res);
            if (parsed && parsed.__unexpected_text) {
                console.error('Resposta inesperada (não JSON):', parsed.text);
                showMessage('danger', 'Resposta inesperada do servidor. Verifique o console.');
                btnUpload.classList.remove('loading');
                btnUpload.disabled = false;
                btnUpload.querySelector('span').textContent = originalText;
                return;
            }

            const json = parsed;
            if (res.ok && json.success) {
                showMessage('success', 'Foto salva com sucesso!');
                setTimeout(() => {
                    closeProfileEditor();
                    location.reload();
                }, 1000);
            } else {
                showMessage('danger', json.message || 'Erro ao salvar foto.');
                btnUpload.classList.remove('loading');
                btnUpload.disabled = false;
                btnUpload.querySelector('span').textContent = originalText;
            }
        } catch (e) {
            console.error(e);
            showMessage('danger', 'Erro de rede ao enviar foto.');
            btnUpload.classList.remove('loading');
            btnUpload.disabled = false;
            btnUpload.querySelector('span').textContent = originalText;
        }
    });

    // ========== HELPERS ==========
    async function parseJsonSafe(res) {
        const ct = res.headers.get('content-type') || '';
        if (ct.indexOf('application/json') !== -1) {
            return await res.json();
        } else {
            const txt = await res.text();
            return { __unexpected_text: true, text: txt, status: res.status };
        }
    }

    function handleCommonHttpStatuses(status) {
        if (status === 419) {
            showMessage('danger', 'Sessão expirada (419). Recarregue a página e faça login novamente.');
            return true;
        }
        if (status === 401) {
            showMessage('danger', 'Usuário não autenticado (401). Faça login novamente.');
            return true;
        }
        if (status === 403) {
            showMessage('danger', 'Acesso negado (403). Você pode não ter permissão para essa ação.');
            return true;
        }
        if (status === 404) {
            showMessage('danger', 'Rota não encontrada (404). Verifique se a URL está correta.');
            return true;
        }
        return false;
    }

    // ========== REMOVE PHOTO ==========
    btnRemove.addEventListener('click', function() {
        clearMessage();
        removeConfirm.style.display = 'block';
    });

    btnCancelRemove.addEventListener('click', function() {
        removeConfirm.style.display = 'none';
    });

    btnConfirmRemove.addEventListener('click', async function() {
        const salaId = salaIdInput.value;
        btnConfirmRemove.disabled = true;
        btnCancelRemove.disabled = true;
        
        const originalText = btnConfirmRemove.textContent;
        btnConfirmRemove.textContent = 'Removendo...';
        
        try {
            const res = await fetch(`/salas/${salaId}/profile-photo`, {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (handleCommonHttpStatuses(res.status)) {
                btnConfirmRemove.disabled = false;
                btnCancelRemove.disabled = false;
                btnConfirmRemove.textContent = originalText;
                return;
            }

            const parsed = await parseJsonSafe(res);
            if (parsed && parsed.__unexpected_text) {
                console.error('Resposta inesperada (não JSON):', parsed.text);
                showMessage('danger', 'Resposta inesperada do servidor ao remover.');
                btnConfirmRemove.disabled = false;
                btnCancelRemove.disabled = false;
                btnConfirmRemove.textContent = originalText;
                return;
            }

            const json = parsed;
            if (res.ok && json.success) {
                showMessage('success', 'Foto removida com sucesso!');
                setTimeout(() => {
                    closeProfileEditor();
                    location.reload();
                }, 1000);
            } else {
                showMessage('danger', json.message || 'Erro ao remover foto.');
                btnConfirmRemove.disabled = false;
                btnCancelRemove.disabled = false;
                btnConfirmRemove.textContent = originalText;
            }
        } catch (e) {
            console.error(e);
            showMessage('danger', 'Erro de rede ao remover foto.');
            btnConfirmRemove.disabled = false;
            btnCancelRemove.disabled = false;
            btnConfirmRemove.textContent = originalText;
        }
    });

    // ========== ABRIR EDITOR ==========
    window.openProfileEditor = function(salaId, photoUrl = null, photoColor = null) {
    salaIdInput.value = salaId;
    fileInput.value = '';
    currentFile = null;
    nsfwAnalysisResult = null;
    
    if (cropper) { 
        cropper.destroy(); 
        cropper = null; 
    }
    
    clearMessage();
    
    if (window.NSFWAlert) {
        NSFWAlert.clear('profileNsfwAlert');
    }
    
    removeConfirm.style.display = 'none';
    btnUpload.disabled = true;
    canvasWrapper.classList.remove('has-image');

    if (photoUrl) {
        previewImg.src = photoUrl;
        previewImg.style.display = 'block';
        previewEmpty.style.display = 'none';
        loadExistingPhotoIntoEditor(photoUrl);
    } else {
        previewImg.style.display = 'none';
        previewEmpty.style.display = 'block';
        img.style.display = 'none';
        emptyMsg.style.display = 'block';
    }

    // FORÇAR ESTILOS VIA JAVASCRIPT
    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.right = '0';
    modal.style.bottom = '0';
    modal.style.zIndex = '10001';
    
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    console.log('✅ Profile Editor Modal aberto:', modal.style.display);
};

    // Alias para compatibilidade
    window.openProfilePhotoEditor = window.openProfileEditor;

    // ========== FECHAR EDITOR ==========
    window.closeProfileEditor = function() {
    // REMOVER ESTILOS INLINE
    modal.style.display = 'none';
    modal.style.removeProperty('alignItems');
    modal.style.removeProperty('justifyContent');
    
    modal.classList.remove('show');
    document.body.style.overflow = '';
    
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    
    clearMessage();
    removeConfirm.style.display = 'none';
    
    console.log('✅ Profile Editor Modal fechado');
};

    // Fechar ao pressionar ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            closeProfileEditor();
        }
    });

    console.log('✅ Profile Photo Editor inicializado com sucesso');
});
</script>

<!-- Adicionar animação de fadeOut no CSS -->
<style>
@keyframes fadeOut {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-10px);
    }
}
</style>