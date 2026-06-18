<?php

namespace Database\Seeders;

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
        // Cria uma conta organizadora
        User::factory()->count(1)->create([
            'name' => 'organizador',
            'email' => 'organizador@gmail.com',
            'password' => '1234',
            'role' => 'organizador',
        ]);
        // Cria uma conta de player
        User::factory()->count(1)->create([
            'name' => 'Player',
            'email' => 'player@gmail.com',
            'password' => '1234',
            'role' => 'player',
        ]);

        $this->createTournament('Torneio de Abertura', 'valorant');
        // $this->createTournament('Torneio de Abertura 2', 'cs2');
        // $this->createTournament('Torneio de Abertura 3', 'mr');
    }

    private function createTournament(string $name, string $category): void
    {


        // 1. Cria 16 times
        $teams = Team::factory()->count(16)->create();

        // 2. Cria 5 jogadores para cada time
        foreach ($teams as $team) {
            $players = User::factory()->count(5)->create([
                'team_id' => $team->id,
            ]);

            $leader = $players->first();

            $team->update([
                'leader_id' => $leader->id
            ]);
        }

        // 3. Cria o torneio
        $tournament = Tournament::factory()->create([
            'name' => $name,
            'category' => $category,
            'max_participants' => '16',
            'current_participants' => 16,
            'status' => 'Finalizado',
            'user_id' => 1
        ]);

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