<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');

        if ($search) {
            $tournaments = Tournament::where([
                ['name', 'like', '%' . $search . '%']
            ])->orWhere([
                        ['category', 'like', '%' . $search . '%']
                    ])->get();
        } else {
            $tournaments = Tournament::all();
        }

        return view('player.torneios', ['tournaments' => $tournaments, 'search' => $search]);
    }

    public function create()
    {
        return view('org.torneio-create');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $tournament = Tournament::with([
            'matches.teamA',
            'matches.teamB',
            'matches.winner'
        ])->findOrFail($id);

        return view('player.torneio', ['Tournament' => $tournament]);
    }

    public function show_match(int $id)
    {
        $match = TournamentMatch::with([
            'tournament',
            'teamA',
            'teamB',
            'player_Infos.player'
        ])->findOrFail($id);

        return view('player.partida', ['Match' => $match]);
    }

}
