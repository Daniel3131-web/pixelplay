<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrgController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PaymentController;
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
    // Rota para ver uma partida específica
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

    // Rotas de Pagamentos

    Route::get('/pagamento/torneio/{id}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/pagamento/processar', [PaymentController::class, 'processSimulation'])->name('payment.process');
    Route::get('/pagamento/processando/{orderId}', [PaymentController::class, 'processing'])->name('payment.processing');
    Route::post('/pagamento/confirmar/{orderId}', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');
    Route::get('/pagamento/sucesso/{orderId}', [PaymentController::class, 'success'])->name('payment.success');

});

// ROTAS PROTEGIDAS PARA ORGANIZADOR

Route::middleware('org')->group(function () {

    // Rota para ver o dashboard do Organizador
    Route::get('/dashboard', [OrgController::class, 'index'])->name('org.dashboard');

    // Rota para criar torneio
    Route::get('/org/torneio/criar', [OrgController::class, 'tournament_create'])->name('org.torneio.criar');
    Route::post('/org/torneio/store', [OrgController::class, 'tournament_store'])->name('org.torneio.store');
    Route::get('/org/torneio/{id}/chaveamento', [OrgController::class, 'bracket'])->name('org.torneio.bracket');
    Route::get('/org/torneio/{id}/edit', [OrgController::class, 'tournament_edit'])->name('org.torneio.edit');
    Route::put('/org/torneio/{id}/update', [OrgController::class, 'tournament_update'])->name('org.torneio.update');
    Route::delete('/org/torneio/{id}/destroy', [OrgController::class, 'tournament_destroy'])->name('org.torneio.destroy');

    // Rota para criar partida
    Route::get('/org/partida/{id}/criar', [OrgController::class, 'match_create'])->name('org.partida.criar');
    Route::get('/org/partida/{id}/edit', [OrgController::class, 'match_edit'])->name('org.partida.edit');
    Route::put('/org/partida/{id}/update', [OrgController::class, 'match_update'])->name('org.partida.update');
    Route::post('/org/partida/{id}/store', [OrgController::class, 'match_store'])->name('org.partida.store');
    Route::delete('/org/partida/{id}/destroy', [OrgController::class, 'match_destroy'])->name('org.partida.destroy');


});

require __DIR__ . '/auth.php';