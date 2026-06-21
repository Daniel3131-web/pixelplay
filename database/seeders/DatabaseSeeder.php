<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Map;
use App\Models\PlayerInfos;
use App\Models\User;
use App\Models\Team;
use App\Models\Event;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Map::factory()->createAll();
        Character::factory()->createAll();

        // 1. Criar os Usuários base (O Organizador assume ID 1)
        User::factory()->create(['name' => 'Organizador', 'email' => 'organizador@gmail.com', 'role' => 'organizador', 'password' => bcrypt('1234')]);
        User::factory()->create(['name' => 'Player', 'email' => 'player@gmail.com', 'role' => 'player', 'password' => bcrypt('1234')]);
        


        // Criar times iniciais do ecossistema
        $teams = Team::factory()->count(16)->create();

        foreach ($teams as $team) {
            $players = User::factory()->count(rand(1,5))->create(['team_id' => $team->id]);
            $team->update(['leader_id' => $players->first()->id]);
        }

        // =================================================================
        // 2. CRIAÇÃO DOS EVENTOS (GUARDA-CHUVA)
        // =================================================================
        
        // Evento 1: Grande festival Online
        $eventoOnline = Event::create([
            'user_id'       => 1,
            'name'          => 'PixelPlay Arena Virtual 2026',
            'slug'          => 'pixelplay-arena-virtual-2026',
            'max_capacity'  => 2000,
            'type'          => 'online',
            'location'      => 'Discord Oficial da PixelPlay',
            'streaming_url' => 'https://twitch.tv/pixelplay',
            'entrance_fee'  => 0.00,
            'entry_date'    => now()->addDays(2),
            'start_date'    => now()->addDays(5),
            'end_date'      => now()->addDays(10),
            'start_time'    => '13:00:00',
            'end_time'      => '22:00:00',
            'description'   => 'A primeira grande call do ano! Vários torneios simultâneos com transmissão ao vivo.'
        ]);

        // Evento 2: Evento Presencial físico
        $eventoPresencial = Event::create([
            'user_id'       => 1,
            'name'          => 'PixelPlay Fest Curitiba 2026',
            'slug'          => 'pixelplay-fest-curitiba-2026',
            'max_capacity'  => 600,
            'type'          => 'presencial',
            'location'      => 'Expo Barigui, Curitiba - PR',
            'streaming_url' => 'https://twitch.tv/pixelplay_stage',
            'entrance_fee'  => 35.00,
            'entry_date'    => now()->addDays(15),
            'start_date'    => now()->addDays(20),
            'end_date'      => now()->addDays(22),
            'start_time'    => '09:00:00',
            'end_time'      => '19:00:00',
            'description'   => 'O reencontro do cenário paranaense de e-sports. Stands, arena freeplay e a grande decisão no palco.'
        ]);

        // =================================================================
        // 3. VINCULAR OS TORNEIOS AOS SEUS RESPECTIVOS EVENTOS
        // =================================================================
        
        // Passamos o ID do evento correspondente como o 4º parâmetro
        $this->createTournament('Torneio de Abertura (Finalizado)', 'valorant', 'Finalizado', $eventoOnline->id);
        $this->createTournament('Liga de Verão (Em andamento)', 'cs2', 'Em andamento', $eventoOnline->id);
        $this->createTournament('Campeonato de Inverno (Agendado)', 'mr', 'Agendado', $eventoPresencial->id);
    }

    // Método atualizado para receber o evento_id
    private function createTournament(string $name, string $category, string $status, int $eventId): void
    {
        $tournament = Tournament::factory()->create([
            'event_id'            => $eventId, // Vinculo direto com o evento pai
            'name'                 => $name, 
            'category'             => $category, 
            'max_participants'     => 16, // Convertido para inteiro conforme nova migration
            'current_participants' => ($status === 'Agendado') ? 0 : 16,
            'status'               => $status, 
            'user_id'              => 1,
            'streaming_url'        => 'https://twitch.tv/pixelplay_' . $category // Link dinâmico opcional do torneio
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
        // ------------------------------------------------
        $classificadosQuartas = [];

        for ($i = 0; $i < 8; $i++) {
            $teamA = $teams->get($i * 2);
            $teamB = $teams->get(($i * 2) + 1);
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
            $classificadosQuartas[] = $winner;
        }

        // ------------------------------------------------
        // QUARTAS DE FINAL — 4 partidas
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

        foreach ($playersA as $player) {
            PlayerInfos::factory()->create([
                'match_id' => $match->id,
                'team_id'  => $teamA->id,
                'user_id'  => $player->id,
            ]);
        }

        foreach ($playersB as $player) {
            PlayerInfos::factory()->create([
                'match_id' => $match->id,
                'team_id'  => $teamB->id,
                'user_id'  => $player->id,
            ]);
        }
    }
}