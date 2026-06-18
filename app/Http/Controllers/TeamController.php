<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        if (!$user->team_id) {
            return redirect()->back()->with('error', 'Você não está em nenhum time.');
        }

        $team = Team::findOrFail($user->team_id);

        DB::transaction(function () use ($user, $team) {
            // saindo o líder do time
            if ($team->leader_id === $user->id) {
                // Busca outro jogador (excluindo o líder atual)
                $nextLeader = User::where('team_id', $team->id)
                                  ->where('id', '!=', $user->id)
                                  ->first();

                if ($nextLeader) {
                    // passa a liderança
                    $team->update(['leader_id' => $nextLeader->id]);
                } else {
                    // Se não houver mais ninguém, deleta o time
                    $team->delete();
                }
            }

            // Remove o time do usuário atual
            $user->update(['team_id' => null]);
        });

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

        if ($user->team_id) {
            return back()->with('msg', 'Você já pertence a outra equipe! Saia dela primeiro.');
        }

        $team->loadCount('users');

        if ($team->users_count >= $team->max_participants) {
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

        $request->validate([
            'name' => 'required|string|unique:teams,name|max:255',
            'acronym' => 'required|string|max:5',
            'privacy' => 'required|in:public,private',
            'password' => 'required_if:privacy,private|nullable|string|min:4',
            'img' => 'nullable|image|max:2048'
        ]);

        $player = Auth::user();

        if ($player->team_id) {
        return redirect()->back()->with('error', 'Você já pertence a um time!');
        }

        // o DB serve para que tudo aconteçã junto ou falhe junto
        $team = DB::transaction(function () use ($request, $player) {
            $newTeam = new Team;
            $newTeam->name = $request->name;
            $newTeam->acronym = $request->acronym;
            $newTeam->description = $request->description;
            $newTeam->privacy = $request->privacy;
            $newTeam->leader_id = $player->id;

            if ($request->privacy === 'private' && $request->filled('password')) {
                $newTeam->password = Hash::make($request->password);
            }

            // IMAGE UPLOAD
            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                $requestImage = $request->img;
                $extension = $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestImage->move(public_path('/assets/teams/'), $imageName);
                $newTeam->img = "/assets/teams/" . $imageName;
            }

            $newTeam->save();

            // Vincula o criador ao time que ele acabou de gerar
            $player->team_id = $newTeam->id;
            $player->save();

            return $newTeam;
        });

        return redirect()->route('player.time.show', $team->id)->with('msg', 'Time criado com sucesso e você é o capitão!');
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
