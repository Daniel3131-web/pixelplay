<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/home', function () {
    return view('player.home');
});

Route::get('/torneio', function() {
    return view('player.torneio');
});