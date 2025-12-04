<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OwlbearGameController;
use App\Http\Controllers\Api\OwlbearMapController;
use App\Http\Controllers\Api\OwlbearTokenController;
use App\Http\Controllers\Api\SessionPlayersController;

// ROTAS SEM AUTH (para funcionar como antes)
Route::middleware(['web'])->group(function () {
    Route::get('/owlbear/session', [OwlbearController::class, 'getSession']);
    
    // Rotas que aceitam tanto autenticado quanto não autenticado
    Route::get('/owlbear/sessions/{sessao_id}/players', [SessionPlayersController::class, 'index']);
    Route::post('/owlbear/sessions/{sessao_id}/join', [SessionPlayersController::class, 'join']);
    Route::post('/owlbear/sessions/{sessao_id}/leave', [SessionPlayersController::class, 'leave']);
    Route::post('/owlbear/sessions/{sessao_id}/heartbeat', [SessionPlayersController::class, 'heartbeat']);

    // Session/Game
    Route::get('/owlbear/session', [OwlbearGameController::class, 'show']);
    Route::post('/owlbear/session', [OwlbearGameController::class, 'store']);
    
    // Maps
    Route::get('/owlbear/maps', [OwlbearMapController::class, 'index']);
    Route::post('/owlbear/maps', [OwlbearMapController::class, 'store']);
    Route::put('/owlbear/maps/{id}', [OwlbearMapController::class, 'update']);
    Route::delete('/owlbear/maps/{id}', [OwlbearMapController::class, 'destroy']);
    
    // Tokens
    Route::get('/owlbear/tokens', [OwlbearTokenController::class, 'index']);
    Route::post('/owlbear/tokens', [OwlbearTokenController::class, 'store']);
    Route::put('/owlbear/tokens/{id}', [OwlbearTokenController::class, 'update']);
    Route::delete('/owlbear/tokens/{id}', [OwlbearTokenController::class, 'destroy']);

    // NOVAS ROTAS - ADICIONE AQUI:
    
    // Buscar permissões do usuário
    Route::get('/permissoes/{sessao_id}/{usuario_id}', [App\Http\Controllers\Api\PermissoesController::class, 'show']);
    
    // Buscar mestre_id da sessão
    Route::get('/sessoes/{sessao_id}/mestre', function($sessao_id) {
    $sessao = \App\Models\SessaoJogo::find($sessao_id);
    
    if (!$sessao) {
        return response()->json(['criador_id' => null, 'sala_id' => null], 404);
    }
    
    // Buscar criador da sala
    $sala = \App\Models\Sala::find($sessao->sala_id);
    
    return response()->json([
        'mestre_id' => $sessao->mestre_id,
        'criador_id' => $sala ? $sala->criador_id : null,
        'sala_id' => $sessao->sala_id
    ]);
})->middleware('web');

    Route::get('/sessoes/{sessao_id}/status', function($sessao_id) {
    $sessao = \App\Models\SessaoJogo::find($sessao_id);
    
    if (!$sessao) {
        return response()->json(['status' => 'finalizada'], 404);
    }
    
    $sala = \App\Models\Sala::find($sessao->sala_id);
    
    return response()->json([
        'status' => $sessao->status,
        'criador_id' => $sala ? $sala->criador_id : null,
        'sala_id' => $sessao->sala_id
    ]);
})->middleware('web');

Route::post('/sessoes/{sessao_id}/iniciar', function($sessao_id) {
    $sessao = \App\Models\SessaoJogo::find($sessao_id);
    if ($sessao) {
        $sessao->update(['status' => 'ativa']);
    }
    return response()->json(['success' => true]);
})->middleware('web');

Route::post('/sessoes/{sessao_id}/pausar', function($sessao_id) {
    $sessao = \App\Models\SessaoJogo::find($sessao_id);
    if ($sessao) {
        $sessao->update(['status' => 'pausada']);
    }
    return response()->json(['success' => true]);
})->middleware('web');

Route::post('/sessoes/{sessao_id}/finalizar', function($sessao_id) {
    $sessao = \App\Models\SessaoJogo::find($sessao_id);
    if ($sessao) {
        $sessao->update([
            'status' => 'finalizada',
            'data_fim' => now()
        ]);
    }
    return response()->json(['success' => true]);
})->middleware('web');

}); 