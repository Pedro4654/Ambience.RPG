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


// ========== ROTAS DE RECUPERAÇÃO DE SENHA ==========
Route::get('/esqueci-minha-senha', [UsuarioController::class, 'showForgotPasswordForm'])
    ->name('usuarios.forgot.form');

Route::post('/enviar-link-recuperacao', [UsuarioController::class, 'sendResetLink'])
    ->name('usuarios.forgot.send');

Route::get('/redefinir-senha/{token}', [UsuarioController::class, 'showResetPasswordForm'])
    ->name('usuarios.reset.form');

Route::post('/redefinir-senha', [UsuarioController::class, 'resetPassword'])
    ->name('usuarios.reset.password');

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
