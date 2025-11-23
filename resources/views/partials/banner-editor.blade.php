<!-- resources/views/partials/banner-editor.blade.php -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<style>
/* ========== BANNER EDITOR STYLES ========== */
.banner-editor-modal {
    display: none;  /* Mantém hidden por padrão */
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 10000;
    animation: fadeIn 0.3s ease;
}

.banner-editor-modal.show {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.banner-editor-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(8px);
    cursor: pointer;
    z-index: 1;
}

.banner-editor-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 95%;
    max-width: 1200px;
    max-height: 90vh; /* ✅ IMPORTANTE - limita altura */
    z-index: 2;
}

/* Wrapper interno (anima) */
.banner-editor-wrapper {
    width: 100%;
    height: auto; /* ✅ MUDE DE 100% PARA auto */
    max-height: 90vh; /* ✅ ADICIONE ESTA LINHA */
    background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.95));
    border-radius: 24px;
    border: 1px solid rgba(34, 197, 94, 0.2);
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6);
    overflow: hidden; /* ✅ Mantém */
    animation: modalSlideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: flex;
    flex-direction: column;
}

/* ✅ ANIMAÇÃO LIMPA */
@keyframes modalSlideUp {
    from {
        opacity: 0;
        transform: translateY(50px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Body com scroll */
.banner-editor-body {
    padding: 2rem;
    overflow-y: auto; /* ✅ Mantém scroll */
    flex: 1 1 auto; /* ✅ IMPORTANTE - permite crescimento mas respeita max-height */
    min-height: 0; /* ✅ ESSENCIAL para scroll funcionar em flexbox */
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Header */
.banner-editor-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 2rem 2rem 1.5rem;
    border-bottom: 1px solid rgba(34, 197, 94, 0.15);
    background: linear-gradient(135deg, rgba(5, 46, 22, 0.3), transparent);
    position: relative;
    overflow: hidden;
}

.banner-editor-header::before {
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

.banner-editor-title-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    z-index: 2;
}

.banner-editor-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.1));
    border: 1px solid rgba(34, 197, 94, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.banner-editor-icon svg {
    width: 24px;
    height: 24px;
    stroke: var(--accent);
}

.banner-editor-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #fff;
    margin: 0;
    letter-spacing: 0.5px;
}

.banner-editor-subtitle {
    font-size: 0.875rem;
    color: var(--muted);
    margin: 0.25rem 0 0;
}

.banner-editor-close {
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

.banner-editor-close:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: #ef4444;
    transform: rotate(90deg);
}

.banner-editor-close svg {
    width: 18px;
    height: 18px;
    stroke: var(--muted);
    transition: stroke 0.2s;
}

.banner-editor-close:hover svg {
    stroke: #ef4444;
}

/* Body */
.banner-editor-body {
    padding: 2rem;
    overflow-y: auto;
    flex: 1;
}

.banner-editor-body::-webkit-scrollbar {
    width: 8px;
}

.banner-editor-body::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 4px;
}

.banner-editor-body::-webkit-scrollbar-thumb {
    background: rgba(34, 197, 94, 0.3);
    border-radius: 4px;
}

/* Alert Container */
.banner-alert-container {
    margin-bottom: 1.5rem;
}

.banner-info-card {
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

.banner-info-card svg {
    width: 20px;
    height: 20px;
    stroke: #3b82f6;
    flex-shrink: 0;
}

.banner-info-card span {
    font-size: 0.875rem;
    color: #d1d5db;
    line-height: 1.6;
}

.banner-info-card strong {
    color: #3b82f6;
    font-weight: 700;
}

/* Layout Grid */
.banner-editor-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

/* Canvas Section */
.banner-canvas-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.banner-canvas-wrapper {
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

.banner-canvas-wrapper:hover {
    border-color: rgba(34, 197, 94, 0.4);
}

.banner-canvas-wrapper.has-image {
    border-style: solid;
    border-color: rgba(34, 197, 94, 0.3);
    background: #000;
}

#bannerEditorImage {
    max-width: 100%;
    max-height: 500px;
    display: none;
}

.banner-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    color: var(--muted);
    padding: 3rem;
}

.banner-empty-state svg {
    width: 64px;
    height: 64px;
    stroke: rgba(34, 197, 94, 0.3);
    opacity: 0.6;
}

.banner-empty-state h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #9ca3af;
    margin: 0;
}

.banner-empty-state p {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
    text-align: center;
}

/* Controls Section */
.banner-controls-section {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.banner-control-card {
    background: rgba(17, 24, 39, 0.6);
    border: 1px solid rgba(55, 65, 81, 0.5);
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s;
}

.banner-control-card:hover {
    border-color: rgba(34, 197, 94, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

.banner-control-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.banner-control-header svg {
    width: 20px;
    height: 20px;
    stroke: var(--accent);
}

.banner-control-header h3 {
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
}

/* File Upload */
.banner-file-input-wrapper {
    position: relative;
}

.banner-file-input {
    display: none;
}

.banner-file-label {
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

.banner-file-label:hover {
    background: rgba(34, 197, 94, 0.15);
    border-color: var(--accent);
    transform: translateY(-2px);
}

.banner-file-label svg {
    width: 32px;
    height: 32px;
    stroke: var(--accent);
}

.banner-file-label-text {
    font-size: 0.875rem;
    font-weight: 600;
    color: #d1d5db;
}

.banner-file-label-hint {
    font-size: 0.75rem;
    color: var(--muted);
}

/* Preview */
.banner-preview-wrapper {
    width: 100%;
    height: 180px;
    background: rgba(17, 24, 39, 0.8);
    border: 1px solid rgba(55, 65, 81, 0.5);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

#bannerPreviewImg {
    max-width: 100%;
    max-height: 100%;
    display: none;
}

.banner-preview-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    color: var(--muted);
}

.banner-preview-empty svg {
    width: 32px;
    height: 32px;
    stroke: rgba(34, 197, 94, 0.3);
}

.banner-preview-empty span {
    font-size: 0.75rem;
}

/* Buttons */
.banner-btn {
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

.banner-btn svg {
    width: 18px;
    height: 18px;
    transition: transform 0.3s;
}

.banner-btn:hover svg {
    transform: scale(1.1);
}

.banner-btn-primary {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #052e16;
    box-shadow: 0 4px 14px rgba(34, 197, 94, 0.4);
}

.banner-btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(34, 197, 94, 0.5);
}

.banner-btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.banner-btn-danger {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.banner-btn-danger:hover {
    background: rgba(239, 68, 68, 0.25);
    border-color: #ef4444;
    transform: translateY(-2px);
}

.banner-btn-secondary {
    background: rgba(55, 65, 81, 0.6);
    color: #d1d5db;
    border: 1px solid rgba(55, 65, 81, 0.8);
}

.banner-btn-secondary:hover {
    background: rgba(55, 65, 81, 0.8);
    border-color: rgba(107, 114, 128, 0.8);
    transform: translateY(-2px);
}

/* Divider */
.banner-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(34, 197, 94, 0.2), transparent);
    margin: 1.5rem 0;
}

/* Remove Confirm */
.banner-remove-confirm {
    background: rgba(239, 68, 68, 0.08);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 12px;
    padding: 1rem;
    animation: slideDown 0.3s ease;
}

.banner-remove-confirm-text {
    font-size: 0.875rem;
    color: #f87171;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.banner-remove-confirm-text svg {
    width: 18px;
    height: 18px;
    stroke: #ef4444;
}

.banner-remove-actions {
    display: flex;
    gap: 0.5rem;
}

.banner-btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

/* Loading State */
.banner-btn.loading {
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
    border-radius: 8px;
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
</style>

<!-- Modal Structure -->
<div class="banner-editor-modal" id="modalBannerEditor">
    <div class="banner-editor-overlay" onclick="closeBannerEditor()"></div>
    
    <div class="banner-editor-container">
        <!-- Header -->
         <div class="banner-editor-wrapper">
        <div class="banner-editor-header">
            <div class="banner-editor-title-wrapper">
                <div class="banner-editor-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
                <div>
                    <h2 class="banner-editor-title">Editor de Banner</h2>
                    <p class="banner-editor-subtitle">Personalize o visual da sua sala</p>
                </div>
            </div>
            <button class="banner-editor-close" onclick="closeBannerEditor()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="banner-editor-body">
            <!-- Alerts -->
            <div class="banner-alert-container" id="bannerEditorMessageContainer"></div>
            <div id="bannerNsfwAlert"></div>

            <!-- Info Card -->
            <div class="banner-info-card">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <path d="M12 16v-4"/>
        <path d="M12 8h.01"/>
    </svg>
    <span>
        A imagem precisa ser <strong>horizontal</strong> (largura > altura). 
        Recomendado: proporção <strong>16:9</strong>. Máximo 8MB.
    </span>
</div>

            <!-- Grid Layout -->
            <div class="banner-editor-grid">
                <!-- Canvas Section -->
                <div class="banner-canvas-section">
                    <div class="banner-canvas-wrapper" id="bannerCanvasWrapper">
                        <img id="bannerEditorImage" alt="Banner"/>
                        <div class="banner-empty-state" id="bannerEditorEmpty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            <h4>Nenhuma Imagem Selecionada</h4>
                            <p>Faça upload de uma imagem para começar</p>
                        </div>
                    </div>
                </div>

                <!-- Controls Section -->
                <div class="banner-controls-section">
                    <!-- Upload Card -->
                    <div class="banner-control-card">
                        <div class="banner-control-header">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                            <h3>Upload de Imagem</h3>
                        </div>

                        <div class="banner-file-input-wrapper">
                            <input 
                                type="file" 
                                id="bannerFileInput" 
                                class="banner-file-input" 
                                accept="image/png,image/jpeg"
                            />
                            <label for="bannerFileInput" class="banner-file-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                <span class="banner-file-label-text">Escolher Imagem</span>
                                <span class="banner-file-label-hint">PNG ou JPG • Máx 8MB</span>
                            </label>
                        </div>

                        <div class="banner-divider"></div>

                        <!-- Preview -->
                        <div>
                            <div class="banner-control-header" style="margin-bottom: 0.75rem;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <h3>Pré-visualização</h3>
                            </div>
                            <div class="banner-preview-wrapper">
                                <img id="bannerPreviewImg" alt="Preview"/>
                                <div class="banner-preview-empty" id="bannerPreviewEmpty">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                    <span>Aguardando imagem</span>
                                </div>
                            </div>
                        </div>

                        <div class="banner-divider"></div>

                        <!-- Actions -->
                        <button id="btnUploadCropped" class="banner-btn banner-btn-primary" disabled>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            <span>Salvar Banner</span>
                        </button>

                        <button id="btnRemoveBanner" class="banner-btn banner-btn-danger">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                            <span>Remover Banner</span>
                        </button>

                        <!-- Remove Confirm -->
                        <div id="bannerRemoveConfirm" class="banner-remove-confirm" style="display: none;">
                            <div class="banner-remove-confirm-text">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
        <line x1="12" y1="9" x2="12" y2="13"/>
        <circle cx="12" cy="17" r="0.5" fill="currentColor"/>
    </svg>
    Confirma remoção do banner desta sala?
</div>
                            <div class="banner-remove-actions">
                                <button id="btnConfirmRemove" class="banner-btn banner-btn-danger banner-btn-sm">
                                    Confirmar
                                </button>
                                <button id="btnCancelRemove" class="banner-btn banner-btn-secondary banner-btn-sm">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
            </div>

            <!-- Hidden Input -->
            <input type="hidden" id="bannerSalaId" value="" />
        </div>
    </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let cropper = null;
    let currentFile = null;
    let nsfwAnalysisResult = null;
    
    const modal = document.getElementById('modalBannerEditor');
    const img = document.getElementById('bannerEditorImage');
    const emptyMsg = document.getElementById('bannerEditorEmpty');
    const canvasWrapper = document.getElementById('bannerCanvasWrapper');
    const fileInput = document.getElementById('bannerFileInput');
    const previewImg = document.getElementById('bannerPreviewImg');
    const previewEmpty = document.getElementById('bannerPreviewEmpty');
    const btnUpload = document.getElementById('btnUploadCropped');
    const btnRemove = document.getElementById('btnRemoveBanner');
    const bannerIdInput = document.getElementById('bannerSalaId');
    const messageContainer = document.getElementById('bannerEditorMessageContainer');
    const removeConfirm = document.getElementById('bannerRemoveConfirm');
    const btnConfirmRemove = document.getElementById('btnConfirmRemove');
    const btnCancelRemove = document.getElementById('btnCancelRemove');

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
            aspectRatio: 16 / 9,
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

    async function loadExistingBannerIntoEditor(url) {
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
            width: 1600,
            height: 900,
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

            NSFWAlert.showLoading('bannerNsfwAlert', 'Analisando imagem...');
            
            const result = await NSFWDetector.analyze(file);
            nsfwAnalysisResult = result;
            
            NSFWAlert.show('bannerNsfwAlert', result, {
                showClose: false,
                showDetails: false
            });

            if (result.isBlocked) {
                btnUpload.disabled = true;
                showMessage('danger', 'Esta imagem foi bloqueada por conter conteúdo inapropriado.');
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
                NSFWAlert.showError('bannerNsfwAlert', 'Erro ao analisar imagem. A imagem será permitida.');
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
        if (f.size > 8 * 1024 * 1024) {
            showMessage('danger', 'Arquivo muito grande. Máx 8MB.');
            this.value = '';
            return;
        }

        currentFile = f;
        clearMessage();
        if (window.NSFWAlert) {
            NSFWAlert.clear('bannerNsfwAlert');
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
            aspectRatio: 16 / 9,
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
            showMessage('danger', 'Esta imagem foi bloqueada. Escolha outra imagem apropriada.');
            return;
        }

        const salaId = bannerIdInput.value;
        const canvas = cropper.getCroppedCanvas({
            width: 1600,
            height: 900,
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
        fd.append('banner', blob, 'banner.jpg');

        // Loading state
        btnUpload.classList.add('loading');
        btnUpload.disabled = true;
        const originalText = btnUpload.querySelector('span').textContent;
        btnUpload.querySelector('span').textContent = 'Salvando...';

        try {
            const res = await fetch(`/salas/${salaId}/banner`, {
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
                showMessage('success', 'Banner salvo com sucesso!');
                setTimeout(() => {
                    closeBannerEditor();
                    location.reload();
                }, 1000);
            } else {
                showMessage('danger', json.message || 'Erro ao salvar banner.');
                btnUpload.classList.remove('loading');
                btnUpload.disabled = false;
                btnUpload.querySelector('span').textContent = originalText;
            }
        } catch (e) {
            console.error(e);
            showMessage('danger', 'Erro de rede ao enviar banner.');
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

    // ========== REMOVE BANNER ==========
    btnRemove.addEventListener('click', function() {
        clearMessage();
        removeConfirm.style.display = 'block';
    });

    btnCancelRemove.addEventListener('click', function() {
        removeConfirm.style.display = 'none';
    });

    btnConfirmRemove.addEventListener('click', async function() {
        const salaId = bannerIdInput.value;
        btnConfirmRemove.disabled = true;
        btnCancelRemove.disabled = true;
        
        const originalText = btnConfirmRemove.textContent;
        btnConfirmRemove.textContent = 'Removendo...';
        
        try {
            const res = await fetch(`/salas/${salaId}/banner`, {
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
                showMessage('success', 'Banner removido com sucesso!');
                setTimeout(() => {
                    closeBannerEditor();
                    location.reload();
                }, 1000);
            } else {
                showMessage('danger', json.message || 'Erro ao remover banner.');
                btnConfirmRemove.disabled = false;
                btnCancelRemove.disabled = false;
                btnConfirmRemove.textContent = originalText;
            }
        } catch (e) {
            console.error(e);
            showMessage('danger', 'Erro de rede ao remover banner.');
            btnConfirmRemove.disabled = false;
            btnCancelRemove.disabled = false;
            btnConfirmRemove.textContent = originalText;
        }
    });



    // ========== ABRIR EDITOR ==========
    window.openBannerEditor = function(salaId, bannerUrl = null, bannerColor = null) {
        bannerIdInput.value = salaId;
        fileInput.value = '';
        currentFile = null;
        nsfwAnalysisResult = null;
        
        if (cropper) { 
            cropper.destroy(); 
            cropper = null; 
        }
        
        clearMessage();
        
        if (window.NSFWAlert) {
            NSFWAlert.clear('bannerNsfwAlert');
        }
        
        removeConfirm.style.display = 'none';
        btnUpload.disabled = true;
        canvasWrapper.classList.remove('has-image');

        if (bannerUrl) {
            previewImg.src = bannerUrl;
            previewImg.style.display = 'block';
            previewEmpty.style.display = 'none';
            loadExistingBannerIntoEditor(bannerUrl);
        } else {
            previewImg.style.display = 'none';
            previewEmpty.style.display = 'block';
            img.style.display = 'none';
            emptyMsg.style.display = 'block';
        }

        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    // ========== FECHAR EDITOR ==========
    window.closeBannerEditor = function() {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        clearMessage();
        removeConfirm.style.display = 'none';
    };

    // Fechar ao pressionar ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            closeBannerEditor();
        }
    });

    console.log('✅ Banner Editor inicializado com sucesso');
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