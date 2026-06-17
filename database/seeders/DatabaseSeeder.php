<?php

namespace Database\Seeders;

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
    
        for ($x=0; $x < 1; $x++) { 
            
            // 1. Cria 16 times
            $teams = Team::factory()->count(16)->create();

            // 2. Cria 5 jogadores para cada time
            foreach ($teams as $team) {
                User::factory()->count(5)->create([
                    'team_id' => $team->id
                ]);
            }

            // 3. Cria o torneio
            $tournament = Tournament::factory()->create([
                'name'                 => 'Torneio '.$x,
                'category'             => 'valorant',
                'max_participants'     => '16',
                'current_participants' => 16,
                'status'               => 'Finalizado'
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

                TournamentMatch::factory()->create([
                    'tournament_id' => $tournament->id,
                    'team_a_id'     => $teamA->id,
                    'team_b_id'     => $teamB->id,
                    'winner_id'     => $winner->id,
                    'stage'         => 'Oitavas de Final',
                    'order_of_keys' => $i + 1,
                    'match_status'  => 'Finalizada',
                ]);

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

                TournamentMatch::factory()->create([
                    'tournament_id' => $tournament->id,
                    'team_a_id'     => $teamA->id,
                    'team_b_id'     => $teamB->id,
                    'winner_id'     => $winner->id,
                    'stage'         => 'Quartas de Final',
                    'order_of_keys' => $i + 1,
                    'match_status'  => 'Finalizada',
                ]);

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

                TournamentMatch::factory()->create([
                    'tournament_id' => $tournament->id,
                    'team_a_id'     => $teamA->id,
                    'team_b_id'     => $teamB->id,
                    'winner_id'     => $winner->id,
                    'stage'         => 'Semi Final',
                    'order_of_keys' => $i + 1,
                    'match_status'  => 'Finalizada',
                ]);

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
                'team_a_id'     => $teamA->id,
                'team_b_id'     => $teamB->id,
                'winner_id'     => $campeao->id,
                'stage'         => 'Final',
                'order_of_keys' => 1,
                'match_status'  => 'Finalizada',
            ]);
        }
    
    }
}