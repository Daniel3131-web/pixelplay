<?php

namespace Database\Factories;

use App\Models\TournamentMatch;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\Map;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TournamentMatch>
 */
class TournamentMatchFactory extends Factory
{
    protected $model = TournamentMatch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            // Cria um torneio ou pega o ID de um gerado dinamicamente
            'tournament_id' => Tournament::factory(),

            'map_id' => function (array $attributes) {
                $tournamentId = $attributes['tournament_id'] instanceof \Illuminate\Database\Eloquent\Model
                    ? $attributes['tournament_id']->id
                    : $attributes['tournament_id'];

                $tournament = Tournament::find($tournamentId);

                $map = Map::where('category', $tournament->category)->inRandomOrder()->first();

                return $map ? $map->id : Map::factory()->create(['category' => $tournament->category])->id;
            },

            'team_a_id' => function () {
                return Team::inRandomOrder()->first()?->id ?? Team::factory()->create()->id;
            },
            'team_b_id' => function (array $attributes) {
                $teamAId = is_numeric($attributes['team_a_id']) ? $attributes['team_a_id'] : null;

                $teamB = Team::when($teamAId, function ($query, $id) {
                    return $query->where('id', '!=', $id);
                })->inRandomOrder()->first();

                return $teamB?->id ?? Team::factory()->create()->id;
            },

            'winner_id' => null,

            'stage' => $this->faker->randomElement(['Oitavas de Final', 'Quartas de Final', 'Semi Final', 'Final']),
            'order_of_keys' => $this->faker->numberBetween(1, 10),
            'match_status' => $this->faker->randomElement(['Agendada', 'Em Andamento', 'Finalizada', 'W.O.']),
        ];
    }

    /**
     * Estado para quando a partida precisar iniciar AO VIVO (Live)
     */
    public function live(): static
    {
        return $this->state(fn(array $attributes) => [
            'match_status' => 'EmAndamento',
        ]);
    }

    /**
     * Estado para quando a partida já tiver um vencedor definido (Finished)
     */
    public function finished(): static
    {
        return $this->state(function (array $attributes) {

            $teamA = $attributes['team_a_id'] instanceof \Closure ? $attributes['team_a_id']() : $attributes['team_a_id'];
            $teamB = $attributes['team_b_id'] instanceof \Closure ? $attributes['team_b_id']() : $attributes['team_b_id'];

            return [
                'match_status' => 'Finalizada',
                'winner_id' => $this->faker->randomElement([$teamA, $teamB]),
            ];
        });
    }
}
