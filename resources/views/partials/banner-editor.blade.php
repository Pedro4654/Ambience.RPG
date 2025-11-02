<!-- Partial: resources/views/partials/banner-editor.blade.php -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<div class="modal fade" id="modalBannerEditor" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Banner da Sala (horizontal)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="bannerEditorMessageContainer" class="mb-2"></div>

                <div class="mb-2 text-muted">
                    A imagem precisa ser <strong>horizontal</strong> (largura &gt; altura). Recomendado: proporção <strong>16:9</strong>.
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div style="width:100%; background:#f8f9fa; display:flex; align-items:center; justify-content:center; min-height:320px;">
                            <img id="bannerEditorImage" style="max-width:100%; display:none; max-height:560px;" />
                            <div id="bannerEditorEmpty" class="text-muted">Escolha uma imagem para começar</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Escolher arquivo (png/jpg) - max 8MB</label>
                        <input type="file" id="bannerFileInput" accept="image/png,image/jpeg" class="form-control mb-2">
                        <div class="mb-2">
                            <h6>Pré-visualização</h6>
                            <div style="width:100%; height:200px; border:1px solid #ddd; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                                <img id="bannerPreviewImg" style="max-width:100%; max-height:100%; display:none;" />
                                <div id="bannerPreviewEmpty" class="text-muted">Faça upload para pré-visualizar</div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <button id="btnUploadCropped" class="btn btn-primary w-100" disabled>Salvar Banner</button>
                            <button id="btnRemoveBanner" class="btn btn-outline-danger w-100 mt-2">Remover Banner</button>
                        </div>

                        <!-- Confirm remove UI (in-interface, hidden until needed) -->
                        <div id="bannerRemoveConfirm" class="mt-2" style="display:none;">
                            <div class="alert alert-warning d-flex justify-content-between align-items-center mb-2" role="alert">
                                <div>Confirma remoção do banner desta sala?</div>
                                <div>
                                    <button id="btnConfirmRemove" class="btn btn-sm btn-danger me-1">Confirmar</button>
                                    <button id="btnCancelRemove" class="btn btn-sm btn-secondary">Cancelar</button>
                                </div>
                            </div>
                        </div>

                        <hr />
                        <div>
                            <label class="form-label">Ou definir cor de fundo (hex)</label>
                            <input type="text" id="bannerColorInput" class="form-control" placeholder="#aabbcc" />
                            <button id="btnSetBannerColor" class="btn btn-secondary w-100 mt-2">Salvar cor</button>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="bannerSalaId" value="" />
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let cropper = null;
        const modalEl = document.getElementById('modalBannerEditor');
        const modal = new bootstrap.Modal(modalEl);
        const img = document.getElementById('bannerEditorImage');
        const emptyMsg = document.getElementById('bannerEditorEmpty');
        const fileInput = document.getElementById('bannerFileInput');
        const previewImg = document.getElementById('bannerPreviewImg');
        const previewEmpty = document.getElementById('bannerPreviewEmpty');
        const btnUpload = document.getElementById('btnUploadCropped');
        const btnRemove = document.getElementById('btnRemoveBanner');
        const bannerIdInput = document.getElementById('bannerSalaId');
        const btnSetColor = document.getElementById('btnSetBannerColor');
        const colorInput = document.getElementById('bannerColorInput');

        const messageContainer = document.getElementById('bannerEditorMessageContainer');
        const removeConfirm = document.getElementById('bannerRemoveConfirm');
        const btnConfirmRemove = document.getElementById('btnConfirmRemove');
        const btnCancelRemove = document.getElementById('btnCancelRemove');

        function clearMessage() {
            messageContainer.innerHTML = '';
        }

        function showMessage(type, text, timeout = 6000) {
            // type: 'success'|'danger'|'warning'|'info'
            clearMessage();
            const div = document.createElement('div');
            div.className = `alert alert-${type}`;
            div.setAttribute('role', 'alert');
            div.textContent = text;
            messageContainer.appendChild(div);
            if (timeout > 0) {
                setTimeout(() => {
                    if (messageContainer.contains(div)) messageContainer.removeChild(div);
                }, timeout);
            }
        }

        function initCropper() {
    if (cropper) { cropper.destroy(); cropper = null; }
    cropper = new Cropper(img, {
      aspectRatio: 16 / 9,
      viewMode: 1,
      autoCropArea: 1,
      movable: true,
      zoomable: true,
      ready() { updatePreview(); },
      crop() { updatePreview(); }
    });
  }

  async function loadExistingBannerIntoEditor(url) {
    // Tenta baixar como blob (mesma origem) para evitar problemas de CORS/taint
    try {
      const res = await fetch(url, { credentials: 'same-origin' });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const blob = await res.blob();
      const objUrl = URL.createObjectURL(blob);
      img.onload = () => { initCropper(); };
      img.src = objUrl;
      img.style.display = 'block';
      emptyMsg.style.display = 'none';
      btnUpload.disabled = false;
      return;
    } catch (e) {
      // Fallback: usar a URL direta com crossOrigin; requer CORS correto no servidor
      img.crossOrigin = 'anonymous';
      img.onload = () => { initCropper(); };
      img.onerror = () => {
        // Se falhar, volta para o estado vazio
        img.style.display = 'none';
        emptyMsg.style.display = 'block';
      };
      img.src = url;
      img.style.display = 'block';
      emptyMsg.style.display = 'none';
      btnUpload.disabled = false;
    }
  }

        // Helper to open editor programmatically (pass salaId, optional bannerUrl)
        window.openBannerEditor = function(salaId, bannerUrl = null, bannerColor = null) {
    bannerIdInput.value = salaId;
    // Reset de estado
    fileInput.value = '';
    if (cropper) { cropper.destroy(); cropper = null; }
    clearMessage();
    removeConfirm.style.display = 'none';
    btnUpload.disabled = true;

    // Preenche cor, se existir
    colorInput.value = bannerColor || '';

    // Pré-visualização lateral (mantém seu comportamento atual)
    if (bannerUrl) {
      previewImg.src = bannerUrl;
      previewImg.style.display = 'block';
      previewEmpty.style.display = 'none';
    } else {
      previewImg.style.display = 'none';
      previewEmpty.style.display = 'block';
    }

    // NOVO: carrega a imagem atual no canvas principal e inicializa o cropper
    if (bannerUrl) {
      loadExistingBannerIntoEditor(bannerUrl);
    } else {
      img.style.display = 'none';
      emptyMsg.style.display = 'block';
    }

    // Abre o modal
    modal.show();
  };

        // File chosen
        fileInput.addEventListener('change', function(e) {
            const f = e.target.files && e.target.files[0];
            if (!f) return;
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

            const url = URL.createObjectURL(f);
            img.src = url;
            img.style.display = 'block';
            emptyMsg.style.display = 'none';

            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            cropper = new Cropper(img, {
                aspectRatio: 16 / 9, // HORIZONTAL
                viewMode: 1,
                autoCropArea: 1,
                movable: true,
                zoomable: true,
                ready() {
                    updatePreview();
                },
                crop() {
                    updatePreview();
                }
            });

            btnUpload.disabled = false;
            clearMessage();
        });

        function updatePreview() {
            if (!cropper) return;
            const canvas = cropper.getCroppedCanvas({
                width: 1600,
                height: 900
            });
            previewImg.src = canvas.toDataURL('image/jpeg', 0.85);
            previewImg.style.display = 'block';
            previewEmpty.style.display = 'none';
        }

        // Safe JSON parser: only parse JSON if content-type is application/json
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

        // Upload cropped
        btnUpload.addEventListener('click', async function() {
            if (!cropper) {
                showMessage('danger', 'Nenhuma imagem selecionada.');
                return;
            }
            const salaId = bannerIdInput.value;
            const canvas = cropper.getCroppedCanvas({
                width: 1600,
                height: 900
            });
            const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.85));
            if (!blob) {
                showMessage('danger', 'Erro ao processar a imagem.');
                return;
            }
            const fd = new FormData();
            fd.append('_token', '{{ csrf_token() }}');
            fd.append('banner', blob, 'banner.jpg');

            try {
                const res = await fetch(`/salas/${salaId}/banner`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: fd,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                        // Note: do NOT set Content-Type when sending FormData
                    }
                });

                if (handleCommonHttpStatuses(res.status)) return;

                const parsed = await parseJsonSafe(res);
                if (parsed && parsed.__unexpected_text) {
                    console.error('Resposta inesperada (não JSON):', parsed.text);
                    // Do not dump full HTML to UI; give actionable hint
                    showMessage('danger', 'Resposta inesperada do servidor (HTML). Possíveis causas: sessão expirada, rota incorreta ou erro interno. Verifique o console/network.');
                    return;
                }

                const json = parsed;
                if (res.ok && json.success) {
                    showMessage('success', 'Banner salvo!');
                    setTimeout(() => {
                        modal.hide();
                        location.reload();
                    }, 700);
                } else {
                    showMessage('danger', json.message || 'Erro ao salvar banner.');
                }
            } catch (e) {
                console.error(e);
                showMessage('danger', 'Erro de rede ao enviar banner.');
            }
        });

        // Remove banner flow: show confirm UI
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
                    return;
                }

                const parsed = await parseJsonSafe(res);
                if (parsed && parsed.__unexpected_text) {
                    console.error('Resposta inesperada (não JSON):', parsed.text);
                    showMessage('danger', 'Resposta inesperada do servidor ao remover. Verifique o console/network.');
                    btnConfirmRemove.disabled = false;
                    btnCancelRemove.disabled = false;
                    return;
                }

                const json = parsed;
                if (res.ok && json.success) {
                    showMessage('success', 'Banner removido.');
                    setTimeout(() => {
                        modal.hide();
                        location.reload();
                    }, 700);
                } else {
                    showMessage('danger', json.message || 'Erro ao remover banner.');
                    btnConfirmRemove.disabled = false;
                    btnCancelRemove.disabled = false;
                }
            } catch (e) {
                console.error(e);
                showMessage('danger', 'Erro de rede ao remover banner.');
                btnConfirmRemove.disabled = false;
                btnCancelRemove.disabled = false;
            }
        });

        // Set background color
        btnSetColor.addEventListener('click', async function() {
            const salaId = bannerIdInput.value;
            const color = colorInput.value.trim();
            if (!/^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(color)) {
                showMessage('danger', 'Hex inválido. Ex: #aabbcc');
                return;
            }

            try {
                const res = await fetch(`/salas/${salaId}/banner/color`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        color
                    })
                });

                if (handleCommonHttpStatuses(res.status)) return;

                const parsed = await parseJsonSafe(res);
                if (parsed && parsed.__unexpected_text) {
                    console.error('Resposta inesperada (não JSON):', parsed.text);
                    showMessage('danger', 'Resposta inesperada do servidor ao salvar cor. Verifique o console/network.');
                    return;
                }

                const json = parsed;
                if (res.ok && json.success) {
                    showMessage('success', 'Cor salva.');
                    setTimeout(() => {
                        modal.hide();
                        location.reload();
                    }, 700);
                } else {
                    showMessage('danger', json.message || 'Erro ao salvar cor.');
                }
            } catch (e) {
                console.error(e);
                showMessage('danger', 'Erro de rede.');
            }
        });

    });
</script>
