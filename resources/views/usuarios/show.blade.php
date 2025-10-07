@extends('layout.app')

@section('content')
<h1>{{ $usuario->username }}</h1>

<div>
    <!-- Foto de Perfil -->
    <div style="margin-bottom: 20px;">
        <img src="{{ $usuario->avatar_url }}" alt="Avatar de {{ $usuario->username }}" 
             style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid #ddd;">
    </div>

    <p><strong>Apelido:</strong> {{ $usuario->nickname ?? 'Não informado' }}</p>
    <p><strong>Nome:</strong> {{ $usuario->nome_completo ?? 'Não informado' }}</p>
    <p><strong>Email:</strong> {{ $usuario->email }}</p>
    <p><strong>Bio:</strong> {{ $usuario->bio ?? 'Não informada' }}</p>
    <p><strong>Membro desde:</strong> {{ $usuario->data_criacao->format('d/m/Y') }}</p>
    <!-- REMOVIDO: Último login -->
    <p><strong>Reputação:</strong> {{ $usuario->pontos_reputacao }} pontos</p>
    <p><strong>Ranking:</strong> #{{ $usuario->ranking_posicao ?: 'N/A' }}</p>
</div>

@can('update', $usuario)
    <a href="{{ route('usuarios.edit', $usuario) }}">Editar Perfil</a>
@endcan

@can('delete', $usuario)
    <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" onsubmit="return confirm('Tem certeza?')">
        @csrf
        @method('DELETE')
        <button type="submit">Excluir Conta</button>
    </form>
@endcan

<script>
// Configuração CSRF
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Classe para gerenciar WebSocket da sala
class GerenciadorSalaWebSocket {
    constructor() {
        this.salaId = {{ $sala->id }};
        this.userId = {{ auth()->id() }};
        this.onlineUsers = new Map();
        this.presenceChannel = null;
        
        this.init();
    }

    init() {
        console.log(`🚀 Inicializando WebSocket para sala ${this.salaId}`);
        this.joinPresenceChannel();
        this.bindEvents();
        this.startHeartbeat();
    }

    // Entrar no canal de presença da sala
    joinPresenceChannel() {
        this.presenceChannel = window.Echo.join(`sala.${this.salaId}`)
            .here((users) => {
                console.log('👥 Usuários já na sala:', users);
                this.updateOnlineUsersList(users);
                this.updateConnectionStatus('Conectado', 'success');
            })
            .joining((user) => {
                console.log('➕ Usuário entrou:', user);
                this.onlineUsers.set(user.id, user);
                this.addUserToList(user);
                this.showNotification(`${user.username} entrou na sala`, 'info');
            })
            .leaving((user) => {
                console.log('➖ Usuário saiu:', user);
                this.onlineUsers.delete(user.id);
                this.removeUserFromList(user.id);
                this.showNotification(`${user.username} saiu da sala`, 'warning');
            })
            .listenForWhisper('typing', (e) => {
                // Para futuras funcionalidades de chat
                console.log(`${e.user.username} está digitando...`);
            })
            .error((error) => {
                console.error('❌ Erro no canal de presença:', error);
                this.updateConnectionStatus('Erro de Conexão', 'danger');
            });
    }

    // Atualizar lista de usuários online
    updateOnlineUsersList(users) {
        const container = $('#participantesContainer');
        
        // Limpar lista atual
        container.empty();
        this.onlineUsers.clear();
        
        // Adicionar usuários online
        users.forEach(user => {
            this.onlineUsers.set(user.id, user);
            this.addUserToList(user, true);
        });
        
        // Atualizar contador
        this.updateUserCount(users.length);
    }

    // Adicionar usuário à lista visual
    addUserToList(user, isInitial = false) {
        const container = $('#participantesContainer');
        const papelClass = `papel-${user.papel}`;
        const papelIcon = this.getPapelIcon(user.papel);
        const papelText = this.getPapelText(user.papel);
        
        const userHtml = `
            <div class="participante-card ${papelClass}" id="user-${user.id}" data-user-id="${user.id}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">
                            ${papelIcon}
                            ${user.username}
                        </h6>
                        <small class="text-muted">
                            ${papelText}
                            ${user.id == {{ $sala->criador_id }} ? ' (Criador)' : ''}
                        </small>
                    </div>
                    <div class="text-end">
                        <small class="text-success d-block">
                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                            Online
                        </small>
                        <div class="badge bg-success">
                            <i class="fas fa-wifi"></i>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Verificar se usuário já existe na lista
        if ($(`#user-${user.id}`).length === 0) {
            container.append(userHtml);
            
            if (!isInitial) {
                // Animar entrada
                $(`#user-${user.id}`).hide().fadeIn(500);
            }
        }
    }

    // Remover usuário da lista visual
    removeUserFromList(userId) {
        const userElement = $(`#user-${userId}`);
        if (userElement.length > 0) {
            userElement.fadeOut(300, function() {
                $(this).remove();
            });
        }
        
        // Atualizar contador
        this.updateUserCount(this.onlineUsers.size);
    }

    // Obter ícone do papel
    getPapelIcon(papel) {
        const icons = {
            'mestre': '<i class="fas fa-crown text-warning me-1"></i>',
            'admin_sala': '<i class="fas fa-star text-warning me-1"></i>',
            'membro': '<i class="fas fa-user text-muted me-1"></i>'
        };
        return icons[papel] || icons.membro;
    }

    // Obter texto do papel
    getPapelText(papel) {
        const texts = {
            'mestre': 'Mestre',
            'admin_sala': 'Admin da Sala', 
            'membro': 'Membro'
        };
        return texts[papel] || 'Membro';
    }

    // Atualizar contador de usuários
    updateUserCount(count) {
        $('#online-user-count').text(count);
        $('#participantes-count').text(count);
    }

    // Atualizar status da conexão
    updateConnectionStatus(status, type) {
        const statusElement = $('#websocketStatus');
        const textElement = $('#wsStatusText');
        
        // Remover classes antigas
        statusElement.removeClass('websocket-status connecting disconnected');
        
        // Aplicar nova classe baseada no tipo
        if (type === 'danger') {
            statusElement.addClass('disconnected');
        } else if (type === 'warning') {
            statusElement.addClass('connecting');
        }
        
        textElement.text(status);
        
        // Atualizar outros indicadores
        $('#wsConnected').text(status).removeClass().addClass(`text-${type === 'success' ? 'success' : 'danger'}`);
        $('#wsLastCheck').text('Agora');
    }

    // Mostrar notificação
    showNotification(message, type = 'info') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show position-fixed" 
                 style="top: 100px; right: 20px; z-index: 1051; max-width: 350px;" 
                 role="alert">
                <i class="fas fa-${type === 'info' ? 'info-circle' : type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('body').append(alertHtml);
        
        // Auto-dismiss após 4 segundos
        setTimeout(() => {
            $('.alert').first().alert('close');
        }, 4000);
    }

    // Heartbeat para manter conexão ativa
    startHeartbeat() {
        setInterval(() => {
            if (this.presenceChannel) {
                // Enviar whisper de heartbeat
                this.presenceChannel.whisper('heartbeat', {
                    user_id: this.userId,
                    timestamp: Date.now()
                });
            }
        }, 30000); // A cada 30 segundos
    }

    // Bind eventos da página
    bindEvents() {
        // Enviar convite
        $('#formConvidarUsuario').submit((e) => {
            e.preventDefault();
            this.enviarConvite();
        });
        
        // Detectar quando usuário sai da página
        $(window).on('beforeunload', () => {
            if (this.presenceChannel) {
                this.presenceChannel.whisper('leaving', {
                    user_id: this.userId
                });
            }
        });
    }

    // Enviar convite
    enviarConvite() {
        const formData = $('#formConvidarUsuario').serialize();
        
        $.post(`/salas/${this.salaId}/convidar`, formData)
            .done(response => {
                if (response.success) {
                    this.showNotification(response.message, 'success');
                    $('#modalConvidarUsuario').modal('hide');
                    $('#formConvidarUsuario')[0].reset();
                } else {
                    this.showNotification(response.message, 'danger');
                }
            })
            .fail(xhr => {
                console.error('Erro ao enviar convite:', xhr);
                this.showNotification('Erro ao enviar convite. Tente novamente.', 'danger');
            });
    }
}

// Funções globais
function mostrarModalConvite() {
    $('#modalConvidarUsuario').modal('show');
}

function sairSala() {
    if (confirm('Tem certeza que deseja sair desta sala?')) {
        $.post(`/salas/{{ $sala->id }}/sair`)
            .done(response => {
                if (response.success) {
                    alert(response.message);
                    window.location.href = response.redirect_to;
                } else {
                    alert(response.message);
                }
            })
            .fail(() => {
                alert('Erro ao sair da sala. Tente novamente.');
            });
    }
}

// Inicializar quando document estiver pronto
let gerenciadorWebSocket;

$(document).ready(function() {
    gerenciadorWebSocket = new GerenciadorSalaWebSocket();
    console.log('✅ Sistema WebSocket da sala inicializado');
});
</script>

@endsection
