{{-- resources/views/partials/glb-viewer-modal.blade.php --}}

<style>
/* Estilos do Modal GLB */
.glb-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    z-index: 10001;
    animation: fadeIn 0.3s ease;
    overflow-y: auto;
}

.glb-modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.glb-modal-container {
    background: white;
    border-radius: 16px;
    max-width: 1400px;
    width: 100%;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.glb-modal-header {
    padding: 24px 30px;
    border-bottom: 2px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.glb-modal-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 20px;
    font-weight: 700;
    color: #1a202c;
}

.glb-modal-title svg {
    width: 24px;
    height: 24px;
    color: #667eea;
}

.glb-modal-close {
    background: #f3f4f6;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #6b7280;
    transition: all 0.2s;
}

.glb-modal-close:hover {
    background: #e5e7eb;
    color: #1a202c;
    transform: rotate(90deg);
}

.glb-modal-body {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 0;
    overflow: hidden;
    flex: 1;
}

.glb-viewer-section {
    position: relative;
    background: #1a202c;
    display: flex;
    flex-direction: column;
}

.glb-viewer-controls {
    padding: 16px 20px;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    gap: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    flex-wrap: wrap;
}

.glb-control-btn {
    padding: 8px 16px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    color: white;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}

.glb-control-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.glb-control-btn.active {
    background: #667eea;
    border-color: #667eea;
}

.glb-control-btn svg {
    width: 16px;
    height: 16px;
}

/* Controles de Escala */
.glb-scale-controls {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-left: auto;
    background: rgba(0, 0, 0, 0.4);
    padding: 4px;
    border-radius: 8px;
}

.glb-scale-btn {
    padding: 6px 12px;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 6px;
    color: white;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
}

.glb-scale-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: scale(1.1);
}

.glb-scale-btn:active {
    transform: scale(0.95);
}

.glb-scale-label {
    color: rgba(255, 255, 255, 0.7);
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.glb-viewer-container {
    flex: 1;
    position: relative;
    min-height: 500px;
}

.glb-viewer-loading {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: rgba(26, 32, 44, 0.9);
    z-index: 10;
}

.glb-viewer-loading.hidden {
    display: none;
}

.glb-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #667eea;
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.glb-loading-text {
    color: white;
    margin-top: 20px;
    font-size: 15px;
}

.glb-viewer-error {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fee2e2;
    border: 2px solid #dc2626;
    border-radius: 12px;
    padding: 30px;
    max-width: 80%;
    text-align: center;
    z-index: 11;
}

.glb-viewer-error.hidden {
    display: none;
}

.glb-error-icon {
    width: 48px;
    height: 48px;
    color: #dc2626;
    margin: 0 auto 12px;
}

.glb-error-title {
    font-size: 18px;
    font-weight: 700;
    color: #991b1b;
    margin-bottom: 8px;
}

.glb-error-message {
    font-size: 14px;
    color: #7f1d1d;
}

.glb-metadata-section {
    background: #f9fafb;
    border-left: 2px solid #e5e7eb;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}

.glb-metadata-header {
    padding: 20px;
    border-bottom: 2px solid #e5e7eb;
    background: white;
    position: sticky;
    top: 0;
    z-index: 5;
}

.glb-metadata-header h3 {
    font-size: 16px;
    color: #1a202c;
    margin-bottom: 8px;
    font-weight: 700;
}

.glb-security-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}

.glb-security-status.safe {
    background: #d1fae5;
    color: #065f46;
}

.glb-security-status.unsafe {
    background: #fee2e2;
    color: #991b1b;
}

.glb-security-status svg {
    width: 14px;
    height: 14px;
}

.glb-metadata-content {
    flex: 1;
    overflow-y: auto;
}

.glb-metadata-group {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.glb-metadata-group:last-child {
    border-bottom: none;
}

.glb-metadata-group-title {
    font-size: 13px;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
}

.glb-metadata-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f3f4f6;
}

.glb-metadata-item:last-child {
    border-bottom: none;
}

.glb-metadata-label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
}

.glb-metadata-value {
    font-size: 14px;
    color: #1a202c;
    font-weight: 600;
}

.glb-metadata-value.highlight {
    color: #667eea;
}

.glb-metadata-value.warning {
    color: #f59e0b;
}

.glb-metadata-value.danger {
    color: #dc2626;
}

.glb-check-icon {
    width: 16px;
    height: 16px;
}

.glb-check-icon.ok {
    color: #10b981;
}

.glb-check-icon.error {
    color: #ef4444;
}

.glb-approval-section {
    padding: 20px;
    background: white;
    border-top: 2px solid #e5e7eb;
}

.glb-approval-buttons {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.glb-btn-approve,
.glb-btn-reject {
    padding: 12px 20px;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.glb-btn-approve {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.glb-btn-approve:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.glb-btn-reject {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.glb-btn-reject:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.glb-btn-approve:disabled,
.glb-btn-reject:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

@media (max-width: 1200px) {
    .glb-modal-body {
        grid-template-columns: 1fr;
    }

    .glb-metadata-section {
        max-height: 400px;
        border-left: none;
        border-top: 2px solid #e5e7eb;
    }
}
</style>

<!-- Modal GLB Viewer -->
<div id="glbModal" class="glb-modal" onclick="closeGLBModal(event)">
    <div class="glb-modal-container" onclick="event.stopPropagation()">
        <div class="glb-modal-header">
            <div class="glb-modal-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Análise de Modelo 3D
            </div>
            <button class="glb-modal-close" onclick="closeGLBModal()">×</button>
        </div>

        <div class="glb-modal-body">
            <div class="glb-viewer-section">
                <div class="glb-viewer-controls">
                    <button class="glb-control-btn active" id="glbBtnAutoRotate">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Auto-Rotação
                    </button>
                    <button class="glb-control-btn" id="glbBtnReset">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                        </svg>
                        Resetar Câmera
                    </button>

                    <!-- Controles de Escala -->
                    <div class="glb-scale-controls">
                        <span class="glb-scale-label">Tamanho</span>
                        <button class="glb-scale-btn" id="glbBtnScaleDown" title="Diminuir">−</button>
                        <button class="glb-scale-btn" id="glbBtnScaleReset" title="Resetar tamanho">⟲</button>
                        <button class="glb-scale-btn" id="glbBtnScaleUp" title="Aumentar">+</button>
                    </div>
                </div>

                <div id="glbViewerContainer" class="glb-viewer-container"></div>

                <div id="glbViewerLoading" class="glb-viewer-loading">
                    <div class="glb-spinner"></div>
                    <div class="glb-loading-text">Carregando modelo 3D...</div>
                </div>

                <div id="glbViewerError" class="glb-viewer-error hidden">
                    <svg class="glb-error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="glb-error-title">Erro ao Carregar Modelo</div>
                    <div class="glb-error-message" id="glbErrorMessage"></div>
                </div>
            </div>

            <div class="glb-metadata-section">
                <div class="glb-metadata-header">
                    <h3>Análise de Segurança</h3>
                    <span class="glb-security-status" id="glbSecurityStatus">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Analisando...
                    </span>
                </div>

                <div id="glbMetadataContent" class="glb-metadata-content">
                    <!-- Metadados serão inseridos aqui via JavaScript -->
                </div>

                @if(auth()->user()->isStaff())
                <div class="glb-approval-section" id="glbApprovalSection" style="display: none;">
                    <div class="glb-approval-buttons">
                        <button class="glb-btn-approve" id="glbBtnApprove">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Aprovar Modelo
                        </button>
                        <button class="glb-btn-reject" id="glbBtnReject">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Rejeitar Modelo
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Sistema de Viewer GLB
(function() {
    let glbViewer = null;
    let currentAnexoId = null;

    window.openGLBModal = async function(anexoUrl, anexoNome, anexoId) {
        const modal = document.getElementById('glbModal');
        const container = document.getElementById('glbViewerContainer');
        const loadingEl = document.getElementById('glbViewerLoading');
        const errorEl = document.getElementById('glbViewerError');
        const errorMessageEl = document.getElementById('glbErrorMessage');
        const metadataEl = document.getElementById('glbMetadataContent');
        const approvalSection = document.getElementById('glbApprovalSection');

        currentAnexoId = anexoId;

        // Limpar estado anterior
        metadataEl.innerHTML = '';
        loadingEl.classList.remove('hidden');
        errorEl.classList.add('hidden');
        if (approvalSection) approvalSection.style.display = 'none';

        // Mostrar modal
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Criar viewer se não existir
        if (!glbViewer) {
            glbViewer = window.GLBViewer.createViewer('glbViewerContainer', null, {
                width: container.clientWidth,
                height: container.clientHeight || 500,
                autoRotate: true,
                showGrid: true,
                showAxes: true
            });

            // Configurar controles
            document.getElementById('glbBtnAutoRotate').addEventListener('click', function() {
                const isActive = glbViewer.toggleAutoRotate();
                this.classList.toggle('active', isActive);
            });

            document.getElementById('glbBtnReset').addEventListener('click', function() {
                glbViewer.resetCamera();
            });

            // Controles de escala
            document.getElementById('glbBtnScaleUp').addEventListener('click', function() {
                glbViewer.scaleUp();
            });

            document.getElementById('glbBtnScaleDown').addEventListener('click', function() {
                glbViewer.scaleDown();
            });

            document.getElementById('glbBtnScaleReset').addEventListener('click', function() {
                glbViewer.resetScale();
            });
        }

        // Carregar arquivo
        try {
            const response = await fetch(anexoUrl);
            if (!response.ok) throw new Error('Erro ao carregar arquivo');
            
            const blob = await response.blob();
            const file = new File([blob], anexoNome, { type: 'model/gltf-binary' });

            const result = await glbViewer.loadModel(file);

            loadingEl.classList.add('hidden');

            if (result.success) {
                displayMetadata(result.metadata);
                const security = glbViewer.checkSecurity(result.metadata);
                updateSecurityStatus(security);

                // Mostrar botões de aprovação apenas para staff
                @if(auth()->user()->isStaff())
                if (approvalSection) {
                    approvalSection.style.display = 'block';
                    document.getElementById('glbBtnApprove').disabled = !security.safe;
                }
                @endif
            } else {
                errorMessageEl.textContent = result.error;
                errorEl.classList.remove('hidden');
            }
        } catch (error) {
            loadingEl.classList.add('hidden');
            errorMessageEl.textContent = error.message;
            errorEl.classList.remove('hidden');
        }
    };

    window.closeGLBModal = function(event) {
        if (event && event.target.id !== 'glbModal') return;
        
        const modal = document.getElementById('glbModal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    };

    function displayMetadata(metadata) {
        const container = document.getElementById('glbMetadataContent');
        let html = '';

        // Informações do Arquivo
        html += `
            <div class="glb-metadata-group">
                <div class="glb-metadata-group-title">📄 Informações do Arquivo</div>
                <div class="glb-metadata-item">
                    <span class="glb-metadata-label">Nome</span>
                    <span class="glb-metadata-value">${metadata.fileName}</span>
                </div>
                <div class="glb-metadata-item">
                    <span class="glb-metadata-label">Tamanho</span>
                    <span class="glb-metadata-value ${metadata.securityChecks.fileSizeOk ? '' : 'danger'}">${metadata.fileSizeFormatted}</span>
                </div>
                <div class="glb-metadata-item">
                    <span class="glb-metadata-label">Versão GLB</span>
                    <span class="glb-metadata-value">${metadata.glbVersion}</span>
                </div>
                <div class="glb-metadata-item">
                    <span class="glb-metadata-label">Última Modificação</span>
                    <span class="glb-metadata-value">${metadata.lastModified}</span>
                </div>
            </div>
        `;

        // Estatísticas do Modelo
        html += `
            <div class="glb-metadata-group">
                <div class="glb-metadata-group-title">📊 Estatísticas do Modelo</div>
                <div class="glb-metadata-item">
                    <span class="glb-metadata-label">Meshes</span>
                    <span class="glb-metadata-value highlight">${metadata.meshCount}</span>
                </div>
                <div class="glb-metadata-item">
                    <span class="glb-metadata-label">Vértices</span>
                    <span class="glb-metadata-value ${metadata.securityChecks.vertexCountOk ? '' : 'danger'}">${metadata.vertexCount.toLocaleString()}</span>
                </div>
                <div class="glb-metadata-item">
                    <span class="glb-metadata-label">Faces</span>
                    <span class="glb-metadata-value ${metadata.securityChecks.faceCountOk ? '' : 'danger'}">${metadata.faceCount.toLocaleString()}</span>
                </div>
                <div class="glb-metadata-item">
                    <span class="glb-metadata-label">Materiais</span>
                    <span class="glb-metadata-value">${metadata.materialCount}</span>
                </div>
                <div class="glb-metadata-item">
                    <span class="glb-metadata-label">Texturas</span>
                    <span class="glb-metadata-value ${metadata.securityChecks.textureCountOk ? '' : 'danger'}">${metadata.textureCount}</span>
                </div>
                <div class="glb-metadata-item">
                    <span class="glb-metadata-label">Nós</span>
                    <span class="glb-metadata-value">${metadata.nodeCount}</span>
                </div>
            </div>
        `;

        // Recursos Avançados
        html += `
            <div class="glb-metadata-group">
                <div class="glb-metadata-group-title">🎨 Recursos</div>
                <div class="glb-metadata-item">
                    <span class="glb-metadata-label">Animações</span>
                    <span class="glb-metadata-value">${metadata.hasAnimations ? '✅ Sim (' + metadata.animationCount + ')' : '❌ Não'}</span>
                </div>
                <div class="glb-metadata-item">
                    <span class="glb-metadata-label">Skinning/Rigging</span>
                    <span class="glb-metadata-value">${metadata.hasSkinning ? '✅ Sim' : '❌ Não'}</span>
                </div>
                <div class="glb-metadata-item">
                    <span class="glb-metadata-label">Morph Targets</span>
                    <span class="glb-metadata-value">${metadata.hasMorphTargets ? '✅ Sim' : '❌ Não'}</span>
                </div>
            </div>
        `;

        // Verificações de Segurança
        html += `
            <div class="glb-metadata-group">
                <div class="glb-metadata-group-title">🔒 Verificações de Segurança</div>
                ${Object.entries(metadata.securityChecks).map(([key, value]) => `
                    <div class="glb-metadata-item">
                        <span class="glb-metadata-label">${getCheckLabel(key)}</span>
                        <svg class="glb-check-icon ${value ? 'ok' : 'error'}" fill="currentColor" viewBox="0 0 20 20">
                            ${value 
                                ? '<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>'
                                : '<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>'
                            }
                        </svg>
                    </div>
                `).join('')}
            </div>
        `;

        container.innerHTML = html;
    }

    function getCheckLabel(key) {
        const labels = {
            fileSizeOk: 'Tamanho de Arquivo',
            versionOk: 'Versão GLB',
            structureOk: 'Estrutura Válida',
            vertexCountOk: 'Contagem de Vértices',
            faceCountOk: 'Contagem de Faces',
            textureCountOk: 'Número de Texturas'
        };
        return labels[key] || key;
    }

    function updateSecurityStatus(security) {
        const statusEl = document.getElementById('glbSecurityStatus');
        if (security.safe) {
            statusEl.className = 'glb-security-status safe';
            statusEl.innerHTML = `
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Seguro
            `;
        } else {
            statusEl.className = 'glb-security-status unsafe';
            statusEl.innerHTML = `
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                Risco: ${security.reason}
            `;
        }
    }

    // Fechar com ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('glbModal');
            if (modal.classList.contains('active')) {
                closeGLBModal();
            }
        }
    });

    // Botões de aprovação (apenas para staff)
    @if(auth()->user()->isStaff())
    document.addEventListener('DOMContentLoaded', function() {
        const btnApprove = document.getElementById('glbBtnApprove');
        const btnReject = document.getElementById('glbBtnReject');

        if (btnApprove) {
            btnApprove.addEventListener('click', function() {
                if (confirm('Deseja aprovar este modelo 3D?')) {
                    // TODO: Implementar requisição AJAX para aprovação
                    alert('Modelo aprovado! (Implementar backend)');
                    closeGLBModal();
                }
            });
        }

        if (btnReject) {
            btnReject.addEventListener('click', function() {
                const reason = prompt('Motivo da rejeição:');
                if (reason) {
                    // TODO: Implementar requisição AJAX para rejeição
                    alert('Modelo rejeitado: ' + reason + ' (Implementar backend)');
                    closeGLBModal();
                }
            });
        }
    });
    @endif
})();
</script>