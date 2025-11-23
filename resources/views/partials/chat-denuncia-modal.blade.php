{{-- ========================================
     MODAL DE DENÚNCIA DE USUÁRIO (CHAT)
     ======================================== --}}

<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="reportForm" enctype="multipart/form-data">
                @csrf
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="reportModalLabel">
                        <i class="fas fa-flag me-2"></i>
                        Denunciar Usuário
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                    <!-- Alerta de informação -->
                    <div class="alert alert-warning d-flex align-items-start">
                        <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                        <div>
                            <strong>Atenção!</strong> Denúncias falsas podem resultar em punições à sua conta.
                            Certifique-se de que está denunciando um comportamento que realmente viola nossas regras.
                        </div>
                    </div>

                    <!-- Campos hidden -->
                    <input type="hidden" id="report_sala_id" name="sala_id" value="{{ $sala->id ?? '' }}">
                    <input type="hidden" id="report_usuario_denunciado_id" name="usuario_denunciado_id">

                    <!-- Tipo de denúncia -->
                    <div class="mb-3">
                        <label for="report_tipo" class="form-label">
                            <i class="fas fa-list me-1"></i>
                            Tipo de Denúncia <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="report_tipo" name="tipo_denuncia" required>
                            <option value="">Selecione...</option>
                            <option value="spam">🔴 Spam / Flood</option>
                            <option value="abuso">⚠️ Abuso Verbal / Ofensas</option>
                            <option value="assedio">🚫 Assédio</option>
                            <option value="sexual">🔞 Conteúdo Sexual Inapropriado</option>
                            <option value="outro">❓ Outro</option>
                        </select>
                        <small class="form-text text-muted">
                            Escolha a categoria que melhor descreve o problema
                        </small>
                    </div>

                    <!-- Descrição -->
                    <div class="mb-3">
                        <label for="report_descricao" class="form-label">
                            <i class="fas fa-comment-dots me-1"></i>
                            Descrição Detalhada <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" 
                                  id="report_descricao" 
                                  name="descricao" 
                                  rows="4" 
                                  minlength="20" 
                                  maxlength="2000" 
                                  required 
                                  placeholder="Descreva detalhadamente o que aconteceu..."></textarea>
                        <small class="form-text text-muted">
                            Mínimo 20 caracteres. Seja específico e objetivo.
                        </small>
                        <div class="mt-1">
                            <span id="report_char_count" class="badge bg-secondary">0 / 2000</span>
                        </div>
                    </div>

                    <!-- Mensagens selecionadas -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-check-square me-1"></i>
                            Mensagens Selecionadas
                        </label>
                        <div id="selectedMessagesContainer" class="border rounded p-3 bg-light">
                            <p class="text-muted mb-0">Nenhuma mensagem selecionada</p>
                        </div>
                        <small class="form-text text-muted">
                            Você pode selecionar mensagens usando o menu de três pontos em cada mensagem do chat
                        </small>
                    </div>

                    <!-- Anexos (evidências) -->
                    <div class="mb-3">
                        <label for="report_anexos" class="form-label">
                            <i class="fas fa-paperclip me-1"></i>
                            Anexar Evidências (Opcional)
                        </label>
                        <input type="file" 
                               class="form-control" 
                               id="report_anexos" 
                               name="anexos[]" 
                               multiple 
                               accept="image/*,.pdf">
                        <small class="form-text text-muted">
                            Máximo 5 arquivos. Formatos: JPG, PNG, GIF, PDF. Máximo 10MB por arquivo.
                        </small>
                        
                        <!-- Preview de anexos -->
                        <div id="reportAttachmentsPreview" class="mt-2 d-none">
                            <!-- Previews serão inseridos aqui via JS -->
                        </div>
                    </div>

                    <!-- Aviso final -->
                    <div class="alert alert-info d-flex align-items-start mb-0">
                        <i class="fas fa-info-circle me-2 mt-1"></i>
                        <div>
                            <strong>Como funciona:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Sua denúncia será analisada pela equipe de moderação</li>
                                <li>Você receberá atualizações por email e notificações no sistema</li>
                                <li>Mensagens e anexos enviados ficam registrados no ticket</li>
                                <li>O processo pode levar até 48 horas úteis</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger" id="reportSubmitBtn">
                        <i class="fas fa-paper-plane me-1"></i> Enviar Denúncia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================== 
     SCRIPT DO MODAL DE DENÚNCIA
     ======================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportForm = document.getElementById('reportForm');
    const reportDescricao = document.getElementById('report_descricao');
    const reportCharCount = document.getElementById('report_char_count');
    const reportAnexos = document.getElementById('report_anexos');
    const reportAttachmentsPreview = document.getElementById('reportAttachmentsPreview');
    const reportSubmitBtn = document.getElementById('reportSubmitBtn');

    // Contador de caracteres
    if (reportDescricao) {
        reportDescricao.addEventListener('input', function() {
            const count = this.value.length;
            reportCharCount.textContent = `${count} / 2000`;
            
            if (count < 20) {
                reportCharCount.classList.remove('bg-success', 'bg-warning');
                reportCharCount.classList.add('bg-danger');
            } else if (count < 100) {
                reportCharCount.classList.remove('bg-danger', 'bg-success');
                reportCharCount.classList.add('bg-warning');
            } else {
                reportCharCount.classList.remove('bg-danger', 'bg-warning');
                reportCharCount.classList.add('bg-success');
            }
        });
    }

    // Preview de anexos
    let reportAttachments = [];
    
    if (reportAnexos) {
        reportAnexos.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            files.forEach(file => {
                if (reportAttachments.length >= 5) {
                    alert('Máximo de 5 anexos');
                    return;
                }

                if (file.size > 10 * 1024 * 1024) {
                    alert(`Arquivo ${file.name} muito grande (máx 10MB)`);
                    return;
                }

                reportAttachments.push(file);
            });

            renderReportAttachmentsPreview();
        });
    }

    function renderReportAttachmentsPreview() {
        if (reportAttachments.length === 0) {
            reportAttachmentsPreview.classList.add('d-none');
            return;
        }

        reportAttachmentsPreview.classList.remove('d-none');
        reportAttachmentsPreview.innerHTML = `
            <div class="d-flex flex-wrap gap-2">
                ${reportAttachments.map((file, index) => `
                    <div class="position-relative border rounded p-2" style="width: 100px;">
                        <div class="text-center">
                            <i class="fas fa-file fa-2x text-muted"></i>
                            <div class="small text-truncate mt-1">${file.name}</div>
                            <div class="x-small text-muted">${formatFileSize(file.size)}</div>
                        </div>
                        <button type="button" 
                                class="btn btn-sm btn-danger position-absolute top-0 end-0 rounded-circle" 
                                style="width: 20px; height: 20px; padding: 0; font-size: 10px;"
                                onclick="removeReportAttachment(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `).join('')}
            </div>
        `;
    }

    window.removeReportAttachment = function(index) {
        reportAttachments.splice(index, 1);
        renderReportAttachmentsPreview();
    };

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    // Submit do formulário
    if (reportForm) {
        reportForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validações
            const tipo = document.getElementById('report_tipo').value;
            const descricao = reportDescricao.value.trim();
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

            // Adicionar mensagens selecionadas
            if (window.chatSystem && window.chatSystem.selectedMessages.size > 0) {
                const selectedArray = Array.from(window.chatSystem.selectedMessages);
                selectedArray.forEach((msgId, index) => {
                    formData.append(`mensagens_selecionadas[${index}]`, msgId);
                });
            }

            // Adicionar anexos
            reportAttachments.forEach((file, index) => {
                formData.append(`anexos[${index}]`, file);
            });

            // Desabilitar botão
            reportSubmitBtn.disabled = true;
            reportSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Enviando...';

            try {
                const response = await fetch('/chat/denunciar-usuario', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Sucesso
                    alert(`Denúncia enviada com sucesso!\n\nNúmero do ticket: ${data.numero_ticket}\n\nVocê pode acompanhar o status em Sistema de Suporte.`);
                    
                    // Fechar modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
                    if (modal) modal.hide();

                    // Limpar formulário
                    reportForm.reset();
                    reportAttachments = [];
                    renderReportAttachmentsPreview();
                    
                    // Limpar mensagens selecionadas
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
                console.error('[Report] Erro:', error);
                alert('Erro ao enviar denúncia: ' + error.message);
            } finally {
                reportSubmitBtn.disabled = false;
                reportSubmitBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Enviar Denúncia';
            }
        });
    }

    // Limpar ao fechar modal
    const reportModal = document.getElementById('reportModal');
    if (reportModal) {
        reportModal.addEventListener('hidden.bs.modal', function() {
            reportForm.reset();
            reportAttachments = [];
            renderReportAttachmentsPreview();
            reportCharCount.textContent = '0 / 2000';
            reportCharCount.className = 'badge bg-secondary';
        });
    }
});
</script>

<style>
/* Estilos adicionais para o modal */
#selectedMessagesContainer .selected-message-preview {
    padding: 8px;
    background: white;
    border-left: 3px solid #0d6efd;
    margin-bottom: 4px;
    border-radius: 4px;
}

#reportAttachmentsPreview .x-small {
    font-size: 0.7rem;
}

.modal-body {
    max-height: calc(100vh - 200px);
    overflow-y: auto;
}
</style>