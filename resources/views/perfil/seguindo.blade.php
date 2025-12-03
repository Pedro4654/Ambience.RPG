@extends('layout.app')

@section('title', $usuario->username . ' está seguindo - Ambience RPG')

@section('content')
<style>
/* ============================================
   VARIÁVEIS - Cores do Ambience
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
   BACKGROUND
   ============================================ */
.following-container {
  position: relative;
  min-height: calc(100vh - 140px);
  padding: 32px 0;
}

.following-container::before {
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

.following-container::after {
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
   CONTEÚDO
   ============================================ */
.following-content {
  max-width: 900px;
  margin: 0 auto;
  padding: 0 24px;
  position: relative;
  z-index: 1;
}

/* ============================================
   HEADER
   ============================================ */
.following-header {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 32px;
  margin-bottom: 24px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 24px;
}

.header-info h1 {
  font-family: 'Montserrat', sans-serif;
  font-size: 32px;
  font-weight: 900;
  color: var(--text-primary);
  margin-bottom: 8px;
}

.header-info p {
  color: var(--text-secondary);
  font-size: 16px;
}

.back-btn {
  padding: 12px 24px;
  background: rgba(34, 197, 94, 0.1);
  border: 1px solid rgba(34, 197, 94, 0.3);
  border-radius: 10px;
  color: var(--accent);
  font-weight: 700;
  font-size: 14px;
  text-decoration: none;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 8px;
  white-space: nowrap;
}

.back-btn:hover {
  background: rgba(34, 197, 94, 0.15);
  border-color: var(--accent);
  transform: translateY(-2px);
}

/* ============================================
   LISTA DE USUÁRIOS
   ============================================ */
.users-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.user-card {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 24px;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
}

.user-card:hover {
  border-color: var(--accent);
  box-shadow: 0 8px 24px rgba(34, 197, 94, 0.15);
  transform: translateY(-2px);
}

.user-info {
  display: flex;
  align-items: center;
  gap: 20px;
  flex: 1;
}

.user-avatar {
  width: 50px;
  height: 50px;
  border-radius: 16px;
  object-fit: cover;
  border: 2px solid var(--border-color);
  background: linear-gradient(135deg, #064e3b, #052e16);
  flex-shrink: 0;
}

.avatar-default {
  width: 80px;
  height: 80px;
  border-radius: 16px;
  background: linear-gradient(135deg, #064e3b, #052e16);
  border: 2px solid var(--border-color);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  font-weight: 900;
  color: var(--accent);
  flex-shrink: 0;
}

.user-details {
  flex: 1;
}

.user-name {
  display: block;
  font-size: 20px;
  font-weight: 900;
  color: var(--text-primary);
  margin-bottom: 4px;
  text-decoration: none;
  transition: color 0.2s;
}

.user-name:hover {
  color: var(--accent);
}

.user-bio {
  color: var(--text-secondary);
  font-size: 14px;
  margin-bottom: 12px;
  line-height: 1.5;
  max-width: 500px;
}

.user-stats {
  display: flex;
  gap: 24px;
}

.user-stat {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  color: var(--text-muted);
}

/* ============================================
   BOTÕES DE AÇÃO
   ============================================ */
.action-buttons {
  display: flex;
  gap: 12px;
}

.btn {
  padding: 10px 20px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 14px;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  text-align: center;
  white-space: nowrap;
}

.btn-unfollow {
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
  border: 1px solid rgba(239, 68, 68, 0.3);
}

.btn-unfollow:hover {
  background: rgba(239, 68, 68, 0.15);
  border-color: #ef4444;
}

.btn-follow {
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
  color: #052e16;
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.btn-follow:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
}

/* ============================================
   ESTADO VAZIO
   ============================================ */
.empty-state {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 80px 40px;
  text-align: center;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

.empty-state-icon {
  font-size: 64px;
  margin-bottom: 20px;
  opacity: 0.5;
}

.empty-state-title {
  font-size: 24px;
  font-weight: 900;
  color: var(--text-primary);
  margin-bottom: 8px;
}

.empty-state-description {
  color: var(--text-secondary);
  margin-bottom: 32px;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
}

.empty-state-btn {
  display: inline-block;
  padding: 14px 32px;
  background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
  color: #052e16;
  border-radius: 10px;
  font-weight: 900;
  font-size: 15px;
  text-decoration: none;
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
  transition: all 0.2s;
}

.empty-state-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
}

/* ============================================
   PAGINAÇÃO
   ============================================ */
.pagination-container {
  margin-top: 40px;
  display: flex;
  justify-content: center;
}

/* ============================================
   RESPONSIVO
   ============================================ */
@media (max-width: 768px) {
  .following-content {
    padding: 0 16px;
  }
  
  .following-header {
    padding: 24px;
  }
  
  .header-content {
    flex-direction: column;
    gap: 16px;
  }
  
  .header-info h1 {
    font-size: 24px;
  }
  
  .user-card {
    flex-direction: column;
    align-items: stretch;
    gap: 20px;
  }
  
  .user-info {
    flex-direction: column;
    text-align: center;
  }
  
  .user-stats {
    justify-content: center;
  }
  
  .action-buttons {
    justify-content: center;
  }
}

/* ============================================
   SVG ICONS
   ============================================ */
.svg-icon {
  width: 1.2em;
  height: 1.2em;
  vertical-align: -0.15em;
  fill: currentColor;
  overflow: hidden;
}
</style>

<div class="following-container">
  <div class="following-content">
    <!-- Header -->
    <div class="following-header">
      <div class="header-content">
        <div class="header-info">
          <h1>
            <svg class="svg-icon" viewBox="0 0 1024 1024">
              <path d="M896 576c70.4 0 128-57.6 128-128s-57.6-128-128-128-128 57.6-128 128 57.6 128 128 128z m128 64h-256c-70.4 0-128 57.6-128 128v64h512v-64c0-70.4-57.6-128-128-128z"/>
            </svg>
            Seguindo
          </h1>
          <p>{{ $usuario->username }} segue {{ $seguindo->total() }} usuário(s)</p>
        </div>
        <a href="{{ route('perfil.show', $usuario->username) }}" class="back-btn">
          <svg class="svg-icon" viewBox="0 0 1024 1024">
            <path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-27.2 65.6 0 89.6l309.6 280z"/>
          </svg>
          Voltar ao Perfil
        </a>
      </div>
    </div>

    <!-- Lista de Usuários -->
    @if($seguindo->count() > 0)
      <div class="users-list">
        @foreach($seguindo as $item)
          @php $usuario_seguido = $item->seguido; @endphp
          <div class="user-card">
            <div class="user-info">
              <a href="{{ route('perfil.show', $usuario_seguido->username) }}">
                @if($usuario_seguido->avatar_url)
                  <img src="{{ $usuario_seguido->avatar_url }}" alt="{{ $usuario_seguido->username }}" class="user-avatar">
                @else
                  <div class="avatar-default">{{ strtoupper(substr($usuario_seguido->username, 0, 1)) }}</div>
                @endif
              </a>
              
              <div class="user-details">
                <a href="{{ route('perfil.show', $usuario_seguido->username) }}" class="user-name">
                  {{ $usuario_seguido->username }}
                </a>
                <p class="user-bio">{{ $usuario_seguido->bio ?? 'Sem bio' }}</p>
                
                <div class="user-stats">
                  <span class="user-stat">
                    <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 13px; height: 13px;">
                      <path d="M896 192H128c-17.6 0-32 14.4-32 32v576c0 17.6 14.4 32 32 32h768c17.6 0 32-14.4 32-32V224c0-17.6-14.4-32-32-32z m-32 576H160V256h704v512z"/>
                      <path d="M256 320h512v64H256zM256 448h384v64H256zM256 576h256v64H256z"/>
                    </svg>
                    {{ $usuario_seguido->posts()->count() ?? 0 }} posts
                  </span>
                  <span class="user-stat">
                    <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 13px; height: 13px;">
                      <path d="M768 576c70.4 0 128-57.6 128-128s-57.6-128-128-128-128 57.6-128 128 57.6 128 128 128z m128 64h-256c-70.4 0-128 57.6-128 128v64h512v-64c0-70.4-57.6-128-128-128zM384 576c70.4 0 128-57.6 128-128s-57.6-128-128-128-128 57.6-128 128 57.6 128 128 128z m128 64H256c-70.4 0-128 57.6-128 128v64h384v-64c0-70.4-57.6-128-128-128z"/>
                    </svg>
                    {{ $usuario_seguido->total_seguidores ?? 0 }} seguidores
                  </span>
                </div>
              </div>
            </div>

            <!-- Botões de Ação -->
            @auth
              @if(Auth::id() === $usuario->id)
                <div class="action-buttons">
                  <form action="{{ route('perfil.deixar_de_seguir', $usuario_seguido->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-unfollow" onclick="return confirm('Deixar de seguir {{ $usuario_seguido->username }}?')">
                      <svg class="svg-icon" viewBox="0 0 1024 1024">
                        <path d="M512 64C264.96 64 64 264.96 64 512s200.96 448 448 448 448-200.96 448-448S759.04 64 512 64z m0 832c-211.2 0-384-172.8-384-384s172.8-384 384-384 384 172.8 384 384-172.8 384-384 384z"/>
                        <path d="M672 352l-160 160-160-160-64 64 224 224 224-224z"/>
                      </svg>
                      Deixar de Seguir
                    </button>
                  </form>
                </div>
              @endif
            @endauth
          </div>
        @endforeach
      </div>

      <!-- Paginação -->
      @if($seguindo->hasPages())
        <div class="pagination-container">
          {{ $seguindo->links() }}
        </div>
      @endif
    @else
      <div class="empty-state">
        <div class="empty-state-icon">
          <svg class="svg-icon" viewBox="0 0 1024 1024" style="width: 64px; height: 64px;">
            <path d="M896 576c70.4 0 128-57.6 128-128s-57.6-128-128-128-128 57.6-128 128 57.6 128 128 128z m128 64h-256c-70.4 0-128 57.6-128 128v64h512v-64c0-70.4-57.6-128-128-128z"/>
          </svg>
        </div>
        <h2 class="empty-state-title">Ninguém sendo seguido ainda</h2>
        <p class="empty-state-description">{{ $usuario->username }} ainda não segue ninguém</p>
        <a href="{{ route('comunidade.feed') }}" class="empty-state-btn">
          <svg class="svg-icon" viewBox="0 0 1024 1024">
            <path d="M832 64H192c-70.4 0-128 57.6-128 128v640c0 70.4 57.6 128 128 128h640c70.4 0 128-57.6 128-128V192c0-70.4-57.6-128-128-128z m-64 704H256V256h512v512z"/>
            <path d="M320 320h384v64H320zM320 448h256v64H320z"/>
          </svg>
          Explorar Comunidade
        </a>
      </div>
    @endif
  </div>
</div>
@endsection