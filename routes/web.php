<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota inicial
Route::get('/', function () {
    return view('welcome');
});

// ============ ROTAS PÚBLICAS (SEM AUTENTICAÇÃO) ============
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


// ============ ROTAS PROTEGIDAS (COM AUTENTICAÇÃO) ============
Route::middleware('auth')->group(function () {
    
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
});
