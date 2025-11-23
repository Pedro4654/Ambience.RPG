{{-- resources/views/partials/invite-links-manager.blade.php --}}

<!-- Modal de Gerenciamento de Links de Convite -->
<div class="modal fade" id="inviteLinksModal" tabindex="-1" aria-labelledby="inviteLinksModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: linear-gradient(145deg, rgba(31, 42, 51, 0.98), rgba(20, 28, 35, 0.95)); border: 1px solid var(--border-subtle); border-radius: 20px;">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-subtle);">
                <h5 class="modal-title" id="inviteLinksModalLabel" style="color: #fff; font-weight: 700;">
                    <i class="fa-solid fa-link me-2"></i> Gerenciar Links de Convite
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <!-- Formulário para criar novo link -->
                <div class="card mb-4" style="background: rgba(17, 24, 39, 0.6); border: 1px solid rgba(55, 65, 81, 0.5); border-radius: 16px;">
                    <div class="card-body">
                        <h6 class="mb-3" style="color: var(--accent); font-weight: 600;">
                            <i class="fa-solid fa-plus-circle me-2"></i> Criar Novo Link
                        </h6>
                        <form id="createInviteLinkForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" style="color: var(--text-secondary); font-weight: 600;">Validade</label>
                                    <select class="form-select" name="validade" required
                                            style="background: rgba(17, 24, 39, 0.8); border: 2px solid rgba(55, 65, 81, 0.8); border-radius: 12px; color: #f9fafb; padding: 0.875rem 1rem;">
                                        <option value="1h">1 hora</option>
                                        <option value="1d" selected>1 dia</option>
                                        <option value="1w">1 semana</option>
                                        <option value="1m">1 mês</option>
                                        <option value="never">Nunca expira</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" style="color: var(--text-secondary); font-weight: 600;">Máximo de Usos</label>
                                    <select class="form-select" name="max_usos"
                                            style="background: rgba(17, 24, 39, 0.8); border: 2px solid rgba(55, 65, 81, 0.8); border-radius: 12px; color: #f9fafb; padding: 0.875rem 1rem;">
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
                            <button type="submit" class="btn-action btn-primary mt-3 w-100">
                                <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Gerar Link de Convite
                            </button>
                        </form>
                        <div id="createLinkAlert" class="alert d-none mt-3" role="alert"></div>
                    </div>
                </div>

                <!-- Lista de links ativos -->
                <div class="card" style="background: rgba(17, 24, 39, 0.6); border: 1px solid rgba(55, 65, 81, 0.5); border-radius: 16px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0" style="color: var(--accent); font-weight: 600;">
                                <i class="fa-solid fa-list me-2"></i> Links Ativos
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.loadInviteLinks()" style="border-color: rgba(55, 65, 81, 0.8);">
                                <i class="fa-solid fa-sync-alt"></i> Atualizar
                            </button>
                        </div>
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
            <div class="modal-footer" style="border-top: 1px solid var(--border-subtle);">
                <button type="button" class="btn-action btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .invite-link-item {
        background: rgba(31, 41, 55, 0.4);
        border: 1px solid rgba(55, 65, 81, 0.5);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
    }

    /* Garantir que backdrop seja removido corretamente */
.modal-backdrop.fade {
    transition: opacity 0.15s linear;
}

.modal-backdrop.show {
    opacity: 0.5;
}

/* Prevenir múltiplos backdrops */
body > .modal-backdrop:not(:last-of-type) {
    display: none !important;
}

    .invite-link-item:hover {
        background: rgba(31, 41, 55, 0.6);
        border-color: rgba(34, 197, 94, 0.3);
        transform: translateX(4px);
    }

    .invite-link-url {
        font-family: 'Courier New', monospace;
        background: rgba(17, 24, 39, 0.8);
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        color: var(--accent);
        font-size: 0.85rem;
        border: 1px solid rgba(34, 197, 94, 0.2);
        word-break: break-all;
        margin-bottom: 0.75rem;
    }

    .invite-link-info {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 0.75rem;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .invite-link-info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .invite-link-actions {
        display: flex;
        gap: 0.5rem;
    }

    .no-links-message {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }

    .no-links-message i {
        font-size: 3rem;
        margin-bottom: 16px;
        opacity: 0.3;
    }

    .btn-copy-link, .btn-revoke-link {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .btn-copy-link {
        background: rgba(34, 197, 94, 0.1);
        color: var(--accent);
        border: 1px solid rgba(34, 197, 94, 0.3);
        flex: 1;
    }

    .btn-copy-link:hover {
        background: rgba(34, 197, 94, 0.2);
    }

    .btn-revoke-link {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .btn-revoke-link:hover {
        background: rgba(239, 68, 68, 0.2);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('[Invite Links] 🚀 Inicializando sistema...');
        
        const modal = document.getElementById('inviteLinksModal');
        const createForm = document.getElementById('createInviteLinkForm');
        const createAlert = document.getElementById('createLinkAlert');
        const linksContainer = document.getElementById('inviteLinksContainer');
        const salaId = "{{ $sala->id }}";

        if (!modal || !createForm || !linksContainer) {
            console.error('[Invite Links] ❌ Elementos não encontrados!');
            return;
        }

        console.log('[Invite Links] ✅ Elementos encontrados, sala ID:', salaId);

        function getCsrf() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }

        function showAlert(element, type, message) {
            if (!element) return;
            element.className = `alert alert-${type}`;
            element.textContent = message;
            element.classList.remove('d-none');
            setTimeout(() => element.classList.add('d-none'), 5000);
        }

        function cleanupModal() {
            console.log('[Invite Links] 🧹 Limpando modal...');
            
            // Remover todos os backdrops
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => {
                console.log('[Invite Links] 🗑️ Removendo backdrop');
                backdrop.remove();
            });
            
            // Limpar classes e estilos do body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
            // Garantir que o modal está escondido
            modal.classList.remove('show');
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            modal.removeAttribute('aria-modal');
            
            console.log('[Invite Links] ✅ Modal limpo');
        }

        // ========== EVENT LISTENERS DO MODAL ==========
        
        // Quando o modal é aberto
        modal.addEventListener('show.bs.modal', function() {
            console.log('[Invite Links] 📂 Modal abrindo...');
        });

        modal.addEventListener('shown.bs.modal', function() {
            console.log('[Invite Links] ✅ Modal aberto, carregando links...');
            window.loadInviteLinks();
        });

        // Quando o modal está sendo fechado
        modal.addEventListener('hide.bs.modal', function() {
            console.log('[Invite Links] 🚪 Modal sendo fechado...');
        });

        // Depois que o modal foi completamente fechado
        modal.addEventListener('hidden.bs.modal', function() {
            console.log('[Invite Links] 🔒 Modal fechado completamente');
            setTimeout(cleanupModal, 50);
        });

        // Listeners nos botões de fechar
        const closeButtons = modal.querySelectorAll('[data-bs-dismiss="modal"], .btn-close');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                console.log('[Invite Links] 🔘 Botão fechar clicado');
                e.preventDefault();
                
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                } else {
                    cleanupModal();
                }
            });
        });

        // Click fora do modal
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                console.log('[Invite Links] 🔘 Click fora do modal');
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        });

        // ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('show')) {
                console.log('[Invite Links] ⌨️ ESC pressionado');
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        });

        // ========== CRIAR NOVO LINK ==========
        createForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('[Invite Links] 📝 Criando novo link...');

            const formData = new FormData(createForm);
            const data = {
                validade: formData.get('validade'),
                max_usos: formData.get('max_usos') ? parseInt(formData.get('max_usos')) : null
            };

            console.log('[Invite Links] 📤 Enviando dados:', data);

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

                console.log('[Invite Links] 📥 Status da resposta:', response.status);

                const result = await response.json();
                console.log('[Invite Links] 📦 Resultado:', result);

                if (result.success) {
                    showAlert(createAlert, 'success', result.message || 'Link criado com sucesso!');
                    createForm.reset();
                    await window.loadInviteLinks();
                } else {
                    showAlert(createAlert, 'danger', result.message || 'Erro ao criar link');
                }
            } catch (error) {
                console.error('[Invite Links] ❌ Erro ao criar:', error);
                showAlert(createAlert, 'danger', 'Erro ao criar link de convite: ' + error.message);
            }
        });

        // ========== CARREGAR LISTA DE LINKS ==========
        window.loadInviteLinks = async function() {
            console.log('[Invite Links] 🔄 Carregando links...');

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
                console.log('[Invite Links] 🌐 URL:', url);

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrf()
                    }
                });

                console.log('[Invite Links] 📊 Status:', response.status);

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('[Invite Links] ❌ Erro HTTP:', errorText);
                    throw new Error(`HTTP ${response.status}: ${errorText.substring(0, 100)}`);
                }

                const result = await response.json();
                console.log('[Invite Links] 📦 Dados recebidos:', result);

                if (result.success) {
                    if (result.links && result.links.length > 0) {
                        console.log('[Invite Links] ✅ Total de links:', result.links.length);
                        renderLinks(result.links);
                    } else {
                        console.log('[Invite Links] ℹ️ Nenhum link encontrado');
                        renderNoLinks();
                    }
                } else {
                    console.error('[Invite Links] ❌ Resposta de erro:', result.message);
                    renderError(result.message || 'Erro ao carregar links.');
                }
            } catch (error) {
                console.error('[Invite Links] ❌ Erro ao carregar:', error);
                renderError('Erro ao carregar links: ' + error.message);
            }
        };

        // ========== RENDERIZAR LISTA DE LINKS ==========
        function renderLinks(links) {
            console.log('[Invite Links] 🎨 Renderizando', links.length, 'links');

            linksContainer.innerHTML = links.map(link => `
                <div class="invite-link-item" data-link-id="${link.id}">
                    <div class="invite-link-url" id="link-${link.id}">
                        ${link.url}
                    </div>
                    
                    <div class="invite-link-info">
                        <div class="invite-link-info-item">
                            <i class="fa-solid fa-user"></i>
                            <span>Criado por: <strong>${link.criador}</strong></span>
                        </div>
                        <div class="invite-link-info-item">
                            <i class="fa-solid fa-clock"></i>
                            <span>${link.validade}</span>
                        </div>
                        <div class="invite-link-info-item">
                            <i class="fa-solid fa-chart-bar"></i>
                            <span>${link.usos_atual}${link.max_usos ? ' / ' + link.max_usos : ''} usos</span>
                        </div>
                        ${!link.esta_valido ? 
                            '<span class="badge bg-danger">Inválido</span>' : 
                            '<span class="badge bg-success">Ativo</span>'
                        }
                    </div>

                    <div class="invite-link-actions">
                        <button type="button" class="btn-copy-link" onclick="window.copyLink('link-${link.id}')">
                            <i class="fa-solid fa-copy me-1"></i>Copiar Link
                        </button>
                        <button type="button" class="btn-revoke-link" onclick="window.revokeLink(${link.id})">
                            <i class="fa-solid fa-trash me-1"></i>Revogar
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // ========== RENDERIZAR SEM LINKS ==========
        function renderNoLinks() {
            console.log('[Invite Links] 📭 Mostrando "sem links"');
            linksContainer.innerHTML = `
                <div class="no-links-message">
                    <i class="fa-solid fa-link-slash"></i>
                    <p class="mb-1">Nenhum link de convite ativo no momento.</p>
                    <small class="text-muted">Crie um novo link usando o formulário acima.</small>
                </div>
            `;
        }

        // ========== RENDERIZAR ERRO ==========
        function renderError(message) {
            console.log('[Invite Links] ⚠️ Mostrando erro:', message);
            linksContainer.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    ${message}
                </div>
            `;
        }

        // ========== COPIAR LINK ==========
        window.copyLink = async function(elementId) {
            const element = document.getElementById(elementId);
            if (!element) {
                console.error('[Invite Links] ❌ Elemento não encontrado:', elementId);
                alert('Erro: elemento não encontrado');
                return;
            }

            const text = (element.innerText || element.textContent || '').trim();
            console.log('[Invite Links] 📋 Copiando:', text);

            const linkItem = element.closest('.invite-link-item');
            const btn = linkItem ? linkItem.querySelector('.btn-copy-link') : null;

            function showSuccess() {
                if (!btn) return;
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check me-1"></i>Copiado!';
                btn.style.background = 'rgba(34, 197, 94, 0.3)';
                btn.disabled = true;
                setTimeout(() => {
                    btn.innerHTML = original;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 2000);
            }

            function showError() {
                if (!btn) return;
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-times me-1"></i>Erro';
                btn.style.background = 'rgba(239, 68, 68, 0.3)';
                setTimeout(() => {
                    btn.innerHTML = original;
                    btn.style.background = '';
                }, 2000);
            }

            // Tentar Clipboard API primeiro
            if (navigator.clipboard && navigator.clipboard.writeText) {
                try {
                    await navigator.clipboard.writeText(text);
                    console.log('[Invite Links] ✅ Copiado com Clipboard API');
                    showSuccess();
                    return;
                } catch (err) {
                    console.warn('[Invite Links] ⚠️ Clipboard API falhou:', err);
                }
            }

            // Fallback com textarea
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';
            textarea.style.top = '0';

            const container = linkItem || document.body;
            container.appendChild(textarea);

            textarea.focus();
            textarea.select();

            let success = false;
            try {
                success = document.execCommand('copy');
                console.log('[Invite Links] execCommand result:', success);
            } catch (err) {
                console.error('[Invite Links] ❌ Erro no execCommand:', err);
            } finally {
                container.removeChild(textarea);
            }

            if (success) {
                console.log('[Invite Links] ✅ Copiado com fallback');
                showSuccess();
            } else {
                console.error('[Invite Links] ❌ Falha ao copiar');
                showError();
                prompt('Copie manualmente (Ctrl+C):', text);
            }
        };

        // ========== REVOGAR LINK ==========
        window.revokeLink = async function(linkId) {
            console.log('[Invite Links] 🗑️ Revogando link:', linkId);

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
                console.log('[Invite Links] 📥 Resposta da revogação:', result);

                if (result.success) {
                    console.log('[Invite Links] ✅ Link revogado');
                    await window.loadInviteLinks();
                } else {
                    alert(result.message || 'Erro ao revogar link.');
                }
            } catch (error) {
                console.error('[Invite Links] ❌ Erro ao revogar:', error);
                alert('Erro ao revogar link: ' + error.message);
            }
        };

        console.log('[Invite Links] ✅ Sistema inicializado com sucesso!');
    });
</script>