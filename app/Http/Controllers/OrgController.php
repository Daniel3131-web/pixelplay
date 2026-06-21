<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Map;
use App\Models\PlayerInfos;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrgController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Dados para os cards do topo
        $totalSubscribers = $tournaments->sum('current_participants');
        $totalTournaments = $tournaments->count();

        return view('org.dashboard', compact(
            'tournaments',
            'totalTournaments',
            'totalSubscribers',
        ));
    }

    public function bracket($id)
    {
        $tournament = Tournament::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('matches')
            ->firstOrFail();

        $fases = [];
        $max = $tournament->max_participants;

        if ($max == 4) {
            $fases = ['Semi Final', 'Final'];
        } elseif ($max == 8) {
            $fases = ['Quartas de Final', 'Semi Final', 'Final'];
        } elseif ($max == 16) {
            $fases = ['Oitavas de Final', 'Quartas de Final', 'Semi Final', 'Final'];
        } else {
            $fases = ['Fase Inicial', 'Semi Final', 'Final']; // Fallback
        }

        return view('org.torneio-bracket', [
            'Tournament' => $tournament,
            'fases' => $fases
        ]);
    }

    public function tournament_create()
    {
        return view('org.torneio-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function tournament_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:tournaments,name|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:valorant,cs2,lol,mlbb,ow2,mr',
            'max_participants' => 'required|in:4,8,16',
            'entrance_fee' => 'required|numeric|min:0',
            'awards' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'entry_date' => 'required|date|before_or_equal:start_date',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $organizer = Auth::user();

        $tournament = DB::transaction(function () use ($request, $organizer) {
            $newTournament = new Tournament();

            $newTournament->user_id = $organizer->id;
            $newTournament->name = $request->name;
            $newTournament->description = $request->description;
            $newTournament->category = $request->category;
            $newTournament->max_participants = $request->max_participants;
            $newTournament->entrance_fee = $request->entrance_fee;
            $newTournament->awards = $request->awards;
            $newTournament->start_date = $request->start_date;
            $newTournament->end_date = $request->end_date;
            $newTournament->start_time = $request->start_time;
            $newTournament->end_time = $request->end_time;
            $newTournament->entry_date = $request->entry_date;
            $newTournament->status = 'Agendado';

            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                $requestImage = $request->img;
                $extension = $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

                $requestImage->move(public_path('/assets/tournaments/'), $imageName);
                $newTournament->img = "/assets/tournaments/" . $imageName;
            }

            $newTournament->save();

            return $newTournament;
        });

        return redirect()->route('org.dashboard')
            ->with('success', 'Torneio criado com sucesso!');
    }

    public function tournament_edit($id)
    {
        $tournament = Tournament::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('org.torneio-edit', ['tournament' => $tournament]);
    }

    public function tournament_update(Request $request, $id)
    {
        $tournament = Tournament::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $tournament->update($request->except('img'));

        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            $imageName = md5($request->img->getClientOriginalName() . strtotime("now")) . "." . $request->img->extension();
            $request->img->move(public_path('/assets/tournaments/'), $imageName);
            $tournament->img = "/assets/tournaments/" . $imageName;
            $tournament->save();
        }

        return redirect()->route('org.dashboard')->with('success', 'Torneio atualizado!');
    }

    public function tournament_destroy($id)
    {
        $tournament = Tournament::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($tournament->img) {
            $imagePath = public_path($tournament->img);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $tournament->delete();

        return redirect()->route('org.dashboard')->with('msg', 'Torneio removido com sucesso!');
    }

    public function match_create($id)
    {
        $tournament = Tournament::where('id', $id)->with('teams')->firstOrFail();
        $teams = $tournament->teams;
        return view('org.partida-create', ['tournament' => $tournament, 'teams' => $teams]);
    }

    public function match_store(Request $request, $id)
    {
        $tournament = Tournament::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'team_a_id' => 'required|exists:teams,id',
            'team_b_id' => 'required|exists:teams,id|different:team_a_id',
            'stage' => 'required|in:Oitavas de Final,Quartas de Final,Semi Final,Final',
            'order_of_keys' => 'required|string|max:50',
            'match_status' => 'required|in:Agendada,Em Andamento,Finalizada,W.O.',
        ]);

        $isTeamAInTournament = $tournament->teams()->where('teams.id', $request->team_a_id)->exists();
        $isTeamBInTournament = $tournament->teams()->where('teams.id', $request->team_b_id)->exists();

        if (!$isTeamAInTournament || !$isTeamBInTournament) {
            return back()->withErrors(['error' => 'Um ou ambos os times não estão inscritos neste torneio.']);
        }

        $match = TournamentMatch::create([
            'tournament_id' => $tournament->id,
            'team_a_id' => $request->team_a_id,
            'team_b_id' => $request->team_b_id,
            'stage' => $request->stage,
            'order_of_keys' => $request->order_of_keys,
            'match_status' => $request->match_status,
        ]);

        $teamA = Team::with('users')->find($request->team_a_id);
        $teamB = Team::with('users')->find($request->team_b_id);

        if ($teamA && $teamA->users) {
            foreach ($teamA->users as $player) {
                PlayerInfos::create([
                    'match_id' => $match->id,
                    'character_id' => Null,
                    'user_id' => $player->id,
                    'team_id' => $teamA->id,
                    'kill' => 0,
                    'death' => 0,
                    'assistance' => 0,
                    'score' => 0,
                ]);

                $player->increment('matches');
            }
        }

        if ($teamB && $teamB->users) {
            foreach ($teamB->users as $player) {
                PlayerInfos::create([
                    'match_id' => $match->id,
                    'character_id' => Null,
                    'user_id' => $player->id,
                    'team_id' => $teamB->id,
                    'kill' => 0,
                    'death' => 0,
                    'assistance' => 0,
                    'score' => 0,
                ]);

                $player->increment('matches');
            }
        }

        return redirect()->route('org.torneio.bracket', $tournament->id)
            ->with('success', 'Partida criada com sucesso!');
    }

    public function match_view($id)
    {
        $match = TournamentMatch::with([
            'tournament',
            'teamA',
            'teamB',
            'player_Infos.player'
        ])->findOrFail($id);

        return view('org.partida-view', ['Match' => $match]);
    }

    public function match_destroy($id)
    {
        $match = TournamentMatch::where('id', $id)->firstOrFail();

        if ($match->winner_id) {
            $team = Team::with('users')->find($match->winner_id);

            foreach ($team->users as $player) {
                $player->decrement('wins');
            }
        }

        $teamA = Team::with('users')->find($match->team_a_id);
        $teamB = Team::with('users')->find($match->team_b_id);

        foreach ($teamA->users as $player) {
            $player->decrement('matches');
        }

        foreach ($teamB->users as $player) {
            $player->decrement('matches');
        }

        $match->delete();

        return redirect()->route('org.dashboard')->with('msg', 'Partida removida com sucesso!');
    }

    public function match_edit($id)
    {
        $match = TournamentMatch::with([
            'tournament',
            'teamA',
            'teamB',
            'player_Infos.player',
            'map'
        ])->findOrFail($id);

        $category = strtolower($match->tournament->category);

        $characters = Character::where('category', $category)->get();
        $maps = Map::where('category', $category)->get();

        return view('org.partida-edit', ['Match' => $match, 'maps' => $maps, 'characters' => $characters]);
    }

    public function match_update(Request $request, $id)
    {
        $request->validate([
            'score_a' => 'required|integer|min:0',
            'score_b' => 'required|integer|min:0',
            'map_id' => 'nullable|exists:maps,id',
            'stats' => 'nullable|array',
            'stats.*.kill' => 'required|integer|min:0',
            'stats.*.death' => 'required|integer|min:0',
            'stats.*.assistance' => 'required|integer|min:0',
            'stats.*.score' => 'required|integer|min:0',
            'stats.*.character' => 'required|integer|min:0',
        ]);

        $match = TournamentMatch::findOrFail($id);
        $teamA = Team::with('users')->find($match->team_a_id);
        $teamB = Team::with('users')->find($match->team_b_id);

        $winner_id = null;
        if ($request->score_a > $request->score_b) {
            $winner_id = $teamA->id;
        } elseif ($request->score_b > $request->score_a) {
            $winner_id = $teamB->id;
        }

        if ($winner_id == null) {
            $team = Team::with('users')->find($winner_id);
            foreach ($team->users as $player) {
                $player->increment('wins');
            }
        }

        $match->update([
            'score_a' => $request->score_a,
            'score_b' => $request->score_b,
            'map_id' => $request->map_id,
            'winner_id' => $winner_id
        ]);

        if ($request->has('stats')) {
            foreach ($request->stats as $statId => $statData) {
                PlayerInfos::where('id', $statId)
                    ->where('match_id', $match->id)
                    ->update([
                        'kill' => $statData['kill'],
                        'death' => $statData['death'],
                        'assistance' => $statData['assistance'],
                        'score' => $statData['score'],
                        'character_id' => $statData['character'],
                    ]);
            }
        }
        return redirect('partida/' . $match->id)->with('success', 'Partida e estatísticas atualizadas com sucesso!');
    }

    // Events function

    public function event_create()
    {
        return view('org.evento-create');
    }

}