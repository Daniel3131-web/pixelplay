<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Map;
use App\Models\User;
use App\Models\Team;
use App\Models\Event;
use App\Models\Tournament;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Collection;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    // --- Configurações do Evento Principal ---
    private const MAIN_STATUS_WEIGHTS = [
        'confirmado' => 85,
        'cancelado'  => 10,
        'ausente'    => 5,
    ];

    // --- Configurações da Massa de Testes (Dashboard/Relatórios) ---
    private const TOTAL_DEMO_EVENTS = 1;
    private const PLAYER_POOL_SIZE = 80;
    private const DEMO_STATUS_WEIGHTS = [
        'confirmado' => 75,
        'cancelado'  => 15,
        'ausente'    => 10,
    ];

    // --- Pool fixo de times reaproveitado em TODOS os torneios ---
    private const TEAM_POOL_SIZE = 16;

    public function run(): void
    {
        // -----------------------------------------------------------------
        // 1. INICIALIZAÇÃO DE GLOBAIS E USUÁRIOS ADMINISTRATIVOS
        // -----------------------------------------------------------------
        Map::factory()->createAll();
        Character::factory()->createAll();

        // O Organizador assume ID 1 se o banco estiver limpo
        $organizer = User::where('role', 'organizador')->first()
            ?? User::factory()->create([
                'name' => 'Organizador', 
                'email' => 'organizador@gmail.com', 
                'role' => 'organizador', 
                'password' => bcrypt('12345678')
            ]);

        User::factory()->create([
            'name' => 'Player', 
            'email' => 'player@gmail.com', 
            'role' => 'player', 
            'password' => bcrypt('12345678')
        ]);

        // Pool fixo de 16 times (os "16 primeiros times do banco"), reaproveitado
        // em TODOS os torneios abaixo — nada de gerar times novos por torneio.
        $teamPool = $this->createTeamPool(self::TEAM_POOL_SIZE);

        // -----------------------------------------------------------------
        // 2. PARTE 1: EVENTO PRINCIPAL PRESENCIAL
        // -----------------------------------------------------------------
        $eventoPresencial = Event::create([
            'user_id' => $organizer->id,
            'name' => 'PixelPlay Fest Curitiba 2026',
            'max_participants' => 600,
            'current_participants' => 0,
            'type' => 'presencial',
            'location' => 'Expo Barigui, Curitiba - PR',
            'streaming_url' => 'https://twitch.tv/pixelplay_stage',
            'entrance_fee' => 35.00,
            'entry_date' => now()->addDays(15),
            'start_date' => now()->addDays(20),
            'end_date' => now()->addDays(22),
            'start_time' => '09:00:00',
            'end_time' => '19:00:00',
            'description' => 'O reencontro do cenário paranaense de e-sports. Stands, arena freeplay e a grande decisão no palco.'
        ]);

        // Torneio de Dupla Eliminação — capacidade 16, mas só 15 times inscritos
        // (sobra 1 vaga de propósito pra você entrar e testar seu time)
        $this->createTournamentWithBracket(
            event: $eventoPresencial,
            name: 'Copa de Inverno - Dupla Eliminação',
            category: 'valorant',
            teams: $teamPool->take(15),
            maxParticipants: self::TEAM_POOL_SIZE,
            statusWeights: self::MAIN_STATUS_WEIGHTS,
            tournamentType: 'duplo'
        );

        // Torneio de Eliminação Simples — 16 times, capacidade cheia
        $this->createTournamentWithBracket(
            event: $eventoPresencial,
            name: 'Campeonato de Inverno',
            category: 'valorant',
            teams: $teamPool,
            maxParticipants: self::TEAM_POOL_SIZE,
            statusWeights: self::MAIN_STATUS_WEIGHTS,
            tournamentType: 'simples'
        );

        // -----------------------------------------------------------------
        // 3. PARTE 2: GERAR DADOS DE RELATÓRIO
        // -----------------------------------------------------------------
        $playerPool = User::factory()->count(self::PLAYER_POOL_SIZE)->create(['role' => 'player']);

        $demoEvents = Event::factory()
            ->count(self::TOTAL_DEMO_EVENTS)
            ->create(['user_id' => $organizer->id]);

        foreach ($demoEvents as $index => $event) {
            $this->seedEventRegistrations($event, $playerPool);

            // Torneio demo, também usando o mesmo pool fixo de 16 times
            $this->createTournamentWithBracket(
                event: $event,
                name: 'Torneio Demo Valorant ' . ($index + 1),
                category: 'valorant', 
                teams: $teamPool,
                maxParticipants: self::TEAM_POOL_SIZE,
                statusWeights: self::DEMO_STATUS_WEIGHTS
            );
        }
    }

    /**
     * Cria (uma única vez) o pool fixo de times reaproveitado em todos os torneios,
     * simulando "os N primeiros times do banco de dados".
     */
    private function createTeamPool(int $size): Collection
    {
        $teams = Team::factory()->count($size)->create();

        foreach ($teams as $team) {
            $players = User::factory()->count(5)->create(['team_id' => $team->id]);
            $team->update(['leader_id' => $players->first()->id]);
        }

        // Reconsulta ordenado por id pra garantir "os N primeiros times do banco"
        return Team::orderBy('id')->take($size)->get();
    }

    /**
     * Cria o torneio e vincula os times recebidos, sem gerar partidas.
     */
    private function createTournamentWithBracket(
        Event $event, 
        string $name, 
        string $category, 
        Collection $teams,
        int $maxParticipants,
        array $statusWeights,
        string $tournamentType = 'simples' // 'simples' ou 'duplo'
    ): void {
        $tournament = Tournament::factory()->create([
            'event_id' => $event->id,
            'name' => $name,
            'category' => $category,
            'max_participants' => $maxParticipants,
            'current_participants' => $teams->count(),
            'user_id' => $event->user_id,
            'streaming_url' => 'https://twitch.tv/pixelplay_' . $category,
            'tournament_type' => $tournamentType,
            // Datas sempre no futuro, pra nenhum torneio nascer "finalizado"
            'entry_date' => now()->addDays(8)->format('Y-m-d'),
            'start_date' => now()->addDays(15)->format('Y-m-d'),
            'end_date' => now()->addDays(17)->format('Y-m-d'),
            'bracket_generated_at' => null // Nulo para o botão aparecer na interface
        ]);

        foreach ($teams as $team) {
            $tournament->teams()->attach($team->id, [
                'created_at' => now(),
                'status' => 'confirmado'
            ]);

            foreach ($team->users as $player) {
                $event->users()->syncWithoutDetaching([
                    $player->id => ['status' => $this->sortearStatus($statusWeights)],
                ]);
            }
        }

        $this->updateEventParticipantsCount($event);
    }

    private function seedEventRegistrations(Event $event, Collection $playerPool): void
    {
        $minimo = (int) ($playerPool->count() * 0.4);
        $amostra = $playerPool->random(random_int($minimo, $playerPool->count()));

        foreach ($amostra as $player) {
            $event->users()->syncWithoutDetaching([
                $player->id => ['status' => $this->sortearStatus(self::DEMO_STATUS_WEIGHTS)],
            ]);
        }

        $this->updateEventParticipantsCount($event);
    }

    private function updateEventParticipantsCount(Event $event): void
    {
        $confirmados = $event->users()
            ->wherePivot('status', 'confirmado')
            ->count();

        $event->update(['current_participants' => $confirmados]);
    }

    private function sortearStatus(array $weights): string
    {
        $sorteio = random_int(1, 100);
        $acumulado = 0;

        foreach ($weights as $status => $peso) {
            $acumulado += $peso;
            if ($sorteio <= $acumulado) {
                return $status;
            }
        }

        return 'confirmado';
    }
}