<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Map;
use App\Models\PlayerInfos;
use App\Models\User;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Limpa o banco antes de popular
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh');

        User::factory()->create(['name' => 'Organizador', 'email' => 'organizador@gmail.com', 'role' => 'organizador', 'password' => bcrypt('1234')]);
        User::factory()->create(['name' => 'Player', 'email' => 'player@gmail.com', 'role' => 'player', 'password' => bcrypt('1234')]);

        Map::factory()->createAll();
        Character::factory()->createAll();

        // $this->createTournament('Torneio de Abertura (Finalizado)', 'valorant', 'Finalizado');
        // $this->createTournament('Liga de Verão (Em andamento)', 'cs2', 'Em andamento');
        // $this->createTournament('Campeonato de Inverno (Agendado)', 'mr', 'Agendado');
    }

    private function createTournament(string $name, string $category, string $status): void
    {
        $tournament = Tournament::factory()->create([
            'name' => $name, 'category' => $category, 'max_participants' => '16',
            'current_participants' => ($status === 'Agendado') ? 0 : 16,
            'status' => $status, 'user_id' => 1
        ]);

        if ($status === 'Agendado') return;

        $teams = Team::factory()->count(16)->create();

        foreach ($teams as $team) {
            
            $tournament->teams()->attach($team->id);

            $players = User::factory()->count(5)->create(['team_id' => $team->id]);
            $team->update(['leader_id' => $players->first()->id]);
        }

        if ($status === 'Em andamento') {
            for ($i = 0; $i < 8; $i++) {
                TournamentMatch::factory()->create([
                    'tournament_id' => $tournament->id, 'team_a_id' => $teams->get($i * 2)->id,
                    'team_b_id' => $teams->get(($i * 2) + 1)->id, 'stage' => 'Oitavas de Final',
                    'match_status' => 'Em andamento'
                ]);
            }
            return;
        }

        // ------------------------------------------------
        // OITAVAS DE FINAL — 8 partidas
        // Sorteia vencedor e guarda os 8 classificados
        // ------------------------------------------------
        $classificadosQuartas = [];

        for ($i = 0; $i < 8; $i++) {
            $teamA = $teams->get($i * 2);
            $teamB = $teams->get(($i * 2) + 1);

            // Sorteia aleatoriamente o vencedor entre os dois times
            $winner = fake()->randomElement([$teamA, $teamB]);

            $match = TournamentMatch::factory()->create([
                'tournament_id' => $tournament->id,
                'team_a_id' => $teamA->id,
                'team_b_id' => $teamB->id,
                'winner_id' => $winner->id,
                'stage' => 'Oitavas de Final',
                'order_of_keys' => $i + 1,
                'match_status' => 'Finalizada',
            ]);

            $this->seedMatchStats($match, $teamA, $teamB);

            // Guarda o vencedor para usar nas quartas
            $classificadosQuartas[] = $winner;
        }

        // ------------------------------------------------
        // QUARTAS DE FINAL — 4 partidas
        // Usa os 8 vencedores das oitavas
        // ------------------------------------------------
        $classificadosSemis = [];

        for ($i = 0; $i < 4; $i++) {
            $teamA = $classificadosQuartas[$i * 2];
            $teamB = $classificadosQuartas[($i * 2) + 1];

            $winner = fake()->randomElement([$teamA, $teamB]);

            $match = TournamentMatch::factory()->create([
                'tournament_id' => $tournament->id,
                'team_a_id' => $teamA->id,
                'team_b_id' => $teamB->id,
                'winner_id' => $winner->id,
                'stage' => 'Quartas de Final',
                'order_of_keys' => $i + 1,
                'match_status' => 'Finalizada',
            ]);

            $this->seedMatchStats($match, $teamA, $teamB);

            $classificadosSemis[] = $winner;
        }

        // ------------------------------------------------
        // SEMIFINAIS — 2 partidas
        // Usa os 4 vencedores das quartas
        // ------------------------------------------------
        $classificadosFinal = [];

        for ($i = 0; $i < 2; $i++) {
            $teamA = $classificadosSemis[$i * 2];
            $teamB = $classificadosSemis[($i * 2) + 1];

            $winner = fake()->randomElement([$teamA, $teamB]);

            $match = TournamentMatch::factory()->create([
                'tournament_id' => $tournament->id,
                'team_a_id' => $teamA->id,
                'team_b_id' => $teamB->id,
                'winner_id' => $winner->id,
                'stage' => 'Semi Final',
                'order_of_keys' => $i + 1,
                'match_status' => 'Finalizada',
            ]);

            $this->seedMatchStats($match, $teamA, $teamB);

            $classificadosFinal[] = $winner;
        }

        // ------------------------------------------------
        // GRANDE FINAL — 1 partida
        // Usa os 2 vencedores das semis
        // ------------------------------------------------
        $teamA = $classificadosFinal[0];
        $teamB = $classificadosFinal[1];

        $campeao = fake()->randomElement([$teamA, $teamB]);

        TournamentMatch::factory()->create([
            'tournament_id' => $tournament->id,
            'team_a_id' => $teamA->id,
            'team_b_id' => $teamB->id,
            'winner_id' => $campeao->id,
            'stage' => 'Final',
            'order_of_keys' => 1,
            'match_status' => 'Finalizada',
        ]);

    }

    private function seedMatchStats(TournamentMatch $match, Team $teamA, Team $teamB): void
    {
        $playersA = User::where('team_id', $teamA->id)->get();
        $playersB = User::where('team_id', $teamB->id)->get();

        //  estatísticas do Time A
        foreach ($playersA as $player) {
            PlayerInfos::factory()->create([
                'match_id' => $match->id,
                'team_id' => $teamA->id,
                'user_id' => $player->id,
            ]);
        }

        // estatísticas do Time B
        foreach ($playersB as $player) {
            PlayerInfos::factory()->create([
                'match_id' => $match->id,
                'team_id' => $teamB->id,
                'user_id' => $player->id,
            ]);
        }
    }
}