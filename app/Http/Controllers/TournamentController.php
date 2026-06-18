<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $search = request('search');

        if($search) {
            $tournaments = Tournament::where([
                ['name', 'like', '%'.$search.'%']
            ])->orWhere([
                ['category', 'like', '%'.$search.'%']
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  
        $tournament = new Tournament;

        $tournament->name = $request->name;
    

        // IMAGE UPLOAD
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('/assets/tournaments/banner/'), $imageName);

            $tournament->img = "/assets/tournaments/banner/" . $imageName;
        }

        $tournament->save();
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tournament $tournament)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tournament $tournament)
    {
        //
    }
}
