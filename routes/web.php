<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SalaController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistema Ambience RPG
|--------------------------------------------------------------------------
|
| Rotas da aplicação organizadas por funcionalidade
| Sistema completo de usuários e salas de RPG
|
*/

// ==================== ROTA RAIZ ====================
Route::get('/', function () {
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
Route::middleware('auth')->group(function () {
    
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
     * Rota principal do sistema de salas
     * GET /salas - Exibe dashboard com minhas salas e salas públicas
     * Retorna JSON com dados para integração React futura
     */
    Route::get('/salas', [SalaController::class, 'index'])->name('salas.index');
    
    /**
     * Criar nova sala
     * POST /salas - Cria uma sala (pública, privada ou apenas convite)
     */
    Route::post('/salas', [SalaController::class, 'store'])->name('salas.store');
    
    /**
     * Entrar em sala por ID
     * POST /salas/entrar - Permite entrar em sala fornecendo ID (e senha se necessário)
     */
    Route::post('/salas/entrar', [SalaController::class, 'entrarSala'])->name('salas.entrar');
    
    /**
     * Visualizar sala específica
     * GET /salas/{id} - Mostra detalhes da sala com teste WebSocket
     */
    Route::get('/salas/{id}', [SalaController::class, 'show'])->name('salas.show');
    
    /**
     * Sair da sala
     * POST /salas/{id}/sair - Remove usuário da sala (exceto criador)
     */
    Route::post('/salas/{id}/sair', [SalaController::class, 'sairSala'])->name('salas.sair');
    
    /**
     * Sistema de convites para salas
     * POST /salas/{id}/convidar - Gera convite com token único
     */
    Route::post('/salas/{id}/convidar', [SalaController::class, 'gerarConvite'])->name('salas.convidar');
    
    // ========== ROTAS DE CONVITES ==========
    
    /**
     * Aceitar convite via token
     * GET /convites/{token} - Link de convite enviado por email/compartilhamento
     */
    Route::get('/convites/{token}', [SalaController::class, 'aceitarConvite'])
        ->name('convites.aceitar');

    // ========== ROTAS DE API PARA INTEGRAÇÃO REACT ==========
    
    /**
     * Rotas API preparadas para integração React futura
     * Todas retornam JSON para facilitar conectividade frontend
     */
    Route::prefix('api')->name('api.')->group(function () {
        
        // API de salas
        Route::get('/salas', [SalaController::class, 'index'])->name('salas.index');
        Route::post('/salas', [SalaController::class, 'store'])->name('salas.store');
        Route::get('/salas/{id}', [SalaController::class, 'show'])->name('salas.show');
        Route::post('/salas/entrar', [SalaController::class, 'entrarSala'])->name('salas.entrar');
        Route::post('/salas/{id}/sair', [SalaController::class, 'sairSala'])->name('salas.sair');
        Route::post('/salas/{id}/convidar', [SalaController::class, 'gerarConvite'])->name('salas.convidar');
        
        // API de convites
        Route::get('/convites/{token}', [SalaController::class, 'aceitarConvite'])->name('convites.aceitar');
        
    });

});

/*
|--------------------------------------------------------------------------
| Observações Importantes para Integração React
|--------------------------------------------------------------------------
|
| 1. Todas as rotas do SalaController retornam JSON, facilitando integração
| 2. Sistema preparado para WebSocket - método testarWebSocket() implementado
| 3. Middleware 'auth' protege todas as rotas de sala
| 4. Rotas duplicadas em /api/ para separar frontend/backend
| 5. Tokens únicos para convites com expiração configurável
| 6. Sistema de permissões granular implementado
| 7. Logs detalhados para debugging e monitoramento
|
*/