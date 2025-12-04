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

    });

