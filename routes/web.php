<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SalaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SavedPostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SessaoController;
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

Broadcast::routes(['middleware' => ['web', 'auth']]);

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




// Rotas de Moderação
Route::post('/moderate', [ModerationController::class, 'moderate'])->name('moderate');

// ========== ROTAS DE RECUPERAÇÃO DE SENHA COM TOKEN DE 6 DÍGITOS ==========
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

Route::post('/salas/{id}/iniciar-sessao', [SalaController::class, 'iniciarSessao'])
    ->name('salas.iniciar-sessao')
    ->where('id', '[0-9]+');

/**
 * Visualizar sala de sessão
 * GET /sessoes/{id}
 */


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

Route::middleware(['auth'])->group(function () {
    Route::get('/sessoes/{id}', [SessaoController::class, 'show'])->name('sessoes.show');
});

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





/*
|--------------------------------------------------------------------------
| Observações Importantes para Desenvolvimento
|--------------------------------------------------------------------------
|
| 1. SISTEMA COMPLETO IMPLEMENTADO:
|    ✅ Dashboard de salas com design avançado
|    ✅ Criação de salas (pública, privada, apenas convite)
|    ✅ Sistema de entrada com validação
|    ✅ Interface individual da sala
|    ✅ Sistema de convites com tokens únicos
|    ✅ Permissões granulares por usuário
|    ✅ Logs detalhados para debugging
|    ✅ Preparação para WebSocket
|
| 2. INTEGRAÇÃO REACT PREPARADA:
|    ✅ Todas as rotas retornam JSON quando solicitado
|    ✅ Rotas API duplicadas em /api/
|    ✅ Estrutura de resposta padronizada
|    ✅ CSRF token configurado
|    ✅ Tratamento de erros consistente
|
| 3. SEGURANÇA IMPLEMENTADA:
|    ✅ Middleware de autenticação customizado
|    ✅ Validação de entrada rigorosa
|    ✅ Verificação de permissões granular
|    ✅ Sanitização de dados
|    ✅ Logs de segurança
|
| 4. RECURSOS AVANÇADOS:
|    ✅ Sistema de convites com expiração
|    ✅ Roles diferenciados (membro, admin_sala, mestre)
|    ✅ Interface responsiva e moderna
|    ✅ Indicadores de status em tempo real
|    ✅ Simulação de WebSocket para demonstração
|
| 5. PRÓXIMOS PASSOS PARA PRODUÇÃO:
|    🔄 Implementar WebSocket real (Laravel WebSockets/Pusher)
|    🔄 Adicionar notificações em tempo real
|    🔄 Desenvolver componentes React
|    🔄 Adicionar testes automatizados
|
*/