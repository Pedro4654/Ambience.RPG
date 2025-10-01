<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para a tabela 'permissoes_sala'  
 * Gerencia permissões granulares de usuários em salas específicas
 */
class PermissaoSala extends Model
{
    use HasFactory;

    protected $table = 'permissoes_sala';
    protected $primaryKey = 'id';
    public $timestamps = false; // Tabela sem timestamps automáticos

    /**
     * Campos que podem ser preenchidos via mass assignment
     */
    protected $fillable = [
        'sala_id',
        'usuario_id',
        'pode_criar_conteudo',
        'pode_editar_grid', 
        'pode_iniciar_sessao',
        'pode_moderar_chat',
        'pode_convidar_usuarios'
    ];

    /**
     * Campos que devem ser convertidos para tipos específicos
     */
    protected $casts = [
        'pode_criar_conteudo' => 'boolean',
        'pode_editar_grid' => 'boolean',
        'pode_iniciar_sessao' => 'boolean',
        'pode_moderar_chat' => 'boolean',
        'pode_convidar_usuarios' => 'boolean'
    ];

    // ==================== RELACIONAMENTOS ====================

    /**
     * Relacionamento: Permissão pertence a uma sala
     */
    public function sala(): BelongsTo
    {
        return $this->belongsTo(Sala::class, 'sala_id');
    }

    /**
     * Relacionamento: Permissão pertence a um usuário
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // ==================== MÉTODOS DE PERMISSÃO ====================

    /**
     * Verificar se tem todas as permissões (admin total)
     */
    public function temTodasPermissoes(): bool
    {
        return $this->pode_criar_conteudo &&
               $this->pode_editar_grid &&
               $this->pode_iniciar_sessao &&
               $this->pode_moderar_chat &&
               $this->pode_convidar_usuarios;
    }

    /**
     * Verificar se não tem nenhuma permissão
     */
    public function naoTemPermissoes(): bool
    {
        return !$this->pode_criar_conteudo &&
               !$this->pode_editar_grid &&
               !$this->pode_iniciar_sessao &&
               !$this->pode_moderar_chat &&
               !$this->pode_convidar_usuarios;
    }

    /**
     * Verificar se tem permissões de moderação
     */
    public function temPermissoesModerador(): bool
    {
        return $this->pode_moderar_chat &&
               $this->pode_convidar_usuarios;
    }

    /**
     * Verificar se tem permissões de mestre/admin
     */
    public function temPermissoesMestre(): bool
    {
        return $this->pode_editar_grid &&
               $this->pode_iniciar_sessao &&
               $this->temPermissoesModerador();
    }

    /**
     * Contar total de permissões ativas
     */
    public function contarPermissoesAtivas(): int
    {
        $contador = 0;
        
        if ($this->pode_criar_conteudo) $contador++;
        if ($this->pode_editar_grid) $contador++;
        if ($this->pode_iniciar_sessao) $contador++;
        if ($this->pode_moderar_chat) $contador++;
        if ($this->pode_convidar_usuarios) $contador++;

        return $contador;
    }

    /**
     * Obter lista de permissões ativas
     */
    public function getPermissoesAtivas(): array
    {
        $permissoes = [];

        if ($this->pode_criar_conteudo) $permissoes[] = 'Criar Conteúdo';
        if ($this->pode_editar_grid) $permissoes[] = 'Editar Grid';
        if ($this->pode_iniciar_sessao) $permissoes[] = 'Iniciar Sessão';
        if ($this->pode_moderar_chat) $permissoes[] = 'Moderar Chat';
        if ($this->pode_convidar_usuarios) $permissoes[] = 'Convidar Usuários';

        return $permissoes;
    }

    /**
     * Obter nível de acesso baseado nas permissões
     */
    public function getNivelAcesso(): string
    {
        $total = $this->contarPermissoesAtivas();

        if ($total === 5) return 'Admin Completo';
        if ($total >= 3) return 'Moderador';
        if ($total >= 1) return 'Membro Ativo';
        
        return 'Membro Básico';
    }

    /**
     * Obter cor do nível de acesso
     */
    public function getCorNivelAcesso(): string
    {
        $nivel = $this->getNivelAcesso();

        $cores = [
            'Admin Completo' => '#dc3545',   // Vermelho
            'Moderador' => '#ffc107',        // Amarelo
            'Membro Ativo' => '#28a745',     // Verde
            'Membro Básico' => '#6c757d'     // Cinza
        ];

        return $cores[$nivel] ?? '#6c757d';
    }

    // ==================== MÉTODOS DE CONFIGURAÇÃO ====================

    /**
     * Conceder todas as permissões (para mestre/admin)
     */
    public function concederTodasPermissoes(): bool
    {
        $this->pode_criar_conteudo = true;
        $this->pode_editar_grid = true;
        $this->pode_iniciar_sessao = true;
        $this->pode_moderar_chat = true;
        $this->pode_convidar_usuarios = true;

        return $this->save();
    }

    /**
     * Revogar todas as permissões
     */
    public function revogarTodasPermissoes(): bool
    {
        $this->pode_criar_conteudo = false;
        $this->pode_editar_grid = false;
        $this->pode_iniciar_sessao = false;
        $this->pode_moderar_chat = false;
        $this->pode_convidar_usuarios = false;

        return $this->save();
    }

    /**
     * Definir permissões padrão para membro comum
     */
    public function definirPermissoesMembro(): bool
    {
        $this->pode_criar_conteudo = true;
        $this->pode_editar_grid = false;
        $this->pode_iniciar_sessao = false;
        $this->pode_moderar_chat = false;
        $this->pode_convidar_usuarios = false;

        return $this->save();
    }

    /**
     * Definir permissões para moderador
     */
    public function definirPermissoesModerador(): bool
    {
        $this->pode_criar_conteudo = true;
        $this->pode_editar_grid = false;
        $this->pode_iniciar_sessao = false;
        $this->pode_moderar_chat = true;
        $this->pode_convidar_usuarios = true;

        return $this->save();
    }

    /**
     * Definir permissões para mestre
     */
    public function definirPermissoesMestre(): bool
    {
        return $this->concederTodasPermissoes();
    }

    /**
     * Aplicar template de permissões baseado no papel
     */
    public function aplicarTemplatePorPapel($papel): bool
    {
        switch ($papel) {
            case 'membro':
                return $this->definirPermissoesMembro();
            case 'admin_sala':
                return $this->definirPermissoesModerador();
            case 'mestre':
                return $this->definirPermissoesMestre();
            default:
                return $this->definirPermissoesMembro();
        }
    }

    // ==================== MÉTODOS ESTÁTICOS ====================

    /**
     * Obter permissões de um usuário em uma sala específica
     */
    public static function getPermissoesUsuario($salaId, $userId)
    {
        return static::where('sala_id', $salaId)
                    ->where('usuario_id', $userId)
                    ->first();
    }

    /**
     * Verificar se usuário tem permissão específica
     */
    public static function usuarioTemPermissao($salaId, $userId, $permissao): bool
    {
        $permissoes = static::getPermissoesUsuario($salaId, $userId);
        
        if (!$permissoes) {
            return false;
        }

        return $permissoes->{$permissao} ?? false;
    }

    /**
     * Criar permissões padrão para novo participante
     */
    public static function criarPermissoesPadrao($salaId, $userId, $papel = 'membro')
    {
        $permissoes = static::create([
            'sala_id' => $salaId,
            'usuario_id' => $userId,
            'pode_criar_conteudo' => true,
            'pode_editar_grid' => false,
            'pode_iniciar_sessao' => false,
            'pode_moderar_chat' => false,
            'pode_convidar_usuarios' => false
        ]);

        // Aplicar template baseado no papel
        $permissoes->aplicarTemplatePorPapel($papel);

        return $permissoes;
    }

    /**
     * Obter todas as permissões de uma sala
     */
    public static function getPermissoesDaSala($salaId)
    {
        return static::where('sala_id', $salaId)
                    ->with('usuario')
                    ->get();
    }

    /**
     * Obter usuários com permissão específica em uma sala
     */
    public static function getUsuariosComPermissao($salaId, $permissao)
    {
        return static::where('sala_id', $salaId)
                    ->where($permissao, true)
                    ->with('usuario')
                    ->get();
    }

    /**
     * Contar usuários com todas as permissões em uma sala
     */
    public static function contarAdminsDaSala($salaId): int
    {
        return static::where('sala_id', $salaId)
                    ->where('pode_criar_conteudo', true)
                    ->where('pode_editar_grid', true)
                    ->where('pode_iniciar_sessao', true)
                    ->where('pode_moderar_chat', true)
                    ->where('pode_convidar_usuarios', true)
                    ->count();
    }

    /**
     * Remover todas as permissões de um usuário de uma sala
     */
    public static function removerPermissoesUsuario($salaId, $userId): bool
    {
        return static::where('sala_id', $salaId)
                    ->where('usuario_id', $userId)
                    ->delete();
    }

    /**
     * Sincronizar permissões com papel do participante
     */
    public static function sincronizarComPapel($salaId, $userId, $novoPapel): bool
    {
        $permissoes = static::getPermissoesUsuario($salaId, $userId);
        
        if (!$permissoes) {
            $permissoes = static::criarPermissoesPadrao($salaId, $userId, $novoPapel);
            return true;
        }

        return $permissoes->aplicarTemplatePorPapel($novoPapel);
    }

    /**
     * Obter estatísticas de permissões de uma sala
     */
    public static function getEstatisticasDaSala($salaId): array
    {
        $permissoes = static::where('sala_id', $salaId)->get();

        return [
            'total_usuarios' => $permissoes->count(),
            'admins_completos' => $permissoes->filter(function($p) { 
                return $p->temTodasPermissoes(); 
            })->count(),
            'moderadores' => $permissoes->filter(function($p) { 
                return $p->temPermissoesModerador() && !$p->temTodasPermissoes(); 
            })->count(),
            'membros_ativos' => $permissoes->filter(function($p) { 
                return $p->contarPermissoesAtivas() > 0 && !$p->temPermissoesModerador(); 
            })->count(),
            'sem_permissoes' => $permissoes->filter(function($p) { 
                return $p->naoTemPermissoes(); 
            })->count()
        ];
    }

    /**
     * Obter mapeamento completo de permissões
     */
    public static function getMapaPermissoes(): array
    {
        return [
            'pode_criar_conteudo' => [
                'nome' => 'Criar Conteúdo',
                'descricao' => 'Permite criar fichas, itens e outros conteúdos',
                'icone' => '📝'
            ],
            'pode_editar_grid' => [
                'nome' => 'Editar Grid',
                'descricao' => 'Permite modificar o grid de jogo e mover tokens',
                'icone' => '🗺️'
            ],
            'pode_iniciar_sessao' => [
                'nome' => 'Iniciar Sessão',
                'descricao' => 'Permite iniciar e gerenciar sessões de jogo',
                'icone' => '🎮'
            ],
            'pode_moderar_chat' => [
                'nome' => 'Moderar Chat',
                'descricao' => 'Permite moderar mensagens e gerenciar o chat',
                'icone' => '🛡️'
            ],
            'pode_convidar_usuarios' => [
                'nome' => 'Convidar Usuários',
                'descricao' => 'Permite enviar convites para novos participantes',
                'icone' => '👥'
            ]
        ];
    }
}