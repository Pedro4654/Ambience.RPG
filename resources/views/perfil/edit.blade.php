
@extends('layout.app')

@section('title', 'Editar Perfil - Ambience RPG')

@section('content')
<style>
/* ============================================
   VARIÁVEIS - Cores do Ambience com fundo mais claro
   ============================================ */
:root {
  --bg-dark: #0a0f14;
  --bg-secondary: #151b23;
  --card-bg: rgba(26, 35, 50, 0.85);
  --card-hover: rgba(31, 42, 61, 0.9);
  --border-color: rgba(34,197,94,0.2);
  --accent: #22c55e;
  --accent-light: #16a34a;
  --accent-dark: #15803d;
  --text-primary: #e6eef6;
  --text-secondary: #8b9ba8;
  --text-muted: #64748b;
}

/* ============================================
   BACKGROUND - Mais visível e claro
   ============================================ */
.edit-container {
  position: relative;
  min-height: calc(100vh - 140px);
  padding: 32px 0;
}

.edit-container::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url('{{ asset("images/rpg-background.gif") }}') center/cover;
  filter: blur(4px) brightness(0.5);
  z-index: -1;
}

.edit-container::after {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(10, 15, 20, 0.7);
  z-index: -1;
}

/* ============================================
   CARD DE EDIÇÃO
   ============================================ */
.edit-card {
  max-width: 800px;
  margin: 0 auto;
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

/* Header do Card */
.edit-header {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
  padding: 24px 32px;
  border-bottom: 1px solid var(--border-color);
}

.edit-header h1 {
  font-family: 'Montserrat', sans-serif;
  font-size: 28px;
  font-weight: 900;
  color: #052e16;
  margin-bottom: 4px;
}

.edit-header p {
  color: rgba(5, 46, 22, 0.8);
  font-size: 14px;
}

/* Formulário */
.edit-form {
  padding: 32px;
}

.form-group {
  margin-bottom: 24px;
}

.form-label {
  display: block;
  font-size: 14px;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.form-control {
  width: 100%;
  padding: 12px 16px;
  background: rgba(10, 15, 20, 0.6);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  color: var(--text-primary);
  font-size: 14px;
  transition: all 0.2s;
}

.form-control:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

textarea.form-control {
  min-height: 120px;
  resize: vertical;
}

/* Opções de Privacidade */
.privacy-options {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 8px;
}

.privacy-option {
  padding: 16px;
  background: rgba(10, 15, 20, 0.4);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
}

.privacy-option:hover {
  border-color: var(--accent);
  background: rgba(34, 197, 94, 0.05);
}

.privacy-option.active {
  border-color: var(--accent);
  background: rgba(34, 197, 94, 0.1);
}

.privacy-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 4px;
}

.privacy-header input[type="radio"] {
  width: 18px;
  height: 18px;
  accent-color: var(--accent);
}

.privacy-title {
  font-size: 15px;
  font-weight: 700;
  color: var(--text-primary);
}

.privacy-desc {
  font-size: 13px;
  color: var(--text-secondary);
  padding-left: 30px;
}

/* Botões */
.form-actions {
  display: flex;
  gap: 16px;
  padding-top: 24px;
  border-top: 1px solid var(--border-color);
  margin-top: 8px;
}

.btn-submit {
  flex: 1;
  padding: 14px;
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
  color: #052e16;
  border: none;
  border-radius: 8px;
  font-weight: 700;
  font-size: 15px;
  cursor: pointer;
  transition: all 0.2s;
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
}

.btn-cancel {
  flex: 1;
  padding: 14px;
  background: rgba(139, 155, 168, 0.1);
  color: var(--text-secondary);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  font-weight: 700;
  font-size: 15px;
  cursor: pointer;
  text-decoration: none;
  text-align: center;
  transition: all 0.2s;
}

.btn-cancel:hover {
  background: rgba(139, 155, 168, 0.2);
  border-color: var(--accent);
  color: var(--accent);
}

/* Mensagens de Erro */
.error-message {
  color: #ef4444;
  font-size: 13px;
  margin-top: 4px;
}

/* Responsivo */
@media (max-width: 768px) {
  .edit-container {
    padding: 16px;
  }
  
  .edit-form {
    padding: 24px;
  }
  
  .form-actions {
    flex-direction: column;
  }
  
  .edit-header h1 {
    font-size: 24px;
  }
}
</style>

<div class="edit-container">
  <div class="edit-card">
    <!-- Header do Card -->
    <div class="edit-header">
      <h1>✏️ Editar Perfil</h1>
      <p>Atualize suas informações pessoais</p>
    </div>

    <!-- Formulário -->
    <form action="{{ route('perfil.update') }}" method="POST" class="edit-form">
      @csrf
      @method('PUT')

      <!-- Bio -->
      <div class="form-group">
        <label class="form-label">📝 Bio (até 500 caracteres)</label>
        <textarea 
          name="bio" 
          maxlength="500"
          class="form-control"
          placeholder="Conte um pouco sobre você, seus interesses em RPG, etc..."
        >{{ old('bio', Auth::user()->bio) }}</textarea>
        @error('bio')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </div>

      <!-- Website -->
      <div class="form-group">
        <label class="form-label">🔗 Website (opcional)</label>
        <input 
          type="url" 
          name="website" 
          placeholder="https://seu-site.com"
          value="{{ old('website', Auth::user()->website) }}"
          class="form-control"
        >
        @error('website')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </div>

      <!-- Privacidade -->
      <div class="form-group">
        <label class="form-label">🔒 Privacidade do Perfil</label>
        <div class="privacy-options">
          <label class="privacy-option {{ old('privacidade_perfil', Auth::user()->privacidade_perfil) === 'publico' ? 'active' : '' }}" onclick="document.getElementById('privacidade_publico').checked = true">
            <div class="privacy-header">
              <input type="radio" id="privacidade_publico" name="privacidade_perfil" value="publico" {{ old('privacidade_perfil', Auth::user()->privacidade_perfil) === 'publico' ? 'checked' : '' }}>
              <span class="privacy-title">🌐 Público</span>
            </div>
            <p class="privacy-desc">Qualquer um pode ver seu perfil e postagens</p>
          </label>
          
          <label class="privacy-option {{ old('privacidade_perfil', Auth::user()->privacidade_perfil) === 'privado' ? 'active' : '' }}" onclick="document.getElementById('privacidade_privado').checked = true">
            <div class="privacy-header">
              <input type="radio" id="privacidade_privado" name="privacidade_perfil" value="privado" {{ old('privacidade_perfil', Auth::user()->privacidade_perfil) === 'privado' ? 'checked' : '' }}>
              <span class="privacy-title">🔒 Privado</span>
            </div>
            <p class="privacy-desc">Apenas seguidores aprovados podem ver seu perfil</p>
          </label>
        </div>
      </div>

      <!-- Botões -->
      <div class="form-actions">
        <button type="submit" class="btn-submit">✅ Salvar Alterações</button>
        <a href="{{ route('perfil.show', Auth::user()->username) }}" class="btn-cancel">❌ Cancelar</a>
      </div>
    </form>
  </div>
</div>

<script>
// Ativar classe active ao clicar nas opções de privacidade
document.querySelectorAll('.privacy-option').forEach(option => {
  option.addEventListener('click', function() {
    document.querySelectorAll('.privacy-option').forEach(opt => {
      opt.classList.remove('active');
    });
    this.classList.add('active');
    
    // Encontra o radio button dentro desta opção e marca como checked
    const radio = this.querySelector('input[type="radio"]');
    if (radio) {
      radio.checked = true;
    }
  });
});
</script>
@endsection
