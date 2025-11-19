<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SalaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SavedPostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ModeracaoUsuarioController;
use App\Http\Controllers\IpBanRecursoController;
use App\Http\Controllers\ModerationController;


/*
|--------------------------------------------------------------------------
| Rotas Web - Sistema Ambience RPG
|--------------------------------------------------------------------------
| 
| Implementação completa do sistema de salas de RPG
| Todas as rotas preparadas para integração com React
|
*/

// ==================== ROTA PADRÃO ====================
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/ban-ip', [App\Http\Controllers\ModeracaoUsuarioController::class, 'mostrarIpBan'])
    ->name('public.ip_ban'); // rota pública para mostrar IP ban para visitantes/guests

    // ========================================
// ROTAS PÚBLICAS DE RECURSO DE IP BAN
// ========================================
Route::prefix('ip-ban')->name('ip-ban.')->group(function () {
    // Formulário de recurso (público, não requer autenticação)
    Route::get('/recurso', [IpBanRecursoController::class, 'showRecursoForm'])
        ->name('recurso.form');
    
    // Enviar recurso (público)
    Route::post('/recurso', [IpBanRecursoController::class, 'submitRecurso'])
        ->name('recurso.submit');
    
    // Verificar status do recurso (público)
    Route::get('/recurso/status/{numero_ticket}', [IpBanRecursoController::class, 'verificarStatus'])
        ->name('recurso.status');
});

// ==================== ROTAS PÚBLICAS DE PUNIÇÕES ====================
// Rotas públicas de punições (não requerem permissões, apenas autenticação)
// ==================== ROTAS PÚBLICAS DE PUNIÇÕES ====================
// Estas rotas são acessíveis mesmo para usuários punidos
Route::middleware(['auth'])->prefix('moderacao')->name('moderacao.')->group(function () {
    
    /**
     * Tela de Warning
     * GET /moderacao/warning
     */
    Route::get('/warning', [ModeracaoUsuarioController::class, 'mostrarWarning'])
        ->name('warning.show');
    
    /**
     * Reativar conta após warning
     * POST /moderacao/warning/reativar
     */
    Route::post('/warning/reativar', [ModeracaoUsuarioController::class, 'reativarAposWarning'])
        ->name('warning.reativar');
    
    /**
     * Tela de Ban
     * GET /moderacao/ban
     */
    Route::get('/ban', [ModeracaoUsuarioController::class, 'mostrarBan'])
        ->name('ban.show');
    
    /**
     * Tela de IP Ban
     * GET /moderacao/ip-ban
     */
    Route::get('/ip-ban', [ModeracaoUsuarioController::class, 'mostrarIpBan'])
        ->name('ip-ban.show');
    
    /**
     * Tela de Account Deleted
     * GET /moderacao/account-deleted
     */
    Route::get('/account-deleted', [ModeracaoUsuarioController::class, 'mostrarAccountDeleted'])
        ->name('account-deleted.show');
});

// ==================== ROTAS DE APLICAÇÃO DE PUNIÇÕES (APENAS STAFF) ====================
Route::middleware(['auth', App\Http\Middleware\VerificarStaff::class])
    ->prefix('moderacao/usuarios')
    ->name('moderacao.usuarios.')
    ->group(function () {
    
    // ========== DASHBOARD ==========
    Route::get('/dashboard', [ModeracaoUsuarioController::class, 'dashboard'])
        ->name('dashboard');
    
    // ========== CRUD DE USUÁRIOS ==========
    Route::get('/', [ModeracaoUsuarioController::class, 'index'])
        ->name('index');
    
    Route::get('/{id}', [ModeracaoUsuarioController::class, 'show'])
        ->name('show')
        ->where('id', '[0-9]+');
    
    Route::get('/{id}/editar', [ModeracaoUsuarioController::class, 'edit'])
        ->name('edit')
        ->where('id', '[0-9]+');
    
    Route::put('/{id}', [ModeracaoUsuarioController::class, 'update'])
        ->name('update')
        ->where('id', '[0-9]+');
    
    // ========== APLICAR PUNIÇÕES ==========
    Route::post('/{id}/warning', [ModeracaoUsuarioController::class, 'aplicarWarning'])
        ->name('warning');
    
    Route::post('/{id}/ban-temporario', [ModeracaoUsuarioController::class, 'aplicarBanTemporario'])
        ->name('ban-temporario');
    
    Route::post('/{id}/perma-ban', [ModeracaoUsuarioController::class, 'aplicarPermaBan'])
        ->name('perma-ban');
    
    Route::post('/{id}/ip-ban', [ModeracaoUsuarioController::class, 'aplicarIpBan'])
        ->middleware(App\Http\Middleware\VerificarAdmin::class)
        ->name('ip-ban');
    
    Route::post('/{id}/deletar-conta', [ModeracaoUsuarioController::class, 'deletarConta'])
        ->name('deletar-conta');
    
    // ========== REATIVAR USUÁRIO (Apenas Admins) ==========
    Route::post('/{id}/reativar', [ModeracaoUsuarioController::class, 'reativar'])
        ->middleware(App\Http\Middleware\VerificarAdmin::class)
        ->name('reativar');

        Route::post('{id}/remover-ip-ban', [ModeracaoUsuarioController::class, 'removerIpBan'])
            ->name('remover-ip-ban')
            ->middleware('admin');
});

Route::middleware(['auth'])->prefix('suporte')->name('suporte.')->group(function () {
    
    // ========== ROTAS PARA USUÁRIOS NORMAIS ==========
    
    /**
     * Dashboard de suporte (lista de tickets do usuário)
     * GET /suporte
     */
    Route::get('/', [App\Http\Controllers\SuporteController::class, 'index'])
        ->name('index');
    
    /**
     * Formulário de criação de ticket
     * GET /suporte/criar
     */
    Route::get('/criar', [App\Http\Controllers\SuporteController::class, 'create'])
        ->name('create');
    
    /**
     * Criar novo ticket
     * POST /suporte
     */
    Route::post('/', [App\Http\Controllers\SuporteController::class, 'store'])
        ->name('store');
    
    /**
     * Visualizar ticket específico
     * GET /suporte/{id}
     */
    Route::get('/{id}', [App\Http\Controllers\SuporteController::class, 'show'])
        ->name('show')
        ->where('id', '[0-9]+');
    
    /**
     * Adicionar resposta ao ticket
     * POST /suporte/{id}/responder
     */
    Route::post('/{id}/responder', [App\Http\Controllers\SuporteController::class, 'responder'])
        ->name('responder')
        ->where('id', '[0-9]+');
    
    /**
     * Buscar usuários para denúncia (AJAX)
     * GET /suporte/buscar-usuarios?termo=username
     */
    Route::get('/api/buscar-usuarios', [App\Http\Controllers\SuporteController::class, 'buscarUsuarios'])
        ->name('buscar-usuarios');
    
    /**
     * Download de anexo
     * GET /suporte/anexos/{id}/download
     */
    Route::get('/anexos/{id}/download', [App\Http\Controllers\SuporteController::class, 'downloadAnexo'])
        ->name('anexos.download')
        ->where('id', '[0-9]+');
    
    
    // ========== ROTAS PARA MODERADORES/ADMINS ==========
    // Protegidas pelo middleware VerificarStaff
    
    Route::middleware([App\Http\Middleware\VerificarStaff::class])
        ->prefix('moderacao')
        ->name('moderacao.')
        ->group(function () {
        
        /**
         * Dashboard de moderação (estatísticas)
         * GET /suporte/moderacao/dashboard
         */
        Route::get('/dashboard', [App\Http\Controllers\ModeracaoSuporteController::class, 'dashboard'])
            ->name('dashboard');
        
        /**
         * Painel de moderação (lista de todos os tickets com filtros)
         * GET /suporte/moderacao
         */
        Route::get('/', [App\Http\Controllers\ModeracaoSuporteController::class, 'index'])
            ->name('index');
        
        /**
         * Atribuir ticket a um staff
         * POST /suporte/moderacao/{id}/atribuir
         */
        Route::post('/{id}/atribuir', [App\Http\Controllers\ModeracaoSuporteController::class, 'atribuir'])
            ->name('atribuir')
            ->where('id', '[0-9]+');
        
        /**
         * Alterar status do ticket
         * POST /suporte/moderacao/{id}/status
         */
        Route::post('/{id}/status', [App\Http\Controllers\ModeracaoSuporteController::class, 'alterarStatus'])
            ->name('status')
            ->where('id', '[0-9]+');
        
        /**
         * Alterar prioridade do ticket
         * POST /suporte/moderacao/{id}/prioridade
         */
        Route::post('/{id}/prioridade', [App\Http\Controllers\ModeracaoSuporteController::class, 'alterarPrioridade'])
            ->name('prioridade')
            ->where('id', '[0-9]+');
        
        /**
         * Fechar ticket
         * POST /suporte/moderacao/{id}/fechar
         */
        Route::post('/{id}/fechar', [App\Http\Controllers\ModeracaoSuporteController::class, 'fechar'])
            ->name('fechar')
            ->where('id', '[0-9]+');
        
        /**
         * Reabrir ticket
         * POST /suporte/moderacao/{id}/reabrir
         */
        Route::post('/{id}/reabrir', [App\Http\Controllers\ModeracaoSuporteController::class, 'reabrir'])
            ->name('reabrir')
            ->where('id', '[0-9]+');
        
        /**
         * Marcar como spam
         * POST /suporte/moderacao/{id}/marcar-spam
         */
        Route::post('/{id}/marcar-spam', [App\Http\Controllers\ModeracaoSuporteController::class, 'marcarSpam'])
            ->name('marcar-spam')
            ->where('id', '[0-9]+');
    });
});

// ============================================================
// ROTAS API PARA INTEGRAÇÃO COM REACT/VUE (OPCIONAL)
// ============================================================

Route::middleware(['auth'])->prefix('api/suporte')->name('api.suporte.')->group(function () {
    
    // Lista de tickets do usuário
    Route::get('/tickets', [App\Http\Controllers\SuporteController::class, 'index']);
    
    // Criar ticket
    Route::post('/tickets', [App\Http\Controllers\SuporteController::class, 'store']);
    
    // Ver ticket específico
    Route::get('/tickets/{id}', [App\Http\Controllers\SuporteController::class, 'show']);
    
    // Responder ticket
    Route::post('/tickets/{id}/responder', [App\Http\Controllers\SuporteController::class, 'responder']);
    
    // Buscar usuários
    Route::get('/buscar-usuarios', [App\Http\Controllers\SuporteController::class, 'buscarUsuarios']);
    
    // Rotas de moderação (apenas staff)
    Route::middleware([App\Http\Middleware\VerificarStaff::class])->group(function () {
        Route::get('/moderacao/tickets', [App\Http\Controllers\ModeracaoSuporteController::class, 'index']);
        Route::post('/moderacao/tickets/{id}/atribuir', [App\Http\Controllers\ModeracaoSuporteController::class, 'atribuir']);
        Route::post('/moderacao/tickets/{id}/status', [App\Http\Controllers\ModeracaoSuporteController::class, 'alterarStatus']);
        Route::post('/moderacao/tickets/{id}/prioridade', [App\Http\Controllers\ModeracaoSuporteController::class, 'alterarPrioridade']);
        Route::post('/moderacao/tickets/{id}/fechar', [App\Http\Controllers\ModeracaoSuporteController::class, 'fechar']);
        Route::post('/moderacao/tickets/{id}/reabrir', [App\Http\Controllers\ModeracaoSuporteController::class, 'reabrir']);
        Route::get('/moderacao/dashboard', [App\Http\Controllers\ModeracaoSuporteController::class, 'dashboard']);
    });
});

// Rotas públicas apenas para visitantes (se estiver logado, middleware redireciona para 'home')
Route::middleware('guest.custom')->group(function () {
    Route::get('/login', [UsuarioController::class, 'loginForm'])->name('usuarios.login');
    Route::post('/login', [UsuarioController::class, 'login'])->name('usuarios.login.post');

    Route::get('/cadastro', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');

    // Recuperação de senha também como guest
    Route::get('/esqueci-minha-senha', [UsuarioController::class, 'showForgotPasswordForm'])
        ->name('usuarios.forgot.form');
    Route::post('/enviar-token-recuperacao', [UsuarioController::class, 'sendResetToken'])
        ->name('usuarios.forgot.send');
    Route::get('/verificar-token', [UsuarioController::class, 'showVerifyTokenForm'])
        ->name('usuarios.verify.token.form');
    Route::post('/verificar-token', [UsuarioController::class, 'verifyToken'])
        ->name('usuarios.verify.token');
    Route::get('/definir-nova-senha', [UsuarioController::class, 'showResetPasswordForm'])
        ->name('usuarios.reset.password.form');
    Route::post('/redefinir-senha', [UsuarioController::class, 'resetPassword'])
        ->name('usuarios.reset.password');
    Route::post('/reenviar-token', [UsuarioController::class, 'resendToken'])
        ->name('usuarios.resend.token');
});

// ========== ROTAS PÚBLICAS DE CONVITE (FORA DO MIDDLEWARE AUTH) ==========

/**
 * Exibir página de convite (estilo Discord)
 * GET /convite/{codigo}
 * Rota pública - não requer autenticação para visualizar
 */
Route::get('/convite/{codigo}', [SalaController::class, 'mostrarConvite'])
    ->name('convite.mostrar')
    ->where('codigo', '[a-zA-Z0-9]{10}');

/**
 * Aceitar convite via link
 * POST /convite/{codigo}/aceitar
 * Requer autenticação
 */
Route::post('/convite/{codigo}/aceitar', [SalaController::class, 'aceitarConviteLink'])
    ->middleware('auth')
    ->name('convite.aceitar')
    ->where('codigo', '[a-zA-Z0-9]{10}');


// Rotas de Moderação
Route::post('/moderate', [ModerationController::class, 'moderate'])->name('moderate');

Route::post('/salas/{id}/iniciar-sessao', [SalaController::class, 'iniciarSessao'])
    ->name('salas.iniciar-sessao')
    ->where('id', '[0-9]+');

/**
 * Visualizar sala de sessão
 * GET /sessoes/{id}
 */
Route::get('/sessoes/{id}', [SalaController::class, 'showSessao'])
    ->name('sessoes.show')
    ->where('id', '[0-9]+');

/**
 * Obter sessão ativa da sala
 * GET /salas/{id}/sessao-ativa
 */
Route::get('/salas/{id}/sessao-ativa', [SalaController::class, 'getSessaoAtiva'])
    ->name('salas.sessao-ativa')
    ->where('id', '[0-9]+');

/**
 * Finalizar sessão
 * POST /sessoes/{id}/finalizar
 */
Route::post('/sessoes/{id}/finalizar', [SalaController::class, 'finalizarSessao'])
    ->name('sessoes.finalizar')
    ->where('id', '[0-9]+');

/**
 * Entrar em uma sessão já iniciada
 * POST /sessoes/{id}/entrar
 */
Route::post('/sessoes/{id}/entrar', [SalaController::class, 'entrarNaSessao'])
    ->name('sessoes.entrar')
    ->where('id', '[0-9]+');


// ==================== ROTAS PROTEGIDAS (COM AUTENTICAÇÃO) ====================
Route::middleware(['auth', App\Http\Middleware\VerificarAutenticacao::class])->group(function () {

    // ========== ROTAS DE USUÁRIOS ==========

    // Logout
    Route::post('/logout', [UsuarioController::class, 'logout'])->name('usuarios.logout');

    // Rota específica para deletar avatar - DEVE vir ANTES das outras rotas de usuários
    Route::delete('/usuarios/{usuario}/avatar', [UsuarioController::class, 'deleteAvatar'])
        ->name('usuarios.deleteAvatar');

    // Rotas de usuários
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.show');
    Route::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // ========== ROTAS DO SISTEMA DE SALAS ==========

    /**
     * Dashboard principal do sistema de salas
     * GET /salas - Exibe interface completa com minhas salas e salas públicas
     * Suporte completo a AJAX/JSON para integração React
     */
    Route::get('/salas', [SalaController::class, 'index'])->name('salas.index');

    // Dentro do grupo de rotas protegidas
    Route::get('/salas/{id}/online-users', [SalaController::class, 'getOnlineUsers'])
        ->name('salas.online-users')
        ->where('id', '[0-9]+');

    Route::get('/api/salas/data', [SalaController::class, 'getSalasAjax'])
        ->middleware(['auth'])
        ->name('salas.ajax.data');

    /**
     * Criar nova sala
     * POST /salas - Cria sala (pública, privada ou apenas convite)
     * Validação completa e tratamento de erros
     */
    Route::post('/salas', [SalaController::class, 'store'])->name('salas.store');

    Route::get('/salas/{id}/info', [SalaController::class, 'infoSala'])->name('salas.info');

    /**
     * Entrar em sala por ID
     * POST /salas/entrar - Entrada em sala com validação de senha e convites
     * Suporte a todos os tipos de sala
     */
    Route::post('/salas/entrar', [SalaController::class, 'entrarSala'])->name('salas.entrar');

    /**
     * Visualizar sala específica
     * GET /salas/{id} - Interface da sala com teste WebSocket
     * Verificação de permissões e status em tempo real
     */
    Route::get('/salas/{id}', [SalaController::class, 'show'])->name('salas.show')
        ->where('id', '[0-9]+'); // Apenas números

    Route::post('/salas/{id}/banner', [App\Http\Controllers\SalaController::class, 'uploadBanner'])
        ->name('salas.banner.upload')
        ->where('id', '[0-9]+');

    Route::post('/salas/{id}/banner/color', [App\Http\Controllers\SalaController::class, 'setBannerColor'])
        ->name('salas.banner.color')
        ->where('id', '[0-9]+');

    Route::delete('/salas/{id}/banner', [App\Http\Controllers\SalaController::class, 'removeBanner'])
        ->name('salas.banner.remove')
        ->where('id', '[0-9]+');


    // Rotas de foto de perfil da sala
    Route::post('/salas/{id}/profile-photo', [App\Http\Controllers\SalaController::class, 'uploadProfilePhoto'])
        ->name('salas.profile.upload')
        ->where('id', '[0-9]+');

    Route::post('/salas/{id}/profile-photo/color', [App\Http\Controllers\SalaController::class, 'setProfilePhotoColor'])
        ->name('salas.profile.color')
        ->where('id', '[0-9]+');

    Route::delete('/salas/{id}/profile-photo', [App\Http\Controllers\SalaController::class, 'removeProfilePhoto'])
        ->name('salas.profile.remove')
        ->where('id', '[0-9]+');
    /**
     * Sair da sala
     * POST /salas/{id}/sair - Remove usuário da sala (exceto criador)
     * Limpeza automática de permissões
     */
    Route::post('/salas/{id}/sair', [SalaController::class, 'sairSala'])->name('salas.sair')
        ->where('id', '[0-9]+');

        // ========== ROTAS CRUD STAFF (APENAS MODERADORES/ADMINS) ==========
    
    /**
     * Editar sala (staff)
     * GET /salas/{id}/staff/edit
     */
    Route::get('/salas/{id}/staff/edit', [SalaController::class, 'staffEdit'])
        ->middleware(App\Http\Middleware\VerificarStaff::class)
        ->name('salas.staff.edit')
        ->where('id', '[0-9]+');

    /**
     * Atualizar sala (staff)
     * PUT /salas/{id}/staff/update
     */
    Route::put('/salas/{id}/staff/update', [SalaController::class, 'staffUpdate'])
        ->middleware(App\Http\Middleware\VerificarStaff::class)
        ->name('salas.staff.update')
        ->where('id', '[0-9]+');

    /**
     * Deletar sala (staff)
     * DELETE /salas/{id}/staff/delete
     */
    Route::delete('/salas/{id}/staff/delete', [SalaController::class, 'staffDestroy'])
        ->middleware(App\Http\Middleware\VerificarStaff::class)
        ->name('salas.staff.delete')
        ->where('id', '[0-9]+');

    /**
     * Ativar/Desativar sala (staff)
     * POST /salas/{id}/staff/toggle-status
     */
    Route::post('/salas/{id}/staff/toggle-status', [SalaController::class, 'staffToggleStatus'])
        ->middleware(App\Http\Middleware\VerificarStaff::class)
        ->name('salas.staff.toggle-status')
        ->where('id', '[0-9]+');

        /**
 * Obter salas desativadas (apenas staff)
 * GET /api/salas/desativadas
 */
Route::get('/api/salas/desativadas', [SalaController::class, 'getSalasDesativadas'])
    ->middleware(App\Http\Middleware\VerificarStaff::class)
    ->name('salas.desativadas');

    Route::get('/api/salas/minhas-desativadas', [SalaController::class, 'getMinhasSalasDesativadas'])
    ->middleware('auth')
    ->name('salas.minhas-desativadas');

    // ========== ROTAS DE LINKS DE CONVITE (ESTILO DISCORD) ==========

    /**
     * Criar novo link de convite
     * POST /salas/{id}/links-convite
     */
    Route::post('/salas/{id}/links-convite', [SalaController::class, 'criarLinkConvite'])
        ->name('salas.links-convite.criar')
        ->where('id', '[0-9]+');

    /**
     * Listar links de convite ativos da sala
     * GET /salas/{id}/links-convite
     */
    Route::get('/salas/{id}/links-convite', [SalaController::class, 'listarLinksConvite'])
        ->name('salas.links-convite.listar')
        ->where('id', '[0-9]+');

    /**
     * Revogar (deletar) link de convite
     * DELETE /salas/{id}/links-convite/{linkId}
     */
    Route::delete('/salas/{id}/links-convite/{linkId}', [SalaController::class, 'revogarLinkConvite'])
        ->name('salas.links-convite.revogar')
        ->where(['id' => '[0-9]+', 'linkId' => '[0-9]+']);

    /**
     * Sistema de convites para salas
     * POST /salas/{id}/convidar - Gera convite com token único e expiração
     * Verificação de permissões granular
     */
    Route::post('/salas/{id}/convidar', [SalaController::class, 'gerarConvite'])->name('salas.convidar')
        ->where('id', '[0-9]+');


    // Exibir permissões do participante (JSON)
    Route::get('/salas/{sala}/participantes/{usuario}/permissoes', [SalaController::class, 'getPermissoesParticipante'])
        ->name('salas.participantes.permissoes.show')
        ->where(['sala' => '[0-9]+', 'usuario' => '[0-9]+']);

    // Atualizar permissões do participante (JSON)
    Route::post('/salas/{sala}/participantes/{usuario}/permissoes', [SalaController::class, 'updatePermissoesParticipante'])
        ->name('salas.participantes.permissoes.update')
        ->where(['sala' => '[0-9]+', 'usuario' => '[0-9]+']);

    // ========== ROTAS DE CONVITES ==========

    /**
     * Aceitar convite via token
     * GET /convites/{token} - Link de convite compartilhável
     * Validação automática de expiração e destinatário
     */
    Route::get('/convites/{token}', [SalaController::class, 'aceitarConvite'])
        ->name('convites.aceitar')
        ->where('token', '[a-zA-Z0-9]{32}'); // Token de 32 caracteres

    // ========== ROTAS DE API PARA INTEGRAÇÃO REACT ==========

    /**
     * Rotas API espelhadas para facilitar integração React
     * Todas retornam JSON e mantêm a mesma funcionalidade
     * Preparadas para desenvolvimento de frontend separado
     */
    Route::prefix('api')->name('api.')->group(function () {

        // API de salas - espelhamento das rotas principais
        Route::get('/salas', [SalaController::class, 'index'])->name('salas.index');
        Route::post('/salas', [SalaController::class, 'store'])->name('salas.store');
        Route::get('/salas/{id}', [SalaController::class, 'show'])->name('salas.show')
            ->where('id', '[0-9]+');
        Route::post('/salas/entrar', [SalaController::class, 'entrarSala'])->name('salas.entrar');
        Route::post('/salas/{id}/sair', [SalaController::class, 'sairSala'])->name('salas.sair')
            ->where('id', '[0-9]+');
        Route::post('/salas/{id}/convidar', [SalaController::class, 'gerarConvite'])->name('salas.convidar')
            ->where('id', '[0-9]+');

        // API de convites
        Route::get('/convites/{token}', [SalaController::class, 'aceitarConvite'])->name('convites.aceitar')
            ->where('token', '[a-zA-Z0-9]{32}');

        // Rotas adicionais para estatísticas e dados complementares
        Route::get('/salas/{id}/participantes', function ($id) {
            $sala = App\Models\Sala::with(['participantes.usuario'])->findOrFail($id);
            return response()->json($sala->participantes);
        })->name('salas.participantes')->where('id', '[0-9]+');

        Route::get('/salas/{id}/permissoes', function ($id) {
            $permissoes = App\Models\PermissaoSala::where('sala_id', $id)
                ->where('usuario_id', auth()->id())
                ->first();
            return response()->json($permissoes);
        })->name('salas.permissoes')->where('id', '[0-9]+');
    });




    // ============================================================
    // ROTAS DA COMUNIDADE (adicionar em routes/web.php)
    // ============================================================

    // ==================== MÓDULO DE COMUNIDADE ====================

    Route::prefix('comunidade')->name('comunidade.')->group(function () {

        // Feed Principal
        Route::get('/', [PostController::class, 'index'])->name('feed');
        Route::get('/feed', [PostController::class, 'index'])->name('feed');

        // Buscar Postagens
        Route::get('/buscar', [PostController::class, 'buscar'])->name('buscar');

        // Criar Postagem
        Route::get('/criar', [PostController::class, 'create'])->name('create');
        Route::post('/criar', [PostController::class, 'store'])->name('store');

        // Visualizar Postagem
        Route::get('/{slug}', [PostController::class, 'show'])->name('post.show');

        // Editar Postagem
        Route::get('/{id}/editar', [PostController::class, 'edit'])->name('post.edit');
        Route::put('/{id}', [PostController::class, 'update'])->name('post.update');

        // Deletar Postagem
        Route::delete('/{id}', [PostController::class, 'destroy'])->name('post.destroy');

        // ========== POSTS SALVOS ==========
        Route::get('/salvos', [SavedPostController::class, 'index'])->name('salvos');
        Route::post('/salvar', [SavedPostController::class, 'store'])->name('salvar');
        Route::delete('/salvar/{post_id}', [SavedPostController::class, 'destroy'])->name('desalvar');

        // ========== LIKES ==========
        Route::post('/curtir', [LikeController::class, 'store'])->name('curtir');
        Route::delete('/curtir/{post_id}', [LikeController::class, 'destroy'])->name('descurtir');


        // ========== COMENTÁRIOS ==========
        Route::post('/comentar', [CommentController::class, 'store'])->name('comentar');
        Route::delete('/comentario/{id}', [CommentController::class, 'destroy'])->name('comentario.destroy');
    });

    // ==================== PERFIL DE USUÁRIO ====================

    Route::prefix('perfil')->name('perfil.')->group(function () {

        // Meu Perfil (Redireciona para perfil do usuário)
        Route::get('/meu-perfil', [ProfileController::class, 'meu_perfil'])->name('meu');

        // Editar Perfil
        Route::get('/editar', [ProfileController::class, 'editarPerfil'])->name('editar');
        Route::put('/editar', [ProfileController::class, 'update'])->name('update');

        // Ver Perfil (Por username)
        Route::get('/{username}', [ProfileController::class, 'show'])->name('show');

        // Seguir / Deixar de Seguir
        Route::post('/{usuario_id}/seguir', [ProfileController::class, 'seguir'])->name('seguir');
        Route::delete('/{usuario_id}/deixar-de-seguir', [ProfileController::class, 'deixar_de_seguir'])
            ->name('deixar_de_seguir');

        // Seguidores e Seguindo
        Route::get('/{usuario_id}/seguidores', [ProfileController::class, 'seguidores'])
            ->name('seguidores');
        Route::get('/{usuario_id}/seguindo', [ProfileController::class, 'seguindo'])
            ->name('seguindo');
    });
});

// API Routes para integração com frontend JavaScript/React

Route::prefix('api/comunidade')->name('api.comunidade.')->middleware('auth')->group(function () {
    Route::get('/feed', [\App\Http\Controllers\PostController::class, 'index']);
    Route::post('/posts', [\App\Http\Controllers\PostController::class, 'store']);
    Route::get('/posts/{slug}', [\App\Http\Controllers\PostController::class, 'show']);
    Route::post('/likes', [\App\Http\Controllers\LikeController::class, 'store']);
    Route::delete('/likes/{post_id}', [\App\Http\Controllers\LikeController::class, 'destroy']);
    Route::post('/comments', [\App\Http\Controllers\CommentController::class, 'store']);
    Route::post('/saved', [\App\Http\Controllers\SavedPostController::class, 'store']);
    Route::delete('/saved/{post_id}', [\App\Http\Controllers\SavedPostController::class, 'destroy']);
    Route::get('/saved', [\App\Http\Controllers\SavedPostController::class, 'index']);
});