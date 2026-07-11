<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Event;
use App\Models\Inbox;
use App\Models\Map;
use App\Models\PlayerInfos;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrgController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        $events = Event::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return view('org.dashboard', compact(
            'tournaments',
            'events',
        ));
    }

    public function bracket(int $id)
    {
        $tournament = Tournament::where('id', $id)->where('user_id', Auth::id())->with('matches.teamA', 'matches.teamB')->firstOrFail();

        return view('org.torneio-bracket', [
            'Tournament' => $tournament,
        ]);
    }

    public function generateBracket(int $id)
    {
        $tournament = Tournament::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        //  para não gerar duplicado
        if ($tournament->bracket_generated_at) {
            return redirect()->back()->withErrors(['error' => 'O chaveamento para este torneio já foi gerado.']);
        }

        $teamCount = $tournament->teams()->count();

        // (potências de 2)
        if (!in_array($teamCount, [4, 8, 16])) {
            return redirect()->back()->withErrors(['error' => 'O torneio precisa ter exatamente 4, 8 ou 16 times confirmados.']);
        }

        DB::transaction(function () use ($tournament, $teamCount) {
            // Embaralha as equipes para o sorteio inicial
            $teams = $tournament->teams->shuffle()->all();
            $jogosNaRodada1 = $teamCount / 2;

            // CHAVE SUPERIOR (UPPER BRACKET) - Comum para Simples e Duplo

            // Criação da Rodada 1 com os times sorteados
            for ($pos = 1; $pos <= $jogosNaRodada1; $pos++) {
                $teamA = array_shift($teams);
                $teamB = array_shift($teams);

                $match = TournamentMatch::create([
                    'tournament_id' => $tournament->id,
                    'bracket_type' => 'upper',
                    'round' => 1,
                    'bracket_position' => $pos,
                    'match_status' => 'Pendente',
                    'team_a_id' => $teamA->id,
                    'team_b_id' => $teamB->id,
                ]);

                // Registra os slots de estatísticas para os jogadores do Time A
                if ($teamA && $teamA->users) {
                    foreach ($teamA->users as $player) {
                        PlayerInfos::create([
                            'user_id' => $player->id,
                            'team_id' => $teamA->id,
                            'match_id' => $match->id,
                        ]);
                    }
                }

                // Registra os slots de estatísticas para os jogadores do Time B
                if ($teamB && $teamB->users) {
                    foreach ($teamB->users as $player) {
                        PlayerInfos::create([
                            'user_id' => $player->id,
                            'team_id' => $teamB->id,
                            'match_id' => $match->id,
                        ]);
                    }
                }

            }

            //Criação das rodadas seguintes da Upper (vazias, aguardando avanço)
            $proximosJogosUpper = $jogosNaRodada1 / 2;
            $rodadaUpper = 2;
            while ($proximosJogosUpper >= 1) {
                for ($pos = 1; $pos <= $proximosJogosUpper; $pos++) {
                    TournamentMatch::create([
                        'tournament_id' => $tournament->id,
                        'bracket_type' => 'upper',
                        'round' => $rodadaUpper,
                        'bracket_position' => $pos,
                        'match_status' => 'Pendente',
                    ]);
                }
                $proximosJogosUpper /= 2;
                $rodadaUpper++;
            }

            // ESTRUTURA SE FOR DUPLA ELIMINAÇÃO 
            if ($tournament->tournament_type === 'duplo') {

                // O número de rodadas da Lower é sempre o dobro das rodadas da Upper menos 2
                // exemplo para 8 times: Upper tem 3 rodadas. Lower tem 4 rodadas de jogos
                $totalRodadasLower = ($tournament->max_participants == 16) ? 6 : (($tournament->max_participants == 8) ? 4 : 2);

                $jogosNaRodadaLower = $jogosNaRodada1 / 2; // Começa com metade dos jogos da Rodada 1 Upper

                for ($r = 1; $r <= $totalRodadasLower; $r++) {
                    // em torneios de dupla eliminação, o número de jogos na Lower diminui apenas a cada duas rodadas
                    if ($r > 1 && $r % 2 != 0) {
                        $jogosNaRodadaLower /= 2;
                    }

                    for ($pos = 1; $pos <= max(1, $jogosNaRodadaLower); $pos++) {
                        TournamentMatch::create([
                            'tournament_id' => $tournament->id,
                            'bracket_type' => 'lower',
                            'round' => $r,
                            'bracket_position' => $pos,
                            'match_status' => 'Pendente',
                        ]);
                    }
                }

                TournamentMatch::create([
                    'tournament_id' => $tournament->id,
                    'bracket_type' => 'grand_final',
                    'round' => 1,
                    'bracket_position' => 1,
                    'match_status' => 'Pendente',
                ]);
            }

            $tournament->update([
                'bracket_generated_at' => now()
            ]);
        });

        return redirect()->back()->with('msg', 'Chaveamento gerado com sucesso para o formato ' . $tournament->tournament_type . '!');
    }

    /**
     * Rota de Teste: Vincula times ao torneio para testar o chaveamento.
     */
    public function attachTestTeams(Request $request, int $id)
    {
        $tournament = Tournament::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $teamIds = $request->input('team_ids');

        if (empty($teamIds)) {
            $needed = $tournament->max_participants;
            $teamIds = Team::limit($needed)->pluck('id')->toArray();
            
            if (count($teamIds) < $needed) {
                return redirect()->back()->withErrors([
                    'error' => "Você precisa ter pelo menos {$needed} times cadastrados no banco de dados global para preencher automaticamente."
                ]);
            }
        }

        $tournament->teams()->syncWithoutDetaching($teamIds);

        if ($tournament->event) {
            $totalInscritos = $tournament->event->tournaments()->withCount('teams')->get()->sum('teams_count');
            $tournament->event->update(['current_participants' => $totalInscritos]);
        }

        $count = $tournament->teams()->count();

        $tournament->current_participants = $count;

        return redirect()->back()->with('msg', "Times vinculados com sucesso! O torneio agora tem {$count}/{$tournament->max_participants} times.");
    }

    public function tournament_create()
    {

        $user = Auth::user();

        $events = Event::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('org.torneio-create', ['events' => $events]);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function tournament_store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|int',
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
            'tournament_type' => 'required|in:simples,duplo'
        ]);

        $organizer = Auth::user();

        $tournament = DB::transaction(function () use ($request, $organizer) {
            $newTournament = new Tournament();

            $newTournament->event_id = $request->event_id;
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
            $newTournament->tournament_type = $request->tournament_type;

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

        return redirect()->route('player.torneio.show', $tournament->id)
            ->with('success', 'Torneio criado com sucesso!');
    }

    public function tournament_edit(int $id)
    {
        $user = Auth::user();
        $events = Event::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $tournament = Tournament::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        return view('org.torneio-edit', ['tournament' => $tournament, 'events' => $events]);
    }

    public function tournament_update(Request $request, int $id)
    {
        $tournament = Tournament::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'event_id' => 'required|integer',
            'name' => 'required|string|max:255|unique:tournaments,name,' . $id,
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
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $tournament->event_id = $request->event_id;
        $tournament->name = $request->name;
        $tournament->description = $request->description;
        $tournament->category = $request->category;
        $tournament->max_participants = $request->max_participants;
        $tournament->entrance_fee = $request->entrance_fee;
        $tournament->awards = $request->awards;
        $tournament->start_date = $request->start_date;
        $tournament->end_date = $request->end_date;
        $tournament->start_time = $request->start_time;
        $tournament->end_time = $request->end_time;
        $tournament->entry_date = $request->entry_date;

        if ($request->hasFile('img') && $request->file('img')->isValid()) {

            if ($tournament->img) {
                $oldImagePath = public_path($tournament->img);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $requestImage = $request->img;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('/assets/tournaments/'), $imageName);
            $tournament->img = "/assets/tournaments/" . $imageName;
        }

        $tournament->save();

        return redirect()->route('player.torneio.show', $tournament->id)
            ->with('success', 'Torneio atualizado com sucesso!');
    }

    public function tournament_destroy(int $id)
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

    public function match_create(int $id)
    {
        $tournament = Tournament::where('id', $id)->with('teams')->firstOrFail();
        $teams = $tournament->teams;
        return view('org.partida-create', ['tournament' => $tournament, 'teams' => $teams]);
    }

    public function match_store(Request $request, int $id)
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

    public function match_view(int $id)
    {
        $match = TournamentMatch::with([
            'tournament',
            'teamA',
            'teamB',
            'player_Infos.player'
        ])->findOrFail($id);

        return view('org.partida-view', ['Match' => $match]);
    }

    public function match_destroy(int $id)
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

    public function match_edit(int $id)
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

    public function match_update(Request $request, int $id)
    {
        $request->validate([
            'score_a' => 'nullable|integer|min:0',
            'score_b' => 'nullable|integer|min:0',
            'map_id' => 'nullable|exists:maps,id',
            'winner_id' => 'nullable|exists:teams,id',
            'is_wo' => 'nullable|boolean',
            'stats' => 'nullable|array',
            'stats.*.kill' => 'nullable|integer|min:0',
            'stats.*.death' => 'nullable|integer|min:0',
            'stats.*.assistance' => 'nullable|integer|min:0',
            'stats.*.score' => 'nullable|integer|min:0',
            'stats.*.character' => 'nullable|integer|min:0',
        ]);

        $match = TournamentMatch::findOrFail($id);

        // Se o resultado já foi encerrado anteriormente, evita reprocessar vitórias
        if (!$match->winner_id && $request->winner_id) {
            $team = Team::with('users')->find($request->winner_id);
            if ($team && $team->users) {
                foreach ($team->users as $player) {
                    $player->increment('wins');
                }
            }

            // Define o status da partida com base na decisão do organizador
            $match_status = ($request->is_wo == '1') ? 'W.O.' : 'Finalizada';

            // Avançar o time (e, se for Upper Bracket de um torneio duplo, manda o perdedor pra Lower)
            $this->next($match, $request->winner_id);

        } else {
            // Mantém o status atual ou define como Em Andamento se apenas salvou dados parciais
            $match_status = $match->winner_id ? $match->match_status : 'Em Andamento';
        }

        // Atualização da Partida
        $match->update([
            'score_a' => $request->score_a,
            'score_b' => $request->score_b,
            'map_id' => $request->map_id,
            'winner_id' => $request->winner_id ?: $match->winner_id,
            'match_status' => $match_status,
        ]);

        // Atualização das estatísticas individuais dos jogadores
        if ($request->has('stats')) {
            foreach ($request->stats as $statId => $statData) {

                $updateData = [
                    'kill' => $statData['kill'],
                    'death' => $statData['death'],
                    'assistance' => $statData['assistance'],
                    'score' => $statData['score'],
                ];

                if (isset($statData['character'])) {
                    $updateData['character_id'] = $statData['character'];
                }

                PlayerInfos::where('id', $statId)
                    ->where('match_id', $match->id)
                    ->update($updateData);
            }
        }

        // Redireciona de volta para a visualização da partida usando sua rota nomeada do painel
        return redirect()->route('player.match.show', $match->id)->with('msg', 'Partida e estatísticas atualizadas com sucesso!');
    }

    private function next(TournamentMatch $match, int $winnerId)
    {
        // Se for a Grand Final o torneio acabou, não há para onde avançar.
        if ($match->bracket_type === 'grand_final') {
            return;
        }

        $proximaRodada = $match->round + 1;
        $proximaPosicao = (int) ceil($match->bracket_position / 2);

        // Determina se o time entra no Slot A ou Slot B do próximo jogo
        // Posição ímpar (1, 3, 5...) vira Time A. Posição par (2, 4, 6...) vira Time B.
        $slotProximoJogo = ($match->bracket_position % 2 !== 0) ? 'team_a_id' : 'team_b_id';

        // 1. Lógica para Chave Superior (Upper Bracket)
        if ($match->bracket_type === 'upper') {

            // Busca se existe a próxima partida na Upper
            $proximoJogo = TournamentMatch::where('tournament_id', $match->tournament_id)
                ->where('bracket_type', 'upper')
                ->where('round', $proximaRodada)
                ->where('bracket_position', $proximaPosicao)
                ->first();

            if ($proximoJogo) {
                $proximoJogo->update([
                    $slotProximoJogo => $winnerId
                ]);

                // Cria as estatísticas para os jogadores do time que avançou nessa nova partida
                $this->gerarSlotsEstatisticasProximoJogo($proximoJogo, $winnerId);
            } else {
                // Se não achou próximo jogo na Upper e o torneio for "duplo", ele vai para a Grand Final
                $grandFinal = TournamentMatch::where('tournament_id', $match->tournament_id)
                    ->where('bracket_type', 'grand_final')
                    ->first();

                if ($grandFinal) {
                    // O campeão da Upper costuma ser o Time A da Grand Final
                    $grandFinal->update(['team_a_id' => $winnerId]);
                    $this->gerarSlotsEstatisticasProximoJogo($grandFinal, $winnerId);
                }
            }

            // Em torneios de dupla eliminação, o time que perdeu na Upper
            // não é eliminado: ele cai para a posição correspondente na Lower Bracket.
            if ($match->tournament && $match->tournament->tournament_type === 'duplo') {
                $loserId = ($winnerId == $match->team_a_id) ? $match->team_b_id : $match->team_a_id;

                if ($loserId) {
                    $this->moverPerdedorParaLower($match, (int) $loserId);
                }
            }
        }

        // 2. Lógica para Chave Inferior (Lower Bracket)
        if ($match->bracket_type === 'lower') {

            // A Lower Bracket NÃO reduz o número de jogos a cada rodada.
            // Rodadas ímpares (1, 3, 5...) alimentam a rodada seguinte de forma "flat"
            // (mesma posição, pois a próxima rodada ainda vai receber novos perdedores
            // da Upper para completar os confrontos).
            // Rodadas pares (2, 4, 6...) são as que já receberam esses novos perdedores;
            // a rodada seguinte é de "consolidação" e reduz pela metade, igual à Upper.
            if ($match->round % 2 !== 0) {
                $proximaPosicaoLower = $match->bracket_position;
                $slotProximoJogoLower = 'team_a_id';
            } else {
                $proximaPosicaoLower = (int) ceil($match->bracket_position / 2);
                $slotProximoJogoLower = ($match->bracket_position % 2 !== 0) ? 'team_a_id' : 'team_b_id';
            }

            $proximoJogoLower = TournamentMatch::where('tournament_id', $match->tournament_id)
                ->where('bracket_type', 'lower')
                ->where('round', $proximaRodada)
                ->where('bracket_position', $proximaPosicaoLower)
                ->first();

            if ($proximoJogoLower) {
                $proximoJogoLower->update([
                    $slotProximoJogoLower => $winnerId
                ]);
                $this->gerarSlotsEstatisticasProximoJogo($proximoJogoLower, $winnerId);
            } else {
                // Se a Lower acabou, o vencedor vai para a Grand Final como Time B (Desafiante)
                $grandFinal = TournamentMatch::where('tournament_id', $match->tournament_id)
                    ->where('bracket_type', 'grand_final')
                    ->first();

                if ($grandFinal) {
                    $grandFinal->update(['team_b_id' => $winnerId]);
                    $this->gerarSlotsEstatisticasProximoJogo($grandFinal, $winnerId);
                }
            }
        }
    }

    /**
     * Envia o time que perdeu uma partida da Upper Bracket para a posição
     * correspondente da Lower Bracket (repescagem).
     *
     * Regra (derivada da forma como generateBracket() monta as rodadas da Lower):
     * - Perdedores da Rodada 1 da Upper caem juntos na Rodada 1 da Lower
     *   (dois perdedores de partidas adjacentes se enfrentam, mesma lógica de
     *   pareamento por posição usada na Upper).
     * - Perdedores das demais rodadas da Upper (round > 1) entram na Lower na
     *   rodada "par" correspondente (round 2*(rodadaUpper-1)), ocupando o Time B
     *   do confronto, enquanto o Time A é o vencedor que já avançou dentro da
     *   própria Lower Bracket, na mesma posição.
     */
    private function moverPerdedorParaLower(TournamentMatch $match, int $loserId)
    {
        $rodadaUpper = $match->round;
        $posicaoUpper = $match->bracket_position;

        if ($rodadaUpper === 1) {
            $rodadaLower = 1;
            $posicaoLower = (int) ceil($posicaoUpper / 2);
            $slot = ($posicaoUpper % 2 !== 0) ? 'team_a_id' : 'team_b_id';
        } else {
            $rodadaLower = 2 * ($rodadaUpper - 1);
            $posicaoLower = $posicaoUpper;
            $slot = 'team_b_id';
        }

        $jogoLower = TournamentMatch::where('tournament_id', $match->tournament_id)
            ->where('bracket_type', 'lower')
            ->where('round', $rodadaLower)
            ->where('bracket_position', $posicaoLower)
            ->first();

        if ($jogoLower) {
            $jogoLower->update([$slot => $loserId]);
            $this->gerarSlotsEstatisticasProximoJogo($jogoLower, $loserId);
        }
    }

    /**
     * Função auxiliar para já deixar os registros de PlayerInfos prontos para o próximo jogo
     */
    private function gerarSlotsEstatisticasProximoJogo(TournamentMatch $nextMatch, int $teamId)
    {
        $team = Team::with('users')->find($teamId);
        if ($team && $team->users) {
            foreach ($team->users as $player) {
                // Evita duplicar se rodar o update duas vezes
                PlayerInfos::firstOrCreate([
                    'match_id' => $nextMatch->id,
                    'user_id' => $player->id,
                    'team_id' => $teamId,
                ]);
            }
        }
    }

    // Events function
    public function event_create()
    {
        return view('org.evento-create');
    }

    public function event_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
            'type' => 'required|in:online,presencial,corporativo',
            'location' => 'required|string|max:255',
            'streaming_url' => 'nullable|url|max:255',
            'entrance_fee' => 'required|numeric|min:0',
            'entry_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $event = new Event();

        $event->user_id = Auth::id(); // Dono do evento (Organizador)
        $event->name = $request->name;
        $event->max_participants = $request->max_participants;
        $event->current_participants = 0;
        $event->type = $request->type;
        $event->location = $request->location;
        $event->streaming_url = $request->streaming_url;
        $event->entrance_fee = $request->entrance_fee;
        $event->entry_date = $request->entry_date;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->description = $request->description;

        // Upload da Imagem do Banner
        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            $requestImage = $request->img;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('/assets/events/'), $imageName);
            $event->img = "/assets/events/" . $imageName;
        }

        $event->save();

        return redirect()->route('org.dashboard')
            ->with('success', 'Evento publicado com sucesso!');
    }

    public function event_edit(int $id)
    {
        $event = Event::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        return view('org.evento-edit', compact('event'));
    }

    public function event_update(Request $request, int $id)
    {
        $event = Event::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
            'type' => 'required|in:online,presencial,corporativo',
            'location' => 'required|string|max:255',
            'streaming_url' => 'nullable|url|max:255',
            'entrance_fee' => 'required|numeric|min:0',
            'entry_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $event->name = $request->name;
        $event->max_participants = $request->max_participants;
        $event->type = $request->type;
        $event->location = $request->location;
        $event->streaming_url = $request->streaming_url;
        $event->entrance_fee = $request->entrance_fee;
        $event->entry_date = $request->entry_date;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->description = $request->description;

        if ($request->hasFile('img') && $request->file('img')->isValid()) {

            if ($event->img) {
                $oldImagePath = public_path($event->img);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $requestImage = $request->img;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('/assets/events/'), $imageName);
            $event->img = "/assets/events/" . $imageName;
        }

        $event->save();

        return redirect()->route('org.dashboard')
            ->with('success', 'Evento atualizado com sucesso!');
    }

    public function event_destroy(int $id)
    {
        $event = Event::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($event->img) {
            $imagePath = public_path($event->img);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $event->delete();

        return redirect()->route('org.dashboard')
            ->with('msg', 'Evento removido com sucesso!');
    }

    // Notificações

    public function notification_create()
    {
        $tournaments = Tournament::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        $events = Event::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        $users = User::all();

        return view('org.notification-create', compact(
            'users',
            'tournaments',
            'events',
        ));
    }

    public function notification_store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_type' => 'required|in:user,event,tournament',

            'user_id' => 'required_if:target_type,user|nullable|exists:users,id',
            'event_id' => 'required_if:target_type,event|nullable|exists:events,id',
            'tournament_id' => 'required_if:target_type,tournament|nullable|exists:tournaments,id',
        ]);

        $userIds = [];

        // Mapeamento dos destinatários com base na escolha do painel
        switch ($request->target_type) {
            case 'user':
                $userIds[] = $request->user_id;
                break;

            case 'event':
                $event = Event::findOrFail($request->event_id);
                $userIds = $event->users()->pluck('users.id')->toArray();
                break;

            case 'tournament':
                $tournament = Tournament::with('teams.users')->findOrFail($request->tournament_id);
                $userIds = $tournament->teams->flatMap(function ($team) {
                    return $team->users->pluck('id');
                })->unique()->toArray();
                break;
        }

        if (empty($userIds)) {
            return redirect()->back()->withInput()->withErrors([
                'target_type' => 'O grupo selecionado não possui nenhum usuário inscrito para receber a notificação.'
            ]);
        }

        // estrutura padrão dos dados fixos
        $notificationData = [
            'title' => $request->title,
            'message' => $request->message,
            'event_id' => $request->target_type === 'event' ? $request->event_id : null,
            'tournament_id' => $request->target_type === 'tournament' ? $request->tournament_id : null,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $insertBatch = [];
        foreach ($userIds as $id) {
            $row = $notificationData;
            $row['user_id'] = $id;
            $insertBatch[] = $row;
        }

        // (alta performance, evita múltiplos loops individuais)
        Inbox::insert($insertBatch);

        return redirect()
            ->route('org.dashboard')
            ->with('msg', 'Notificação enviada com sucesso para ' . count($userIds) . ' destinatário(s)!');
    }

}