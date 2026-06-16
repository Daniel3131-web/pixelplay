<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Teams = Team::withCount('users')->get();
        return view('player.times', ['Teams' => $Teams]);
    }

    /** 
     * Entrar no time
    */

    public function join(Request $request, Team $team)
    {   
        $team->loadCount('users');

        if ($team->current_participants >= $team->max_participants) {
            return back()->with('msg', 'Esse time já está cheio.');
        }

        if($team->privacy !== 'public') {
            $request->validate(['password' => 'required']);

            if(! Hash::check($request->password, $team->password)) {
                return back()->with('msg', 'Senha incorreta!');
            }
        }

        return redirect()->route('player.torneios')->with('msg', 'Você entrou no time!');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Teams = new Team;

        $Teams->name = $request->name;

        // IMAGE UPLOAD
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('/assets/tournaments/banner/'), $imageName);

            $Teams->img = "/assets/tournaments/banner/" . $imageName;
        }

        $Teams->save();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {   
        $Team = Team::withCount('users')->findOrFail($id);
        return view('player.time', ['Team' => $Team]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $Teams)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $Teams)
    {
        //
    }
}
