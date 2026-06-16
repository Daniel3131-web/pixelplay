<?php

use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/time', function () {
    return view('player.time');
});


Route::get('/home', [TournamentController::class, 'index'])->name('tournaments.index');
Route::get('/torneio/{id}', [TournamentController::class, 'show'])->name('tournaments.show');