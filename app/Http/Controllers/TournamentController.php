<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Tournaments = Tournament::all();
        return view('player.torneios', ['Tournaments' => $Tournaments]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Tournament = new Tournament;

        $Tournament->name = $request->name;

        // IMAGE UPLOAD
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('/assets/tournaments/banner/'), $imageName);

            $Tournament->img = "/assets/tournaments/banner/" . $imageName;
        }

        $Tournament->save();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Tournament = Tournament::with([
            'matches.teamA',
            'matches.teamB',
            'matches.winner'
        ])->findOrFail($id);

        return view('player.torneio', ['Tournament' => $Tournament]);
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
