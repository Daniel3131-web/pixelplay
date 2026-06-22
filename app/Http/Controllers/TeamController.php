<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $hasTeam = (bool) $user->team_id;

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

    /** * Sair do time
     */
    public function leave()
    {
        $user = Auth::user();

        if (!$user->team_id) {
            // Ajustado para withErrors para disparar o toast vermelho
            return redirect()->back()->withErrors(['error' => 'Você não está em nenhum time.']);
        }

        $team = Team::findOrFail($user->team_id);

        DB::transaction(function () use ($user, $team) {
            if ($team->leader_id === $user->id) {
                $nextLeader = User::where('team_id', $team->id)
                    ->where('id', '!=', $user->id)
                    ->first();

                if ($nextLeader) {
                    $team->update(['leader_id' => $nextLeader->id]);
                } else {
                    $team->delete();
                }
            }

            $user->update(['team_id' => null]);
        });

        return redirect()->route('player.times')->with('msg', 'Você saiu do time com sucesso.');
    }

    /** * Entrar no time
     */
    public function join(Request $request, Team $team)
    {
        $user = Auth::user();

        if ($user->team_id == $team->id) {
            return redirect()->route('player.time.show', $team->id)->with('msg', 'Você já está neste time!');
        }

        if ($user->team_id) {
            // Ajustado para withErrors
            return back()->withErrors(['error' => 'Você já pertence a outra equipe! Saia dela primeiro.']);
        }

        $team->loadCount('users');

        if ($team->users_count >= $team->max_participants) {
            // Ajustado para withErrors
            return back()->withErrors(['error' => 'Este time já está cheio.']);
        }

        if ($team->privacy !== 'public') {
            $request->validate(['password' => 'required']);

            if (!Hash::check($request->password, $team->password)) {
                // Ajustado para com errors para invalidar o input de senha do front
                return back()->withErrors(['password' => 'Senha incorreta!']);
            }
        }

        $user->team_id = $team->id;
        $user->save();

        return redirect()->route('player.time.show', $team->id)->with('msg', 'Você entrou no time com sucesso!');
    }

    public function create()
    {
        return view('player.time-create');
    }

    public function edit(Team $team)
    {
        // Garante que apenas o dono/capitão do time possa acessar a tela de edição
        if ($team->leader_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para alterar as configurações deste time.');
        }
        return view('player.time-edit', compact('team'));

    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|min:5|max:255',
            'acronym' => 'required|string|max:5',
            'privacy' => 'required|in:public,private',
            'password' => 'nullable|string|min:8',
            'description' => 'required|string|max:255',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.min' => 'O nome do time deve ter no mínimo 5 caracteres.',
            'acronym.max' => 'A sigla pode ter no máximo 5 letras.',
            'password.min' => 'A senha deve conter pelo menos 8 caracteres.',
            'img.image' => 'O arquivo enviado deve ser uma imagem válida.',
            'img.max' => 'A imagem não pode ser maior que 2MB.',
        ]);

        if ($request->hasFile('img')) {
            if ($team->img && Storage::disk('public')->exists($team->img)) {
                Storage::disk('public')->delete($team->img);
            }

            $requestImage = $request->img;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestImage->move(public_path('/assets/teams/'), $imageName);
            $team->img = "/assets/teams/" . $imageName;
        }

        if ($request->privacy === 'public') {
            $team->password = null;
        } elseif ($request->filled('password')) {
            $team->password = Hash::make($request->password);
        }
        // mantendo o hash da senha antiga intacto no banco de dados.

        $team->name = $request->name;
        $team->acronym = strtoupper($request->acronym);
        $team->privacy = $request->privacy;
        $team->description = $request->description;

        $team->save();

        return redirect()->route('player.time.show', $team->id)->with('msg', 'As informações do time foram atualizadas com sucesso!');
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
            // Ajustado para com errors
            return redirect()->back()->withErrors(['error' => 'Você já pertence a um time!']);
        }

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

            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                $requestImage = $request->img;
                $extension = $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
                $requestImage->move(public_path('/assets/teams/'), $imageName);
                $newTeam->img = "/assets/teams/" . $imageName;
            }

            $newTeam->save();

            $player->team_id = $newTeam->id;
            $player->save();

            return $newTeam;
        });

        return redirect()->route('player.time.show', $team->id)->with('msg', 'Time criado com sucesso! Você é o capitão.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $team = Team::withCount('users')->findOrFail($id);
        return view('player.time', ['Team' => $team]);
    }


    public function removeMember(int $teamId, User $user)
    {
        $team = Team::findOrFail($teamId);

        if (Auth::id() !== $team->leader_id) {
            abort(403, 'Apenas o líder do time pode remover membros.');
        }

        if ($user->id === $team->leader_id) {
            return redirect()->back()->with('error', 'O líder não pode ser removido do time.');
        }

        $user->team_id = null; 
        $user->save();

        return redirect()->back()->with('msg', 'Jogador removido do time com sucesso!');
    }
}