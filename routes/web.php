<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

// Página Inicial do Projeto
Route::get('/', function () {
    return view('welcome');
});

// para a sua página de torneios!
Route::get('/dashboard', function () {
    return redirect()->route('player.torneios');
})->middleware(['auth', 'verified']);


// ROTAS PROTEGIDAS (Só entra quem estiver logado)
Route::middleware('auth')->group(function () {
    
    // --- Rotas de Torneios ---
    Route::get('/torneios', [TournamentController::class, 'index'])->name('player.torneios');

    // Rota para ver um torneio específico 
    Route::get('/torneio/{id}', [TournamentController::class, 'show'])->name('player.torneio.show');

    // Rota para ver todos os times
    Route::get('/times', [TeamController::class, 'index'])->name('player.times');

    // Rota para ver um time específico
    Route::get('/time/{id}', [TeamController::class, 'show'])->name('player.time.show');
    
    // Rota que processa o clique no botão "Entrar" do formulário
    Route::post('/time/{team}/join', [TeamController::class, 'join'])->name('player.time.join');


    // --- Rotas de Perfil (Nativas do Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';