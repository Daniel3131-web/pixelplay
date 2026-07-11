<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrgController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Models\Inbox;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// DEV ROUTES
Route::get('/limpar-tudo', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    return 'Laravel atualizado no servidor!';
});

Route::get('/migrations', function () {
    Artisan::call('migrate:refresh --seed');
    Artisan::call('db:seed --class=ReportsDemoSeeder');
    return 'Laravel atualizado no servidor!';
});



// Página Inicial do Projeto
Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/contato', function () {
    return view('contato');
})->name('contato');

Route::get('/sobre', function () {
    return view('sobre');
})->name('sobre');

// ==========================================
// ROTAS PROTEGIDAS (Apenas Usuários Logados)
// ==========================================
Route::middleware('auth')->group(function () {

    // --- Eventos e Torneios (Nomes Corrigidos) ---
    Route::get('/eventos', [EventController::class, 'index'])->name('player.eventos');
    Route::get('/evento/{id}', [EventController::class, 'show'])->name('player.evento.show');
    Route::get('/evento/{id}/torneios', [EventController::class, 'tournaments'])->name('player.torneios');
    Route::get('/torneio/{id}', [TournamentController::class, 'show'])->name('player.torneio.show');
    Route::get('/partida/{id}', [TournamentController::class, 'show_match'])->name('player.match.show');

    // -- Meus Eventos
    Route::get('/meus-eventos', [EventController::class, 'meusEventos'])->name('player.meuseventos');

    // --- Gerenciamento de Times ---
    Route::get('/times', [TeamController::class, 'index'])->name('player.times');
    Route::get('/time/criar', [TeamController::class, 'create'])->name('player.time.create');
    Route::post('/time/store', [TeamController::class, 'store'])->name('player.time.store');
    Route::post('/time/leave', [TeamController::class, 'leave'])->name('player.time.leave');
    Route::get('/time/{id}', [TeamController::class, 'show'])->name('player.time.show');
    Route::get('/time/{team}/edit', [TeamController::class, 'edit'])->name('player.time.edit');
    Route::put('/time/{team}/update', [TeamController::class, 'update'])->name('player.time.update');
    Route::post('/time/{team}/join', [TeamController::class, 'join'])->name('player.time.join');
    Route::put('/time/{id}/{user}', [TeamController::class, 'removeMember'])->name('player.time.remove-member');

    // --- Perfil de Usuário ---
    Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Fluxo de Pagamento Simulado ---
    Route::get('/pagamento/checkout/{id}/{type}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/pagamento/processar', [PaymentController::class, 'processSimulation'])->name('payment.process');
    Route::get('/pagamento/processando/{orderId}', [PaymentController::class, 'processing'])->name('payment.processing');
    Route::post('/pagamento/confirmar/{orderId}', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');
    Route::get('/pagamento/sucesso/{orderId}', [PaymentController::class, 'success'])->name('payment.success');

    // -- Inbox
    Route::post('/inbox/{id}/read', function ($id) {
        $message = Inbox::where('user_id', Auth::id())->findOrFail($id);
        $message->update(['is_read' => true]);

        return redirect()->back()->with('msg', 'Mensagem marcada como lida!');
    })->name('inbox.read')->middleware('auth');

});

// ==========================================
// ROTAS DO ORGANIZADOR (Logado + Role Org)
// ==========================================
Route::middleware(['auth', 'org'])->group(function () {

    // Rota de teste para anexar times ao torneio
    Route::post('/org/tournament/{id}/attach-test-teams', [OrgController::class, 'attachTestTeams'])->name('org.tournament.attachTeamsTest');

    // Painel Principal
    Route::get('/dashboard', [OrgController::class, 'index'])->name('org.dashboard');

    // -- Report --
    Route::get('/relatorios', [ReportController::class, 'index'])->name('org.reports.index');
    Route::get('/relatorios/exportar', [ReportController::class, 'export'])->name('org.reports.export');

    // --- Controle de Eventos (CRUD) ---
    Route::get('/org/evento/criar', [OrgController::class, 'event_create'])->name('org.evento.criar');
    Route::post('/org/evento/store', [OrgController::class, 'event_store'])->name('org.evento.store');
    Route::get('/org/evento/{id}/edit', [OrgController::class, 'event_edit'])->name('org.evento.edit');
    Route::put('/org/evento/{id}/update', [OrgController::class, 'event_update'])->name('org.evento.update');
    Route::delete('/org/evento/{id}/destroy', [OrgController::class, 'event_destroy'])->name('org.evento.destroy');

    // --- Controle de Torneios (CRUD e Chaves) ---
    Route::get('/org/torneio/criar', [OrgController::class, 'tournament_create'])->name('org.torneio.criar');
    Route::post('/org/torneio/store', [OrgController::class, 'tournament_store'])->name('org.torneio.store');
    Route::post('/org/tournament/{id}/generate-bracket', [OrgController::class, 'generateBracket'])->name('org.tournament.generate-bracket');
    Route::get('/org/torneio/{id}/edit', [OrgController::class, 'tournament_edit'])->name('org.torneio.edit');
    Route::put('/org/torneio/{id}/update', [OrgController::class, 'tournament_update'])->name('org.torneio.update');
    Route::delete('/org/torneio/{id}/destroy', [OrgController::class, 'tournament_destroy'])->name('org.torneio.destroy');

    // --- Controle de Partidas ---
    Route::get('/org/partida/{id}/criar', [OrgController::class, 'match_create'])->name('org.partida.criar');
    Route::get('/org/partida/{id}/edit', [OrgController::class, 'match_edit'])->name('org.partida.edit');
    Route::put('/org/partida/{id}/update', [OrgController::class, 'match_update'])->name('org.partida.update');
    Route::post('/org/partida/{id}/store', [OrgController::class, 'match_store'])->name('org.partida.store');
    Route::delete('/org/partida/{id}/destroy', [OrgController::class, 'match_destroy'])->name('org.partida.destroy');

    // -- Rota de notificação
    Route::get('/org/notificacao/criar', [OrgController::class, 'notification_create'])->name('org.notificacao.criar');
    Route::post('/org/notificacao/store', [OrgController::class, 'notification_store'])->name('org.notificacao.store');

    // --- Rota para validação de ingresso
    Route::get('/validar/{orderId}', [PaymentController::class, 'validateEntry'])
        ->name('admin.validate.order');

    Route::get('/admin/scanner', function () {
        return view('admin.scanner');
    })->name('admin.checkin.scan');
});

require __DIR__ . '/auth.php';