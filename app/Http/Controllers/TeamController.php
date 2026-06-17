<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $hasTeam = false;

        if ($user->team_id) {
            $hasTeam = true;
        }

        $search = request('search');

        if ($search) {
            $teams = Team::where([
                ['name', 'like', '%' . $search . '%']
            ])->orWhere([
                        ['privacy', 'like', '%' . $search . '%']
                    ])->withCount('users')->get();
        } else {
            $teams = Team::withCount('users')->get();
        }

        return view('player.times', ['Teams' => $teams, 'search' => $search, 'hasTeam' => $hasTeam]);
    }

    /** 
     * Sair do time
    */

    public function leave()
    {
        $user = Auth::user();
        $user->team_id = null;
        $user->save();

        return redirect()->route('player.times')->with('msg', 'Você saiu do time.');
    }

    /** 
     * Entrar no time
    */

    public function join(Request $request, Team $team)
    {
        $user = Auth::user();

        if ($user->team_id == $team->id) {
            return redirect()->route('player.time.show', $team->id)->with('msg', 'Você já está no time!');
        }


        $team->loadCount('users');

        if ($team->current_participants >= $team->max_participants) {
            return back()->with('msg', 'Esse time já está cheio.');
        }

        if ($team->privacy !== 'public') {
            $request->validate(['password' => 'required']);

            if (!Hash::check($request->password, $team->password)) {
                return back()->with('msg', 'Senha incorreta!');
            }
        }

        $user->team_id = $team->id;
        $user->save();

        return redirect()->route('player.time.show', $team->id)->with('msg', 'Você entrou no time!');

    }

    public function create()
    {
        return view('player.time-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $team = new Team;

        $team->name = $request->name;
        $team->acronym = $request->acronym;
        $team->description = $request->description;
        $team->privacy = $request->privacy;

        // salvando a senha criptografada
        if ($request->privacy === 'private' && $request->filled('password')) {
            $team->password = Hash::make($request->password);
        }


        // IMAGE UPLOAD
        if ($request->hasFile('img') && $request->file('img')->isValid()) {

            $requestImage = $request->img;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('/assets/teams/'), $imageName);

            $team->img = "/assets/teams/" . $imageName;
        }

        $team->save();

        $user = Auth::user();
        if ($user) {
            $user->team_id = $team->id;
            $user->save();
        }

        return redirect()->route('player.time.show', $team->id)->with('msg', 'Time criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $team = Team::withCount('users')->findOrFail($id);
        return view('player.time', ['Team' => $team]);
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
