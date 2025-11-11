{{-- Salvar em: resources/views/partials/invite-links-manager.blade.php --}}

<!-- Modal de Gerenciamento de Links de Convite -->
<div class="modal fade" id="inviteLinksModal" tabindex="-1" aria-labelledby="inviteLinksModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inviteLinksModalLabel">
                    <i class="fas fa-link me-2"></i>Gerenciar Links de Convite
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <!-- Formulário para criar novo link -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-plus-circle me-2"></i>Criar Novo Link
                    </div>
                    <div class="card-body">
                        <form id="createInviteLinkForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Validade</label>
                                    <select class="form-select" name="validade" required>
                                        <option value="1h">1 hora</option>
                                        <option value="1d" selected>1 dia</option>
                                        <option value="1w">1 semana</option>
                                        <option value="1m">1 mês</option>
                                        <option value="never">Nunca expira</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Máximo de Usos</label>
                                    <select class="form-select" name="max_usos">
                                        <option value="">Ilimitado</option>
                                        <option value="1">1 uso</option>
                                        <option value="5">5 usos</option>
                                        <option value="10">10 usos</option>
                                        <option value="25">25 usos</option>
                                        <option value="50">50 usos</option>
                                        <option value="100">100 usos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-magic me-2"></i>Gerar Link de Convite
                                </button>
                            </div>
                        </form>

                        <div id="createLinkAlert" class="alert d-none mt-3" role="alert"></div>
                    </div>
                </div>

                <!-- Lista de links ativos -->
                <div class="card">
                    <div class="card-header bg-light">
                        <i class="fas fa-list me-2"></i>Links Ativos
                        <button type="button" class="btn btn-sm btn-outline-secondary float-end" onclick="window.loadInviteLinks()">
                            <i class="fas fa-sync-alt"></i> Atualizar
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="inviteLinksContainer">
                            <div class="text-center text-muted py-3">
                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                                Carregando links...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .invite-link-item {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 12px;
        transition: all 0.2s;
        background: white;
    }

    .invite-link-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .invite-link-url {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 10px 12px;
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        color: #495057;
        word-break: break-all;
        margin-bottom: 10px;
    }

    .invite-link-info {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .invite-link-info-item {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .invite-link-actions {
        display: flex;
        gap: 8px;
    }

    .btn-copy-link {
        flex: 1;
    }

    .btn-revoke-link {
        flex: 0 0 auto;
    }

    .no-links-message {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .no-links-message i {
        font-size: 3rem;
        margin-bottom: 16px;
        color: #dee2e6;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('inviteLinksModal');
        const createForm = document.getElementById('createInviteLinkForm');
        const createAlert = document.getElementById('createLinkAlert');
        const linksContainer = document.getElementById('inviteLinksContainer');
        const salaId = "{{ $sala->id }}";

        console.log('[Invite Links] Inicializando com sala ID:', salaId);

        function getCsrf() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        function showAlert(element, type, message) {
            element.className = `alert alert-${type}`;
            element.textContent = message;
            element.classList.remove('d-none');
            setTimeout(() => element.classList.add('d-none'), 5000);
        }

        // Carregar links quando o modal abre
        modal.addEventListener('show.bs.modal', function() {
            console.log('[Invite Links] Modal aberto, carregando links...');
            window.loadInviteLinks();
        });

        // Criar novo link
        createForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('[Invite Links] Criando novo link...');

            const formData = new FormData(createForm);
            const data = {
                validade: formData.get('validade'),
                max_usos: formData.get('max_usos') ? parseInt(formData.get('max_usos')) : null
            };

            console.log('[Invite Links] Dados do link:', data);

            try {
                const response = await fetch(`/salas/${salaId}/links-convite`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrf(),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                console.log('[Invite Links] Resposta da criação:', result);

                if (result.success) {
                    showAlert(createAlert, 'success', result.message);
                    createForm.reset();
                    // RECARREGAR LINKS IMEDIATAMENTE
                    await window.loadInviteLinks();
                } else {
                    showAlert(createAlert, 'danger', result.message);
                }
            } catch (error) {
                console.error('[Invite Links] Erro ao criar:', error);
                showAlert(createAlert, 'danger', 'Erro ao criar link de convite.');
            }
        });

        // Carregar lista de links
        window.loadInviteLinks = async function() {
            console.log('[Invite Links] Carregando links da sala:', salaId);

            linksContainer.innerHTML = `
            <div class="text-center text-muted py-3">
                <div class="spinner-border spinner-border-sm me-2" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
                Carregando links...
            </div>
        `;

            try {
                const url = `/salas/${salaId}/links-convite`;
                console.log('[Invite Links] Fazendo requisição para:', url);

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrf()
                    }
                });

                console.log('[Invite Links] Status da resposta:', response.status);

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('[Invite Links] Erro HTTP:', response.status, errorText);
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }

                const result = await response.json();
                console.log('[Invite Links] Dados recebidos:', result);

                if (result.success) {
                    if (result.links && result.links.length > 0) {
                        console.log('[Invite Links] Total de links:', result.links.length);
                        renderLinks(result.links);
                    } else {
                        console.log('[Invite Links] Nenhum link encontrado');
                        renderNoLinks();
                    }
                } else {
                    console.error('[Invite Links] Resposta de erro:', result.message);
                    renderError(result.message || 'Erro ao carregar links.');
                }
            } catch (error) {
                console.error('[Invite Links] Erro ao carregar:', error);
                renderError('Erro ao carregar links: ' + error.message);
            }
        };

        // Renderizar lista de links
        function renderLinks(links) {
            console.log('[Invite Links] Renderizando', links.length, 'links');

            linksContainer.innerHTML = links.map(link => `
            <div class="invite-link-item" data-link-id="${link.id}">
                <div class="invite-link-url" id="link-${link.id}">
                    ${link.url}
                </div>
                
                <div class="invite-link-info">
                    <div class="invite-link-info-item">
                        <i class="fas fa-user"></i>
                        <span>Criado por: <strong>${link.criador}</strong></span>
                    </div>
                    <div class="invite-link-info-item">
                        <i class="fas fa-clock"></i>
                        <span>${link.validade}</span>
                    </div>
                    <div class="invite-link-info-item">
                        <i class="fas fa-chart-bar"></i>
                        <span>
                            ${link.usos_atual}${link.max_usos ? ' / ' + link.max_usos : ''} usos
                        </span>
                    </div>
                    ${!link.esta_valido ? '<span class="badge bg-danger">Inválido</span>' : '<span class="badge bg-success">Ativo</span>'}
                </div>

                <div class="invite-link-actions">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-copy-link" onclick="window.copyLink('link-${link.id}')">
                        <i class="fas fa-copy me-1"></i>Copiar Link
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-revoke-link" onclick="window.revokeLink(${link.id})">
                        <i class="fas fa-trash me-1"></i>Revogar
                    </button>
                </div>
            </div>
        `).join('');
        }

        // Renderizar mensagem quando não há links
        function renderNoLinks() {
            console.log('[Invite Links] Mostrando mensagem de "sem links"');
            linksContainer.innerHTML = `
            <div class="no-links-message">
                <i class="fas fa-link-slash"></i>
                <p class="mb-0">Nenhum link de convite ativo no momento.</p>
                <small class="text-muted">Crie um novo link usando o formulário acima.</small>
            </div>
        `;
        }

        // Renderizar mensagem de erro
        function renderError(message) {
            console.log('[Invite Links] Mostrando erro:', message);
            linksContainer.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
            </div>
        `;
        }

        // Copiar link - MÉTODO DEFINITIVO QUE FUNCIONA EM HTTP
        window.copyLink = async function(elementId) {
    const element = document.getElementById(elementId);
    if (!element) {
        console.error('[Invite Links] Elemento não encontrado:', elementId);
        alert('Erro: elemento não encontrado');
        return;
    }

    const text = (element.innerText || element.textContent || '').trim();
    console.log('[Invite Links] Copiando texto:', text);

    const linkItem = element.closest('.invite-link-item');
    const btn = linkItem ? linkItem.querySelector('.btn-copy-link') : null;

    function showSuccess() {
        if (!btn) return;
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Copiado!';
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-success');
        btn.disabled = true;
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-primary');
            btn.disabled = false;
        }, 2000);
    }
    function showError() {
        if (!btn) return;
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-times me-1"></i>Erro';
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-danger');
        setTimeout(() => {
            btn.innerHTML = original;
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-outline-primary');
        }, 2000);
    }

    // 1) Tenta Clipboard API (HTTPS / localhost)
    if (navigator.clipboard && navigator.clipboard.writeText) {
        try {
            await navigator.clipboard.writeText(text);
            console.log('[Invite Links] ✅ Copiado com Clipboard API!');
            showSuccess();
            return;
        } catch (err) {
            console.warn('[Invite Links] Clipboard API falhou:', err);
            // continua para fallback
        }
    }

    // 2) Fallback que funciona mesmo com focus trap do modal: anexar dentro do modal ou do próprio item
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.setAttribute('readonly', '');
    // colocar fora da visualização, MAS dentro do modal/linkItem para respeitar focus trap
    textarea.style.position = 'absolute';
    textarea.style.left = '-9999px';
    textarea.style.top = '0';
    textarea.style.width = '1px';
    textarea.style.height = '1px';
    textarea.style.opacity = '0'; // invisível
    textarea.setAttribute('aria-hidden', 'true');

    // container preferencial: o próprio invite-link-item (fica dentro do modal)
    const container = linkItem || document.getElementById('inviteLinksModal') || document.body;
    container.appendChild(textarea);

    // garantir foco/seleção
    textarea.focus();
    textarea.select();
    textarea.setSelectionRange(0, textarea.value.length);

    let success = false;
    try {
        success = document.execCommand('copy');
        console.log('[Invite Links] execCommand result:', success);
    } catch (err) {
        console.error('[Invite Links] Erro no execCommand:', err);
        success = false;
    } finally {
        // remover rapidamente
        setTimeout(() => {
            try { container.removeChild(textarea); } catch (e) {}
        }, 50);
    }

    if (success) {
        console.log('[Invite Links] ✅ Copiado com fallback execCommand!');
        showSuccess();
    } else {
        console.error('[Invite Links] ❌ Falha ao copiar com fallback.');
        showError();
        // fallback final: prompt para o usuário copiar manualmente
        setTimeout(() => prompt('Copie manualmente (Ctrl+C):', text), 100);
    }
};

        // Função de fallback SIMPLIFICADA e FUNCIONAL
        function fallbackCopy(text) {
            let success = false;

            const textarea = document.createElement('textarea');
            textarea.value = text;

            // Estilo mínimo, visível mas praticamente invisível ao usuário
            textarea.style.position = 'fixed';
            textarea.style.top = '0';
            textarea.style.left = '0';
            textarea.style.width = '1px';
            textarea.style.height = '1px';
            textarea.style.padding = '0';
            textarea.style.border = 'none';
            textarea.style.outline = 'none';
            textarea.style.boxShadow = 'none';
            textarea.style.background = 'transparent';
            textarea.setAttribute('aria-hidden', 'true');

            document.body.appendChild(textarea);

            try {
                // assegurar foco e seleção antes de copiar
                textarea.focus();
                textarea.select();
                textarea.setSelectionRange(0, textarea.value.length);

                success = document.execCommand('copy');
                console.log('[Invite Links] execCommand result:', success);

                if (!success) {
                    console.error('[Invite Links] ❌ execCommand retornou false');
                } else {
                    console.log('[Invite Links] ✅ Copiado com execCommand!');
                }
            } catch (err) {
                console.error('[Invite Links] ❌ Erro no execCommand:', err);
                success = false;
            } finally {
                // remover do DOM independente do resultado
                document.body.removeChild(textarea);
            }

            return success;
        }

        // Revogar link
        window.revokeLink = async function(linkId) {
            console.log('[Invite Links] Revogando link:', linkId);

            if (!confirm('Tem certeza que deseja revogar este link? Esta ação não pode ser desfeita.')) {
                return;
            }

            try {
                const response = await fetch(`/salas/${salaId}/links-convite/${linkId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': getCsrf(),
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();
                console.log('[Invite Links] Resposta da revogação:', result);

                if (result.success) {
                    await window.loadInviteLinks();
                } else {
                    alert(result.message || 'Erro ao revogar link.');
                }
            } catch (error) {
                console.error('[Invite Links] Erro ao revogar:', error);
                alert('Erro ao revogar link.');
            }
        };
    });
</script>