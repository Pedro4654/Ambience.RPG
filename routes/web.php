<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SalaController;
use Illuminate\Support\Facades\Route;

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
    // Redirecionar usuários autenticados para o sistema de salas
    if (auth()->check()) {
        return redirect()->route('salas.index');
    }

    // Usuários não autenticados para login
    return redirect()->route('usuarios.login');
});

// ==================== ROTAS PÚBLICAS (SEM AUTENTICAÇÃO) ====================

// Rotas de autenticação
Route::get('/login', [UsuarioController::class, 'loginForm'])->name('usuarios.login');
Route::post('/login', [UsuarioController::class, 'login']);
Route::get('/cadastro', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');

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

        Route::get('/salas/websocket-config', [SalaController::class, 'getWebSocketConfig'])->middleware('auth');


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