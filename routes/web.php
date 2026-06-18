<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrgController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

// Página Inicial do Projeto
Route::get('/', function () {
    return view('welcome');
});


// ROTAS PROTEGIDAS (Só entra quem estiver logado)
Route::middleware('auth')->group(function () {
    
    // --- Rotas de Torneios ---
    Route::get('/torneios', [TournamentController::class, 'index'])->name('player.torneios');

    // Rota para ver um torneio específico 
    Route::get('/torneio/{id}', [TournamentController::class, 'show'])->name('player.torneio.show');

    Route::get('/partida/{id}', [TournamentController::class, 'show_match'])->name('player.match.show');

    // Rota para ver todos os times
    Route::get('/times', [TeamController::class, 'index'])->name('player.times');

    // Rota para criar um time
    Route::get('/time/criar', [TeamController::class, 'create'])->name('player.time.create');

    // Rota para criar um time
    Route::post('/time/store', [TeamController::class, 'store'])->name('player.time.store');

    // Rota para sair do time
    Route::post('/time/leave', [TeamController::class, 'leave'])->name('player.time.leave');

    // Rota para ver um time específico
    Route::get('/time/{id}', [TeamController::class, 'show'])->name('player.time.show');
    
    // Rota que processa o clique no botão "Entrar" do formulário
    Route::post('/time/{team}/join', [TeamController::class, 'join'])->name('player.time.join');

    // Rota para ver o perfil publico
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
    // --- Rotas de Perfil (Nativas do Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ROTAS PROTEGIDAS PARA ORGANIZADOR

Route::middleware('org')->group(function () {


    // Rota para ver o dashboard do Organizador
    Route::get('/dashboard', [OrgController::class, 'index'])->name('org.dashboard');

    Route::get('/org/torneio/criar', [OrgController::class, 'create'])->name('org.torneio.criar');
    Route::post('/org/torneio/store', [OrgController::class, 'store'])->name('org.torneio.store');
    Route::get('/org/torneio/{id}/edit', [OrgController::class, 'edit'])->name('org.torneio.edit');
    Route::put('/org/torneio/{id}/update', [OrgController::class, 'update'])->name('org.torneio.update');
    Route::delete('/org/torneio/{id}/destroy', [OrgController::class, 'destroy'])->name('org.torneio.destroy');
});

require __DIR__.'/auth.php';