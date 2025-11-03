{{-- resources/views/partials/profile-photo-editor.blade.php --}}
{{-- Mesmo padrão do editor de banner, ajustado para 1:1 e endpoints de foto de perfil --}}


<!-- Cropper (mesma versão usada no banner) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>


<div class="modal fade" id="modalProfileEditor" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">


      <div class="modal-header">
        <h5 class="modal-title">Editar Foto de Perfil (1:1)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>


      <div class="modal-body">
        <div id="profileEditorMessageContainer" class="mb-2"></div>


        <div class="mb-2 text-muted">
          A imagem precisa ser quadrada (1:1). Recomendado 500x500. Formatos: JPG/PNG. Máx 5MB.
        </div>


        <div class="row">
          <div class="col-md-8">
            <div style="width:100%; background:#f8f9fa; display:flex; align-items:center; justify-content:center; min-height:320px;">
              <img id="profileEditorImage" style="max-width:100%; display:none; max-height:560px;" alt="Editor de Foto">
              <div id="profileEditorEmpty" class="text-muted">Escolha uma imagem para começar</div>
            </div>
          </div>


          <div class="col-md-4">
            <label class="form-label">Escolher arquivo (png/jpg - máx 5MB)</label>
            <input type="file" id="profileFileInput" accept="image/png,image/jpeg" class="form-control mb-2">


            <div class="mb-2">
              <h6>Pré-visualização</h6>
              <div style="width:100%; height:200px; border:1px solid #ddd; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                <img id="profilePreviewImg" style="max-width:100%; max-height:100%; display:none;" alt="Preview">
                <div id="profilePreviewEmpty" class="text-muted">Faça upload para pré-visualizar</div>
              </div>
            </div>


            <div class="mb-2">
              <button id="btnUploadProfile" class="btn btn-primary w-100" disabled>Salvar Foto</button>
              <button id="btnRemoveProfile" class="btn btn-outline-danger w-100 mt-2">Remover Foto</button>
            </div>


            <!-- Confirmação de remoção (oculta até acionar) -->
            <div id="profileRemoveConfirm" class="mt-2" style="display:none;">
              <div class="alert alert-warning d-flex justify-content-between align-items-center mb-2" role="alert">
                <div>Confirma remoção da foto de perfil desta sala?</div>
                <div>
                  <button id="btnConfirmRemoveProfile" class="btn btn-sm btn-danger me-1">Confirmar</button>
                  <button id="btnCancelRemoveProfile" class="btn btn-sm btn-secondary">Cancelar</button>
                </div>
              </div>
            </div>


            <hr>


            <label class="form-label">Ou definir cor de fundo (hex)</label>
            <input type="text" id="profileColorInput" class="form-control" placeholder="aabbcc">
            <button id="btnSetProfileColor" class="btn btn-secondary w-100 mt-2">Salvar cor</button>
          </div>
        </div>


        <input type="hidden" id="profileSalaId" value="">
      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
  let cropper = null;

  // pega CSRF via meta
  const csrftoken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

  const modalEl = document.getElementById('modalProfileEditor');
  const modal = new bootstrap.Modal(modalEl);

  const img = document.getElementById('profileEditorImage');
  const emptyMsg = document.getElementById('profileEditorEmpty');
  const fileInput = document.getElementById('profileFileInput');
  const previewImg = document.getElementById('profilePreviewImg');
  const previewEmpty = document.getElementById('profilePreviewEmpty');

  const btnUpload = document.getElementById('btnUploadProfile');
  const btnRemove = document.getElementById('btnRemoveProfile');

  const salaIdInput = document.getElementById('profileSalaId');
  const colorInput = document.getElementById('profileColorInput');
  const btnSetColor = document.getElementById('btnSetProfileColor');

  const messageContainer = document.getElementById('profileEditorMessageContainer');

  const removeConfirm = document.getElementById('profileRemoveConfirm');
  const btnConfirmRemove = document.getElementById('btnConfirmRemoveProfile');
  const btnCancelRemove = document.getElementById('btnCancelRemoveProfile');

  function clearMessage() { messageContainer.innerHTML = ''; }

  function showMessage(type, text, timeout = 6000) {
    clearMessage();
    const div = document.createElement('div');
    div.className = `alert alert-${type}`;
    div.setAttribute('role', 'alert');
    div.textContent = text;
    messageContainer.appendChild(div);
    if (timeout > 0) {
      setTimeout(() => { if (messageContainer.contains(div)) messageContainer.removeChild(div); }, timeout);
    }
  }

  function initCropper() {
    if (cropper) { cropper.destroy(); cropper = null; }
    cropper = new Cropper(img, {
      aspectRatio: 1,
      viewMode: 1,
      autoCropArea: 1,
      movable: true,
      zoomable: true,
      ready() { updatePreview(); },
      crop() { updatePreview(); },
    });
  }

  async function loadExistingPhotoIntoEditor(url) {
    try {
      const res = await fetch(url, { credentials: 'same-origin' });
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const blob = await res.blob();
      const objUrl = URL.createObjectURL(blob);
      img.onload = () => { initCropper(); };
      img.src = objUrl;
      img.style.display = 'block';
      emptyMsg.style.display = 'none';
      btnUpload.disabled = false;
      return;
    } catch (e) {
      img.crossOrigin = 'anonymous';
      img.onload = () => { initCropper(); };
      img.onerror = () => {
        img.style.display = 'none';
        emptyMsg.style.display = 'block';
      };
      img.src = url;
      img.style.display = 'block';
      emptyMsg.style.display = 'none';
      btnUpload.disabled = false;
    }
  }

  window.openProfileEditor = function (salaId, photoUrl = null, photoColor = null) {
    salaIdInput.value = salaId;
    fileInput.value = '';
    if (cropper) { cropper.destroy(); cropper = null; }
    clearMessage();
    removeConfirm.style.display = 'none';
    btnUpload.disabled = true;
    colorInput.value = photoColor || '';

    if (photoUrl) {
      previewImg.src = photoUrl;
      previewImg.style.display = 'block';
      previewEmpty.style.display = 'none';
    } else {
      previewImg.style.display = 'none';
      previewEmpty.style.display = 'block';
    }

    if (photoUrl) {
      loadExistingPhotoIntoEditor(photoUrl);
    } else {
      img.style.display = 'none';
      emptyMsg.style.display = 'block';
    }

    modal.show();
  };

  window.openProfilePhotoEditor = function (salaId, photoUrl = null, photoColor = null) {
    return window.openProfileEditor(salaId, photoUrl, photoColor);
  };

  fileInput.addEventListener('change', function (e) {
    const f = e.target.files && e.target.files[0];
    if (!f) return;

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

    const url = URL.createObjectURL(f);
    img.src = url;
    img.style.display = 'block';
    emptyMsg.style.display = 'none';

    if (cropper) { cropper.destroy(); cropper = null; }
    cropper = new Cropper(img, {
      aspectRatio: 1,
      viewMode: 1,
      autoCropArea: 1,
      movable: true,
      zoomable: true,
      ready() { updatePreview(); },
      crop() { updatePreview(); },
    });

    btnUpload.disabled = false;
    clearMessage();
  });

  function updatePreview() {
    if (!cropper) return;
    const canvas = cropper.getCroppedCanvas({ width: 800, height: 800 });
    previewImg.src = canvas.toDataURL('image/jpeg', 0.85);
    previewImg.style.display = 'block';
    previewEmpty.style.display = 'none';
  }

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
    if (status === 419) { showMessage('danger', 'Sessão expirada (419). Recarregue e faça login.'); return true; }
    if (status === 401) { showMessage('danger', 'Usuário não autenticado (401). Faça login novamente.'); return true; }
    if (status === 403) { showMessage('danger', 'Acesso negado (403).'); return true; }
    if (status === 404) { showMessage('danger', 'Rota não encontrada (404).'); return true; }
    return false;
  }

  btnUpload.addEventListener('click', async function () {
  if (!cropper) { showMessage('danger', 'Nenhuma imagem selecionada.'); return; }

  const salaId = salaIdInput.value;
  const canvas = cropper.getCroppedCanvas({ width: 800, height: 800 });
  const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', 0.85));
  if (!blob) { showMessage('danger', 'Erro ao processar a imagem.'); return; }

  const fd = new FormData();
  // campo _token para Laravel
  fd.append('_token', csrftoken);
  // **NOME CORRIGIDO DO CAMPO**: backend espera 'profile_photo'
  fd.append('profile_photo', blob, 'profile.jpg');

  // Debug rápido: lista os nomes/valores do FormData no console (apenas durante testes)
  for (const pair of fd.entries()) {
    console.debug('[ProfileUpload] FormData:', pair[0], pair[1]);
  }

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

    if (handleCommonHttpStatuses(res.status)) return;

    const parsed = await parseJsonSafe(res);
    if (parsed && parsed.__unexpected_text) {
      console.error('Resposta inesperada (HTML):', parsed.text);
      showMessage('danger', 'Resposta inesperada do servidor (HTML). Verifique sessão/rota/erros.');
      return;
    }

    const json = parsed;
    if (res.ok && json.success) {
      showMessage('success', 'Foto salva!');
      setTimeout(() => { modal.hide(); location.reload(); }, 700);
    } else {
      showMessage('danger', json.message || 'Erro ao salvar foto.');
    }
  } catch (e) {
    console.error(e);
    showMessage('danger', 'Erro de rede ao enviar foto.');
  }
});

  btnRemove.addEventListener('click', function () {
    clearMessage();
    removeConfirm.style.display = 'block';
  });

  btnCancelRemove.addEventListener('click', function () {
    removeConfirm.style.display = 'none';
  });

  btnConfirmRemove.addEventListener('click', async function () {
    const salaId = salaIdInput.value;
    btnConfirmRemove.disabled = true;
    btnCancelRemove.disabled = true;

    try {
      const res = await fetch(`/salas/${salaId}/profile-photo`, {
        method: 'DELETE',
        credentials: 'same-origin',
        headers: {
          'X-CSRF-TOKEN': csrftoken,
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
        console.error('Resposta inesperada (HTML):', parsed.text);
        showMessage('danger', 'Resposta inesperada ao remover. Verifique o console/rede.');
        btnConfirmRemove.disabled = false;
        btnCancelRemove.disabled = false;
        return;
      }

      const json = parsed;
      if (res.ok && json.success) {
        showMessage('success', 'Foto removida.');
        setTimeout(() => { modal.hide(); location.reload(); }, 700);
      } else {
        showMessage('danger', json.message || 'Erro ao remover foto.');
        btnConfirmRemove.disabled = false;
        btnCancelRemove.disabled = false;
      }
    } catch (e) {
      console.error(e);
      showMessage('danger', 'Erro de rede ao remover foto.');
      btnConfirmRemove.disabled = false;
      btnCancelRemove.disabled = false;
    }
  });

  btnSetColor.addEventListener('click', async function () {
  const salaId = salaIdInput.value;
  let color = colorInput.value.trim();

  if (!/^#?([0-9a-f]{3}|[0-9a-f]{6})$/i.test(color)) {
    showMessage('danger', 'Hex inválido. Ex.: #aabbcc ou aabbcc');
    return;
  }

  if (!color.startsWith('#')) color = '#' + color;

  try {
    const res = await fetch(`/salas/${salaId}/profile-photo/color`, {   // <-- ROTA CORRIGIDA
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrftoken,
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({ color })
    });

    if (handleCommonHttpStatuses(res.status)) return;

    const parsed = await parseJsonSafe(res);
    if (parsed && parsed.__unexpected_text) {
      console.error('Resposta inesperada (HTML):', parsed.text);
      showMessage('danger', 'Resposta inesperada ao salvar cor. Verifique o console/rede.');
      return;
    }

    const json = parsed;
    if (res.ok && json.success) {
      showMessage('success', 'Cor salva.');
      setTimeout(() => { modal.hide(); location.reload(); }, 700);
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
