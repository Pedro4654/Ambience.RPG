<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero_ticket',
        'usuario_id',
        'atribuido_a',
        'usuario_denunciado_id',
        'categoria',
        'assunto',
        'descricao',
        'status',
        'prioridade',
        'ip_address',
        'user_agent',
        'visivel_usuario',
        'visualizacoes',
        'data_abertura',
        'data_fechamento',
        'ultima_resposta_staff',
        'ultima_resposta_usuario'
    ];

    protected $casts = [
        'data_abertura' => 'datetime',
        'data_fechamento' => 'datetime',
        'ultima_resposta_staff' => 'datetime',
        'ultima_resposta_usuario' => 'datetime',
        'visivel_usuario' => 'boolean',
        'visualizacoes' => 'integer'
    ];

    protected $dates = ['deleted_at'];

    // ========== RELATIONSHIPS ==========

    /**
     * Usuário que criou o ticket
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Staff atribuído ao ticket
     */
    public function atribuidoA()
    {
        return $this->belongsTo(Usuario::class, 'atribuido_a');
    }

    /**
     * Usuário denunciado (se for ticket de denúncia)
     */
    public function usuarioDenunciado()
    {
        return $this->belongsTo(Usuario::class, 'usuario_denunciado_id');
    }

    /**
     * Respostas do ticket
     */
    public function respostas()
    {
        return $this->hasMany(TicketResposta::class)->orderBy('created_at', 'asc');
    }

    /**
     * Anexos do ticket
     */
    public function anexos()
    {
        return $this->hasMany(TicketAnexo::class);
    }

    /**
     * Histórico de ações
     */
    public function historico()
    {
        return $this->hasMany(TicketHistorico::class)->orderBy('data_acao', 'desc');
    }

    /**
     * Notificações relacionadas
     */
    public function notificacoes()
    {
        return $this->hasMany(TicketNotificacao::class);
    }

    // ========== SCOPES ==========

    /**
     * Tickets abertos (novo, pendente, em_analise, aguardando_resposta)
     */
    public function scopeAbertos($query)
    {
        return $query->whereIn('status', ['novo', 'pendente', 'em_analise', 'aguardando_resposta']);
    }

    /**
     * Tickets fechados (resolvido, fechado)
     */
    public function scopeFechados($query)
    {
        return $query->whereIn('status', ['resolvido', 'fechado']);
    }

    /**
     * Apenas denúncias
     */
    public function scopeDenuncias($query)
    {
        return $query->where('categoria', 'denuncia');
    }

    /**
     * Tickets novos (sem atribuição)
     */
    public function scopeNovos($query)
    {
        return $query->where('status', 'novo')->whereNull('atribuido_a');
    }

    /**
     * Tickets atribuídos a um staff
     */
    public function scopeAtribuidosA($query, $usuarioId)
    {
        return $query->where('atribuido_a', $usuarioId);
    }

    /**
     * Tickets marcados como spam
     */
    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    /**
     * Tickets por prioridade
     */
    public function scopePorPrioridade($query, $prioridade)
    {
        return $query->where('prioridade', $prioridade);
    }

    // ========== MÉTODOS ÚTEIS ==========

    /**
     * Gerar número único do ticket
     */
    public static function gerarNumeroTicket()
    {
        $ano = date('Y');
        $ultimoTicket = self::where('numero_ticket', 'like', "TICK-{$ano}-%")
            ->orderBy('id', 'desc')
            ->first();

        $numero = $ultimoTicket ? (int)substr($ultimoTicket->numero_ticket, -6) + 1 : 1;
        
        return sprintf('TICK-%s-%06d', $ano, $numero);
    }

    /**
     * Verificar se usuário pode ver o ticket
     */
    public function podeVer(Usuario $usuario)
    {
        // Staff sempre pode ver
        if ($usuario->isStaff()) {
            return true;
        }

        // Usuário criador pode ver se visível
        if ($this->usuario_id === $usuario->id && $this->visivel_usuario) {
            return true;
        }

        return false;
    }

    /**
     * Verificar se usuário pode editar
     */
    public function podeEditar(Usuario $usuario)
    {
        // Apenas staff pode editar
        return $usuario->isStaff();
    }

    /**
     * Atribuir ticket a um staff
     */
    public function atribuir(Usuario $staff, Usuario $atribuidoPor = null)
    {
        if (!$staff->isStaff()) {
            throw new \Exception('Apenas staff pode ser atribuído a tickets.');
        }

        $this->update([
            'atribuido_a' => $staff->id,
            'status' => $this->status === 'novo' ? 'em_analise' : $this->status
        ]);

        // Registrar no histórico
        $this->registrarAcao('atribuido', [
            'atribuido_a' => $staff->username,
            'atribuido_por' => $atribuidoPor ? $atribuidoPor->username : 'Sistema'
        ], $atribuidoPor);

        // Notificar o staff
        $this->notificarUsuario($staff->id, 'ticket_atribuido', "Ticket #{$this->numero_ticket} foi atribuído a você.");

        Log::info('Ticket atribuído', [
            'ticket_id' => $this->id,
            'staff_id' => $staff->id,
            'atribuido_por' => $atribuidoPor ? $atribuidoPor->id : null
        ]);

        return $this;
    }

    /**
     * Alterar status do ticket
     */
    public function alterarStatus($novoStatus, Usuario $usuario, $observacao = null)
    {
        $statusAntigo = $this->status;
        
        $this->update(['status' => $novoStatus]);

        if ($novoStatus === 'fechado' || $novoStatus === 'resolvido') {
            $this->update(['data_fechamento' => now()]);
        }

        // Registrar no histórico
        $this->registrarAcao('status_alterado', [
            'status_anterior' => $statusAntigo,
            'status_novo' => $novoStatus
        ], $usuario, $observacao);

        // Notificar usuário
        $this->notificarUsuario(
            $this->usuario_id,
            'status_alterado',
            "Status do ticket #{$this->numero_ticket} alterado para: " . $this->getStatusLabel()
        );

        Log::info('Status do ticket alterado', [
            'ticket_id' => $this->id,
            'status_anterior' => $statusAntigo,
            'status_novo' => $novoStatus,
            'usuario_id' => $usuario->id
        ]);

        return $this;
    }

    /**
     * Alterar prioridade
     */
    public function alterarPrioridade($novaPrioridade, Usuario $usuario, $observacao = null)
    {
        $prioridadeAnterior = $this->prioridade;
        
        $this->update(['prioridade' => $novaPrioridade]);

        // Registrar no histórico
        $this->registrarAcao('prioridade_alterada', [
            'prioridade_anterior' => $prioridadeAnterior,
            'prioridade_nova' => $novaPrioridade
        ], $usuario, $observacao);

        Log::info('Prioridade do ticket alterada', [
            'ticket_id' => $this->id,
            'prioridade_anterior' => $prioridadeAnterior,
            'prioridade_nova' => $novaPrioridade,
            'usuario_id' => $usuario->id
        ]);

        return $this;
    }

    /**
     * Registrar ação no histórico
     */
    public function registrarAcao($acao, $dados = [], Usuario $usuario = null, $observacao = null)
    {
        TicketHistorico::create([
            'ticket_id' => $this->id,
            'usuario_id' => $usuario ? $usuario->id : null,
            'acao' => $acao,
            'dados_novos' => $dados,
            'observacao' => $observacao,
            'ip_address' => request()->ip()
        ]);
    }

    /**
     * Notificar usuário sobre ação no ticket
     */
    public function notificarUsuario($usuarioId, $tipo, $mensagem)
    {
        TicketNotificacao::create([
            'ticket_id' => $this->id,
            'usuario_id' => $usuarioId,
            'tipo' => $tipo,
            'mensagem' => $mensagem
        ]);
    }

    /**
     * Incrementar visualizações
     */
    public function incrementarVisualizacoes()
    {
        $this->increment('visualizacoes');
    }

    /**
     * Verificar se ticket está aberto
     */
    public function estaAberto()
    {
        return in_array($this->status, ['novo', 'pendente', 'em_analise', 'aguardando_resposta']);
    }

    /**
     * Verificar se é denúncia
     */
    public function ehDenuncia()
    {
        return $this->categoria === 'denuncia';
    }

    /**
     * Obter label do status
     */
    public function getStatusLabel()
    {
        $labels = [
            'novo' => 'Novo',
            'pendente' => 'Pendente',
            'em_analise' => 'Em Análise',
            'aguardando_resposta' => 'Aguardando Resposta',
            'resolvido' => 'Resolvido',
            'fechado' => 'Fechado',
            'spam' => 'Spam'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Obter label da prioridade
     */
    public function getPrioridadeLabel()
    {
        $labels = [
            'baixa' => 'Baixa',
            'normal' => 'Normal',
            'alta' => 'Alta',
            'urgente' => 'Urgente'
        ];

        return $labels[$this->prioridade] ?? $this->prioridade;
    }

    /**
     * Obter cor da prioridade
     */
    public function getPrioridadeCor()
    {
        $cores = [
            'baixa' => 'gray',
            'normal' => 'blue',
            'alta' => 'orange',
            'urgente' => 'red'
        ];

        return $cores[$this->prioridade] ?? 'gray';
    }

    /**
     * Obter label da categoria
     */
    public function getCategoriaLabel()
    {
        $labels = [
            'duvida' => 'Dúvida',
            'denuncia' => 'Denúncia',
            'problema_tecnico' => 'Problema Técnico',
            'sugestao' => 'Sugestão',
            'outro' => 'Outro'
        ];

        return $labels[$this->categoria] ?? $this->categoria;
    }

    /**
     * Tempo decorrido desde abertura
     */
    public function getTempoAberto()
    {
        return $this->data_abertura->diffForHumans();
    }
}